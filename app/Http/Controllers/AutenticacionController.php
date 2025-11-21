<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\ObjectId;

class AutenticacionController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function mostrarLogin()
    {
        return view('inicio');
    }

    /**
     * Procesar login
     */
    public function iniciarSesion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ser un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $correo = $request->input('email');
        $contraseña = $request->input('password');

        // Buscar usuario por correo
        $usuario = Usuarios::where('correo', $correo)->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        // Verificar contraseña
        if (!Hash::check($contraseña, $usuario->contraseña)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.'
            ], 401);
        }

        // Verificar que el usuario esté activo o pendiente (prestadores)
        if (isset($usuario->estado) && !in_array($usuario->estado, ['activo', 'pendiente'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta está inactiva. Contacta al administrador.'
            ], 403);
        }

        // Crear sesión
        session([
            'usuario_id' => $usuario->id_usuario,
            'usuario_nombre' => $usuario->nombre_completo,
            'usuario_correo' => $usuario->correo,
            'usuario_tipo' => $usuario->tipo_usuario,
            'fecha_registro' => $usuario->fecha_registro ?? now(),
            'autenticado' => true
        ]);

        // Si el usuario quiere recordar sesión
        if ($request->has('remember_me') && $request->input('remember_me')) {
            $request->session()->put('remember_me', true);
        }

        // Redirigir según el tipo de usuario
        $redirectUrl = $this->obtenerUrlRedireccion($usuario->tipo_usuario);

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',
            'redirect' => $redirectUrl,
            'usuario' => [
                'nombre' => $usuario->nombre_completo,
                'tipo' => $usuario->tipo_usuario
            ]
        ]);
    }

    /**
     * Procesar registro
     */
    public function registrar(Request $request)
    {
        // Validar datos básicos
        $tipoUsuario = $request->input('tipo_usuario', 'turista');
        
        $rules = [
            'nombre' => 'required|string|min:3|max:100',
            'correo' => 'required|email',
            'telefono' => 'nullable|string|max:20',
            'contraseña' => 'required|min:8',
            'confirmar' => 'required|same:contraseña',
            'tipo_usuario' => 'required|in:turista,prestador',
        ];

        $messages = [
            'nombre.required' => 'El nombre completo es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Debe ser un correo electrónico válido.',
            'contraseña.required' => 'La contraseña es obligatoria.',
            'contraseña.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'confirmar.required' => 'Debes confirmar tu contraseña.',
            'confirmar.same' => 'Las contraseñas no coinciden.',
            'tipo_usuario.required' => 'Debes seleccionar un tipo de usuario.',
            'tipo_usuario.in' => 'El tipo de usuario seleccionado no es válido.',
        ];

        // Campos adicionales para prestadores
        if ($tipoUsuario === 'prestador') {
            $rules = array_merge($rules, [
                'nombre_servicio' => 'required|string|min:5|max:200',
                'descripcion_servicio' => 'required|string|min:50|max:1000',
                'categoria_servicio' => 'required|in:naturaleza,cultura,gastronomia,aventura,relax,eventos',
                'direccion' => 'required|string|max:200',
                'ciudad' => 'required|string|max:100',
                'documento_identidad' => 'required|string|max:20',
                'tipo_documento' => 'required|in:cc,ce,passport,nit',
                'experiencia_anos' => 'nullable|integer|min:0|max:50',
            ]);

            $messages = array_merge($messages, [
                'nombre_servicio.required' => 'El nombre del servicio es obligatorio.',
                'nombre_servicio.min' => 'El nombre del servicio debe tener al menos 5 caracteres.',
                'descripcion_servicio.required' => 'La descripción del servicio es obligatoria.',
                'descripcion_servicio.min' => 'La descripción debe tener al menos 50 caracteres.',
                'categoria_servicio.required' => 'Debes seleccionar una categoría de servicio.',
                'direccion.required' => 'La dirección es obligatoria.',
                'ciudad.required' => 'La ciudad es obligatoria.',
                'documento_identidad.required' => 'El documento de identidad es obligatorio.',
                'tipo_documento.required' => 'Debes seleccionar el tipo de documento.',
            ]);
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Verificar si el correo ya existe
            $correoExistente = Usuarios::where('correo', $request->input('correo'))->first();
            if ($correoExistente) {
                return response()->json([
                    'success' => false,
                    'errors' => ['correo' => ['Este correo electrónico ya está registrado.']]
                ], 422);
            }

            // Generar ID único para el usuario
            $idUsuario = new ObjectId();

            // Crear nuevo usuario
            $tipoUsuario = $request->input('tipo_usuario', 'turista');
            
            $usuario = new Usuarios();
            $usuario->id_usuario = (string)$idUsuario;
            $usuario->nombre_completo = $request->input('nombre');
            $usuario->correo = $request->input('correo');
            $usuario->telefono = $request->input('telefono') ?? null;
            $usuario->contraseña = Hash::make($request->input('contraseña'));
            $usuario->tipo_usuario = $tipoUsuario;
            $usuario->fecha_registro = now();
            $usuario->estado = $tipoUsuario === 'prestador' ? 'pendiente' : 'activo'; // Prestadores requieren validación
            $usuario->token_verificacion = null;

            // Campos adicionales para prestadores
            if ($tipoUsuario === 'prestador') {
                $usuario->nombre_servicio = $request->input('nombre_servicio');
                $usuario->descripcion_servicio = $request->input('descripcion_servicio');
                $usuario->categoria_servicio = $request->input('categoria_servicio');
                $usuario->direccion = $request->input('direccion');
                $usuario->ciudad = $request->input('ciudad', 'Quibdó');
                $usuario->documento_identidad = $request->input('documento_identidad');
                $usuario->tipo_documento = $request->input('tipo_documento');
                $usuario->experiencia_anos = $request->input('experiencia_anos') ?? 0;
                $usuario->numero_cuenta = $request->input('numero_cuenta') ?? null;
                $usuario->banco = $request->input('banco') ?? null;
                $usuario->certificaciones = $request->input('certificaciones') ?? [];
                $usuario->horario_atencion = $request->input('horario_atencion') ?? null;
                $usuario->redes_sociales = $request->input('redes_sociales') ?? [];
            }

            $usuario->save();

            // Iniciar sesión automáticamente después del registro
            session([
                'usuario_id' => $usuario->id_usuario,
                'usuario_nombre' => $usuario->nombre_completo,
                'usuario_correo' => $usuario->correo,
                'usuario_tipo' => $usuario->tipo_usuario,
                'fecha_registro' => $usuario->fecha_registro ?? now(),
                'autenticado' => true
            ]);

            $redirectUrl = $this->obtenerUrlRedireccion($usuario->tipo_usuario);
            $mensaje = $tipoUsuario === 'prestador' 
                ? 'Registro exitoso. Tu cuenta está pendiente de validación por un administrador.' 
                : 'Registro exitoso. Bienvenido a ExploraQuibdó!';

            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'redirect' => $redirectUrl,
                'usuario' => [
                    'nombre' => $usuario->nombre_completo,
                    'tipo' => $usuario->tipo_usuario
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cerrar sesión
     */
    public function cerrarSesion(Request $request)
    {
        $request->session()->flush();

        return redirect('/')->with('success', 'Sesión cerrada correctamente.');
    }

    /**
     * Obtener URL de redirección según el tipo de usuario
     */
    private function obtenerUrlRedireccion($tipoUsuario)
    {
        switch ($tipoUsuario) {
            case 'administrador':
                return '/panel/administrador';
            case 'prestador':
                return '/panel/prestador';
            case 'turista':
            default:
                return '/panel/turista';
        }
    }
}


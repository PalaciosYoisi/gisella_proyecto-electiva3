<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use App\Models\Publicaciones;
use App\Models\Reportes;
use App\Models\Reseñas;
use App\Models\Reservas;
use App\Models\Favoritos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use MongoDB\BSON\ObjectId;

class AdministradorController extends Controller
{
    /**
     * Mostrar dashboard del administrador
     */
    public function dashboard()
    {
        // Verificar que el usuario sea administrador
        if (!session('autenticado') || session('usuario_tipo') !== 'administrador') {
            return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        $stats = [
            'usuarios_totales' => Usuarios::count(),
            'prestadores_activos' => Usuarios::where('tipo_usuario', 'prestador')->where('estado', 'activo')->count(),
            'prestadores_pendientes' => Usuarios::where('tipo_usuario', 'prestador')->where('estado', 'pendiente')->count(),
            'contenido_reportado' => Reportes::where('validado_por_admin', false)->count(),
            'publicaciones_totales' => Publicaciones::count(),
            'reservas_totales' => Reservas::count(),
        ];

        $actividad_reciente = $this->obtenerActividadReciente();

        return view('panel_administrador', compact('stats', 'actividad_reciente'));
    }

    /**
     * Obtener prestadores pendientes de validación
     */
    public function obtenerPrestadoresPendientes()
    {
        $prestadores = Usuarios::where('tipo_usuario', 'prestador')
            ->where('estado', 'pendiente')
            ->orderBy('fecha_registro', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $prestadores]);
    }

    /**
     * Validar prestador (aprobar o rechazar)
     */
    public function validarPrestador(Request $request, $id)
    {
        $request->validate([
            'accion' => 'required|in:aprobar,rechazar',
            'comentario' => 'nullable|string|max:500'
        ]);

        $prestador = Usuarios::where('id_usuario', $id)->first();

        if (!$prestador || $prestador->tipo_usuario !== 'prestador') {
            return response()->json(['success' => false, 'message' => 'Prestador no encontrado'], 404);
        }

        if ($request->accion === 'aprobar') {
            $prestador->estado = 'activo';
            $mensaje = 'Prestador aprobado exitosamente';
        } else {
            $prestador->estado = 'rechazado';
            $mensaje = 'Prestador rechazado';
        }

        $prestador->save();

        return response()->json(['success' => true, 'message' => $mensaje]);
    }

    /**
     * Obtener contenido reportado
     */
    public function obtenerContenidoReportado()
    {
        $reportes = Reportes::where('validado_por_admin', false)
            ->orderBy('fecha_reporte', 'desc')
            ->get();

        // Agregar información adicional si existe
        foreach ($reportes as $reporte) {
            if ($reporte->id_publicacion) {
                $publicacion = Publicaciones::where('id_publicacion', $reporte->id_publicacion)->first();
                $reporte->publicacion = $publicacion;
            }
            if ($reporte->id_reseña) {
                $reseña = Reseñas::where('id_reseña', $reporte->id_reseña)->first();
                $reporte->reseña = $reseña;
            }
            if ($reporte->id_usuario_reporter) {
                $usuario = Usuarios::where('id_usuario', $reporte->id_usuario_reporter)->first();
                $reporte->usuario_reporter = $usuario;
            }
        }

        return response()->json(['success' => true, 'data' => $reportes]);
    }

    /**
     * Moderar contenido reportado
     */
    public function moderarContenido(Request $request, $id)
    {
        $request->validate([
            'accion' => 'required|in:aprobar,eliminar,advertencia',
            'comentario' => 'nullable|string|max:500'
        ]);

        $reporte = Reportes::where('id_reporte', $id)->first();

        if (!$reporte) {
            return response()->json(['success' => false, 'message' => 'Reporte no encontrado'], 404);
        }

        $reporte->validado_por_admin = true;
        $reporte->id_admin = session('usuario_id');
        $reporte->comentario_admin = $request->comentario;
        $reporte->save();

        // Si se decide eliminar, marcar el contenido como eliminado
        if ($request->accion === 'eliminar') {
            if ($reporte->id_publicacion) {
                $publicacion = Publicaciones::where('id_publicacion', $reporte->id_publicacion)->first();
                if ($publicacion) {
                    $publicacion->estado_publicacion = 'eliminado';
                    $publicacion->save();
                }
            }
            if ($reporte->id_reseña) {
                $reseña = Reseñas::where('id_reseña', $reporte->id_reseña)->first();
                if ($reseña) {
                    $reseña->estado = 'eliminado';
                    $reseña->save();
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Contenido moderado exitosamente']);
    }

    /**
     * Obtener todos los usuarios
     */
    public function obtenerUsuarios(Request $request)
    {
        $tipo = $request->get('tipo', 'todos');
        $estado = $request->get('estado', 'todos');

        $query = Usuarios::query();

        if ($tipo !== 'todos') {
            $query->where('tipo_usuario', $tipo);
        }

        if ($estado !== 'todos') {
            $query->where('estado', $estado);
        }

        $usuarios = $query->orderBy('fecha_registro', 'desc')->get();

        return response()->json(['success' => true, 'data' => $usuarios]);
    }

    /**
     * Gestionar usuario (cambiar estado, tipo, etc.)
     */
    public function gestionarUsuario(Request $request, $id)
    {
        $request->validate([
            'accion' => 'required|in:activar,desactivar,cambiar_tipo,eliminar',
            'tipo_usuario' => 'required_if:accion,cambiar_tipo|in:turista,prestador,administrador'
        ]);

        $usuario = Usuarios::where('id_usuario', $id)->first();

        if (!$usuario) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
        }

        switch ($request->accion) {
            case 'activar':
                $usuario->estado = 'activo';
                break;
            case 'desactivar':
                $usuario->estado = 'inactivo';
                break;
            case 'cambiar_tipo':
                $usuario->tipo_usuario = $request->tipo_usuario;
                break;
            case 'eliminar':
                $usuario->delete();
                return response()->json(['success' => true, 'message' => 'Usuario eliminado']);
        }

        $usuario->save();

        return response()->json(['success' => true, 'message' => 'Usuario actualizado exitosamente']);
    }

    /**
     * Obtener todas las publicaciones
     */
    public function obtenerPublicaciones(Request $request)
    {
        $estado = $request->get('estado', 'todos');
        $busqueda = $request->get('busqueda');

        $query = Publicaciones::query();

        if ($estado !== 'todos') {
            $query->where('estado_publicacion', $estado);
        } else {
            // Si es 'todos', excluir solo las eliminadas
            $query->where('estado_publicacion', '!=', 'eliminado');
        }

        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('titulo_publicacion', 'like', "%{$busqueda}%")
                  ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        $publicaciones = $query->orderBy('fecha_creacion', 'desc')->get();

        // Optimizar: Obtener todos los IDs únicos de una vez
        $autorIds = $publicaciones->pluck('id_autor')->unique()->filter()->toArray();
        $publicacionIds = $publicaciones->pluck('id_publicacion')->toArray();

        // Obtener todos los autores en una consulta
        $autores = [];
        if (!empty($autorIds)) {
            $autoresData = Usuarios::whereIn('id_usuario', $autorIds)->get();
            foreach ($autoresData as $autor) {
                $autores[$autor->id_usuario] = [
                    'id' => $autor->id_usuario,
                    'nombre' => $autor->nombre_completo,
                    'correo' => $autor->correo,
                    'tipo' => $autor->tipo_usuario
                ];
            }
        }

        // Obtener todas las reservas en una consulta
        $reservasCounts = [];
        if (!empty($publicacionIds)) {
            $reservasData = Reservas::whereIn('id_experiencia', $publicacionIds)
                ->selectRaw('id_experiencia, COUNT(*) as total')
                ->groupBy('id_experiencia')
                ->get();
            foreach ($reservasData as $reserva) {
                $reservasCounts[$reserva->id_experiencia] = $reserva->total;
            }
        }

        // Obtener todas las reseñas en una consulta
        $calificaciones = [];
        if (!empty($publicacionIds)) {
            $reseñas = Reseñas::whereIn('id_publicacion', $publicacionIds)->get();
            foreach ($reseñas as $reseña) {
                if (!isset($calificaciones[$reseña->id_publicacion])) {
                    $calificaciones[$reseña->id_publicacion] = [];
                }
                $calificaciones[$reseña->id_publicacion][] = $reseña->calificacion;
            }
        }

        // Asignar datos a cada publicación
        foreach ($publicaciones as $publicacion) {
            $publicacion->autor = $autores[$publicacion->id_autor] ?? null;
            $publicacion->total_reservas = $reservasCounts[$publicacion->id_publicacion] ?? 0;
            
            if (isset($calificaciones[$publicacion->id_publicacion]) && count($calificaciones[$publicacion->id_publicacion]) > 0) {
                $publicacion->calificacion_promedio = round(array_sum($calificaciones[$publicacion->id_publicacion]) / count($calificaciones[$publicacion->id_publicacion]), 1);
                $publicacion->total_reseñas = count($calificaciones[$publicacion->id_publicacion]);
            } else {
                $publicacion->calificacion_promedio = 0;
                $publicacion->total_reseñas = 0;
            }
        }

        return response()->json(['success' => true, 'data' => $publicaciones]);
    }

    /**
     * Gestionar publicación
     */
    public function gestionarPublicacion(Request $request, $id)
    {
        $request->validate([
            'accion' => 'required|in:aprobar,rechazar,eliminar',
            'comentario' => 'nullable|string|max:500'
        ]);

        $publicacion = Publicaciones::where('id_publicacion', $id)->first();

        if (!$publicacion) {
            return response()->json(['success' => false, 'message' => 'Publicación no encontrada'], 404);
        }

        switch ($request->accion) {
            case 'aprobar':
                $publicacion->estado_publicacion = 'activo';
                break;
            case 'rechazar':
                $publicacion->estado_publicacion = 'rechazado';
                break;
            case 'eliminar':
                $publicacion->estado_publicacion = 'eliminado';
                break;
        }

        $publicacion->save();

        return response()->json(['success' => true, 'message' => 'Publicación actualizada exitosamente']);
    }

    /**
     * Generar reportes estadísticos
     */
    public function generarReportes()
    {
        try {
            // Usuarios por tipo - consultas optimizadas
            $usuariosPorTipo = [
                'turista' => Usuarios::where('tipo_usuario', 'turista')->count(),
                'prestador' => Usuarios::where('tipo_usuario', 'prestador')->count(),
                'administrador' => Usuarios::where('tipo_usuario', 'administrador')->count(),
            ];

            // Publicaciones por categoría - optimizado
            $publicaciones = Publicaciones::select('categoria')->get();
            $publicacionesPorCategoria = [];
            
            foreach ($publicaciones as $publicacion) {
                $categoria = $publicacion->categoria ?? 'Sin categoría';
                if (!isset($publicacionesPorCategoria[$categoria])) {
                    $publicacionesPorCategoria[$categoria] = 0;
                }
                $publicacionesPorCategoria[$categoria]++;
            }

            // Reservas totales
            $reservasTotales = Reservas::count();
            
            // Favoritos totales
            $favoritosTotales = Favoritos::count();
            
            // Publicaciones activas
            $publicacionesActivas = Publicaciones::where('estado_publicacion', 'activo')->count();

            $reportes = [
                'usuarios_por_tipo' => $usuariosPorTipo,
                'publicaciones_por_categoria' => $publicacionesPorCategoria,
                'reservas_totales' => $reservasTotales,
                'favoritos_totales' => $favoritosTotales,
                'publicaciones_activas' => $publicacionesActivas
            ];

            return response()->json(['success' => true, 'data' => $reportes]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error al generar reportes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar nuevo administrador
     */
    public function registrarAdministrador(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'contraseña' => 'required|min:8',
            'telefono' => 'nullable|string|max:20'
        ]);

        $idUsuario = new ObjectId();

        $admin = new Usuarios();
        $admin->id_usuario = (string)$idUsuario;
        $admin->nombre_completo = $request->nombre;
        $admin->correo = $request->correo;
        $admin->contraseña = Hash::make($request->contraseña);
        $admin->telefono = $request->telefono ?? null;
        $admin->tipo_usuario = 'administrador';
        $admin->fecha_registro = now();
        $admin->estado = 'activo';
        $admin->token_verificacion = null;

        $admin->save();

        return response()->json(['success' => true, 'message' => 'Administrador registrado exitosamente']);
    }

    /**
     * Obtener actividad reciente
     */
    private function obtenerActividadReciente()
    {
        $actividad = [];

        // Últimos prestadores registrados
        $prestadores = Usuarios::where('tipo_usuario', 'prestador')
            ->orderBy('fecha_registro', 'desc')
            ->limit(5)
            ->get();

        foreach ($prestadores as $prestador) {
            $actividad[] = [
                'tipo' => 'prestador_registrado',
                'mensaje' => $prestador->nombre_completo . ' se registró como prestador',
                'fecha' => $prestador->fecha_registro,
                'estado' => $prestador->estado
            ];
        }

        // Últimos reportes
        $reportes = Reportes::where('validado_por_admin', false)
            ->orderBy('fecha_reporte', 'desc')
            ->limit(5)
            ->get();

        foreach ($reportes as $reporte) {
            $actividad[] = [
                'tipo' => 'contenido_reportado',
                'mensaje' => 'Contenido reportado - ' . $reporte->motivo_reporte,
                'fecha' => $reporte->fecha_reporte
            ];
        }

        // Ordenar por fecha
        usort($actividad, function($a, $b) {
            return strtotime($b['fecha']) - strtotime($a['fecha']);
        });

        return array_slice($actividad, 0, 10);
    }
}


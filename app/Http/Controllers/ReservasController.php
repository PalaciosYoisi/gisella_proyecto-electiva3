<?php

namespace App\Http\Controllers;

use App\Models\Reservas;
use App\Models\Publicaciones;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\ObjectId;

class ReservasController extends Controller
{
    /**
     * Obtener reservas del usuario actual
     */
    public function index(Request $request)
    {
        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para ver tus reservas'
            ], 401);
        }

        $tipoUsuario = session('usuario_tipo');
        $query = Reservas::query();

        if ($tipoUsuario === 'turista') {
            $query->where('id_usuario', $usuarioId);
        } elseif ($tipoUsuario === 'prestador') {
            // Obtener IDs de publicaciones del prestador
            $publicacionesIds = Publicaciones::where('id_autor', $usuarioId)
                ->pluck('id_publicacion')
                ->toArray();
            $query->whereIn('id_experiencia', $publicacionesIds);
        }

        $estado = $request->get('estado');
        if ($estado) {
            $query->where('estado_reserva', $estado);
        }

        $reservas = $query->orderBy('fecha_reserva', 'desc')->get();

        // Agregar información de la experiencia y usuario
        foreach ($reservas as $reserva) {
            $experiencia = Publicaciones::where('id_publicacion', $reserva->id_experiencia)->first();
            $reserva->experiencia = $experiencia ? [
                'id' => $experiencia->id_publicacion,
                'titulo' => $experiencia->titulo_publicacion,
                'imagen' => $experiencia->imagen_url,
                'precio' => $experiencia->precio_aproximado
            ] : null;

            if ($tipoUsuario === 'prestador') {
                $usuario = Usuarios::where('id_usuario', $reserva->id_usuario)->first();
                $reserva->usuario = $usuario ? [
                    'id' => $usuario->id_usuario,
                    'nombre' => $usuario->nombre_completo,
                    'correo' => $usuario->correo,
                    'telefono' => $usuario->telefono
                ] : null;
            }
        }

        return response()->json(['success' => true, 'data' => $reservas]);
    }

    /**
     * Crear nueva reserva
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_experiencia' => 'required|string',
            'fecha_evento' => 'required|date|after_or_equal:today',
            'cantidad_personas' => 'required|integer|min:1|max:50',
            'detalles' => 'nullable|string|max:500',
            'contacto_emergencia' => 'nullable|string|max:100',
            'telefono_emergencia' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para realizar reservas'
            ], 401);
        }

        // Verificar que la experiencia existe y está activa o pendiente
        $experiencia = Publicaciones::where('id_publicacion', $request->id_experiencia)
            ->where(function($q) {
                $q->where('estado_publicacion', 'activo')
                  ->orWhere('estado_publicacion', 'pendiente');
            })
            ->first();

        if (!$experiencia) {
            return response()->json([
                'success' => false,
                'message' => 'La experiencia no está disponible'
            ], 404);
        }

        // Verificar capacidad
        if ($experiencia->capacidad_maxima) {
            $reservasExistentes = Reservas::where('id_experiencia', $request->id_experiencia)
                ->where('fecha_evento', $request->fecha_evento)
                ->where('estado_reserva', '!=', 'cancelada')
                ->sum('cantidad_personas');

            if (($reservasExistentes + $request->cantidad_personas) > $experiencia->capacidad_maxima) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay disponibilidad para la fecha seleccionada'
                ], 400);
            }
        }

        // Crear reserva
        $reserva = new Reservas();
        $reserva->id_reserva = (string) new ObjectId();
        $reserva->id_experiencia = $request->id_experiencia;
        $reserva->id_usuario = $usuarioId;
        $reserva->fecha_reserva = now();
        $reserva->fecha_evento = $request->fecha_evento;
        $reserva->cantidad_personas = $request->cantidad_personas;
        $reserva->estado_reserva = 'pendiente';
        $reserva->detalles = $request->detalles;
        $reserva->contacto_emergencia = $request->contacto_emergencia;
        $reserva->telefono_emergencia = $request->telefono_emergencia;
        $reserva->precio_total = $experiencia->precio_aproximado * $request->cantidad_personas;

        $reserva->save();

        // Obtener información completa para la respuesta
        $reserva->experiencia = [
            'titulo' => $experiencia->titulo_publicacion,
            'imagen' => $experiencia->imagen_url
        ];

        return response()->json([
            'success' => true,
            'message' => 'Reserva creada exitosamente. El prestador la revisará pronto.',
            'data' => $reserva
        ]);
    }

    /**
     * Actualizar estado de reserva
     */
    public function update(Request $request, $id)
    {
        $reserva = Reservas::where('id_reserva', $id)->first();

        if (!$reserva) {
            return response()->json(['success' => false, 'message' => 'Reserva no encontrada'], 404);
        }

        $usuarioId = session('usuario_id');
        $tipoUsuario = session('usuario_tipo');

        // Verificar permisos
        $experiencia = Publicaciones::where('id_publicacion', $reserva->id_experiencia)->first();
        $puedeEditar = false;

        if ($tipoUsuario === 'administrador') {
            $puedeEditar = true;
        } elseif ($tipoUsuario === 'prestador' && $experiencia && $experiencia->id_autor === $usuarioId) {
            $puedeEditar = true;
        } elseif ($tipoUsuario === 'turista' && $reserva->id_usuario === $usuarioId) {
            // Los turistas solo pueden cancelar sus propias reservas
            if ($request->estado_reserva === 'cancelada') {
                $puedeEditar = true;
            }
        }

        if (!$puedeEditar) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para modificar esta reserva'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'estado_reserva' => 'required|in:pendiente,confirmada,cancelada,completada',
            'comentario' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $reserva->estado_reserva = $request->estado_reserva;
        if ($request->comentario) {
            $reserva->comentario_prestador = $request->comentario;
        }
        $reserva->save();

        return response()->json([
            'success' => true,
            'message' => 'Reserva actualizada exitosamente',
            'data' => $reserva
        ]);
    }

    /**
     * Cancelar reserva
     */
    public function cancelar($id)
    {
        $reserva = Reservas::where('id_reserva', $id)->first();

        if (!$reserva) {
            return response()->json(['success' => false, 'message' => 'Reserva no encontrada'], 404);
        }

        $usuarioId = session('usuario_id');
        if ($reserva->id_usuario !== $usuarioId) {
            return response()->json([
                'success' => false,
                'message' => 'Solo puedes cancelar tus propias reservas'
            ], 403);
        }

        if ($reserva->estado_reserva === 'cancelada') {
            return response()->json([
                'success' => false,
                'message' => 'Esta reserva ya está cancelada'
            ], 400);
        }

        if ($reserva->estado_reserva === 'completada') {
            return response()->json([
                'success' => false,
                'message' => 'No puedes cancelar una reserva completada'
            ], 400);
        }

        $reserva->estado_reserva = 'cancelada';
        $reserva->save();

        return response()->json([
            'success' => true,
            'message' => 'Reserva cancelada exitosamente'
        ]);
    }

    /**
     * Obtener estadísticas de reservas
     */
    public function estadisticas()
    {
        $usuarioId = session('usuario_id');
        $tipoUsuario = session('usuario_tipo');

        if ($tipoUsuario === 'prestador') {
            $publicacionesIds = Publicaciones::where('id_autor', $usuarioId)
                ->pluck('id_publicacion')
                ->toArray();
            
            $total = Reservas::whereIn('id_experiencia', $publicacionesIds)->count();
            $pendientes = Reservas::whereIn('id_experiencia', $publicacionesIds)
                ->where('estado_reserva', 'pendiente')->count();
            $confirmadas = Reservas::whereIn('id_experiencia', $publicacionesIds)
                ->where('estado_reserva', 'confirmada')->count();
            $completadas = Reservas::whereIn('id_experiencia', $publicacionesIds)
                ->where('estado_reserva', 'completada')->count();
        } else {
            $total = Reservas::where('id_usuario', $usuarioId)->count();
            $pendientes = Reservas::where('id_usuario', $usuarioId)
                ->where('estado_reserva', 'pendiente')->count();
            $confirmadas = Reservas::where('id_usuario', $usuarioId)
                ->where('estado_reserva', 'confirmada')->count();
            $completadas = Reservas::where('id_usuario', $usuarioId)
                ->where('estado_reserva', 'completada')->count();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'pendientes' => $pendientes,
                'confirmadas' => $confirmadas,
                'completadas' => $completadas
            ]
        ]);
    }
}


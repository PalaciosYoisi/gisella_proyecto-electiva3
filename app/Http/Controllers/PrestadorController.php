<?php

namespace App\Http\Controllers;

use App\Models\Publicaciones;
use App\Models\Reservas;
use App\Models\Usuarios;
use App\Models\Reseñas;
use App\Models\Estadistica;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;

class PrestadorController extends Controller
{
    /**
     * Mostrar panel de prestador
     */
    public function index()
    {
        $usuarioId = session('usuario_id');
        if (!$usuarioId || session('usuario_tipo') !== 'prestador') {
            return redirect('/')->with('error', 'Debes iniciar sesión como prestador');
        }

        $usuario = Usuarios::where('id_usuario', $usuarioId)->first();
        
        // Verificar estado
        if ($usuario->estado === 'pendiente') {
            return view('panel_prestador', [
                'mensaje_pendiente' => 'Tu cuenta está pendiente de validación por un administrador.'
            ]);
        }

        return view('panel_prestador');
    }

    /**
     * Obtener estadísticas del dashboard
     */
    public function estadisticas()
    {
        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        $publicacionesIds = Publicaciones::where('id_autor', $usuarioId)
            ->pluck('id_publicacion')
            ->toArray();

        $stats = [
            'total_publicaciones' => Publicaciones::where('id_autor', $usuarioId)->count(),
            'publicaciones_activas' => Publicaciones::where('id_autor', $usuarioId)
                ->where('estado_publicacion', 'activo')->count(),
            'total_reservas' => Reservas::whereIn('id_experiencia', $publicacionesIds)->count(),
            'reservas_pendientes' => Reservas::whereIn('id_experiencia', $publicacionesIds)
                ->where('estado_reserva', 'pendiente')->count(),
            'reservas_confirmadas' => Reservas::whereIn('id_experiencia', $publicacionesIds)
                ->where('estado_reserva', 'confirmada')->count(),
            'total_visitas' => Publicaciones::where('id_autor', $usuarioId)
                ->sum('cantidad_visitas'),
            'calificacion_promedio' => 0
        ];

        // Calcular calificación promedio
        $reseñas = Reseñas::whereIn('id_publicacion', $publicacionesIds)->get();
        if ($reseñas->count() > 0) {
            $stats['calificacion_promedio'] = round($reseñas->avg('calificacion'), 1);
        }

        return response()->json(['success' => true, 'data' => $stats]);
    }

    /**
     * Obtener publicaciones del prestador
     */
    public function publicaciones(Request $request)
    {
        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        $estado = $request->get('estado');
        $query = Publicaciones::where('id_autor', $usuarioId);

        if ($estado && $estado !== 'todos') {
            $query->where('estado_publicacion', $estado);
        } else {
            $query->where('estado_publicacion', '!=', 'eliminado');
        }

        $publicaciones = $query->orderBy('fecha_creacion', 'desc')->get();

        // Optimizar: Obtener estadísticas en consultas agrupadas
        $publicacionIds = $publicaciones->pluck('id_publicacion')->toArray();

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
     * Obtener métricas de publicaciones
     */
    public function metricas()
    {
        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        $publicaciones = Publicaciones::where('id_autor', $usuarioId)
            ->where('estado_publicacion', 'activo')
            ->get();

        $metricas = [];
        foreach ($publicaciones as $publicacion) {
            $reservas = Reservas::where('id_experiencia', $publicacion->id_publicacion)->get();
            $reseñas = Reseñas::where('id_publicacion', $publicacion->id_publicacion)->get();

            $metricas[] = [
                'id' => $publicacion->id_publicacion,
                'titulo' => $publicacion->titulo_publicacion,
                'visitas' => $publicacion->cantidad_visitas ?? 0,
                'reservas' => $reservas->count(),
                'calificacion' => $reseñas->count() > 0 ? round($reseñas->avg('calificacion'), 1) : 0,
                'ingresos_estimados' => $reservas->where('estado_reserva', '!=', 'cancelada')->sum('precio_total')
            ];
        }

        return response()->json(['success' => true, 'data' => $metricas]);
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Publicaciones;
use App\Models\Usuarios;
use App\Models\Reservas;
use App\Models\Reseñas;
use App\Models\Favoritos;
use Illuminate\Http\Request;

class ExperienciaController extends Controller
{
    /**
     * Mostrar detalle de experiencia
     * Accesible para todos los usuarios (público)
     */
    public function show($id)
    {
        // Verificar si es admin para permitir ver publicaciones inactivas
        $esAdmin = session('usuario_tipo') === 'administrador';
        
        $query = Publicaciones::where('id_publicacion', $id);
        
        // Mostrar activas y pendientes a todos (las pendientes se publican automáticamente)
        // Solo los admin pueden ver eliminadas y rechazadas
        if (!$esAdmin) {
            $query->where(function($q) {
                $q->where('estado_publicacion', 'activo')
                  ->orWhere('estado_publicacion', 'pendiente');
            });
        }
        
        $publicacion = $query->first();

        if (!$publicacion) {
            return redirect('/')->with('error', 'Experiencia no encontrada o no disponible');
        }

        // Incrementar visitas solo si está activa
        if ($publicacion->estado_publicacion === 'activo') {
            $publicacion->cantidad_visitas = ($publicacion->cantidad_visitas ?? 0) + 1;
            $publicacion->save();
        }

        // Optimizar: Obtener autor en una sola consulta
        $autor = Usuarios::where('id_usuario', $publicacion->id_autor)->first();
        
        // Optimizar: Obtener todas las reseñas con usuarios en una consulta
        // MongoDB puede usar fecha_reseña o fecha_comentario
        $reseñas = Reseñas::where('id_publicacion', $id)->get();
        
        // Ordenar por fecha (manejar ambos campos posibles)
        $reseñas = $reseñas->sortByDesc(function($reseña) {
            return $reseña->fecha_reseña ?? $reseña->fecha_comentario ?? now();
        })->values();

        // Obtener IDs de usuarios únicos para evitar consultas repetidas
        $usuarioIds = $reseñas->pluck('id_usuario')->unique()->filter()->toArray();
        
        // Obtener todos los usuarios de una vez
        $usuarios = [];
        if (!empty($usuarioIds)) {
            $usuariosData = Usuarios::whereIn('id_usuario', $usuarioIds)->get();
            foreach ($usuariosData as $usuario) {
                $usuarios[$usuario->id_usuario] = [
                    'nombre' => $usuario->nombre_completo,
                    'foto' => $usuario->foto_perfil ?? null
                ];
            }
        }

        // Asignar usuarios a reseñas
        foreach ($reseñas as $reseña) {
            $reseña->usuario = $usuarios[$reseña->id_usuario] ?? [
                'nombre' => 'Usuario Anónimo',
                'foto' => null
            ];
        }

        // Optimizar: Calcular estadísticas en una sola consulta
        $total_reservas = Reservas::where('id_experiencia', $id)->count();
        
        // Calcular calificación promedio de forma optimizada
        $calificacion_promedio = 0;
        if ($reseñas->count() > 0) {
            $calificacion_promedio = round($reseñas->avg('calificacion'), 1);
        }

        // Verificar si está en favoritos (si el usuario está autenticado)
        $es_favorito = false;
        $usuarioId = session('usuario_id');
        if ($usuarioId) {
            $es_favorito = Favoritos::where('id_usuario', $usuarioId)
                ->where('id_publicacion', $id)
                ->exists();
        }

        return view('experiencia-detalle', compact(
            'publicacion',
            'autor',
            'reseñas',
            'total_reservas',
            'calificacion_promedio',
            'es_favorito'
        ));
    }
}


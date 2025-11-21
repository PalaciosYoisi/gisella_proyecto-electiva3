<?php

namespace App\Http\Controllers;

use App\Models\Publicaciones;
use App\Models\Reservas;
use App\Models\Favoritos;
use App\Models\Reseñas;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;

class TuristaController extends Controller
{
    /**
     * Mostrar panel de turista
     */
    public function index()
    {
        $usuarioId = session('usuario_id');
        if (!$usuarioId || session('usuario_tipo') !== 'turista') {
            return redirect('/')->with('error', 'Debes iniciar sesión como turista');
        }

        return view('panel_turista');
    }

    /**
     * Obtener experiencias para explorar
     */
    public function explorar(Request $request)
    {
        $categoria = $request->get('categoria');
        $busqueda = $request->get('busqueda');
        $precioMin = $request->get('precio_min');
        $precioMax = $request->get('precio_max');

        $query = Publicaciones::where('estado_publicacion', 'activo');

        if ($categoria) {
            $query->where('categoria', $categoria);
        }

        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('titulo_publicacion', 'like', "%{$busqueda}%")
                  ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        if ($precioMin) {
            $query->where('precio_aproximado', '>=', (float)$precioMin);
        }

        if ($precioMax) {
            $query->where('precio_aproximado', '<=', (float)$precioMax);
        }

        $publicaciones = $query->orderBy('fecha_creacion', 'desc')->get();

        // Agregar información adicional
        $usuarioId = session('usuario_id');
        foreach ($publicaciones as $publicacion) {
            $autor = Usuarios::where('id_usuario', $publicacion->id_autor)->first();
            $publicacion->autor = $autor ? [
                'nombre' => $autor->nombre_completo,
                'foto' => $autor->foto_perfil ?? null
            ] : null;

            // Verificar si está en favoritos
            $publicacion->es_favorito = Favoritos::where('id_usuario', $usuarioId)
                ->where('id_publicacion', $publicacion->id_publicacion)
                ->exists();

            // Calcular calificación
            $reseñas = Reseñas::where('id_publicacion', $publicacion->id_publicacion)->get();
            $publicacion->calificacion_promedio = $reseñas->count() > 0 
                ? round($reseñas->avg('calificacion'), 1) 
                : 0;
            $publicacion->total_reseñas = $reseñas->count();
        }

        return response()->json(['success' => true, 'data' => $publicaciones]);
    }

    /**
     * Agregar a favoritos
     */
    public function agregarFavorito(Request $request)
    {
        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión'
            ], 401);
        }

        $publicacionId = $request->input('id_publicacion');
        if (!$publicacionId) {
            return response()->json([
                'success' => false,
                'message' => 'ID de publicación requerido'
            ], 400);
        }

        // Verificar si ya existe
        $existe = Favoritos::where('id_usuario', $usuarioId)
            ->where('id_publicacion', $publicacionId)
            ->first();

        if ($existe) {
            return response()->json([
                'success' => false,
                'message' => 'Ya está en tus favoritos'
            ], 400);
        }

        $favorito = new Favoritos();
        $favorito->id_favorito = (string) new ObjectId();
        $favorito->id_usuario = $usuarioId;
        $favorito->id_publicacion = $publicacionId;
        $favorito->fecha_agregado = now();
        $favorito->save();

        return response()->json([
            'success' => true,
            'message' => 'Agregado a favoritos'
        ]);
    }

    /**
     * Eliminar de favoritos
     */
    public function eliminarFavorito($id)
    {
        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión'
            ], 401);
        }

        $favorito = Favoritos::where('id_favorito', $id)
            ->where('id_usuario', $usuarioId)
            ->first();

        if (!$favorito) {
            return response()->json([
                'success' => false,
                'message' => 'Favorito no encontrado'
            ], 404);
        }

        $favorito->delete();

        return response()->json([
            'success' => true,
            'message' => 'Eliminado de favoritos'
        ]);
    }

    /**
     * Obtener favoritos del usuario
     */
    public function favoritos()
    {
        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión'
            ], 401);
        }

        $favoritos = Favoritos::where('id_usuario', $usuarioId)
            ->orderBy('fecha_agregado', 'desc')
            ->get();

        foreach ($favoritos as $favorito) {
            $publicacion = Publicaciones::where('id_publicacion', $favorito->id_publicacion)->first();
            if ($publicacion) {
                $autor = Usuarios::where('id_usuario', $publicacion->id_autor)->first();
                $publicacion->autor = $autor ? [
                    'nombre' => $autor->nombre_completo
                ] : null;

                $reseñas = Reseñas::where('id_publicacion', $publicacion->id_publicacion)->get();
                $publicacion->calificacion_promedio = $reseñas->count() > 0 
                    ? round($reseñas->avg('calificacion'), 1) 
                    : 0;
            }
            $favorito->publicacion = $publicacion;
        }

        return response()->json(['success' => true, 'data' => $favoritos]);
    }

    /**
     * Obtener reseñas del usuario
     */
    public function reseñas()
    {
        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión'
            ], 401);
        }

        $reseñas = Reseñas::where('id_usuario', $usuarioId)
            ->orderBy('fecha_reseña', 'desc')
            ->get();

        foreach ($reseñas as $reseña) {
            $publicacion = Publicaciones::where('id_publicacion', $reseña->id_publicacion)->first();
            $reseña->publicacion = $publicacion ? [
                'id' => $publicacion->id_publicacion,
                'titulo' => $publicacion->titulo_publicacion,
                'imagen' => $publicacion->imagen_url
            ] : null;
        }

        return response()->json(['success' => true, 'data' => $reseñas]);
    }
}


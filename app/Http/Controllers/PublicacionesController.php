<?php

namespace App\Http\Controllers;

use App\Models\Publicaciones;
use App\Models\Usuarios;
use App\Models\Reservas;
use App\Models\Favoritos;
use App\Models\Reseñas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use MongoDB\BSON\ObjectId;

class PublicacionesController extends Controller
{
    /**
     * Obtener todas las publicaciones activas
     */
    public function index(Request $request)
    {
        $categoria = $request->get('categoria');
        $busqueda = $request->get('busqueda');
        $precioMin = $request->get('precio_min');
        $precioMax = $request->get('precio_max');
        $limit = $request->get('limit');

        // Mostrar publicaciones activas y pendientes (las pendientes se publican automáticamente)
        // Solo excluir las eliminadas y rechazadas
        $query = Publicaciones::where(function($q) {
            $q->where('estado_publicacion', 'activo')
              ->orWhere('estado_publicacion', 'pendiente');
        });

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

        $query->orderBy('fecha_creacion', 'desc');
        
        if ($limit) {
            $publicaciones = $query->limit((int)$limit)->get();
        } else {
            $publicaciones = $query->get();
        }

        // Optimizar: Obtener todos los IDs únicos de una vez
        $autorIds = $publicaciones->pluck('id_autor')->unique()->filter()->toArray();
        $publicacionIds = $publicaciones->pluck('id_publicacion')->toArray();

        // Obtener todos los autores en una consulta
        $autores = [];
        if (!empty($autorIds)) {
            $autoresData = Usuarios::whereIn('id_usuario', $autorIds)->get();
            foreach ($autoresData as $autor) {
                $autores[$autor->id_usuario] = [
                    'nombre' => $autor->nombre_completo,
                    'foto' => $autor->foto_perfil ?? null
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
     * Obtener una publicación específica
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
            return response()->json(['success' => false, 'message' => 'Publicación no encontrada o no disponible'], 404);
        }

        // Incrementar visitas solo si está activa
        if ($publicacion->estado_publicacion === 'activo') {
            $publicacion->cantidad_visitas = ($publicacion->cantidad_visitas ?? 0) + 1;
            $publicacion->save();
        }

        // Optimizar: Obtener información del autor en una consulta
        $autor = Usuarios::where('id_usuario', $publicacion->id_autor)->first();
        $publicacion->autor = $autor ? [
            'id' => $autor->id_usuario,
            'nombre' => $autor->nombre_completo,
            'foto' => $autor->foto_perfil ?? null,
            'telefono' => $autor->telefono ?? null,
            'descripcion' => $autor->descripcion_servicio ?? null
        ] : null;

        // Optimizar: Obtener reseñas con usuarios en consultas optimizadas
        // MongoDB puede usar fecha_reseña o fecha_comentario
        $reseñas = Reseñas::where('id_publicacion', $id)->get();
        
        // Ordenar por fecha (manejar ambos campos posibles)
        $reseñas = $reseñas->sortByDesc(function($reseña) {
            return $reseña->fecha_reseña ?? $reseña->fecha_comentario ?? now();
        })->values();

        // Obtener IDs de usuarios únicos
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

        $publicacion->reseñas = $reseñas;

        // Calcular estadísticas de forma optimizada
        $publicacion->total_reservas = Reservas::where('id_experiencia', $id)->count();
        $publicacion->calificacion_promedio = $reseñas->count() > 0 
            ? round($reseñas->avg('calificacion'), 1) 
            : 0;
        $publicacion->total_reseñas = $reseñas->count();

        return response()->json(['success' => true, 'data' => $publicacion]);
    }

    /**
     * Crear nueva publicación
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo_publicacion' => 'required|string|max:200',
            'descripcion' => 'required|string|min:50',
            'categoria' => 'required|in:naturaleza,cultura,gastronomia,aventura,relax,eventos',
            'ubicacion' => 'required|string',
            'precio_aproximado' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
            'video' => 'nullable|mimes:mp4,avi,mov,wmv,flv,webm|max:51200', // 50MB max
            'imagen_url' => 'nullable|string',
            'url_multimedia' => 'nullable|string',
            'fecha_evento' => 'nullable|date',
            'duracion_horas' => 'nullable|numeric|min:0.5',
            'capacidad_maxima' => 'nullable|integer|min:1',
            'incluye' => 'nullable|array',
            'no_incluye' => 'nullable|array',
            'requisitos' => 'nullable|array'
        ], [
            'titulo_publicacion.required' => 'El título de la experiencia es obligatorio.',
            'titulo_publicacion.max' => 'El título no puede exceder 200 caracteres.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.min' => 'La descripción debe tener al menos 50 caracteres.',
            'categoria.required' => 'Debes seleccionar una categoría.',
            'categoria.in' => 'La categoría seleccionada no es válida.',
            'ubicacion.required' => 'La ubicación es obligatoria.',
            'precio_aproximado.required' => 'El precio es obligatorio.',
            'precio_aproximado.numeric' => 'El precio debe ser un número válido.',
            'precio_aproximado.min' => 'El precio debe ser mayor o igual a 0.',
            'imagen.image' => 'El archivo de imagen debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe ser JPG, PNG, GIF o WEBP.',
            'imagen.max' => 'La imagen no puede exceder 10MB.',
            'video.mimes' => 'El video debe ser MP4, AVI, MOV, WMV, FLV o WEBM.',
            'video.max' => 'El video no puede exceder 50MB.',
            'imagen_url.url' => 'La URL de imagen debe ser válida.',
            'url_multimedia.url' => 'La URL de video debe ser válida.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación. Por favor, revisa los campos.',
                'errors' => $validator->errors()
            ], 422);
        }

        $usuarioId = session('usuario_id');
        if (!$usuarioId) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para crear publicaciones'
            ], 401);
        }

        // Manejar subida de imagen
        $imagenUrl = null;
        if ($request->hasFile('imagen')) {
            try {
                $imagen = $request->file('imagen');
                $nombreImagen = time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
                $rutaImagen = $imagen->storeAs('publicaciones/imagenes', $nombreImagen, 'public');
                // La ruta devuelta por storeAs con disco 'public' es relativa desde storage/app/public
                $imagenUrl = Storage::url($rutaImagen);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al subir la imagen: ' . $e->getMessage()
                ], 500);
            }
        } elseif ($request->filled('imagen_url') && filter_var($request->imagen_url, FILTER_VALIDATE_URL)) {
            $imagenUrl = $request->imagen_url;
        }

        // Manejar subida de video
        $videoUrl = null;
        if ($request->hasFile('video')) {
            try {
                $video = $request->file('video');
                $nombreVideo = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
                $rutaVideo = $video->storeAs('publicaciones/videos', $nombreVideo, 'public');
                // La ruta devuelta por storeAs con disco 'public' es relativa desde storage/app/public
                $videoUrl = Storage::url($rutaVideo);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al subir el video: ' . $e->getMessage()
                ], 500);
            }
        } elseif ($request->filled('url_multimedia') && filter_var($request->url_multimedia, FILTER_VALIDATE_URL)) {
            $videoUrl = $request->url_multimedia;
        }

        // Validar que haya al menos una imagen (archivo o URL)
        if (!$imagenUrl) {
            return response()->json([
                'success' => false,
                'message' => 'Debes proporcionar una imagen (sube un archivo o ingresa una URL válida)'
            ], 422);
        }

        $publicacion = new Publicaciones();
        $publicacion->id_publicacion = (string) new ObjectId();
        $publicacion->titulo_publicacion = $request->titulo_publicacion;
        $publicacion->descripcion = $request->descripcion;
        $publicacion->categoria = $request->categoria;
        $publicacion->ubicacion = $request->ubicacion;
        $publicacion->precio_aproximado = (float)$request->precio_aproximado;
        $publicacion->imagen_url = $imagenUrl;
        $publicacion->url_multimedia = $videoUrl;
        $publicacion->fecha_evento = $request->fecha_evento;
        $publicacion->duracion_horas = $request->duracion_horas ?? null;
        $publicacion->capacidad_maxima = $request->capacidad_maxima ?? null;
        $publicacion->incluye = $request->incluye ?? [];
        $publicacion->no_incluye = $request->no_incluye ?? [];
        $publicacion->requisitos = $request->requisitos ?? [];
        $publicacion->id_autor = $usuarioId;
        $publicacion->fecha_creacion = now();
        $publicacion->cantidad_visitas = 0;
        $publicacion->estado_publicacion = 'activo'; // Se publica automáticamente, el admin solo hace seguimiento
        $publicacion->tipo_contenido = 'experiencia';

        $publicacion->save();

        return response()->json([
            'success' => true,
            'message' => 'Publicación creada exitosamente y publicada automáticamente.',
            'data' => $publicacion
        ]);
    }

    /**
     * Actualizar publicación
     */
    public function update(Request $request, $id)
    {
        $publicacion = Publicaciones::where('id_publicacion', $id)->first();

        if (!$publicacion) {
            return response()->json(['success' => false, 'message' => 'Publicación no encontrada'], 404);
        }

        $usuarioId = session('usuario_id');
        if ($publicacion->id_autor !== $usuarioId && session('usuario_tipo') !== 'administrador') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para editar esta publicación'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'titulo_publicacion' => 'sometimes|required|string|max:200',
            'descripcion' => 'sometimes|required|string|min:50',
            'categoria' => 'sometimes|required|in:naturaleza,cultura,gastronomia,aventura,relax,eventos',
            'ubicacion' => 'sometimes|required|string',
            'precio_aproximado' => 'sometimes|required|numeric|min:0',
            'imagen_url' => 'nullable|url',
            'url_multimedia' => 'nullable|url',
            'fecha_evento' => 'nullable|date',
            'duracion_horas' => 'nullable|numeric|min:0.5',
            'capacidad_maxima' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $publicacion->fill($request->only([
            'titulo_publicacion', 'descripcion', 'categoria', 'ubicacion',
            'precio_aproximado', 'imagen_url', 'url_multimedia', 'fecha_evento',
            'duracion_horas', 'capacidad_maxima', 'incluye', 'no_incluye', 'requisitos'
        ]));

        // Mantener el estado actual (no cambiar a pendiente al actualizar)
        // El admin puede cambiar el estado manualmente si es necesario

        $publicacion->save();

        return response()->json([
            'success' => true,
            'message' => 'Publicación actualizada exitosamente',
            'data' => $publicacion
        ]);
    }

    /**
     * Eliminar publicación
     */
    public function destroy($id)
    {
        $publicacion = Publicaciones::where('id_publicacion', $id)->first();

        if (!$publicacion) {
            return response()->json(['success' => false, 'message' => 'Publicación no encontrada'], 404);
        }

        $usuarioId = session('usuario_id');
        if ($publicacion->id_autor !== $usuarioId && session('usuario_tipo') !== 'administrador') {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar esta publicación'
            ], 403);
        }

        // Cambiar estado a eliminado en lugar de borrar físicamente
        $publicacion->estado_publicacion = 'eliminado';
        $publicacion->save();

        return response()->json([
            'success' => true,
            'message' => 'Publicación eliminada exitosamente'
        ]);
    }

    /**
     * Obtener publicaciones de un prestador
     */
    public function getByPrestador($prestadorId)
    {
        $publicaciones = Publicaciones::where('id_autor', $prestadorId)
            ->where('estado_publicacion', '!=', 'eliminado')
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        foreach ($publicaciones as $publicacion) {
            $publicacion->total_reservas = Reservas::where('id_experiencia', $publicacion->id_publicacion)->count();
            $reseñas = Reseñas::where('id_publicacion', $publicacion->id_publicacion)->get();
            $publicacion->calificacion_promedio = $reseñas->count() > 0 
                ? round($reseñas->avg('calificacion'), 1) 
                : 0;
        }

        return response()->json(['success' => true, 'data' => $publicaciones]);
    }
}


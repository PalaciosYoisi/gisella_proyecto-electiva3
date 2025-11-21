<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Publicaciones extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'publicaciones';
    protected $primaryKey = 'id_publicacion';

    protected $fillable = [
        'id_publicacion',
        'titulo_publicacion',
        'descripcion',
        'categoria',
        'ubicacion',
        'fecha_evento',
        'imagen_url',
        'precio_aproximado',
        'estado_publicacion',
        'tipo_contenido',
        'url_multimedia',
        'id_autor',
        'fecha_creacion',
        'cantidad_visitas',
        'duracion_horas',
        'capacidad_maxima',
        'incluye',
        'no_incluye',
        'requisitos'
    ];
}

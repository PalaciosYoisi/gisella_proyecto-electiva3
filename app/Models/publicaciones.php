<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Publicaciones extends Model
{
    protected $table = 'publicaciones';
    protected $primaryKey = '_id';

    protected $fillable = [
        'id',
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
        'cantidad_visitas'
    ];
}

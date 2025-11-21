<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Reseñas extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reseñas';
    protected $primaryKey = 'id_reseña';

    protected $fillable = [
        'id_reseña',
        'calificacion',
        'comentario',
        'id_publicacion', // Cambiado de id_experiencia a id_publicacion para consistencia
        'id_usuario',
        'fecha_reseña', // Cambiado de fecha_comentario a fecha_reseña para consistencia
        'estado'
    ];
}

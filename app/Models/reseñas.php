<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Reseñas extends Model
{
    protected $table = 'reseñas';
    protected $primaryKey = '_id';

    protected $fillable = [
        'id',
        'calificacion',
        'comentario',
        'id_experiencia',
        'id_usuario',
        'fecha_comentario',
        'estado'
    ];
}

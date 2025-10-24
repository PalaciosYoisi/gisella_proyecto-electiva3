<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Favoritos extends Model
{
    protected $table = 'favoritos';
    protected $primaryKey = '_id';

    protected $fillable = [
        'id',
        'id_usuario',
        'id_publicacion',
        'fecha_agregado'
    ];
}

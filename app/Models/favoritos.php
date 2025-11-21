<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Favoritos extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'favoritos';
    protected $primaryKey = 'id_favorito';

    protected $fillable = [
        'id_favorito',
        'id_usuario',
        'id_publicacion',
        'fecha_agregado'
    ];
}

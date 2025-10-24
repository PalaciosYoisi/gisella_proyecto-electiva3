<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Estadistica extends Model
{
    protected $table = 'estadisticas';
    protected $primaryKey = '_id';

    protected $fillable = [
        'id',
        'id_publicacion',
        'fecha',
        'visitas',
        'reservas'
    ];
}

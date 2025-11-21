<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Estadistica extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'estadisticas';
    protected $primaryKey = 'id_estadistica';

    protected $fillable = [
        'id_estadistica',
        'id_publicacion',
        'fecha',
        'visitas',
        'reservas'
    ];
}

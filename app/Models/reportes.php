<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Reportes extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reportes';
    protected $primaryKey = 'id_reporte';

    protected $fillable = [
        'id_reporte',
        'id_publicacion',
        'id_reseña',
        'id_usuario_reporter',
        'motivo_reporte',
        'fecha_reporte',
        'validado_por_admin',
        'comentario_admin','id_admin'
    ];
}

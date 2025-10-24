<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Reportes extends Model
{
    protected $table = 'reportes';
    protected $primaryKey = '_id';

    protected $fillable = [
        'id',
        'id_publicacion',
        'id_reseña',
        'id_usuario_reporter',
        'motivo_reporte',
        'fecha_reporte',
        'validado_por_admin',
        'comentario_admin',
        'id_admin'
    ];
}

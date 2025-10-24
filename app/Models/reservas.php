<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Reservas extends Model
{
    protected $table = 'reservas';
    protected $primaryKey = '_id';

    protected $fillable = [
        'id',
        'id_experiencia',
        'id_usuario',
        'fecha_reserva',
        'fecha_evento',
        'estado_reserva',
        'cantidad_personas',
        'detalles'
    ];
}

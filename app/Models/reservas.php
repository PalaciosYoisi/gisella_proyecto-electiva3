<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Reservas extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reservas';
    protected $primaryKey = 'id_reserva';

    protected $fillable = [
        'id_reserva',
        'id_experiencia',
        'id_usuario',
        'fecha_reserva',
        'fecha_evento',
        'estado_reserva',
        'cantidad_personas',
        'detalles',
        'contacto_emergencia',
        'telefono_emergencia',
        'precio_total',
        'comentario_prestador'
    ];
}

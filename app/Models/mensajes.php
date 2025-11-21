<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Mensajes extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'mensajes';
    protected $primaryKey = 'id_mensaje';

    protected $fillable = [
        'id_mensaje',
        'id_remitente',
        'id_destinatario',
        'mensaje',
        'fecha_envio',
        'leido'
    ];
}

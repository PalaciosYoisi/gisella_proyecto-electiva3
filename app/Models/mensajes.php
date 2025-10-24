<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Mensajes extends Model
{
    protected $table = 'mensajes';
    protected $primaryKey = '_id';

    protected $fillable = [
        'id',
        'id_remitente',
        'id_destinatario',
        'mensaje',
        'fecha_envio',
        'leido'
    ];
}

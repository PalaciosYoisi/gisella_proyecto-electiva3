<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Usuarios extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = '_id';

    protected $fillable = [
        'id',
        'tipo_usuario',
        'nombre_completo',
        'correo',
        'contraseña',
        'telefono',
        'fecha_registro',
        'estado',
        'token_verificacion'
    ];
}

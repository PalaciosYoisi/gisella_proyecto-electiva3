<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Hash;

class Usuarios extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $connection = 'mongodb';
    protected $collection = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'id_usuario',
        'tipo_usuario',
        'nombre_completo',
        'correo',
        'contraseña',
        'telefono',
        'fecha_registro',
        'estado',
        'token_verificacion',
        // Campos adicionales para prestadores
        'nombre_servicio',
        'descripcion_servicio',
        'categoria_servicio',
        'direccion',
        'ciudad',
        'documento_identidad',
        'tipo_documento',
        'numero_cuenta',
        'banco',
        'foto_perfil',
        'redes_sociales',
        'horario_atencion',
        'experiencia_anos',
        'certificaciones'
    ];

    protected $hidden = [
        'contraseña',
        'token_verificacion'
    ];

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id_usuario';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->id_usuario;
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

    /**
     * Get the token value for the "remember me" session.
     */
    public function getRememberToken()
    {
        return null; // No usamos remember token por ahora
    }

    /**
     * Set the token value for the "remember me" session.
     */
    public function setRememberToken($value)
    {
        // No usamos remember token por ahora
    }

    /**
     * Get the column name for the "remember me" token.
     */
    public function getRememberTokenName()
    {
        return null;
    }
}

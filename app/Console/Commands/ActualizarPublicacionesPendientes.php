<?php

namespace App\Console\Commands;

use App\Models\Publicaciones;
use Illuminate\Console\Command;

class ActualizarPublicacionesPendientes extends Command
{
    protected $signature = 'publicaciones:activar-pendientes';
    protected $description = 'Actualiza todas las publicaciones pendientes a activas';

    public function handle()
    {
        $count = Publicaciones::where('estado_publicacion', 'pendiente')
            ->update(['estado_publicacion' => 'activo']);

        $this->info("Se actualizaron {$count} publicaciones de 'pendiente' a 'activo'.");
        return 0;
    }
}


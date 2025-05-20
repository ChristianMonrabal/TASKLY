<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Ruta para autorizar canales (usa auth)
        Broadcast::routes(['middleware' => ['auth']]);

        // Carga tus canales
        require base_path('routes/channels.php');
    }
}

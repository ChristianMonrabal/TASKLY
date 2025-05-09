<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('layouts.app', function($view) {
            if ($user = Auth::user()) {
                // Obtener las notificaciones (leídas y no leídas)
                $notificaciones = Notificacion::where('usuario_id', $user->id)
                    ->orderByDesc('fecha_creacion')
                    ->get(); // Cargamos todas las notificaciones
    
                // Contar las notificaciones no leídas
                $notiCount = Notificacion::where('usuario_id', $user->id)
                    ->where('leido', 0) // Solo las no leídas
                    ->count();
    
                // Pasar las variables a la vista
                $view->with('notificaciones', $notificaciones)
                     ->with('notiCount', $notiCount);
            }
        });
    }
}

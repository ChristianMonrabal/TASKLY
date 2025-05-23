<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        // Middleware inline para chequear perfil completo
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                $user = Auth::user();

                // Campos obligatorios
                $required = ['nombre', 'apellidos', 'email', 'dni'];
                $incomplete = false;
                foreach ($required as $field) {
                    if (empty($user->$field)) {
                        $incomplete = true;
                        break;
                    }
                }

                // Rutas permitidas aun con perfil incompleto
                $allowed = [
                    'signin.auth',       // formulario login
                    'signup.auth',       // formulario registro
                    'signup.store',      // acción registro
                    'login',             // POST login
                    'login.google',      // redirección Google
                    'auth.google',       // misma ruta alias
                    'auth/google',       // redirección Socialite
                    'auth/google/callback', // callback Google
                    'profile',           // ver perfil
                    'profile.update',    // actualizar perfil
                    'profile.datos-bancarios',        // ver datos bancarios
                    'profile.datos-bancarios.update', // actualizar datos bancarios
                    'logout',            // cerrar sesión
                ];

                $routeName = $request->route() 
                    ? $request->route()->getName() 
                    : null;

                if ($incomplete && ! in_array($routeName, $allowed)) {
                    return redirect()->route('profile')
                                     ->with('error', 'Por favor completa todos los campos obligatorios de tu perfil antes de continuar.');
                }
            }

            return $next($request);
        });
    }
}

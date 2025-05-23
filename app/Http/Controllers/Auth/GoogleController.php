<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $gUser = Socialite::driver('google')->user();

        // Intentamos cargar por google_id o email
        $user = User::where('google_id', $gUser->getId())
                    ->orWhere('email', $gUser->getEmail())
                    ->first();

        $isNew = false;
        if ($user) {
            if (! $user->google_id) {
                $user->google_id = $gUser->getId();
                $user->save();
            }
        } else {
            // Creamos uno nuevo
            $user = User::create([
                'nombre'       => $gUser->getName(),
                'apellidos'    => '',
                'email'        => $gUser->getEmail(),
                'google_id'    => $gUser->getId(),
                'password'     => bcrypt(Str::random(16)),
                'rol_id'       => 2,
                'activo'       => 'si',
                'dni'          => '',   // evita el error de campo no nulo
            ]);
            $isNew = true;
        }

        Auth::login($user, true);

        // Si acaban de registrarse con Google, que vayan a completar su perfil
        if ($isNew) {
            return redirect()->route('profile');
        }

        // Si ya existía:
        // - si es admin, al dashboard
        if ($user->rol_id === 1) {
            return redirect()->route('admin.dashboard.index');
        }

        // si no, al listado de trabajos
        return redirect()->route('trabajos.index');
    }
}

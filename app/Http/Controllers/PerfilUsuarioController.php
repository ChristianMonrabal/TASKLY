<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PerfilUsuarioController extends Controller
{
    public function perfilPorNombre($nombre)
    {
        // Suponiendo que los usuarios tienen un campo 'nombre' y 'apellido'
        $usuario = User::with(['rol', 'valoracionesRecibidas.trabajo'])
                    ->whereRaw("CONCAT(nombre, ' ', apellidos) = ?", [$nombre])
                    ->firstOrFail();

        return view('profile_user', compact('usuario'));
    }

    public function mostrar($nombre)
    {
        // Convertir el slug a nombre y apellido (opcional)
        $nombre = str_replace('-', ' ', $nombre);

        // Buscar el usuario por nombre completo
        $usuario = User::whereRaw("CONCAT(nombre, ' ', apellidos) = ?", [$nombre])->firstOrFail();

        $mediaValoraciones = $usuario->valoracionesRecibidas()->avg('puntuacion');

        return view('profile_user', compact('usuario', 'mediaValoraciones'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PerfilUsuarioController extends Controller
{
    public function perfil($id)
    {
        $usuario = User::with(['rol', 'valoracionesRecibidas.trabajo'])->findOrFail($id);
    
        return view('profile_user', compact('usuario'));
    }
    }

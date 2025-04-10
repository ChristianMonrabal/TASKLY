<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;


class ChatController extends Controller
{
    public function Vistachat()
    {
        $chats = Trabajo::with(['postulaciones', 'postulaciones.trabajador:id,nombre,apellidos'])
            ->where('cliente_id', Auth::user()->id)
            ->get();
        return view('chat.index', compact('chats'));
    }
}

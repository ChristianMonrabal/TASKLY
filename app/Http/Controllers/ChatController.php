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
        $user = Auth::user();
        $chats = Trabajo::with(['postulaciones', 'postulaciones.trabajador:id,nombre,apellidos,foto_perfil'])
            ->where('cliente_id', Auth::user()->id)
            ->get();
        return view('chat.index', compact('chats', 'user'));
    }

    public function cargamensajes(Request $request)
    {
        $trabajoId = $request->input('trabajo_id');
        $chat = Chat::with(['postulaciones', 'postulaciones.trabajador:id,nombre,apellidos,foto_perfil'])
            ->where('trabajo_id', $trabajoId)
            ->first();

        return response()->json($chat);
    }
}

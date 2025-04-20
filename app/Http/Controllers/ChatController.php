<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\User;



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
        $trabajo_id = $request->input('trabajo_id');
        $trabajador_id = $request->input('trabajador_id');
        $usuario = auth()->user()->id;

        $chats = Chat::with("emisor:id,nombre,apellidos,foto_perfil,created_at,updated_at")
            ->with("receptor:id,nombre,apellidos,foto_perfil")
            ->with("trabajo:id,titulo,descripcion")
            ->where('trabajo_id', $trabajo_id)
            ->where(function ($trabajador) use ($trabajador_id) {
                $trabajador->where('emisor', $trabajador_id)
                    ->orWhere('receptor', $trabajador_id);
            })
            ->where(function ($cliente) use ($usuario) {
                $cliente->where('emisor', $usuario)
                    ->orWhere('receptor', $usuario);
            })
            ->get();

        $user = User::where('id', $trabajador_id)->get();

        // $chat = Chat::with("trabajador:id,nombre,apellidos,foto_perfil")
        // ->with("trabajo:id,titulo,descripcion")
        // ->where('trabajo_id', $trabajo_id)
        // ->where(function ($query) use ($trabajador_id) {
        //     $query->where('trabajador_id', $trabajador_id)
        //         ->orWhere('trabajador_id', auth()->user()->id);
        // })
        // ->get();

        return response()->json(['chats' => $chats, 'user' => $user,]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Chat;
use App\Models\User;
use App\Models\Postulacion;



class ChatController extends Controller
{
    public function Vistachat($id = null)
    {
        $user = Auth::user();
        $trabajoSeleccionado = null;

        // Si se proporciona un ID de trabajo, lo cargamos para la vista
        if ($id !== null) {
            $trabajoSeleccionado = Trabajo::with(['cliente', 'postulaciones'])->findOrFail($id);
        }

        $postulacionesRecibidas = Postulacion::with(['trabajador:id,nombre,apellidos,foto_perfil', 'trabajo:id,titulo'])
            ->whereHas('trabajo', function ($query) use ($user) {
                $query->where('cliente_id', $user->id);
            })
            ->where('estado_id', 10) // Estado de postulacion recibida
            ->get()
            ->map(function ($postulacion) {
                $postulacion->tipo = 'recibida';
                return $postulacion;
            });

        $postulacionesRealizadas = Postulacion::with(['trabajo:id,titulo,cliente_id', 'trabajo.cliente:id,nombre,apellidos,foto_perfil'])
            ->where('trabajador_id', $user->id)
            ->where('estado_id', 10)
            ->get()
            ->map(function ($postulacion) {
                $postulacion->tipo = 'realizada';
                return $postulacion;
            });

        // Aquí se mezclan
        $chats = $postulacionesRecibidas->merge($postulacionesRealizadas);

        return view('chat.index', compact('chats', 'user', 'trabajoSeleccionado'));
    }

    public function cargamensajes(Request $request)
    {
        $trabajo_id = $request->input('trabajo_id');
        $trabajador_id = $request->input('trabajador_id');
        $usuario = Auth::user()->id;

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

    public function enviomensaje(Request $request)
    {
        $trabajo = $request->input('trabajo');
        $trabajador = $request->input('trabajador');
        $mensaje = $request->input('mensaje');

        $postulacion = new Chat();
        $postulacion->trabajo_id = $trabajo;
        $postulacion->emisor = Auth::user()->id;
        $postulacion->receptor = $trabajador;
        $postulacion->contenido = $mensaje;
        $postulacion->save();
    }
}

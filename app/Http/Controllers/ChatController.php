<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Trabajo;
use App\Models\Chat;
use App\Models\User;
use App\Models\Postulacion;
use App\Models\Notificacion;
use App\Events\NewNotificacion;

class ChatController extends Controller
{
    /**
     * Muestra la vista de chats. Si llega ?trabajador=ID, calcula selectedChatId
     */
    public function Vistachat(Request $request, $id = null)
    {
        $user = Auth::user();

        // 1) Trabajo seleccionado opcional
        $trabajoSeleccionado = $id
            ? Trabajo::with(['cliente','postulaciones'])->findOrFail($id)
            : null;

        // 2) Construir lista de “chats” (postulaciones)
        $recibidas = Postulacion::with([
                'trabajador:id,nombre,apellidos,foto_perfil',
                'trabajo:id,titulo'
            ])
            ->whereHas('trabajo', fn($q) => $q->where('cliente_id', $user->id))
            ->where('estado_id', 10)
            ->get()
            ->map(fn($p) => tap($p, fn($x) => $x->tipo = 'recibida'));

        $realizadas = Postulacion::with([
                'trabajo:id,titulo,cliente_id',
                'trabajo.cliente:id,nombre,apellidos,foto_perfil'
            ])
            ->where('trabajador_id', $user->id)
            ->where('estado_id', 10)
            ->get()
            ->map(fn($p) => tap($p, fn($x) => $x->tipo = 'realizada'));

        $chats = $recibidas->merge($realizadas);

        // 3) Parám. de notificación: otro usuario
        $selectedTrabajadorId = $request->query('trabajador');

        // 4) Buscar el “id” de la postulación (el chat) que coincida
        $selectedChatId = null;
        if ($id && $selectedTrabajadorId) {
            foreach ($chats as $p) {
                $otro = $p->tipo === 'recibida'
                    ? $p->trabajador_id
                    : $p->trabajo->cliente_id;
                if ($p->trabajo_id == $id && $otro == $selectedTrabajadorId) {
                    $selectedChatId = $p->id;
                    break;
                }
            }
        }

        return view('chat.index', compact(
            'chats',
            'user',
            'trabajoSeleccionado',
            'selectedTrabajadorId',
            'selectedChatId'
        ));
    }

    /**
     * Devuelve JSON con mensajes y user (colección).
     */
    public function cargamensajes(Request $request)
    {
        $trabajo_id    = $request->input('trabajo_id');
        $trabajador_id = $request->input('trabajador_id');
        $usuario_id    = Auth::id();

        $chats = Chat::with('emisor:id,nombre,apellidos,foto_perfil')
            ->with('receptor:id,nombre,apellidos,foto_perfil')
            ->with('trabajo:id,titulo,descripcion')
            ->where('trabajo_id', $trabajo_id)
            ->where(fn($q) => $q->where('emisor', $trabajador_id)
                                 ->orWhere('receptor', $trabajador_id))
            ->where(fn($q) => $q->where('emisor', $usuario_id)
                                 ->orWhere('receptor', $usuario_id))
            ->get();

        $user = User::where('id', $trabajador_id)->get();

        return response()->json([
            'chats' => $chats,
            'user'  => $user,
        ]);
    }

    /**
     * Guarda mensaje y emite notificación con URL al hilo concreto.
     */
    public function enviomensaje(Request $request)
    {
        $trabajo    = $request->input('trabajo');
        $trabajador = $request->input('trabajador');
        $mensaje    = $request->input('mensaje');

        // 1) Guardar mensaje
        Chat::create([
            'trabajo_id' => $trabajo,
            'emisor'     => Auth::id(),
            'receptor'   => $trabajador,
            'contenido'  => $mensaje,
        ]);

        // 2) Crear notificación
        $emisorNombre = Auth::user()->nombre;
        $notificacion = Notificacion::create([
            'usuario_id'    => $trabajador,
            'mensaje'       => "Tienes un nuevo mensaje de {$emisorNombre}",
            'fecha_creacion'=> now(),
            'leido'         => false,
            'tipo'          => 'mensaje',
            'trabajo_id'    => $trabajo,
        ]);

        // 3) Inyectar URL al hilo concreto
        $notificacion->url = route('vista.chat', [
            'id'        => $trabajo,
            'trabajador'=> $trabajador,
        ]);

        event(new NewNotificacion($notificacion));

        // 4) Para que tu JS siga funcionando igual
        return response()->json(['status' => 'success']);
    }
}

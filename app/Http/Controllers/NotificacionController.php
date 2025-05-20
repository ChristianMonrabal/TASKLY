<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewNotificacion;
use Illuminate\Support\Facades\DB;

class NotificacionController extends Controller
{
    public function index()
    {
        $notificaciones = Notificacion::where('usuario_id', Auth::id())
            ->orderByDesc('fecha_creacion')
            ->get();

        return view('notificaciones.index', compact('notificaciones'));
    }

    public function store(Request $request)
    {
        $userId  = Auth::id();
        $mensaje = $request->input('mensaje');
        $trabajo = $request->input('trabajo_id');

        $notificacion = Notificacion::create([
            'usuario_id'    => $userId,
            'mensaje'       => $mensaje,
            'fecha_creacion'=> now(),
            'leido'         => false,
            'trabajo_id'    => $trabajo,  // opcional
        ]);

        if (! $notificacion->trabajo_id) {
            $notificacion->setAttribute('url', route('vista.chat', $trabajo));
        }

        event(new NewNotificacion($notificacion));

        return response()->json(['status' => 'success']);
    }

    public function getNewNotifications(Request $request)
    {
        $userId = $request->user()->id;

        $notificaciones = Notificacion::where('usuario_id', $userId)
            ->where('leido', false)
            ->orderByDesc('fecha_creacion')
            ->get();

        return response()->json($notificaciones);
    }

    public function markAllAsRead()
    {
        DB::table('notificaciones')
            ->where('usuario_id', Auth::id())
            ->where('leido', false)
            ->update(['leido' => true]);

        return response()->json(['success' => true]);
    }

    public function markAsRead($id)
    {
        Notificacion::where('id', $id)->update(['leido' => true]);
        return response()->json(['success' => true]);
    }
}

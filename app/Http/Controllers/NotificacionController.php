<?php

namespace App\Http\Controllers;

use App\Events\NewNotificacion;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $notis = Notificacion::where('usuario_id', $userId)
                    ->orderByDesc('fecha_creacion')
                    ->get();
    
        // Pasar las notificaciones a la vista
        return view('layouts.app', ['notificaciones' => $notis]);
    }

    // Marcar todas como leídas
    public function markAllRead(Request $request)
    {
        // Obtener el usuario autenticado
        $userId = $request->user()->id;
    
        // Actualizar todas las notificaciones no leídas a leídas
        $updated = Notificacion::where('usuario_id', $userId)
            ->where('leido', 0)  // Solo las notificaciones no leídas
            ->update(['leido' => 1]);  // Marcamos como leídas
    
        // Si no se actualizan filas, logueamos una advertencia
        if ($updated === 0) {
            Log::warning('No se actualizaron las notificaciones como leídas para el usuario', ['userId' => $userId]);
        }
    
        return response()->json(['status' => 'success', 'updatedRows' => $updated]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulacion;
use App\Models\Trabajo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Chat;
use App\Models\Notificacion;
use App\Events\NewNotificacion;
use Illuminate\Support\Facades\Log;
class PostulacionController extends Controller
{
    /**
     * Aceptar un candidato para un trabajo
     * 
     * @param int $postulacionId El ID de la postulación a aceptar
     * @return \Illuminate\Http\JsonResponse
     */

     public function aceptar($postulacionId)
     {
         try {
             DB::beginTransaction();
     
             // Obtener la postulación y el trabajo
             $postulacion = Postulacion::findOrFail($postulacionId);
             $trabajo = $postulacion->trabajo;
     
             // Verificar que el usuario autenticado es el dueño del trabajo
             if (Auth::id() != $trabajo->cliente_id) {
                 return response()->json([
                     'success' => false,
                     'message' => 'No estás autorizado para realizar esta acción'
                 ], 403);
             }
     
             // Actualizar el estado de la postulación a "aceptada"
             $estadoAceptadoId = 10;
             $postulacion->estado_id = $estadoAceptadoId;
             $postulacion->save();
     
             // Rechazar automáticamente las demás postulaciones
             $estadoRechazadoId = 11;
             $trabajo->postulaciones()
                 ->where('id', '!=', $postulacionId)
                 ->update(['estado_id' => $estadoRechazadoId]);
     
             // Eliminar los chats de los demás postulantes
             Chat::where('trabajo_id', $trabajo->id)->delete();
     
             // Crear un chat vacío entre el cliente y el postulante aceptado
             Chat::create([
                 'trabajo_id' => $trabajo->id,
                 'emisor' => $trabajo->cliente_id,  // El cliente es el emisor
                 'receptor' => $postulacion->trabajador_id,  // El postulante aceptado es el receptor
                 'contenido' => 'Hola, gracias por aplicar al trabajo, cualquier duda preguntame! ',  // Chat vacío
             ]);
     
             // Opcional: actualizar el estado del trabajo a "asignado"
             $trabajo->estado_id = $estadoAceptadoId;
             $trabajo->save();
     
             $notificacion = Notificacion::create([
                'usuario_id' => $postulacion->trabajador_id,  // El trabajador es el receptor
                'mensaje' => '¡Felicidades! Tu postulación para el trabajo "' . $trabajo->titulo . '" ha sido aceptada.',
            ]);
            
            // Asegurémonos de que la notificación se crea correctamente
            if (!$notificacion) {
                Log::error("Error al crear la notificación para el trabajador: {$postulacion->trabajador_id}");
                return response()->json(['success' => false, 'message' => 'Error al crear la notificación'], 500);
            }
            
            // Emitir el evento de notificación
            event(new NewNotificacion($notificacion));
     
             DB::commit();
     
             return response()->json([
                 'success' => true,
                 'message' => 'Candidato aceptado correctamente, chat creado y notificación enviada'
             ]);
         } catch (\Exception $e) {
             DB::rollBack();
             return response()->json([
                 'success' => false,
                 'message' => 'Error al aceptar el candidato: ' . $e->getMessage()
             ], 500);
         }
     }

    /**
     * Rechazar un candidato para un trabajo
     * 
     * @param int $postulacionId El ID de la postulación a rechazar
     * @return \Illuminate\Http\JsonResponse
     */
    public function rechazar($postulacionId)
{
    try {
        $postulacion = Postulacion::findOrFail($postulacionId);
        $trabajo = $postulacion->trabajo;

        // Verificar que el usuario autenticado es el dueño del trabajo
        if (Auth::id() != $trabajo->cliente_id) {
            return response()->json([
                'success' => false,
                'message' => 'No estás autorizado para realizar esta acción'
            ], 403);
        }

        // Actualizar el estado de la postulación a "rechazado"
        // Según el seeder, el ID 11 corresponde al estado "Rechazada" para postulaciones
        $estadoRechazadoId = 11;
        $postulacion->estado_id = $estadoRechazadoId;
        $postulacion->save();

        $notificacion = Notificacion::create([
            'usuario_id' => $postulacion->trabajador_id,  // El trabajador es el receptor
            'mensaje' => 'Tu postulación para el trabajo "' . $trabajo->titulo . '" ha sido rechazada.',
        ]);
        
        // Asegurémonos de que la notificación se crea correctamente
        if (!$notificacion) {
            Log::error("Error al crear la notificación para el trabajador: {$postulacion->trabajador_id}");
            return response()->json(['success' => false, 'message' => 'Error al crear la notificación'], 500);
        }
        
        // Emitir el evento de notificación
        event(new NewNotificacion($notificacion));
        return response()->json([
            'success' => true,
            'message' => 'Candidato rechazado correctamente y notificación enviada'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al rechazar el candidato: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Ver el estado de todas las postulaciones de un trabajo
     * 
     * @param int $trabajoId El ID del trabajo
     * @return \Illuminate\Http\JsonResponse
     */
    public function estadoPostulaciones($trabajoId)
    {
        try {
            $trabajo = Trabajo::findOrFail($trabajoId);

            // Verificar que el usuario autenticado es el dueño del trabajo
            if (Auth::id() != $trabajo->cliente_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No estás autorizado para realizar esta acción'
                ], 403);
            }

            $postulaciones = $trabajo->postulaciones()
                ->with('trabajador')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $postulaciones
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las postulaciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ver las postulaciones del usuario autenticado
     * 
     * @return \Illuminate\View\View
     */
    public function misPostulaciones()
    {
        $postulaciones = Postulacion::with(['trabajo.imagenes', 'trabajo.cliente', 'estado'])
            ->where('trabajador_id', Auth::id())
            ->get();

        return view('postulaciones', compact('postulaciones'));
    }
}

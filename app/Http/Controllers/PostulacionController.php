<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Postulacion;
use App\Models\Trabajo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            
            $postulacion = Postulacion::findOrFail($postulacionId);
            $trabajo = $postulacion->trabajo;
            
            // Verificar que el usuario autenticado es el dueño del trabajo
            if (Auth::id() != $trabajo->cliente_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No estás autorizado para realizar esta acción'
                ], 403);
            }
            
            // Actualizar el estado de la postulación a "aceptado"
            // Segln el seeder, el ID 10 corresponde al estado "Aceptada" para postulaciones
            $estadoAceptadoId = 10;
            $postulacion->estado_id = $estadoAceptadoId;
            $postulacion->save();
            
            // Opcional: rechazar automáticamente las demás postulaciones
            // Segln el seeder, el ID 11 corresponde al estado "Rechazada" para postulaciones
            $estadoRechazadoId = 11;
            $trabajo->postulaciones()
                ->where('id', '!=', $postulacionId)
                ->update(['estado_id' => $estadoRechazadoId]);
            
            // Opcional: actualizar el estado del trabajo a "asignado"
            $trabajo->estado = 'asignado';
            $trabajo->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Candidato aceptado correctamente'
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
            // Segln el seeder, el ID 11 corresponde al estado "Rechazada" para postulaciones
            $estadoRechazadoId = 11;
            $postulacion->estado_id = $estadoRechazadoId;
            $postulacion->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Candidato rechazado correctamente'
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
}

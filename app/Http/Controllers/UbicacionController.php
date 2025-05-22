<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Direccion;
use App\Models\User;
use App\Models\Trabajo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class UbicacionController extends Controller
{
    /**
     * Guarda o actualiza la dirección del usuario
     */
    public function guardarDireccionUsuario(Request $request)
    {
        $request->validate([
            'direccion' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'ciudad' => 'required|string|max:100',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'es_visible_para_trabajador' => 'boolean',
        ]);

        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            
            // Buscar si el usuario ya tiene una dirección
            $direccionExistente = Direccion::where('user_id', $userId)
                ->whereNull('trabajo_id')
                ->first();
                
            if ($direccionExistente) {
                // Actualizar dirección existente
                $direccionExistente->update([
                    'direccion' => $request->direccion,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                    'es_visible_para_trabajador' => $request->has('es_visible_para_trabajador'),
                ]);
                
                $mensaje = 'Dirección actualizada correctamente';
            } else {
                // Crear nueva dirección
                Direccion::create([
                    'user_id' => $userId,
                    'direccion' => $request->direccion,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                    'es_visible_para_trabajador' => $request->has('es_visible_para_trabajador'),
                ]);
                
                $mensaje = 'Dirección guardada correctamente';
            }
            
            DB::commit();
            return redirect()->back()->with('success', $mensaje);
            
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Error al guardar dirección de usuario: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ha ocurrido un error al guardar la dirección. Inténtalo de nuevo.']);
        }
    }
    
    /**
     * Guarda o actualiza la dirección de un trabajo
     */
    public function guardarDireccionTrabajo(Request $request, $trabajoId)
    {
        $request->validate([
            'direccion' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'ciudad' => 'required|string|max:100',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'es_visible_para_trabajador' => 'boolean',
        ]);

        try {
            DB::beginTransaction();
            
            $trabajo = Trabajo::findOrFail($trabajoId);
            
            // Verificar que el usuario autenticado es el dueño del trabajo
            if (Auth::id() != $trabajo->cliente_id) {
                return redirect()->back()->withErrors(['error' => 'No tienes permiso para modificar este trabajo.']);
            }
            
            // Buscar si el trabajo ya tiene una dirección
            $direccionExistente = Direccion::where('trabajo_id', $trabajoId)->first();
                
            if ($direccionExistente) {
                // Actualizar dirección existente
                $direccionExistente->update([
                    'direccion' => $request->direccion,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                    'es_visible_para_trabajador' => $request->has('es_visible_para_trabajador'),
                ]);
                
                $mensaje = 'Dirección del trabajo actualizada correctamente';
            } else {
                // Crear nueva dirección
                Direccion::create([
                    'trabajo_id' => $trabajoId,
                    'direccion' => $request->direccion,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                    'es_visible_para_trabajador' => $request->has('es_visible_para_trabajador'),
                ]);
                
                $mensaje = 'Dirección del trabajo guardada correctamente';
            }
            
            DB::commit();
            return redirect()->back()->with('success', $mensaje);
            
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Error al guardar dirección del trabajo: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ha ocurrido un error al guardar la dirección. Inténtalo de nuevo.']);
        }
    }
    
    /**
     * Muestra la vista de mapa completo para un trabajo
     */
    public function mostrarMapa($trabajoId)
    {
        $trabajo = Trabajo::with(['direcciones', 'cliente', 'categoriastipotrabajo'])->findOrFail($trabajoId);
        
        // Verificar que el trabajo tiene una dirección
        if ($trabajo->direcciones->isEmpty()) {
            return redirect()->route('trabajos.detalle', $trabajoId)
                ->withErrors(['error' => 'Este trabajo no tiene una ubicación registrada.']);
        }
        
        // Verificar que el usuario puede ver la dirección
        $direccion = $trabajo->direcciones->first();
        if (!$direccion->es_visible_para_trabajador && Auth::id() != $trabajo->cliente_id) {
            return redirect()->route('trabajos.detalle', $trabajoId)
                ->withErrors(['error' => 'No tienes permiso para ver la ubicación de este trabajo.']);
        }
        
        return view('trabajos.mapa', compact('trabajo'));
    }
    
    /**
     * Obtener trabajos cercanos a una ubicación
     */
    public function trabajosCercanos(Request $request)
    {
        $request->validate([
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'distancia' => 'required|numeric|min:1|max:100', // en kilómetros
        ]);
        
        $lat = $request->latitud;
        $lng = $request->longitud;
        $distancia = $request->distancia;
        
        // Fórmula Haversine para calcular distancias entre coordenadas
        $trabajos = Trabajo::select('trabajos.*')
            ->join('direcciones', 'trabajos.id', '=', 'direcciones.trabajo_id')
            ->selectRaw("
                (6371 * acos(
                    cos(radians(?)) * 
                    cos(radians(latitud)) * 
                    cos(radians(longitud) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(latitud))
                )) AS distancia", [$lat, $lng, $lat])
            ->where('direcciones.es_visible_para_trabajador', true)
            ->where('trabajos.estado_id', 1) // Solo trabajos disponibles
            ->having('distancia', '<=', $distancia)
            ->orderBy('distancia')
            ->with(['categoriastipotrabajo', 'cliente', 'direcciones'])
            ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $trabajos
        ]);
    }
}

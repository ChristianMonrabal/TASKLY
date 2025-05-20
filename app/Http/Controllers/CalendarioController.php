<?php

namespace App\Http\Controllers;

use App\Models\calendario;
use App\Models\Trabajo;
use App\Models\Postulacion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $eventos = calendario::where('cliente', $userId)
            ->orWhere('trabajador', $userId)
            ->get()
            ->map(function ($evento) {
                return [
                    'date' => $evento->fecha,
                    'text' => $evento->titulo . ': ' . $evento->descripcion,
                    'type' => 'work',
                ];
            });

        return view('calendario.calendario', [
            'eventos' => $eventos,
        ]);
    }

    public function insertar(Request $request)
    {
        try {
            $request->validate([
                'trabajo_id' => 'required|exists:trabajos,id',
                'fecha' => 'required|date|after_or_equal:today',
            ]);
    
            $trabajo = Trabajo::find($request->trabajo_id);
    
            $trabajadorId = $trabajo->trabajador_id;
    
            if (!$trabajadorId) {
                $postulacionAceptada = Postulacion::where('trabajo_id', $trabajo->id)
                    ->where('estado_id', 10)
                    ->first();
    
                if (!$postulacionAceptada) {
                    return response()->json(['error' => 'Este trabajo aún no tiene un trabajador asignado.'], 422);
                }
    
                $trabajadorId = $postulacionAceptada->trabajador_id;
            }
    
            calendario::create([
                'titulo' => $trabajo->titulo,
                'descripcion' => '',
                'trabajo' => $trabajo->id,
                'cliente' => $trabajo->cliente_id,
                'trabajador' => $trabajadorId,
                'fecha' => $request->fecha,
            ]);

        return response()->json(['success' => 'Fecha de encuentro guardada correctamente.']);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['error' => 'Validación fallida.'], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Hubo un error al guardar la fecha.'], 500);
    }
}

public function actualizar(Request $request)
{
    try {
        $request->validate([
            'trabajo_id' => 'required|exists:trabajos,id',
            'fecha' => 'required|date|after_or_equal:today',
        ]);

        $evento = calendario::where('trabajo', $request->trabajo_id)->first();

        if (!$evento) {
            return response()->json(['error' => 'No existe una fecha registrada para este trabajo.'], 404);
        }

        $evento->update([
            'fecha' => $request->fecha,
        ]);

        return response()->json(['success' => 'Fecha de encuentro actualizada correctamente.']);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['error' => 'Validación fallida.'], 422);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Hubo un error al actualizar la fecha.'], 500);
    }
}

public function obtenerFecha($trabajoId)
{
    $evento = calendario::where('trabajo', $trabajoId)->first();
    if ($evento) {
        return response()->json(['fecha' => $evento->fecha]);
    }
    return response()->json(['fecha' => null]);
}
}
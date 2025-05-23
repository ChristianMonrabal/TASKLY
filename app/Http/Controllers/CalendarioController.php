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
                    'text' => $evento->titulo . ($evento->hora ? ' - ' . substr($evento->hora, 0, 5) : ''),
                    'type' => 'work',
                    'trabajo_id' => $evento->trabajo,
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
                'hora' => 'required|date_format:H:i',
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
                'hora' => $request->hora,
            ]);

            return response()->json(['success' => 'Fecha y hora de encuentro guardadas correctamente.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validación fallida.'], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function actualizar(Request $request)
    {
        try {
            $request->validate([
                'trabajo_id' => 'required|exists:trabajos,id',
                'fecha' => 'required|date|after_or_equal:today',
                'hora' => 'nullable|date_format:H:i',
            ]);

            $evento = calendario::where('trabajo', $request->trabajo_id)->first();

            if (!$evento) {
                return response()->json(['error' => 'No existe una fecha registrada para este trabajo.'], 404);
            }

            $evento->update([
                'fecha' => $request->fecha,
                'hora' => $request->hora,
            ]);

            return response()->json(['success' => 'Fecha y hora de encuentro actualizadas correctamente.']);

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
            return response()->json([
                'fecha' => $evento->fecha,
                'hora' => $evento->hora,
            ]);
        }
        return response()->json(['fecha' => null, 'hora' => null]);
    }
}

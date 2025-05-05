<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trabajo;
use App\Models\Estado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminJobController extends Controller
{
    /**
     * Muestra la lista de trabajos.
     */
    public function index()
    {
        if (Auth::user()->rol_id != 1) {
            return redirect()->route('trabajos.index');
        }

        $trabajos = Trabajo::with(['cliente', 'estado'])
            ->whereHas('estado', function($q) {
                $q->whereNotIn('nombre', ['Completado']);
            })
            ->get();

        return view('Admin.trabajos.index', compact('trabajos'));
    }

    /**
     * Muestra los detalles de un trabajo (para AJAX).
     */
    public function show(Trabajo $trabajo)
    {
        return response()->json($trabajo);
    }

    /**
     * Formulario de edición (modal).
     */
    public function edit(Trabajo $trabajo)
    {
        $estados = Estado::all();
        return view('Admin.trabajos.edit', compact('trabajo', 'estados'));
    }

    /**
     * Actualiza un trabajo.
     */
    public function update(Request $request, Trabajo $trabajo)
    {
        $validated = $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric',
            'direccion'   => 'required|string|max:255',
            'estado_id'   => 'required|exists:estados,id',
        ]);

        $trabajo->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Trabajo actualizado correctamente.',
                'data'    => $trabajo,
            ]);
        }

        return redirect()->route('admin.trabajos.index')
                         ->with('success', 'Trabajo actualizado correctamente.');
    }

    /**
     * JSON de trabajos (filtros).
     */
    public function apiIndex(Request $request)
    {
        $query = Trabajo::with(['cliente', 'estado'])
            ->whereHas('estado', function($q) {
                $q->whereNotIn('nombre', ['Finalizado', 'Completado']);
            });

        if ($request->filled('cliente')) {
            $query->whereHas('cliente', fn($q) =>
                $q->where('nombre', 'like', "%{$request->cliente}%")
            );
        }
        if ($request->filled('estado')) {
            $query->whereHas('estado', fn($q) =>
                $q->where('nombre', 'like', "%{$request->estado}%")
            );
        }

        return response()->json($query->get());
    }

    /**
     * JSON de estados para trabajos.
     */
    public function apiEstadosTrabajo()
    {
        return response()->json(
            Estado::where('tipo_estado', 'trabajos')->get()
        );
    }

    /**
     * Elimina un trabajo (solo si NO está finalizado/completado).
     */
    public function destroy(Trabajo $trabajo)
    {
        // No permitir borrado si el estado es Finalizado o Completado
        if ($trabajo->estado && in_array($trabajo->estado->nombre, ['Finalizado', 'Completado'])) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes borrar un trabajo que ya está completado.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            if (method_exists($trabajo, 'categoriastipotrabajo')) {
                $trabajo->categoriastipotrabajo()->detach();
            }
            if (method_exists($trabajo, 'chat') && $trabajo->chat()->exists()) {
                $trabajo->chat()->delete();
            }
            if (method_exists($trabajo, 'postulaciones') && $trabajo->postulaciones()->exists()) {
                $trabajo->postulaciones()->delete();
            }
            if (method_exists($trabajo, 'imagenes') && $trabajo->imagenes()->exists()) {
                $trabajo->imagenes()->delete();
            }
            if (method_exists($trabajo, 'pagos') && $trabajo->pagos()->exists()) {
                $trabajo->pagos()->delete();
            }
            if (method_exists($trabajo, 'valoraciones') && $trabajo->valoraciones()->exists()) {
                $trabajo->valoraciones()->delete();
            }

            $trabajo->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Trabajo eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el trabajo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Historial: lista de trabajos finalizados/completados.
     */
    public function historial()
    {
        if (Auth::user()->rol_id != 1) {
            return redirect()->route('trabajos.index');
        }

        $trabajos = Trabajo::with(['cliente', 'estado'])
            ->whereHas('estado', fn($q) =>
                $q->whereIn('nombre', ['Finalizado', 'Completado'])
            )
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('Admin.trabajos.historial', compact('trabajos'));
    }

    /**
     * Muestra la vista de trabajos completados con filtros.
     */
    public function completadosIndex()
    {
        if (Auth::user()->rol_id != 1) {
            return redirect()->route('trabajos.index');
        }
        return view('Admin.trabajos.completados');
    }

/**
 * Devuelve JSON de trabajos cuyo estado sea "Finalizado" o "Completado",
 * con filtros opcionales por cliente y por fecha de última actualización.
 */
public function apiCompletados(Request $request)
{
    $query = Trabajo::with(['cliente', 'estado'])
        ->whereHas('estado', fn($q) =>
            $q->whereIn('nombre', ['Finalizado','Completado'])
        );

    // Filtro por cliente (nombre)
    if ($request->filled('cliente')) {
        $query->whereHas('cliente', function($q) use ($request) {
            $q->where('nombre', 'like', "%{$request->cliente}%")
              ->orWhere('apellidos', 'like', "%{$request->cliente}%");
        });
    }

    // Filtro por fecha (updated_at)
    if ($request->filled('fecha')) {
        // Suponiendo que llega en formato YYYY-MM-DD
        $query->whereDate('updated_at', $request->fecha);
    }

    $completados = $query->orderBy('updated_at','desc')->get();
    return response()->json($completados);
}
}

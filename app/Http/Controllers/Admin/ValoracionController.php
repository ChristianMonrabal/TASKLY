<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Valoracion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ValoracionController extends Controller
{
    /**
     * Muestra la vista principal donde se listan las valoraciones.
     * La vista se encargará de obtener los datos mediante fetch.
     */
    public function index()
    {
        if (Auth::user()->rol_id != 1) {
            return redirect()->route('trabajos.index');
        }
        return view('Admin.valoraciones.index');
    }

    /**
     * Devuelve los datos de una valoración en formato JSON.
     */
    public function show(Valoracion $valoracion)
    {
        return response()->json($valoracion);
    }

    /**
     * Actualiza la información de una valoración.
     * Solo se permite actualizar el "comentario" y la "imagen" (img_valoracion).
     */
    public function update(Request $request, Valoracion $valoracion)
{
    $validated = $request->validate([
        'comentario'    => 'nullable|string',
        'img_valoracion'=> 'nullable|image|max:2048',
    ]);

    // Verifica si se marcó la opción para eliminar la imagen.
    if ($request->has('remove_img_valoracion') && $request->input('remove_img_valoracion')) {
        $validated['img_valoracion'] = null;
        // Opcional: eliminar el archivo de almacenamiento
        // if ($valoracion->img_valoracion) {
        //     \Storage::disk('public')->delete($valoracion->img_valoracion);
        // }
    } elseif ($request->hasFile('img_valoracion')) {
        $path = $request->file('img_valoracion')->store('valoraciones', 'public');
        $validated['img_valoracion'] = $path;
    }

    $valoracion->update($validated);

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Valoración actualizada correctamente.',
            'data'    => $valoracion,
        ]);
    }

    return redirect()->route('admin.valoraciones.index')->with('success', 'Valoración actualizada correctamente.');
}

    /**
     * Devuelve las valoraciones (con filtro por trabajador/cliente) en JSON.
     */
    public function apiIndex(Request $request)
    {
        $query = Valoracion::with(['trabajo.cliente', 'trabajador']);

        if ($request->filled('trabajador')) {
            $query->whereHas('trabajador', fn($q) =>
                $q->where('nombre', 'like', "%{$request->trabajador}%")
            );
        }
        if ($request->filled('cliente')) {
            $query->whereHas('trabajo.cliente', fn($q) =>
                $q->where('nombre', 'like', "%{$request->cliente}%")
            );
        }

        return $query->orderByDesc('created_at')->paginate(10);
    }

    /**
     * Elimina una valoración usando transacciones.
     */
    public function destroy(Valoracion $valoracion)
    {
        try {
            // Iniciar transacción para garantizar que todas las operaciones se completen o ninguna
            DB::beginTransaction();
            
            // Eliminar la valoración
            $valoracion->delete();
            
            // Si todo salió bien, confirmar la transacción
            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Valoración eliminada correctamente.'
                ]);
            }

            return redirect()->route('admin.valoraciones.index')->with('success', 'Valoración eliminada correctamente.');
        } catch (\Exception $e) {
            // Si algo salió mal, revertir todos los cambios
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la valoración: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error al eliminar la valoración: ' . $e->getMessage());
        }
    }
}

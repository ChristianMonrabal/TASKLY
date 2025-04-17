<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Valoracion;

class ValoracionController extends Controller
{
    /**
     * Muestra la vista principal donde se listan las valoraciones.
     * La vista se encargará de obtener los datos mediante fetch.
     */
    public function index()
    {
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
     * Elimina una valoración.
     */
    public function destroy(Valoracion $valoracion)
    {
        $valoracion->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Valoración eliminada correctamente.'
            ]);
        }

        return redirect()->route('admin.valoraciones.index')->with('success', 'Valoración eliminada correctamente.');
    }
}

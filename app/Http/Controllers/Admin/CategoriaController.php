<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * Muestra la vista principal (index) donde se listan las categorías.
     */
    public function index()
    {
        return view('Admin.categorias.index');
    }
    
    /**
     * Devuelve los datos de una categoría en formato JSON.
     * Útil para cargar los datos en el modal de edición.
     */
    public function show(Categoria $categoria)
    {
        return response()->json($categoria);
    }

    /**
     * Almacena una nueva categoría.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre'
        ]);

        $categoria = Categoria::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoría creada correctamente.',
                'data'    => $categoria
            ], 201);
        }
        
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Actualiza la información de una categoría.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre,'.$categoria->id
        ]);

        $categoria->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Categoría actualizada correctamente.',
                'data'    => $categoria
            ]);
        }
        
        return redirect()->route('admin.categorias.index')->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Elimina una categoría.
     */
    public function destroy(Categoria $categoria)
    {
        try {
            $categoria->delete();
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Categoría eliminada correctamente.'
                ]);
            }
            return redirect()->route('admin.categorias.index')->with('success', 'Categoría eliminada correctamente.');
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la categoría: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }
}

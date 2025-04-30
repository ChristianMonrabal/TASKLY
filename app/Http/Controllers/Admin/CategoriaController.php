<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CategoriaTipoTrabajo;
use App\Models\Habilidad;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    public function index()
    {
        if (Auth::user()->rol_id != 1) {
            return redirect()->route('trabajos.index');
        }
        return view('Admin.categorias.index');
    }
    
    public function show(Categoria $categoria)
    {
        return response()->json($categoria);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'  => 'required|string|max:100|unique:categorias,nombre',
            'visible' => 'sometimes|in:Sí,No'
        ]);
        $validated['visible'] = $validated['visible'] ?? 'Sí';
        $categoria = Categoria::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada correctamente.',
            'data'    => $categoria
        ], 201);
    }

    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate([
            'nombre'  => 'required|string|max:100|unique:categorias,nombre,'.$categoria->id,
            'visible' => 'required|in:Sí,No'
        ]);
        $categoria->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada correctamente.',
            'data'    => $categoria
        ]);
    }

    public function toggleVisible(Categoria $categoria)
    {
        $categoria->visible = $categoria->visible === 'Sí' ? 'No' : 'Sí';
        $categoria->save();

        return response()->json([
            'success' => true,
            'visible' => $categoria->visible
        ]);
    }

    public function apiIndex(Request $request)
    {
        $query = Categoria::query();
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', "%{$request->nombre}%");
        }
        if ($request->filled('sort') && in_array($request->sort, ['asc','desc'])) {
            $query->orderBy('nombre', $request->sort);
        }
        return response()->json($query->get());
    }

    public function destroy(Categoria $categoria)
    {
        // Si hay trabajos asociados, no dejar borrar
        if (CategoriaTipoTrabajo::where('categoria_id', $categoria->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar la categoría porque hay trabajos asociados.'
            ], 400);
        }

        try {
            DB::beginTransaction();
            CategoriaTipoTrabajo::where('categoria_id', $categoria->id)->delete();
            Habilidad::where('categoria_id', $categoria->id)->delete();
            $categoria->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al eliminar categoría {$categoria->id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la categoría: ' . $e->getMessage()
            ], 500);
        }
    }
}

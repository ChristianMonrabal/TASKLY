<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CategoriaTipoTrabajo;
use App\Models\Habilidad;

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
     * Devuelve las categorías (con filtro por nombre y orden) en JSON.
     */
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

    /**
     * Elimina una categoría y gestiona sus relaciones usando transacciones
     * para garantizar la integridad de los datos.
     */
    public function destroy(Categoria $categoria)
    {
        try {
            // Iniciar transacción para garantizar que todas las operaciones se completen o ninguna
            DB::beginTransaction();
            
            // Eliminar relaciones en la tabla pivot (categorias_tipo_trabajo)
            CategoriaTipoTrabajo::where('categoria_id', $categoria->id)->delete();
            
            // Eliminar las habilidades asociadas a esta categoría
            Habilidad::where('categoria_id', $categoria->id)->delete();
            
            // Ahora eliminamos la categoría
            $categoria->delete();
            
            // Si todo salió bien, confirmar la transacción
            DB::commit();
            
           
                return response()->json([
                    'success' => true,
                    'message' => 'Categoría eliminada correctamente.'
                ]);
           
        } catch (\Exception $e) {
            // Registramos el error en el log
            Log::error("Error al eliminar categoría {$categoria->id}: " . $e->getMessage());
            
            // Revertir la transacción en caso de error
            DB::rollBack();
            
            
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la categoría: ' . $e->getMessage()
                ], 500);
           
        }
    }
}

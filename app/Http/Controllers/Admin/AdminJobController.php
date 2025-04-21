<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trabajo;
use App\Models\Estado;
use Illuminate\Support\Facades\DB;

class AdminJobController extends Controller
{
    /**
     * Muestra la lista de trabajos.
     */
    public function index()
    {
        // Se cargan las relaciones cliente y estado para mostrar la información
        $trabajos = Trabajo::with(['cliente', 'estado'])->get();
        return view('Admin.trabajos.index', compact('trabajos'));
    }

    /**
     * Muestra los detalles de un trabajo (para cargar datos via AJAX).
     */
    public function show(Trabajo $trabajo)
    {
        return response()->json($trabajo);
    }

    /**
     * Muestra el formulario para editar un trabajo.
     * (En este caso se utilizará un modal; se pasa la información necesaria para cargar select de estados)
     */
    public function edit(Trabajo $trabajo)
    {
        // Obtenemos los estados disponibles para actualizar el campo estado_id.
        $estados = Estado::all();
        return view('Admin.trabajos.edit', compact('trabajo', 'estados'));
    }

    /**
     * Actualiza la información de un trabajo.
     * Se permite editar: titulo, descripcion, precio, direccion y estado_id.
     * NO se permite modificar el cliente (cliente_id).
     */
    public function update(Request $request, Trabajo $trabajo)
    {
        $validated = $request->validate([
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric',
            'direccion'   => 'required|string|max:255',
            'estado_id'   => 'required|exists:estados,id',
            // Se excluye 'cliente_id'
        ]);

        $trabajo->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Trabajo actualizado correctamente.',
                'data'    => $trabajo,
            ]);
        }

        return redirect()->route('admin.trabajos.index')->with('success', 'Trabajo actualizado correctamente.');
    }

    /**
     * Devuelve los trabajos (con filtro por cliente/estado) en JSON.
     */
    public function apiIndex(Request $request)
    {
        $query = Trabajo::with(['cliente', 'estado']);

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
     * Devuelve los estados de tipo "trabajos" en JSON.
     */
    public function apiEstadosTrabajo()
    {
        return response()->json(
            Estado::where('tipo_estado', 'trabajos')->get()
        );
    }


    /**
     * Elimina un trabajo y todas sus relaciones usando transacciones.
     */
    public function destroy(Trabajo $trabajo)
    {
        try {
            // Iniciar transacción para garantizar que todas las operaciones se completen o ninguna
            DB::beginTransaction();
            
            // Desvincular la relación many-to-many con categorías (tabla pivot "categorias_tipo_trabajo")
            if(method_exists($trabajo, 'categoriastipotrabajo')) {
                $trabajo->categoriastipotrabajo()->detach();
            }
            
            // Eliminar los chats asociados a este trabajo
            if(method_exists($trabajo, 'chat') && $trabajo->chat()->exists()){
                $trabajo->chat()->delete();
            }
            
            // Eliminar las postulaciones asociadas al trabajo
            if(method_exists($trabajo, 'postulaciones') && $trabajo->postulaciones()->exists()){
                $trabajo->postulaciones()->delete();
            }
            
            // Eliminar las imágenes asociadas al trabajo, si existen
            if(method_exists($trabajo, 'imagenes') && $trabajo->imagenes()->exists()){
                $trabajo->imagenes()->delete();
            }
            
            // Eliminar los pagos asociados al trabajo, si existen
            if(method_exists($trabajo, 'pagos') && $trabajo->pagos()->exists()){
                $trabajo->pagos()->delete();
            }
            
            // Eliminar las valoraciones asociadas al trabajo, si existen
            if(method_exists($trabajo, 'valoraciones') && $trabajo->valoraciones()->exists()){
                $trabajo->valoraciones()->delete();
            }
            
            // Finalmente, eliminar el trabajo
            $trabajo->delete();
            
            // Si todo salió bien, confirmar la transacción
            DB::commit();
    
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Trabajo eliminado correctamente.'
                ]);
            }
            return redirect()->route('admin.trabajos.index')->with('success', 'Trabajo eliminado correctamente.');
        } catch (\Exception $e) {
            // Si algo salió mal, revertir todos los cambios
            DB::rollBack();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el trabajo: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al eliminar el trabajo: ' . $e->getMessage());
        }
    }
}

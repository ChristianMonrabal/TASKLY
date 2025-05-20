<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logro;
use Illuminate\Support\Facades\DB;

class LogroController extends Controller
{
    public function index()
    {
        return view('Admin.logros.index');
    }

    public function apiIndex(Request $request)
    {
        $query = Logro::query();
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', "%{$request->nombre}%");
        }
        return $query->orderBy('nombre')->paginate(10);
    }

    public function show(Logro $logro)
    {
        return response()->json($logro);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100|unique:logros,nombre',
            'descripcion' => 'nullable|string',
            'descuento'   => 'required|numeric|min:0'
        ]);

        $logro = Logro::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Logro creado correctamente.',
            'data'    => $logro
        ], 201);
    }

    public function update(Request $request, Logro $logro)
    {
        $validated = $request->validate([
            'nombre'      => "required|string|max:100|unique:logros,nombre,{$logro->id}",
            'descripcion' => 'nullable|string',
            'descuento'   => 'required|numeric|min:0'
        ]);

        $logro->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Logro actualizado correctamente.',
            'data'    => $logro
        ]);
    }

    public function destroy(Logro $logro)
    {
        // Antes de borrar: comprobar que no hay registros en logros_completos
        if ($logro->logrosCompletos()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el logro porque hay registros asociados.'
            ], 400);
        }

        try {
            $logro->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logro eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el logro: ' . $e->getMessage()
            ], 500);
        }
    }
}

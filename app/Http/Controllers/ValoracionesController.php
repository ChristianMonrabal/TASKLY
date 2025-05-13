<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Valoracion;

class ValoracionesController extends Controller
{
    public function index()
    {
        return view('valoraciones.valoraciones');
    }
    
    /**
     * Muestra la página para valorar a un trabajador tras completar un pago
     */
    public function mostrarFormularioValoracion($trabajador_id)
    {
        // Obtener el trabajador
        $trabajador = \App\Models\User::findOrFail($trabajador_id);
        
        // Obtener valoraciones previas del trabajador para mostrarlas
        $valoraciones = Valoracion::where('trabajador_id', $trabajador_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('valoraciones.crear', [
            'trabajador' => $trabajador,
            'valoraciones' => $valoraciones
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'comentario' => 'required|string',
        'puntuacion' => 'nullable|integer|min:1|max:5',
        'imagen' => 'nullable|image|max:2048',
    ]);

    $trabajoId = session('trabajo_id');
    $trabajadorId = session('trabajador_id');

    if (!$trabajoId || !$trabajadorId) {
        return redirect()->back()->with('error', 'Faltan datos del trabajo o trabajador.');
    }

    $rutaImagen = null;
    if ($request->hasFile('imagen')) {
        $imagen = $request->file('imagen');
        $nombreImagen = uniqid('valoracion_') . '.' . $imagen->getClientOriginalExtension();
        $imagen->move(public_path('img/valoraciones'), $nombreImagen);
        $rutaImagen = 'img/valoraciones/' . $nombreImagen;
    }

    Valoracion::create([
        'trabajo_id' => $trabajoId,
        'trabajador_id' => $trabajadorId,
        'puntuacion' => $request->input('puntuacion'),
        'img_valoracion' => $rutaImagen,
        'comentario' => $request->input('comentario'),
    ]);

    return redirect()->route('trabajos.finalizados')->with('success', 'Valoración guardada correctamente.');
}

}

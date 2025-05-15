<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Valoracion;
use App\Models\Trabajo;
use App\Models\User;
use App\Models\Postulacion;

class ValoracionesController extends Controller
{
    public function index(Request $request)
    {
        $trabajoId = $request->input('trabajo_id', session('trabajo_id'));
        $trabajadorId = $request->input('trabajador_id', session('trabajador_id'));
        $postulacionId = $request->input('postulacion_id', session('postulacion_id'));
        
        if ($trabajoId && $trabajadorId) {
            session(['trabajo_id' => $trabajoId]);
            session(['trabajador_id' => $trabajadorId]);
            if ($postulacionId) {
                session(['postulacion_id' => $postulacionId]);
            }
            
            $trabajo = Trabajo::find($trabajoId);
            $trabajador = User::find($trabajadorId);
            
            \Illuminate\Support\Facades\Log::info("Cargando valoración", [
                'trabajo_id' => $trabajoId,
                'trabajador_id' => $trabajadorId,
                'trabajo_encontrado' => $trabajo ? 'Sí' : 'No',
                'trabajador_encontrado' => $trabajador ? 'Sí' : 'No'
            ]);
            
            if ($trabajo && $trabajador) {
                return view('valoraciones.valoraciones', [
                    'trabajo' => $trabajo,
                    'trabajador' => $trabajador,
                    'postulacion_id' => $postulacionId
                ]);
            } else {
                return redirect()->route('trabajos.index')
                    ->with('error', 'No se pudieron cargar los datos del trabajo o del trabajador.');
            }
        }
        
        return redirect()->route('trabajos.index')
            ->with('error', 'No hay suficiente información para mostrar esta valoración.');
    }
    
    public function mostrarFormularioValoracion($trabajador_id)
    {
        $trabajador = User::findOrFail($trabajador_id);
        
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
        'puntuacion' => 'required|integer|min:1|max:5',
        'imagen' => 'nullable|image|max:2048',
    ], [
        'comentario.required' => 'El comentario es obligatorio.',
        'puntuacion.required' => 'La puntuación es obligatoria.',
        'puntuacion.integer' => 'La puntuación debe ser un número entero.',
        'puntuacion.min' => 'La puntuación mínima es 1.',
        'puntuacion.max' => 'La puntuación máxima es 5.',
        'imagen.image' => 'El archivo debe ser una imagen.',
        'imagen.max' => 'La imagen no debe superar los 2MB.',
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

    return redirect()->route('trabajos.index')->with('success', 'Valoración guardada correctamente.');
}
}

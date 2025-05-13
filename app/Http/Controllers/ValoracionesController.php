<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Valoracion;
use App\Models\Trabajo;
use App\Models\User;
use App\Models\Postulacion;

class ValoracionesController extends Controller
{
    /**
     * Muestra la página de valoraciones con datos dinámicos del trabajo y trabajador
     */
    public function index(Request $request)
    {
        // Intentamos obtener datos del trabajo y trabajador de la sesión o de la URL
        $trabajoId = $request->input('trabajo_id', session('trabajo_id'));
        $trabajadorId = $request->input('trabajador_id', session('trabajador_id'));
        $postulacionId = $request->input('postulacion_id', session('postulacion_id'));
        
        // Guardamos en sesión para uso posterior
        if ($trabajoId && $trabajadorId) {
            session(['trabajo_id' => $trabajoId]);
            session(['trabajador_id' => $trabajadorId]);
            if ($postulacionId) {
                session(['postulacion_id' => $postulacionId]);
            }
            
            // Cargamos el trabajo y el trabajador
            $trabajo = Trabajo::find($trabajoId);
            $trabajador = User::find($trabajadorId);
            
            // Registramos para depuración
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
                // Si no encontramos los objetos, mostramos un error
                return redirect()->route('trabajos.index')
                    ->with('error', 'No se pudieron cargar los datos del trabajo o del trabajador.');
            }
        }
        
        // Si llegamos aquí, no hay datos suficientes para mostrar la valoración
        return redirect()->route('trabajos.index')
            ->with('error', 'No hay suficiente información para mostrar esta valoración.');
    }
    
    /**
     * Muestra la página para valorar a un trabajador tras completar un pago
     */
    public function mostrarFormularioValoracion($trabajador_id)
    {
        // Obtener el trabajador
        $trabajador = User::findOrFail($trabajador_id);
        
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
        'puntuacion' => 'required|integer|min:1|max:5', // ahora es requerido
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

    return redirect()->route('trabajos.index')->with('success', 'Valoración guardada correctamente.');
}

}

<?php
namespace App\Http\Controllers;

use App\Models\Trabajo;
use App\Models\CategoriaTipoTrabajo;
use App\Models\Categoria;
use App\Models\Habilidad;
use App\Models\ImgTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function crear()
    {
        $user = auth()->user();
    
        // Verificar si el usuario está autenticado
        if (!$user) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión primero.');
        }
    
        $categorias = Categoria::all(); // Obtén todas las categorías
        return view('crear_trabajo', compact('user', 'categorias')); // Pásalas a la vista
    }
    
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión primero.');
        }
    
        DB::beginTransaction();
        try {
            // Validación de los datos
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string|max:1000',
                'precio' => 'required|numeric',
                'categorias' => 'nullable|array', // Ahora esperamos las categorías
                'categorias.*' => 'exists:categorias,id', // Asegura que las categorías existan
                'direccion' => 'required|string|max:255',
                'imagenes' => 'nullable|array|max:5',
                'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            // Crear trabajo
            $trabajo = Trabajo::create([
                'titulo' => $validated['titulo'],
                'descripcion' => $validated['descripcion'],
                'precio' => $validated['precio'],
                'direccion' => $validated['direccion'],
                'cliente_id' => auth()->id('1'),
                'estado_id' => 1, // Estado inicial
            ]);
    
            // Guardar imágenes si existen
            if ($request->hasFile('imagenes')) {
                $destinationPath = public_path('img/trabajos');
    
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
    
                foreach ($request->file('imagenes') as $image) {
                    $filename = uniqid() . '_' . $image->getClientOriginalName();
                    $image->move($destinationPath, $filename);
    
                    ImgTrabajo::create([
                        'trabajo_id' => $trabajo->id,
                        'ruta_imagen' => 'img/trabajos/' . $filename,
                        'descripcion' => $validated['descripcion'],
                    ]);
                }
            }
    
            // Guardar categorías seleccionadas
            if (!empty($validated['categorias'])) {
                $trabajo->categorias()->sync($validated['categorias']);
            }
    
            DB::commit();
            return redirect()->route('trabajos.publicados')->with('success', 'Trabajo creado exitosamente.');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Error al crear trabajo: ' . $e->getMessage());
            return redirect()->route('trabajos.publicados')->with('error', 'Hubo un error al crear el trabajo. Intenta de nuevo.');
        }
    }

    public function trabajosPublicados()
    {
        $usuarioId = auth()->id(); // Obtener el ID del usuario autenticado
    
        // Obtener todos los trabajos publicados por el usuario, incluyendo sus imágenes
        $trabajos = Trabajo::where('cliente_id', $usuarioId)->with('imagenes')->get();
    
        // Pasar los trabajos y sus imágenes a la vista
        return view('trabajos_publicados', compact('trabajos'));
    }

    // TrabajoController.php
    public function show($id)
    {
        // Recupera el trabajo por ID
        $trabajo = Trabajo::with('imagenes')->findOrFail($id);

        // Retorna la vista con los datos del trabajo
        return view('detalles_trabajo', compact('trabajo'));
    }

}

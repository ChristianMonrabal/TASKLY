<?php
namespace App\Http\Controllers;

use App\Models\Trabajo;
use App\Models\CategoriaTipoTrabajo;
use App\Models\ImgTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function crear()
    {
        return view('crear_trabajo');
    }

    public function store(Request $request)
    {   // Iniciar una transacción
        DB::beginTransaction();
        try {
        // Validar los datos del formulario
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'precio' => 'required|numeric',
            'tags' => 'nullable|string',
            'direccion' => 'required|string|max:255',
            'imagenes' => 'nullable|array|max:5', // Limitar a 5 imágenes
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validar imagen
        ]);

        // Crear el trabajo en la base de datos
        $trabajo = new Trabajo();
        $trabajo->titulo = $request->titulo;
        $trabajo->descripcion = $request->descripcion;
        $trabajo->precio = $request->precio;
        $trabajo->direccion = $request->direccion;
        // $trabajo->tags = $request->tags;
        $trabajo->cliente_id = auth()->user()->id; // Asignar el cliente autenticado
        // $trabajo->cliente_id = 1; // Asignar el cliente autenticado
        $trabajo->estado_id = 1; // Asignar un estado por defecto
        $trabajo->save(); // Guardar en la base de datos

        // Manejar las imágenes (si existen)
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $image) {
                $path = $image->store('trabajos', 'public'); // Guardar la imagen en la carpeta 'trabajos' dentro de 'public/storage'
                $imagen = new ImgTrabajo();
                $imagen->trabajo_id = $trabajo->id; // Asignar el trabajo al que pertenece la imagen
                $imagen->ruta_imagen = $path; // Guardar el ruta de la imagen
                $imagen->descripcion = $request->descripcion; // Asignar la descripción de la imagen
                $imagen->save(); // Guardar la imagen en la base de datos
                // Guardar cada imagen asociada al trabajo
            }
        }
        $categoria = new CategoriaTipoTrabajo();
        $categoria->trabajo_id = $trabajo->id;
        $categoria->categoria_id = $request->tags; // Asignar la categoría seleccionada
        $categoria->save(); // Guardar la relación en la base de datos
       
        DB::commit(); // Confirmar la transacción
        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('trabajos.publicados')->with('success', 'Trabajo creado con éxito');

        } catch (\PDOException $th) {
            echo $th;
            DB::rollback();
        }
    }

    public function trabajosPublicados()
    {
        $usuarioId = auth()->id(); // Obtener el ID del usuario autenticado

        // Obtener todos los trabajos publicados por el usuario
        $trabajos = Trabajo::where('cliente_id', $usuarioId)->get();

        // Pasar los trabajos a la vista
        return view('trabajos_publicados', compact('trabajos'));
    }
}

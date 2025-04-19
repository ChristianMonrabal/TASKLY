<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajo;
use App\Models\Categoria;
use App\Models\CategoriaTipoTrabajo;
use App\Models\ImgTrabajo;
use App\Models\Estado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function crear()
    {
        $categorias = Categoria::all(); // Obtener todas las categorías
        $user = auth()->user(); // Obtener el usuario autenticado
        return view('crear_trabajo', compact('categorias', 'user'));
    }
    
    public function store(Request $request)
    {
        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'direccion' => 'required|string|max:255',
            'categorias' => 'required|array',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Permitir hasta 5 imágenes
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Obtener el cliente (usuario autenticado)
        $cliente = Auth::user();

        // Crear el trabajo
        $trabajo = new Trabajo();
        $trabajo->cliente_id = $cliente->id;
        $trabajo->titulo = $request->titulo;
        $trabajo->descripcion = $request->descripcion;
        $trabajo->precio = $request->precio;
        $trabajo->direccion = $request->direccion;
        $trabajo->estado_id = Estado::first()->id; // Asignar un estado por defecto
        $trabajo->fecha_limite = now()->addDays(7); // Establecer una fecha límite por defecto
        $trabajo->save();

        // Asignar las categorías al trabajo
        foreach ($request->categorias as $categoriaId) {
            CategoriaTipoTrabajo::create([
                'trabajo_id' => $trabajo->id,
                'categoria_id' => $categoriaId,
            ]);
        }

        // Subir las imágenes, si hay alguna
        if ($request->hasFile('imagenes')) {
            $imagenes = $request->file('imagenes');
            foreach ($imagenes as $imagen) {
                // Guardar la imagen y obtener la ruta
                $path = $imagen->store('trabajos', 'public');
                // Crear el registro en la tabla de imágenes del trabajo
                ImgTrabajo::create([
                    'ruta_imagen' => $path,
                    'trabajo_id' => $trabajo->id,
                    'descripcion' => '',
                ]);
            }
        }
        return redirect()->route('trabajos.publicados')->with('success', 'Trabajo creado exitosamente.');

    }

    public function trabajosPublicados()
    {
        $user = Auth::user();
        $trabajos = Trabajo::where('cliente_id', $user->id)->get();

        return view('trabajos_publicados', compact('trabajos'));
    }

}

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
        // Sólo traemos las categorías visibles
        $categorias = Categoria::where('visible', 'Sí')->get();
        $user = Auth::user(); // Obtener el usuario autenticado
        return view('crear_trabajo', compact('categorias', 'user'));
    }
    
    public function store(Request $request)
    {
        if ($request->has('categorias') && is_string($request->categorias)) {
            $request->merge([
                'categorias' => explode(',', $request->categorias)
            ]);
        }
        
        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'direccion' => 'required|string|max:255',
            'categorias' => 'required|array',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Permitir hasta 5 imágenes
            'alta_responsabilidad' => 'required|in:Sí,No',
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
        $trabajo->alta_responsabilidad = $request->input('alta_responsabilidad', 'No');
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
                if ($imagen) {
                    $filename = time() . '_' . $imagen->getClientOriginalName();
                    $imagen->move(public_path('img/trabajos'), $filename);
                    // Guardar solo el nombre del archivo, sin la ruta
        
                    ImgTrabajo::create([
                        'ruta_imagen' => $filename,
                        'trabajo_id' => $trabajo->id,
                        'descripcion' => '',
                    ]);
                }
            }
        }
        return redirect()->route('trabajos.publicados')->with('success', 'Trabajo creado exitosamente.');

    }

    public function trabajosPublicados()
    {
        $user = Auth::user();
        $trabajos = Trabajo::with('imagenes')->where('cliente_id', $user->id)->get();

        return view('trabajos_publicados', compact('trabajos'));
    }

    public function show($id)
    {
        $trabajo = Trabajo::with('imagenes', 'categoriastipotrabajo')->findOrFail($id);

        return view('detalles_trabajo', compact('trabajo'));
    }

    public function candidatos($id)
    {
        $trabajo = Trabajo::with('postulaciones.trabajador')->findOrFail($id);
        
        // Verificar que el trabajo pertenezca al usuario autenticado
        if ($trabajo->cliente_id != Auth::id()) {
            return redirect()->route('trabajos.publicados')->with('error', 'No tienes permiso para ver los candidatos de este trabajo.');
        }
        
        return view('candidatos_trabajo', compact('trabajo'));
    }

    public function editar($id)
    {
        $trabajo = Trabajo::with('imagenes')->findOrFail($id);
        $categorias = Categoria::all();
        return view('editar_trabajo', compact('trabajo', 'categorias'));
    }
    
    public function actualizar(Request $request)
    {
        if ($request->has('categorias') && is_string($request->categorias)) {
            $request->merge([
                'categorias' => explode(',', $request->categorias)
            ]);
        }
    
        $request->validate([
            'trabajo_id' => 'required|exists:trabajos,id',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'direccion' => 'required|string|max:255',
            'alta_responsabilidad' => 'required|in:Sí,No',
            'categorias' => 'required|array',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'imagenes_anteriores' => 'nullable|array',
        ]);
    
        $trabajo = Trabajo::with('imagenes')->findOrFail($request->trabajo_id);
    
        $trabajo->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'direccion' => $request->direccion,
            'alta_responsabilidad' => $request->alta_responsabilidad,
        ]);
    
        // Actualizar categorías
        CategoriaTipoTrabajo::where('trabajo_id', $trabajo->id)->delete();
        foreach ($request->categorias as $categoriaId) {
            CategoriaTipoTrabajo::create([
                'trabajo_id' => $trabajo->id,
                'categoria_id' => $categoriaId,
            ]);
        }
    
        // Procesar nuevas imágenes
        if ($request->hasFile('imagenes_nuevas')) {
            $imagenes = $request->file('imagenes_nuevas');
            foreach ($imagenes as $index => $imagen) {
                if ($imagen) {
                    // Reemplazar imagen existente si hay una en esa posición
                    $imagenAnt = $trabajo->imagenes[$index] ?? null;
    
                    if ($imagenAnt) {
                        // Borrar archivo anterior
                        $rutaAnterior = public_path('img/trabajos/' . $imagenAnt->ruta_imagen);
                        if (file_exists($rutaAnterior)) {
                            unlink($rutaAnterior);
                        }
                        // Reemplazar en BD
                        $filename = time() . '_' . $imagen->getClientOriginalName();
                        $imagen->move(public_path('img/trabajos'), $filename);
                        $imagenAnt->ruta_imagen = $filename;
                        $imagenAnt->save();
                    } else {
                        // Agregar una nueva imagen
                        $filename = time() . '_' . $imagen->getClientOriginalName();
                        $imagen->move(public_path('img/trabajos'), $filename);
                        ImgTrabajo::create([
                            'ruta_imagen' => $filename,
                            'trabajo_id' => $trabajo->id,
                            'descripcion' => '',
                        ]);
                    }
                }
            }
        }
    
        return redirect()->route('trabajos.publicados')->with('success', 'Trabajo actualizado correctamente.');
    }
                    
    public function eliminar($id)
    {
        // Buscar el trabajo por ID
        $trabajo = Trabajo::findOrFail($id);
    
        // Eliminar las relaciones en la tabla pivote 'categorias_tipo_trabajo'
        $trabajo->categoriastipotrabajo()->detach();
    
        // Eliminar las imágenes asociadas al trabajo
        $trabajo->imagenes()->delete();
    
        // Eliminar el trabajo
        $trabajo->delete();
    
        // Redirigir a la página de trabajos publicados con un mensaje de éxito
        return redirect()->route('trabajos.publicados')->with('success', 'Trabajo eliminado correctamente.');
    }
                            
}

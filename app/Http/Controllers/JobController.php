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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Pago;

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

    // Validación de los datos con mensajes personalizados
    $validator = Validator::make($request->all(), [
        'titulo' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'precio' => 'required|numeric|min:1|max:1000',
        'direccion' => 'required|digits:5', // Código postal: solo 5 dígitos exactos
        'categorias' => 'required|array',
        'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'alta_responsabilidad' => 'required|in:Sí,No',
    ], [
        'titulo.required' => 'Este campo es obligatorio',
        'descripcion.required' => 'Este campo es obligatorio',
        'precio.required' => 'Este campo es obligatorio',
        'precio.min' => 'El precio mínimo permitido es 1€',
        'precio.max' => 'El precio máximo permitido es de 1000€',
        'direccion.required' => 'Este campo es obligatorio',
        'direccion.digits' => 'El código postal debe contener 5 dígitos',
        'categorias.required' => 'Este campo es obligatorio',
        'alta_responsabilidad.required' => 'Este campo es obligatorio',
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
    $trabajo->estado_id = Estado::first()->id;
    $trabajo->fecha_limite = now()->addDays(7);
    $trabajo->alta_responsabilidad = $request->input('alta_responsabilidad', 'No');
    $trabajo->save();

    // Asignar categorías
    foreach ($request->categorias as $categoriaId) {
        CategoriaTipoTrabajo::create([
            'trabajo_id' => $trabajo->id,
            'categoria_id' => $categoriaId,
        ]);
    }

    // Guardar imágenes si existen
    if ($request->hasFile('imagenes')) {
        foreach ($request->file('imagenes') as $imagen) {
            if ($imagen) {
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

    return redirect()->route('trabajos.publicados')->with('success', 'Trabajo creado exitosamente.');
}


    public function trabajosPublicados()
    {
        $user = Auth::user();
        $trabajos = Trabajo::with(['imagenes', 'categoriastipotrabajo'])->where('cliente_id', $user->id)->get();
        $categorias = Categoria::where('visible', 'Sí')->get();

        return view('trabajos_publicados', compact('trabajos', 'categorias'));
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
        
        // Verificar si el trabajo está completado (tiene pago asociado)
        $trabajoCompletado = Pago::whereHas('postulacion', function($query) use ($trabajo) {
            $query->where('trabajo_id', $trabajo->id);
        })->exists();
        
        return view('candidatos_trabajo', compact('trabajo', 'trabajoCompletado'));
    }

    public function editar($id)
    {
        $trabajo = Trabajo::with('imagenes')->findOrFail($id);
        
        // Verificar si el trabajo está completado (tiene pago asociado)
        $trabajoCompletado = Pago::whereHas('postulacion', function($query) use ($trabajo) {
            $query->where('trabajo_id', $trabajo->id);
        })->exists();
        
        // Si el trabajo está completado, redirigir con mensaje de error
        if ($trabajoCompletado) {
            return redirect()->route('trabajos.publicados')
                ->with('error', 'No se puede editar un trabajo que ya ha sido completado y pagado.');
        }
        
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
        'precio' => 'required|numeric|min:1|max:1000',
        'direccion' => 'required|string|max:255|digits:5',
        'alta_responsabilidad' => 'required|in:Sí,No',
        'categorias' => 'required|array',
        'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'imagenes_anteriores' => 'nullable|array',
    ], [
        'titulo.required' => 'Este campo es obligatorio',
        'descripcion.required' => 'Este campo es obligatorio',
        'precio.required' => 'Este campo es obligatorio',
        'precio.min' => 'El precio mínimo permitido es 1€',
        'precio.max' => 'El precio máximo permitido es de 1000€',
        'direccion.required' => 'Este campo es obligatorio',
        'direccion.digits' => 'El código postal debe contener 5 dígitos',
        'alta_responsabilidad.required' => 'Este campo es obligatorio',
        'categorias.required' => 'Este campo es obligatorio',
    ]);
    
    $trabajo = Trabajo::with('imagenes')->findOrFail($request->trabajo_id);

    $trabajo->update([
        'titulo' => $request->titulo,
        'descripcion' => $request->descripcion,
        'precio' => $request->precio,
        'direccion' => $request->direccion,
        'alta_responsabilidad' => $request->alta_responsabilidad,
    ]);

    CategoriaTipoTrabajo::where('trabajo_id', $trabajo->id)->delete();
    foreach ($request->categorias as $categoriaId) {
        CategoriaTipoTrabajo::create([
            'trabajo_id' => $trabajo->id,
            'categoria_id' => $categoriaId,
        ]);
    }

    if ($request->hasFile('imagenes_nuevas')) {
        $imagenes = $request->file('imagenes_nuevas');
        foreach ($imagenes as $index => $imagen) {
            if ($imagen) {
                $imagenAnt = $trabajo->imagenes[$index] ?? null;

                if ($imagenAnt) {
                    $rutaAnterior = public_path('img/trabajos/' . $imagenAnt->ruta_imagen);
                    if (file_exists($rutaAnterior)) {
                        unlink($rutaAnterior);
                    }
                    $filename = time() . '_' . $imagen->getClientOriginalName();
                    $imagen->move(public_path('img/trabajos'), $filename);
                    $imagenAnt->ruta_imagen = $filename;
                    $imagenAnt->save();
                } else {
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
    DB::beginTransaction();

    try {
        $trabajo = Trabajo::findOrFail($id);

        DB::table('postulaciones')->where('trabajo_id', $trabajo->id)->delete();
        DB::table('calendario')->where('trabajo', $trabajo->id)->delete();
        DB::table('chats')->where('trabajo_id', $trabajo->id)->delete();

        $trabajo->imagenes()->delete();

        DB::table('categorias_tipo_trabajo')->where('trabajo_id', $trabajo->id)->delete();

        $trabajo->delete();

        DB::commit();
        return redirect()->route('trabajos.publicados')->with('success', 'Trabajo eliminado correctamente.');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al eliminar trabajo ID '.$id.': '.$e->getMessage());
        return redirect()->route('trabajos.publicados')->with('error', 'Error al eliminar el trabajo: '.$e);
    }
}
}
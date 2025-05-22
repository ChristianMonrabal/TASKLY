<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajo;
use App\Models\Categoria;
use App\Models\Postulacion;
use App\Models\Valoracion;
use Illuminate\Support\Facades\Auth;
use App\Models\Notificacion;
use App\Events\NewNotificacion;

class TrabajoController extends Controller
{
public function index()
{
    $categorias = Categoria::all();
    $user = Auth::user();

    $trabajosCercanos = [];

    if ($user && $user->codigo_postal) {
        $trabajosCercanos = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])
            ->where('direccion', $user->codigo_postal)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
    }

    return view('index', compact('categorias', 'user', 'trabajosCercanos'));
}


    // Los métodos filtrarPorCategoria() y buscar() han sido eliminados porque no se utilizan en la aplicación

    public function todos()
    {
        // Obtener solo los trabajos disponibles para postulación
        // Excluir trabajos completados (3), pagados (6), cancelados (4) u otros estados no disponibles
        $trabajos = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])
            ->whereNotIn('estado_id', [3, 4]) // Excluir solo completados y cancelados
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($trabajos);
    }

    public function nuevos()
    {
        // Obtener los 5 trabajos más recientes disponibles para postulación
        $nuevosTrabajos = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])
            ->whereNotIn('estado_id', [3, 4]) // Excluir solo completados y cancelados
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return response()->json($nuevosTrabajos);
    }

    public function categoriaJson($categoria_id)
    {
        $trabajos = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])
            ->whereHas('categoriastipotrabajo', function ($query) use ($categoria_id) {
                $query->where('categoria_id', $categoria_id);
            })
            ->whereNotIn('estado_id', [3, 4]) // Excluir solo completados y cancelados
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($trabajos);
    }

    public function buscarJson(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $trabajos = $this->buscarTrabajos($busqueda);

        return response()->json($trabajos);
    }

    private function buscarTrabajos($busqueda)
    {
        return Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])
            ->where(function ($query) use ($busqueda) {
                $query->where('titulo', 'LIKE', "%{$busqueda}%")
                    ->orWhere('descripcion', 'LIKE', "%{$busqueda}%")
                    ->orWhere('precio', 'LIKE', "%{$busqueda}%")
                    ->orWhereHas('categoriastipotrabajo', function ($q) use ($busqueda) {
                        $q->where('nombre', 'like', "%{$busqueda}%");
                    });
            })
            ->whereNotIn('estado_id', [3, 4]) // Excluir solo completados y cancelados
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // El método filtrar() ha sido eliminado porque no se utiliza en la aplicación

    public function filtrarSimple(Request $request)
    {
        $query = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])
            ->whereNotIn('estado_id', [3, 4]); // Excluir solo completados y cancelados

        // Filtrar por búsqueda (nombre o descripción)
        if ($request->has('busqueda') && !empty($request->busqueda)) {
            $busqueda = $request->busqueda;
            $query->where(function ($q) use ($busqueda) {
                $q->where('titulo', 'like', "%{$busqueda}%")
                    ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        // Filtrar por categorías
        if ($request->has('categorias') && !empty($request->categorias)) {
            $categorias = explode(',', $request->categorias);
            $query->whereHas('categoriastipotrabajo', function ($q) use ($categorias) {
                $q->whereIn('categoria_id', $categorias);
            });
        }
        
        // Filtrar por código postal
        if ($request->has('codigo_postal') && !empty($request->codigo_postal)) {
            $codigoPostal = $request->codigo_postal;
            $query->where('direccion', $codigoPostal);
        }

        // Ordenación por fecha de creación descendente
        $query->orderBy('created_at', 'desc');
        
        // Número de elementos por página (puede ser personalizado desde el request)
        $perPage = $request->has('per_page') ? $request->per_page : 10;
        
        // Aplicar paginación
        $trabajos = $query->paginate($perPage);
        
        return response()->json($trabajos);
    }

    public function mostrarDetalle($id)
    {
        $trabajo = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'estado', 'cliente', 'valoraciones', 'postulaciones'])->findOrFail($id);

        $yaPostulado = false;
        if (Auth::check()) {
            $yaPostulado = Postulacion::where('trabajo_id', $id)
                ->where('trabajador_id', Auth::id())
                ->exists();
        }

        $totalValoraciones = Valoracion::where('trabajo_id', $id)->count();

        return view('trabajo.detalle', compact('trabajo', 'yaPostulado', 'totalValoraciones'));
    }

    public function postular($id)
    {
        try {
            $usuario = Auth::user();

            if (!$usuario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes iniciar sesión para postularte'
                ]);
            }

            $trabajo = Trabajo::findOrFail($id);

            $isAlta = strtolower($trabajo->alta_responsabilidad) === 'sí';

            if ($isAlta) {
                $valoraciones = $usuario->valoracionesComoTrabajador;
                $total = $valoraciones->count();
                $media = $valoraciones->avg('puntuacion');

                if ($total < 20 || $media < 6) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este trabajo requiere al menos 20 valoraciones y una media mínima de 6.'
                    ]);
                }
            }

            $yaExiste = Postulacion::where('trabajo_id', $id)
                ->where('trabajador_id', $usuario->id)
                ->first();

            if ($yaExiste) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya te has postulado para este trabajo'
                ]);
            }

            $postulacion = new Postulacion();
            $postulacion->trabajo_id = $id;
            $postulacion->trabajador_id = $usuario->id;
            $postulacion->estado_id = 9;
            $postulacion->save();

            // Crear notificación
            $noti = Notificacion::create([
                'usuario_id'    => $trabajo->cliente_id,
                'mensaje'       => 'Nuevo postulante para tu trabajo "' . $trabajo->titulo . '"',
                'leido'         => false,
                'fecha_creacion'=> now(),
                'trabajo_id'    => $trabajo->id,
                'tipo'          => 'postulacion',
            ]);
            event(new NewNotificacion($noti));

            return response()->json([
                'success' => true,
                'message' => 'Postulación creada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la postulación: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancelarPostulacion($id)
    {
        try {
            $usuario = Auth::user();
    
            if (!$usuario) {
                return redirect()->back()->with('error', 'Debes iniciar sesión para cancelar tu postulación');
            }
    
            // Buscar la postulación existente
            $postulacion = Postulacion::where('trabajo_id', $id)
                ->where('trabajador_id', $usuario->id)
                ->first();
    
            if (!$postulacion) {
                return redirect()->back()->with('error', 'No te has postulado a este trabajo');
            }
    
            // Eliminar la postulación
            $postulacion->delete();
    
            return redirect()->back()->with('success', 'Postulación cancelada exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al cancelar la postulación: ' . $e->getMessage());
        }
    }
}

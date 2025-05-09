<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajo;
use App\Models\Categoria;
use App\Models\Postulacion;
use App\Models\Valoracion;
use Illuminate\Support\Facades\Auth;

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


    public function filtrarPorCategoria($categoria_id)
    {
        $categorias = Categoria::all();
        $categoriaActual = $categoria_id;

        return view('index', compact('categorias', 'categoriaActual'));
    }

    public function buscar(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $categorias = Categoria::all();

        return view('index', compact('categorias', 'busqueda'));
    }

    public function todos()
    {
        $trabajos = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])->orderBy('created_at', 'desc')->get();
        return response()->json($trabajos);
    }

    public function nuevos()
    {
        $nuevosTrabajos = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])->orderBy('created_at', 'desc')->take(5)->get();
        return response()->json($nuevosTrabajos);
    }

    public function categoriaJson($categoria_id)
    {
        $trabajos = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])
            ->whereHas('categoriastipotrabajo', function ($query) use ($categoria_id) {
                $query->where('categoria_id', $categoria_id);
            })
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
            ->where('titulo', 'LIKE', "%{$busqueda}%")
            ->orWhere('descripcion', 'LIKE', "%{$busqueda}%")
            ->orWhere('precio', 'LIKE', "%{$busqueda}%")
            ->orWhereHas('categoriastipotrabajo', function ($query) use ($busqueda) {
                $query->where('nombre', 'like', "%{$busqueda}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function filtrar(Request $request)
    {
        $query = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones']);

        if ($request->has('busqueda') && !empty($request->busqueda)) {
            $busqueda = $request->busqueda;
            $query->where(function ($q) use ($busqueda) {
                $q->where('titulo', 'like', "%{$busqueda}%")
                    ->orWhere('descripcion', 'like', "%{$busqueda}%")
                    ->orWhereHas('categoriastipotrabajo', function ($query) use ($busqueda) {
                        $query->where('nombre', 'like', "%{$busqueda}%");
                    });
            });
        }

        if ($request->has('categorias') && is_array($request->categorias) && count($request->categorias) > 0) {
            $query->whereHas('categoriastipotrabajo', function ($q) use ($request) {
                $q->whereIn('categoria_id', $request->categorias);
            });
        }

        if ($request->has('precio') && !empty($request->precio) && $request->precio != 'todos') {
            $rangoPrecio = explode('-', $request->precio);

            if (count($rangoPrecio) == 2) {
                $min = $rangoPrecio[0] !== '' ? floatval($rangoPrecio[0]) : null;
                $max = $rangoPrecio[1] !== '' ? floatval($rangoPrecio[1]) : null;

                if ($min !== null && $max !== null) {
                    $query->whereBetween('precio', [$min, $max]);
                } elseif ($min !== null) {
                    $query->where('precio', '>=', $min);
                } elseif ($max !== null) {
                    $query->where('precio', '<=', $max);
                }
            }
        }

        if ($request->has('ubicacion') && !empty($request->ubicacion) && $request->ubicacion != 'todos') {
            if ($request->ubicacion == 'remoto') {
                $query->where('es_remoto', true);
            }
        }

        if ($request->has('orden') && !empty($request->orden)) {
            switch ($request->orden) {
                case 'precio-asc':
                    $query->orderBy('precio', 'asc');
                    break;
                case 'precio-desc':
                    $query->orderBy('precio', 'desc');
                    break;
                case 'valoracion':
                    $query->orderBy('valoracion', 'desc');
                    break;
                case 'reciente':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $trabajos = $query->get();

        return response()->json($trabajos);
    }

    public function filtrarSimple(Request $request)
    {
        $query = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones']);

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

        $query->orderBy('created_at', 'desc');

        $trabajos = $query->get();

        return response()->json($trabajos);
    }

    public function mostrarDetalle($id)
    {
        $trabajo = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'estado', 'cliente', 'valoraciones'])->findOrFail($id);

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

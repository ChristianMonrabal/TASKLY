<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajo;
use App\Models\Categoria;
use App\Models\Postulacion;
use Illuminate\Support\Facades\Auth;

class TrabajoController extends Controller
{
    
    public function index(){
        // Obtener todas las categorías para los filtros
        $categorias = Categoria::all();
        $user = Auth::user();
        return view('index', compact('categorias', 'user'));
    }

    public function filtrarPorCategoria($categoria_id){
        $categorias = Categoria::all();
        $categoriaActual = $categoria_id;
        
        return view('index', compact('categorias', 'categoriaActual'));
    }

    public function buscar(Request $request){
        $busqueda = $request->input('busqueda');
        $categorias = Categoria::all();
        
        return view('index', compact('categorias', 'busqueda'));
    }
    
    // Método para obtener todos los trabajos en formato JSON (para fetch)
    public function todos(){
        $trabajos = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])->orderBy('created_at', 'desc')->get();
        return response()->json($trabajos);
    }
    
    // Método para obtener los nuevos trabajos en formato JSON (para fetch)
    public function nuevos(){
        $nuevosTrabajos = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])->orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($nuevosTrabajos);
    }
    
    // Método para obtener trabajos por categoría en formato JSON (para fetch)
    public function categoriaJson($categoria_id){
        $trabajos = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])
            ->whereHas('categoriastipotrabajo', function($query) use ($categoria_id) {
                $query->where('categoria_id', $categoria_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($trabajos);
    }
    
    /**
     * Buscar trabajos y devolver JSON
     */
    public function buscarJson(Request $request)
    {
        $busqueda = $request->input('busqueda');
        $trabajos = $this->buscarTrabajos($busqueda);
        
        return response()->json($trabajos);
    }
    
    /**
     * Método auxiliar para buscar trabajos
     */
    private function buscarTrabajos($busqueda)
    {
        return Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones'])
            ->where('titulo', 'LIKE', "%{$busqueda}%")
            ->orWhere('descripcion', 'LIKE', "%{$busqueda}%")
            ->orWhere('precio', 'LIKE', "%{$busqueda}%")
            ->orWhereHas('categoriastipotrabajo', function($query) use ($busqueda) {
                $query->where('nombre', 'like', "%{$busqueda}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Filtrar trabajos con múltiples criterios y devolver JSON
     */
    public function filtrar(Request $request)
    {
        $query = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'valoraciones']);
        
        // Filtro de búsqueda por texto
        if ($request->has('busqueda') && !empty($request->busqueda)) {
            $busqueda = $request->busqueda;
            $query->where(function($q) use ($busqueda) {
                $q->where('titulo', 'like', "%{$busqueda}%")
                  ->orWhere('descripcion', 'like', "%{$busqueda}%")
                  ->orWhereHas('categoriastipotrabajo', function($query) use ($busqueda) {
                      $query->where('nombre', 'like', "%{$busqueda}%");
                  });
            });
        }
        
        // Filtro por categorías (múltiples)
        if ($request->has('categorias') && is_array($request->categorias) && count($request->categorias) > 0) {
            $query->whereHas('categoriastipotrabajo', function($q) use ($request) {
                $q->whereIn('categoria_id', $request->categorias);
            });
        }
        
        // Filtro por precio
        if ($request->has('precio') && !empty($request->precio) && $request->precio != 'todos') {
            // Formato de precio: "min-max" o "min-" o "-max"
            $rangoPrecio = explode('-', $request->precio);
            
            if (count($rangoPrecio) == 2) {
                $min = $rangoPrecio[0] !== '' ? floatval($rangoPrecio[0]) : null;
                $max = $rangoPrecio[1] !== '' ? floatval($rangoPrecio[1]) : null;
                
                if ($min !== null && $max !== null) {
                    $query->whereBetween('precio', [$min, $max]);
                } else if ($min !== null) {
                    $query->where('precio', '>=', $min);
                } else if ($max !== null) {
                    $query->where('precio', '<=', $max);
                }
            }
        }
        
        // Filtro por ubicación
        if ($request->has('ubicacion') && !empty($request->ubicacion) && $request->ubicacion != 'todos') {
            // Implementar lógica para filtrar por ubicación
            // Por ejemplo, para trabajo remoto
            if ($request->ubicacion == 'remoto') {
                $query->where('es_remoto', true);
            }
            // Para trabajos en una distancia específica, se requiere implementar
            // una columna de latitud/longitud y usar cálculo de distancia
        }
        
        // Ordenar resultados
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
            // Por defecto, ordenar por más recientes
            $query->orderBy('created_at', 'desc');
        }
        
        $trabajos = $query->get();
        
        return response()->json($trabajos);
    }
    
    // Método para mostrar la vista de detalles de un trabajo
    public function mostrarDetalle($id){
        $trabajo = Trabajo::with(['categoriastipotrabajo', 'imagenes', 'estado', 'cliente', 'valoraciones'])->findOrFail($id);
        
        // Verificar si el usuario está autenticado y si ya se ha postulado
        $yaPostulado = false;
        if (Auth::check()) {
            $yaPostulado = Postulacion::where('trabajo_id', $id)
                ->where('trabajador_id', Auth::id())
                ->exists();
        }
        
        return view('trabajo.detalle', compact('trabajo', 'yaPostulado'));
    }
    
    // Método para postularse a un trabajo
    public function postular($id){
        try {
            // Suponiendo que tenemos un usuario autenticado
            // En un caso real se usaría Auth::id() para obtener el usuario actual
            $usuario_id = Auth::id() ?? 1; // Fallback a ID 1 si no hay usuario autenticado
            
            // Verificar si ya hay una postulación
            $postulacionExistente = Postulacion::where('trabajo_id', $id)
                ->where('trabajador_id', $usuario_id)
                ->first();
                
            if ($postulacionExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya te has postulado para este trabajo'
                ]);
            }
            
            // Crear nueva postulación
            $postulacion = new Postulacion();
            $postulacion->trabajo_id = $id;
            $postulacion->trabajador_id = $usuario_id;
            $postulacion->estado_id = 1; // Estado inicial (pendiente)
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
}
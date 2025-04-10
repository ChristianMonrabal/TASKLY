<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\pruebas;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\TrabajoController;
use App\Http\Controllers\Admin\ValoracionController;
use \App\Http\Controllers\Admin\CategoriaController;
use App\Models\Categoria;
use App\Models\User;
use App\Models\Valoracion;
use App\Models\Trabajo;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('welcome');
});

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('signin.auth');
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.auth');
Route::get('/', [pruebas::class, 'prueba']);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('trabajos', TrabajoController::class);
    Route::resource('valoraciones', ValoracionController::class)->parameters(['valoraciones' => 'valoracion']);
    Route::resource('categorias', CategoriaController::class);

});

// Rutas API (defínelas aquí si no usas routes/api.php)
Route::get('api/usuarios', function () {
    // Incluimos la relación "rol" para cada usuario
    return response()->json(User::with('rol')->get());
});
Route::get('api/usuarios/{id}', function ($id) {
    return response()->json(User::with('rol')->findOrFail($id));
});

// Endpoints API para obtener las valoraciones y una valoración individual
Route::get('api/valoraciones', function () {
    // Asegúrate de cargar las relaciones necesarias, p.ej. trabajo y trabajador
    return response()->json(Valoracion::with(['trabajo', 'trabajador'])->get());
});
Route::get('api/valoraciones/{id}', function ($id) {
    return response()->json(Valoracion::with(['trabajo', 'trabajador'])->findOrFail($id));
});

// Endpoint API para obtener la lista de trabajos y un trabajo individual (incluyendo relaciones, si lo deseas)
Route::get('api/trabajos', function () {
    // Se cargan las relaciones necesarias: 'cliente' y 'estado'
    return response()->json(Trabajo::with(['cliente', 'estado'])->get());
});
Route::get('api/trabajos/{id}', function ($id) {
    return response()->json(Trabajo::with(['cliente', 'estado'])->findOrFail($id));
});

// Endpoint para obtener usuarios con filtros (se usa para el filtrado dinámico)
Route::get('/usuarios', function (Request $request) {
    $query = User::query();
    if ($request->filled('nombre')) {
        $query->where('nombre', 'like', '%' . $request->nombre . '%');
    }
    if ($request->filled('apellidos')) {
        $query->where('apellidos', 'like', '%' . $request->apellidos . '%');
    }
    if ($request->filled('correo')) {
        $query->where('email', 'like', '%' . $request->correo . '%');
    }
    if ($request->filled('dni')) {
        $query->where('dni', 'like', '%' . $request->dni . '%');
    }
    if ($request->filled('codigo_postal')) {
        $query->where('codigo_postal', 'like', '%' . $request->codigo_postal . '%');
    }
    return response()->json($query->with('rol')->get());
});

Route::get('api/valoraciones', function (Illuminate\Http\Request $request) {
    $query = \App\Models\Valoracion::with(['trabajo.cliente', 'trabajador']);
    
    // Filtrar por nombre del trabajador
    if ($request->filled('trabajador')) {
        $query->whereHas('trabajador', function ($q) use ($request) {
            $q->where('nombre', 'like', '%' . $request->trabajador . '%');
        });
    }
    
    // Filtrar por nombre del cliente (relación a través de trabajo)
    if ($request->filled('cliente')) {
        $query->whereHas('trabajo.cliente', function ($q) use ($request) {
            $q->where('nombre', 'like', '%' . $request->cliente . '%');
        });
    }

    return response()->json($query->get());
});

Route::get('api/trabajos', function (Request $request) {
    $query = Trabajo::with(['cliente', 'estado']);
    
    // Filtrar por nombre del cliente (a través de la relación cliente)
    if ($request->filled('cliente')) {
        $query->whereHas('cliente', function ($q) use ($request) {
            $q->where('nombre', 'like', '%' . $request->cliente . '%');
        });
    }
    
    // Filtrar por estado (filtrando por el nombre del estado)
    if ($request->filled('estado')) {
        $query->whereHas('estado', function ($q) use ($request) {
            $q->where('nombre', 'like', '%' . $request->estado . '%');
        });
    }
    
    return response()->json($query->get());
});
Route::get('/api/estados/trabajos', function () {
    return response()->json(\App\Models\Estado::where('tipo_estado', 'trabajos')->get());
});

// Endpoint API para obtener todas las categorías.
Route::get('api/categorias', function () {
    // Si deseas agregar filtro por nombre y ordenamiento lo podrías hacer acá.
    $query = Categoria::query();
    if(request()->filled('nombre')){
        $query->where('nombre', 'like', '%' . request()->nombre . '%');
    }
    if(request()->filled('sort') && in_array(request()->sort, ['asc', 'desc'])){
        $query->orderBy('nombre', request()->sort);
    }
    return response()->json($query->get());
});
Route::get('api/categorias/{id}', function ($id) {
    return response()->json(Categoria::findOrFail($id));
});

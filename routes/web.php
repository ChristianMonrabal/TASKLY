<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ValoracionController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\AdminJobController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PostulacionController;
use App\Http\Controllers\PerfilUsuarioController;
use App\Models\Categoria;
use App\Models\User;
use App\Models\Valoracion;
use App\Models\Trabajo;
use App\Http\Controllers\CalendarioController;


// Ruta principal (index) - Accesible sin autenticación
Route::get('/', [TrabajoController::class, 'index'])->name('trabajos.index');

// Rutas de autenticación - Accesibles sin estar logueado
Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('signin.auth');
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.auth');
Route::post('/signup', [AuthController::class, 'register'])->name('signup.store');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/login', function () {
    return redirect('/signin');
})->name('login');
Route::get('/auth/redirect', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/google-callback', [GoogleController::class, 'handleGoogleCallback']);

// Rutas para ver trabajos sin autenticación
Route::get('/trabajos/todos', [TrabajoController::class, 'todos'])->name('trabajos.todos');
Route::get('/trabajos/nuevos', [TrabajoController::class, 'nuevos'])->name('trabajos.nuevos');
Route::get('/trabajos/categoria-json/{categoria_id}', [TrabajoController::class, 'categoriaJson'])->name('trabajos.categoria.json');
Route::get('/trabajos/buscar-json', [TrabajoController::class, 'buscarJson'])->name('trabajos.buscar.json');
Route::get('/trabajos/filtrar-simple', [TrabajoController::class, 'filtrarSimple'])->name('trabajos.filtrar.simple');
Route::get('/trabajos/categoria/{categoria_id}', [TrabajoController::class, 'filtrarPorCategoria'])->name('trabajos.categoria');
Route::get('/trabajos/buscar', [TrabajoController::class, 'buscar'])->name('trabajos.buscar');
Route::get('/trabajos/{id}', [TrabajoController::class, 'mostrarDetalle'])->name('trabajos.detalle');

// Todas las demás rutas requieren autenticación
Route::middleware('auth')->group(function () {
    // Rutas de perfil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Ruta para la página de mensajes
    Route::get('/mensajes', function () {return view('mensajes.index');})->name('mensajes');
    
    // Ruta para mostrar todos los usuarios
    Route::get('/usuarios', function () {
        $users = User::with('rol')->get();
        return view('usuarios.index', compact('users'));
    })->name('usuarios.index');
    
    // Rutas de trabajos protegidas
    Route::post('/trabajos/{id}/postular', [TrabajoController::class, 'postular'])->name('trabajos.postular');
    Route::get('/crear_trabajo', [JobController::class, 'crear'])->name('trabajos.crear');
    Route::post('/trabajos', [JobController::class, 'store'])->name('trabajos.store');
    Route::get('/trabajos_publicados', [JobController::class, 'trabajosPublicados'])->name('trabajos.publicados');
    Route::get('/trabajo/{id}', [JobController::class, 'show'])->name('trabajos.show');
    Route::get('/trabajos/crear', [JobController::class, 'crear'])->name('trabajos.create');
    Route::get('/detalles_trabajo/{id}', [JobController::class, 'show'])->name('trabajos.detalles');
    Route::get('/candidatos_trabajo/{id}', [JobController::class, 'candidatos'])->name('trabajos.candidatos');

    // Rutas para gestión de postulaciones/candidatos
    Route::post('/postulaciones/{id}/aceptar', [PostulacionController::class, 'aceptar'])->name('postulaciones.aceptar');
    Route::post('/postulaciones/{id}/rechazar', [PostulacionController::class, 'rechazar'])->name('postulaciones.rechazar');
    Route::get('/trabajo/{id}/postulaciones', [PostulacionController::class, 'estadoPostulaciones'])->name('trabajo.postulaciones');
    
    // Chat
    Route::controller(ChatController::class)->group(function () {
        Route::get('/chat', 'Vistachat')->name('vista.chat');
        Route::post('/cargamensajes', 'cargamensajes');
        Route::post('/enviomensaje', 'enviomensaje');
    });
    
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
    
    // Rutas de administración
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('trabajos', AdminJobController::class);
        Route::resource('valoraciones', ValoracionController::class)->parameters(['valoraciones' => 'valoracion']);
        Route::resource('categorias', CategoriaController::class);
    });
    // Calendario
    Route::get('/calendario', [CalendarioController::class, 'index'])->name('calendario.index');

    
    // Rutas API Admin
    // —— API para el CRUD Admin ——
// Usuarios:
Route::get('/usuarios',    [UsuarioController::class, 'apiIndex']);
Route::get('api/usuarios',    [UsuarioController::class, 'apiIndex']);
Route::get('api/usuarios/{usuario}', [UsuarioController::class, 'show']);
// Valoraciones:
Route::get('api/valoraciones',    [ValoracionController::class, 'apiIndex']);
Route::get('api/valoraciones/{valoracion}', [ValoracionController::class, 'show']);
// Trabajos:
Route::get('api/trabajos',    [AdminJobController::class, 'apiIndex']);
Route::get('api/trabajos/{trabajo}', [AdminJobController::class, 'show']);
Route::get('api/estados/trabajos', [AdminJobController::class, 'apiEstadosTrabajo']);
// Categorías:
Route::get('api/categorias',    [CategoriaController::class, 'apiIndex']);
Route::get('api/categorias/{categoria}', [CategoriaController::class, 'show']);

Route::get('/auth/redirect', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/google-callback', [GoogleController::class, 'handleGoogleCallback']);
});


Route::get('/footer/sobre_nosotros', function () {
    return view('/footer/sobre_nosotros');
});

// Route::get('/perfil/{id}', [PerfilUsuarioController::class, 'perfil'])->name('perfil.usuario');
Route::get('/perfil/{nombre}', [PerfilUsuarioController::class, 'perfilPorNombre'])->name('perfil.usuario');
Route::get('/perfil/{nombre}', [PerfilUsuarioController::class, 'mostrar'])->name('perfil.usuario');
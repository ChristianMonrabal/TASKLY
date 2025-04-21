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
use App\Models\Categoria;
use App\Models\User;
use App\Models\Valoracion;
use App\Models\Trabajo;


Route::get('/', [TrabajoController::class, 'index'])->name('trabajos.index');


Route::get('/trabajos/todos', [TrabajoController::class, 'todos'])->name('trabajos.todos');
Route::get('/trabajos/nuevos', [TrabajoController::class, 'nuevos'])->name('trabajos.nuevos');
Route::get('/trabajos/categoria-json/{categoria_id}', [TrabajoController::class, 'categoriaJson'])->name('trabajos.categoria.json');
Route::get('/trabajos/buscar-json', [TrabajoController::class, 'buscarJson'])->name('trabajos.buscar.json');
Route::get('/trabajos/filtrar', [TrabajoController::class, 'filtrar'])->name('trabajos.filtrar');
Route::get('/trabajos/categoria/{categoria_id}', [TrabajoController::class, 'filtrarPorCategoria'])->name('trabajos.categoria');
Route::get('/trabajos/buscar', [TrabajoController::class, 'buscar'])->name('trabajos.buscar');

// Ruta para ver detalles del trabajo (nuevo formato)
Route::get('/view/trabajo/detalle/{id}', [TrabajoController::class, 'mostrarDetalle'])->name('view.trabajo.detalle');

// Esta ruta debe ir AL FINAL porque captura cualquier /trabajos/{id}
Route::get('/trabajos/{id}', [TrabajoController::class, 'mostrarDetalle'])->name('trabajos.detalle');

// Ruta para postularse a un trabajo (POST)
Route::post('/trabajos/{id}/postular', [TrabajoController::class, 'postular'])->name('trabajos.postular');


Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('signin.auth');
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.auth');
Route::post('/signup', [AuthController::class, 'register'])->name('signup.store');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/profile', [ProfileController::class, 'show'])->name('profile')->middleware('auth');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

// Ruta para la página de mensajes
Route::get('/mensajes', function () {return view('mensajes.index');})->name('mensajes')->middleware('auth');


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// Agrupamos TODO admin bajo auth
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('trabajos', AdminJobController::class);
    Route::resource('valoraciones', ValoracionController::class)->parameters(['valoraciones' => 'valoracion']);
    Route::resource('categorias', CategoriaController::class);
});

// Rutas API Admin:
Route::get('/usuarios',       [UsuarioController::class,   'apiIndex']);
Route::get('api/usuarios',    [UsuarioController::class,   'apiIndex']);
Route::get('api/usuarios/{id}', [UsuarioController::class, 'show']);

Route::get('api/valoraciones',    [ValoracionController::class, 'apiIndex']);
Route::get('api/valoraciones/{id}', [ValoracionController::class, 'show']);

Route::get('api/trabajos',    [AdminJobController::class, 'apiIndex']);
Route::get('api/trabajos/{id}', [AdminJobController::class, 'show']);

Route::get('/api/estados/trabajos', [AdminJobController::class, 'apiEstadosTrabajo']);

Route::get('api/categorias',    [CategoriaController::class, 'apiIndex']);
Route::get('api/categorias/{id}', [CategoriaController::class, 'show']);

Route::get('/auth/redirect', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/google-callback', [GoogleController::class, 'handleGoogleCallback']);

Route::controller(ChatController::class)->group(function () {
    Route::get('/chat', 'Vistachat')->name('vista.chat');
    // Route::get('/chat/{id}', 'Vistachat')->name('chat.index');
    Route::post('/cargamensajes', 'cargamensajes');
    Route::post('/enviomensaje', 'enviomensaje');
    // Route::post('/editarassignar', 'editarassignar');
    // Route::post('/editarprioridad', 'editarprioridad');
});


Route::get('/crear_trabajo', [JobController::class, 'crear'])->name('trabajos.crear');
Route::post('/trabajos', [JobController::class, 'store'])->name('trabajos.store');
Route::get('/trabajos_publicados', function () {
    return view('trabajos_publicados');
})->name('trabajos.publicados');
Route::get('/trabajos_publicados', [JobController::class, 'trabajosPublicados'])->name('trabajos.publicados');
Route::get('/trabajo/{id}', [JobController::class, 'show'])->name('trabajos.show');
Route::get('/trabajos/crear', [JobController::class, 'crear'])->name('trabajos.create');
// Ruta para ver los detalles del trabajo
Route::get('/detalles_trabajo/{id}', [JobController::class, 'show'])->name('trabajos.detalles');
Route::get('/candidatos_trabajo/{id}', [JobController::class, 'candidatos'])->name('trabajos.candidatos');
Route::get('trabajos/crear', [JobController::class, 'create'])->name('trabajos.create');
Route::get('/trabajos_publicados', [JobController::class, 'trabajosPublicados'])
    ->middleware('auth')
    ->name('trabajos.publicados');

Route::get('/login', function () {
    return redirect('/signin');
})->name('login');


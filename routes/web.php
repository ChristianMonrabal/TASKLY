<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\ValoracionController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\AdminJobController;
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
    return redirect('/signin');
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

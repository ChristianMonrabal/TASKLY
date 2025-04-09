<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\Auth\GoogleController;
Route::get('/', [TrabajoController::class, 'index'])->name('trabajos.index');


// Rutas para fetch directo (sin API) - DEBEN IR ANTES DE LAS RUTAS CON COMODINES
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


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/signin');
})->name('logout');

// use Laravel\Socialite\Facades\Socialite;

// Route::get('/auth/redirect', function () {
//     return Socialite::driver('google')->redirect();
// })->name('login.google');

// Route::get('/google-callback', function () {
//     $user = Socialite::driver('google')->user();
//     dd($user);
//     // $user->token
// });


Route::get('/auth/redirect', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/google-callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/crear_trabajo', [JobController::class, 'crear'])->name('trabajos.crear');
Route::post('/trabajos', [JobController::class, 'store'])->name('trabajos.store');
Route::get('/trabajos_publicados', function () {
    return view('trabajos_publicados');
})->name('trabajos.publicados');
Route::get('/trabajos_publicados', [JobController::class, 'trabajosPublicados'])->name('trabajos.publicados');
Route::get('/trabajo/{id}', [JobController::class, 'show'])->name('trabajos.show');
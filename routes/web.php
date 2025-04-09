<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\JobController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('signin.auth');
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.auth');
Route::post('/signup', [AuthController::class, 'register'])->name('signup.store');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/signin');
})->name('logout');

Route::get('/crear_trabajo', [JobController::class, 'crear'])->name('trabajos.crear');
Route::post('/trabajos', [JobController::class, 'store'])->name('trabajos.store');
Route::get('/trabajos_publicados', function () {
    return view('trabajos_publicados');
})->name('trabajos.publicados');
Route::get('/trabajos_publicados', [JobController::class, 'trabajosPublicados'])->name('trabajos.publicados');
Route::get('/trabajo/{id}', [JobController::class, 'show'])->name('trabajos.show');
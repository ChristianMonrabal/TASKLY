<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\pruebas;

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

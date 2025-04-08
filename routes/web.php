<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [pruebas::class, 'prueba']);

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/signin', [AuthController::class, 'showLoginForm'])->name('signin.auth');
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup.auth');
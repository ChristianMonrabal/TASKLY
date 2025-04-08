<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pruebas;

// Ruta principal que carga la vista "welcome"
Route::get('/', function () {
    return view('welcome');
});

// Ruta aparte para tu prueba
Route::get('/prueba', [Pruebas::class, 'prueba']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pruebas;
use App\Http\Controllers\TrabajoController;

// Ruta principal que ahora lleva a la página de trabajos
Route::get('/', [TrabajoController::class, 'index'])->name('trabajos.index');

// Rutas para las vistas
Route::get('/trabajos', [TrabajoController::class, 'index'])->name('trabajos.index');

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

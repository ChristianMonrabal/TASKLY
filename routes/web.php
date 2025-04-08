<?php

use Illuminate\Support\Facades\Route;

// Ruta para mostrar la página de crear trabajo
Route::get('/crear-trabajo', function () {
    return view('crear_trabajo');
});

Route::get('/', function () {
    return view('welcome');
});

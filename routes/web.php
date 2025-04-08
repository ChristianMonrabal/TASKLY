<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pruebas;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [pruebas::class, 'prueba']);

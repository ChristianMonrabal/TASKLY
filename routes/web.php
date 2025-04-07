<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrabajoController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/trabajo', [TrabajoController::class, 'index'])->name('trabajo.index');

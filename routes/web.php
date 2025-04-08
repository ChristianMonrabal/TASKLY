<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');

Route::get('/', [AuthController::class, 'showLoginForm'])->name('signin.auth');
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

use App\Http\Controllers\Auth\GoogleController;

Route::get('/auth/redirect', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/google-callback', [GoogleController::class, 'handleGoogleCallback']);

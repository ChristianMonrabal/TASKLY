<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {
                Auth::login($finduser);
                return redirect()->intended('welcome');
            } else {
                $newUser = User::create([
                    'nombre' => $user->user['given_name'],
                    'apellidos' => $user->user['family_name'],
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => encrypt('123456dummy'),
                    'dni' => 'null',
                    'rol_id' => 2,
                ]);

                Auth::login($newUser);
                return redirect()->intended('profile');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}

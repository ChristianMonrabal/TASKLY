<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.signin');
    }

    public function showSignupForm()
    {
        return view('auth.signup');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('welcome')->with('success', 'Inicio de sesión exitoso');
        }
    
        return redirect()->route('signin.auth')
            ->withErrors([
                'email' => 'Las credenciales no son válidas.',
            ])
            ->withInput();
    }

    
}


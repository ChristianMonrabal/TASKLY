<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;

class PasswordRestartController extends Controller
{
    public function showForm(Request $request)
    {
        $email = $request->query('email');
        return view('passwords.password_restart', compact('email'));
    }

    public function handleForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => [
                'required',
                'min:8',
                'confirmed'
            ],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Introduce un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No se encontró un usuario con ese correo electrónico.'])->withInput();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('status', 'Contraseña actualizada correctamente.');
    }

    public function showForgotForm()
{
    return view('auth.forgot_password');
}

    public function sendResetLink(Request $request)
    {
        $request->validate(
            ['email' => 'required|email'],
            [
                'email.required' => 'Este campo es obligatorio',
                'email.email' => 'Introduce un correo electrónico válido',
            ]
        );

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No hay ninguna cuenta con ese correo.']);
        }

        $resetUrl = URL::temporarySignedRoute(
            'password.restart',
            now()->addMinutes(60),
            ['email' => $user->email]
        );

        Mail::to($user->email)->send(new \App\Mail\ResetPasswordLink($user, $resetUrl));

        return back()->with('status', 'Te hemos enviado un correo con el enlace para restablecer tu contraseña.');
    }

}

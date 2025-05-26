<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoMail;

class ContactoController extends Controller
{
    public function mostrarFormulario()
    {
        return view('contacto.formulario');
    }

    public function enviarContacto(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'asunto' => 'required|string|max:100',
            'mensaje' => 'required|string'
        ]);

        // Enviar el correo electrónico
        Mail::to(config('mail.from.address'))->send(new ContactoMail($request->all()));

        return back()->with('success', 'Tu mensaje ha sido enviado. Gracias por contactarnos.');
    }
}

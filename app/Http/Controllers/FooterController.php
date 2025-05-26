<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function terminosServicio()
    {
        return view('footer.terminos_servicio');
    }

    public function politicaPrivacidad()
    {
        return view('footer.politica_privacidad');
    }

    public function cookies()
    {
        return view('footer.cookies');
    }

    public function sobreNosotros()
    {
        return view('footer.sobre_nosotros');
    }

    public function comoFunciona()
    {
        return view('footer.como_funciona');
    }
    
    public function freelancers()
    {
        return view('footer.freelancers');
    }
}

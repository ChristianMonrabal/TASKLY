<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;
use App\Models\Trabajo;
use App\Models\Logro;
use App\Models\Chat;
use App\Models\Postulacion;
use App\Models\Valoracion;
use App\Models\Pago;

class pruebas extends Controller
{
    //
    public function prueba()
    {
        // user + rol
        $user = user::with('rol')->get();
        // user + rol + codigosDescuento 
        $user = user::with('codigosDescuento')->with('rol')->get();
        // user + rol + codigosDescuento + datosBancarios
        $user = user::with('codigosDescuento')->with('datosBancarios')->with('rol')->get();
        // user + rol + codigosDescuento + datosBancarios + habilidades
        $user = user::with('rol')->with('codigosDescuento')->with('datosBancarios')->with('habilidades')->get();
        // user + rol + codigosDescuento + datosBancarios + habilidades + trabajosComoCliente
        $user = user::with('rol')->with('codigosDescuento')->with('datosBancarios')->with('habilidades')
            ->with('trabajosComoCliente')->get();
        // user + rol + codigosDescuento + datosBancarios + habilidades + trabajosComoCliente + postulaciones
        $user = user::with('rol')->with('codigosDescuento')->with('datosBancarios')->with('habilidades')
            ->with('trabajosComoCliente')->with('postulaciones')->get();

        // return $user;

        // Trabajo + cliente
        $Trabajo = Trabajo::with('cliente')->get();
        // Trabajo + cliente + estado
        $Trabajo = Trabajo::with('cliente')->with('estado')->get();
        // Trabajo + cliente + estado + categoriastipotrabajo
        $Trabajo = Trabajo::with('cliente')->with('estado')->with('categoriastipotrabajo')->get();
        // Trabajo + cliente + estado + categoriastipotrabajo + postulaciones
        $Trabajo = Trabajo::with('cliente')->with('estado')->with('categoriastipotrabajo')->with('postulaciones')->get();
        // Trabajo + cliente + estado + categoriastipotrabajo + postulaciones + imagenes
        $Trabajo = Trabajo::with('cliente')->with('estado')->with('categoriastipotrabajo')->with('postulaciones')
            ->with('imagenes')->get();
        // Trabajo + cliente + estado + categoriastipotrabajo + postulaciones + imagenes + pagos
        $Trabajo = Trabajo::with('cliente')->with('estado')->with('categoriastipotrabajo')->with('postulaciones')
            ->with('imagenes')->with('pagos')->get();
        // Trabajo + cliente + estado + categoriastipotrabajo + postulaciones + imagenes + pagos + valoraciones
        $Trabajo = Trabajo::with('cliente')->with('estado')->with('categoriastipotrabajo')->with('postulaciones')
            ->with('imagenes')->with('pagos')->with('valoraciones')->get();
        // Trabajo + cliente + estado + categoriastipotrabajo + postulaciones + imagenes + pagos + valoraciones + chat
        $Trabajo = Trabajo::with('cliente')->with('estado')->with('categoriastipotrabajo')->with('postulaciones')
            ->with('imagenes')->with('pagos')->with('valoraciones')->with('chat')->get();
        return $Trabajo;
    }
}

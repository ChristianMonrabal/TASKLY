<?php

namespace App\Http\Controllers;
use App\Models\calendario;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $eventos = calendario::where('cliente', $userId)
            ->orWhere('trabajador', $userId)
            ->get()
            ->map(function ($evento) {
                return [
                    'date' => $evento->fecha,
                    'text' => $evento->titulo . ': ' . $evento->descripcion,
                    'type' => 'work',
                ];
            });
    
        return view('calendario.calendario', [
            'eventos' => $eventos,
        ]);
    }
    
}

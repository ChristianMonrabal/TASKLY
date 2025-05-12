<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\User;

class ReporteController extends Controller
{
    public function index($user_id)
    {
        $usuarioReportado = User::findOrFail($user_id);

        return view('report.reportes', compact('usuarioReportado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'motivo' => 'required|string|max:500',
        ]);

        // Crear el reporte en la base de datos
        // Reporte::create([
        //     'reportado_por' => auth()->user()->id, // ID del usuario autenticado
        //     'usuario_reportado' => $request->user_id, // El usuario que se está reportando
        //     'motivo' => $request->motivo,
        // ]);

        return redirect()->route('reportes.index', ['user_id' => $request->user_id])
            ->with('success', 'El reporte ha sido enviado a nuestro administrador.');
    }
}

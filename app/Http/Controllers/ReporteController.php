<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\reportes;
use App\Models\User;
use App\Models\Estado;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
public function index($user_id)
{
    $usuarioReportado = User::findOrFail($user_id);

    $nivelesGravedad = Estado::whereIn('id', [12, 13, 14])->get();

    return view('report.reportes', compact('usuarioReportado', 'nivelesGravedad'));
}

public function store(Request $request)
{
    $request->validate([
        'motivo' => 'required|string|max:500',
        'gravedad' => 'required|in:12,13,14',
    ], [
        'motivo.required' => 'El motivo no puede estar vacío.',
        'motivo.string' => 'El motivo debe ser un texto válido.',
        'motivo.max' => 'El motivo no puede tener más de 500 caracteres.',
        'gravedad.required' => 'El nivel de gravedad es obligatorio.',
        'gravedad.in' => 'Seleccione un nivel de gravedad válido.',
    ]);

    reportes::create([
        'motivo' => $request->motivo,
        'id_usuario' => $request->user_id,
        'gravedad' => $request->gravedad,
        'estado' => 1,
        'reportado_Por' => Auth::id(),
    ]);

    return redirect()->route('reportes.index', ['user_id' => $request->user_id])
        ->with('success', 'El reporte ha sido enviado a nuestro administrador.');
}


    public function listareportes()
    {
        $reportes = reportes::with([
            'usuarioReportado:id,nombre',
            'reportadoPor:id,nombre',
            'nivelGravedad:id,nombre',
            'estadoReporte:id,nombre'
        ])->get();

        $reportes->each(function ($reporte) {
            $reporte->total_reportes_usuario = reportes::where('id_usuario', $reporte->id_usuario)->count();
        });

        return response()->json($reportes);
    }
}

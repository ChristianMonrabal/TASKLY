<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\reportes;
use App\Models\Estado;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    /** Muestra la página de administración (HTML) */
    public function index()
    {
        if (Auth::user()->rol_id !== 1) {
            return redirect()->route('trabajos.index');
        }

        $niveles = Estado::where('tipo_estado', 'reporte_gravedad')->get();
        $estados = Estado::where('tipo_estado', 'reporte_estado')->get();

        return view('Admin.reportes.index', compact('niveles', 'estados'));
    }

    /** Devuelve JSON paginado y filtrado */
    public function json(Request $request)
    {
        if (Auth::user()->rol_id !== 1) abort(403);

        $q = reportes::with(['usuarioReportado','reportadoPor','nivelGravedad','estadoReporte']);

        if ($request->filled('motivo'))   $q->where('motivo', 'like', '%'.$request->motivo.'%');
        if ($request->filled('gravedad')) $q->where('gravedad', $request->gravedad);
        if ($request->filled('estado'))   $q->where('estado',   $request->estado);

        $reportes = $q->orderByDesc('created_at')->paginate(10);

        return response()->json($reportes);
    }

    /** Devuelve un solo reporte (JSON) */
    public function show($id)
    {
        if (Auth::user()->rol_id !== 1) abort(403);

        $r = reportes::with(['usuarioReportado','reportadoPor','nivelGravedad','estadoReporte'])
                     ->findOrFail($id);

        return response()->json($r);
    }

    /** Actualiza sólo el campo estado (JSON) */
    public function update(Request $request, $id)
    {
        if (Auth::user()->rol_id !== 1) abort(403);

        $request->validate([
            'estado' => 'required|exists:estados,id',
        ]);

        $r = reportes::findOrFail($id);
        $r->estado = $request->estado;
        $r->save();

        return response()->json(['message' => 'Estado actualizado.']);
    }

    /** Elimina el reporte (JSON) */
    public function destroy($id)
    {
        if (Auth::user()->rol_id !== 1) abort(403);

        $r = reportes::findOrFail($id);
        $r->delete();

        return response()->json(['message' => 'Reporte eliminado.']);
    }
}

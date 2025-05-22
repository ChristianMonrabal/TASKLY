<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Valoracion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ValoracionController extends Controller
{
    public function index()
    {
        if (Auth::user()->rol_id != 1) {
            return redirect()->route('trabajos.index');
        }
        return view('Admin.valoraciones.index');
    }

    /**
     * Devuelve la valoración con la URL pública correcta en img_url.
     */
    public function show(Valoracion $valoracion)
    {
        // Aseguramos que img_valoracion incluye la carpeta correcta
        $path = $valoracion->img_valoracion;
        if ($path && ! preg_match('#^img/valoraciones/#', $path)) {
            $path = 'img/valoraciones/' . ltrim($path, '/');
        }

        return response()->json([
            'id'           => $valoracion->id,
            'trabajo_id'   => $valoracion->trabajo_id,
            'trabajador_id'=> $valoracion->trabajador_id,
            'puntuacion'   => $valoracion->puntuacion,
            'comentario'   => $valoracion->comentario,
            // URL absoluta para <img src="...">
            'img_url'      => $path ? url($path) : null,
        ]);
    }

    public function update(Request $request, Valoracion $valoracion)
    {
        $validated = $request->validate([
            'comentario'            => 'nullable|string',
            'img_valoracion'        => 'nullable|image|max:2048',
            'remove_img_valoracion' => 'nullable|boolean',
        ]);

        // Si quitan la foto existente
        if ($request->filled('remove_img_valoracion') && $valoracion->img_valoracion) {
            $old = public_path($valoracion->img_valoracion);
            if (File::exists($old)) File::delete($old);
            $validated['img_valoracion'] = null;
        }
        // Si envían nueva imagen
        elseif ($request->hasFile('img_valoracion')) {
            $file     = $request->file('img_valoracion');
            $filename = time() . '_' . $file->getClientOriginalName();
            $dest     = public_path('img/valoraciones');
            if (! File::isDirectory($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $file->move($dest, $filename);
            // Guardamos la ruta completa relativa a public/
            $validated['img_valoracion'] = "img/valoraciones/{$filename}";
        }

        $valoracion->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Valoración actualizada correctamente.',
                'data'    => $valoracion,
            ]);
        }

        return redirect()
            ->route('admin.valoraciones.index')
            ->with('success', 'Valoración actualizada correctamente.');
    }

    public function apiIndex(Request $request)
    {
        $query = Valoracion::with(['trabajo.cliente', 'trabajador']);

        if ($request->filled('trabajador')) {
            $query->whereHas('trabajador', fn($q) =>
                $q->where('nombre', 'like', "%{$request->trabajador}%")
            );
        }
        if ($request->filled('cliente')) {
            $query->whereHas('trabajo.cliente', fn($q) =>
                $q->where('nombre', 'like', "%{$request->cliente}%")
            );
        }

        return $query->orderByDesc('created_at')->paginate(10);
    }

    public function destroy(Valoracion $valoracion)
    {
        try {
            DB::beginTransaction();
            $valoracion->delete();
            DB::commit();

            if (request()->wantsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()
                ->route('admin.valoraciones.index')
                ->with('success', 'Valoración eliminada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

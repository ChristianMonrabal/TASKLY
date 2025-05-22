<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LogroController extends Controller
{
    public function index()
    {
        return view('Admin.logros.index');
    }

    /**
     * Listado JSON de logros (con foto_url añadido).
     */
    public function apiIndex(Request $request)
    {
        $query = Logro::query();
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', "%{$request->nombre}%");
        }

        $paginator = $query->orderBy('nombre')->paginate(10);

        // Añadimos foto_url a cada logro
        $paginator->getCollection()->transform(function (Logro $l) {
            return array_merge($l->toArray(), [
                'foto_url' => $l->foto_logro
                    ? url($l->foto_logro)
                    : null,
            ]);
        });

        return $paginator;
    }

    /**
     * Devuelve un solo logro con foto_url.
     */
    public function show(Logro $logro)
    {
        return response()->json([
            'id'           => $logro->id,
            'nombre'       => $logro->nombre,
            'descripcion'  => $logro->descripcion,
            'foto_url'     => $logro->foto_logro
                ? url($logro->foto_logro)
                : null,
        ]);
    }

    /**
     * Crea un nuevo logro con imagen obligatoria.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:100|unique:logros,nombre',
            'descripcion' => 'nullable|string',
            'foto_logro'  => 'required|image|max:2048',
        ]);

        if ($request->hasFile('foto_logro')) {
            $file       = $request->file('foto_logro');
            // Nombre original sin extensión
            $name       = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // Slug (quita espacios/caracteres extraños)
            $slug       = Str::slug($name);
            $ext        = $file->getClientOriginalExtension();
            $filename   = time() . "_{$slug}.{$ext}";

            $dest = public_path('img/insignias');
            if (! File::isDirectory($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $file->move($dest, $filename);

            $validated['foto_logro'] = "img/insignias/{$filename}";
        }

        $logro = Logro::create($validated);

        return response()->json([
            'success' => true, 'data' => $logro
        ], 201);
    }

    public function update(Request $request, Logro $logro)
    {
        $validated = $request->validate([
            'nombre'             => "required|string|max:100|unique:logros,nombre,{$logro->id}",
            'descripcion'        => 'nullable|string',
            'foto_logro'         => 'nullable|image|max:2048',
            'remove_foto_logro'  => 'nullable|boolean',
        ]);

        // Eliminar foto antigua
        if ($request->boolean('remove_foto_logro') && $logro->foto_logro) {
            $old = public_path($logro->foto_logro);
            if (File::exists($old)) {
                File::delete($old);
            }
            // lo dejamos en null para que en DB se borre la ruta
            $validated['foto_logro'] = null;
        }
        // Subir nueva foto con slug
        elseif ($request->hasFile('foto_logro')) {
            $file     = $request->file('foto_logro');
            $name     = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $slug     = Str::slug($name);
            $ext      = $file->getClientOriginalExtension();
            $filename = time() . "_{$slug}.{$ext}";

            $dest = public_path('img/insignias');
            if (! File::isDirectory($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $file->move($dest, $filename);

            // borrar antigua
            if ($logro->foto_logro) {
                $old = public_path($logro->foto_logro);
                if (File::exists($old)) File::delete($old);
            }

            $validated['foto_logro'] = "img/insignias/{$filename}";
        }

        $logro->update($validated);

        return response()->json([
            'success' => true, 'data' => $logro
        ]);
    }

    /**
     * Elimina un logro (si no hay dependientes).
     */
    public function destroy(Logro $logro)
    {
        if ($logro->logrosCompletos()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el logro porque hay registros asociados.'
            ], 400);
        }

        try {
            // opcional: borrar foto
            if ($logro->foto_logro) {
                $old = public_path($logro->foto_logro);
                if (File::exists($old)) {
                    File::delete($old);
                }
            }

            $logro->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logro eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el logro: ' . $e->getMessage()
            ], 500);
        }
    }
}

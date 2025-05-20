<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trabajo;
use App\Models\Pago;
use App\Models\Chat;
use App\Models\Postulacion;
use App\Models\Valoracion;
use App\Models\ImgTrabajo;
use App\Models\DatosBancarios;
use App\Models\Notificacion;
use App\Models\CategoriaTipoTrabajo;
use App\Models\LogroCompleto;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Habilidad;
use Illuminate\Support\Facades\Auth;
use App\Models\Categoria;
use App\Events\NewNotificacion;





class UsuarioController extends Controller
{
    /**
     * Muestra la vista principal (index) donde se listan los usuarios.
     * La vista se encargará de obtener los datos mediante fetch.
     */
    public function index()
    {
        if (Auth::user()->rol_id != 1) {
            return redirect()->route('trabajos.index');
        }
        return view('Admin.usuarios.index');
    }
    
    /**
     * Devuelve los datos de un usuario en formato JSON.
     * Útil para cargar los datos en el modal de edición.
     */
    public function show(User $usuario)
    {
        return response()->json($usuario);
    }
    
    /**
     * Actualiza la información de un usuario en la base de datos.
     * Se incluyen ahora los campos 'descripcion' y 'foto_perfil'.
     */
    public function update(Request $request, User $usuario)
    {
        // Reglas básicas para los campos
        $rules = [
            'nombre'           => 'required|string|max:255',
            'apellidos'        => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,'.$usuario->id,
            'telefono'         => 'nullable|string|max:20|unique:users,telefono,'.$usuario->id,
            'codigo_postal'    => 'nullable|string|max:10',
            'fecha_nacimiento' => 'nullable|date',
            'dni'              => 'nullable|string|unique:users,dni,'.$usuario->id,
            'descripcion'      => 'nullable|string',
            'foto_perfil'      => 'nullable|image|max:2048',
        ];
        
        // Si se ingresa un nuevo password, agregar las reglas para validarlo.
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }
    
        $validated = $request->validate($rules);
    
        // Encriptar el nuevo password si se proporcionó.
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        }
    
        // Actualizar el usuario.
        $usuario->update($validated);
    
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente.',
                'data'    => $usuario
            ]);
        }
    
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }
    
    /**
     * Devuelve los usuarios (con filtro dinámico) en JSON.
     * Antes estabas en web.php; ahora aquí.
     */
    public function apiIndex(Request $request)
    {
        $query = User::with('rol');

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', "%{$request->nombre}%");
        }
        if ($request->filled('apellidos')) {
            $query->where('apellidos', 'like', "%{$request->apellidos}%");
        }
        if ($request->filled('correo')) {
            $query->where('email', 'like', "%{$request->correo}%");
        }
        if ($request->filled('dni')) {
            $query->where('dni', 'like', "%{$request->dni}%");
        }
        if ($request->filled('codigo_postal')) {
            $query->where('codigo_postal', 'like', "%{$request->codigo_postal}%");
        }

        // Paginamos 10 por página y devolvemos sus datos + links
        $paginated = $query->orderBy('id', 'desc')->paginate(10);

        // Retornamos tal cual la colección de Laravel, que incluye:
        // data, current_page, last_page, links[], etc.
        return response()->json($paginated);
    }

    /**
     * Elimina un usuario y todas las relaciones dependientes usando una transacción.
     * Utilizando modelos estáticos sin bucles.
     */
    public function destroy(User $usuario)
    {
        try {
            DB::beginTransaction();
            
            // 1. Eliminar trabajos como cliente y sus relaciones
            $trabajosIds = $usuario->trabajosComoCliente()->pluck('id')->toArray();
            
            if (!empty($trabajosIds)) {
                CategoriaTipoTrabajo::whereIn('trabajo_id', $trabajosIds)->delete();
                ImgTrabajo::whereIn('trabajo_id', $trabajosIds)->delete();
                Valoracion::whereIn('trabajo_id', $trabajosIds)->delete();
                Chat::whereIn('trabajo_id', $trabajosIds)->delete();
                
                // Primero obtener postulaciones de estos trabajos
                $postulacionIds = Postulacion::whereIn('trabajo_id', $trabajosIds)->pluck('id');
                // Eliminar pagos de estas postulaciones
                Pago::whereIn('postulacion_id', $postulacionIds)->delete();
                // Luego eliminar las postulaciones
                Postulacion::whereIn('trabajo_id', $trabajosIds)->delete();
                
                // Finalmente eliminar los trabajos
                Trabajo::whereIn('id', $trabajosIds)->delete();
            }
            
            // 2. Eliminar postulaciones como trabajador y sus pagos
            $postulacionIds = Postulacion::where('trabajador_id', $usuario->id)->pluck('id');
            Pago::whereIn('postulacion_id', $postulacionIds)->delete();
            Postulacion::where('trabajador_id', $usuario->id)->delete();
            
            // 3. Eliminar otras relaciones directas
            Valoracion::where('trabajador_id', $usuario->id)->delete();
            LogroCompleto::where('usuario_id', $usuario->id)->delete();
            Habilidad::where('trabajador_id', $usuario->id)->delete();    
            DatosBancarios::where('usuario_id', $usuario->id)->delete();
            Notificacion::where('usuario_id', $usuario->id)->delete();
            
            // 4. Finalmente, eliminar el usuario
            $usuario->delete();
            
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente.'
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error eliminando usuario: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }
  /**
     * Alterna el campo 'activo' entre 'si' y 'no'.
     */
    public function toggleActive(Request $request, $id)
    {
        $request->validate([
            'activo' => 'required|in:si,no',
        ]);

        $user = User::findOrFail($id);
        $user->activo = $request->activo;
        $user->save();

        return response()->json([
            'message' => $user->activo === 'no'
                ? 'Cuenta desactivada correctamente.'
                : 'Cuenta reactivada correctamente.'
        ]);
    }

    /**
     * Envía un aviso al usuario (crea una notificación tipo 'aviso').
     */
    public function notify(Request $request, $id)
    {
        $request->validate([
            'mensaje' => 'required|string|max:500',
        ]);

        $user = User::findOrFail($id);
        $texto = 'Aviso del admin: ' . $request->mensaje;

        $notificacion = Notificacion::create([
            'usuario_id'     => $user->id,
            'mensaje'        => $texto,
            'tipo'           => 'aviso',
            'fecha_creacion' => now(),
            'leido'          => false,
        ]);

        event(new NewNotificacion($notificacion));

        return response()->json([
            'message' => 'Aviso enviado correctamente.'
        ]);
    }
}
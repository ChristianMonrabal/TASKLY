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



class UsuarioController extends Controller
{
    /**
     * Muestra la vista principal (index) donde se listan los usuarios.
     * La vista se encargará de obtener los datos mediante fetch.
     */
    public function index()
    {
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

        return response()->json($query->get());
    }

    /**
     * Elimina un usuario y todas las relaciones dependientes usando una transacción.
     * Utilizando modelos estáticos sin bucles.
     */
    public function destroy(User $usuario)
    {
        try {
            // Iniciar transacción para garantizar que todas las operaciones se completen o ninguna
            DB::beginTransaction();
            
            // Obtener los IDs de los trabajos del usuario
            $trabajosIds = $usuario->trabajosComoCliente()->pluck('id')->toArray();
            
            if (!empty($trabajosIds)) {
                // Eliminar las relaciones de los trabajos usando modelos estáticos
                
                // Eliminar relaciones en tabla pivot
                CategoriaTipoTrabajo::whereIn('trabajo_id', $trabajosIds)->delete();
                
                // Eliminar imágenes de los trabajos
                ImgTrabajo::whereIn('trabajo_id', $trabajosIds)->delete();
                
                // Eliminar valoraciones de los trabajos
                Valoracion::whereIn('trabajo_id', $trabajosIds)->delete();
                
                // Eliminar chats asociados a los trabajos
                Chat::whereIn('trabajo_id', $trabajosIds)->delete();
                
                // Eliminar postulaciones a los trabajos
                Postulacion::whereIn('trabajo_id', $trabajosIds)->delete();
                
                // Eliminar pagos de los trabajos
                Pago::whereIn('trabajo_id', $trabajosIds)->delete();
                
                // Finalmente eliminar los trabajos
                Trabajo::whereIn('id', $trabajosIds)->delete();
            }
            
            // Eliminar relaciones directas del usuario
            
            // Eliminar postulaciones realizadas por el usuario
            Postulacion::where('trabajador_id', $usuario->id)->delete();
            
            // Eliminar valoraciones realizadas por/para el usuario
            Valoracion::where('trabajador_id', $usuario->id)
                ->delete();
            
            // Eliminar pagos asociados al usuario
            Pago::where('trabajador_id', $usuario->id)->delete();
            
            // Eliminar relaciones en tablas pivot
            LogroCompleto::where('usuario_id', $usuario->id)->delete();
            Habilidad::where('trabajador_id', $usuario->id)->delete();    
            
            // Eliminar datos bancarios
            DatosBancarios::where('usuario_id', $usuario->id)->delete();
            
            // Eliminar notificaciones
            Notificacion::where('usuario_id', $usuario->id)->delete();
            
            // Finalmente, eliminar el usuario
            $usuario->delete();
            
            // Si todo salió bien, confirmar la transacción
            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario eliminado correctamente.'
                ]);
            }
            return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
            // Si algo salió mal, revertir todos los cambios
            DB::rollBack();
            echo $e->getMessage();
            die();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }
}
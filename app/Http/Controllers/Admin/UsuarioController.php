<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log; // Añadir esta línea


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
     * Elimina un usuario.
     */
    public function destroy(User $usuario)
    {
        try {
            // --- Eliminar trabajos asociados (si el usuario actúa como cliente) ---
            foreach ($usuario->trabajosComoCliente as $trabajo) {
                // Desvincular la relación many-to-many con categorías
                if (method_exists($trabajo, 'categoriastipotrabajo')) {
                    $trabajo->categoriastipotrabajo()->detach();
                }
                
                // Eliminar los chats asociados a este trabajo.
                if (method_exists($trabajo, 'chat') && $trabajo->chat()->exists()) {
                    $trabajo->chat()->delete();
                }
                
                // Eliminar las postulaciones asociadas al trabajo.
                if (method_exists($trabajo, 'postulaciones') && $trabajo->postulaciones()->exists()) {
                    $trabajo->postulaciones()->delete();
                }
                
                // Eliminar imágenes asociadas.
                if (method_exists($trabajo, 'imagenes') && $trabajo->imagenes()->exists()){
                    $trabajo->imagenes()->delete();
                }
                
                // Eliminar pagos asociados al trabajo, si fueran referenciados (en algunos casos, la relación de pagos podría ser diferente)
                if (method_exists($trabajo, 'pagos') && $trabajo->pagos()->exists()){
                    $trabajo->pagos()->delete();
                }
                
                // Eliminar las valoraciones asociadas.
                if (method_exists($trabajo, 'valoraciones') && $trabajo->valoraciones()->exists()){
                    $trabajo->valoraciones()->delete();
                }
                
                // Eliminar el trabajo.
                $trabajo->delete();
            }
            
            // --- Eliminar relaciones directas del usuario ---
            // Eliminar chats asociados en los que el usuario actúa como trabajador.
            if (method_exists($usuario, 'chats') && $usuario->chats()->exists()){
                $usuario->chats()->delete();
            }
            
            // Eliminar pagos asociados directamente al usuario (referenciados con trabajador_id).
            if (method_exists($usuario, 'pagos') && $usuario->pagos()->exists()){
                $usuario->pagos()->delete();
            }
            
            // Eliminar postulaciones adicionales del usuario (si actúa como trabajador).
            if (method_exists($usuario, 'postulaciones') && $usuario->postulaciones()->exists()){
                $usuario->postulaciones()->delete();
            }
            
            // Eliminar otros datos, por ejemplo:
            if ($usuario->datosBancarios) {
                $usuario->datosBancarios()->delete();
            }
            if (method_exists($usuario, 'logrosCompletos')) {
                $usuario->logrosCompletos()->detach();
            }
            if (method_exists($usuario, 'habilidades')) {
                $usuario->habilidades()->detach();
            }
            if (method_exists($usuario, 'codigosDescuento') && $usuario->codigosDescuento()->exists()){
                $usuario->codigosDescuento()->delete();
            }
            
            // Finalmente, eliminar el usuario.
            $usuario->delete();
    
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario eliminado correctamente.'
                ]);
            }
            return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
        } catch (\Exception $e) {
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
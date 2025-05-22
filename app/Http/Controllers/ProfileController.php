<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Categoria;
use App\Models\DatosBancarios;
use App\Models\Direccion;
use App\Models\Trabajo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user2 = User::with('habilidades')->find(Auth::id());
        $habilidades = Categoria::all();
        return view('profile.profile', compact('user', 'habilidades', 'user2'));
    }
    
    public function showDatosBancarios()
    {
        $user = Auth::user();
        $datosBancarios = DatosBancarios::where('usuario_id', Auth::id())->first();
        return view('profile.datos_bancarios', compact('user', 'datosBancarios'));
    }
    
    /**
     * Muestra la página de gestión de ubicaciones
     */
    public function showUbicaciones()
    {
        $user = Auth::user();
        // Obtener la dirección principal del usuario (la única no asociada a un trabajo)
        $direccionPrincipal = Direccion::where('user_id', Auth::id())
            ->whereNull('trabajo_id')
            ->first();
            
        return view('profile.ubicaciones', compact('user', 'direccionPrincipal'));
    }
    
    public function updateDatosBancarios(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        
        // Validar stripe_account_id si está presente
        if (!empty($data['stripe_account_id']) && strlen($data['stripe_account_id']) < 18) {
            return back()->withErrors(['general' => 'El Stripe Account ID debe tener al menos 18 caracteres'])->withInput();
        }
        
        try {
            // Iniciar transacción
            DB::beginTransaction();
            
            // Guardar datos bancarios
            DatosBancarios::updateOrCreate(
                ['usuario_id' => $user->id],
                [
                    'titular' => $data['titular'] ?? null,
                    'iban' => $data['iban'] ?? null,
                    'nombre_banco' => $data['nombre_banco'] ?? null,
                    'stripe_account_id' => $data['stripe_account_id'] ?? null
                ]
            );
            
            // Confirmar transacción
            DB::commit();
            
            return redirect()->route('profile.datos-bancarios')->with('success', 'Datos bancarios actualizados correctamente');
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            
            // Registrar el error para depuración
            Log::error('Error al actualizar datos bancarios: ' . $e->getMessage());
            
            return back()->withErrors(['general' => 'Error al actualizar datos bancarios: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(500);
        }
        
        // Iniciar transacción para asegurar que todos los datos se guardan correctamente
        DB::beginTransaction();

        if (empty($data['nombre']) || empty($data['apellidos']) || empty($data['email'])) {
            return back()->withErrors(['general' => 'Todos los campos obligatorios deben estar completos'])->withInput();
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors(['general' => 'El formato del email no es válido'])->withInput();
        }

        if (User::where('email', $data['email'])->where('id', '!=', $user->id)->exists()) {
            return back()->withErrors(['general' => 'El email ya está en uso por otro usuario'])->withInput();
        }

        if (!empty($data['telefono'])) {
            if (!preg_match('/^\d{9}$/', $data['telefono'])) {
                return back()->withErrors(['general' => 'El teléfono debe tener exactamente 9 dígitos'])->withInput();
            }

            if (User::where('telefono', $data['telefono'])->where('id', '!=', $user->id)->exists()) {
                return back()->withErrors(['general' => 'El número de teléfono ya está en uso'])->withInput();
            }
        }

        // Validamos el código postal si está presente para la dirección
        if (!empty($data['codigo_postal'])) {
            if (!preg_match('/^\d{5}$/', $data['codigo_postal'])) {
                return back()->withErrors(['general' => 'El código postal debe tener 5 dígitos'])->withInput();
            }
        }

        if (!empty($data['fecha_nacimiento'])) {
            $birthDate = new \DateTime($data['fecha_nacimiento']);
            $today = new \DateTime();
            $age = $today->diff($birthDate)->y;

            if ($age < 18) {
                return back()->withErrors(['general' => 'Debes tener al menos 18 años'])->withInput();
            }
        }

        if (!empty($data['password'])) {
            if (strlen($data['password']) < 8) {
                return back()->withErrors(['general' => 'La nueva contraseña debe tener al menos 8 caracteres'])->withInput();
            }

            $user->password = Hash::make($data['password']);
        }

        if (!empty($data['descripcion']) && strlen($data['descripcion']) > 500) {
            return back()->withErrors(['general' => 'La descripción no puede exceder los 500 caracteres'])->withInput();
        }
        

        if (!empty($data['foto_perfil_camera'])) {
            $imageData = $data['foto_perfil_camera'];

            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
                $extension = $matches[1];
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageData = base64_decode($imageData);

                $fileName = 'profile_' . $user->id . '_' . time() . '.' . $extension;
                $directory = public_path('img/profile_images');

                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                file_put_contents($directory . '/' . $fileName, $imageData);

                $defaultPhoto = 'perfil_default.png';
                if (
                    $user->foto_perfil &&
                    $user->foto_perfil !== $defaultPhoto &&
                    file_exists($directory . '/' . $user->foto_perfil)
                ) {
                    @unlink($directory . '/' . $user->foto_perfil);
                }

                $data['foto_perfil'] = $fileName;
            }
        }

        $user->nombre = $data['nombre'];
        $user->apellidos = $data['apellidos'];
        $user->email = $data['email'];
        $user->telefono = $data['telefono'] ?? null;
        // Eliminamos el código postal de la tabla users ya que estará en la tabla direcciones
        // $user->codigo_postal = $data['codigo_postal'] ?? null;
        $user->fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
        $user->descripcion = $data['descripcion'] ?? null;
        $user->foto_perfil = $data['foto_perfil'] ?? $user->foto_perfil;
        $user->habilidades()->sync($data['habilidades'] ?? []);

        try {
            // Guardar el usuario primero
            $user->save();
            
            // Guardar la dirección si los datos están presentes
            if (!empty($data['direccion']) && !empty($data['ciudad']) && 
                !empty($data['latitud']) && !empty($data['longitud'])) {
                
                // Verificar si el usuario ya tiene una dirección
                $direccion = Direccion::where('user_id', $user->id)
                    ->whereNull('trabajo_id')
                    ->first();
                
                if ($direccion) {
                    // Actualizar dirección existente
                    $direccion->update([
                        'direccion' => $data['direccion'],
                        'codigo_postal' => $data['codigo_postal'] ?? '',
                        'ciudad' => $data['ciudad'],
                        'latitud' => $data['latitud'],
                        'longitud' => $data['longitud'],
                    ]);
                } else {
                    // Crear nueva dirección
                    Direccion::create([
                        'user_id' => $user->id,
                        'direccion' => $data['direccion'],
                        'codigo_postal' => $data['codigo_postal'] ?? '',
                        'ciudad' => $data['ciudad'],
                        'latitud' => $data['latitud'],
                        'longitud' => $data['longitud'],
                        'es_visible_para_trabajador' => true, // La dirección del usuario es visible por defecto
                    ]);
                }
            }
            
            // Si todo ha ido bien, confirmar la transacción
            DB::commit();
            
            return redirect()->route('profile')->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            // Si algo sale mal, revertir la transacción
            DB::rollBack();
            Log::error('Error al actualizar el perfil: ' . $e->getMessage());
            
            return back()->withErrors(['general' => 'Error al actualizar el perfil: ' . $e->getMessage()])->withInput();
        }
    }
    
    /**
     * Guarda o actualiza la dirección de un trabajo
     */
    public function guardarDireccionTrabajo(Request $request, $trabajoId)
    {
        $request->validate([
            'direccion' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'ciudad' => 'required|string|max:100',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'es_visible_para_trabajador' => 'boolean',
        ]);

        try {
            DB::beginTransaction();
            
            // Verificar que el trabajo existe y pertenece al usuario
            $trabajo = Trabajo::where('id', $trabajoId)
                ->where('user_id', Auth::id())
                ->firstOrFail();
                
            // Buscar si el trabajo ya tiene una dirección
            $direccionExistente = Direccion::where('trabajo_id', $trabajoId)
                ->first();
                
            if ($direccionExistente) {
                // Actualizar dirección existente
                $direccionExistente->update([
                    'direccion' => $request->direccion,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                    'es_visible_para_trabajador' => $request->es_visible_para_trabajador ?? false,
                ]);
                
                $mensaje = 'Ubicación del trabajo actualizada correctamente';
            } else {
                // Crear nueva dirección
                Direccion::create([
                    'trabajo_id' => $trabajoId,
                    'direccion' => $request->direccion,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                    'es_visible_para_trabajador' => $request->es_visible_para_trabajador ?? false,
                ]);
                
                $mensaje = 'Ubicación del trabajo guardada correctamente';
            }
            
            DB::commit();
            return redirect()->route('trabajos.detalle', $trabajoId)->with('success', $mensaje);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar dirección de trabajo: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ha ocurrido un error al guardar la ubicación. Inténtalo de nuevo.']);
        }
    }
    
    /**
     * Muestra el mapa completo de una ubicación
     */
    public function verMapa($id, $tipo)
    {
        // Verificar tipo (trabajo o usuario)
        if ($tipo !== 'trabajo' && $tipo !== 'usuario') {
            abort(404);
        }
        
        // Obtener la dirección según el tipo
        if ($tipo === 'trabajo') {
            $direccion = Direccion::where('trabajo_id', $id)->firstOrFail();
            $titulo = Trabajo::find($id)->titulo ?? 'Trabajo';
        } else {
            $direccion = Direccion::where('user_id', $id)->whereNull('trabajo_id')->firstOrFail();
            $usuario = User::find($id);
            $titulo = $usuario->nombre . ' ' . $usuario->apellidos;
        }
        
        return view('mapas.mapa-completo', compact('direccion', 'titulo', 'tipo'));
    }
    
    /**
     * Formulario para añadir/editar dirección de un trabajo
     */
    public function formDireccionTrabajo($trabajoId)
    {
        // Verificar que el trabajo existe y pertenece al usuario
        $trabajo = Trabajo::where('id', $trabajoId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        // Obtener la dirección si existe
        $direccion = Direccion::where('trabajo_id', $trabajoId)->first();
        
        return view('mapas.direccion-trabajo', compact('trabajo', 'direccion'));
    }
    
    /**
     * Guarda o actualiza la dirección principal del usuario
     */
    public function guardarUbicacion(Request $request)
    {
        $request->validate([
            'direccion' => 'required|string|max:255',
            'codigo_postal' => 'required|string|max:10',
            'ciudad' => 'required|string|max:100',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            
            $userId = Auth::id();
            
            // Buscar si el usuario ya tiene una dirección principal
            $direccionExistente = Direccion::where('user_id', $userId)
                ->whereNull('trabajo_id')
                ->first();
                
            if ($direccionExistente) {
                // Actualizar dirección existente
                $direccionExistente->update([
                    'direccion' => $request->direccion,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                ]);
                
                $mensaje = 'Dirección actualizada correctamente';
            } else {
                // Crear nueva dirección
                Direccion::create([
                    'user_id' => $userId,
                    'direccion' => $request->direccion,
                    'codigo_postal' => $request->codigo_postal,
                    'ciudad' => $request->ciudad,
                    'latitud' => $request->latitud,
                    'longitud' => $request->longitud,
                    'es_visible_para_trabajador' => true, // La dirección del usuario es visible por defecto
                ]);
                
                $mensaje = 'Dirección guardada correctamente';
            }
            
            DB::commit();
            return redirect()->route('profile.ubicaciones')->with('success', $mensaje);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar dirección de usuario: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Ha ocurrido un error al guardar la dirección. Inténtalo de nuevo.']);
        }
    }

    /**
     * Busca trabajos cercanos a una ubicación
     */
    public function buscarTrabajosCercanos(Request $request)
    {
        $request->validate([
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'distancia' => 'required|numeric|min:1|max:100',
            'categoria_id' => 'nullable|exists:categorias_tipo_trabajo,id'
        ]);
        
        $lat = $request->latitud;
        $lng = $request->longitud;
        $distanciaKm = $request->distancia;
        $categoriaId = $request->categoria_id;
        
        // Convertir km a grados (aproximación)
        $distanciaGrados = $distanciaKm / 111;
        
        // Consulta para encontrar trabajos cercanos
        $query = Trabajo::join('direcciones', 'trabajos.id', '=', 'direcciones.trabajo_id')
            ->select('trabajos.*', 
                DB::raw("(6371 * acos(cos(radians($lat)) * cos(radians(direcciones.latitud)) * "
                . "cos(radians(direcciones.longitud) - radians($lng)) + "
                . "sin(radians($lat)) * sin(radians(direcciones.latitud)))) AS distancia"))
            ->where('trabajos.estado', 'publicado')
            ->where('direcciones.es_visible_para_trabajador', true)
            ->having('distancia', '<', $distanciaKm);
            
        // Filtrar por categoría si se especifica
        if ($categoriaId) {
            $query->whereHas('categoriastipotrabajo', function($q) use ($categoriaId) {
                $q->where('categorias_tipo_trabajo.id', $categoriaId);
            });
        }
        
        $trabajos = $query->orderBy('distancia', 'asc')->get();
        
        return response()->json([
            'success' => true,
            'trabajos' => $trabajos
        ]);
    }
}

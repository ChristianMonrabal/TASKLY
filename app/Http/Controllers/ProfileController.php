<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Categoria;
use App\Models\DatosBancarios;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Acciones permitidas sin completar perfil
            $allowed = ['show', 'update', 'showDatosBancarios', 'updateDatosBancarios'];

            // Método del controlador actual
            $action = $request->route()->getActionMethod();

            if (! in_array($action, $allowed, true)) {
                if (
                    empty($user->telefono)
                    || empty($user->codigo_postal)
                    || empty($user->fecha_nacimiento)
                    || empty($user->dni)
                ) {
                    return redirect()
                        ->route('profile')
                        ->with('warning', 'Debes completar tu perfil antes de continuar.');
                }
            }

            return $next($request);
        });
    }

    public function show()
    {
        $user       = Auth::user();
        $user2      = User::with('habilidades')->find($user->id);
        $habilidades = Categoria::all();
        return view('profile.profile', compact('user', 'habilidades', 'user2'));
    }
    
    public function showDatosBancarios()
    {
        $user           = Auth::user();
        $datosBancarios = DatosBancarios::where('usuario_id', $user->id)->first();
        return view('profile.datos_bancarios', compact('user', 'datosBancarios'));
    }
    
    public function updateDatosBancarios(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        
        if (!empty($data['stripe_account_id']) && strlen($data['stripe_account_id']) < 18) {
            return back()->withErrors(['general' => 'El Stripe Account ID debe tener al menos 18 caracteres'])->withInput();
        }
        
        try {
            DB::beginTransaction();
            
            // Guardar datos bancarios y fiscales
            DatosBancarios::updateOrCreate(
                ['usuario_id' => $user->id],
                [
                    // Datos bancarios
                    'titular' => $data['titular'] ?? null,
                    'iban' => $data['iban'] ?? null,
                    'nombre_banco' => $data['nombre_banco'] ?? null,
                    'stripe_account_id' => $data['stripe_account_id'] ?? null,
                    
                    // Datos fiscales
                    'nif_fiscal' => $data['nif_fiscal'] ?? null,
                    'direccion_fiscal' => $data['direccion_fiscal'] ?? null,
                    'codigo_postal_fiscal' => $data['codigo_postal_fiscal'] ?? null,
                    'ciudad_fiscal' => $data['ciudad_fiscal'] ?? null
                ]
            );
            
            DB::commit();
            return redirect()->route('profile.datos-bancarios')
                            ->with('success', 'Datos bancarios actualizados correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar datos bancarios: ' . $e->getMessage());
            return back()
                ->withErrors(['general' => 'Error al actualizar datos bancarios: ' . $e->getMessage()])
                ->withInput();
        }
    }

public function update(Request $request)
{
    $data = $request->all();
    /** @var User $user */
    $user = Auth::user();

    // Validaciones
    $errors = [];

    if (empty($data['nombre'])) {
        $errors['nombre'] = 'El nombre es obligatorio';
    }

    if (empty($data['apellidos'])) {
        $errors['apellidos'] = 'Los apellidos son obligatorios';
    }

    if (empty($data['email'])) {
        $errors['email'] = 'El email es obligatorio';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'El formato del email no es válido';
    } elseif (User::where('email', $data['email'])->where('id', '!=', $user->id)->exists()) {
        $errors['email'] = 'El email ya está en uso por otro usuario';
    }

    if (!empty($data['telefono'])) {
        if (!preg_match('/^\d{9}$/', $data['telefono'])) {
            $errors['telefono'] = 'El teléfono debe tener exactamente 9 dígitos';
        } elseif (User::where('telefono', $data['telefono'])->where('id', '!=', $user->id)->exists()) {
            $errors['telefono'] = 'El número de teléfono ya está en uso';
        }
    }

    if (!empty($data['codigo_postal']) && !preg_match('/^\d{5}$/', $data['codigo_postal'])) {
        $errors['codigo_postal'] = 'El código postal debe tener 5 dígitos';
    }

    if (!empty($data['fecha_nacimiento'])) {
        $birthDate = new \DateTime($data['fecha_nacimiento']);
        $age = (new \DateTime())->diff($birthDate)->y;
        if ($age < 18) {
            $errors['fecha_nacimiento'] = 'Debes tener al menos 18 años';
        }
    }

    if (empty($user->dni) && !empty($data['dni'])) {
        if (User::where('dni', $data['dni'])->exists()) {
            $errors['dni'] = 'El DNI ya está en uso por otro usuario';
        } else {
            $user->dni = $data['dni'];
        }
    }


    if (!empty($data['password'])) {
        if (strlen($data['password']) < 8) {
            $errors['password'] = 'La nueva contraseña debe tener al menos 8 caracteres';
        } else {
            $user->password = Hash::make($data['password']);
        }
    }

    if (!empty($data['descripcion']) && strlen($data['descripcion']) > 500) {
        $errors['descripcion'] = 'La descripción no puede exceder los 500 caracteres';
    }

    if (!empty($errors)) {
        return back()->withErrors($errors)->withInput();
    }

    // Procesar foto subida desde cámara
    if (!empty($data['foto_perfil_camera']) && preg_match('/^data:image\/(\w+);base64,/', $data['foto_perfil_camera'], $m)) {
        $ext = $m[1];
        $blob = substr($data['foto_perfil_camera'], strpos($data['foto_perfil_camera'], ',') + 1);
        $bytes = base64_decode($blob);
        $fname = 'profile_' . $user->id . '_' . time() . '.' . $ext;
        $dir = public_path('img/profile_images');
        if (!file_exists($dir)) mkdir($dir, 0755, true);
        file_put_contents("$dir/$fname", $bytes);

        if ($user->foto_perfil && $user->foto_perfil !== 'perfil_default.png') {
            @unlink("$dir/{$user->foto_perfil}");
        }

        $data['foto_perfil'] = $fname;
    }

    // Actualizar datos del usuario
    $user->nombre = $data['nombre'];
    $user->apellidos = $data['apellidos'];
    $user->email = $data['email'];
    $user->telefono = $data['telefono'] ?? null;
    $user->codigo_postal = $data['codigo_postal'] ?? null;
    $user->fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
    $user->descripcion = $data['descripcion'] ?? null;
    $user->foto_perfil = $data['foto_perfil'] ?? $user->foto_perfil;

    // Solo guardar el DNI si aún está vacío
    if (empty($user->dni) && !empty($data['dni'])) {
        $user->dni = $data['dni'];
    }

    $user->habilidades()->sync($data['habilidades'] ?? []);

    if ($user->save()) {
        return redirect()->route('profile')->with('success', 'Perfil actualizado correctamente');
    }

    return back()->withErrors(['general' => 'Error al actualizar el perfil'])->withInput();
}

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Categoria;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user2 = User::with('habilidades')->find(Auth::id());
        $habilidades = Categoria::all();
        return view('profile.profile', compact('user', 'habilidades', 'user2'));
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(500);
        }

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
        $user->codigo_postal = $data['codigo_postal'] ?? null;
        $user->fecha_nacimiento = $data['fecha_nacimiento'] ?? null;
        $user->descripcion = $data['descripcion'] ?? null;
        $user->foto_perfil = $data['foto_perfil'] ?? $user->foto_perfil;
        $user->habilidades()->sync($data['habilidades'] ?? []);

        if ($user->save()) {
            return redirect()->route('profile')->with('success', 'Perfil actualizado correctamente');
        }

        return back()->withErrors(['general' => 'Error al actualizar el perfil'])->withInput();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(500);
        }

        if (empty($data['nombre']) || empty($data['apellidos']) || 
            empty($data['email']) || empty($data['dni'])) {
            return back()->withErrors(['general' => 'Todos los campos obligatorios deben estar completos'])->withInput();
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return back()->withErrors(['general' => 'El formato del email no es válido'])->withInput();
        }

        if (User::where('email', $data['email'])->where('id', '!=', $user->id)->exists()) {
            return back()->withErrors(['general' => 'El email ya está en uso por otro usuario'])->withInput();
        }

        if (strlen($data['dni']) != 9) {
            return back()->withErrors(['general' => 'El DNI debe tener 9 caracteres (8 números + 1 letra)'])->withInput();
        }

        if (!preg_match('/^\d{8}[A-Za-z]$/', $data['dni'])) {
            return back()->withErrors(['general' => 'El formato del DNI no es válido'])->withInput();
        }

        $dniLetters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        $numbers = substr($data['dni'], 0, 8);
        $letter = strtoupper(substr($data['dni'], 8, 1));
        $calculatedLetter = $dniLetters[$numbers % 23];
        
        if ($letter !== $calculatedLetter) {
            return back()->withErrors(['general' => 'La letra del DNI no es válida'])->withInput();
        }

        if (User::where('dni', strtoupper($data['dni']))->where('id', '!=', $user->id)->exists()) {
            return back()->withErrors(['general' => 'El DNI ya está en uso por otro usuario'])->withInput();
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
                
                if ($user->foto_perfil && file_exists($directory . '/' . $user->foto_perfil)) {
                    @unlink($directory . '/' . $user->foto_perfil);
                }
                
                $data['foto_perfil'] = $fileName;
            }
        }

        $user->fill($data);
        
        if ($user->save()) {
            return redirect()->route('profile')->with('success', 'Perfil actualizado correctamente');
        }

        return back()->withErrors(['general' => 'Error al actualizar el perfil'])->withInput();
    }
}
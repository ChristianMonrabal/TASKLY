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
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(500);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:20',
            'codigo_postal' => 'nullable|string|max:10',
            'fecha_nacimiento' => 'nullable|date',
            'descripcion' => 'nullable|string',
            'dni' => 'required|string|max:9|unique:users,dni,' . $user->id,
            'foto_perfil_camera' => 'nullable|string',
        ]);

        $user->fill($request->except(['foto_perfil_camera']));

        if ($request->foto_perfil_camera) {
            $imageData = $request->foto_perfil_camera;

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
                
                $user->foto_perfil = $fileName;
            }
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Perfil actualizado correctamente');
    }
}
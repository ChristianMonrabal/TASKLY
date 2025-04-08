<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.signin');
    }

    public function showSignupForm()
    {
        return view('auth.signup');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
    
            if (Auth::user()->rol_id == 1) {
                return redirect()->route('admin.usuarios.index');
            } else {
                return redirect()->route('welcome');
            }
        }
    
        return redirect()->route('signin.auth')
            ->withErrors([
                'email' => 'Las credenciales no son válidas.',
            ])
            ->withInput();
    }
    

    public function register(Request $request)
    {
        $data = $request->all();
    
        if (
            empty($data['name']) || empty($data['surname']) ||
            empty($data['doc_type']) || empty($data['dni']) ||
            empty($data['email']) || empty($data['password']) ||
            empty($data['password_confirmation'])
        ) {
            return back()->withErrors(['general' => 'Todos los campos son obligatorios'])->withInput();
        }
    
        if (!in_array($data['doc_type'], ['nif', 'nie'])) {
            return back()->withErrors(['general' => 'Tipo de documento no válido'])->withInput();
        }
    
        if (strlen($data['dni']) != 9) {
            return back()->withErrors(['general' => 'El DNI/NIE debe tener 9 caracteres'])->withInput();
        }
    
        if (User::where('email', $data['email'])->exists()) {
            return back()->withErrors(['general' => 'El email ya está en uso'])->withInput();
        }
    
        if (User::where('dni', strtoupper($data['dni']))->exists()) {
            return back()->withErrors(['general' => 'El DNI ya está en uso'])->withInput();
        }
    
        if (!empty($data['phone']) && User::where('telefono', $data['phone'])->exists()) {
            return back()->withErrors(['general' => 'El número de teléfono ya está en uso'])->withInput();
        }
    
        if (!empty($data['phone']) && strlen($data['phone']) > 9) {
            return back()->withErrors(['general' => 'El número de teléfono no puede tener más de 9 dígitos'])->withInput();
        }
    
        if ($data['password'] !== $data['password_confirmation']) {
            return back()->withErrors(['general' => 'Las contraseñas no coinciden'])->withInput();
        }
    
        if (strlen($data['password']) < 8) {
            return back()->withErrors(['general' => 'La contraseña debe tener al menos 8 caracteres'])->withInput();
        }
    
        $user = User::create([
            'nombre' => $data['name'],
            'apellidos' => $data['surname'],
            'telefono' => $data['phone'],
            'codigo_postal' => $data['postcode'],
            'dni' => strtoupper($data['dni']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'rol_id' => 2,
        ]);
    
        Auth::login($user);
        $request->session()->regenerate();
    
        return redirect()->route('welcome');
    }
    
}


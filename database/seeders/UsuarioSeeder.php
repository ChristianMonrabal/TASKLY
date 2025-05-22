<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        // admin
        User::create([
            'nombre' => 'Administrador',
            'apellidos' => 'Administrador',
            'email' => 'admin@taskly.com',
            'telefono' => '630099700',
            'codigo_postal' => '08001',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Administrador del sistema',
            'dni' => '12345678A',
            'rol_id' => 1,
        ]);

        // cliente

        User::create([
            'nombre' => 'Christian',
            'apellidos' => 'Monrabal Donis',
            'email' => 'Christian@taskly.com',
            'telefono' => '746687398',
            'codigo_postal' => '08810',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Adicto al fentanilo.',
            'dni' => '25482103N',
            'rol_id' => 2,
        ]);

        User::create([
            'nombre' => 'Alex',
            'apellidos' => 'Ventura Reynés',
            'email' => 'Alex@taskly.com',
            'telefono' => '676493142',
            'codigo_postal' => '08810',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Soy Alex, me encanta resolver problemas prácticos y ayudar a los demás.',
            'dni' => '96328481L',
            'rol_id' => 2,
        ]);

        User::create([
            'nombre' => 'Daniel',
            'apellidos' => 'Becerra Vidaume',
            'email' => 'Daniel@taskly.com',
            'telefono' => '666667917',
            'codigo_postal' => '08810',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Técnico de confianza. Me especializo en arreglos del hogar y mantenimiento.',
            'dni' => '52980798E',
            'rol_id' => 2,
        ]);

        User::create([
            'nombre' => 'Juan Carlos',
            'apellidos' => 'Prado García',
            'email' => 'Juan@taskly.com',
            'telefono' => '724885636',
            'codigo_postal' => '08810',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Persona comprometida, puntual y con ganas de ayudarte con tus tareas.',
            'dni' => '11201597E',
            'rol_id' => 2,
        ]);

        User::create([
            'nombre' => 'Julio César',
            'apellidos' => 'Carrillo Rocha',
            'email' => 'Julio@taskly.com',
            'telefono' => '659933798',
            'codigo_postal' => '08810',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Con experiencia en múltiples áreas, siempre listo para trabajar.',
            'dni' => '27394859L',
            'rol_id' => 2,
        ]);

        User::create([
            'nombre' => 'Pablo',
            'apellidos' => 'González Márquez',
            'email' => 'pablo@taskly.com',
            'telefono' => '612345678',
            'codigo_postal' => '08001',
            'password' => bcrypt('pablo1234'),
            'fecha_nacimiento' => '1995-05-15',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Hola, soy Pablo y me encanta ayudar con tareas del hogar.',
            'dni' => '50133963N',
            'rol_id' => 2,
        ]);
    }
}

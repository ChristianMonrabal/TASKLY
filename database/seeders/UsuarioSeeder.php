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
            'telefono' => '666111222',
            'codigo_postal' => '08001',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Administrador del sistema',
            'dni' => '12345678A',
            'rol_id' => 1,
        ]);

        User::create([
            'nombre' => 'Christian',
            'apellidos' => 'Monrabal Donis',
            'email' => 'Christian@taskly.com',
            'telefono' => '666111222',
            'codigo_postal' => '08810',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Descripción de Christian',
            'dni' => '25482103N',
            'rol_id' => 2,
        ]);

        User::create([
            'nombre' => 'Alex',
            'apellidos' => 'Ventura Reynés',
            'email' => 'Alex@taskly.com',
            'telefono' => '666111222',
            'codigo_postal' => '08810',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Descripción de Alex',
            'dni' => '96328481L',
            'rol_id' => 2,
        ]);

        User::create([
            'nombre' => 'Daniel',
            'apellidos' => 'Becerra Vidaume',
            'email' => 'Daniel@taskly.com',
            'telefono' => '666111222',
            'codigo_postal' => '08810',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Descripción de Daniel',
            'dni' => '52980798E',
            'rol_id' => 2,
        ]);

        User::create([
            'nombre' => 'Juan Carlos',
            'apellidos' => 'Prado García',
            'email' => 'Juan@taskly.com',
            'telefono' => '666111222',
            'codigo_postal' => '08810',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Descripción de Juan',
            'dni' => '11201597E',
            'rol_id' => 2,
        ]);

        User::create([
            'nombre' => 'Julio César',
            'apellidos' => 'Carrillo Rocha',
            'email' => 'Julio@taskly.com',
            'telefono' => '666111222',
            'codigo_postal' => '08810',
            'password' => bcrypt('asdASD123'),
            'fecha_nacimiento' => '2000-01-01',
            'foto_perfil' => 'perfil_default.png',
            'descripcion' => 'Descripción de Julio',
            'dni' => '27394859L',
            'rol_id' => 2,
        ]);
    }
}

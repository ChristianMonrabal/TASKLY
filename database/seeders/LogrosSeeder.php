<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Logro;

class LogrosSeeder extends Seeder
{
    public function run(): void
    {
        Logro::create([
            'nombre' => 'Primer trabajo',
            'descripcion' => 'Has completado tu primer trabajo exitosamente.',
            'foto_logro' => 'Insignia1.png',
        ]);

        Logro::create([
            'nombre' => 'Valoración perfecta',
            'descripcion' => 'Recibiste 5 valoraciones con 5 estrellas.',
            'foto_logro' => 'Insignia2.png',
        ]);

        Logro::create([
            'nombre' => '10 trabajos completados',
            'descripcion' => 'Has completado 10 trabajos exitosamente.',
            'foto_logro' => 'Insignia3.png',
        ]);

        Logro::create([
            'nombre' => '50 trabajos completados',
            'descripcion' => 'Has completado 50 trabajos exitosamente.',
            'foto_logro' => 'Insignia4.png',
        ]);

        Logro::create([
            'nombre' => '100 trabajos completados',
            'descripcion' => 'Has completado 100 trabajos exitosamente.',
            'foto_logro' => 'Insignia5.png',
        ]);

    }
}

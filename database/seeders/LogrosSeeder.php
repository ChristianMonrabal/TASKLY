<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Logro;

class LogrosSeeder extends Seeder
{
    public function run(): void
    {
        Logro::create([
            'nombre' => 'Primer Trabajo',
            'descripcion' => 'Has completado tu primer trabajo exitosamente.',
            'descuento' => 5,
        ]);

        Logro::create([
            'nombre' => 'Cliente Estrella',
            'descripcion' => 'Recibiste 5 valoraciones con 5 estrellas.',
            'descuento' => 10,
        ]);

        Logro::create([
            'nombre' => 'Trabajador del Mes',
            'descripcion' => 'Fuiste elegido como el mejor trabajador del mes.',
            'descuento' => 15,
        ]);
    }
}

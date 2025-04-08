<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trabajo;
use App\Models\ImgTrabajo;

class TrabajoSeeder extends Seeder
{
    public function run(): void
    {
        Trabajo::create([
            'titulo' => 'Trabajo 1',
            'descripcion' => 'Descripción del trabajo de ejemplo.',
            'precio' => 10,
            'direccion' => 'Calle Ejemplo, 123',
            'cliente_id' => 2,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Trabajo 2',
            'descripcion' => 'Descripción del trabajo de ejemplo.',
            'precio' => 10,
            'direccion' => 'Calle Ejemplo, 123',
            'cliente_id' => 2,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Trabajo 3',
            'descripcion' => 'Descripción del trabajo de ejemplo.',
            'precio' => 10,
            'direccion' => 'Calle Ejemplo, 123',
            'cliente_id' => 3,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 1,
            'ruta_imagen' => 'foto1.jpg',
            'descripcion' => 'asd',
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 1,
            'ruta_imagen' => 'foto2.jpg',
            'descripcion' => 'qwe',
        ]);
    }
}

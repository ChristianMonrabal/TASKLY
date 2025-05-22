<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Valoracion;
use App\Models\Trabajo;
use App\Models\User;

class ValoracionesSeeder extends Seeder
{
    public function run(): void
    {
        Valoracion::create([
            'trabajo_id' => 1,
            'trabajador_id' => 6,
            'puntuacion' => rand(1, 5),
            'img_valoracion' => 'persiana.png',
            'comentario' => 'Llegó puntual y realizó el trabajo de forma correcta.',
        ]);

        Valoracion::create([
            'trabajo_id' => 2,
            'trabajador_id' => 6,
            'puntuacion' => rand(1, 5),
            'img_valoracion' => 'persiana.png',
            'comentario' => 'Todo perfecto, muy contento con el resultado.',
        ]);
    }
}

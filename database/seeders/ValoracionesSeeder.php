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
            'trabajador_id' => 3,
            'puntuacion' => rand(1, 5),
            'img_valoracion' => 'foto.jpg',
            'comentario' => 'Bien',
        ]);
    }
}

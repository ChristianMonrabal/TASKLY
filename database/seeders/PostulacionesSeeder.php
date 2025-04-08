<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Postulacion;
use App\Models\Trabajo;
use App\Models\User;
use App\Models\Estado;

class PostulacionesSeeder extends Seeder
{
    public function run(): void
    {
        Postulacion::create([
            'trabajo_id' => 1,
            'trabajador_id' => 3,
            'estado_id' => 10,
        ]);

        Postulacion::create([
            'trabajo_id' => 1,
            'trabajador_id' => 4,
            'estado_id' => 11,
        ]);

        Postulacion::create([
            'trabajo_id' => 2,
            'trabajador_id' => 4,
            'estado_id' => 9,
        ]);

        Postulacion::create([
            'trabajo_id' => 3,
            'trabajador_id' => 4,
            'estado_id' => 9,
        ]);
    }
}

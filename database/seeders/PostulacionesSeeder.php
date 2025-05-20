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
        // Trabajo 1: El cliente es el usuario con id 3 (Christian)
        // Postulantes: Alex (id 2), Juan Carlos (id 4)
        Postulacion::create([
            'trabajo_id' => 1,
            'trabajador_id' => 2,  // Alex
            'estado_id' => 9,
        ]);
        Postulacion::create([
            'trabajo_id' => 1,
            'trabajador_id' => 4,  // Juan Carlos
            'estado_id' => 9,
        ]);

        // Trabajo 2: El cliente es el usuario con id 4 (Juan Carlos)
        // Postulantes: Daniel (id 3), Julio César (id 5)
        Postulacion::create([
            'trabajo_id' => 2,
            'trabajador_id' => 4,  // Daniel
            'estado_id' => 9,
        ]);
        Postulacion::create([
            'trabajo_id' => 2,
            'trabajador_id' => 6,  // Julio César
            'estado_id' => 9,
        ]);
    }
}

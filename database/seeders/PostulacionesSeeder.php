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
            'trabajador_id' => 2, // Alex (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);
        Postulacion::create([
            'trabajo_id' => 1,
            'trabajador_id' => 4, // Juan Carlos (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);

        // Trabajo 2: El cliente es el usuario con id 3 (Christian), los postulantes no serán este usuario
        Postulacion::create([
            'trabajo_id' => 2,
            'trabajador_id' => 3, // Daniel (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);
        Postulacion::create([
            'trabajo_id' => 2,
            'trabajador_id' => 5, // Julio César (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);

        // Trabajo 3: El cliente es el usuario con id 3 (Christian), los postulantes no serán este usuario
        Postulacion::create([
            'trabajo_id' => 3,
            'trabajador_id' => 2, // Alex (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);
        Postulacion::create([
            'trabajo_id' => 3,
            'trabajador_id' => 4, // Juan Carlos (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);

        // Trabajo 4: El cliente es el usuario con id 4 (Juan Carlos), los postulantes no serán este usuario
        Postulacion::create([
            'trabajo_id' => 4,
            'trabajador_id' => 2, // Alex (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);
        Postulacion::create([
            'trabajo_id' => 4,
            'trabajador_id' => 3, // Daniel (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);

        // Trabajo 5: El cliente es el usuario con id 4 (Juan Carlos), los postulantes no serán este usuario
        Postulacion::create([
            'trabajo_id' => 5,
            'trabajador_id' => 5, // Julio César (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);
        Postulacion::create([
            'trabajo_id' => 5,
            'trabajador_id' => 2, // Alex (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);

        // Trabajo 6: El cliente es el usuario con id 4 (Juan Carlos), los postulantes no serán este usuario
        Postulacion::create([
            'trabajo_id' => 6,
            'trabajador_id' => 3, // Daniel (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);
        Postulacion::create([
            'trabajo_id' => 6,
            'trabajador_id' => 5, // Julio César (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);

        // Trabajo 7: El cliente es el usuario con id 4 (Juan Carlos), los postulantes no serán este usuario
        Postulacion::create([
            'trabajo_id' => 7,
            'trabajador_id' => 3, // Daniel (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);
        Postulacion::create([
            'trabajo_id' => 7,
            'trabajador_id' => 4, // Juan Carlos (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);

        // Trabajo 8: El cliente es el usuario con id 5 (Pablo), los postulantes no serán este usuario
        Postulacion::create([
            'trabajo_id' => 8,
            'trabajador_id' => 2, // Alex (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);
        Postulacion::create([
            'trabajo_id' => 8,
            'trabajador_id' => 3, // Daniel (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);

        // Trabajo 9: El cliente es el usuario con id 5 (Pablo), los postulantes no serán este usuario
        Postulacion::create([
            'trabajo_id' => 9,
            'trabajador_id' => 2, // Alex (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);
        Postulacion::create([
            'trabajo_id' => 9,
            'trabajador_id' => 4, // Juan Carlos (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);

        // Trabajo 10: El cliente es el usuario con id 5 (Pablo), los postulantes no serán este usuario
        Postulacion::create([
            'trabajo_id' => 10,
            'trabajador_id' => 2, // Alex (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);
        Postulacion::create([
            'trabajo_id' => 10,
            'trabajador_id' => 3, // Daniel (trabajador)
            'estado_id' => 10, // Estado de la postulación
        ]);
    }
}

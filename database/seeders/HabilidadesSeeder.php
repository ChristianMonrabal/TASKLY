<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Habilidad;
use App\Models\User;
use App\Models\Categoria;

class HabilidadesSeeder extends Seeder
{
    public function run(): void
    {
        Habilidad::create([
            'trabajador_id' => 2, // Christian
            'categoria_id' => 1,  // Electricidad
        ]);

        Habilidad::create([
            'trabajador_id' => 3, // Alex
            'categoria_id' => 2,  // Fontanería
        ]);

        Habilidad::create([
            'trabajador_id' => 4, // Daniel
            'categoria_id' => 3,  // Carpintería
        ]);

        Habilidad::create([
            'trabajador_id' => 5, // Juan Carlos
            'categoria_id' => 4,  // Pintura
        ]);

        Habilidad::create([
            'trabajador_id' => 6, // Julio César
            'categoria_id' => 5,  // Mantenimiento
        ]);

        Habilidad::create([
            'trabajador_id' => 7, // Pablo
            'categoria_id' => 7,  // Cerrajería
        ]);
    }
}

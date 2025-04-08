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
            'trabajador_id' => 3,
            'categoria_id' => 3,
        ]);

        Habilidad::create([
            'trabajador_id' => 3,
            'categoria_id' => 2,
        ]);

        Habilidad::create([
            'trabajador_id' => 4,
            'categoria_id' => 1,
        ]);
        Habilidad::create([
            'trabajador_id' => 4,
            'categoria_id' => 2,
        ]);
    }
}

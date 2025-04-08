<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaTipoTrabajo;

class CategoriaTipoTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear relaciones entre trabajos y categorías
        CategoriaTipoTrabajo::create([
            'trabajo_id' => 1,
            'categoria_id' => 1,
        ]);

        CategoriaTipoTrabajo::create([
            'trabajo_id' => 1,
            'categoria_id' => 2,
        ]);

        CategoriaTipoTrabajo::create([
            'trabajo_id' => 2,
            'categoria_id' => 2,
        ]);

        CategoriaTipoTrabajo::create([
            'trabajo_id' => 3,
            'categoria_id' => 3,
        ]);
    }
}

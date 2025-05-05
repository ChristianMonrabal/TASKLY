<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoriaTipoTrabajo;

class CategoriaTipoTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        CategoriaTipoTrabajo::create(['trabajo_id' => 1, 'categoria_id' => 2]); // Fontanería

        // Trabajo 2: Instalación de enchufes
        CategoriaTipoTrabajo::create(['trabajo_id' => 2, 'categoria_id' => 1]); // Electricidad

        // Trabajo 3: Reparación de puerta
        CategoriaTipoTrabajo::create(['trabajo_id' => 3, 'categoria_id' => 3]); // Carpintería
        CategoriaTipoTrabajo::create(['trabajo_id' => 3, 'categoria_id' => 5]); // Mantenimiento

        // Trabajo 4: Instalación de grifo
        CategoriaTipoTrabajo::create(['trabajo_id' => 4, 'categoria_id' => 2]); // Fontanería

        // Trabajo 5: Instalación de lámpara
        CategoriaTipoTrabajo::create(['trabajo_id' => 5, 'categoria_id' => 1]); // Electricidad
        CategoriaTipoTrabajo::create(['trabajo_id' => 5, 'categoria_id' => 17]); // Decoración

        // Trabajo 6: Reparación de ventana
        CategoriaTipoTrabajo::create(['trabajo_id' => 6, 'categoria_id' => 3]); // Carpintería
        CategoriaTipoTrabajo::create(['trabajo_id' => 6, 'categoria_id' => 5]); // Mantenimiento

        // Trabajo 7: Colocación de cortinas
        CategoriaTipoTrabajo::create(['trabajo_id' => 7, 'categoria_id' => 17]); // Decoración
        CategoriaTipoTrabajo::create(['trabajo_id' => 7, 'categoria_id' => 5]);  // Mantenimiento

        // Trabajo 8: Limpieza de garaje
        CategoriaTipoTrabajo::create(['trabajo_id' => 8, 'categoria_id' => 13]); // Limpieza
        CategoriaTipoTrabajo::create(['trabajo_id' => 8, 'categoria_id' => 30]); // Cuidado del hogar

        // Trabajo 9: Reparación de persiana
        CategoriaTipoTrabajo::create(['trabajo_id' => 9, 'categoria_id' => 3]); // Carpintería
        CategoriaTipoTrabajo::create(['trabajo_id' => 9, 'categoria_id' => 5]); // Mantenimiento

        // Trabajo 10: Mantenimiento de jardín
        CategoriaTipoTrabajo::create(['trabajo_id' => 10, 'categoria_id' => 12]); // Jardinería
        CategoriaTipoTrabajo::create(['trabajo_id' => 10, 'categoria_id' => 31]); // Cuidado del jardín

        // Trabajo 11: Instalación de espejo grande
        CategoriaTipoTrabajo::create(['trabajo_id' => 11, 'categoria_id' => 17]); // Decoración
        CategoriaTipoTrabajo::create(['trabajo_id' => 11, 'categoria_id' => 5]);  // Mantenimiento
        CategoriaTipoTrabajo::create(['trabajo_id' => 11, 'categoria_id' => 3]);  // Carpintería
    }
}

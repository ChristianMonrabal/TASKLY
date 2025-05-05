<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoSeeder extends Seeder
{
    public function run(): void
    {
        // Insertar estados predefinidos en la tabla estados
        Estado::create([
            'nombre' => 'Pendiente',
            'tipo_estado' => 'trabajos',
        ]);

        Estado::create([
            'nombre' => 'En Progreso',
            'tipo_estado' => 'trabajos',
        ]);

        Estado::create([
            'nombre' => 'Completado',
            'tipo_estado' => 'trabajos',
        ]);

        Estado::create([
            'nombre' => 'Cancelado',
            'tipo_estado' => 'trabajos',
        ]);

        Estado::create([
            'nombre' => 'Pendiente',
            'tipo_estado' => 'pagos',
        ]);
        Estado::create([
            'nombre' => 'pagado',
            'tipo_estado' => 'pagos',
        ]);
        Estado::create([
            'nombre' => 'Reembolsado',
            'tipo_estado' => 'pagos',
        ]);
        Estado::create([
            'nombre' => 'Rechazado',
            'tipo_estado' => 'pagos',
        ]);



        Estado::create([
            'nombre' => 'Pendiente',
            'tipo_estado' => 'postulaciones',
        ]);
        Estado::create([
            'nombre' => 'Aceptada',
            'tipo_estado' => 'postulaciones',
        ]);
        Estado::create([
            'nombre' => 'Rechazada',
            'tipo_estado' => 'postulaciones',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoSeeder extends Seeder
{
    public function run(): void
    {
        // Insertar estados predefinidos en la tabla estados

        // Estado de trabajos
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

        // Estado de pagos
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


        // Estado de postulaciones
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


        // Estado de gravedad de un reporte
        Estado::create([
            'nombre' => 'Baja',
            'tipo_estado' => 'reporte_gravedad',
        ]);
        Estado::create([
            'nombre' => 'Media',
            'tipo_estado' => 'reporte_gravedad',
        ]);
        Estado::create([
            'nombre' => 'Alta',
            'tipo_estado' => 'reporte_gravedad',
        ]);


        // Estado de la solicitud de reparto
        Estado::create([
            'nombre' => 'Espera',
            'tipo_estado' => 'reporte_estado',
        ]);
        Estado::create([
            'nombre' => 'Abierto',
            'tipo_estado' => 'reporte_estado',
        ]);
        Estado::create([
            'nombre' => 'Cerrado',
            'tipo_estado' => 'reporte_estado',
        ]);
    }
}

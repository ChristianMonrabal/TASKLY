<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trabajo;
use App\Models\ImgTrabajo;
use App\Models\Estado;
use Illuminate\Support\Facades\Auth;

class TrabajoFinalizadosSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener los IDs de los estados “Finalizado” y “Completado”
        $estadoCompletado = Estado::where('nombre', 'Completado')->first();

        if (!$estadoCompletado) {
            $this->command->error('No se encontraron los estados Finalizado/Completado en la tabla estados.');
            return;
        }

        $completadoId  = $estadoCompletado->id;

        // Crear trabajos finalizados
        $datos = [
            [
                'titulo'        => 'Jardinería completa',
                'descripcion'   => 'Diseño y mantenimiento de jardín residencial.',
                'precio'        => 200,
                'direccion'     => 'Plaza Mayor, 3',
                'cliente_id'    => 4,
                'estado_id'     => $completadoId,
                'fecha_limite'  => now()->subDays(5),
            ],
            [
                'titulo'        => 'Instalación de aire acondicionado',
                'descripcion'   => 'Montaje y puesta en marcha de equipo split de 3000 frigorías.',
                'precio'        => 350,
                'direccion'     => 'Calle Real, 22',
                'cliente_id'    => 5,
                'estado_id'     => $completadoId,
                'fecha_limite'  => now()->subDays(15),
            ],
        ];

        foreach ($datos as $key => $dato) {
            $trabajo = Trabajo::create($dato);

            // Crear al menos una imagen de ejemplo por trabajo
            ImgTrabajo::create([
                'trabajo_id'   => $trabajo->id,
                'ruta_imagen'  => 'default.png',
                'descripcion'  => 'Imagen de ejemplo'
            ]);
        }

        $this->command->info('Seeder de trabajos finalizados/completados ejecutado correctamente.');
    }
}

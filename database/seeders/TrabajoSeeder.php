<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trabajo;
use App\Models\ImgTrabajo;
use Illuminate\Support\Facades\DB;


class TrabajoSeeder extends Seeder
{
    public function run(): void
    {
        Trabajo::create([
            'titulo' => 'El grifo pierde agua',
            'descripcion' => 'Ayer empezo a perderme agua el grifo de la cocina, necesito que venga un fontanero a repararlo.',
            'precio' => 10,
            'direccion' => 'av da de la libertad, 13',
            'cliente_id' => 3,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Instalación de enchufes',
            'descripcion' => 'Necesito un electricista para instalar 3 enchufes en mi casa.',
            'precio' => 10,
            'direccion' => 'Av. de la libertad, 13',
            'cliente_id' => 3,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Reparación de puerta',
            'descripcion' => 'La puerta de la habitación no cierra bien, necesito un carpintero para repararla.',
            'precio' => 10,
            'direccion' => 'Calle de la paz, 13',
            'cliente_id' => 3,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Instalación de grifo',
            'descripcion' => 'Necesito un fontanero para instalar un grifo en la cocina.',
            'precio' => 10,
            'direccion' => 'Calle de la paz, 13',
            'cliente_id' => 4,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Instalación de lámpara',
            'descripcion' => 'Necesito un electricista para instalar una lámpara en el salón.',
            'precio' => 10,
            'direccion' => 'Calle de la paz, 13',
            'cliente_id' => 5,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Reparación de ventana',
            'descripcion' => 'La ventana del salón no cierra bien, necesito un carpintero para repararla.',
            'precio' => 10,
            'direccion' => 'Calle de la paz, 13',
            'cliente_id' => 6,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Instalación de grifo',
            'descripcion' => 'Necesito un fontanero para instalar un grifo en la cocina.',
            'precio' => 10,
            'direccion' => 'Calle de la paz, 13',
            'cliente_id' => 6,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 1,
            'ruta_imagen' => 'electricidad.png',
            'descripcion' => 'electricidad',
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 2,
            'ruta_imagen' => 'fontaneria.png',
            'descripcion' => 'fontaneria',
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 3,
            'ruta_imagen' => 'carpinteria.png',
            'descripcion' => 'carpinteria',
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 4,
            'ruta_imagen' => 'fontaneria.png',
            'descripcion' => 'fontaneria',
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 5,
            'ruta_imagen' => 'electricidad.png',
            'descripcion' => 'electricidad',
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 6,
            'ruta_imagen' => 'carpinteria.png',
            'descripcion' => 'carpinteria',
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 7,
            'ruta_imagen' => 'fontaneria.png',
            'descripcion' => 'fontaneria',
        ]);
    }
}

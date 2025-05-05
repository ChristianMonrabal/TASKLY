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
            'cliente_id' => 4,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Reparación de ventana',
            'descripcion' => 'La ventana del salón no cierra bien, necesito un carpintero para repararla.',
            'precio' => 10,
            'direccion' => 'Calle de la paz, 13',
            'cliente_id' => 4,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);
        Trabajo::create([
            'titulo' => 'Colocación de cortinas',
            'descripcion' => 'Necesito ayuda para instalar cortinas en el salón y dormitorio.',
            'precio' => 18,
            'direccion' => 'Calle Jardines, 3',
            'cliente_id' => 4,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Limpieza de garaje',
            'descripcion' => 'Busco alguien que pueda limpiar mi garaje de 20m².',
            'precio' => 25,
            'direccion' => 'Calle Roble, 44',
            'cliente_id' => 5,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Reparación de persiana',
            'descripcion' => 'La persiana del dormitorio está atascada, necesito repararla.',
            'precio' => 14,
            'direccion' => 'Av. del Mar, 9',
            'cliente_id' => 5,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Mantenimiento de jardín',
            'descripcion' => 'Cortar césped y podar setos en un jardín pequeño.',
            'precio' => 35,
            'direccion' => 'Calle Romero, 21',
            'cliente_id' => 5,
            'estado_id' => 2,
            'fecha_limite' => now()->addDays(rand(1, 30)),
        ]);

        Trabajo::create([
            'titulo' => 'Instalación de espejo grande',
            'descripcion' => 'Quiero colgar un espejo grande en el recibidor, se necesita taladro y nivel.',
            'precio' => 22,
            'direccion' => 'Plaza del Carmen, 10',
            'cliente_id' => 5,
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
            'ruta_imagen' => 'decoracion.png',
            'descripcion' => 'colocación de cortinas',
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 8,
            'ruta_imagen' => 'limpieza.png',
            'descripcion' => 'limpieza de garaje',
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 9,
            'ruta_imagen' => 'persiana.png',
            'descripcion' => 'reparación de persiana',
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 10,
            'ruta_imagen' => 'jardineria.png',
            'descripcion' => 'mantenimiento de jardín',
        ]);

        ImgTrabajo::create([
            'trabajo_id' => 11,
            'ruta_imagen' => 'bricolaje.png',
            'descripcion' => 'instalación de espejo',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {

        Categoria::create([
            'nombre' => 'Electricidad',
        ]);
        Categoria::create([
            'nombre' => 'Fontanería',
        ]);
        Categoria::create([
            'nombre' => 'Carpintería',
        ]);
        Categoria::create([
            'nombre' => 'Pintura',
        ]);
        Categoria::create([
            'nombre' => 'Mantenimiento',
        ]);
        Categoria::create([
            'nombre' => 'Reparación de electrodomésticos',
        ]);
        Categoria::create([
            'nombre' => 'Cerrajería',
        ]);
        Categoria::create([
            'nombre' => 'Mudanzas',
        ]);
        Categoria::create([
            'nombre' => 'Reformas',
        ]);
        Categoria::create([
            'nombre' => 'Desatascos',
        ]);
        Categoria::create([
            'nombre' => 'Climatización',
        ]);
        Categoria::create([
            'nombre' => 'Jardinería',
        ]);
        Categoria::create([
            'nombre' => 'Limpieza',
        ]);
        Categoria::create([
            'nombre' => 'Construcción',
        ]);
        Categoria::create([
            'nombre' => 'Pavimentación',
        ]);
        Categoria::create([
            'nombre' => 'Albañilería',
        ]);
        Categoria::create([
            'nombre' => 'Decoración',
        ]);
        Categoria::create([
            'nombre' => 'Reparación de ordenadores',
        ]);
        Categoria::create([
            'nombre' => 'Reparación de móviles',
        ]);
        Categoria::create([
            'nombre' => 'Fotografía',
        ]);
        Categoria::create([
            'nombre' => 'Diseño gráfico',
        ]);
        Categoria::create([
            'nombre' => 'Marketing digital',
        ]);
        Categoria::create([
            'nombre' => 'Asesoría legal',
        ]);
        Categoria::create([
            'nombre' => 'Contabilidad',
        ]);
        Categoria::create([
            'nombre' => 'Clases particulares',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado de mascotas',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado de personas mayores',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado de niños',
        ]);
        Categoria::create([
            'nombre' => 'Entrenamiento personal',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado del hogar',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado del jardín',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado del coche',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado de la salud',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado de la belleza',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado de la imagen personal',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado de la ropa',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado de la casa',
        ]);
        Categoria::create([
            'nombre' => 'Cuidado de la familia',
        ]);
    }
}

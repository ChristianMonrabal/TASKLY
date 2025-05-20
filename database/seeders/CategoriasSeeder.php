<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Electricidad',
            'Fontanería',
            'Carpintería',
            'Pintura',
            'Mantenimiento',
            'Reparación de electrodomésticos',
            'Cerrajería',
            'Mudanzas',
            'Reformas',
            'Desatascos',
            'Climatización',
            'Jardinería',
            'Limpieza',
            'Construcción',
            'Pavimentación',
            'Albañilería',
            'Decoración',
            'Reparación de ordenadores',
            'Reparación de móviles',
            'Fotografía',
            'Diseño gráfico',
            'Marketing digital',
            'Asesoría legal',
            'Contabilidad',
            'Clases particulares',
            'Cuidado de mascotas',
            'Cuidado de personas mayores',
            'Cuidado de niños',
            'Entrenamiento personal',
            'Cuidado del hogar',
            'Cuidado del jardín',
            'Cuidado del coche',
            'Cuidado de la salud',
            'Cuidado de la belleza',
            'Cuidado de la imagen personal',
            'Cuidado de la ropa',
            'Cuidado de la casa',
            'Cuidado de la familia'
        ];

        foreach ($categorias as $categoria) {
            Categoria::create(['nombre' => $categoria]);
        }
    }
}

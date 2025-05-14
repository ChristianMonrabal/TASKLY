<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Postulacion;
use App\Models\Estado;
use App\Models\MetodoPago;

class PagosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscamos una postulación existente para asociar el pago
        $postulacion = Postulacion::first();
        
        // Si existe al menos una postulación, creamos el pago
        if ($postulacion) {
            Pago::create([
                'postulacion_id' => $postulacion->id,
                'cantidad' => rand(5, 1000),  // Pago aleatorio entre 5 y 1000
                'estado_id' => 6,
                'metodo_id' => 3,
                'fecha_pago' => now()->subDays(rand(1, 30)),  // Fecha de pago aleatoria en los últimos 30 días
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pago;
use App\Models\Trabajo;
use App\Models\User;
use App\Models\Estado;
use App\Models\MetodoPago;

class PagosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pago::create([
            'trabajo_id' => 1,
            'trabajador_id' => 3,
            'cantidad' => rand(5, 1000),  // Pago aleatorio entre 5 y 1000
            'estado_id' => 6,
            'metodo_id' => 3,
            'fecha_pago' => now()->subDays(rand(1, 30)),  // Fecha de pago aleatoria en los últimos 30 días
        ]);
    }
}

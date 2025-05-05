<?php

namespace Database\Seeders;

use App\Models\MetodoPago;
use Illuminate\Database\Seeder;

class MetodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metodospagos = [
            'Tarjeta de crédito',
            'PayPal',
            'Transferencia bancaria',
            'Efectivo',
            'Bizum'
        ];

        foreach ($metodospagos as $metodopago) {
            MetodoPago::create(['nombre' => $metodopago]);
        }
    }
}

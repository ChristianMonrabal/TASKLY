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
        $roles = ['Tarjeta de crédito', 'PayPal', 'Transferencia bancaria', 'Efectivo', 'Bizum'];

        foreach ($roles as $rol) {
            MetodoPago::create(['nombre' => $rol]);
        }
    }
}

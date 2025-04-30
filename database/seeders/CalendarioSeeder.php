<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Calendario;

class CalendarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Calendario::create([
            'titulo' => 'Acudir a: el grifo pierde agua',
            'descripcion' => 'El grifo pierde agua',
            'trabajo' => 1,
            'cliente' => 3,
            'trabajador' => 2,
            'fecha' => now()->addDays(1),
        ]);

        Calendario::create([
            'titulo' => 'Acudir a: Instalación de enchufes',
            'descripcion' => 'Instalación de enchufes',
            'trabajo' => 2,
            'cliente' => 3,
            'trabajador' => 2,
            'fecha' => now()->addDays(3),
        ]);
    }
}

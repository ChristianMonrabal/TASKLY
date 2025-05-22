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
            'titulo' => 'Acudir a:',
            'descripcion' => 'El grifo pierde agua',
            'trabajo' => 1,
            'cliente' => 5,
            'trabajador' => 6,
            'fecha' => now()->addDays(1),
        ]);

        Calendario::create([
            'titulo' => 'Acudir a:',
            'descripcion' => 'Instalación de enchufes',
            'trabajo' => 2,
            'cliente' => 5,
            'trabajador' => 6,
            'fecha' => now()->addDays(3),
        ]);
    }
}

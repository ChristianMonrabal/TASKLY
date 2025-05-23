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
            'titulo' => 'El grifo pierde agua',
            'descripcion' => 'El grifo pierde agua',
            'trabajo' => 1,
            'cliente' => 5,
            'trabajador' => 6,
            'fecha' => now()->addDays(1),
            'hora' => '09:30', 
        ]);

        Calendario::create([
            'titulo' => 'Instalación de enchufes',
            'descripcion' => 'Instalación de enchufes',
            'trabajo' => 2,
            'cliente' => 5,
            'trabajador' => 6,
            'fecha' => now()->addDays(3),
            'hora' => '15:00', 
        ]);
    }
}

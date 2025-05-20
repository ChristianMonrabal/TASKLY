<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\reportes;    // tu modelo
use App\Models\User;
use App\Models\Estado;
use Illuminate\Support\Str;

class ReporteSeeder extends Seeder
{
    public function run(): void
    {
        // Traemos IDs de usuarios
        $users       = User::pluck('id')->toArray();
        // IDs de gravedad y de estado
        $gravedads   = Estado::where('tipo_estado', 'reporte_gravedad')->pluck('id')->toArray();
        $estados     = Estado::where('tipo_estado', 'reporte_estado')->pluck('id')->toArray();

        // Creamos 50 reportes aleatorios
        for ($i = 0; $i < 50; $i++) {
            reportes::create([
                'motivo'         => Str::limit(Str::random(40), 40, ''),
                'id_usuario'     => $users[array_rand($users)],
                'gravedad'       => $gravedads[array_rand($gravedads)],
                'estado'         => $estados[array_rand($estados)],
                'reportado_Por'  => $users[array_rand($users)],
            ]);
        }
    }
}

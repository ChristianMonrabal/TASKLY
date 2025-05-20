<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LogroCompleto;
use App\Models\Logro;

class LogrosCompletadosSeeder extends Seeder
{
    public function run(): void
    {
        // ID del logro que representa "Valoración Perfecta"
        $logroValoracionPerfecta = Logro::where('nombre', 'Valoración Perfecta')->first();

        if (!$logroValoracionPerfecta) {
            $this->command->error('El logro "Valoración Perfecta" no existe.');
            return;
        }

        // Obtener todos los usuarios
        $usuarios = User::all();

        foreach ($usuarios as $usuario) {
            $media = $usuario->valoracionesRecibidas()->avg('puntuacion');

            if ($media == 5) {
                // Evitar duplicados
                $yaLoTiene = LogroCompleto::where('usuario_id', $usuario->id)
                                ->where('logro_id', $logroValoracionPerfecta->id)
                                ->exists();

                if (!$yaLoTiene) {
                    LogroCompleto::create([
                        'usuario_id' => $usuario->id,
                        'logro_id' => $logroValoracionPerfecta->id,
                    ]);

                    $this->command->info("Insignia otorgada a: {$usuario->nombre} {$usuario->apellidos}");
                }
            }
        }
    }
}

<?php

namespace App\Observers;

use App\Models\Valoracion;
use App\Models\Logro;
use App\Models\LogroCompleto;

class ValoracionObserver
{
    /**
     * Se ejecuta justo después de que una nueva valoración se haya guardado.
     */
    public function created(Valoracion $valoracion)
    {
        // 1) Verificar si esta valoración tiene puntuación 5
        if ((int) $valoracion->puntuacion === 5) {
            // 2) Buscar el logro "Valoración perfecta"
            $logro = Logro::where('nombre', 'Valoración perfecta')->first();

            if ($logro) {
                $trabajador = $valoracion->trabajador;

                // 3) Evita duplicados
                $yaLoTiene = $trabajador->logrosCompletados()
                                        ->where('logro_id', $logro->id)
                                        ->exists();

                if (! $yaLoTiene) {
                    LogroCompleto::create([
                        'logro_id' => $logro->id,
                        'usuario_id' => $trabajador->id
                    ]);
                }
            }
        }
    }
}

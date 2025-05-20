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
        // 1) Calcula la media de todas las valoraciones del trabajador
        $media = $valoracion
            ->trabajador
            ->valoracionesRecibidas()
            ->avg('puntuacion');

        // 2) Si la media es exactamente 5, busca el logro "Valoración perfecta" (con p minúscula)
        if ((float)$media === 5.0) {
            $logro = Logro::where('nombre', 'Valoración perfecta')->first();
            if ($logro) {
                // 3) Evita duplicados y lo crea
                $trabajador = $valoracion->trabajador;
                $yaLoTiene = $trabajador->logrosCompletados()
                                       ->where('logro_id', $logro->id)
                                       ->exists();

                if (! $yaLoTiene) {
                    // Usar directamente el modelo LogroCompleto para crear la relación
                    LogroCompleto::create([
                        'logro_id' => $logro->id,
                        'usuario_id' => $trabajador->id
                    ]);
                }
            }
        }
    }
}

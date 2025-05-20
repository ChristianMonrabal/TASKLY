<?php

namespace App\Observers;

use App\Models\Pago;
use App\Models\Logro;
use App\Models\LogroCompleto;
use App\Models\Postulacion;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PagoObserver
{
    /**
     * Se ejecuta justo después de que un nuevo pago se haya guardado.
     */
    public function created(Pago $pago)
    {
        // Obtener la postulación asociada al pago
        $postulacion = $pago->postulacion;
        if (!$postulacion) {
            Log::warning('No se encontró la postulación asociada al pago ID: ' . $pago->id);
            return;
        }
        
        Log::info('Pago registrado para postulación ID: ' . $postulacion->id . ' - Trabajo ID: ' . $postulacion->trabajo_id);
        
        $trabajadorId = $postulacion->trabajador_id;
        $trabajador = User::find($trabajadorId);
        
        if (!$trabajador) {
            Log::warning('No se encontró el trabajador para el pago ID: ' . $pago->id);
            return;
        }
        
        // Verificar el logro "Primer trabajo"
        $this->verificarLogroPrimerTrabajo($trabajador);
        
        // Verificar los logros de trabajos completados (10, 50, 100)
        $this->verificarLogrosTrabajoCompletados($trabajador);
    }
    
    /**
     * Verifica y otorga el logro "Primer trabajo" si corresponde
     */
    private function verificarLogroPrimerTrabajo($trabajador)
    {
        $logro = Logro::where('nombre', 'Primer trabajo')->first();
        if (!$logro) {
            Log::warning('No se encontró el logro "Primer trabajo" en la base de datos');
            return;
        }
        
        // Verificar si ya tiene el logro
        $yaLoTiene = $trabajador->logrosCompletados()
                      ->where('logro_id', $logro->id)
                      ->exists();
                      
        if (!$yaLoTiene) {
            // Otorgar el logro
            LogroCompleto::create([
                'usuario_id' => $trabajador->id,
                'logro_id' => $logro->id
            ]);
            
            Log::info("Logro 'Primer trabajo' otorgado al usuario ID: " . $trabajador->id);
        }
    }
    
    /**
     * Verifica y otorga los logros de trabajos completados (10, 50, 100)
     */
    private function verificarLogrosTrabajoCompletados($trabajador)
    {
        // Obtener los logros relacionados con número de trabajos
        $logro10 = Logro::where('nombre', '10 trabajos completados')->first();
        $logro50 = Logro::where('nombre', '50 trabajos completados')->first();
        $logro100 = Logro::where('nombre', '100 trabajos completados')->first();
        
        if (!$logro10 || !$logro50 || !$logro100) {
            Log::warning('No se encontraron todos los logros de trabajos completados en la base de datos');
            return;
        }
        
        // Contar trabajos completados (con pago)
        $totalTrabajosCompletados = Postulacion::where('trabajador_id', $trabajador->id)
                                   ->whereHas('pago')
                                   ->count();
        
        Log::info("Usuario ID: {$trabajador->id} - Total trabajos completados: {$totalTrabajosCompletados}");
        
        // Verificar y otorgar los logros correspondientes
        if ($totalTrabajosCompletados >= 100) {
            $this->otorgarLogroSiNoLoTiene($trabajador, $logro100);
            $this->otorgarLogroSiNoLoTiene($trabajador, $logro50);
            $this->otorgarLogroSiNoLoTiene($trabajador, $logro10);
        } elseif ($totalTrabajosCompletados >= 50) {
            $this->otorgarLogroSiNoLoTiene($trabajador, $logro50);
            $this->otorgarLogroSiNoLoTiene($trabajador, $logro10);
        } elseif ($totalTrabajosCompletados >= 10) {
            $this->otorgarLogroSiNoLoTiene($trabajador, $logro10);
        }
    }
    
    /**
     * Otorga un logro al usuario si no lo tiene ya
     */
    private function otorgarLogroSiNoLoTiene($trabajador, $logro)
    {
        $yaLoTiene = $trabajador->logrosCompletados()
                      ->where('logro_id', $logro->id)
                      ->exists();
                      
        if (!$yaLoTiene) {
            LogroCompleto::create([
                'usuario_id' => $trabajador->id,
                'logro_id' => $logro->id
            ]);
            
            Log::info("Logro '{$logro->nombre}' otorgado al usuario ID: " . $trabajador->id);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajo;
use App\Models\DatosBancarios;
use App\Models\Postulacion;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function show(Trabajo $trabajo)
    {
        $postulacion = Postulacion::where('trabajo_id', $trabajo->id)
            ->where('estado_id', 10)
            ->firstOrFail();
        $trabajadorId = $postulacion->trabajador_id;

        return view('payment', compact('trabajo', 'trabajadorId'));
    }

    public function createIntent(Request $request)
    {
        try {
            // Validar los parámetros recibidos
            $request->validate([
                'trabajo_id' => 'required|exists:trabajos,id',
                'trabajador_id' => 'required|exists:users,id',
            ]);

            Stripe::setApiKey(config('services.stripe.secret'));

            $trabajo = Trabajo::findOrFail($request->trabajo_id);
            $trabajadorId = $request->trabajador_id;
            $datos = DatosBancarios::where('usuario_id', $trabajadorId)->first();

            if (!$datos) {
                return response()->json(['error' => 'El trabajador no tiene datos bancarios configurados'], 400);
            }

            if (!$datos->stripe_account_id) {
                return response()->json(['error' => 'El trabajador no tiene cuenta de Stripe configurada'], 400);
            }

            $amount = (int) ($trabajo->precio * 100);

            // Iniciar transacción para asegurar atomicidad
            DB::beginTransaction();
            
            try {
                $intent = PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => 'eur',
                    'transfer_data' => ['destination' => $datos->stripe_account_id],
                    'metadata' => ['trabajo_id' => $trabajo->id, 'trabajador_id' => $trabajadorId],
                    'description' => "Pago por '{$trabajo->titulo}'"
                ]);
                
                // Actualizar el estado de la postulación si es necesario
                // Aquí podrías agregar lógica para marcar la postulación como "en proceso de pago"
                
                DB::commit();
                
                return response()->json([
                    'clientSecret' => $intent->client_secret,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error al crear PaymentIntent: ' . $e->getMessage());
                return response()->json(['error' => 'Error al procesar el pago: ' . $e->getMessage()], 500);
            }
        } catch (ApiErrorException $e) {
            Log::error('Error de API de Stripe: ' . $e->getMessage());
            return response()->json(['error' => 'Error del servicio de pagos: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Error general: ' . $e->getMessage());
            return response()->json(['error' => 'Error del servidor: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Actualiza el estado de un trabajo después de un pago exitoso.
     */
    public function updatePaymentStatus(Request $request)
    {
        try {
            $request->validate([
                'payment_intent_id' => 'required|string',
            ]);
            
            Stripe::setApiKey(config('services.stripe.secret'));
            
            $intent = PaymentIntent::retrieve($request->payment_intent_id);
            
            if ($intent->status !== 'succeeded') {
                return response()->json(['error' => 'El pago no se ha completado correctamente'], 400);
            }
            
            DB::beginTransaction();
            
            try {
                // Obtener los datos del trabajo y la postulación
                $trabajoId = $intent->metadata->trabajo_id;
                $trabajadorId = $intent->metadata->trabajador_id;
                
                // Actualizar el estado de la postulación a "Pagado"
                $postulacion = Postulacion::where('trabajo_id', $trabajoId)
                    ->where('trabajador_id', $trabajadorId)
                    ->where('estado_id', 10) // Asumiendo que 10 es el estado "aceptado"
                    ->firstOrFail();
                
                // Actualizar a estado pagado (suponiendo que tienes un ID de estado para "pagado")
                $postulacion->estado_id = 11; // Ajusta según tu sistema de estados
                $postulacion->save();
                
                // Opcional: actualizar el estado del trabajo si es necesario
                
                DB::commit();
                
                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error al actualizar estado tras pago: ' . $e->getMessage());
                return response()->json(['error' => 'Error al actualizar el estado del trabajo'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error en updatePaymentStatus: ' . $e->getMessage());
            return response()->json(['error' => 'Error del servidor'], 500);
        }
    }
}

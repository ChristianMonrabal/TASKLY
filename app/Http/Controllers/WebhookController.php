<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;
use App\Models\Postulacion;
use App\Models\Trabajo;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload    = $request->getContent();
        $sigHeader  = $request->header('Stripe-Signature');
        $whsec      = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $whsec);

            // Manejar diferentes tipos de eventos
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    return $this->handlePaymentIntentSucceeded($event->data->object);
                case 'payment_intent.payment_failed':
                    return $this->handlePaymentIntentFailed($event->data->object);
                default:
                    Log::info("Evento de Stripe no manejado: {$event->type}");
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Webhook error: '.$e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        }
    }

    /**
     * Maneja el evento de pago exitoso
     */
    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        Log::info("Pago completado: {$paymentIntent->id}");
        
        // Verificar si hay metadatos
        if (!isset($paymentIntent->metadata->trabajo_id) || !isset($paymentIntent->metadata->trabajador_id)) {
            Log::warning("PaymentIntent {$paymentIntent->id} no tiene los metadatos necesarios");
            return response()->json(['status' => 'success']);
        }
        
        $trabajoId = $paymentIntent->metadata->trabajo_id;
        $trabajadorId = $paymentIntent->metadata->trabajador_id;
        
        // Actualizar estado de postulación y trabajo
        DB::beginTransaction();
        
        try {
            // Buscar la postulación
            $postulacion = Postulacion::where('trabajo_id', $trabajoId)
                ->where('trabajador_id', $trabajadorId)
                ->where('estado_id', 10) // Estado "Aceptado"
                ->first();
            
            if (!$postulacion) {
                Log::warning("No se encontró postulación para trabajo_id={$trabajoId}, trabajador_id={$trabajadorId}");
                DB::rollBack();
                return response()->json(['status' => 'success']);
            }
            
            // Actualizar a estado pagado
            $postulacion->estado_id = 11; // Ajusta según tu sistema de estados para "Pagado"
            $postulacion->save();
            
            // Opcional: actualizar el estado del trabajo
            $trabajo = Trabajo::find($trabajoId);
            if ($trabajo) {
                // Actualizar estado del trabajo si es necesario
                // $trabajo->estado_id = X; // Estado "En progreso" o "Pagado"
                // $trabajo->save();
            }
            
            DB::commit();
            
            Log::info("Estado de postulación actualizado para PI: {$paymentIntent->id}");
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar estado post-pago: {$e->getMessage()}");
            return response()->json(['status' => 'success']); // Devolvemos success a Stripe para evitar reintentos
        }
    }
    
    /**
     * Maneja el evento de pago fallido
     */
    protected function handlePaymentIntentFailed($paymentIntent)
    {
        $errorMessage = 'No error message';
        if (isset($paymentIntent->last_payment_error) && isset($paymentIntent->last_payment_error->message)) {
            $errorMessage = $paymentIntent->last_payment_error->message;
        }
        
        Log::warning("Pago fallido: {$paymentIntent->id}, Error: {$errorMessage}");
        
        // Aquí podrías actualizar el estado de la postulación o enviar notificaciones
        
        return response()->json(['status' => 'success']);
    }
}

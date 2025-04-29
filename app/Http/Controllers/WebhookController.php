<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

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

            if ($event->type === 'payment_intent.succeeded') {
                $pi = $event->data->object;
                // … tu lógica post-pago …
                Log::info("Pago completado: {$pi->id}");
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Webhook error: '.$e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        }
    }
}

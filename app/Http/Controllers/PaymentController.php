<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajo;
use App\Models\DatosBancarios;
use App\Models\Postulacion;
use Stripe\Stripe;
use Stripe\PaymentIntent;

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
        Stripe::setApiKey(config('services.stripe.secret'));

        $trabajo = Trabajo::findOrFail($request->trabajo_id);
        $trabajadorId = $request->trabajador_id;
        $datos = DatosBancarios::where('usuario_id', $trabajadorId)->firstOrFail();

        if (!$datos->stripe_account_id) {
            return response()->json(['error' => 'El trabajador no tiene cuenta de Stripe configurada'], 400);
        }

        $amount = (int) ($trabajo->precio * 100);

        $intent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'eur',
            'transfer_data' => ['destination' => $datos->stripe_account_id],
            'metadata' => ['trabajo_id' => $trabajo->id, 'trabajador_id' => $trabajadorId],
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
        ]);
    }
}

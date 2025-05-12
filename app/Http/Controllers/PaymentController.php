<?php

namespace App\Http\Controllers;

use App\Models\Postulacion;
use App\Models\DatosBancarios;
use App\Models\Trabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    /**
     * Constructor que inicializa la API de Stripe
     */
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    
    /**
     * Muestra la página de pago
     */
    public function show(Trabajo $trabajo)
    {
        if (Auth::check() && Auth::id() !== $trabajo->cliente_id) {
            return redirect()->route('trabajos.index')
                ->with('error', 'No tienes permiso para realizar este pago');
        }

        $postulacion = Postulacion::where('trabajo_id', $trabajo->id)
            ->where('estado_id', 10) // Estado aceptado
            ->first();

        if (! $postulacion) {
            return redirect()->route('candidatos.trabajo', $trabajo->id)
                ->with('error', 'No hay un trabajador aceptado para este trabajo');
        }

        $trabajador = $postulacion->trabajador;

        return view('payment', [
            'trabajador'     => $trabajador,
            'trabajo'        => $trabajo,
            'postulacion'    => $postulacion,
            'stripe_key'     => config('services.stripe.key'),
            'usuario_actual' => Auth::user(),
        ]);
    }
    
    /**
     * Crea un PaymentIntent de Stripe (Destination Charges)
     */
    public function createIntent(Request $request)
    {
        $validated = $request->validate([
            'trabajo_id'    => 'required|integer|exists:trabajos,id',
            'trabajador_id' => 'required|integer|exists:users,id',
            'amount'        => 'required|numeric|min:1',
        ]);

        $amount = (int) ($validated['amount'] * 100);
        $trabajadorId = $validated['trabajador_id'];

        // Mapeo user_id → Stripe Connect account ID
        $cuentas = [
            2 => 'acct_1RLmSdIJN9D9qNg6',  // Alex
            3 => 'acct_1RLmMlIE2Y4Yj6ja',  // Daniel
            4 => 'acct_1RLTsZIxgDk5hYr7',  // Christian
        ];
        $accountId = $cuentas[$trabajadorId] ?? null;

        // Fallback: si no está en el array, lo leemos de la BD
        if (! $accountId) {
            $datosB = DatosBancarios::where('usuario_id', $trabajadorId)->first();
            $accountId = $datosB->stripe_account_id ?? null;
        }

        if (! $accountId) {
            return response()->json(['error' => 'El trabajador no tiene cuenta Stripe configurada'], 400);
        }

        $applicationFee = (int) ($amount * 0.1); // 10% plataforma

        Log::info('Creando PaymentIntent (Destination Charges)', [
            'amount'          => $amount,
            'application_fee' => $applicationFee,
            'destination'     => $accountId,
        ]);

        try {
            $stripe = new StripeClient(config('services.stripe.secret'));

            $intent = $stripe->paymentIntents->create([
                'amount'                 => $amount,
                'currency'               => 'eur',
                'payment_method_types'   => ['card'],
                'application_fee_amount' => $applicationFee,
                'transfer_data'          => [
                    'destination' => $accountId,
                ],
                'metadata'               => [
                    'trabajo_id'    => $validated['trabajo_id'],
                    'trabajador_id' => $trabajadorId,
                ],
            ]);

            return response()->json([
                'clientSecret' => $intent->client_secret,
                'account_id'   => $accountId,
            ]);
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Actualiza el estado del pago tras la confirmación en el frontend.
     * Con Destination Charges no hace falta Transfer manual.
     */
    public function updatePaymentStatus(Request $request)
    {
        $validated = $request->validate([
            'payment_intent_id' => 'required|string',
            'trabajo_id'        => 'required|integer',
            'trabajador_id'     => 'required|integer',
        ]);

        $intent = PaymentIntent::retrieve($validated['payment_intent_id']);

        if ($intent->status === 'succeeded') {
            $post = Postulacion::where('trabajo_id', $validated['trabajo_id'])
                ->where('trabajador_id', $validated['trabajador_id'])
                ->first();

            if (! $post) {
                return response()->json(['error' => 'No se encontró la postulación'], 404);
            }

            $post->estado_id = 11; // pagado
            $post->save();

            return response()->json([
                'success' => true,
                'message' => 'Pago completado correctamente',
            ]);
        }

        return response()->json(['error' => 'El pago no ha sido completado'], 400);
    }
    
    /**
     * Verifica la configuración de Stripe
     */
    public function checkStripeConfig()
    {
        $publicKey = config('services.stripe.key');
        $secretKey = config('services.stripe.secret');
        
        return response()->json([
            'configured' => ! empty($publicKey) && ! empty($secretKey),
            'public_key' => $publicKey ?: null,
        ]);
    }
}

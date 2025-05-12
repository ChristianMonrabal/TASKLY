<?php

namespace App\Http\Controllers;

use App\Models\Postulacion;
use App\Models\DatosBancarios;
use App\Models\Trabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Transfer;
use Stripe\Account;

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
        // Validar que el trabajo existe y pertenece al usuario logueado
        if (Auth::check() && Auth::id() !== $trabajo->cliente_id) {
            return redirect()->route('trabajos.index')->with('error', 'No tienes permiso para realizar este pago');
        }
        
        // Verificar que hay un trabajador aceptado para este trabajo
        $postulacion = Postulacion::where('trabajo_id', $trabajo->id)
            ->where('estado_id', 10) // Estado aceptado
            ->first();
        
        if (!$postulacion) {
            return redirect()->route('candidatos.trabajo', $trabajo->id)->with('error', 'No hay un trabajador aceptado para este trabajo');
        }
        
        // Obtener información del trabajador
        $trabajador = $postulacion->trabajador;
        
        // Datos para la vista
        $datos = [
            'trabajador' => $trabajador,
            'trabajo' => $trabajo,
            'postulacion' => $postulacion,
            'stripe_key' => config('services.stripe.key'),
            'usuario_actual' => Auth::user()
        ];
        
        return view('payment', $datos);
    }
    
    /**
     * Crea un PaymentIntent de Stripe
     */
    public function createIntent(Request $request)
    {
        try {
            // Validación
            $validated = $request->validate([
                'trabajo_id' => 'required|integer|exists:trabajos,id',
                'trabajador_id' => 'required|integer|exists:users,id',
                'amount' => 'required|numeric|min:1'
            ]);
            
            // Convertir cantidad a centavos (Stripe usa centavos)
            $amount = (int)($validated['amount'] * 100);
            
            // Crear el PaymentIntent
            $intent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'eur',
                'metadata' => [
                    'trabajo_id' => $validated['trabajo_id'],
                    'trabajador_id' => $validated['trabajador_id']
                ]
            ]);
            
            // Devolver el client_secret que se usará en el frontend
            return response()->json([
                'clientSecret' => $intent->client_secret
            ]);
            
        } catch (ApiErrorException $e) {
            Log::error('Error al crear PaymentIntent: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Actualiza el estado del pago
     */
    public function updatePaymentStatus(Request $request)
    {
        try {
            // Validación
            $validated = $request->validate([
                'payment_intent_id' => 'required|string',
                'trabajo_id' => 'required|integer',
                'trabajador_id' => 'required|integer'
            ]);
            
            $trabajoId = $validated['trabajo_id'];
            $trabajadorId = $validated['trabajador_id'];
            
            // Recuperamos el PaymentIntent
            $intent = PaymentIntent::retrieve($request->payment_intent_id);
            
            if ($intent->status === 'succeeded') {
                // PASO 1: Actualizar el estado de la postulación
                $postulacion = Postulacion::where('trabajo_id', $trabajoId)
                    ->where('trabajador_id', $trabajadorId)
                    ->first();
                
                if (!$postulacion) {
                    Log::error('No se encontró la postulación para actualizar el estado de pago', [
                        'trabajo_id' => $trabajoId,
                        'trabajador_id' => $trabajadorId
                    ]);
                    
                    return response()->json([
                        'error' => 'No se encontró la postulación'
                    ], 404);
                }
                
                // Actualizar el estado a pagado (suponiendo que el ID de estado pagado es 11)
                $postulacion->estado_id = 11; // Estado pagado
                $postulacion->save();
                
                // PASO 2: Transferencia al trabajador desde la plataforma
                // Buscamos la cuenta conectada del trabajador, basado en los valores proporcionados
                
                // Asignamos directamente los IDs de cuentas correctos
                $cuentasStripe = [
                    1 => 'acct_1RLTsZIxgDk5hYr7', // Christian (TASKLY)
                    2 => 'acct_1RLmSdIJN9D9qNg6', // Alex
                    3 => 'acct_1RLmMlIE2Y4Yj6ja'  // Juan/Daniel
                ];
                
                Log::info('Transferencia a trabajador - IDs específicos', [
                    'trabajador_id' => $trabajadorId,
                    'cuentas_disponibles' => array_keys($cuentasStripe)
                ]);
                
                // Obtenemos el ID de cuenta del trabajador
                $accountId = $cuentasStripe[$trabajadorId] ?? null;
                
                if (!$accountId) {
                    // Como plan B, buscamos en la base de datos
                    $datosBancarios = DatosBancarios::where('usuario_id', $trabajadorId)->first();
                    if ($datosBancarios && $datosBancarios->stripe_account_id) {
                        $accountId = $datosBancarios->stripe_account_id;
                    }
                }
                
                Log::info('ID de cuenta para la transferencia', [
                    'trabajador_id' => $trabajadorId,
                    'account_id' => $accountId
                ]);
                
                if ($accountId) {
                    try {
                        // Verificamos que la cuenta existe y está activa
                        $account = Account::retrieve($accountId);
                        
                        Log::info('Cuenta Stripe del trabajador', [
                            'account_id' => $accountId,
                            'charges_enabled' => $account->charges_enabled,
                            'payouts_enabled' => $account->payouts_enabled
                        ]);
                        
                        // Calculamos el monto a transferir (90% para el trabajador)
                        $amount = $intent->amount;
                        $transferAmount = (int)($amount * 0.9); // 90% para el trabajador
                        
                        if ($account->charges_enabled) {
                            Log::info('Iniciando transferencia al trabajador', [
                                'amount' => $transferAmount,
                                'currency' => 'eur',
                                'destination' => $accountId
                            ]);
                            
                            // Creamos la transferencia a la cuenta conectada
                            $transfer = Transfer::create([
                                'amount' => $transferAmount,
                                'currency' => 'eur',
                                'destination' => $accountId,
                                'transfer_group' => "TRABAJO-{$trabajoId}",
                                'metadata' => [
                                    'trabajo_id' => $trabajoId,
                                    'trabajador_id' => $trabajadorId
                                ]
                            ]);
                            
                            Log::info('Transferencia al trabajador completada con éxito', [
                                'transfer_id' => $transfer->id,
                                'amount' => $transferAmount / 100, // Convertimos de centavos a euros para el log
                                'destination' => $accountId
                            ]);
                        } else {
                            Log::warning('La cuenta Stripe del trabajador no está habilitada para recibir pagos', [
                                'account_id' => $accountId,
                                'charges_enabled' => $account->charges_enabled,
                                'payouts_enabled' => $account->payouts_enabled
                            ]);
                        }
                    } catch (\Exception $e) {
                        // Capturamos el error pero continuamos con el proceso
                        // El administrador podrá transferir manualmente más tarde
                        Log::error('Error al transferir fondos al trabajador: ' . $e->getMessage(), [
                            'error' => $e->getMessage(),
                            'trabajador_id' => $trabajadorId,
                            'stripe_account_id' => $accountId
                        ]);
                    }
                } else {
                    Log::warning('El trabajador no tiene cuenta Stripe configurada', [
                        'trabajador_id' => $trabajadorId
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pago completado correctamente'
                ]);
            } else {
                return response()->json([
                    'error' => 'El pago no ha sido completado'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error al actualizar el estado del trabajo: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al actualizar el estado del trabajo: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Verifica la configuración de Stripe
     */
    public function checkStripeConfig()
    {
        $publicKey = config('services.stripe.key');
        $secretKey = config('services.stripe.secret');
        
        $isConfigured = !empty($publicKey) && !empty($secretKey);
        
        return response()->json([
            'configured' => $isConfigured,
            'public_key' => $isConfigured ? $publicKey : null
        ]);
    }
}

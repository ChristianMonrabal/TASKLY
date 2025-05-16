<?php

namespace App\Http\Controllers;

use App\Models\Postulacion;
use App\Models\DatosBancarios;
use App\Models\Trabajo;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use App\Models\Estado;

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
        $trabajoId = $validated['trabajo_id'];
        
        // Obtenemos el trabajo para incluir su información en la descripción del pago
        $trabajo = Trabajo::find($trabajoId);
        $trabajoTitulo = $trabajo->titulo ?? 'Trabajo ID: ' . $trabajoId;
        
        // ID de la cuenta principal de TASKLY que recibirá la comisión
        // Obtenemos la cuenta principal de TASKLY de la base de datos (usuarioID = 1)
        $datosTASKLY = DatosBancarios::where('usuario_id', 1)->first();
        $tasklyAccountId = $datosTASKLY ? $datosTASKLY->stripe_account_id : 'acct_1RLTsZIxgDk5hYr7';
        
        // Obtenemos el ID de cuenta de Stripe Connect del trabajador
        $datosB = DatosBancarios::where('usuario_id', $trabajadorId)->first();
        
        // Si el trabajador no tiene datos bancarios configurados, devolvemos un error
        if (!$datosB || empty($datosB->stripe_account_id)) {
            Log::error("Error: El trabajador ID {$trabajadorId} no tiene cuenta Stripe configurada");
            return response()->json([
                'error' => 'El trabajador no tiene una cuenta para recibir pagos configurada.'
            ], 400);
        }
        
        // Usamos la cuenta Stripe del trabajador directamente de la BD
        $accountId = $datosB->stripe_account_id;
        Log::info("Usando cuenta Stripe existente para trabajador ID: {$trabajadorId}: {$accountId}");
        
        Log::info("Usando cuenta de Stripe: {$accountId} para el trabajador ID: {$trabajadorId}");

        // Comisión de TASKLY (10% del monto total)
        // Este monto se enviará automáticamente a la cuenta principal de TASKLY ($tasklyAccountId)
        $applicationFee = (int) ($amount * 0.1); // 10% para TASKLY

        // La plataforma TASKLY ($tasklyAccountId) siempre recibe la comisión automáticamente
        // ya que estamos usando la clave API de TASKLY para crear el PaymentIntent
        Log::info('Creando PaymentIntent (Destination Charges)', [
            'amount'          => $amount,
            'application_fee' => $applicationFee, // Esta comisión va para TASKLY
            'destination'     => $accountId,     // Cuenta del trabajador que recibirá el pago (menos la comisión)
            'platform'        => $tasklyAccountId, // Cuenta de TASKLY que recibirá la comisión
        ]);

        try {
            $stripe = new StripeClient(config('services.stripe.secret'));

            // Creamos el PaymentIntent con la comisión para TASKLY
            // La API key configurada en services.stripe.secret debe pertenecer a la cuenta de TASKLY ($tasklyAccountId)
            // para que la comisión (application_fee_amount) se destine automáticamente a esa cuenta
            // Preparamos una descripción clara para el pago en Stripe
            $descripcionPago = "Pago por: {$trabajoTitulo} - TASKLY";
            
            $intent = $stripe->paymentIntents->create([
                'amount'                 => $amount,
                'currency'               => 'eur',
                'payment_method_types'   => ['card'],
                'application_fee_amount' => $applicationFee,  // Comisión para TASKLY ($tasklyAccountId)
                'description'            => $descripcionPago, // Descripción del pago que aparecerá en Stripe
                'statement_descriptor_suffix' => 'TASKLY',  // Sufijo que aparece en el extracto bancario
                'transfer_data'          => [
                    'destination' => $accountId,  // Cuenta del trabajador que recibe el pago (menos la comisión)
                ],
                'metadata'               => [
                    'trabajo_id'    => $trabajoId,
                    'trabajo_titulo'=> $trabajoTitulo,
                    'trabajador_id' => $trabajadorId,
                    'platform'      => $tasklyAccountId, // ID de la cuenta de TASKLY
                    'fecha_pago'    => now()->format('Y-m-d H:i:s'),
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
            
            // Obtener datos del trabajo y del trabajador para la valoración
            $trabajo = Trabajo::find($validated['trabajo_id']);
            
            // Actualizar el estado del trabajo a Completado
            $estadoCompletadoId = Estado::where('nombre', 'Completado')->first()->id;
            $trabajo->estado_id = $estadoCompletadoId; // Completado
            $trabajo->save();
            
            Log::info('Trabajo ID: ' . $trabajo->id . ' actualizado a estado Completado');
            
            // Registramos el pago en la base de datos usando la relación con postulación
            try {
                // Usamos el ID de la postulación directamente
                Pago::create([
                    'postulacion_id' => $post->id, // Aquí está la relación clave
                    'cantidad' => $intent->amount / 100, // Convertimos de centavos a euros
                    'estado_id' => 11, // Pagado, ID coincide con el estado de la postulación
                    'metodo_id' => 1, // Stripe
                    'fecha_pago' => now()
                ]);
                
                // Log de éxito
                Log::info('Pago registrado correctamente para trabajo ID: ' . $validated['trabajo_id']);
            } catch (\Exception $e) {
                Log::error('Error al registrar pago: ' . $e->getMessage());
                // Continuamos aunque falle el registro del pago, ya se hizo en Stripe
            }
            
            // Devolvemos todos los datos necesarios de forma sencilla
            return response()->json([
                'success' => true,
                'message' => 'Pago completado correctamente',
                'valoracion_data' => [
                    'trabajo_id' => $validated['trabajo_id'],
                    'trabajador_id' => $validated['trabajador_id'],
                    'postulacion_id' => $post->id,
                    'redirect_url' => route('valoraciones.valoraciones')
                ]
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
    
    /**
     * Genera y permite descargar la factura de un trabajo
     */
    public function generarFactura(Trabajo $trabajo)
    {
        // Buscamos el pago relacionado con este trabajo
        $pago = Pago::whereHas('postulacion', function($query) use ($trabajo) {
            $query->where('trabajo_id', $trabajo->id);
        })->first();
        
        if (!$pago) {
            return redirect()->back()->with('error', 'No se encontró un pago para este trabajo');
        }
        
        // Datos para la factura
        $datos = [
            'numero_factura' => 'TASKLY-' . str_pad($pago->id, 6, '0', STR_PAD_LEFT),
            'fecha' => is_string($pago->fecha_pago) ? $pago->fecha_pago : $pago->fecha_pago->format('d/m/Y'),
            'cliente' => $trabajo->cliente->nombre,
            'trabajador' => $pago->postulacion->trabajador->nombre,
            'concepto' => $trabajo->titulo,
            'subtotal' => $pago->cantidad,
            'comision' => $pago->cantidad * 0.1, // 10% de comisión
            'total' => $pago->cantidad,
        ];
        
        // En un sistema real, aquí generaríamos un PDF
        // Por ahora, mostramos una vista con los datos
        return view('facturas.detalle', compact('datos', 'trabajo', 'pago'));
    }
}

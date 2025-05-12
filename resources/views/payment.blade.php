@extends('layouts.app')

@section('content')
<div class="container payment-container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h3 class="mb-0">Realizar Pago</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2">Detalles del Trabajo</h4>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p><strong>Título:</strong> {{ $trabajo->titulo }}</p>
                                    <p><strong>Descripción:</strong> {{ Str::limit($trabajo->descripcion, 100) }}</p>
                                </div>
                                <div>
                                    <p><strong>Precio:</strong> €{{ number_format($trabajo->precio, 2) }}</p>
                                    <p><strong>Categoría:</strong> {{ $trabajo->categoriastipotrabajo->first()->nombre ?? 'No especificada' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2">Información del Trabajador</h4>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p><strong>Nombre:</strong> {{ $trabajador->nombre }}</p>
                                    <p><strong>Email:</strong> {{ $trabajador->email }}</p>
                                </div>
                                <div>
                                    <p><strong>Experiencia:</strong> {{ $trabajador->perfil->experiencia ?? 'No especificada' }}</p>
                                    <p><strong>Valoración media:</strong> 
                                        @if(isset($trabajador->perfil) && $trabajador->perfil->valoracion_media)
                                            {{ number_format($trabajador->perfil->valoracion_media, 1) }}/5
                                        @else
                                            Sin valoraciones
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2">Resumen del Pago</h4>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p><strong>Importe:</strong> €{{ number_format($trabajo->precio, 2) }}</p>
                                    <p><strong>Comisión TASKLY (10%):</strong> €{{ number_format($trabajo->precio * 0.1, 2) }}</p>
                                </div>
                                <div>
                                    <p><strong>Trabajador recibe:</strong> €{{ number_format($trabajo->precio * 0.9, 2) }}</p>
                                    <p><strong>Total a pagar:</strong> <span class="text-danger font-weight-bold">€{{ number_format($trabajo->precio, 2) }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="border-bottom pb-2">Método de Pago</h4>
                            <div id="payment-message" class="alert d-none"></div>
                            
                            <form id="payment-form">
                                <input type="hidden" id="trabajo_id" value="{{ $trabajo->id }}">
                                <input type="hidden" id="trabajador_id" value="{{ $trabajador->id }}">
                                <input type="hidden" id="amount" value="{{ $trabajo->precio }}">
                                
                                <div id="payment-element" class="mb-3">
                                    <!-- El elemento de pago de Stripe se insertará aquí -->
                                </div>
                                
                                <button id="submit-button" class="btn btn-primary btn-lg btn-block">
                                    <div class="spinner d-none" id="spinner"></div>
                                    <span id="button-text">Pagar ahora</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .payment-container {
        min-height: 70vh;
    }
    
    #payment-element {
        margin-bottom: 24px;
    }
    
    #submit-button {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
        font-size: 16px;
        padding: 12px 16px;
        width: 100%;
        position: relative;
    }
    
    #submit-button:hover {
        background-color: #d85959;
    }
    
    .spinner,
    .spinner:before,
    .spinner:after {
        border-radius: 50%;
    }
    
    .spinner {
        color: #ffffff;
        font-size: 22px;
        text-indent: -99999px;
        margin: 0 auto;
        position: relative;
        width: 20px;
        height: 20px;
        box-shadow: inset 0 0 0 2px;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
    }
    
    .spinner:before,
    .spinner:after {
        position: absolute;
        content: '';
    }
    
    .spinner:before {
        width: 10.4px;
        height: 20.4px;
        background: var(--primary);
        border-radius: 20.4px 0 0 20.4px;
        top: -0.2px;
        left: -0.2px;
        -webkit-transform-origin: 10.4px 10.2px;
        transform-origin: 10.4px 10.2px;
        -webkit-animation: loading 2s infinite ease 1.5s;
        animation: loading 2s infinite ease 1.5s;
    }
    
    .spinner:after {
        width: 10.4px;
        height: 10.2px;
        background: var(--primary);
        border-radius: 0 10.2px 10.2px 0;
        top: -0.1px;
        left: 10.2px;
        -webkit-transform-origin: 0px 10.2px;
        transform-origin: 0px 10.2px;
        -webkit-animation: loading 2s infinite ease;
        animation: loading 2s infinite ease;
    }
    
    @keyframes loading {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    let stripe;
    let elements;
    let emailAddress = '{{ $usuario_actual->email }}';
    let clientSecretToPush;

    document.addEventListener('DOMContentLoaded', function() {
        initialize();
        document.querySelector("#payment-form").addEventListener("submit", handleSubmit);
    });

    // Inicialización de Stripe y elementos de pago
    async function initialize() {
        try {
            // Primero definimos stripe con la clave pública directamente
            stripe = Stripe('{{ $stripe_key }}');
            
            console.log('Stripe inicializado con clave:', '{{ $stripe_key }}');
            
            // Luego creamos el intent
            const intentResponse = await createPaymentIntent();
            
            if (!intentResponse || !intentResponse.clientSecret) {
                showError("Error al crear la intención de pago. Respuesta:", intentResponse);
                return;
            }
            
            // Guardamos clientSecret para usar luego
            clientSecretToPush = intentResponse.clientSecret;
            console.log('Intent creado, clientSecret obtenido');
            
            const appearance = {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#EC6A6A',
                }
            };
            
            // Creamos los elementos del formulario de pago
            elements = stripe.elements({ 
                clientSecret: clientSecretToPush,
                appearance,
            });
            
            const paymentElement = elements.create("payment");
            paymentElement.mount("#payment-element");
            console.log('Elementos de pago montados correctamente');
            
        } catch (error) {
            console.error("Error initialization:", error);
            showError("Error al inicializar el proceso de pago: " + error.message);
        }
    }

    async function createPaymentIntent() {
        const trabajo_id = document.getElementById('trabajo_id').value;
        const trabajador_id = document.getElementById('trabajador_id').value;
        const amount = document.getElementById('amount').value;
        
        console.log('Creando payment intent con datos:', { trabajo_id, trabajador_id, amount });
        
        try {
            const response = await fetch('{{ route("payment.create-intent") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    trabajo_id: trabajo_id,
                    trabajador_id: trabajador_id,
                    amount: amount
                })
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Error en la respuesta del servidor:', errorText);
                throw new Error('Error del servidor: ' + response.status);
            }
            
            const data = await response.json();
            console.log('Respuesta del servidor:', data);
            
            if (data.error) {
                throw new Error(data.error);
            }
            
            return data;
            
        } catch (error) {
            console.error("Error creating payment intent:", error);
            showError("Error al procesar tu solicitud de pago: " + error.message);
            throw error;
        }
    }

    async function handleSubmit(e) {
        e.preventDefault();
        setLoading(true);
        
        const trabajo_id = document.getElementById('trabajo_id').value;
        const trabajador_id = document.getElementById('trabajador_id').value;
        
        console.log('Iniciando confirmación de pago');
        
        // Verificar que stripe y elements estén inicializados
        if (!stripe || !elements) {
            console.error('Stripe o elements no están inicializados');
            showError('Error: El sistema de pago no se ha inicializado correctamente. Por favor, recarga la página.');
            setLoading(false);
            return;
        }
        
        try {
            console.log('Llamando a confirmPayment con clientSecret:', clientSecretToPush);
            
            const { error, paymentIntent } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: window.location.origin + "/payment/complete",
                    payment_method_data: {
                        billing_details: {
                            email: emailAddress
                        }
                    }
                },
                redirect: 'if_required'
            });
            
            if (error) {
                console.error('Error en confirmPayment:', error);
                showError(error.message);
                setLoading(false);
                return;
            }
            
            console.log('Pago confirmado:', paymentIntent);
            
            // Si llegamos aquí, el pago fue exitoso
            await updatePaymentStatus(trabajo_id, trabajador_id, clientSecretToPush);
            
        } catch (error) {
            console.error("Error confirming payment:", error);
            showError("Hubo un error al procesar tu pago: " + error.message);
            setLoading(false);
        }
    }
    
    async function updatePaymentStatus(trabajo_id, trabajador_id, client_secret) {
        try {
            console.log('Actualizando estado de pago:', { trabajo_id, trabajador_id, client_secret });
            
            // Obtenemos el payment_intent_id del client_secret
            const payment_intent_id = client_secret.split('_secret_')[0];
            console.log('PaymentIntent ID extraído:', payment_intent_id);
            
            const response = await fetch('{{ route("payment.update-status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_intent_id: payment_intent_id,
                    trabajo_id: trabajo_id,
                    trabajador_id: trabajador_id
                })
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Error en la respuesta de actualización:', errorText);
                throw new Error('Error del servidor: ' + response.status);
            }
            
            const data = await response.json();
            console.log('Respuesta de actualización:', data);
            
            if (data.error) {
                showError(data.error);
                setLoading(false);
                return;
            }
            
            // Mostrar mensaje de éxito
            document.getElementById('payment-message').textContent = 'Pago completado con éxito. Redirigiendo...';
            document.getElementById('payment-message').classList.remove('d-none', 'alert-danger');
            document.getElementById('payment-message').classList.add('alert-success');
            
            // Esperar un momento antes de redirigir
            setTimeout(() => {
                // Redirigimos a la página de pago completado
                window.location.href = '{{ route("payment.complete") }}';
            }, 2000);
            
        } catch (error) {
            console.error("Error updating payment status:", error);
            showError("El pago se ha realizado pero hubo un error al actualizar el estado: " + error.message);
            setLoading(false);
        }
    }

    function showError(message, details) {
        console.error('Error en el proceso de pago:', message, details || '');
        
        const messageDiv = document.getElementById("payment-message");
        messageDiv.textContent = message;
        messageDiv.classList.remove("d-none");
        messageDiv.classList.add("alert-danger");
        messageDiv.classList.remove("alert-success");
        
        // No ocultar automáticamente errores críticos
        // para que el usuario pueda leerlos
    }

    function setLoading(isLoading) {
        const submitButton = document.getElementById("submit-button");
        const spinner = document.getElementById("spinner");
        const buttonText = document.getElementById("button-text");
        
        if (isLoading) {
            submitButton.disabled = true;
            spinner.classList.remove("d-none");
            buttonText.classList.add("d-none");
        } else {
            submitButton.disabled = false;
            spinner.classList.add("d-none");
            buttonText.classList.remove("d-none");
        }
    }
</script>
@endsection

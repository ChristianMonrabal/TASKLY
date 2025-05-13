@extends('layouts.app')

@section('content')
<div class="container payment-container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h3 class="mb-0">Realizar Pago</h3>
                </div>
                <div class="card-body p-0">
                    <div class="row no-gutters">
                        <!-- Columna izquierda: Extracto del pago -->
                        <div class="col-md-5 border-right">
                            <div class="payment-summary p-4">
                                <div class="d-flex align-items-center mb-4">
                                    <img src="/img/logo.png" alt="TASKLY" height="40" class="mr-3">
                                    <div class="payment-badge">
                                        <i class="fas fa-shield-alt"></i> Pago Seguro
                                    </div>
                                </div>
                                
                                <h4 class="mb-3">{{ $trabajo->titulo }}</h4>
                                
                                <div class="worker-info d-flex align-items-center mb-4">
                                    <div class="avatar mr-2">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <span>{{ $trabajador->nombre }}</span>
                                </div>
                                
                                <div class="divider my-4"></div>
                                
                                <div class="price-breakdown">
                                    <h5 class="mb-3">Resumen del pago</h5>
                                    
                                    <div class="fee-summary mb-3">
                                        <div class="row mb-2">
                                            <div class="col-8">Subtotal</div>
                                            <div class="col-4 text-right">€{{ number_format($trabajo->precio, 2) }}</div>
                                        </div>
                                        <div class="row text-muted small mb-1">
                                            <div class="col-8">Comisión de la plataforma (10%)</div>
                                            <div class="col-4 text-right">€{{ number_format($trabajo->precio * 0.1, 2) }}</div>
                                        </div>
                                        <div class="row text-muted small">
                                            <div class="col-8">El trabajador recibe</div>
                                            <div class="col-4 text-right">€{{ number_format($trabajo->precio * 0.9, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="divider my-4"></div>
                                
                                <div class="total-amount mt-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Total a pagar:</span>
                                        <span class="font-weight-bold price-tag">€{{ number_format($trabajo->precio, 2) }}</span>
                                    </div>
                                </div>
                                
                                <div class="payment-guarantee mt-4 pt-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-lock text-primary mr-2"></i>
                                        <small class="text-muted">Pago seguro mediante cifrado SSL</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Columna derecha: Método de pago -->
                        <div class="col-md-7">
                            <div class="payment-method p-4">
                                <h4 class="mb-4">Método de Pago</h4>
                                <div id="payment-message" class="alert d-none"></div>
                                
                                <div class="payment-card-header mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-credit-card text-primary mr-2"></i>
                                        <h5 class="mb-0">Tarjeta de crédito o débito</h5>
                                    </div>
                                    <div class="mt-2 mb-3 accepted-cards">
                                        <i class="fab fa-cc-visa mx-1"></i>
                                        <i class="fab fa-cc-mastercard mx-1"></i>
                                        <i class="fab fa-cc-amex mx-1"></i>
                                    </div>
                                </div>
                                
                                <form id="payment-form">
                                    <input type="hidden" id="trabajo_id" value="{{ $trabajo->id }}">
                                    <input type="hidden" id="trabajador_id" value="{{ $trabajador->id }}">
                                    <input type="hidden" id="amount" value="{{ $trabajo->precio }}">
                                    
                                    <div id="payment-element" class="mb-4">
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
</div>
@endsection

@section('styles')
<style>
    :root {
        --payment-primary: #EC6A6A;
        --payment-light: #f9f9f9;
        --payment-dark: #333;
        --payment-border: #eaeaea;
    }
    
    .payment-container {
        min-height: 70vh;
    }
    
    .card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background-color: var(--primary);
        border: none;
        padding: 15px;
    }
    
    .payment-info {
        background-color: var(--payment-light);
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .payment-badge {
        background-color: rgba(236, 106, 106, 0.1);
        color: var(--primary);
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .payment-badge i {
        margin-right: 5px;
    }
    
    .price-tag {
        font-size: 28px;
        font-weight: 700;
        color: var(--payment-dark);
    }
    
    .currency {
        font-size: 18px;
        vertical-align: top;
        margin-right: 2px;
    }
    
    .worker-info {
        background-color: rgba(236, 106, 106, 0.05);
        border-radius: 30px;
        padding: 8px 15px;
        display: inline-flex;
    }
    
    .avatar i {
        font-size: 22px;
        color: var(--primary);
    }
    
    .divider {
        height: 1px;
        background-color: var(--payment-border);
        width: 100%;
    }
    
    .fee-summary {
        background-color: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
    }
    
    .fee-summary .row {
        margin-bottom: 5px;
    }
    
    .total-amount {
        font-size: 18px;
    }
    
    .total-amount .font-weight-bold {
        color: var(--primary);
        font-size: 22px;
    }
    
    #payment-element {
        margin: 20px 0;
        background-color: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    
    #submit-button {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
        font-size: 16px;
        padding: 12px 16px;
        width: 100%;
        position: relative;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    #submit-button:hover {
        background-color: #d85959;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(236, 106, 106, 0.3);
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            
            // Registra los datos para depuración
            console.log('Respuesta completa del servidor:', data);
            
            // Guarda los datos importantes inmediatamente en sessionStorage
            if (data.valoracion_data) {
                sessionStorage.setItem('trabajo_id', data.valoracion_data.trabajo_id);
                sessionStorage.setItem('trabajador_id', data.valoracion_data.trabajador_id);
                sessionStorage.setItem('postulacion_id', data.valoracion_data.postulacion_id);
            } else {
                // Datos fallback si no vienen del backend
                sessionStorage.setItem('trabajo_id', trabajo_id);
                sessionStorage.setItem('trabajador_id', trabajador_id);
            }
            
            // Preparamos la URL a la que redirigir a la página de valoraciones existente
            let baseUrl = data.valoracion_data && data.valoracion_data.redirect_url 
                ? data.valoracion_data.redirect_url 
                : '{{ route("valoraciones.valoraciones") }}';
                
            // Añadimos los parámetros necesarios a la URL para pasarlos al backend
            let redirectUrl = baseUrl;
            if (data.valoracion_data) {
                redirectUrl = baseUrl + '?trabajo_id=' + data.valoracion_data.trabajo_id + 
                              '&trabajador_id=' + data.valoracion_data.trabajador_id + 
                              '&postulacion_id=' + data.valoracion_data.postulacion_id;
            }
            
            console.log('URL de redirección preparada:', redirectUrl);
            
            // Mostrar notificación simple con redirección directa
            console.log('Mostrando SweetAlert y preparando redirección a:', redirectUrl);
            
            // Primero agregamos la redirección con un pequeño retraso para asegurar que funcione
            setTimeout(function() {
                window.location.href = redirectUrl;
            }, 2000);
            
            // Luego mostramos el SweetAlert (la redirección ocurrirá de todos modos)
            Swal.fire({
                icon: 'success',
                title: '¡Pago completado con éxito!',
                text: 'El trabajador ha sido notificado. Redirigiendo a valoraciones...',
                showConfirmButton: false,
                timer: 1500,
                allowOutsideClick: false
            });
            
            // Por si acaso el SweetAlert no funciona, redireccionar después de 5 segundos
            setTimeout(function() {
                console.log('Redirección por timeout de seguridad');
                window.location.href = redirectUrl;
            }, 5000);
            
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

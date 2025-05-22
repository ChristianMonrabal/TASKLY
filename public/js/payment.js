// Variables globales para Stripe
let stripe;
let elements;
let clientSecretToPush;
let paymentIntent;

document.addEventListener('DOMContentLoaded', function() {
    // Capturar email y stripe_key desde data attributes
    const paymentForm = document.querySelector("#payment-form");
    const emailAddress = paymentForm.dataset.email;
    const stripeKey = paymentForm.dataset.stripeKey;
    
    initialize(stripeKey);
    paymentForm.addEventListener("submit", handleSubmit);
});

// Inicialización de Stripe y elementos de pago
async function initialize(stripeKey) {
    try {
        // Primero definimos stripe con la clave pública
        stripe = Stripe(stripeKey);
        
        console.log('Stripe inicializado con clave:', stripeKey);
        
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
    const createIntentUrl = document.getElementById('payment-form').dataset.createIntentUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    console.log('Creando payment intent con datos:', { trabajo_id, trabajador_id, amount });
    
    try {
        const response = await fetch(createIntentUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
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
    const emailAddress = document.getElementById('payment-form').dataset.email;
    
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
        const updateStatusUrl = document.getElementById('payment-form').dataset.updateStatusUrl;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Obtenemos el payment_intent_id del client_secret
        const payment_intent_id = client_secret.split('_secret_')[0];
        console.log('PaymentIntent ID extraído:', payment_intent_id);
        
        const response = await fetch(updateStatusUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
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
        
        // Mostramos mensaje de éxito y redirigimos
        Swal.fire({
            title: '¡Pago completado con éxito!',
            html: `
                <div class="payment-success-container">
                <div class="payment-success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="payment-details">
                    <p>El pago de <strong>€${document.getElementById('amount').value}</strong> se ha procesado correctamente.</p>
                    <p class="payment-small">El 90% ha sido transferido al trabajador y el 10% corresponde a la comisión de TASKLY.</p>
                </div>
                <div class="taskly-tagline">
                    <p>¡Gracias por usar TASKLY!</p>
                </div>
                </div>
            `,
            icon: 'success',
            confirmButtonColor: '#EC6A6A',
            confirmButtonText: 'Valora a tu trabajador',
        }).then((result) => {
            if (result.isConfirmed) {
                // La ruta de valoraciones es valoraciones.valoraciones (/valoraciones?...)
                // Según el archivo de rutas web.php
                const valoracionesUrl = `/valoraciones?trabajo_id=${trabajo_id}&trabajador_id=${trabajador_id}`;
                window.location.href = data.redirect_url || valoracionesUrl;
            }
        });
        
    } catch (error) {
        console.error('Error al actualizar el estado del pago:', error);
        showError('Error al finalizar la transacción: ' + error.message);
        setLoading(false);
    }
}

function showError(message, details) {
    console.error('Error en el proceso de pago:', message, details || '');
    
    // Mostrar error con SweetAlert para mejor experiencia de usuario
    Swal.fire({
        title: 'Error en el pago',
        text: message,
        icon: 'error',
        confirmButtonColor: '#EC6A6A'
    });
    
    // También mostrar en el formulario
    const messageDiv = document.getElementById("payment-message");
    messageDiv.textContent = message;
    messageDiv.classList.remove("d-none");
    messageDiv.classList.add("alert-danger");
    
    setLoading(false);
}

function setLoading(isLoading) {
    if (isLoading) {
        // Deshabilitamos el botón y mostramos el spinner
        document.querySelector("#submit-button").disabled = true;
        document.querySelector("#spinner").classList.remove("d-none");
        document.querySelector("#button-text").classList.add("d-none");
    } else {
        document.querySelector("#submit-button").disabled = false;
        document.querySelector("#spinner").classList.add("d-none");
        document.querySelector("#button-text").classList.remove("d-none");
    }
}

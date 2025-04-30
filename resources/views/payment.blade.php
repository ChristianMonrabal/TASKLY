@extends('layouts.app')

@section('title', 'Pagar con Stripe')

@section('styles')
    <!-- Bootstrap CSS opcional, si tu layout no lo incluye -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .payment-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        #card-element {
            border: 1px solid #ccc;
            padding: 12px;
            border-radius: 4px;
        }
        #card-errors {
            color: red;
            margin-top: 10px;
        }
        #boton-pago {
            width: 100%;
        }
    </style>
@endsection

@section('content')
<div class="container payment-container">
    <h2 class="text-center mb-4">Pagar 10,00 €</h2>

    <div id="card-element"><!-- Stripe Elements inserta el input --></div>
    <div id="card-errors" role="alert"></div>

    <button id="boton-pago" class="btn btn-primary mt-4">Pagar</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stripe = Stripe("{{ config('services.stripe.key') }}");
        const elements = stripe.elements();
        const card = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    '::placeholder': { color: '#aab7c4' }
                },
                invalid: {
                    color: '#9e2146'
                }
            }
        });
        card.mount('#card-element');

        card.on('change', event => {
            const displayError = document.getElementById('card-errors');
            displayError.textContent = event.error ? event.error.message : '';
        });

        document.getElementById('boton-pago').addEventListener('click', async () => {
            // Llama a tu ruta para crear el PaymentIntent
            const response = await fetch("{{ url('/pago/intent') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ amount: 1000 })
            });
            const { clientSecret } = await response.json();

            const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: { card: card }
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                alert('¡Pago realizado con éxito! ID: ' + paymentIntent.id);
                // Opcional: redirigir o actualizar UI
            }
        });
    });
</script>

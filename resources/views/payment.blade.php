<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Pagar con Stripe</title>
  <script src="https://js.stripe.com/v3/"></script>
  <style>
    /* Unos estilos mínimos para el campo de tarjeta */
    #card-element {
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 4px;
      max-width: 400px;
    }
    #card-errors {
      color: red;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <h1>Pagar 19,99 €</h1>

  <!-- Aquí montaremos el UI de Stripe Elements -->
  <div id="card-element"><!-- Stripe Elements injecta el input --></div>
  <div id="card-errors" role="alert"></div>

  <button id="boton-pago">Pagar</button>

  <script>
    // 1) Inicializa Stripe con tu clave pública
    const stripe = Stripe("{{ config('services.stripe.key') }}");

    // 2) Crea un objeto Elements y un elemento de tarjeta
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
    // 3) Monta el elemento en el DOM
    card.mount('#card-element');

    // 4) Muestra errores de validación en tiempo real
    card.on('change', e => {
      const displayError = document.getElementById('card-errors');
      displayError.textContent = e.error ? e.error.message : '';
    });

    // 5) Maneja el click de “Pagar”
    document.getElementById('boton-pago').addEventListener('click', async () => {
      // Crea el PaymentIntent en tu servidor
      const response = await fetch("{{ url('/pago/intent') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
      });
      const { clientSecret } = await response.json();

      // Confirma el pago pasando el elemento `card`
      const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
          card: card,
          billing_details: {
            // opcional: nombre del cliente
            name: 'Cliente de Prueba'
          }
        }
      });

      if (error) {
        // Error al procesar el pago
        document.getElementById('card-errors').textContent = error.message;
      } else if (paymentIntent && paymentIntent.status === 'succeeded') {
        // Pago completado
        alert('¡Pago realizado con éxito! ID: ' + paymentIntent.id);
      }
    });
  </script>
</body>
</html>

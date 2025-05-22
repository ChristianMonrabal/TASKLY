document.addEventListener('DOMContentLoaded', function () {
    // Referencias a elementos del formulario
    const titularInput = document.getElementById('titular');
    const ibanInput = document.getElementById('iban');
    const nombreBancoInput = document.getElementById('nombre_banco');
    const stripeAccountInput = document.getElementById('stripe_account_id');
    const submitButton = document.querySelector('button[type="submit"]');
    
    // Referencias para mensajes de error
    let errorMessages = {};
    
    // Crear elementos para mostrar errores
    function createErrorElement(inputElement, message) {
        // Verificar si ya existe un mensaje de error
        const existingError = inputElement.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.textContent = message;
            return existingError;
        }
        
        // Crear nuevo elemento de error
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        inputElement.parentNode.appendChild(errorElement);
        return errorElement;
    }
    
    // Eliminar mensaje de error
    function removeErrorElement(inputElement) {
        const errorElement = inputElement.parentNode.querySelector('.error-message');
        if (errorElement) {
            errorElement.remove();
        }
    }
    
    // Validación del titular
    titularInput.addEventListener('blur', function() {
        if (!titularInput.value.trim()) {
            errorMessages.titular = 'El titular de la cuenta es obligatorio';
            createErrorElement(titularInput, errorMessages.titular);
            titularInput.classList.add('invalid');
        } else {
            delete errorMessages.titular;
            removeErrorElement(titularInput);
            titularInput.classList.remove('invalid');
        }
        validateForm();
    });
    
    // Validación del IBAN
    ibanInput.addEventListener('blur', function() {
        const ibanValue = ibanInput.value.trim();
        // Expresión regular básica para IBAN español (ES seguido de 22 dígitos)
        const ibanRegex = /^ES[0-9]{22}$/;
        
        if (!ibanValue) {
            errorMessages.iban = 'El IBAN es obligatorio';
            createErrorElement(ibanInput, errorMessages.iban);
            ibanInput.classList.add('invalid');
        } else if (!ibanRegex.test(ibanValue.replace(/\s/g, ''))) {
            errorMessages.iban = 'El formato del IBAN no es válido (debe ser ES seguido de 22 dígitos)';
            createErrorElement(ibanInput, errorMessages.iban);
            ibanInput.classList.add('invalid');
        } else {
            delete errorMessages.iban;
            removeErrorElement(ibanInput);
            ibanInput.classList.remove('invalid');
        }
        validateForm();
    });
    
    // Validación del nombre del banco
    nombreBancoInput.addEventListener('blur', function() {
        if (!nombreBancoInput.value.trim()) {
            errorMessages.nombreBanco = 'El nombre del banco es obligatorio';
            createErrorElement(nombreBancoInput, errorMessages.nombreBanco);
            nombreBancoInput.classList.add('invalid');
        } else {
            delete errorMessages.nombreBanco;
            removeErrorElement(nombreBancoInput);
            nombreBancoInput.classList.remove('invalid');
        }
        validateForm();
    });
    
    // Validación del ID de cuenta Stripe
    stripeAccountInput.addEventListener('blur', function() {
        const stripeValue = stripeAccountInput.value.trim();
        
        if (!stripeValue) {
            errorMessages.stripeAccount = 'El ID de Stripe es obligatorio';
            createErrorElement(stripeAccountInput, errorMessages.stripeAccount);
            stripeAccountInput.classList.add('invalid');
        } else if (!stripeValue.startsWith('acct_') || stripeValue.length < 18) {
            errorMessages.stripeAccount = 'El ID de Stripe debe comenzar con "acct_" y tener al menos 18 caracteres';
            createErrorElement(stripeAccountInput, errorMessages.stripeAccount);
            stripeAccountInput.classList.add('invalid');
        } else {
            delete errorMessages.stripeAccount;
            removeErrorElement(stripeAccountInput);
            stripeAccountInput.classList.remove('invalid');
        }
        validateForm();
    });
    
    // Validación del formulario completo
    function validateForm() {
        // Deshabilitar o habilitar el botón de envío según si hay errores
        if (Object.keys(errorMessages).length > 0) {
            submitButton.disabled = true;
            submitButton.classList.add('disabled');
        } else {
            submitButton.disabled = false;
            submitButton.classList.remove('disabled');
        }
    }
    
    // Validación al enviar el formulario
    document.querySelector('form').addEventListener('submit', function(e) {
        // Simular blur en todos los campos para validar antes de enviar
        titularInput.dispatchEvent(new Event('blur'));
        ibanInput.dispatchEvent(new Event('blur'));
        nombreBancoInput.dispatchEvent(new Event('blur'));
        stripeAccountInput.dispatchEvent(new Event('blur'));
        
        // Prevenir envío si hay errores
        if (Object.keys(errorMessages).length > 0) {
            e.preventDefault();
            
            // Mostrar mensaje de error con SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Error de validación',
                text: 'Por favor, corrige los errores en el formulario antes de continuar.',
                confirmButtonColor: '#EC6A6A'
            });
        }
    });
});

    document.addEventListener('DOMContentLoaded', function () {
        const emailInput = document.getElementById('email');
        const emailGroup = document.getElementById('email-group');

        emailInput.addEventListener('blur', function () {
            const emailValue = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Elimina el error anterior si existe
            const existingError = emailGroup.querySelector('.email-error');
            if (existingError) {
                existingError.remove();
            }

            let errorMessage = '';
            if (emailValue === '') {
                errorMessage = 'El correo electrónico es obligatorio.';
            } else if (!emailRegex.test(emailValue)) {
                errorMessage = 'Introduce un correo electrónico válido.';
            }

            if (errorMessage) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'email-error';
                errorDiv.style.color = 'red';
                errorDiv.style.fontSize = '14px';
                errorDiv.style.marginTop = '5px';
                errorDiv.textContent = errorMessage;
                emailInput.style.borderColor = '#EC6A6A';
                emailGroup.appendChild(errorDiv);
            } else {
                emailInput.style.borderColor = ''; // Restaurar borde si todo está bien
            }
        });
    });
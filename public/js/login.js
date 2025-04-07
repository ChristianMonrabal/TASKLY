document.addEventListener('DOMContentLoaded', function () {
    function validateEmail(email) {
        emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        return emailPattern.test(email);
    }

    function validatePassword(password) {
        return password.length >= 8;
    }

    function validatePasswordConfirmation(password, passwordConfirmation) {
        return password === passwordConfirmation;
    }

    function showError(input, message) {
        input.style.borderColor = 'red';
        const errorMessageElement = document.getElementById(input.id + '-error');
        if (errorMessageElement) {
            errorMessageElement.textContent = message;
            errorMessageElement.style.color = 'red';
        }
    }

    function createErrorElements(form) {
        form.querySelectorAll('input').forEach(input => {
            const errorId = input.id + '-error';
            if (!document.getElementById(errorId)) {
                const errorElement = document.createElement('p');
                errorElement.id = errorId;
                errorElement.className = 'error-message';

                const wrapper = input.closest('.password-container') || input;
                wrapper.insertAdjacentElement('afterend', errorElement);
            }
        });
    }

    function handleSigninBlur(event) {
        const input = event.target;
        const form = input.closest('form');
        const errorMessageElement = document.getElementById(input.id + '-error');

        if (errorMessageElement) {
            errorMessageElement.textContent = '';
            input.style.borderColor = '';
        }

        if (input.name === 'email') {
            if (input.value === '') {
                showError(input, 'El campo de correo electrónico es obligatorio.');
            } else if (!validateEmail(input.value)) {
                showError(input, 'Por favor ingrese un correo electrónico válido.');
            }
        }

        if (input.name === 'password') {
            if (input.value === '') {
                showError(input, 'El campo de contraseña es obligatorio.');
            }
        }
    }

    function handleSignupBlur(event) {
        const input = event.target;
        const form = input.closest('form');
        const errorMessageElement = document.getElementById(input.id + '-error');

        if (errorMessageElement) {
            errorMessageElement.textContent = '';
            input.style.borderColor = '';
        }

        if (input.name === 'email') {
            if (input.value === '') {
                showError(input, 'El campo de correo electrónico es obligatorio.');
            } else if (!validateEmail(input.value)) {
                showError(input, 'Por favor ingrese un correo electrónico válido.');
            }
        }

        if (input.name === 'password') {
            if (input.value === '') {
                showError(input, 'El campo de contraseña es obligatorio.');
            } else if (!validatePassword(input.value)) {
                showError(input, 'La contraseña debe tener al menos 8 caracteres.');
            }
        }

        if (input.name === 'password_confirmation') {
            const passwordInput = form.querySelector('input[name="password"]');
            if (input.value === '') {
                showError(input, 'Este campo es obligatorio.');
            } else if (!validatePasswordConfirmation(passwordInput.value, input.value)) {
                showError(input, 'Las contraseñas no coinciden.');
            }
        }
    }

    const signinForm = document.getElementById('signin-form');
    const signupForm = document.getElementById('signup-form');

    if (signinForm) {
        createErrorElements(signinForm);
        signinForm.querySelectorAll('input').forEach(input => {
            input.addEventListener('blur', handleSigninBlur);
        });
    }

    if (signupForm) {
        createErrorElements(signupForm);
        signupForm.querySelectorAll('input').forEach(input => {
            input.addEventListener('blur', handleSignupBlur);
        });
    }
});

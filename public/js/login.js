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

    function validatePhone(phone) {
        return /^[0-9]{9}$/.test(phone);
    }

    function validateNIF(nif) {
        nifPattern = /^[0-9]{8}[A-Za-z]$/;
        if (!nifPattern.test(nif)) {
            return 'Formato de NIF incorrecto (8 números y 1 letra).';
        }

        dni = nif.slice(0, 8);
        letter = nif.slice(8).toUpperCase();
        validLetters = "TRWAGMYFPDXBNJZSQVHLCKE".split('');

        letterIndex = parseInt(dni) % 23;
        if (validLetters[letterIndex] !== letter) {
            return 'El número de DNI no es válido según la letra.';
        }

        return true;
    }

    function validateNIE(nie) {
        niePattern = /^[XYZ][0-9]{7}[A-Za-z]$/;
        if (!niePattern.test(nie)) {
            return 'Formato de NIE incorrecto (X/Y/Z seguido de 7 números y 1 letra).';
        }

        letterMapping = { X: '0', Y: '1', Z: '2' };
        dni = nie.replace(/^([XYZ])/, (match) => letterMapping[match]);
        letter = nie.slice(8).toUpperCase();
        validLetters = "TRWAGMYFPDXBNJZSQVHLCKE".split('');

        letterIndex = parseInt(dni) % 23;
        if (validLetters[letterIndex] !== letter) {
            return 'El número de NIE no es válido según la letra.';
        }

        return true;
    }

    function validatePostcode(postcode) {
        return /^[0-9]{5}$/.test(postcode);
    }

    function showError(input, message) {
        input.style.borderColor = 'red';
        errorMessageElement = document.getElementById(input.id + '-error');
        if (errorMessageElement) {
            errorMessageElement.textContent = message;
            errorMessageElement.style.color = 'red';
        }
    }

    function createErrorElements(form) {
        form.querySelectorAll('input, select').forEach(input => {
            errorId = input.id + '-error';
            if (!document.getElementById(errorId)) {
                errorElement = document.createElement('p');
                errorElement.id = errorId;
                errorElement.className = 'error-message';
                wrapper = input.closest('.password-container') || input;
                wrapper.insertAdjacentElement('afterend', errorElement);
            }
        });
    }

    function handleSignupBlur(event) {
        input = event.target;
        form = input.closest('form');
        errorMessageElement = document.getElementById(input.id + '-error');
        if (errorMessageElement) {
            errorMessageElement.textContent = '';
            input.style.borderColor = '';
        }

        name = form.querySelector('input[name="name"]');
        surname = form.querySelector('input[name="surname"]');
        phone = form.querySelector('input[name="phone"]');
        postcode = form.querySelector('input[name="postcode"]');
        docType = form.querySelector('select[name="doc_type"]');
        dni = form.querySelector('input[name="dni"]');

        if (input.name === 'name' && input.value.trim() === '') {
            showError(input, 'El nombre es obligatorio.');
        }

        if (input.name === 'surname' && input.value.trim() === '') {
            showError(input, 'Los apellidos son obligatorios.');
        }

        if (input.name === 'phone') {
            if (input.value.trim() === '') {
                showError(input, 'El teléfono es obligatorio.');
            } else if (!validatePhone(input.value.trim())) {
                showError(input, 'El teléfono debe tener 9 dígitos numéricos.');
            }
        }

        if (input.name === 'postcode') {
            if (input.value.trim() === '') {
                showError(input, 'El código postal es obligatorio.');
            } else if (!validatePostcode(input.value.trim())) {
                showError(input, 'El código postal debe tener 5 dígitos.');
            }
        }

        if (input.id === 'doc_type' && input.value === '') {
            showError(input, 'Selecciona un tipo de documento.');
            dni.disabled = true;
        } else if (input.id === 'doc_type' && input.value !== '') {
            dni.disabled = false;
        }

        if (input.name === 'dni') {
            type = docType.value;
            if (input.value.trim() === '') {
                showError(input, 'El campo DNI/NIE es obligatorio.');
            } else {
                let validationResponse = true;
                if (type === 'nif') {
                    validationResponse = validateNIF(input.value.trim());
                } else if (type === 'nie') {
                    validationResponse = validateNIE(input.value.trim());
                }

                if (validationResponse === true) {
                } else {
                    showError(input, validationResponse);
                }
            }
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
            passwordInput = form.querySelector('input[name="password"]');
            if (input.value === '') {
                showError(input, 'Este campo es obligatorio.');
            } else if (!validatePasswordConfirmation(passwordInput.value, input.value)) {
                showError(input, 'Las contraseñas no coinciden.');
            }
        }
    }

    signinForm = document.getElementById('signin-form');
    signupForm = document.getElementById('signup-form');

    if (signinForm) {
        createErrorElements(signinForm);
        signinForm.querySelectorAll('input').forEach(input => {
            input.addEventListener('blur', handleSigninBlur);
        });
    }

    if (signupForm) {
        createErrorElements(signupForm);
        signupForm.querySelectorAll('input, select').forEach(input => {
            input.addEventListener('blur', handleSignupBlur);
        });

        docTypeSelect = signupForm.querySelector('select[name="doc_type"]');
        dniInput = signupForm.querySelector('input[name="dni"]');
        docTypeSelect.addEventListener('change', function () {
            if (docTypeSelect.value === '') {
                dniInput.disabled = true;
            } else {
                dniInput.disabled = false;
            }
        });
    }
});

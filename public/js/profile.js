document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, textarea');

    inputs.forEach(input => {
        input.addEventListener('blur', function () {
            validateField(this);
        });
    });

    form.addEventListener('submit', function (e) {
        let isValid = true;
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Por favor, corrige los errores en el formulario antes de enviar.');
        }
    });

    function validateField(field) {
        if (field.readOnly) return true;

        clearError(field);

        switch (field.name) {
            case 'nombre':
            case 'apellidos':
                return validateRequired(field) && validateLength(field, 2, 50);
            case 'email':
                return validateRequired(field) && validateEmail(field);
            case 'telefono':
                if (field.value) return validatePhone(field);
                return true;
            case 'codigo_postal':
                if (field.value) return validatePostalCode(field);
                return true;
            case 'fecha_nacimiento':
                if (field.value) return validateBirthDate(field);
                return true;
            case 'descripcion':
                return true;
            default:
                return true;
        }
    }

    function validateRequired(field) {
        if (!field.value.trim()) {
            showError(field, 'Este campo es obligatorio');
            return false;
        }
        return true;
    }

    function validateLength(field, min, max) {
        if (field.value.length < min || field.value.length > max) {
            showError(field, `Debe tener entre ${min} y ${max} caracteres`);
            return false;
        }
        return true;
    }

    function validateEmail(field) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            showError(field, 'Ingresa un email válido');
            return false;
        }
        return true;
    }

    function validatePhone(field) {
        const phoneRegex = /^\d{9}$/;
        if (!phoneRegex.test(field.value)) {
            showError(field, 'El teléfono debe tener 9 dígitos');
            return false;
        }
        return true;
    }

    function validatePostalCode(field) {
        const postalRegex = /^\d{5}$/;
        if (!postalRegex.test(field.value)) {
            showError(field, 'El código postal debe tener 5 dígitos');
            return false;
        }
        return true;
    }

    function validateBirthDate(field) {
        const birthDate = new Date(field.value);
        const today = new Date();
        const minAgeDate = new Date(
            today.getFullYear() - 18,
            today.getMonth(),
            today.getDate()
        );

        if (birthDate > minAgeDate) {
            showError(field, 'Debes tener al menos 18 años');
            return false;
        }
        return true;
    }

    function showError(field, message) {
        field.style.borderColor = '#EC6A6A';

        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.color = '#EC6A6A';
        errorDiv.style.fontSize = '0.8rem';
        errorDiv.style.marginTop = '5px';
        errorDiv.textContent = message;

        field.parentNode.appendChild(errorDiv);
    }

    function clearError(field) {
        field.style.borderColor = '';

        const errorDiv = field.parentNode.querySelector('.error-message');
        if (errorDiv) {
            field.parentNode.removeChild(errorDiv);
        }
    }
});

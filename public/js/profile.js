document.addEventListener('DOMContentLoaded', function () {
    form = document.querySelector('form');
    inputs = form.querySelectorAll('input, textarea');

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
        emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            showError(field, 'Ingresa un email válido');
            return false;
        }
        return true;
    }

    function validatePhone(field) {
        phoneRegex = /^\d{9}$/;
        if (!phoneRegex.test(field.value)) {
            showError(field, 'El teléfono debe tener 9 dígitos');
            return false;
        }
        return true;
    }

    function validatePostalCode(field) {
        postalRegex = /^\d{5}$/;
        if (!postalRegex.test(field.value)) {
            showError(field, 'El código postal debe tener 5 dígitos');
            return false;
        }
        return true;
    }

    function validateBirthDate(field) {
        birthDate = new Date(field.value);
        today = new Date();
        minAgeDate = new Date(
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

        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.color = '#EC6A6A';
        errorDiv.style.fontSize = '0.8rem';
        errorDiv.style.marginTop = '5px';
        errorDiv.textContent = message;

        field.parentNode.appendChild(errorDiv);
    }

    function clearError(field) {
        field.style.borderColor = '';

        errorDiv = field.parentNode.querySelector('.error-message');
        if (errorDiv) {
            field.parentNode.removeChild(errorDiv);
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    fields = [
        'input[name="nombre"]',
        'input[name="apellidos"]',
        'input[name="email"]',
        'input[name="telefono"]',
        'input[name="codigo_postal"]',
        'input[name="fecha_nacimiento"]',
        'textarea[name="descripcion"]'
    ];

    anyEmpty = fields.some(selector => {
        element = document.querySelector(selector);
        return !element || !element.value.trim();
    });

    hasPhoto = document.querySelector('img.current-photo') !== null;

    if (anyEmpty || !hasPhoto) {
        Swal.fire({
            icon: 'warning',
            title: 'Completa tu perfil',
            text: 'Debes completar todos los campos del perfil y subir una foto para usar Taskly.',
            confirmButtonColor: '#EC6A6A'
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    select = document.getElementById('habilidades');

    if (select) {
        Array.from(select.options).forEach(option => {
            option.addEventListener('mousedown', function (e) {
                e.preventDefault();

                scrollTop = select.scrollTop;

                option.selected = !option.selected;

                setTimeout(() => {
                    select.scrollTop = scrollTop;
                }, 0); 
            });
        });
    }
});

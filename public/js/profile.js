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
                return validateRequired(field) && validatePhone(field);
            case 'codigo_postal':
                return validateRequired(field) && validatePostalCode(field);
            case 'fecha_nacimiento':
                return validateRequired(field) && validateBirthDate(field);
            case 'descripcion':
                return validateRequired(field);
            case 'dni':
                return validateRequired(field) && validateDniNie(field);
            case 'password':
                return field.value ? validatePassword(field) : true;
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

    function validatePassword(field) {
        if (field.value.length < 8) {
            showError(field, 'La contraseña debe tener al menos 8 caracteres');
            return false;
        }
        return true;
    }

    function validateDniNie(field) {
        let value = field.value.toUpperCase().trim();
        let dniRegex = /^\d{8}[A-Z]$/;
        let nieRegex = /^[XYZ]\d{7}[A-Z]$/;

        const letras = 'TRWAGMYFPDXBNJZSQVHLCKE';

        if (dniRegex.test(value)) {
            let number = parseInt(value.substring(0, 8));
            let letter = value.charAt(8);
            if (letras[number % 23] !== letter) {
                showError(field, 'DNI inválido');
                return false;
            }
            return true;
        } else if (nieRegex.test(value)) {
            let prefix = value.charAt(0);
            let numberPart = value.substring(1, 8);
            let letter = value.charAt(8);
            let prefixNumber = { X: 0, Y: 1, Z: 2 }[prefix];
            let fullNumber = parseInt(prefixNumber + numberPart);
            if (letras[fullNumber % 23] !== letter) {
                showError(field, 'NIE inválido');
                return false;
            }
            return true;
        } else {
            showError(field, 'Formato de DNI/NIE inválido');
            return false;
        }
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

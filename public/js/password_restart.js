document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', () => {
            const targetId = icon.getAttribute('data-target');
            const input = document.getElementById(targetId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');

    function setError(input, message) {
        clearError(input);
        const error = document.createElement('p');
        error.className = 'input-error';
        error.style.color = 'red';
        error.style.margin = '5px 0 0';
        error.textContent = message;
        input.parentNode.appendChild(error);
    }

    function clearError(input) {
        const existingError = input.parentNode.querySelector('.input-error');
        if (existingError) {
            existingError.remove();
        }
    }

    passwordInput.addEventListener('blur', () => {
        clearError(passwordInput);
        if (passwordInput.value.trim() === '') {
            setError(passwordInput, 'La contraseña no puede estar vacía.');
        }
    });

    confirmInput.addEventListener('blur', () => {
        clearError(confirmInput);
        if (confirmInput.value.trim() === '') {
            setError(confirmInput, 'Confirma tu contraseña.');
        } else if (confirmInput.value !== passwordInput.value) {
            setError(confirmInput, 'Las contraseñas no coinciden.');
        }
    });
});

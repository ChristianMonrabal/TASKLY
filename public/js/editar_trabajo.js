document.addEventListener('DOMContentLoaded', function () {
    const tagOptions = document.querySelectorAll('.tag-option');
    const categoriasInput = document.getElementById('categorias');

    tagOptions.forEach(option => {
        option.addEventListener('click', function () {
            const selectedId = option.dataset.id;
            let categorias = categoriasInput.value ? categoriasInput.value.split(',') : [];

            if (categorias.includes(selectedId)) {
                categorias = categorias.filter(id => id !== selectedId);
                option.classList.remove('selected');
            } else {
                categorias.push(selectedId);
                option.classList.add('selected');
            }

            categoriasInput.value = categorias.join(',');
        });
    });

    for (let i = 1; i <= 5; i++) {
        const input = document.getElementById(`imagen${i}`);
        const preview = document.getElementById(`imagen${i}-preview`);

        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        preview.addEventListener('click', function () {
            input.click();
        });
    }

    const form = document.querySelector('form');
    const imageError = document.getElementById('image-error');

    form.addEventListener('submit', function (e) {
        const inputs = Array.from({ length: 5 }, (_, i) => document.getElementById(`imagen${i + 1}`));
        const hiddenFields = document.querySelectorAll('input[name="imagenes_anteriores[]"]');

        const algunaImagen =
            inputs.some(input => input.files.length > 0) ||
            Array.from(hiddenFields).some(h => h.value.trim() !== '');

        if (!algunaImagen) {
            e.preventDefault();
            imageError.style.display = 'block';
        } else {
            imageError.style.display = 'none';
        }

        ['titulo', 'descripcion', 'precio', 'alta_responsabilidad', 'direccion'].forEach(id => {
            validateNotEmpty(document.getElementById(id));
        });
    });

    document.querySelectorAll('input, textarea, select').forEach(input => {
        input.addEventListener('blur', function () {
            validateNotEmpty(input);
        });
    });

    const direccionInput = document.getElementById('direccion');
    direccionInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '');
        if (this.value.length > 5) {
            this.value = this.value.slice(0, 5);
        }
    });
});

function validateNotEmpty(input) {
    const errorDiv = document.getElementById(`error-${input.id}`);

    if (!input.value.trim()) {
        input.style.borderColor = '#dc3545';
        errorDiv.textContent = 'Este campo es obligatorio';
    } else if (input.id === 'direccion' && input.value.length !== 5) {
        input.style.borderColor = '#dc3545';
        errorDiv.textContent = 'El código postal debe tener 5 dígitos';
    } else {
        input.style.borderColor = '';
        errorDiv.textContent = '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Manejo de tags (ya lo tienes)
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

    // Previsualización de imágenes y cambio dinámico
    for (let i = 1; i <= 5; i++) {
        const input = document.getElementById(`imagen${i}`);
        const preview = document.getElementById(`imagen${i}-preview`);

        // Mostrar vista previa al seleccionar archivo
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

        // Reemplazar imagen al hacer clic sobre la vista previa
        preview.addEventListener('click', function () {
            input.click();
        });
    }

    // Validación: al menos una imagen
    const form = document.querySelector('form');
    const imageError = document.getElementById('image-error');

    form.addEventListener('submit', function (e) {
        const inputs = Array.from({ length: 5 }, (_, i) => document.getElementById(`imagen${i + 1}`));
        const previews = Array.from({ length: 5 }, (_, i) => document.getElementById(`imagen${i + 1}-preview`));
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
    });
});

// Previsualizar imagen al seleccionarla
function previewImage(event, previewId) {
    const input = event.target;
    const preview = document.getElementById(previewId);
    const label = input.previousElementSibling;

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            label.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = "#";
        preview.style.display = 'none';
        label.style.display = 'flex';
    }
}

// Mostrar imagen en el modal al hacer clic
function showImageInModal(imageElement) {
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageElement.src;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();

    document.getElementById('modifyImageButton').style.display = 'block';
    document.getElementById('deleteImageButton').style.display = 'block';

    const previewId = imageElement.id;
    const inputId = previewId.replace('-preview', '');
    window.selectedImageInput = document.getElementById(inputId);
    window.selectedPreviewImage = imageElement;
}

// Modificar imagen (abrir selector de archivos)
function modifyImage() {
    if (window.selectedImageInput) {
        window.selectedImageInput.click();
        const modal = bootstrap.Modal.getInstance(document.getElementById('imageModal'));
        modal.hide();
    } else {
        console.error("No se ha seleccionado ninguna imagen para modificar.");
    }
}

// Borrar imagen
function deleteImage() {
    if (window.selectedImageInput && window.selectedPreviewImage) {
        window.selectedImageInput.value = "";
        window.selectedPreviewImage.src = "#";
        window.selectedPreviewImage.style.display = "none";

        const label = window.selectedImageInput.previousElementSibling;
        label.style.display = "flex";

        const modal = bootstrap.Modal.getInstance(document.getElementById('imageModal'));
        modal.hide();
    } else {
        console.error("No se ha seleccionado ninguna imagen para borrar.");
    }
}

// Actualizar preview cuando se cambia la imagen
function handleImageChange(event) {
    const input = event.target;
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewId = input.id + '-preview';
            const previewImage = document.getElementById(previewId);
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const fields = ['titulo', 'descripcion', 'precio', 'tags', 'direccion']; // Agregar 'imagenes' a la lista de campos

    // Función para validar un campo
    function validateField(input) {
        const errorSpan = input.closest('.form-group').querySelector('.error-message');
        
        if (!input.value.trim()) {
            errorSpan.textContent = 'Este campo es obligatorio.';
            errorSpan.style.display = 'block';
        } else {
            errorSpan.textContent = '';
            errorSpan.style.display = 'none';
        }
    }

    // Validar imágenes
    function validateImages() {
        const inputs = document.querySelectorAll('input[type="file"][name="imagenes[]"]');
        let imageSelected = false;
        
        inputs.forEach(input => {
            if (input.files.length > 0) {
                imageSelected = true;
            }
        });

        const errorSpan = document.getElementById('image-error');

        if (!imageSelected) {
            errorSpan.style.display = 'block';
        } else {
            errorSpan.style.display = 'none';
        }
    }

    // Agregar un evento 'blur' para cada campo (excepto las imágenes, porque usan 'change')
    fields.forEach(function (fieldName) {
        const input = document.getElementById(fieldName);

        if (input) {
            input.addEventListener('blur', function () {
                validateField(input); // Validar otros campos cuando pierden el foco
            });
        }
    });

    // Validar todo al enviar el formulario
    form.addEventListener('submit', function (e) {
        let hasErrors = false;

        fields.forEach(function (fieldName) {
            const input = document.getElementById(fieldName);
            const errorSpan = input.closest('.form-group').querySelector('.error-message');

            // Comprobar si el campo tiene errores antes de enviar
            if (!input.value.trim()) {
                errorSpan.textContent = 'Este campo es obligatorio.';
                errorSpan.style.display = 'block';
                hasErrors = true;
            }
        });

        // Validar imágenes
        const inputs = document.querySelectorAll('input[type="file"][name="imagenes[]"]');
        let imageSelected = false;
        
        inputs.forEach(input => {
            if (input.files.length > 0) {
                imageSelected = true;
            }
        });

        const errorSpan = document.getElementById('image-error');

        if (!imageSelected) {
            errorSpan.style.display = 'block';
            hasErrors = true;
        } else {
            errorSpan.style.display = 'none';
        }

        if (hasErrors) {
            e.preventDefault(); // Prevenir el envío si hay errores
        }
    });
});

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
    const form = document.getElementById('mi-formulario');
    const textFields = ['titulo', 'descripcion', 'precio', 'direccion'];

    // Función para validar campos de texto
    function validateTextField(input) {
        const errorSpan = input.closest('.form-group').querySelector('.error-message');

        if (!input.value.trim()) {
            errorSpan.textContent = 'Este campo es obligatorio.';
            errorSpan.style.display = 'block';
            return false;
        } else if (input.id === 'direccion') {
            if (!/^\d{5}$/.test(input.value.trim())) {
                errorSpan.textContent = 'El código postal debe tener exactamente 5 números.';
                errorSpan.style.display = 'block';
                return false;
            }
        }
        
        errorSpan.textContent = '';
        errorSpan.style.display = 'none';
        return true;
    }

    // Función para validar selección de categorías
    function validateCategories() {
        const categorias = document.getElementById('categorias');
        const errorSpan = categorias.closest('.form-group').querySelector('.error-message');

        if (categorias.selectedOptions.length === 0) {
            errorSpan.textContent = 'Debes seleccionar al menos un tag.';
            errorSpan.style.display = 'block';
            return false;
        }

        errorSpan.textContent = '';
        errorSpan.style.display = 'none';
        return true;
    }

    // Función para validar imágenes
    function validateImages() {
        const inputs = document.querySelectorAll('input[type="file"][name="imagenes[]"]');
        const errorSpan = document.getElementById('image-error');
        let imageSelected = false;

        inputs.forEach(input => {
            if (input.files.length > 0) {
                imageSelected = true;
            }
        });

        if (!imageSelected) {
            errorSpan.textContent = 'Debes añadir al menos una imagen.';
            errorSpan.style.display = 'block';
            return false;
        } else {
            errorSpan.textContent = '';
            errorSpan.style.display = 'none';
            return true;
        }
    }

    // Validar en tiempo real (onBlur) para campos de texto
    textFields.forEach(function (fieldName) {
        const input = document.getElementById(fieldName);
        if (input) {
            input.addEventListener('blur', function () {
                validateTextField(input);
            });
        }
    });

    // Validar en tiempo real (onBlur) para categorías
    const categoriasSelect = document.getElementById('categorias');
    if (categoriasSelect) {
        categoriasSelect.addEventListener('blur', function () {
            validateCategories();
        });
    }

    // Validar imágenes en tiempo real
    const imageInputs = document.querySelectorAll('input[type="file"][name="imagenes[]"]');
    imageInputs.forEach(input => {
        input.addEventListener('blur', validateImages);
        input.addEventListener('change', validateImages);
    });

    // Validar todo al enviar el formulario
    form.addEventListener('submit', function (e) {
        let hasErrors = false;

        // Validar campos de texto
        textFields.forEach(function (fieldName) {
            const input = document.getElementById(fieldName);
            if (!validateTextField(input)) {
                hasErrors = true;
            }
        });

        // Validar categorías
        if (!validateCategories()) {
            hasErrors = true;
        }

        // Validar imágenes
        if (!validateImages()) {
            hasErrors = true;
        }

        if (hasErrors) {
            e.preventDefault(); // No enviar el formulario si hay errores
        }
    });
});

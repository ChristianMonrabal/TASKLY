document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("mi-formulario");
    const errorMessages = document.querySelectorAll(".error-message");

    const titulo = document.getElementById("titulo");
    const descripcion = document.getElementById("descripcion");
    const precio = document.getElementById("precio");
    const direccion = document.getElementById("direccion");

    form.addEventListener("submit", function (e) {
        let isValid = true;
        errorMessages.forEach(msg => msg.style.display = "none");

        if (titulo.value.trim() === "") {
            showError(titulo, "Este campo es obligatorio.");
            isValid = false;
        }

        if (descripcion.value.trim() === "") {
            showError(descripcion, "Este campo es obligatorio.");
            isValid = false;
        }

        const precioValor = precio.value.trim();
        if (precioValor === "") {
            showError(precio, "Este campo es obligatorio.");
            isValid = false;
        } else if (isNaN(precioValor) || Number(precioValor) < 1 || Number(precioValor) > 1000) {
            showError(precio, "Debe ser un número entre 1 y 1000.");
            isValid = false;
        }

        const direccionValor = direccion.value.trim();
        if (direccionValor === "") {
            showError(direccion, "Este campo es obligatorio.");
            isValid = false;
        } else if (!/^\d{5}$/.test(direccionValor)) {
            showError(direccion, "El código postal debe contener 5 dígitos.");
            isValid = false;
        }

        const categoriasInput = document.getElementById("categorias");
        const categoriasValor = categoriasInput.value.trim();
        const tagContainer = document.getElementById("tag-container");

        if (categoriasValor === "") {
            showError(tagContainer, "Selecciona al menos una categoría.");
            isValid = false;
        }

        const imagenes = [
            document.getElementById("imagen1"),
            document.getElementById("imagen2"),
            document.getElementById("imagen3"),
            document.getElementById("imagen4"),
            document.getElementById("imagen5")
        ];
        const algunaImagenSeleccionada = imagenes.some(input => input.files.length > 0);
        const imageError = document.getElementById("image-error");

        if (!algunaImagenSeleccionada) {
            imageError.style.display = "block";
            isValid = false;
        } else {
            imageError.style.display = "none";
        }

        if (!isValid) e.preventDefault();
    });

    function showError(inputElement, message) {
        const formGroup = inputElement.closest('.form-group');
        const errorSpan = formGroup.querySelector(".error-message");
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.style.display = "block";
        }
    }

    function clearError(inputElement) {
        const formGroup = inputElement.closest('.form-group');
        const errorSpan = formGroup.querySelector(".error-message");
        if (errorSpan) {
            errorSpan.style.display = "none";
            errorSpan.textContent = "";
        }
    }

    titulo.addEventListener("blur", () => {
        titulo.value.trim() === "" ? showError(titulo, "Este campo es obligatorio.") : clearError(titulo);
    });

    descripcion.addEventListener("blur", () => {
        descripcion.value.trim() === "" ? showError(descripcion, "Este campo es obligatorio.") : clearError(descripcion);
    });

    precio.addEventListener("blur", () => {
        const valor = precio.value.trim();
        if (valor === "") {
            showError(precio, "Este campo es obligatorio.");
        } else if (isNaN(valor) || Number(valor) < 1 || Number(valor) > 1000) {
            showError(precio, "Debe ser un número entre 1 y 1000.");
        } else {
            clearError(precio);
        }
    });

    direccion.addEventListener("blur", () => {
        const val = direccion.value.trim();
        if (val === "") {
            showError(direccion, "Este campo es obligatorio.");
        } else if (!/^\d{5}$/.test(val)) {
            showError(direccion, "El codigo postal es de 5 digitos.");
        } else {
            clearError(direccion);
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const tagContainer = document.getElementById("tag-container");
    const categoriasInput = document.getElementById("categorias");
    const selectedTags = new Set();

    tagContainer.addEventListener("click", function (e) {
        if (e.target.classList.contains("tag-option")) {
            const tagElement = e.target;
            const tagId = tagElement.getAttribute("data-id");

            if (selectedTags.has(tagId)) {
                selectedTags.delete(tagId);
                tagElement.classList.remove("selected");
            } else {
                selectedTags.add(tagId);
                tagElement.classList.add("selected");
            }

            categoriasInput.value = Array.from(selectedTags).join(',');
        }
    });
});

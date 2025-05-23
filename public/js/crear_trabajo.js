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

        // Validación de dirección
        const direccionValor = direccion.value.trim();
        if (direccionValor === "") {
            showError(direccion, "La dirección es obligatoria.");
            isValid = false;
        }
        
        // Validación del código postal
        const codigoPostal = document.getElementById("codigo_postal");
        const codigoPostalValor = codigoPostal.value.trim();
        if (codigoPostalValor === "") {
            showError(codigoPostal, "El código postal es obligatorio.");
            isValid = false;
        } else if (!/^\d{5}$/.test(codigoPostalValor)) {
            showError(codigoPostal, "El código postal debe contener 5 dígitos.");
            isValid = false;
        }
        
        // Validación de ciudad
        const ciudad = document.getElementById("ciudad");
        const ciudadValor = ciudad.value.trim();
        if (ciudadValor === "") {
            showError(ciudad, "La ciudad es obligatoria.");
            isValid = false;
        }
        
        // Validación de coordenadas
        const latitud = document.getElementById("latitud").value;
        const longitud = document.getElementById("longitud").value;
        if (!latitud || !longitud) {
            showError(direccion, "Por favor, selecciona una ubicación válida en el mapa.");
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

    // Función para obtener coordenadas desde dirección y código postal
    async function obtenerCoordenadas() {
        const direccionVal = direccion.value.trim();
        const codigoPostalVal = document.getElementById("codigo_postal").value.trim();
        const ciudadVal = document.getElementById("ciudad").value.trim();
        
        if (direccionVal === "" || codigoPostalVal === "") {
            return;
        }
        
        // Construir la consulta de geocodificación
        const searchQuery = `${direccionVal}, ${codigoPostalVal}, ${ciudadVal}, España`;
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(searchQuery)}`;
        
        try {
            const response = await fetch(url);
            const data = await response.json();
            
            if (data && data.length > 0) {
                // Obtener la primera coincidencia
                const result = data[0];
                document.getElementById("latitud").value = result.lat;
                document.getElementById("longitud").value = result.lon;
                clearError(direccion);
            } else {
                showError(direccion, "No se pudieron obtener coordenadas para esta dirección");
            }
        } catch (error) {
            console.error("Error al obtener coordenadas:", error);
        }
    }
    
    // Escuchar cambios en los campos de ubicación
    // Validación en tiempo real del campo dirección
    direccion.addEventListener("blur", () => {
        const val = direccion.value.trim();
        if (val === "") {
            showError(direccion, "La dirección es obligatoria.");
        } else {
            clearError(direccion);
            obtenerCoordenadas();
        }
    });
    
    // Validación en tiempo real del campo código postal
    const codigoPostalInput = document.getElementById("codigo_postal");
    codigoPostalInput.addEventListener("blur", () => {
        const val = codigoPostalInput.value.trim();
        if (val === "") {
            showError(codigoPostalInput, "El código postal es obligatorio.");
        } else if (!/^\d{5}$/.test(val)) {
            showError(codigoPostalInput, "El código postal debe contener 5 dígitos.");
        } else {
            clearError(codigoPostalInput);
            obtenerCoordenadas();
        }
    });
    
    // Validación en tiempo real del campo ciudad
    const ciudadInput = document.getElementById("ciudad");
    ciudadInput.addEventListener("blur", () => {
        const val = ciudadInput.value.trim();
        if (val === "") {
            showError(ciudadInput, "La ciudad es obligatoria.");
        } else {
            clearError(ciudadInput);
            obtenerCoordenadas();
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

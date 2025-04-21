document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("mi-formulario");
    const errorMessages = document.querySelectorAll(".error-message");

    form.addEventListener("submit", function (e) {
        console.log("Intentando enviar el formulario...");
        let isValid = true;

        // Limpiar mensajes de error previos
        errorMessages.forEach(msg => {
            msg.style.display = "none";
        });

        // Validar campos de texto
        const titulo = document.getElementById("titulo");
        if (titulo.value.trim() === "") {
            showError(titulo, "Este campo es obligatorio.");
            isValid = false;
        }

        const descripcion = document.getElementById("descripcion");
        if (descripcion.value.trim() === "") {
            showError(descripcion, "Este campo es obligatorio.");
            isValid = false;
        }

        const precio = document.getElementById("precio");
        if (precio.value.trim() === "") {
            showError(precio, "Este campo es obligatorio.");
            isValid = false;
        }

        const direccion = document.getElementById("direccion");
        const direccionValor = direccion.value.trim();
        
        if (direccionValor === "") {
            showError(direccion, "Este campo es obligatorio.");
            isValid = false;
        } else if (!/^\d{5}$/.test(direccionValor)) {
            showError(direccion, "Debe contener 5 dígitos.");
            isValid = false;
        }

        // Validar selección de categorías
        const categorias = document.getElementById("categorias");
        if ([...categorias.selectedOptions].length === 0) {
            showError(categorias, "Selecciona al menos una categoría.");
            isValid = false;
        }

        // Validar al menos una imagen
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

        if (!isValid) {
            console.log("Formulario inválido. Previniendo envío.");
            e.preventDefault(); // ⛔ Evita el envío del formulario
        } else {
            console.log("Formulario válido. Enviando...");
        }
    });

    function showError(inputElement, message) {
        // Busca el contenedor más cercano con la clase form-group
        const formGroup = inputElement.closest('.form-group');
        const errorSpan = formGroup.querySelector(".error-message");
    
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.style.display = "block";
        }
    }
});

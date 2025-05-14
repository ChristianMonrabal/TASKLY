function validarMotivo() {
    const motivo = document.getElementById('motivo').value.trim();
    const errorDiv = document.getElementById('motivo-error');

    if (motivo === '') {
        errorDiv.textContent = 'Este campo es obligatorio.';
        return false;
    } else {
        errorDiv.textContent = '';
        return true;
    }
}

function validarTextarea() {
    return validarMotivo();
}
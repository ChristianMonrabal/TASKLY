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
function validarGravedad() {
    const gravedad = document.getElementById('gravedad').value;
    const errorDiv = document.getElementById('gravedad-error');

    if (gravedad === '') {
        errorDiv.textContent = 'Debe seleccionar un nivel de gravedad.';
        return false;
    } else {
        errorDiv.textContent = '';
        return true;
    }
}

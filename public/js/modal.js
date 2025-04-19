function previewImage(event, previewId) {
    const input = event.target;
    const file = input.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        const preview = document.getElementById(previewId);
        preview.src = e.target.result;
        preview.style.display = 'block';
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}

function showImageInModal(imgElement) {
    const modalImg = document.getElementById('modal-img');
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));

    modalImg.src = imgElement.src;
    imageModal.show();
}

function handleImageClick(imgElement, inputId) {
    const input = document.getElementById(inputId);
    
    // Si ya hay una imagen cargada, mostrar modal
    if (input.files && input.files[0]) {
        showImageInModal(imgElement);
    } else {
        // Si no hay imagen, forzar clic en el input para seleccionar archivo
        input.click();
    }
}

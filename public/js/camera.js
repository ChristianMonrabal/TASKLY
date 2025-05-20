document.addEventListener('DOMContentLoaded', () => {
    const cameraModal = document.getElementById('cameraModal');
    const cameraPreview = document.getElementById('camera-preview');
    const captureButton = document.getElementById('capture-button');
    const photoCanvas = document.getElementById('photo-canvas');
    const fotoPerfilInput = document.getElementById('foto_perfil_camera');
    const currentPhoto = document.getElementById('current-photo');
    
    let stream = null;

    cameraModal.addEventListener('shown.bs.modal', () => {
        startCamera();
    });

    cameraModal.addEventListener('hidden.bs.modal', () => {
        stopCamera();
    });

    async function startCamera() {
        try {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: 'user' 
                } 
            });
            cameraPreview.srcObject = stream;
        } catch (err) {
            console.error('No se pudo acceder a la cámara:', err);
            alert('No se pudo acceder a la cámara. Asegúrate de permitir el acceso.');
        }
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
            cameraPreview.srcObject = null;
        }
    }

    captureButton.addEventListener('click', () => {
        const context = photoCanvas.getContext('2d');
        const width = cameraPreview.videoWidth;
        const height = cameraPreview.videoHeight;

        photoCanvas.width = width;
        photoCanvas.height = height;

        context.drawImage(cameraPreview, 0, 0, width, height);

        const photoDataUrl = photoCanvas.toDataURL('image/jpeg', 0.8);

        fotoPerfilInput.value = photoDataUrl;
        currentPhoto.src = photoDataUrl;

        const modal = bootstrap.Modal.getInstance(cameraModal);
        modal.hide();
    });

    window.addEventListener('beforeunload', () => {
        stopCamera();
    });
});
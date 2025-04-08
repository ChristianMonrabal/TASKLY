document.addEventListener('DOMContentLoaded', () => {
    cameraPreview = document.getElementById('camera-preview');
    captureButton = document.getElementById('capture-button');
    photoCanvas = document.getElementById('photo-canvas');
    fotoPerfilInput = document.getElementById('foto_perfil_camera');
    photoPreview = document.getElementById('photo-preview');
    photoPreviewContainer = document.getElementById('photo-preview-container');
    
    stream = null;

    startCamera();

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
        }
    }

    captureButton.addEventListener('click', () => {
        context = photoCanvas.getContext('2d');
        width = cameraPreview.videoWidth;
        height = cameraPreview.videoHeight;

        photoCanvas.width = width;
        photoCanvas.height = height;

        context.drawImage(cameraPreview, 0, 0, width, height);

        photoDataUrl = photoCanvas.toDataURL('image/jpeg', 0.8);

        fotoPerfilInput.value = photoDataUrl;

        photoPreview.src = photoDataUrl;
        photoPreviewContainer.style.display = 'block';
    });

    window.addEventListener('beforeunload', () => {
        stopCamera();
    });
});
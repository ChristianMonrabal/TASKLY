let imagenesTrabajo = [];
let currentImageIndex = 0;

function cambiarImagen(src) {
  const imagenGrande = document.getElementById('imagen-grande');
  if (imagenGrande) {
    imagenGrande.style.opacity = '0.5';
    setTimeout(() => {
      imagenGrande.src = src;
      imagenGrande.style.opacity = '1';
    }, 200);
    
    const miniaturas = document.querySelectorAll('.miniatura');
    miniaturas.forEach(miniatura => {
      miniatura.classList.remove('active');
      if (miniatura.querySelector('img').src === src) {
        miniatura.classList.add('active');
      }
    });
  }
}

function abrirModalImagen(src, index) {
  const modal = document.getElementById('modalImagen');
  const modalImg = document.getElementById('modalImagenSrc');
  const contador = document.getElementById('contadorImagen');
  
  const miniaturas = document.querySelectorAll('.miniatura img');
  imagenesTrabajo = Array.from(miniaturas).map(img => img.src);
  currentImageIndex = index;
  
  modal.style.display = 'flex';
  modalImg.src = src;
  
  actualizarContadorImagen();
  
  const flechaPrev = document.querySelector('.modal-nav.prev');
  const flechaNext = document.querySelector('.modal-nav.next');
  
  flechaPrev.style.display = imagenesTrabajo.length > 1 ? 'block' : 'none';
  flechaNext.style.display = imagenesTrabajo.length > 1 ? 'block' : 'none';
}

function navegarImagen(direction) {
  currentImageIndex += direction;
  
  if (currentImageIndex >= imagenesTrabajo.length) {
    currentImageIndex = 0;
  } else if (currentImageIndex < 0) {
    currentImageIndex = imagenesTrabajo.length - 1;
  }
  
  document.getElementById('modalImagenSrc').src = imagenesTrabajo[currentImageIndex];
  actualizarContadorImagen();
}

function actualizarContadorImagen() {
  const contador = document.getElementById('contadorImagen');
  if (imagenesTrabajo.length > 1) {
    contador.textContent = `${currentImageIndex + 1}/${imagenesTrabajo.length}`;
    contador.style.display = 'block';
  } else {
    contador.style.display = 'none';
  }
}

function cerrarModal() {
  document.getElementById('modalImagen').style.display = 'none';
  document.getElementById('modalImagenSrc').src = '';
  currentImageIndex = 0;
}

document.addEventListener('DOMContentLoaded', function() {
  const miniaturas = document.querySelectorAll('.miniatura');
  if (miniaturas.length > 0) {
    miniaturas[0].classList.add('active');
    
    document.addEventListener('keydown', function(e) {
      const modal = document.getElementById('modalImagen');
      
      if (modal.style.display === 'flex') {
        if (e.key === 'ArrowRight' || e.key === ' ') {
          e.preventDefault();
          navegarImagen(1);
        } else if (e.key === 'ArrowLeft') {
          e.preventDefault();
          navegarImagen(-1);
        } else if (e.key === 'Escape') {
          cerrarModal();
        }
      } else if (miniaturas.length > 1) {
        const activeIndex = Array.from(miniaturas).findIndex(m => m.classList.contains('active'));
        
        if (e.key === 'ArrowRight' && activeIndex < miniaturas.length - 1) {
          cambiarImagen(miniaturas[activeIndex + 1].querySelector('img').src);
        } else if (e.key === 'ArrowLeft' && activeIndex > 0) {
          cambiarImagen(miniaturas[activeIndex - 1].querySelector('img').src);
        }
      }
    });
  }
  
  const imagenPrincipal = document.querySelector('.imagen-principal img');
  if (imagenPrincipal) {
    imagenPrincipal.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.05)';
    });
    imagenPrincipal.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1)';
    });
  }
  
  const postularForm = document.querySelector('.postular-form');
  if (postularForm) {
    postularForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const botonPostular = this.querySelector('.btn-postular');
      const textoOriginal = botonPostular.innerHTML;
      const botonWrapper = postularForm.parentNode;
      
      const postulacionUrl = this.action;
      
      Swal.fire({
        title: '¿Postularte para este trabajo?',
        text: 'Tu perfil será enviado al cliente para su evaluación.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#EC6A6A',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, postularme',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: () => {
          botonPostular.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
          botonPostular.disabled = true;
          
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          const formData = new FormData(postularForm);
          
          return fetch(postulacionUrl, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData,
            credentials: 'same-origin'
          })
          .then(response => {
            if (!response.ok) {
              return response.json().then(errorData => {
                throw new Error(errorData.message || `Error ${response.status}: ${response.statusText}`);
              });
            }
            return response.json();
          })
          .catch(error => {
            console.error('Error en la postulación:', error);
            Swal.showValidationMessage(`Error: ${error.message || 'No se pudo completar la solicitud'}`);
            
            botonPostular.disabled = false;
            botonPostular.innerHTML = textoOriginal;
            
            return false;
          });
        }
      }).then((result) => {
        if (!result.isConfirmed || !result.value) {
          botonPostular.disabled = false;
          botonPostular.innerHTML = textoOriginal;
          return;
        }
        
        const data = result.value;
        
        if (data.success) {
          Swal.fire({
            title: '¡Postulación enviada!',
            text: data.message || 'Tu postulación ha sido enviada con éxito.',
            icon: 'success',
            confirmButtonColor: '#EC6A6A',
            timer: 2000,
            timerProgressBar: true
          }).then(() => {
            // Refresca badge y dropdown de notificaciones
            if (typeof fetchNotifications === 'function') {
              fetchNotifications();
            }
            // Deshabilita el botón y cambia texto
            botonPostular.disabled = true;
            botonPostular.innerHTML = '<i class="fas fa-check"></i> Postulado';
          });
        } else {
          Swal.fire({
            title: 'Error',
            text: data.message || 'No se pudo completar la postulación',
            icon: 'error',
            confirmButtonColor: '#EC6A6A'
          });
          botonPostular.disabled = false;
          botonPostular.innerHTML = textoOriginal;
        }
      });
    });
  }
});

function confirmarCancelacion() {
  Swal.fire({
      title: '¿Estás seguro?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, cancelar',
      cancelButtonText: 'No, volver',
  }).then((result) => {
      if (result.isConfirmed) {
          document.getElementById('cancelar-postulacion-form').submit();
      }
  });
}

window.addEventListener('click', function (event) {
  const modal = document.getElementById('modalImagen');
  if (event.target === modal) {
      cerrarModal();
  }
});

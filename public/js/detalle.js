/**
 * Script para la página de detalles de trabajo
 * Maneja la galería de imágenes y la interacción con el usuario
 */

// Función para cambiar la imagen principal al hacer clic en una miniatura
function cambiarImagen(src) {
  const imagenGrande = document.getElementById('imagen-grande');
  if (imagenGrande) {
    // Animación suave de transición
    imagenGrande.style.opacity = '0.5';
    setTimeout(() => {
      imagenGrande.src = src;
      imagenGrande.style.opacity = '1';
    }, 200);
    
    // Marcar la miniatura activa
    const miniaturas = document.querySelectorAll('.miniatura');
    miniaturas.forEach(miniatura => {
      miniatura.classList.remove('active');
      if (miniatura.querySelector('img').src === src) {
        miniatura.classList.add('active');
      }
    });
  }
}

// Eventos para la página
document.addEventListener('DOMContentLoaded', function() {
  // Inicializar galería de imágenes
  const miniaturas = document.querySelectorAll('.miniatura');
  if (miniaturas.length > 0) {
    // Marcar la primera miniatura como activa
    miniaturas[0].classList.add('active');
    
    // Añadir controles de teclado para navegar por las imágenes
    document.addEventListener('keydown', function(e) {
      if (miniaturas.length <= 1) return;
      
      const activeIndex = Array.from(miniaturas).findIndex(m => m.classList.contains('active'));
      if (activeIndex === -1) return;
      
      // Flecha derecha: siguiente imagen
      if (e.key === 'ArrowRight' && activeIndex < miniaturas.length - 1) {
        cambiarImagen(miniaturas[activeIndex + 1].querySelector('img').src);
      }
      // Flecha izquierda: imagen anterior
      else if (e.key === 'ArrowLeft' && activeIndex > 0) {
        cambiarImagen(miniaturas[activeIndex - 1].querySelector('img').src);
      }
    });
  }
  
  // Efecto de zoom para la imagen principal
  const imagenPrincipal = document.querySelector('.imagen-principal img');
  if (imagenPrincipal) {
    imagenPrincipal.addEventListener('mouseenter', function() {
      this.style.transform = 'scale(1.05)';
    });
    
    imagenPrincipal.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1)';
    });
  }
  
  // Postulación para el trabajo
  const postularForm = document.querySelector('.postular-form');
  if (postularForm) {
    postularForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const botonPostular = this.querySelector('.btn-postular');
      const textoOriginal = botonPostular.innerHTML;
      const botonWrapper = postularForm.parentNode;
      
      // Obtener la URL del formulario para la postulación
      const postulacionUrl = this.action;
      
      // Configurar SweetAlert2 para confirmación con estilo TASKLY
      Swal.fire({
        title: '¿Postularte para este trabajo?',
        text: 'Tu perfil será enviado al cliente para su evaluación.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#EC6A6A', // Color primario de TASKLY
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, postularme',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        allowOutsideClick: () => !Swal.isLoading(),
        preConfirm: () => {
          // Mostrar indicador de carga en el botón del formulario
          botonPostular.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
          botonPostular.disabled = true;
          
          // Obtener token CSRF
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          const formData = new FormData(postularForm);
          
          // Enviar solicitud mediante AJAX con mejor manejo de errores
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
            // Verificar posibles errores HTTP y convertir a JSON
            if (!response.ok) {
              // Si tenemos una respuesta pero no es 2xx, puede ser error controlado
              return response.json().then(errorData => {
                throw new Error(errorData.message || `Error ${response.status}: ${response.statusText}`);
              });
            }
            return response.json();
          })
          .catch(error => {
            console.error('Error en la postulación:', error);
            Swal.showValidationMessage(`Error: ${error.message || 'No se pudo completar la solicitud'}`)
            
            // Restaurar el botón
            botonPostular.disabled = false;
            botonPostular.innerHTML = textoOriginal;
            
            return false;
          });
        }
      }).then((result) => {
        // Si el usuario canceló o hubo error en preConfirm, no hacer nada más
        if (!result.isConfirmed || !result.value) {
          botonPostular.disabled = false;
          botonPostular.innerHTML = textoOriginal;
          return;
        }
        
        const data = result.value;
        
        // Si la postulación fue exitosa
        if (data.success) {
          // Crear una animación suave para transicionar al botón de "Ya postulado"
          botonWrapper.style.transition = 'opacity 0.3s';
          botonWrapper.style.opacity = '0';
          
          setTimeout(() => {
            // Reemplazar el botón con el de "Ya postulado"
            const btnPostulado = document.createElement('button');
            btnPostulado.type = 'button';
            btnPostulado.className = 'btn btn-postulado';
            btnPostulado.disabled = true;
            btnPostulado.innerHTML = '<i class="fas fa-check"></i> Ya postulado';
            
            // Eliminar el formulario y agregar el botón de postulado
            botonWrapper.innerHTML = '';
            botonWrapper.appendChild(btnPostulado);
            botonWrapper.style.opacity = '1';
            
            // Actualizar contador de postulaciones si existe
            const contadorPostulaciones = document.querySelector('.meta-item .fa-users')?.parentNode.querySelector('span');
            if (contadorPostulaciones) {
              const partes = contadorPostulaciones.textContent.split(': ');
              const actual = parseInt(partes[1]) || 0;
              contadorPostulaciones.textContent = `Postulaciones: ${actual + 1}`;
            }
          }, 300);
          
          // Mostrar mensaje de éxito personalizado
          Swal.fire({
            title: '¡Postulación enviada!',
            text: data.message || 'Tu postulación ha sido enviada con éxito. El cliente se pondrá en contacto contigo si está interesado.',
            icon: 'success',
            confirmButtonColor: '#EC6A6A',
            timer: 3000,
            timerProgressBar: true
          });
        } else {
          // En caso de error inesperado (aunque normalmente lo maneja preConfirm)
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

// Función para confirmar la cancelación de la postulación
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
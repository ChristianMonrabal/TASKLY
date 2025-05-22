/**
 * TASKLY - Script para la página de detalles de trabajo
 */

// Variables globales
let imagenesTrabajo = [];
let currentImageIndex = 0;
let mapaTrabajo = null;

/**
 * Cambia la imagen principal cuando se hace clic en una miniatura
 */
function cambiarImagen(src) {
  const imagenGrande = document.getElementById('imagen-grande');
  if (!imagenGrande) return;

  imagenGrande.style.opacity = '0.5';
  setTimeout(() => {
    imagenGrande.src = src;
    imagenGrande.style.opacity = '1';
  }, 200);

  document.querySelectorAll('.miniatura').forEach(miniatura => {
    miniatura.classList.toggle(
      'active',
      miniatura.querySelector('img').src === src
    );
  });
}

/**
 * Abre el modal con la imagen ampliada
 */
function abrirModalImagen(src, index) {
  const modal = document.getElementById('modalImagen');
  const modalImg = document.getElementById('modalImagenSrc');
  const flechaPrev = modal.querySelector('.modal-nav.prev');
  const flechaNext = modal.querySelector('.modal-nav.next');

  const miniImgs = Array.from(document.querySelectorAll('.miniatura img'));
  imagenesTrabajo = miniImgs.map(img => img.src);
  currentImageIndex = index;

  modal.style.display = 'flex';
  modalImg.src = src;
  actualizarContadorImagen();

  const tieneVarias = imagenesTrabajo.length > 1;
  flechaPrev.style.display = tieneVarias ? 'block' : 'none';
  flechaNext.style.display = tieneVarias ? 'block' : 'none';
}

/**
 * Navega entre imágenes en el modal
 */
function navegarImagen(direction) {
  currentImageIndex = (currentImageIndex + direction + imagenesTrabajo.length) % imagenesTrabajo.length;
  document.getElementById('modalImagenSrc').src = imagenesTrabajo[currentImageIndex];
  actualizarContadorImagen();
}

/**
 * Actualiza el contador de imágenes en el modal
 */
function actualizarContadorImagen() {
  const contador = document.getElementById('contadorImagen');
  if (imagenesTrabajo.length > 1) {
    contador.textContent = `${currentImageIndex + 1}/${imagenesTrabajo.length}`;
    contador.style.display = 'block';
  } else {
    contador.style.display = 'none';
  }
}

/**
 * Cierra el modal de imagen
 */
function cerrarModal() {
  const modal = document.getElementById('modalImagen');
  modal.style.display = 'none';
  document.getElementById('modalImagenSrc').src = '';
  currentImageIndex = 0;
}

/**
 * Inicializa el mapa de ubicación del trabajo
 */
function inicializarMapaTrabajo() {
  console.log('Iniciando inicializarMapaTrabajo');
  const mapaEl = document.getElementById('mapa-trabajo');
  if (!mapaEl) {
    console.log('No se encontró el elemento mapa-trabajo');
    return;
  }
  
  console.log('Elemento mapa encontrado:', mapaEl);
  console.log('Dimensiones del mapa:', mapaEl.offsetWidth, 'x', mapaEl.offsetHeight);
  console.log('Datos del elemento:', mapaEl.dataset);

  const lat = parseFloat(mapaEl.dataset.lat);
  const lng = parseFloat(mapaEl.dataset.lng);
  if (isNaN(lat) || isNaN(lng)) {
    console.log('Coordenadas inválidas:', mapaEl.dataset.lat, mapaEl.dataset.lng);
    return;
  }
  
  console.log('Coordenadas encontradas:', lat, lng);
  
  // Si ya existe un mapa, no crear otro
  if (mapaTrabajo) {
    console.log('El mapa ya está inicializado, actualizando tamaño');
    mapaTrabajo.invalidateSize();
    return;
  }
  
  try {
    console.log('Creando mapa Leaflet...');
    mapaTrabajo = L.map('mapa-trabajo').setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mapaTrabajo);

    const customIcon = L.divIcon({
      className: 'custom-map-marker',
      html: '<i class="fas fa-map-marker-alt"></i>',
      iconSize: [30, 42],
      iconAnchor: [15, 42]
    });

    L.marker([lat, lng], { icon: customIcon })
      .addTo(mapaTrabajo)
      .bindPopup('<strong>Ubicación del trabajo</strong>')
      .openPopup();

    mapaTrabajo.scrollWheelZoom.disable();
    console.log('Mapa inicializado correctamente');
    
    // Forzar un resize después de un momento para asegurar que el mapa se renderice correctamente
    setTimeout(() => {
      if (mapaTrabajo) {
        mapaTrabajo.invalidateSize();
        console.log('Tamaño del mapa actualizado');
      }
    }, 200);
  } catch (error) {
    console.error('Error al inicializar el mapa:', error);
  }
}

/**
 * Confirma la cancelación de una postulación
 */
function confirmarCancelacion() {
  Swal.fire({
    title: '¿Estás seguro?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, cancelar',
    cancelButtonText: 'No, volver'
  }).then(result => {
    if (result.isConfirmed) {
      document.getElementById('cancelar-postulacion-form').submit();
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  // Inicializaciones
  inicializarMapaTrabajo();

  // Miniaturas
  const miniaturas = document.querySelectorAll('.miniatura');
  if (miniaturas.length > 0) {
    miniaturas[0].classList.add('active');

    miniaturas.forEach((mini, idx) => {
      mini.addEventListener('click', () => {
        const src = mini.querySelector('img').src;
        cambiarImagen(src);
      });
      mini.querySelector('img').addEventListener('click', () => {
        abrirModalImagen(mini.querySelector('img').src, idx);
      });
    });
  }

  // Navegación por teclado
  document.addEventListener('keydown', e => {
    const modal = document.getElementById('modalImagen');
    const abierto = modal.style.display === 'flex';
    if (abierto) {
      if (e.key === 'ArrowRight' || e.key === ' ') {
        e.preventDefault(); navegarImagen(1);
      } else if (e.key === 'ArrowLeft') {
        e.preventDefault(); navegarImagen(-1);
      } else if (e.key === 'Escape') {
        cerrarModal();
      }
    } else if (miniaturas.length > 1) {
      const activeIdx = Array.from(miniaturas).findIndex(m => m.classList.contains('active'));
      if (e.key === 'ArrowRight' && activeIdx < miniaturas.length - 1) {
        cambiarImagen(miniaturas[activeIdx + 1].querySelector('img').src);
      } else if (e.key === 'ArrowLeft' && activeIdx > 0) {
        cambiarImagen(miniaturas[activeIdx - 1].querySelector('img').src);
      }
    }
  });

  // Cerrar modal al hacer clic fuera de la imagen
  window.addEventListener('click', e => {
    const modal = document.getElementById('modalImagen');
    if (e.target === modal) {
      cerrarModal();
    }
  });

  // Efecto hover en imagen principal
  const imagenPrincipal = document.querySelector('.imagen-principal img');
  if (imagenPrincipal) {
    imagenPrincipal.addEventListener('mouseenter', () => {
      imagenPrincipal.style.transform = 'scale(1.05)';
    });
    imagenPrincipal.addEventListener('mouseleave', () => {
      imagenPrincipal.style.transform = 'scale(1)';
    });
  }

  // Formulario de postulación con SweetAlert2
  const postularForm = document.querySelector('.postular-form');
  if (postularForm) {
    postularForm.addEventListener('submit', e => {
      e.preventDefault();
      const boton = postularForm.querySelector('.btn-postular');
      const textoOriginal = boton.innerHTML;
      const url = postularForm.action;
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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
          boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
          boton.disabled = true;
          const formData = new FormData(postularForm);
          return fetch(url, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData,
            credentials: 'same-origin'
          })
          .then(res => {
            if (!res.ok) {
              return res.json().then(err => { throw new Error(err.message || res.statusText); });
            }
            return res.json();
          })
          .catch(err => {
            Swal.showValidationMessage(`Error: ${err.message}`);
            boton.disabled = false;
            boton.innerHTML = textoOriginal;
            return false;
          });
        }
      }).then(result => {
        boton.disabled = false;
        boton.innerHTML = textoOriginal;
        if (result.isConfirmed && result.value) {
          const data = result.value;
          if (data.success) {
            Swal.fire({
              title: '¡Postulación enviada!',
              text: data.message || 'Tu postulación ha sido enviada con éxito.',
              icon: 'success',
              timer: 2000,
              timerProgressBar: true
            });
            boton.disabled = true;
            boton.innerHTML = '<i class="fas fa-check"></i> Postulado';
            if (typeof fetchNotifications === 'function') fetchNotifications();
          } else {
            Swal.fire({ title: 'Error', text: data.message, icon: 'error' });
          }
        }
      });
    });
  }

  // Botón de cancelación
  const btnCancelar = document.getElementById('btn-cancelar-postulacion');
  if (btnCancelar) {
    btnCancelar.addEventListener('click', confirmarCancelacion);
  }
});

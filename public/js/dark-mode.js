/**
 * TASKLY - Gestión de modo oscuro
 * Guarda la preferencia del usuario en localStorage y aplica el tema correspondiente
 */
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el tema según preferencia guardada
    initTheme();
    
    // Agregar evento al botón de cambio de tema
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
    
    // Escuchar cambios en las preferencias del sistema
    if (window.matchMedia) {
        const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        // Establecer tema inicial basado en preferencias del sistema si no hay preferencia guardada
        if (!localStorage.getItem('darkMode')) {
            if (darkModeMediaQuery.matches) {
                enableDarkMode();
            } else {
                disableDarkMode();
            }
        }
        
        // Escuchar cambios en las preferencias del sistema
        try {
            // Chrome y Firefox
            darkModeMediaQuery.addEventListener('change', (e) => {
                if (!localStorage.getItem('darkModeManual')) {
                    if (e.matches) {
                        enableDarkMode();
                    } else {
                        disableDarkMode();
                    }
                }
            });
        } catch (e) {
            // Fallback para Safari antiguo
            darkModeMediaQuery.addListener((e) => {
                if (!localStorage.getItem('darkModeManual')) {
                    if (e.matches) {
                        enableDarkMode();
                    } else {
                        disableDarkMode();
                    }
                }
            });
        }
    }
});

/**
 * Inicializa el tema basado en la preferencia del usuario
 */
function initTheme() {
    // Comprobar si hay una preferencia guardada
    const darkMode = localStorage.getItem('darkMode');
    
    // Si el usuario ha seleccionado modo oscuro anteriormente
    if (darkMode === 'enabled') {
        enableDarkMode();
    } else if (darkMode === 'disabled') {
        disableDarkMode();
    } else {
        // Si no hay preferencia guardada, usar la preferencia del sistema
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            enableDarkMode();
        } else {
            disableDarkMode();
        }
    }
    
    // Aplicar transición suave después de cargar
    setTimeout(() => {
        document.body.style.transition = 'background-color 0.3s ease, color 0.3s ease';
    }, 100);
}

/**
 * Activa el modo oscuro
 */
function enableDarkMode() {
    // Agregar clase dark-mode al body
    document.body.classList.add('dark-mode');
    
    // Cambiar icono del botón
    const themeIcon = document.getElementById('themeIcon');
    if (themeIcon) {
        themeIcon.className = 'fas fa-sun';
    }
    
    // Cambiar el logo por la version para modo oscuro
    const logoImg = document.querySelector('.logo-img');
    if (logoImg) {
        const currentSrc = logoImg.src;
        // Guardar la ruta original si no está guardada
        if (!logoImg.dataset.originalSrc) {
            logoImg.dataset.originalSrc = currentSrc;
        }
        // Cambiar a icono blanco para modo oscuro
        const iconPath = currentSrc.substring(0, currentSrc.lastIndexOf('/') + 1);
        logoImg.src = iconPath + 'icon-blanco.png';
    }
    
    // Inyectar un estilo global para arreglar TODOS los fondos
    injectGlobalStyle();
    
    // Guardar preferencia
    localStorage.setItem('darkMode', 'enabled');
}

/**
 * Desactiva el modo oscuro
 */
function disableDarkMode() {
    // Quitar clase dark-mode del body
    document.body.classList.remove('dark-mode');
    
    // Cambiar icono del botón
    const themeIcon = document.getElementById('themeIcon');
    if (themeIcon) {
        themeIcon.className = 'fas fa-moon';
    }
    
    // Restaurar el logo original
    const logoImg = document.querySelector('.logo-img');
    if (logoImg && logoImg.dataset.originalSrc) {
        logoImg.src = logoImg.dataset.originalSrc;
    }
    
    // Eliminar estilos inyectados
    const injectedStyle = document.getElementById('dark-mode-fix-style');
    if (injectedStyle) {
        injectedStyle.remove();
    }
    
    // Guardar preferencia
    localStorage.setItem('darkMode', 'disabled');
}

/**
 * Alterna entre modo claro y oscuro
 */
function toggleTheme() {
    // Comprobar estado actual
    const darkMode = localStorage.getItem('darkMode');
    
    // Cambiar al modo opuesto
    if (darkMode !== 'enabled') {
        enableDarkMode();
    } else {
        disableDarkMode();
    }
    
    // Marcar que el usuario ha establecido manualmente su preferencia
    localStorage.setItem('darkModeManual', 'true');
    
    // Mostrar notificación de cambio de tema con SweetAlert2
    if (typeof Swal !== 'undefined') {
        const icon = darkMode !== 'enabled' ? 'moon' : 'sun';
        const text = darkMode !== 'enabled' ? 'Modo oscuro activado' : 'Modo claro activado';
        
        Swal.fire({
            title: text,
            icon: 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            customClass: {
                popup: darkMode !== 'enabled' ? 'swal2-dark' : ''
            }
        });
    }
}

/**
 * Inyecta estilos globales para corregir todos los fondos en modo oscuro
 * Esta función soluciona el problema de los paddings y márgenes negros
 */
function injectGlobalStyle() {
    // Eliminar estilos anteriores si existen
    const oldStyle = document.getElementById('dark-mode-fix-style');
    if (oldStyle) {
        oldStyle.remove();
    }
    
    // Crear un nuevo elemento style
    const style = document.createElement('style');
    style.id = 'dark-mode-fix-style';
    
    // Estilos CSS complementarios (mínimos, ya que lo principal está en simple-dark.css)
    style.innerHTML = `
        /* Soluciones específicas para elementos dinámicos o problemáticos */
        body.dark-mode .swal2-popup {
            background-color: #232323 !important;
            color: #E0E0E0 !important;
        }
        
        body.dark-mode .swal2-title, 
        body.dark-mode .swal2-content,
        body.dark-mode .swal2-html-container {
            color: #E0E0E0 !important;
        }
    `;
    
    // Añadir al head del documento
    document.head.appendChild(style);
}

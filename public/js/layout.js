// layout.js - Funcionalidad para el layout principal

document.addEventListener('DOMContentLoaded', function() {
    // Elementos del menú móvil (adaptado para solo usar el dropdown de usuario)
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    
    // Elementos de notificaciones y usuario
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const userDropdownBtns = document.querySelectorAll('.user-dropdown .dropdown-btn');
    const userDropdownContents = document.querySelectorAll('.user-dropdown .dropdown-content');
    
    // Toggle del menú para móvil (ahora maneja el dropdown de usuario)
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            
            // Mostrar el dropdown de usuario si existe
            if (userDropdownContents.length > 0) {
                userDropdownContents.forEach(dropdown => {
                    dropdown.classList.toggle('show');
                });
            }
        });
    }
    
    // Toggle de notificaciones
    if (notificationBtn && notificationDropdown) {
        notificationBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            notificationDropdown.classList.toggle('show');
            
            // Cerrar otros dropdowns
            userDropdownContents.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        });
    }
    
    // Toggle de usuario dropdown
    userDropdownBtns.forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            event.stopPropagation();
            const dropdown = this.nextElementSibling;
            dropdown.classList.toggle('show');
            
            // Cerrar el dropdown de notificaciones
            if (notificationDropdown) {
                notificationDropdown.classList.remove('show');
            }
        });
    });
    
    // Marcar notificaciones como leídas
    const markAllReadBtn = document.querySelector('.mark-all-read');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            // Marcar todas las notificaciones como leídas
            const unreadItems = document.querySelectorAll('.notification-item.unread');
            unreadItems.forEach(item => {
                item.classList.remove('unread');
            });
            
            // Actualizar el contador de notificaciones
            const notificationBadge = document.querySelector('.notification-badge');
            if (notificationBadge) {
                notificationBadge.textContent = '0';
                notificationBadge.style.display = 'none';
            }
        });
    }
    
    // Cerrar dropdowns al hacer clic fuera de ellos
    document.addEventListener('click', function() {
        if (notificationDropdown) {
            notificationDropdown.classList.remove('show');
        }
        
        userDropdownContents.forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    });
    
    // Evitar que se propague el clic dentro de los dropdowns
    const dropdowns = document.querySelectorAll('.notification-dropdown, .dropdown-content');
    dropdowns.forEach(function(dropdown) {
        dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
    
    // Cerrar menú móvil al hacer clic en un enlace
    const mobileMenuLinks = mobileMenu ? mobileMenu.querySelectorAll('a') : [];
    mobileMenuLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
            mobileMenuBtn.classList.remove('active');
            document.body.classList.remove('no-scroll');
        });
    });
    
    // Añadir clase activa a los enlaces del menú según la ruta actual
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(function(link) {
        const href = link.getAttribute('href');
        if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
            link.classList.add('active');
        }
    });
});

// layout.js - Funcionalidad para el layout principal

document.addEventListener('DOMContentLoaded', function() {
    // Elementos del menú móvil para usuarios no autenticados
    const mobileDropdownBtn = document.querySelector('.mobile-dropdown-btn');
    const mobileDropdownContent = document.querySelector('.mobile-dropdown-content');
    
    // Elementos de notificaciones y usuario
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const userDropdownBtns = document.querySelectorAll('.user-dropdown .dropdown-btn');
    const userDropdownContents = document.querySelectorAll('.user-dropdown .dropdown-content');
    
    // Toggle del menú para usuarios no autenticados
    if (mobileDropdownBtn && mobileDropdownContent) {
        mobileDropdownBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            this.classList.toggle('active');
            mobileDropdownContent.classList.toggle('show');
        });
        
        // Evitar que se propague el clic dentro del menú móvil
        mobileDropdownContent.addEventListener('click', function(event) {
            event.stopPropagation();
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
        
        // Cerrar menú móvil para usuarios no autenticados
        if (mobileDropdownContent) {
            mobileDropdownContent.classList.remove('show');
            if (mobileDropdownBtn) {
                mobileDropdownBtn.classList.remove('active');
            }
        }
    });
    
    // Evitar que se propague el clic dentro de los dropdowns
    const dropdowns = document.querySelectorAll('.notification-dropdown, .dropdown-content');
    dropdowns.forEach(function(dropdown) {
        dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
    
    // Cerrar menú móvil para usuarios no autenticados al hacer clic en un enlace
    const mobileDropdownLinks = mobileDropdownContent ? mobileDropdownContent.querySelectorAll('a') : [];
    mobileDropdownLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            mobileDropdownContent.classList.remove('show');
            mobileDropdownBtn.classList.remove('active');
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

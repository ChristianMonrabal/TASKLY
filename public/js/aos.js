// AOS - Animate On Scroll initialization
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,     // valores en ms
        easing: 'ease-in-out',
        once: false,       // si true, la animación solo ocurre una vez
        mirror: false,     // si true, los elementos se animarán al salir del viewport
        offset: 120,       // offset (en px) desde la posición original
        delay: 0,          // valores de 0 a 3000, paso 50ms
        anchorPlacement: 'top-bottom', // define qué posición del elemento respecto al viewport dispara la animación
    });
});

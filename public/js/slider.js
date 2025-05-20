document.addEventListener('DOMContentLoaded', () => {
    const sliderWrapper = document.querySelector('.slider-wrapper');
    const sliderItems = document.querySelectorAll('.slider-item');
    const prevButton = document.querySelector('.slider-prev');
    const nextButton = document.querySelector('.slider-next');

    let currentIndex = 0; // Índice de la imagen actual

    // Actualiza la posición del slider
    const updateSliderPosition = () => {
        const offset = -currentIndex * 100; // Calcula el desplazamiento en porcentaje
        sliderWrapper.style.transform = `translateX(${offset}%)`;
    };

    // Evento para el botón "anterior"
    prevButton.addEventListener('click', () => {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : sliderItems.length - 1; // Vuelve al final si está en la primera imagen
        updateSliderPosition();
    });

    // Evento para el botón "siguiente"
    nextButton.addEventListener('click', () => {
        currentIndex = (currentIndex < sliderItems.length - 1) ? currentIndex + 1 : 0; // Vuelve al inicio si está en la última imagen
        updateSliderPosition();
    });

    // Muestra solo la primera imagen al cargar la página
    updateSliderPosition();
});
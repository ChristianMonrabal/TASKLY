document.addEventListener('DOMContentLoaded', function() {
    const bubblesContainer = document.querySelector('.bubbles');
    
    // Función para crear una burbuja
    function createBubble() {
        const bubble = document.createElement('div');
        bubble.classList.add('bubble');
        
        // Asignar una de las clases de color aleatoriamente
        const colorClasses = ['bubble--white', 'bubble--light', 'bubble--accent'];
        const randomClass = colorClasses[Math.floor(Math.random() * colorClasses.length)];
        bubble.classList.add(randomClass);
        
        // Tamaño aleatorio entre 20px y 100px
        const size = Math.random() * 80 + 20;
        bubble.style.width = `${size}px`;
        bubble.style.height = `${size}px`;
        
        // Posición horizontal aleatoria
        bubble.style.left = `${Math.random() * 100}%`;
        
        // Opacidad entre 0.3 y 0.7 para mayor visibilidad
        bubble.style.opacity = (Math.random() * 0.4 + 0.3).toString();
        
        // Duración aleatoria entre 5s y 12s
        const duration = Math.random() * 7 + 5;
        bubble.style.animationDuration = `${duration}s`;
        
        // Añadir efecto de rotación aleatoria para más dinamismo
        const rotation = Math.random() * 360;
        bubble.style.transform = `rotate(${rotation}deg)`;
        
        // Añadir al contenedor y eliminar después de la animación
        bubblesContainer.appendChild(bubble);
        
        // Eliminar la burbuja después de que termine la animación para evitar sobrecarga del DOM
        setTimeout(() => {
            bubble.remove();
        }, duration * 1000);
    }
    
    // Crear burbujas iniciales (más cantidad para llenar la pantalla rápidamente)
    for (let i = 0; i < 25; i++) {
        setTimeout(() => {
            createBubble();
        }, Math.random() * 2000); // Distribución inicial en los primeros 2 segundos
    }
    
    // Crear nuevas burbujas con mayor frecuencia
    setInterval(createBubble, 200); // Una nueva burbuja cada 200ms
});

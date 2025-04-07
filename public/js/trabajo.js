document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('cardScrollNuevos');
    const leftBtn = document.getElementById('btn-left-nuevos');
    const rightBtn = document.getElementById('btn-right-nuevos');
  
    // función reutilizable que calcula el ancho exacto de una card
    const scrollAmount = () => {
      const card = container.querySelector('.card');
      return card ? card.offsetWidth + 20 /* gap entre cards */ : 300;
    };
  
    leftBtn.addEventListener('click', () => {
      container.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
    });
  
    rightBtn.addEventListener('click', () => {
      container.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
    });
  });
  
document.addEventListener('DOMContentLoaded', () => {
    // Scroll horizontal ya existente...
    const container = document.getElementById('cardScrollNuevos');
    const leftBtn = document.getElementById('btn-left-nuevos');
    const rightBtn = document.getElementById('btn-right-nuevos');
  
    const scrollAmount = () => {
      const card = container.querySelector('.card');
      return card ? card.offsetWidth + 20 : 300;
    };
  
    leftBtn.addEventListener('click', () => {
      container.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
    });
  
    rightBtn.addEventListener('click', () => {
      container.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
    });
  
    // Filtro para "Todos los Trabajos"
    const filtroBtns = document.querySelectorAll('#filtros-todos .filtro-btn');
    const trabajos = document.querySelectorAll('#gridTrabajos .card');
  
    filtroBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filtroBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
  
        const filtro = btn.dataset.filtro;
  
        trabajos.forEach(trabajo => {
          if (filtro === 'todos' || trabajo.dataset.categoria === filtro) {
            trabajo.style.display = 'block';
          } else {
            trabajo.style.display = 'none';
          }
        });
      });
    });
  });
  
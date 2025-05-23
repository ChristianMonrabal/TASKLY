// Variables globales
let mapa, rutaControl, origenMarker;
let modoActual = 'driving';
const destino = L.latLng(window.trabajoLat, window.trabajoLng);

document.addEventListener('DOMContentLoaded', () => {
  // Crear el mapa
  mapa = L.map('mapa-container').setView(destino, 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(mapa);

  // Marcador destino con icono personalizado
  L.marker(destino, { 
    icon: L.divIcon({ 
      className: 'destino-marker', 
      html: '<div class="marker-pin"><i class="fas fa-location-dot"></i></div>', 
      iconSize: [40, 40],
      iconAnchor: [20, 40],
      popupAnchor: [0, -40] 
    }) 
  }).addTo(mapa).bindPopup(window.document.title).openPopup();

  // Bind botones
  document.querySelectorAll('.route-btn').forEach(btn => {
    btn.addEventListener('click', () => changeModo(btn.dataset.modo));
  });

  // Arranca calculando ruta driving
  getLocationAndRoute(modoActual);
});

function changeModo(modo) {
  modoActual = modo;
  document.querySelectorAll('.route-btn').forEach(b => b.classList.toggle('active', b.dataset.modo === modo));
  getLocationAndRoute(modo);
}

function getLocationAndRoute(modo) {
  document.getElementById('routeInfo').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Calculando...';
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(pos => {
      const origen = L.latLng(pos.coords.latitude, pos.coords.longitude);
      if (origenMarker) origenMarker.setLatLng(origen);
      else {
        origenMarker = L.marker(origen, { draggable: true }).addTo(mapa);
        origenMarker.on('dragend', e => drawRoute(e.target.getLatLng(), destino, modo));
      }
      drawRoute(origen, destino, modo);
    }, () => {
      document.getElementById('routeInfo').innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> No se pudo obtener ubicación.';
    });
  }
}

function drawRoute(origen, destino, modo) {
  if (rutaControl) mapa.removeControl(rutaControl);
  
  const icons = { driving: 'car-side', walking: 'person-walking', transit: 'train-subway' };
  
  // Cambiar el color de la ruta según el modo
  const routeColors = {
    driving: '#EC6A6A',  // Color TASKLY
    walking: '#4e73df',  // Azul
    transit: '#f6c23e'   // Amarillo
  };
  
  // Añadir un marcador personalizado para la ubicación del usuario
  if (origenMarker) mapa.removeLayer(origenMarker);
  origenMarker = L.marker(origen, { 
    icon: L.divIcon({ 
      className: 'origen-marker', 
      html: '<div class="marker-pin marker-pin-origen"><i class="fas fa-user"></i></div>', 
      iconSize: [40, 40],
      iconAnchor: [20, 40],
      popupAnchor: [0, -40] 
    }),
    draggable: true 
  }).addTo(mapa);
  origenMarker.on('dragend', e => drawRoute(e.target.getLatLng(), destino, modo));
  
  // Crear opciones de ruta específicas para cada modo
  const routingOptions = {
    waypoints: [origen, destino],
    routeWhileDragging: true,
    showAlternatives: false,
    fitSelectedRoutes: true,
    show: false,  // No mostrar el panel de instrucciones
    lineOptions: { 
      styles: [{ color: routeColors[modo], opacity: 0.8, weight: 6 }],
      addWaypoints: false
    },
    createMarker: function() { return null; }  // No crear marcadores adicionales
  };
  
  // Utilizamos servicios diferentes para obtener rutas más precisas
  if (modo === 'walking') {
    // Ruta específica para peatones (OpenRouteService)
    routingOptions.router = L.Routing.osrmv1({
      serviceUrl: 'https://routing.openstreetmap.de/routed-foot/route/v1',
      profile: 'foot'
    });
  } else if (modo === 'transit') {
    // Para transporte público usamos un servicio diferente 
    routingOptions.router = L.Routing.osrmv1({
      serviceUrl: 'https://routing.openstreetmap.de/routed-bus/route/v1',
      profile: 'bus'
    });
  } else {
    // Ruta para coches (predeterminada)
    routingOptions.router = L.Routing.osrmv1({
      serviceUrl: 'https://routing.openstreetmap.de/routed-car/route/v1',
      profile: 'car'
    });
  }
  
  // Crear la ruta con las opciones específicas
  rutaControl = L.Routing.control(routingOptions).addTo(mapa);
  rutaControl.on('routesfound', e => {
    const s = e.routes[0].summary;
    const h = Math.floor(s.totalTime/3600), m = Math.floor((s.totalTime%3600)/60);
    const tiempo = (h>0? h+' h ': '') + m+' min';
    const dist = (s.totalDistance/1000).toFixed(1)+' km';
    document.getElementById('routeInfo').innerHTML =
      `<i class="fas fa-${icons[modo]}"></i> Ruta ${modo === 'driving' ? 'en coche' : modo === 'walking' ? 'a pie' : 'en transporte'}: ${dist} · ${tiempo}`;
      
    // Ajustar la vista del mapa para mostrar la ruta completa
    setTimeout(() => mapa.fitBounds(L.latLngBounds([origen, destino]), { padding: [50, 50] }), 500);
  });
}

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Trabajos Disponibles</title>
    <link rel="stylesheet" href="{{ asset('css/trabajo.css') }}"/>
</head>
<body>

  <div class="page-container">
    <header>
      <h1>🎯 Trabajos Disponibles</h1>
      <div class="filtros">
        <button class="filtro-btn active">Todos</button>
        <button class="filtro-btn">Diseño</button>
        <button class="filtro-btn">Traducción</button>
        <button class="filtro-btn">Programación</button>
        <button class="filtro-btn">Cercanos</button>
      </div>
    </header>

    <section class="nuevos-trabajos">
      <h2 class="seccion-titulo">🆕 Nuevos Trabajos</h2>
      <div class="scroll-wrapper">
        <button class="scroll-btn" id="btn-left-nuevos">⭠</button>
        <div class="scroll-container" id="cardScrollNuevos">
          <div class="card">
            <div class="card-img">
              <img src="{{ asset('./img/trabajos/prueba.jpg') }}" alt="Diseño">
            </div>
            <div class="card-content">
              <h2>Diseño Web</h2>
              <p>Landing page moderna para empresa tech.</p>
              <p class="precio">$300</p>
              <span class="categoria">Diseño</span>
            </div>
          </div>

          <div class="card">
            <div class="card-img">
              <img src="{{ asset('./img/trabajos/prueba.jpg') }}" alt="Traducción">
            </div>
            <div class="card-content">
              <h2>Traducción técnica</h2>
              <p>Documento técnico de 20 páginas.</p>
              <p class="precio">$150</p>
              <span class="categoria">Traducción</span>
            </div>
          </div>

          <div class="card">
            <div class="card-img">
              <img src="{{ asset('./img/trabajos/prueba.jpg') }}" alt="Programación">
            </div>
            <div class="card-content">
              <h2>App Android</h2>
              <p>Gestión de gastos personales.</p>
              <p class="precio">$500</p>
              <span class="categoria">Programación</span>
            </div>
          </div>
        </div>
        <button class="scroll-btn" id="btn-right-nuevos">⭢</button>
      </div>
    </section>

    <section class="todos-trabajos">
      <h2 class="seccion-titulo">📋 Todos los Trabajos</h2>
      <div class="buscador">
        <input type="text" placeholder="Buscar por título, categoría o precio...">
      </div>
      <div class="grid-trabajos">
        <div class="card">
          <div class="card-img">
            <img src="{{ asset('./img/trabajos/prueba.jpg') }}" alt="Diseño">
          </div>
          <div class="card-content">
            <h2>Branding para app</h2>
            <p>Diseño de logo y paleta de colores.</p>
            <p class="precio">$120</p>
            <span class="categoria">Diseño</span>
          </div>
        </div>

        <div class="card">
          <div class="card-img">
            <img src="{{ asset('./img/trabajos/prueba.jpg') }}" alt="Traducción">
          </div>
          <div class="card-content">
            <h2>Manual de usuario</h2>
            <p>Traducción EN → ES 10 páginas.</p>
            <p class="precio">$80</p>
            <span class="categoria">Traducción</span>
          </div>
        </div>

        <div class="card">
          <div class="card-img">
            <img src="{{ asset('./img/trabajos/prueba.jpg') }}" alt="Programación">
          </div>
          <div class="card-content">
            <h2>Aplicación de clima</h2>
            <p>React Native + API OpenWeather.</p>
            <p class="precio">$220</p>
            <span class="categoria">Programación</span>
          </div>
        </div>

        <div class="card">
          <div class="card-img">
            <img src="{{ asset('./img/trabajos/prueba.jpg') }}" alt="Programación">
          </div>
          <div class="card-content">
            <h2>Control de stock</h2>
            <p>Sistema web para tienda local.</p>
            <p class="precio">$200</p>
            <span class="categoria">Programación</span>
          </div>
        </div>

        <div class="card">
          <div class="card-img">
            <img src="{{ asset('./img/trabajos/prueba.jpg') }}" alt="Programación">
          </div>
          <div class="card-content">
            <h2>Chatbot para WhatsApp</h2>
            <p>Automatización para atención al cliente.</p>
            <p class="precio">$300</p>
            <span class="categoria">Programación</span>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script src="{{ asset('js/trabajo.js') }}"></script>
</body>
</html>

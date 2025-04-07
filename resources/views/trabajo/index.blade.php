<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Trabajos Disponibles</title>
  <link rel="stylesheet" href="{{ asset('css/trabajo.css') }}">
</head>
<body>

  <div class="page-container">
    <h1>🎯 Trabajos Disponibles</h1>

    <div class="filtros">
      <button class="filtro-btn active">Todos</button>
      <button class="filtro-btn">Diseño</button>
      <button class="filtro-btn">Traducción</button>
      <button class="filtro-btn">Programación</button>
      <button class="filtro-btn">Cercanos</button>
    </div>

    {{-- Sección: Nuevos Trabajos --}}
    <section>
      <h2 class="seccion-titulo">🆕 Nuevos Trabajos</h2>
      <div class="scroll-wrapper">
        <button class="scroll-btn" id="btn-left-nuevos">⭠</button>
        <div class="scroll-container" id="cardScrollNuevos">
          <div class="card">
            <img src="{{ asset('images/diseño.jpg') }}" alt="Diseño Web">
            <h2>Diseño Web</h2>
            <p>Landing page moderna para empresa tech.</p>
            <p class="precio">$300</p>
            <span class="categoria">Diseño</span>
          </div>

          <div class="card">
            <img src="{{ asset('images/traduccion.jpg') }}" alt="Traducción técnica">
            <h2>Traducción técnica</h2>
            <p>Traducción técnica de documentos legales.</p>
            <p class="precio">$150</p>
            <span class="categoria">Traducción</span>
          </div>
          <div class="card">
            <img src="{{ asset('images/traduccion.jpg') }}" alt="Traducción técnica">
            <h2>Traducción técnica</h2>
            <p>Traducción técnica de documentos legales.</p>
            <p class="precio">$150</p>
            <span class="categoria">Traducción</span>
          </div>
          <div class="card">
            <img src="{{ asset('images/traduccion.jpg') }}" alt="Traducción técnica">
            <h2>Traducción técnica</h2>
            <p>Traducción técnica de documentos legales.</p>
            <p class="precio">$150</p>
            <span class="categoria">Traducción</span>
          </div>
          <div class="card">
            <img src="{{ asset('images/traduccion.jpg') }}" alt="Traducción técnica">
            <h2>Traducción técnica</h2>
            <p>Traducción técnica de documentos legales.</p>
            <p class="precio">$150</p>
            <span class="categoria">Traducción</span>
          </div>
          <div class="card">
            <img src="{{ asset('images/traduccion.jpg') }}" alt="Traducción técnica">
            <h2>Traducción técnica</h2>
            <p>Traducción técnica de documentos legales.</p>
            <p class="precio">$150</p>
            <span class="categoria">Traducción</span>
          </div>

          <div class="card">
            <img src="{{ asset('images/app.jpg') }}" alt="App Android">
            <h2>App Android</h2>
            <p>App móvil para gestión de finanzas personales.</p>
            <p class="precio">$500</p>
            <span class="categoria">Programación</span>
          </div>
        </div>
        <button class="scroll-btn" id="btn-right-nuevos">⭢</button>
      </div>
    </section>
  </div>

  <script src="{{ asset('js/trabajo.js') }}"></script>
</body>
</html>

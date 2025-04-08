<!-- Vista parcial para detalles de trabajo cargados con AJAX -->
<div class="trabajo-detalle-modal">
    <div class="modal-header">
        <h2>{{ $trabajo->titulo }}</h2>
        <button class="close-modal">×</button>
    </div>
    
    <div class="modal-content">
        <div class="trabajo-slider">
            @if($trabajo->imagenes && count($trabajo->imagenes) > 0)
                <div class="trabajo-imagenes">
                    @foreach($trabajo->imagenes as $imagen)
                        <div class="imagen-trabajo">
                            <img src="{{ asset('img/trabajos/' . $imagen->ruta_imagen) }}" alt="{{ $trabajo->titulo }}">
                        </div>
                    @endforeach
                </div>
                <div class="slider-controls">
                    <button class="slider-prev"><i class="fas fa-chevron-left"></i></button>
                    <button class="slider-next"><i class="fas fa-chevron-right"></i></button>
                </div>
            @else
                <div class="imagen-trabajo no-imagen">
                    <img src="{{ asset('img/trabajos/trabajo-default.jpg') }}" alt="{{ $trabajo->titulo }}">
                </div>
            @endif
        </div>
        
        <div class="trabajo-info">
            <div class="precio-tag">{{ $trabajo->precio }}€</div>
            
            <div class="categoria-tags">
                @if($trabajo->categoriastipotrabajo && count($trabajo->categoriastipotrabajo) > 0)
                    @foreach($trabajo->categoriastipotrabajo as $categoria)
                        <span class="categoria">{{ $categoria->nombre }}</span>
                    @endforeach
                @else
                    <span class="categoria">Sin categoría</span>
                @endif
            </div>
            
            <div class="trabajo-valoracion">
                @php
                    $valoracionPromedio = 0;
                    $numValoraciones = 0;
                    
                    if($trabajo->valoraciones && count($trabajo->valoraciones) > 0) {
                        $numValoraciones = count($trabajo->valoraciones);
                        $valoracionPromedio = $trabajo->valoraciones->avg('puntuacion');
                    }
                @endphp
                
                <div class="estrellas">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($i <= floor($valoracionPromedio))
                            <i class="fas fa-star"></i>
                        @elseif ($i - 0.5 <= $valoracionPromedio)
                            <i class="fas fa-star-half-alt"></i>
                        @else
                            <i class="far fa-star"></i>
                        @endif
                    @endfor
                </div>
                <span class="num-valoraciones">({{ $numValoraciones }})</span>
            </div>
            
            <div class="trabajo-descripcion">
                <h3>Descripción</h3>
                <p>{{ $trabajo->descripcion }}</p>
            </div>
            
            <div class="trabajo-direccion">
                <h3>Ubicación</h3>
                <p><i class="fas fa-map-marker-alt"></i> {{ $trabajo->direccion ?: 'Trabajo remoto' }}</p>
            </div>
            
            <div class="trabajo-fecha">
                <h3>Fecha límite</h3>
                <p><i class="fas fa-calendar-alt"></i> {{ $trabajo->fecha_limite ? date('d/m/Y', strtotime($trabajo->fecha_limite)) : 'Sin fecha límite' }}</p>
            </div>
            
            <div class="trabajo-acciones">
                <a href="{{ route('trabajos.detalle', $trabajo->id) }}" class="btn-ver-completo">Ver página completa</a>
                <button class="btn-postular" data-trabajo-id="{{ $trabajo->id }}">Postularme para este trabajo</button>
            </div>
        </div>
    </div>
</div>

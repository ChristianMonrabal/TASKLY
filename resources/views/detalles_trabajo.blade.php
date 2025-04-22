{{-- <!-- recursos/views/trabajos/detalles_trabajo.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Trabajo</title>
    <link rel="stylesheet" href="{{ asset('css/trabajos_publicados.css') }}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="position-relative mb-4">
            <a href="{{ url('/') }}" class="btn btn-secondary position-absolute start-0 top-50 translate-middle-y">← Volver</a>
            <h1 class="mb-4">{{ $trabajo->titulo }}</h1>
        </div>

        <div class="row">
            <div class="col-md-6">
                <!-- Imagen del trabajo -->
                @if($trabajo->imagenes->isNotEmpty())
                    <img src="{{ asset($trabajo->imagenes->first()->ruta_imagen) }}" class="img-fluid" alt="{{ $trabajo->titulo }}">
                @else
                    <img src="{{ asset('images/no-image.png') }}" class="img-fluid" alt="No hay imagen">
                @endif
            </div>
        
            <div class="col-md-6">
                <p><strong>Descripción:</strong> {{ $trabajo->descripcion }}</p>
            
                <p><strong>Precio:</strong> {{ $trabajo->precio }} €</p>
            
                <p><strong>Código Postal:</strong> {{ $trabajo->direccion }}</p>
                
                @if($trabajo->categorias->isNotEmpty())
                    <p><strong>Categorías:</strong>
                        @foreach($trabajo->categorias as $categoria)
                            <span class="badge bg-primary">{{ $categoria->nombre }}</span>
                        @endforeach
                    </p>
                @endif
            
            </div>

        </div>
    </div>
</body>
</html> --}}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Trabajos Publicados</title>
    <link rel="stylesheet" href="{{ asset('css/trabajos_publicados.css') }}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <div class="position-relative mb-4">
            <a href="{{ url('/') }}" class="btn btn-secondary position-absolute start-0 top-50 translate-middle-y">← Volver</a>
            <h1 class="text-center">Mis Trabajos Publicados</h1>
        </div>
        
        @if ($trabajos->count() > 0)
            <div class="row">
                @foreach ($trabajos as $trabajo)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <!-- Imagen del trabajo -->
                            @if($trabajo->imagenes->isNotEmpty())
                            <img src="{{ asset($trabajo->imagenes->first()->ruta_imagen) }}" class="card-img-top" alt="{{ $trabajo->titulo }}">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No hay imagen">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $trabajo->titulo }}</h5>
                                <p class="card-text">{{ Str::limit($trabajo->descripcion, 100) }}</p>
                                <p class="card-text"><strong>Precio:</strong> {{ $trabajo->precio }} €</p>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <!-- Contenedor para los botones con clases para alinearlos -->
                                <div class="d-flex gap-2">
                                    <a href="/view/trabajo/detalle/{{ $trabajo->id }}" class="btn btn-primary w-50">Ver detalles</a>
                                    <a href="/candidatos_trabajo/{{ $trabajo->id }}" class="btn btn-primary w-50">Ver candidatos</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                No has publicado ningún trabajo todavía.
            </div>
        @endif

    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Trabajos Publicados</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">Mis Trabajos Publicados</h1>

        @if ($trabajos->count() > 0)
            <div class="row">
                @foreach ($trabajos as $trabajo)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $trabajo->titulo }}</h5>
                                <p class="card-text">{{ Str::limit($trabajo->descripcion, 100) }}</p>
                                <p class="card-text"><strong>Precio:</strong> {{ $trabajo->precio }} €</p>
                            </div>
                            <div class="card-footer bg-transparent border-0">
                                <a href="{{ route('trabajos.show', $trabajo->id) }}" class="btn btn-primary w-100">Ver detalles</a>
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

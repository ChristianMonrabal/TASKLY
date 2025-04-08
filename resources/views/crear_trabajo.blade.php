<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crear Trabajo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Crear Trabajo</h1>
        <form>
            <div class="form-group mb-3">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" placeholder="Título del trabajo">
            </div>

            <div class="form-group mb-3">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="4" placeholder="Descripción del trabajo"></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="precio">Precio</label>
                <div class="input-group">
                    <input type="number" name="precio" id="precio" class="form-control" placeholder="Precio">
                    <div class="input-group-append">
                        <span class="input-group-text">€</span>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="tags">Tags</label>
                <input type="text" name="tags" id="tags" class="form-control" placeholder="Ejemplo: diseño, desarrollo, marketing">
            </div>

            <div class="form-group mb-3">
                <label>Añadir imágenes (5 máximo)</label>
                <div class="d-flex justify-content-between">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="image-upload">
                            <label for="imagen{{ $i }}" class="btn btn-outline-secondary">
                                <span>+</span>
                            </label>
                            <input type="file" name="imagenes[]" id="imagen{{ $i }}" class="d-none" accept="image/*">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="form-group text-center">
                <button type="button" class="btn btn-success">Crear</button>
            </div>
        </form>
    </div>
</body>
</html>

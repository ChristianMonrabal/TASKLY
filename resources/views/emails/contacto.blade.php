<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nuevo mensaje de contacto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #EC6A6A;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666;
        }
        .data-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nuevo mensaje de contacto</h1>
        </div>
        <div class="content">
            <p>Has recibido un nuevo mensaje desde el formulario de contacto de TASKLY:</p>
            
            <div class="data-row">
                <span class="label">Nombre:</span> {{ $datos['nombre'] }}
            </div>
            
            <div class="data-row">
                <span class="label">Email:</span> {{ $datos['email'] }}
            </div>
            
            <div class="data-row">
                <span class="label">Asunto:</span> {{ $datos['asunto'] }}
            </div>
            
            <div class="data-row">
                <span class="label">Tipo de consulta:</span> {{ $datos['tipo'] ?? 'No especificado' }}
            </div>
            
            <div class="data-row">
                <span class="label">Mensaje:</span>
                <p>{{ $datos['mensaje'] }}</p>
            </div>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} TASKLY - Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>

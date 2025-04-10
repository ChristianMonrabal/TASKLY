<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    @foreach ($chats as $chat)
        @foreach ($chat->postulaciones as $postulante)
            <div>
                <p>{{ $postulante->trabajador->nombre }}</p>
                <p>{{ $chat->titulo }}</p>
            </div>
        @endforeach
    @endforeach
</body>

</html>

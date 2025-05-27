@extends('layouts.app')

<link href="{{ asset('css/password_restart.css') }}" rel="stylesheet">
@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection


@section('content')
<div class="container">
    <h2>Restablecer contraseña</h2>

    <form action="{{ route('password.restart.submit') }}" method="POST">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" id="email" value="{{ request('email') }}" readonly>
        </div>

        <div style="margin-bottom: 15px; position: relative;">
            <label for="password">Nueva contraseña:</label>
            <input type="password" name="password" id="password">
            <i class="fa-solid fa-eye toggle-password" data-target="password" style="position:absolute; right:10px; top:36px; cursor:pointer;"></i>
        </div>

        <div style="margin-bottom: 15px; position: relative;">
            <label for="password_confirmation">Repetir contraseña:</label>
            <input type="password" name="password_confirmation" id="password_confirmation">
            <i class="fa-solid fa-eye toggle-password" data-target="password_confirmation" style="position:absolute; right:10px; top:36px; cursor:pointer;"></i>
        </div>

        @if ($errors->any())
            <div class='error'>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
            </div>
        @endif

        @if (session('status'))
            <div class='success'>
                <p>{{ session('status') }}</p>
            </div>
        @endif

        <button type="submit">Restablecer contraseña</button>
    </form>
</div>

    <script src="{{ asset('js/password_restart.js') }}"></script>
@endsection

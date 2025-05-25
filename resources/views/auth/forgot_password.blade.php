@extends('layouts.app')

<link href="{{ asset('css/password_restart.css') }}" rel="stylesheet">
@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section('content')
    <div class="container">
        <h2>¿Olvidaste tu contraseña?</h2>
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;" id="email-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" name="email" id="email">
            </div>

            @if (session('status'))
                <div class="success">{{ session('status') }}</div>
            @endif
            
            @if ($errors->any())
                <div class='error'>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                </div>
            @endif

            <button type="submit">Enviar correo</button>
        </form>
    </div>
@endsection

@section('script')
<script src="{{ asset('js/forgot_password.js') }}"></script>

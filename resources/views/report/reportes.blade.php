@extends('layouts.app')

@section('title', 'Reportes')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/reportes.css') }}">
@endsection

@section('content')
<div class="container">
    <h2>Reportar a {{ $usuarioReportado->nombre }} {{ $usuarioReportado->apellidos }}</h2>

    <form action="{{ route('reportes.store') }}" method="POST" onsubmit="return validarTextarea()">
        @csrf
        <input type="hidden" name="user_id" value="{{ $usuarioReportado->id }}">

        <div class="form-group">
            <label for="gravedad">Nivel de gravedad</label>
            <select id="gravedad" name="gravedad" class="form-control" onblur="validarGravedad()">
                <option value="">Seleccione un nivel</option>
                @foreach($nivelesGravedad as $nivel)
                    <option value="{{ $nivel->id }}" {{ old('gravedad') == $nivel->id ? 'selected' : '' }}>{{ $nivel->nombre }}</option>
                @endforeach
            </select>
            <div id="gravedad-error" class="error-message"></div>
            @error('gravedad')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <br>

        <div class="form-group">
            <label for="motivo">Escriba el motivo del reporte</label>
            <textarea id="motivo" name="motivo" class="form-control" onblur="validarMotivo()">{{ old('motivo') }}</textarea>
            <div id="motivo-error" class="error-message"></div>
            @error('motivo')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        @if(session('success'))
            <div  style="color: green; border: 1px solid #c3e6cb; background-color: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif


        <div class="center">
            <button type="submit">Enviar reporte</button>
        </div>
    </form>
</div>

<script src="{{ asset('js/reportes.js') }}"></script>
@endsection

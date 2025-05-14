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
                <label for="motivo">Escriba el motivo del reporte</label>
                <textarea id="motivo" name="motivo" class="form-control" onblur="validarMotivo()"></textarea>
                <div id="motivo-error" class="error-message"></div>
            </div>

            <div class="center">
                <button type="submit">Enviar reporte</button>
            </div>
        </form>
    </div>

    <script src="{{ asset('js/reportes.js') }}"></script>
@endsection

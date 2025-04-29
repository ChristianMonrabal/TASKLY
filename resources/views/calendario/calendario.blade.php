@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Calendario</h1>
    <div class="calendar-container">
        <div class="calendar-header">
            <button id="prev" class="calendar-btn">&laquo;</button>
            <h2 id="month-year"></h2>
            <button id="next" class="calendar-btn">&raquo;</button>
        </div>
        <div class="calendar">
            <div class="day-names">
                <div>Lun</div>
                <div>Mar</div>
                <div>Mié</div>
                <div>Jue</div>
                <div>Vie</div>
                <div>Sáb</div>
                <div>Dom</div>
            </div>
            <div id="days" class="days-grid"></div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/calendario.css') }}">
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/calendario.js') }}"></script>
@endsection
<script>
    const events = @json($eventos);
</script>

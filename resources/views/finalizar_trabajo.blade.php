@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h2 class="text-center mb-0">Finalizar Trabajo</h2>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4>{{ $trabajo->titulo }}</h4>
                        <p class="text-muted">{{ $trabajo->descripcion }}</p>
                        <div class="d-flex justify-content-between">
                            <span><strong>Precio:</strong> €{{ number_format($trabajo->precio, 2) }}</span>
                            <span><strong>Dirección:</strong> {{ $trabajo->direccion }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Trabajador asignado</h5>
                        <div class="d-flex align-items-center">
                            <div class="circle-avatar mr-3" style="background-color: #EC6A6A; width: 50px; height: 50px; border-radius: 50%; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold;">
                                {{ substr($trabajador->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $trabajador->name }}</h6>
                                <small class="text-muted">{{ $trabajador->email }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <p class="mb-0">Al finalizar este trabajo, confirmas que el trabajador ha completado satisfactoriamente todas las tareas acordadas.</p>
                    </div>

                    <form action="{{ route('finalizar_trabajo', $trabajo->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="valoracion">Valora al trabajador (1-5 estrellas):</label>
                            <div class="rating-stars mb-3">
                                <div class="d-flex">
                                    @for($i = 5; $i >= 1; $i--)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="valoracion" id="star{{ $i }}" value="{{ $i }}" {{ old('valoracion') == $i ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="star{{ $i }}">
                                                <i class="fas fa-star" style="color: #FFD700; font-size: 1.5rem;"></i>
                                                <span class="rating-text">{{ $i }}</span>
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            @error('valoracion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="comentario">Comentario (opcional):</label>
                            <textarea class="form-control" id="comentario" name="comentario" rows="3">{{ old('comentario') }}</textarea>
                            @error('comentario')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('trabajos.candidatos', $trabajo->id) }}" class="btn btn-secondary">Volver</a>
                            <button type="submit" class="btn btn-primary" style="background-color: #EC6A6A; border-color: #EC6A6A;">Finalizar Trabajo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .rating-stars .form-check-input {
        opacity: 0;
        position: absolute;
    }
    
    .rating-stars .form-check-label {
        cursor: pointer;
        padding: 0 5px;
    }
    
    .rating-stars .form-check-input:checked ~ .form-check-label i,
    .rating-stars .form-check-input:hover ~ .form-check-label i {
        color: #FFD700;
    }
    
    .rating-text {
        display: none;
    }
    
    .btn-primary {
        background-color: #EC6A6A;
        border-color: #EC6A6A;
    }
    
    .btn-primary:hover, .btn-primary:focus {
        background-color: #d65c5c;
        border-color: #d65c5c;
    }
    
    .circle-avatar {
        background-color: #EC6A6A;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-weight: bold;
        margin-right: 15px;
    }
</style>
@endsection

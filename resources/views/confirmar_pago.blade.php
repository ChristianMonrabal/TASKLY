@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h2 class="text-center mb-0">Confirmar Pago</h2>
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
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle mr-2"></i> ¡Trabajo completado exitosamente!
                        </div>
                        
                        <h4>{{ $trabajo->titulo }}</h4>
                        <p class="text-muted">{{ $trabajo->descripcion }}</p>
                        
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Detalles del pago</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Monto a pagar:</strong></p>
                                        <h3 class="text-primary">€{{ number_format($trabajo->precio, 2) }}</h3>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Trabajador:</strong></p>
                                        <div class="d-flex align-items-center">
                                            <div class="circle-avatar mr-2">{{ substr($trabajador->name, 0, 1) }}</div>
                                            <div>{{ $trabajador->name }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <p class="mb-0">Al confirmar el pago, indicas que has pagado al trabajador por los servicios prestados. Esta acción finalizará completamente el trabajo.</p>
                    </div>

                    <form action="{{ route('confirmar_pago', $trabajo->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="metodo_pago">Método de pago:</label>
                            <select class="form-control" id="metodo_pago" name="metodo_pago" required>
                                <option value="">Seleccionar método de pago</option>
                                @foreach($metodosPago as $metodo)
                                    <option value="{{ $metodo->id }}" {{ old('metodo_pago') == $metodo->id ? 'selected' : '' }}>
                                        {{ $metodo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('metodo_pago')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="confirmar_checkbox" required>
                            <label class="form-check-label" for="confirmar_checkbox">
                                Confirmo que he pagado el monto de €{{ number_format($trabajo->precio, 2) }} al trabajador
                            </label>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('trabajos.publicados') }}" class="btn btn-secondary">Volver</a>
                            <button type="submit" id="btn_confirmar" class="btn btn-primary" disabled>Confirmar Pago</button>
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
    .text-primary {
        color: #EC6A6A !important;
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
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-weight: bold;
        margin-right: 10px;
    }
    
    .card-title {
        color: #333;
        font-weight: 600;
    }
    
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const confirmarCheckbox = document.getElementById('confirmar_checkbox');
        const btnConfirmar = document.getElementById('btn_confirmar');
        
        confirmarCheckbox.addEventListener('change', function() {
            btnConfirmar.disabled = !this.checked;
        });
    });
</script>
@endsection

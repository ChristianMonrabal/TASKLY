@extends('layouts.app')

@section('title', 'Mis Trabajos Publicados - TASKLY')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/trabajos_publicados.css') }}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="{{ asset('js/sweet_alert_calendario.js') }}"></script>
    <script src="{{ asset('js/eliminar_trabajo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        /* Estilos para el botón de pago */
        .payment-btn {
            background-color: #fff;
            border: 1px solid #EC6A6A;
        }
        
        .payment-btn:hover {
            background-color: #EC6A6A;
        }
        
        .payment-btn:hover i {
            color: white !important;
        }
        
        .payment-action-btn {
            background-color: #EC6A6A;
            color: white;
            font-weight: bold;
        }
        
        .payment-action-btn:hover {
            background-color: #d85555;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-5">
        @if ($trabajos->count() > 0)
            <div class="trabajos-grid">
                @foreach ($trabajos as $trabajo)
                <div class="trabajo-item">
                    <div class="card">
                        <div class="card-img-container">
                            <div class="image-wrapper">
                                @if($trabajo->imagenes->isNotEmpty())
                                    <img src="{{ asset('img/trabajos/' . $trabajo->imagenes->first()->ruta_imagen) }}" class="card-img-top" alt="{{ $trabajo->titulo }}"/>
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" class="card-img-top" alt="No hay imagen"/>
                                @endif

                                <div class="icon-buttons">
                                    <button title="Añadir fecha de encuentro" class="icon-button add-date-btn" data-trabajo-id="{{ $trabajo->id }}">
                                        <i class="fas fa-calendar-plus"></i>
                                    </button>

                                    <a href="{{ route('trabajos.editar', $trabajo->id) }}" title="Editar" class="edit-btn icon-button">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @php
                                        $tienePago = App\Models\Pago::whereHas('postulacion', function($query) use ($trabajo) {
                                            $query->where('trabajo_id', $trabajo->id);
                                        })->exists();
                                    @endphp
                                    
                                    @if($tienePago)
                                        <a href="{{ route('payment.factura', $trabajo->id) }}" title="Descargar factura" class="icon-button" style="background-color: #4CAF50;">
                                            <i class="fas fa-file-invoice-dollar" style="color: white;"></i>
                                        </a>
                                    @else
                                        <a href="#" onclick="event.preventDefault(); confirmDeleteTrabajo({{ $trabajo->id }});" title="Eliminar" class="icon-button">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    @endif
                                    
                                    @php
                                        $postulacion = App\Models\Postulacion::where('trabajo_id', $trabajo->id)
                                            ->where('estado_id', 10) // Estado aceptado
                                            ->first();
                                    @endphp
                                    
                                    @if($postulacion)
                                        <a href="{{ route('payment.show', $trabajo->id) }}" title="Realizar pago" class="icon-button payment-btn">
                                            <i class="fas fa-credit-card" style="color:#EC6A6A;"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <br/>
                        <div class="card-body">
                            <h2 class="card-title">{{ $trabajo->titulo }}</h2>
                            <p class="card-text">{{ Str::limit($trabajo->descripcion, 100) }}</p>
                            <p class="card-text"><strong>Precio:</strong> {{ $trabajo->precio }} €</p>
                        </div>
                        <div class="card-actions">
                            <div class="action-buttons">
                                <a href="{{ route('trabajos.detalle', $trabajo->id) }}" class="action-btn">Ver detalles</a>
                                <a href="/candidatos_trabajo/{{ $trabajo->id }}" class="action-btn">Ver candidatos</a>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @if(session('error'))
                    <div class="alert alert-danger">
                        {!! session('error') !!}
                    </div>
                @endif
            </div>
        @else
            <div class="alert">
                <p>No has publicado ningún trabajo.</p>
            </div>
        @endif
    </div>
@endsection


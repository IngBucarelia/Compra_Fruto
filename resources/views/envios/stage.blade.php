@extends('layouts.app')

@section('content')
<style>
      body{
        background-image: url('{{ asset('images/fondo_envios.png') }}'); 
    }
    @media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    .grid-3 { grid-template-columns: repeat(2,1fr);}

    .grid-3 { grid-template-columns: 1fr; }
    .btn-circle {
        width: 30px;
        height: 30px;
    }
    
    .table-responsive {
        font-size: 0.9em;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group .form-control {
        margin-bottom: 10px;
    }
     .container {
        margin-left: -70px;
        width: 125%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }

        .card{
        width: 100%;
    }
}
</style>
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="text-success mb-0">
                <i class="fas fa-box me-2"></i>Detalle del Envío #{{ $envio->id }}
            </h5>
            <a href="{{ route('envios.index') }}" class="btn btn-outline-secondary">⬅ Volver</a>
        </div>

        <div class="card-body">
            <p><strong>Plantación:</strong> {{ $envio->plantacion->nombre ?? 'N/A' }}</p>
            <p><strong>Descripción:</strong> {{ $envio->descripcion_envio ?? 'N/A' }}</p>
            <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($envio->fecha_envio)->format('d/m/Y') }}</p>
            <p><strong>Estado actual:</strong> 
                <span class="badge bg-{{ 
                    $envio->estado == 'completado' ? 'success' : 
                    ($envio->estado == 'en_proceso' ? 'warning' : 'secondary') 
                }}">
                    {{ ucfirst($envio->estado) }}
                </span>
            </p>

            {{-- Botón para comenzar --}}
            @if($envio->estado == 'planificado')
                <form action="{{ route('envios.comenzar', $envio->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        🚀 Comenzar Envío
                    </button>
                </form>
            @elseif($envio->estado == 'en_proceso')
                <div class="alert alert-warning mt-3">
                    El envío se encuentra <strong>en proceso</strong>.
                </div>
            @else
                <div class="alert alert-success mt-3">
                    El envío fue <strong>completado</strong>.
                </div>
            @endif
        </div>
        <a href="{{ route('envios.pdf', $envio->id) }}" target="_blank" class="btn btn-outline-success">
    <i class="fas fa-file-pdf"></i> Imprimir PDF
</a>

    </div>
</div>
@endsection

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
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-success">
                <i class="fas fa-truck me-2"></i>Envíos Planificados
            </h5>
            <a href="{{ route('envios.create') }}" class="btn btn-success">➕ Nuevo Envío</a>
        </div>

        <div class="card-body">
            @if($envios->count())
                <div class="list-group">
                    @foreach($envios as $envio)
                        {{-- Enlace al stage (detalle) del envío --}}
                        <a href="{{ route('envios.stage', $envio->id) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <strong>#{{ $envio->id }} - {{ $envio->plantacion->nombre ?? 'Plantación' }}</strong>
                                <div class="small text-muted">{{ $envio->descripcion_envio }}</div>
                                <div class="small text-muted">
                                    Fecha: {{ \Carbon\Carbon::parse($envio->fecha_envio)->format('d/m/Y') }} —
                                    Estado: {{ ucfirst($envio->estado) }}
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-{{ 
                                    $envio->estado == 'completado' ? 'success' : 
                                    ($envio->estado == 'en_proceso' ? 'warning' : 'secondary') 
                                }}">
                                    {{ ucfirst($envio->estado) }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-3">
                    {{ $envios->links() }}
                </div>
            @else
                <p class="text-muted">No hay envíos planificados.</p>
            @endif
        </div>
    </div>
</div>
@endsection

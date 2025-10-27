@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-success"><i class="fas fa-truck me-2"></i>Envíos Planificados</h5>
            <a href="{{ route('envios.create') }}" class="btn btn-success">➕ Nuevo Envío</a>
        </div>
        <div class="card-body">
            @if($envios->count())
                <div class="list-group">
                    @foreach($envios as $envio)
                        <a href="{{ route('envios.show', $envio->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <strong>#{{ $envio->id }} - {{ $envio->plantacion->nombre ?? 'Plantación' }}</strong>
                                <div class="small text-muted">{{ $envio->descripcion_envio }}</div>
                                <div class="small text-muted">Fecha: {{ $envio->fecha_envio->format('d/m/Y') }} — Estado: {{ ucfirst($envio->estado) }}</div>
                            </div>
                            <div>
                                <span class="badge bg-{{ $envio->estado == 'completado' ? 'success' : ($envio->estado == 'en_ejecucion' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($envio->estado) }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-3">{{ $envios->links() }}</div>
            @else
                <p>No hay envíos planificados.</p>
            @endif
        </div>
    </div>
</div>
@endsection

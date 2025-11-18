@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><i class="fas fa-truck me-2"></i>Preparar Envío #{{ $envio->id }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Plantación:</strong> {{ $envio->plantacion->nombre ?? 'Sin nombre' }}</p>
            <p><strong>Proveedor:</strong> {{ $envio->proveedor->proveedor_nombre ?? 'Sin proveedor' }}</p>
            <p><strong>Técnico:</strong> {{ $envio->tecnico->name ?? 'Sin asignar' }}</p>
            <p><strong>Fecha envío:</strong> {{ optional($envio->fecha_envio)->format('d/m/Y') }}</p>
            <p><strong>Descripción:</strong> {{ $envio->descripcion_envio }}</p>

            <div class="mt-4 text-end">
                <a href="{{ route('envios.index') }}" class="btn btn-secondary">⬅️ Atrás</a>
                <form action="{{ route('envios.iniciar', $envio->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">🚀 Comenzar Envío</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

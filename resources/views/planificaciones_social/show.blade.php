@extends('layouts.app')

@section('content')
<div class="container" style="background-color: whitesmoke; padding: 20px;">
    <h2>Detalle de Planificación Social</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Fecha:</strong> {{ $planificacionSocial->fecha }}</p>
            <p><strong>Proveedor:</strong> {{ $planificacionSocial->proveedor->proveedor_nombre ?? '-' }}</p>
            <p><strong>Plantación:</strong> {{ $planificacionSocial->plantacion->nombre ?? '-' }}</p>
            <p><strong>Técnico:</strong> {{ $planificacionSocial->tecnico->name ?? '-' }}</p>
            <p><strong>Tipo de visita:</strong> {{ $planificacionSocial->tipo_visita }}</p>
            <p><strong>Estado:</strong> 
                <span class="badge bg-secondary">{{ ucfirst($planificacionSocial->estado) }}</span>
            </p>
        </div>
    </div>

    {{-- 🔹 Aquí puedes agregar lógica similar a visitas agronómicas si tienes visitas sociales --}}
    @if ($planificacionSocial->visita)
        <a href="{{ route('visitas_social.show', $planificacionSocial->visita->id) }}" class="btn btn-success mt-3">
            👀 Ver visita social
        </a>
    @else
        
            @csrf
            <input type="hidden" name="planificacion_social_id" value="{{ $planificacionSocial->id }}">
            <input type="hidden" name="id_proveedor" value="{{ $planificacionSocial->id_proveedor }}">
            <input type="hidden" name="id_plantacion" value="{{ $planificacionSocial->id_plantacion }}">
            <input type="hidden" name="id_usuario" value="{{ $planificacionSocial->id_usuario }}">
            <input type="hidden" name="tipo_visita" value="{{ $planificacionSocial->tipo_visita }}">
            <input type="hidden" name="estado" value="pendiente">
            
            <a href="{{ route('visitas_social.indexSocial') }}"><button type="submit" class="btn btn-primary mt-3">
                ➕ Ir a Lista visita social
            </button></a>
        
    @endif

    <a href="{{ route('planificaciones_social.index') }}" class="btn btn-secondary mt-3">← Volver</a>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container" style="background-color: whitesmoke; padding: 20px;">
    <h2>Detalle de Planificación</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Fecha:</strong> {{ $planificacion->fecha }}</p>
            <p><strong>Proveedor:</strong> {{ $planificacion->proveedor->proveedor_nombre ?? '-' }}</p>
            <p><strong>Plantación:</strong> {{ $planificacion->plantacion->nombre ?? '-' }}</p>
            <p><strong>Técnico:</strong> {{ $planificacion->tecnico->name ?? '-' }}</p>
            <p><strong>Tipo de visita:</strong> {{ $planificacion->tipo_visita }}</p>
            <p><strong>Estado6:</strong> 
                <span class="badge bg-secondary">{{ ucfirst($planificacion->estado) }}</span>
            </p>
        </div>
    </div>

    @if ($planificacion->visita)
        @php
            $estadoVisita = $planificacion->visita->estado;
        @endphp

        @if ($estadoVisita === 'pendiente')
            <a href="{{ route('areas.create', ['visita_id' => $planificacion->visita->id]) }}" class="btn btn-success mt-3">
                🟢 Comenzar visita
            </a>
        @elseif ($estadoVisita === 'en_ejecucion')
            <a href="{{ route('visitas.show', ['visita' => $planificacion->visita]) }}" class="btn btn-warning mt-3">
                🟡 Continuar visita
            </a>
        @elseif ($estadoVisita === 'finalizada')
            <div class="alert alert-info mt-3">
                ✅ Esta visita ya fue finalizada.
            </div>
        @endif
    @else
        <form method="POST" action="{{ route('visitas.store') }}">
            @csrf
            <input type="hidden" name="planificacion_id" value="{{ $planificacion->id }}">
            <input type="hidden" name="id_proveedor" value="{{ $planificacion->id_proveedor }}">
            <input type="hidden" name="id_plantacion" value="{{ $planificacion->id_plantacion }}">
            <input type="hidden" name="id_usuario" value="{{ $planificacion->id_usuario }}">
            <input type="hidden" name="tipo_visita" value="{{ $planificacion->tipo_visita }}">
            <input type="hidden" name="estado" value="pendiente">
             @if(Auth::check() && in_array(Auth::user()->rol, [1,2]))
            <button type="submit" class="btn btn-primary mt-3">
                ➕ Crear y comenzar visita
            </button>
            @endif
        </form>

        @if ($planificacion->visita)
    <div class="mt-4">
        <h4>📝 Resumen de Visita</h4>

        {{-- Resumen Área --}}
        @if ($planificacion->visita->area)
            <div class="card mb-3">
                <div class="card-header">📍 Área registrada</div>
                <div class="card-body">
                    <p><strong>Material:</strong> {{ $planificacion->visita->area->material }}</p>
                    <p><strong>Estado:</strong> {{ $planificacion->visita->area->estado }}</p>
                    <p><strong>Año de siembra:</strong> {{ $planificacion->visita->area->anio_siembra }}</p>
                    <p><strong>Área:</strong> {{ $planificacion->visita->area->area }} m²</p>
                    <p><strong>Orden plantis:</strong> {{ $planificacion->visita->area->orden_plantis_numero }}</p>
                    <p><strong>Estado orden plantis:</strong> {{ $planificacion->visita->area->estado_oren_plantis }}</p>
                </div>
            </div>
        @endif

        {{-- Resumen Fertilizaciones --}}
        @if ($planificacion->visita->fertilizaciones->count())
            <div class="card">
                <div class="card-header">💧 Fertilizaciones registradas</div>
                <div class="card-body">
                    @foreach ($planificacion->visita->fertilizaciones as $fertilizacion)
                        <div class="mb-3">
                            <p><strong>Fecha:</strong> {{ $fertilizacion->fecha_fertilizacion }}</p>
                            <ul>
                                @foreach ($fertilizacion->fertilizantes as $f)
                                    <li>{{ ucfirst($f->nombre) }} - {{ $f->cantidad }} kg</li>
                                @endforeach
                            </ul>
                            <hr>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-muted">No hay fertilizaciones registradas aún.</p>
        @endif
    </div>
@endif

    @endif

    <a href="{{ route('planificaciones.index') }}" class="btn btn-secondary mt-3">← Volver</a>
</div>

@endsection

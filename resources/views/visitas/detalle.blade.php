@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Detalle de Visita - {{ $visita->proveedor->proveedor_nombre ?? 'Proveedor desconocido' }}</h3>

    <hr>
    <h4>📍 Área</h4>
    @if ($visita->area)
        <ul>
            <li><strong>Material:</strong> {{ $visita->area->material }}</li>
            <li><strong>Estado:</strong> {{ $visita->area->estado }}</li>
            <li><strong>Año siembra:</strong> {{ $visita->area->anio_siembra }}</li>
            <li><strong>Área (m²):</strong> {{ $visita->area->area }}</li>
            <li><strong>Orden Plantis N°:</strong> {{ $visita->area->orden_plantis_numero }}</li>
            <li><strong>Estado orden Plantis:</strong> {{ $visita->area->estado_orden_plantis }}</li>
        </ul>
    @else
        <p>No se ha registrado información de área.</p>
    @endif

    <hr>
    <h4>💧 Fertilizaciones</h4>
@if ($visita->fertilizaciones->count())
    @foreach ($visita->fertilizaciones as $fertilizacion)
        <div class="mb-4">
            <h5>📅 Fecha: {{ $fertilizacion->fecha_fertilizacion }}</h5>
            @if ($fertilizacion->detalles->count())
                <ul class="list-group">
                    @foreach ($fertilizacion->detalles as $detalle)
                        <li class="list-group-item">
                            Fertilizante: <strong>{{ $detalle->fertilizante }}</strong> |
                            Cantidad: <strong>{{ $detalle->cantidad }} kg</strong>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No hay fertilizantes registrados.</p>
            @endif
        </div>
    @endforeach
@else
    <p>No hay fertilizaciones registradas.</p>
@endif

    <a href="{{ route('visitas.index') }}" class="btn btn-secondary mt-3">⬅️ Volver</a>
</div>
@endsection

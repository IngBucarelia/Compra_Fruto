@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Detalle de Visita</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Fecha:</strong> {{ $visita->fecha }}</p>
            <p><strong>Proveedor:</strong> {{ $visita->proveedor->proveedor_nombre }}</p>
            <p><strong>Técnico de campo:</strong> {{ $visita->tecnico->name }}</p>
            <p><strong>Tipo de visita:</strong> {{ $visita->tipo_visita }}</p>
            <p><strong>Ubicación:</strong> {{ $visita->ubicacion }}</p>
            <p><strong>Recibió la visita:</strong> {{ $visita->recibio_visita }}</p>
             <p><strong>Estado de visita:</strong> {{ $visita->estado }}</p>
        </div>
    </div>
    <!-- Botón dinámico según el estado de la visita -->
        @if ($visita->estado === 'pendiente')
            <a href="{{ route('areas.create', ['visita_id' => $visita->id]) }}" class="btn btn-success">
                🟢 Comenzar visita
            </a>
        @elseif ($visita->estado === 'en_ejecucion')
            <a href="{{ route('fertilizaciones.create', ['visita_id' => $visita->id]) }}" class="btn btn-warning">
                🔄 Continuar visita
            </a>
        @elseif ($visita->estado === 'finalizada')
            <!-- No mostrar botón -->
        @endif


    <a href="{{ route('visitas.detalle', $visita->id) }}" class="btn btn-info btn-sm">🔍 Ver detalle</a>


    <a href="{{ route('visitas.index') }}" class="btn btn-secondary mt-3">Volver al listado</a>
    <h4 class="mt-4">Ubicación exacta en el mapa</h4>
<div id="map" style="height: 400px;" class="mb-4 border rounded shadow-sm"></div>

<!-- Leaflet CSS/JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const geo = "{{ $visita->plantacion->geolocalizacion ?? '' }}";

        if (!geo || !geo.includes(',')) {
            alert("No hay geolocalización disponible para esta visita.");
            return;
        }

        const [lat, lon] = geo.split(',').map(coord => parseFloat(coord.trim()));

        const map = L.map('map').setView([lat, lon], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        L.marker([lat, lon]).addTo(map)
            .bindPopup("Ubicación registrada")
            .openPopup();
    });
</script>


</div>
@endsection

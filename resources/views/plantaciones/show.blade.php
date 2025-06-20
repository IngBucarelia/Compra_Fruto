@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Detalle de Plantación</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-12"><strong>Proveedor:</strong> {{ $plantacion->proveedor->proveedor_nombre }}</div>
                <div class="col-12"><strong>Nombre:</strong> {{ $plantacion->nombre }}</div>
                <div class="col-12"><strong>Vereda:</strong> {{ $plantacion->vereda }}</div>
                <div class="col-12"><strong>Municipio:</strong> {{ $plantacion->municipio }}</div>
                <div class="col-12"><strong>Corregimiento:</strong> {{ $plantacion->corregimiento }}</div>
                <div class="col-12"><strong>Departamento:</strong> {{ $plantacion->departamento }}</div>
                <div class="col-12"><strong>Geolocalización:</strong> {{ $plantacion->geolocalizacion }}</div>
                <div class="col-12"><strong>Fecha de creación:</strong> {{ $plantacion->dia_creado }}</div>
            </div>
        </div>
    </div>

    <div id="map" style="height: 400px; margin-top: 20px;" class="rounded shadow-sm"></div>

    <div class="text-center mt-3">
        <a href="{{ route('plantaciones.index') }}" class="btn btn-secondary btn-block">Volver</a>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('map').setView([5.0, -72.0], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        const geo = "{{ $plantacion->geolocalizacion }}".split(',');
        const lat = parseFloat(geo[0]);
        const lng = parseFloat(geo[1]);

        if (!isNaN(lat) && !isNaN(lng)) {
            const marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup("Ubicación de la plantación").openPopup();
            map.setView([lat, lng], 14);
        }
    });
</script>
@endsection

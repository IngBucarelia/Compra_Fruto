@extends('layouts.app')

@section('content')

<style>

    .container{
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
    }

    .title{
    text-align: center; 
    font-family: Arial Black; 
    font-weight: bold; 
    font-size: 30px; 
    color: #fdffe5; 
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    }


    @media (max-width: 768px) {

        .container {
        margin-left: -35px;
        width: 110%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }
    }
</style>
<div class="container" >
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
    <hr>
<h4 class="mt-4">📋 Visitas asociadas a esta plantación</h4>

    @if ($plantacion->visitas->count())
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha de creación</th>
                    <th>Estado</th>
                    <th>Técnico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($plantacion->visitas as $visita)
                    <tr>
                        <td>{{ $visita->id }}</td>
                        <td>{{ $visita->created_at->format('Y-m-d') }}</td>
                        <td>
                            <span class="badge bg-{{ $visita->estado === 'finalizada' ? 'success' : ($visita->estado === 'en_ejecucion' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($visita->estado) }}
                            </span>
                        </td>
                        <td>{{ $visita->tecnico->name ?? 'No asignado' }}</td>
                        <td>
                            <a href="{{ route('visitas.show', $visita->id) }}" class="btn btn-sm btn-outline-primary">
                                Ver detalle
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">No hay visitas registradas para esta plantación.</p>
    @endif


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

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Visita</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('visitas.update', $visita->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Fecha:</label>
            <input type="date" name="fecha" value="{{ $visita->fecha }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Proveedor:</label>
            <select name="proveedor_id" class="form-control" required>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" {{ $visita->proveedor_id == $proveedor->id ? 'selected' : '' }}>
                        {{ $proveedor->proveedor_nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
        <label>Plantación:</label>
            <select id="plantacion-select" name="plantacion_id" class="form-control" required>
                @foreach($plantaciones as $plantacion)
                    <option value="{{ $plantacion->id }}" {{ $visita->plantacion_id == $plantacion->id ? 'selected' : '' }}>
                        {{ $plantacion->nombre }} - {{ $plantacion->vereda }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="mb-3">
            <label>Ubicación:</label>
            <input type="text" name="ubicacion" value="{{ $visita->ubicacion }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Técnico de campo:</label>
            <select name="tecnico_campo" class="form-control" required>
                @foreach($tecnicos as $user)
                    <option value="{{ $user->id }}" {{ $visita->tecnico_campo == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tipo de visita:</label>
            <select name="tipo_visita" class="form-control" required>
                <option value="Inicial" {{ $visita->tipo_visita == 'Inicial' ? 'selected' : '' }}>Inicial</option>
                <option value="Seguimiento" {{ $visita->tipo_visita == 'Seguimiento' ? 'selected' : '' }}>Seguimiento</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Recibió la visita:</label>
            <input type="text" name="recibio_visita" value="{{ $visita->recibio_visita }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('visitas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <h5 class="mt-4">Ubicación en el mapa</h5>
    <div id="map" style="height: 400px;" class="mb-4 border rounded shadow-sm"></div>

</div>
<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const initialGeo = "{{ $visita->plantacion->geolocalizacion ?? '' }}";
        const map = L.map('map').setView([4.6097, -74.0817], 6);
        let marker;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        function setMarker(geo) {
            if (!geo || !geo.includes(',')) return;

            const [lat, lon] = geo.split(',').map(coord => parseFloat(coord.trim()));
            if (!lat || !lon) return;

            if (marker) marker.setLatLng([lat, lon]);
            else marker = L.marker([lat, lon]).addTo(map);

            map.setView([lat, lon], 15);
        }

        // Inicial
        setMarker(initialGeo);

        // Si cambia la plantación → traer nueva geo
        document.getElementById('plantacion-select').addEventListener('change', function () {
            const id = this.value;
            if (!id) return;

            fetch(`/api/plantacion/geo/${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.geolocalizacion) {
                        setMarker(data.geolocalizacion);
                    }
                });
        });
    });
</script>

@endsection

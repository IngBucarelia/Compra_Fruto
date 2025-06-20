@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Plantación</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ups, hubo algunos errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('plantaciones.update', $plantacion->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Proveedor</label>
            <select name="id_proveedor" class="form-control" required>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" {{ $plantacion->id_proveedor == $proveedor->id ? 'selected' : '' }}>
                        {{ $proveedor->proveedor_nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Nombre de Plantación</label>
            <input type="text" name="nombre" class="form-control" value="{{ $plantacion->nombre }}" required>
        </div>

        <div class="mb-3">
            <label>Vereda</label>
            <input type="text" name="vereda" id="vereda" class="form-control" value="{{ $plantacion->vereda }}" >
        </div>

        <div class="mb-3">
            <label>Municipio</label>
            <input type="text" name="municipio" id="municipio" class="form-control" value="{{ $plantacion->municipio }}" required>
        </div>

        <div class="mb-3">
            <label>Corregimiento</label>
            <input type="text" name="corregimiento" id="corregimiento" class="form-control" value="{{ $plantacion->corregimiento }}" >
        </div>

        <div class="mb-3">
            <label>Departamento</label>
            <input type="text" name="departamento" id="departamento" class="form-control" value="{{ $plantacion->departamento }}" required>
        </div>

        <div class="mb-3">
            <label>Geolocalización</label>
            <input type="text" id="geolocalizacion" name="geolocalizacion" class="form-control" value="{{ $plantacion->geolocalizacion }}" required>
        </div>

        

        <div class="mb-3">
            <label>Fecha de creación</label>
            <input type="date" name="dia_creado" class="form-control" value="{{ $plantacion->dia_creado }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('plantaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <div class="mt-4">
        <div id="map" style="height: 400px;" class="mb-3 rounded shadow-sm"></div>
        <label>Buscador del mapa</label>
        <input type="text" id="search" class="form-control" placeholder="Buscar lugar...">
    </div>
</div>

{{-- Leaflet JS y CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const geo = "{{ $plantacion->geolocalizacion }}".split(',');
    const lat = parseFloat(geo[0]);
    const lng = parseFloat(geo[1]);

    const map = L.map('map').setView([lat, lng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    let marker = L.marker([lat, lng]).addTo(map);

    // click en mapa
    map.on('click', function (e) {
        const { lat, lng } = e.latlng;
        document.getElementById('geolocalizacion').value = `${lat.toFixed(6)},${lng.toFixed(6)}`;
        marker.setLatLng([lat, lng]);
        reverseGeocode(lat, lng);
    });

    // cambio manual
    document.getElementById('geolocalizacion').addEventListener('change', function () {
        const coords = this.value.split(',').map(Number);
        if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
            marker.setLatLng(coords);
            map.setView(coords, 14);
            reverseGeocode(coords[0], coords[1]);
        }
    });

    // buscador
    document.getElementById('search').addEventListener('change', function () {
        const query = this.value;
        if (!query) return;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    document.getElementById('geolocalizacion').value = `${lat.toFixed(6)},${lon.toFixed(6)}`;
                    marker.setLatLng([lat, lon]);
                    map.setView([lat, lon], 14);
                    reverseGeocode(lat, lon);
                }
            });
    });

    // geocodificación inversa
    function reverseGeocode(lat, lon) {
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
            .then(response => response.json())
            .then(data => {
                const a = data.address;
                document.getElementById('vereda').value = a.village || a.hamlet || '';
                document.getElementById('municipio').value = a.city || a.town || a.municipality || '';
                document.getElementById('corregimiento').value = a.suburb || a.neighbourhood || '';
                document.getElementById('departamento').value = a.state || '';
            });
    }
</script>
@endsection

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
    <h2 class="mb-4">Crear Plantación</h2>
@if ($errors->any())
    <div class="alert alert-danger">
        <h5><strong>Ups, hubo algunos errores:</strong></h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form method="POST" action="{{ route('plantaciones.store') }}">
        @csrf

        <div class="mb-3">
            <label>Proveedor</label>
            <select name="id_proveedor" class="form-control" required>
                <option value="">Seleccione un proveedor</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}">{{ $proveedor->proveedor_nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Nombre de Plantación</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Vereda</label>
            <input type="text" name="vereda" id="vereda" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Municipio</label>
            <input type="text" name="municipio" id="municipio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Corregimiento</label>
            <input type="text" name="corregimiento" id="corregimiento" class="form-control" >
        </div>

        <div class="mb-3">
            <label>Departamento</label>
            <input type="text" name="departamento" id="departamento" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Fecha de creación</label>
            <input type="date" name="dia_creado" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
        </div>


        <div class="mb-3">
            <label>Geolocalización (lat,lng)</label>
            <input type="text" id="geolocalizacion" name="geolocalizacion" class="form-control" placeholder="Ingrese Datos o Seleccione en el mapa " required>
        </div>

       

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('plantaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

    <br><br>
     <div id="map" style="height: 400px;" class="mb-3"></div>
    <label>Buscador del Mapa</label>
    <div class="mb-3">
        <input type="text" id="search" class="form-control" placeholder="Buscar lugar...">
    </div>
</div>

<script>
document.querySelector('form').addEventListener('submit', function (e) {
    if (!navigator.onLine) {
        e.preventDefault();

        const formData = new FormData(this);
        const jsonData = {};
        formData.forEach((val, key) => {
            jsonData[key] = val;
        });

        localStorage.setItem('plantacion_offline', JSON.stringify(jsonData));
        alert('No tienes conexión. El formulario se guardó localmente y se enviará automáticamente cuando vuelva el internet.');
    }
});

// Intentar reenvío automático al volver conexión
window.addEventListener('online', function () {
    const stored = localStorage.getItem('plantacion_offline');
    if (stored) {
        const data = JSON.parse(stored);

        fetch("{{ route('plantaciones.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(res => {
            if (res.ok) {
                alert("Formulario enviado correctamente al recuperar conexión.");
                localStorage.removeItem('plantacion_offline');
                location.href = "{{ route('plantaciones.index') }}";
            } else {
                console.error("Error al enviar al reconectar");
            }
        });
    }
});
</script>


<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    const map = L.map('map').setView([5.0, -72.0], 6);
    let marker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19
    }).addTo(map);

    const input = document.getElementById('geolocalizacion');

    // Si ya hay coordenadas, colocar marcador
    if (input.value) {
        const [lat, lng] = input.value.split(',').map(Number);
        marker = L.marker([lat, lng]).addTo(map);
        map.setView([lat, lng], 14);
    }

    // Al hacer clic en el mapa
    map.on('click', function (e) {
        const { lat, lng } = e.latlng;
        input.value = `${lat.toFixed(6)},${lng.toFixed(6)}`;

        if (marker) marker.setLatLng([lat, lng]);
        else marker = L.marker([lat, lng]).addTo(map);

        reverseGeocode(lat, lng);
    });

    // Cambios en input manual de geolocalización
    input.addEventListener('change', () => {
        const [lat, lng] = input.value.split(',').map(Number);
        if (!isNaN(lat) && !isNaN(lng)) {
            if (marker) marker.setLatLng([lat, lng]);
            else marker = L.marker([lat, lng]).addTo(map);
            map.setView([lat, lng], 14);
            reverseGeocode(lat, lng);
        }
    });

    // Buscador de dirección
    document.getElementById('search').addEventListener('change', function () {
        const query = this.value;
        if (!query) return;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lon = parseFloat(data[0].lon);
                    input.value = `${lat.toFixed(6)},${lon.toFixed(6)}`;
                    if (marker) marker.setLatLng([lat, lon]);
                    else marker = L.marker([lat, lon]).addTo(map);
                    map.setView([lat, lon], 14);
                    reverseGeocode(lat, lon);
                } else {
                    alert("No se encontraron resultados");
                }
            });
    });

    // Función para hacer geocodificación inversa
    function reverseGeocode(lat, lon) {
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
            .then(response => response.json())
            .then(data => {
                const address = data.address;

                document.getElementById('vereda').value = address.village || address.hamlet || '';
                document.getElementById('municipio').value = address.city || address.town || address.municipality || '';
                document.getElementById('corregimiento').value = address.suburb || address.neighbourhood || '';
                document.getElementById('departamento').value = address.state || '';
            })
            .catch(error => {
                console.error('Error obteniendo datos de Nominatim:', error);
            });
    }
</script>

@endsection

@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <!-- Header con Estadísticas -->
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-info py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-black">
                                <i class="fas fa-edit me-2"></i>
                                Editar Plantación
                            </h4>
                            <p class="mb-0 text-black-50 small">Actualice la información de la plantación</p>
                        </div>
                        <div class="text-end">
                            <div class="badge  text-withe-50 fs-6 p-2" style="background-color: darkgreen">
                                <i class="fas fa-seedling me-1"></i>
                                ID: #{{ $plantacion->id }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Principal -->
            <div class="card shadow">
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Por favor corrige los siguientes errores:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('plantaciones.update', $plantacion->id) }}" id="plantacionForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Proveedor -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-building me-2"></i>Proveedor
                                </label>
                                <select name="id_proveedor" class="form-control form-control-lg" required
                                        style="border-radius: 10px; border: 2px solid #e9ecef;">
                                    @foreach($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}" {{ $plantacion->id_proveedor == $proveedor->id ? 'selected' : '' }}>
                                            {{ $proveedor->proveedor_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nombre de Plantación -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-seedling me-2"></i>Nombre de Plantación
                                </label>
                                <input type="text" name="nombre" class="form-control form-control-lg" 
                                       value="{{ $plantacion->nombre }}" required
                                       placeholder="Ingrese el nombre de la plantación"
                                       style="border-radius: 10px; border: 2px solid #e9ecef;">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Vereda -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-map-marker-alt me-2"></i>Vereda
                                </label>
                                <input type="text" name="vereda" id="vereda" class="form-control form-control-lg" 
                                       value="{{ $plantacion->vereda }}"
                                       placeholder="Nombre de la vereda"
                                       style="border-radius: 10px; border: 2px solid #e9ecef;">
                            </div>

                            <!-- Municipio -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-city me-2"></i>Municipio
                                </label>
                                <input type="text" name="municipio" id="municipio" class="form-control form-control-lg" 
                                       value="{{ $plantacion->municipio }}" required
                                       placeholder="Nombre del municipio"
                                       style="border-radius: 10px; border: 2px solid #e9ecef;">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Corregimiento -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-location-dot me-2"></i>Corregimiento
                                </label>
                                <input type="text" name="corregimiento" id="corregimiento" class="form-control form-control-lg" 
                                       value="{{ $plantacion->corregimiento }}"
                                       placeholder="Corregimiento (opcional)"
                                       style="border-radius: 10px; border: 2px solid #e9ecef;">
                            </div>

                            <!-- Departamento -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-globe-americas me-2"></i>Departamento
                                </label>
                                <input type="text" name="departamento" id="departamento" class="form-control form-control-lg" 
                                       value="{{ $plantacion->departamento }}" required
                                       placeholder="Nombre del departamento"
                                       style="border-radius: 10px; border: 2px solid #e9ecef;">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Fecha de creación -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-calendar-alt me-2"></i>Fecha de Creación
                                </label>
                                <input type="date" name="dia_creado" class="form-control form-control-lg" 
                                       value="{{ $plantacion->dia_creado }}" required
                                       style="border-radius: 10px; border: 2px solid #e9ecef;">
                            </div>

                            <!-- Geolocalización -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-map-pin me-2"></i>Geolocalización
                                </label>
                                <input type="text" id="geolocalizacion" name="geolocalizacion" class="form-control form-control-lg" 
                                       value="{{ $plantacion->geolocalizacion }}" required
                                       placeholder="Latitud, Longitud o seleccione en el mapa"
                                       style="border-radius: 10px; border: 2px solid #e9ecef;">
                            </div>
                        </div>

                        <!-- Mapa Section -->
                        <div class="card mt-4 mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 text-success">
                                    <i class="fas fa-map me-2"></i>Mapa de Ubicación
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Buscador del Mapa -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-success">
                                        <i class="fas fa-search me-2"></i>Buscar en el Mapa
                                    </label>
                                    <input type="text" id="search" class="form-control form-control-lg" 
                                           placeholder="Buscar lugar (ej: Bogotá, Colombia)..."
                                           style="border-radius: 10px; border: 2px solid #e9ecef;">
                                </div>
                                
                                <!-- Mapa -->
                                <div id="map" style="height: 400px; border-radius: 10px;"></div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-info-circle me-1"></i>Haga clic en el mapa para actualizar la ubicación
                                </small>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-3 flex-wrap">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>Actualizar Plantación
                            </button>
                            <a href="{{ route('plantaciones.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.form-control, .form-select {
    border-radius: 10px !important;
    border: 2px solid #e9ecef !important;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #36b9cc !important;
    box-shadow: 0 0 0 0.2rem rgba(54, 185, 204, 0.25) !important;
}

.btn-success {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    border-radius: 10px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn-outline-secondary {
    border-radius: 10px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.alert {
    border-radius: 10px;
    border: none;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 10px !important;
    }
    
    .card-body {
        padding: 20px !important;
    }
    
    .btn-lg {
        padding: 10px 20px !important;
        font-size: 0.9rem;
    }
    
    .form-control-lg {
        font-size: 0.9rem !important;
    }
    
    .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .d-flex.flex-wrap {
        flex-direction: column;
    }
    
    .d-flex.flex-wrap .btn {
        width: 100%;
        margin-bottom: 10px;
    }
    
    #map {
        height: 300px !important;
    }
}

@media (max-width: 576px) {
    #map {
        height: 250px !important;
    }
}
</style>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar mapa con las coordenadas existentes
    const geo = "{{ $plantacion->geolocalizacion }}".split(',');
    const lat = parseFloat(geo[0]);
    const lng = parseFloat(geo[1]);

    const map = L.map('map').setView([lat, lng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker = L.marker([lat, lng]).addTo(map);

    // Click en mapa para actualizar ubicación
    map.on('click', function (e) {
        const { lat, lng } = e.latlng;
        document.getElementById('geolocalizacion').value = `${lat.toFixed(6)},${lng.toFixed(6)}`;
        marker.setLatLng([lat, lng]);
        reverseGeocode(lat, lng);
    });

    // Cambio manual en el input de geolocalización
    document.getElementById('geolocalizacion').addEventListener('change', function () {
        const coords = this.value.split(',').map(Number);
        if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
            marker.setLatLng(coords);
            map.setView(coords, 14);
            reverseGeocode(coords[0], coords[1]);
        }
    });

    // Buscador de direcciones
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

    // Función para geocodificación inversa
    function reverseGeocode(lat, lon) {
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
            .then(response => response.json())
            .then(data => {
                const a = data.address;
                document.getElementById('vereda').value = a.village || a.hamlet || '';
                document.getElementById('municipio').value = a.city || a.town || a.municipality || '';
                document.getElementById('corregimiento').value = a.suburb || a.neighbourhood || '';
                document.getElementById('departamento').value = a.state || '';
            })
            .catch(error => {
                console.error('Error en geocodificación inversa:', error);
            });
    }
});
</script>
@endsection
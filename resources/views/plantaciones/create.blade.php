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
                                <i class="fas fa-plus-circle me-2"></i>
                                Crear Nueva Plantación
                            </h4>
                            <p class="mb-0 text-white-50 small">Complete los datos para registrar una nueva plantación</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-white text-info fs-6 p-2">
                                <i class="fas fa-seedling me-1"></i>
                                Nueva Plantación
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

                    <form method="POST" action="{{ route('plantaciones.store') }}" id="plantacionForm">
                        @csrf

                        <div class="row">
                            <!-- Proveedor -->
<div class="col-md-6 mb-3">
    <label class="form-label fw-bold text-success">
        <i class="fas fa-building me-2"></i>Proveedor
    </label>

    <div style="position: relative;">
        <!-- Campo de búsqueda -->
        <input type="text"
               id="buscarProveedor"
               class="form-control form-control-lg mb-2"
               placeholder="🔍 Buscar proveedor..."
               style="border-radius: 10px; border: 2px solid #e9ecef;">

        <!-- Select de proveedores -->
        <select id="id_proveedor"
                name="id_proveedor"
                class="form-control form-control-lg"
                required
                size="6"
                style="border-radius: 10px; border: 2px solid #e9ecef; height:auto;">
            <option value="">Seleccione un proveedor</option>
            @foreach($proveedores as $proveedor)
                <option value="{{ $proveedor->id }}">{{ $proveedor->proveedor_nombre }}</option>
            @endforeach
        </select>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputBusqueda = document.getElementById('buscarProveedor');
    const selectProveedor = document.getElementById('id_proveedor');

    inputBusqueda.addEventListener('keyup', () => {
        const filtro = inputBusqueda.value.toLowerCase();
        const opciones = selectProveedor.querySelectorAll('option');

        opciones.forEach(op => {
            const texto = op.textContent.toLowerCase();
            if (texto.includes(filtro) || op.value === '') {
                op.style.display = '';
            } else {
                op.style.display = 'none';
            }
        });

        // Mantener visible siempre la opción principal
        opciones[0].style.display = '';
    });
});
</script>


                            <!-- Nombre de Plantación -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-seedling me-2"></i>Nombre de Plantación
                                </label>
                                <input type="text" name="nombre" class="form-control form-control-lg" required
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
                                <input type="text" name="vereda" id="vereda" class="form-control form-control-lg" required
                                       placeholder="Nombre de la vereda"
                                       style="border-radius: 10px; border: 2px solid #e9ecef;">
                            </div>

                            <!-- Municipio -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-city me-2"></i>Municipio
                                </label>
                                <input type="text" name="municipio" id="municipio" class="form-control form-control-lg" required
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
                                       placeholder="Corregimiento (opcional)"
                                       style="border-radius: 10px; border: 2px solid #e9ecef;">
                            </div>

                            <!-- Departamento -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-globe-americas me-2"></i>Departamento
                                </label>
                                <input type="text" name="departamento" id="departamento" class="form-control form-control-lg" required
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
                                       value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required
                                       style="border-radius: 10px; border: 2px solid #e9ecef;">
                            </div>

                            <!-- Geolocalización -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-map-pin me-2"></i>Geolocalización
                                </label>
                                <input type="text" id="geolocalizacion" name="geolocalizacion" class="form-control form-control-lg" 
                                       placeholder="Latitud, Longitud o seleccione en el mapa" required
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
                                    <i class="fas fa-info-circle me-1"></i>Haga clic en el mapa para seleccionar la ubicación exacta
                                </small>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex gap-3 flex-wrap">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save me-2"></i>Guardar Plantación
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
    // Funcionalidad offline
    document.getElementById('plantacionForm').addEventListener('submit', function (e) {
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

    // Mapa Leaflet
    const map = L.map('map').setView([5.0, -72.0], 6);
    let marker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
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
});
</script>
@endsection
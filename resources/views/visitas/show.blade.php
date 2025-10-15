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
                            <h4 class="mb-0 text-green">
                                <i class="fas fa-clipboard-check me-2"></i>
                                Detalle de Visita
                            </h4>
                            <p class="mb-0 text-green-50 small">Información completa de la visita y secciones disponibles</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-green text-info fs-6 p-2">
                                <i class="fas fa-hashtag me-1"></i>
                                ID: #{{ $visita->id }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Información Principal -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-info-circle me-2"></i>Información de la Visita
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-calendar-alt me-2"></i>Fecha
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">{{ $visita->fecha }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-building me-2"></i>Proveedor
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">{{ $visita->proveedor->proveedor_nombre }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-user-tie me-2"></i>Técnico de Campo
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">{{ $visita->tecnico->name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-tasks me-2"></i>Tipo de Visita
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">{{ $visita->tipo_visita }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-map-marker-alt me-2"></i>Ubicación
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">{{ $visita->ubicacion }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-user-check me-2"></i>Recibió la Visita
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">{{ $visita->recibio_visita }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-circle me-2"></i>Estado
                                </label>
                                <div class="p-3 bg-light rounded">
                                    @php
                                        $badgeClass = [
                                            'finalizada' => 'success',
                                            'en_ejecucion' => 'warning',
                                            'pendiente' => 'secondary'
                                        ][$visita->estado] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }} fs-6">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                        {{ ucfirst($visita->estado) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('info'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Card de Modo Offline -->
            @if(Auth::check() && in_array(Auth::user()->rol, [1,2]))
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-wifi-slash me-2"></i>Modo Offline
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-cloud-download-alt fa-3x text-dark mb-3"></i>
                        <h5>Trabaja sin conexión a internet</h5>
                        <p class="text-muted">Complete los formularios sin necesidad de conexión</p>
                    </div>
                    <!-- En tu archivo Laravel Blade -->
                    <a href="{{ url('/offline/area?visita_id=' . $visita->id) }}" 
                    class="btn btn-dark btn-lg" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    onclick="guardarVisitaOffline({{ json_encode($visita) }})">
                        <i class="fas fa-download me-2"></i>Continuar sin conexión
                    </a>

                    <script>
                    function guardarVisitaOffline(visita) {
                        // Guardar la visita en localStorage temporalmente
                        localStorage.setItem('visita_offline_' + visita.id, JSON.stringify(visita));
                        
                        // También puedes guardar en IndexedDB si quieres
                        if (window.saveFormData) {
                            window.saveFormData('visita', visita);
                        }
                    }
                    </script>
                </div>
            </div>

            <!-- Card de Navegación entre Secciones -->

                @if ($visita->estado !== 'finalizada')
                    @if ($visita->estado === 'pendiente')
                        <!-- Botón para comenzar visita agronómica -->
                        <div class="card shadow mb-4">
                            <div class="card-header bg-success text-white py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-play-circle me-2"></i>Comenzar Visita Agronómica
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fas fa-seedling fa-3x text-success mb-3"></i>
                                    <h5>¿Está listo para comenzar la visita agronómica?</h5>
                                    <p class="text-muted">Al iniciar, la visita cambiará a estado "En Ejecución" y será redirigido a la sección de Área</p>
                                </div>
                                <form action="{{ route('visitas.iniciar_agronomica', $visita->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-play-circle me-2"></i>Comenzar Visita Agronómica
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Selector de secciones para visitas agronómicas en ejecución -->
                        <div class="card shadow mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 text-success">
                                    <i class="fas fa-compass me-2"></i>Navegar entre Secciones Agronómicas
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('redireccion_seccion_agronomica', $visita->id) }}" method="GET">
                                    <div class="mb-3">
                                        <label for="seccion" class="form-label fw-bold text-success">
                                            <i class="fas fa-map-signs me-2"></i>Seleccione una sección:
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <select id="seccion" name="seccion" class="form-select" required
                                                    style="border-radius: 10px 0 0 10px; border: 2px solid #e9ecef;width:100%">
                                                <option value="">Seleccione una sección</option>
                                                <option value="area">📍 Área</option>
                                                <option value="fertilizacion">💧 Fertilización</option>
                                                <option value="polinizacion">🌸 Polinización</option>
                                                <option value="sanidad">🦠 Sanidad</option>
                                                <option value="suelo">🧪 Análisis de Suelo</option>
                                                <option value="labores_cultivo">🚜 Labores de Cultivo</option>
                                                <option value="evaluacion_cosecha">🌴 Evaluación de Cosecha en Campo</option>
                                            </select>
                                            <button type="submit" class="btn btn-success" 
                                                    style="border-radius: 0 10px 10px 0;">
                                                <i class="fas fa-arrow-right me-2"></i>Ir
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                @endif
                               
                            </div>
                        </div>
                    @endif
                @endif
           <div class="text-center mt-3">
                                    <a href="{{ route('visitas.detalle', $visita->id) }}" class="btn btn-info btn-lg">
                                        <i class="fas fa-search me-2"></i>Ver Detalle Completo
                                    </a>
                                </div><br><br>

            <!-- Card de Otras Visitas -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-history me-2"></i>Otras Visitas a esta Plantación
                        @if ($visita->plantacion && $visita->plantacion->visitas->count() > 1)
                            <span class="badge bg-info ms-2">{{ $visita->plantacion->visitas->count() - 1 }}</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if ($visita->plantacion && $visita->plantacion->visitas->count() > 1)
                        <div class="list-group">
                            @foreach ($visita->plantacion->visitas->where('id', '!=', $visita->id) as $otraVisita)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-clipboard-list me-2 text-info"></i>
                                        <strong>Visita #{{ $otraVisita->id }}</strong>
                                        <span class="badge bg-{{ $otraVisita->estado === 'finalizada' ? 'success' : ($otraVisita->estado === 'en_ejecucion' ? 'warning' : 'secondary') }} ms-2">
                                            {{ ucfirst($otraVisita->estado) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('visitas.show', $otraVisita->id) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Ver
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No hay otras visitas registradas para esta plantación.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Agrega esta sección del mapa en tu show de visita agronómica, después de la card de "Otras Visitas" -->

<!-- Card del Mapa Interactivo -->
<div class="card shadow mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-success">
            <i class="fas fa-map-marked-alt me-2"></i>Ubicación en el Mapa
        </h5>
    </div>
    <div class="card-body p-0">
        <div id="map" style="height: 400px; border-radius: 0 0 10px 10px;"></div>
        <div class="p-3 bg-light">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Ubicación geográfica de la plantación visitada. Coordenadas: 
                <span id="coordenadas">{{ $visita->plantacion->geolocalizacion ?? 'No disponible' }}</span>
            </small>
        </div>
    </div>
</div>

<style>
/* Estilos específicos para el mapa */
#map {
    border-radius: 0 0 10px 10px;
}

.leaflet-popup-content {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.leaflet-popup-content h4 {
    color: #2e7d32;
    margin-bottom: 8px;
    font-weight: 600;
}

.leaflet-popup-content p {
    margin: 4px 0;
    font-size: 0.9rem;
}

.leaflet-popup-content .coordenadas {
    background: #f8f9fa;
    padding: 4px 8px;
    border-radius: 4px;
    font-family: monospace;
    font-size: 0.8rem;
}
</style>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar mapa
    const map = L.map('map').setView([4.6097, -74.0817], 6);
    let marker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Obtener coordenadas de la plantación
    const geo = "{{ $visita->plantacion->geolocalizacion ?? '' }}";

    if (geo && geo.includes(',')) {
        const [lat, lon] = geo.split(',').map(coord => parseFloat(coord.trim()));
        
        if (!isNaN(lat) && !isNaN(lon)) {
            // Crear marcador con información detallada
            marker = L.marker([lat, lon]).addTo(map);
            
            // Popup con información de la visita y plantación
            const popupContent = `
                <div style="min-width: 250px;">
                    <h4>📍 Ubicación de la Visita</h4>
                    <p><strong>🌱 Plantación:</strong> {{ $visita->plantacion->nombre ?? 'N/A' }}</p>
                    <p><strong>🏢 Proveedor:</strong> {{ $visita->proveedor->proveedor_nombre }}</p>
                    <p><strong>👨‍💼 Técnico:</strong> {{ $visita->tecnico->name }}</p>
                    <p><strong>📅 Fecha:</strong> {{ $visita->fecha }}</p>
                    <p><strong>📍 Ubicación:</strong> {{ $visita->ubicacion }}</p>
                    <div class="coordenadas mt-2">
                        <strong>Coordenadas:</strong><br>
                        Lat: ${lat.toFixed(6)}<br>
                        Lon: ${lon.toFixed(6)}
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent).openPopup();
            
            // Centrar mapa en la ubicación
            map.setView([lat, lon], 15);
            
            // Agregar círculo para mejor visualización
            L.circle([lat, lon], {
                color: '#2e7d32',
                fillColor: '#4caf50',
                fillOpacity: 0.1,
                radius: 100
            }).addTo(map);
            
        } else {
            mostrarMapaSinUbicacion();
        }
    } else {
        mostrarMapaSinUbicacion();
    }

    function mostrarMapaSinUbicacion() {
        // Mostrar mensaje en el mapa cuando no hay coordenadas
        const bounds = map.getBounds();
        const center = bounds.getCenter();
        
        L.marker([center.lat, center.lng]).addTo(map)
            .bindPopup(`
                <div style="text-align: center;">
                    <h4 style="color: #e74a3b;">⚠️ Ubicación No Disponible</h4>
                    <p>No se encontraron coordenadas geográficas para esta plantación.</p>
                    <small>Actualice la información de la plantación para ver su ubicación en el mapa.</small>
                </div>
            `)
            .openPopup();
            
        // Actualizar texto de coordenadas
        document.getElementById('coordenadas').textContent = 'Coordenadas no disponibles';
        document.getElementById('coordenadas').style.color = '#e74a3b';
    }

    // Control de escala
    L.control.scale({ imperial: false }).addTo(map);

    // Agregar botón de geolocalización del usuario
    const locateControl = L.control.locate({
        position: 'topright',
        drawCircle: true,
        follow: true,
        setView: true,
        keepCurrentZoomLevel: true,
        markerStyle: {
            weight: 1,
            opacity: 0.8,
            fillOpacity: 0.8
        },
        circleStyle: {
            weight: 1,
            opacity: 0.8,
            fillOpacity: 0.8
        },
        icon: 'fas fa-crosshairs',
        metric: true,
        strings: {
            title: "Mostrar mi ubicación",
            popup: "Estás dentro de {distance} {unit} de este punto",
            outsideMapBoundsMsg: "Parece que estás fuera de los límites del mapa"
        },
        locateOptions: {
            maxZoom: 16,
            watch: true,
            enableHighAccuracy: true,
            maximumAge: 10000,
            timeout: 10000
        }
    }).addTo(map);

    // Agregar control de capas
    const baseLayers = {
        "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}/', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }),
        "Satélite": L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: '© Esri'
        })
    };

    L.control.layers(baseLayers).addTo(map);
});
</script>

            <!-- Botón Volver -->
            <div class="text-center mt-3">
                <a href="{{ route('visitas.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
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

.bg-light {
    background-color: #f8f9fa !important;
    border-radius: 10px;
}

.list-group-item {
    border-radius: 8px !important;
    margin-bottom: 5px;
    border: 1px solid #e9ecef !important;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 10px !important;
        width: 115%;
        margin-left: -40px
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
    
    #map {
        height: 300px !important;
    }
}

@media (max-width: 576px) {
    #map {
        height: 250px !important;
    }
    
    .input-group.input-group-lg {
        flex-direction: column;
    }
    
    .input-group.input-group-lg .form-select {
        border-radius: 10px !important;
        margin-bottom: 10px;
    }
    
    .input-group.input-group-lg .btn {
        border-radius: 10px !important;
        width: 100%;
    }
}
</style>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // =========================
    //  🗺️ MAPA SIMPLE LEAFLET
    // =========================
    const geo = "{{ $visita->plantacion->geolocalizacion ?? '' }}";
    const map = L.map('map').setView([4.5709, -74.2973], 6); // Vista inicial: Colombia

    // Capa base OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    if (geo && geo.includes(',')) {
        const [lat, lon] = geo.split(',').map(v => parseFloat(v.trim()));

        if (!isNaN(lat) && !isNaN(lon)) {
            const marker = L.marker([lat, lon]).addTo(map);

            // Popup con información básica
            const popupContent = `
                <div style="min-width: 260px;">
                    <h4>📍 Visita Social</h4>
                    <p><strong>🌱 Plantación:</strong> {{ $visita->plantacion->nombre ?? 'N/A' }}</p>
                    <p><strong>🏢 Proveedor:</strong> {{ $visita->proveedor->proveedor_nombre ?? 'N/A' }}</p>
                    <p><strong>👨‍💼 Técnico:</strong> {{ $visita->tecnico->name ?? 'N/A' }}</p>
                    <p><strong>📅 Fecha:</strong> {{ $visita->fecha }}</p>
                    <div style="font-family: monospace;">
                        <strong>Coordenadas:</strong><br>
                        Lat: ${lat.toFixed(6)}<br>
                        Lon: ${lon.toFixed(6)}
                    </div>
                </div>
            `;
            marker.bindPopup(popupContent).openPopup();

            // Centrar en la ubicación
            map.setView([lat, lon], 15);

            // Círculo decorativo
            L.circle([lat, lon], {
                color: '#2e7d32',
                fillColor: '#4caf50',
                fillOpacity: 0.15,
                radius: 100,
                weight: 1
            }).addTo(map);

            // Mostrar coordenadas en texto
            const coordText = document.getElementById('coordenadas');
            if (coordText) {
                coordText.textContent = `${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                coordText.style.fontFamily = 'Courier New, monospace';
                coordText.style.color = '#2e7d32';
            }

        } else {
            mostrarMapaSinUbicacion();
        }
    } else {
        mostrarMapaSinUbicacion();
    }

    function mostrarMapaSinUbicacion() {
        const center = map.getCenter();
        L.marker(center).addTo(map)
            .bindPopup("<b>⚠️ Ubicación no disponible</b><br>Actualice las coordenadas de la plantación.")
            .openPopup();

        const coordText = document.getElementById('coordenadas');
        if (coordText) {
            coordText.textContent = 'Coordenadas no disponibles';
            coordText.style.color = '#e74a3b';
        }
    }

});
</script>

@endsection
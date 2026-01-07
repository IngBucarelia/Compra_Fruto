@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8"><br><br><br>
            <!-- Header con Estadísticas -->
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-success py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-green">
                                <i class="fas fa-leaf me-2"></i>
                                Detallde de Visita Ambiental
                            </h4>
                            <p class="mb-0 text-green-50 small">Información completa de la visita ambiental y componentes disponibles</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-light text-success fs-6 p-2">
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
                        <i class="fas fa-info-circle me-2"></i>Información de la Visita Ambiental
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
                                    <span class="fw-semibold">{{ $visita->fecha_visita }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-seedling me-2"></i>Plantación
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">{{ $visita->plantacion->nombre ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-industry me-2"></i>Proveedor
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">{{ $visita->proveedor->proveedor_nombre ?? 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-user-tie me-2"></i>Técnico de Campo
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">{{ $visita->tecnico->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-tasks me-2"></i>Tipo de Visita
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">Ambiental</span>
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
                                    <i class="fas fa-clipboard me-2"></i>Observaciones
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">{{ $visita->observaciones ?: 'Sin observaciones' }}</span>
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
            @if(Auth::check() && in_array(Auth::user()->rol, [1,3]))
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-wifi-slash me-2"></i>Modo Offline - Componente Ambiental
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-leaf fa-3x text-success mb-3"></i>
                        <h5>Trabaja sin conexión a internet</h5>
                        <p class="text-muted">Complete los formularios ambientales sin necesidad de conexión</p>
                    </div>
                    <a href="{{ url('/offline/agua-captacion-legal') }}?visita_id={{ $visita->id }}"
                    class="btn btn-dark btn-lg"
                    target="_blank"
                    rel="noopener noreferrer"
                    onclick="guardarVisitaOffline({{ json_encode($visita) }})">
                    <i class="fas fa-download me-2"></i>Continuar sin conexión
                    </a>
                    <script>
                    const proveedorNombre = @json($visita->proveedor->proveedor_nombre ?? 'N/A');
                    function guardarVisitaOffline(visita) {
                        visita.proveedor_nombre = proveedorNombre;
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
             <a href="{{ route('visitas_ambiental.detalle', $visita->id) }}" class="btn btn-info btn-lg">
                                        <i class="fas fa-search me-2"></i>Ver Detalle Completo
                                    </a>
                             
            <!-- Card de Navegación entre Componentes Ambientales -->
            @if ($visita->estado !== 'finalizada')
                @if ($visita->estado === 'pendiente')
                    <!-- Botón para comenzar visita ambiental -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-play-circle me-2"></i>Comenzar Visita Ambiental
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-leaf fa-3x text-success mb-3"></i>
                                <h5>¿Está listo para comenzar la visita ambiental?</h5>
                                <p class="text-muted">Al iniciar, la visita cambiará a estado "En Ejecución" y será redirigido al primer componente</p>
                            </div>
                            <form action="{{ route('visitas_ambiental.iniciar', $visita->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-play-circle me-2"></i>Comenzar Visita Ambiental
                                </button>
                            </form>

                        </div>
                    </div>
                @else
                    <!-- Selector de componentes para visitas ambientales en ejecución -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 text-success">
                                <i class="fas fa-compass me-2"></i>Navegar entre Componentes Ambientales
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('redireccion_componente_ambiental', $visita->id) }}" method="GET">
                                <div class="mb-3">
                                    <label for="componente" class="form-label fw-bold text-success">
                                        <i class="fas fa-map-signs me-2"></i>Seleccione un componente:
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <select id="componente" name="seccion" class="form-select" required
                                                style="border-radius: 10px 0 0 10px; border: 2px solid #e9ecef;">
                                            <option value="">Seleccione un componente</option>
                                            <option value="agua_captacion_legal">💧 Agua - Captación Legal</option>
                                            <option value="agua_uso_eficiente">🚰 Agua - Uso Eficiente</option>
                                            <option value="suelo_conservacion">🌱 Suelo - Conservación</option>
                                            <option value="energia">⚡ Energía</option>
                                            <option value="gobernanza_hidrica">🤝 Gobernanza Hídrica</option>
                                            <option value="emisiones_gei">🏭 Emisiones GEI</option>
                                            <option value="residuos_manejo">🗑️ Residuos - Manejo</option>
                                            <option value="sustancias_manejo">🧪 Sustancias - Manejo</option>
                                            <option value="vertimientos_manejo">💦 Vertimientos - Manejo</option>
                                            <option value="hmp_manejo">☣️ HMP - Manejo</option>
                                            <option value="avc_control">🛡️ AVC - Control</option>
                                            <option value="ecosistema_proteccion">🌳 Ecosistema - Protección</option>
                                            <option value="avc_no_reemplazo">🚫 AVC - No Reemplazo 🌲 No Deforestación</option>
                                           
                                        </select>
                                        <button type="submit" class="btn btn-success" 
                                                style="border-radius: 0 10px 10px 0;">
                                            <i class="fas fa-arrow-right me-2"></i>Ir
                                        </button>
                                    </div>
                                </div>
                            </form>

                            
                        </div>
                    </div>
                @endif
            @endif
            @endif

            <!-- Card de Otras Visitas Ambientales -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-history me-2"></i>Otras Visitas Ambientales a esta Plantación
                        @if ($otrasVisitasAmbientales->count() > 0)
                            <span class="badge bg-success ms-2">{{ $otrasVisitasAmbientales->count() }}</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if ($otrasVisitasAmbientales->count() > 0)
                        <div class="list-group">
                            @foreach ($otrasVisitasAmbientales as $otraVisitaAmbiental)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-leaf me-2 text-success"></i>
                                        <strong>Visita Ambiental #{{ $otraVisitaAmbiental->id }}</strong>
                                        <span class="badge bg-{{ $otraVisitaAmbiental->estado === 'finalizada' ? 'success' : ($otraVisitaAmbiental->estado === 'en_ejecucion' ? 'warning' : 'secondary') }} ms-2">
                                            {{ ucfirst($otraVisitaAmbiental->estado) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('visitasAmbientales.show', $otraVisitaAmbiental->id) }}" 
                                       class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-eye me-1"></i>Ver
                                    </a>
                                </div>
                            @endforeach
                        </div> 
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No hay otras visitas ambientales registradas para esta plantación.</p>
                        </div>
                    @endif
                </div>
            </div>

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

            <!-- Botón Volver -->
            <div class="text-center mt-3">
                <a href="{{ route('visitasAmbientales.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Volver al Listado
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    body{
        background-image: url('{{ asset('images/fondo_ambiental.png') }}'); 
    }
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
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
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

/* Estilos específicos para el mapa */
#map {
    border-radius: 0 0 10px 10px;
}

.leaflet-popup-content {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    min-width: 280px;
}

.leaflet-popup-content h4 {
    color: #2e7d32;
    margin-bottom: 10px;
    font-weight: 600;
    border-bottom: 2px solid #4caf50;
    padding-bottom: 5px;
}

.leaflet-popup-content p {
    margin: 6px 0;
    font-size: 0.9rem;
    line-height: 1.4;
}

.leaflet-popup-content .coordenadas {
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 0.85rem;
    border-left: 4px solid #4caf50;
    margin-top: 10px;
}

.leaflet-popup-content strong {
    color: #2c3e50;
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
    
    #map {
        height: 350px !important;
    }
    
    .leaflet-popup-content {
        min-width: 250px;
    }
}

@media (max-width: 576px) {
    #map {
        height: 300px !important;
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
    
    .leaflet-popup-content {
        min-width: 220px;
        font-size: 0.8rem;
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
                    <h4>🌱 Visita Ambiental</h4>
                    <p><strong>🌿 Plantación:</strong> {{ $visita->plantacion->nombre ?? 'N/A' }}</p>
                    <p><strong>👨‍💼 Técnico:</strong> {{ $visita->tecnico->name ?? 'N/A' }}</p>
                    <p><strong>📅 Fecha:</strong> {{ $visita->fecha_visita }}</p>
                    <p><strong>📍 Ubicación:</strong> {{ $visita->ubicacion }}</p>
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
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
                                <i class="fas fa-seedling me-2"></i>
                                Detalle de Plantación
                            </h4>
                            <p class="mb-0 text-black-50 small">Información completa de la plantación y visitas asociadas</p>
                        </div>
                        <div class="text-end">
                            <div class="badge  text-withe-50 fs-6 p-2" style="background-color: darkgreen">
                                <i class="fas fa-info-circle me-1"></i>
                                ID: #{{ $plantacion->id }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Principal de Información -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-info-circle me-2"></i>Información General
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Columna 1 -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-building me-2"></i>Proveedor
                                </label>
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="avatar-sm bg-info rounded-circle me-3 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-building text-white fs-6"></i>
                                    </div>
                                    <span class="fw-semibold fs-5">{{ $plantacion->proveedor->proveedor_nombre }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-seedling me-2"></i>Nombre de Plantación
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="fw-semibold">🌱 {{ $plantacion->nombre }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-map-marker-alt me-2"></i>Vereda
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $plantacion->vereda }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-city me-2"></i>Municipio
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="text-muted">{{ $plantacion->municipio }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Columna 2 -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-location-dot me-2"></i>Corregimiento
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="text-muted">{{ $plantacion->corregimiento ?: 'No especificado' }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-globe-americas me-2"></i>Departamento
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="text-muted">{{ $plantacion->departamento }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-map-pin me-2"></i>Geolocalización
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <code class="text-info">{{ $plantacion->geolocalizacion }}</code>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">
                                    <i class="fas fa-calendar-alt me-2"></i>Fecha de Creación
                                </label>
                                <div class="p-3 bg-light rounded">
                                    <span class="text-muted">{{ $plantacion->dia_creado }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card de Visitas Asociadas -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-clipboard-list me-2"></i>Visitas Asociadas
                        <span class="badge bg-info ms-2">{{ $plantacion->visitas->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if ($plantacion->visitas->count())
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-info">
                                    <tr>
                                        <th class="ps-4">
                                            <i class="fas fa-hashtag me-2"></i>ID
                                        </th>
                                        <th>
                                            <i class="fas fa-calendar me-2"></i>Fecha
                                        </th>
                                        <th>
                                            <i class="fas fa-tasks me-2"></i>Estado
                                        </th>
                                        <th>
                                            <i class="fas fa-user-tie me-2"></i>Técnico
                                        </th>
                                        <th class="text-center pe-4">
                                            <i class="fas fa-cogs me-2"></i>Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($plantacion->visitas as $visita)
                                        <tr class="align-middle">
                                            <td class="ps-4">
                                                <div class="fw-bold text-info">#{{ $visita->id }}</div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $visita->created_at->format('Y-m-d') }}</span>
                                            </td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $visita->tecnico->name ?? 'No asignado' }}</span>
                                            </td>
                                            <td class="text-center pe-4">
                                                <a href="{{ route('visitas.show', $visita->id) }}" 
                                                   class="btn btn-info btn-sm btn-circle"
                                                   data-bs-toggle="tooltip" title="Ver detalle de visita">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                                <h5>No hay visitas registradas</h5>
                                <p>No se han realizado visitas a esta plantación</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Card del Mapa -->
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-success">
                        <i class="fas fa-map me-2"></i>Ubicación en el Mapa
                    </h5>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 400px; border-radius: 10px;"></div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>Ubicación geográfica de la plantación
                    </small>
                </div>
            </div>

            <!-- Botón Volver -->
            <div class="text-center mt-3" >
                <a href="{{ url()->previous() }}" class="btn btn-outline btn-lg" style="background-color: seagreen;color:#f8f9fa">
                    <i class="fas fa-arrow-left me-2" ></i>Volver al Listado
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

.table th {
    border-top: none;
    font-weight: 600;
    color: #36b9cc;
}

.table-hover tbody tr:hover {
    background-color: rgba(54, 185, 204, 0.05) !important;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.btn-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-circle:hover {
    transform: scale(1.1);
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.bg-light {
    background-color: #f8f9fa !important;
    border-radius: 10px;
}

.btn-outline-secondary {
    border-radius: 10px;
    padding: 12px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
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
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn-circle {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
    
    #map {
        height: 300px !important;
    }
}

@media (max-width: 576px) {
    #map {
        height: 250px !important;
    }
    
    .row .col-md-6 {
        margin-bottom: 1rem;
    }
}
</style>

<!-- Incluir tooltips -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('map').setView([5.0, -72.0], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const geo = "{{ $plantacion->geolocalizacion }}".split(',');
        const lat = parseFloat(geo[0]);
        const lng = parseFloat(geo[1]);

        if (!isNaN(lat) && !isNaN(lng)) {
            const marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup(`
                <strong>🌱 {{ $plantacion->nombre }}</strong><br>
                <strong>Proveedor:</strong> {{ $plantacion->proveedor->proveedor_nombre }}<br>
                <strong>Ubicación:</strong> {{ $plantacion->vereda }}, {{ $plantacion->municipio }}
            `).openPopup();
            map.setView([lat, lng], 14);
        }
    });
</script>
@endsection
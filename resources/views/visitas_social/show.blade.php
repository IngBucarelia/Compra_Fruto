@extends('layouts.app')

@section('content')

<style>
    .container{
        background-color: #e8d5dce0;
        padding: 20px;
        width: 60%;
        align: center !important;
        align-items: center !important;
    }

    .card{
        width: 100%;
    }

    @media (max-width: 768px) {
        .container {
            margin-left: -70px;
            width: 125%;
        }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }

        .card{
            width: 100%;
        }
    }
</style>

<div class="container">
    <!-- Card de modo offline -->
    <div class="col-md-6 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h5>🌐 Modo Offline</h5>
                <p>Trabaja sin conexión a internet</p>
                <a href="{{ url('/offline/area?visita_id=' . $visita->id) }}" 
                   class="btn btn-dark" 
                   target="_blank" 
                   rel="noopener noreferrer">
                    Continuar sin conexión
                </a>
            </div>
        </div>
    </div>

    <!-- Información principal de la visita -->
    <h2 class="mb-4 text-success">📋 Detalle de la Visita Social</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>📅 Fecha:</strong> {{ $visita->fecha }}</p>
            <p><strong>👤 Proveedor:</strong> {{ $visita->proveedor->proveedor_nombre }}</p>
            <p><strong>🧑‍💼 Técnico de campo:</strong> {{ $visita->tecnico->name }}</p>
            <p><strong>📑 Tipo de visita:</strong> {{ $visita->tipo_visita }}</p>
            <p><strong>📍 Ubicación:</strong> {{ $visita->ubicacion }}</p>
            <p><strong>🤝 Recibió la visita:</strong> {{ $visita->recibio_visita }}</p>
            <p><strong>⚡ Estado:</strong> 
                <span class="badge bg-success">{{ ucfirst($visita->estado) }}</span>
            </p>
        </div>
    </div>

    @if(session('info'))
        <div class="alert alert-warning mt-3">{{ session('info') }}</div>
    @endif

    <!-- Botón dinámico según estado -->
    @if ($visita->estado !== 'finalizada')
        <form action="{{ route('redireccion_seccion_social', $visita->id) }}" method="GET" class="mt-4">
            <label for="seccion" class="form-label fw-bold text-success">📋 Ir a sección:</label>
            <div class="input-group">
                <select id="seccion" name="seccion" class="form-select" required>
                    <option value="">Seleccione una sección</option>
                    @if ($visita->estado === 'pendiente' || $visita->estado === 'en_ejecucion')
                        <option value="inicio"> Pagina de Inicio de Visita</option>
                        <option value="datos_personales">👤 Datos Personales</option>
                        <option value="miembros">👨‍👩‍👧‍👦 Miembros del Hogar</option>
                        <option value="predio">🏡 Datos del Predio</option>
                        <option value="fuerza_laboral">🧑‍🌾 Fuerza Laboral</option>
                        <option value="organizacion_social">👥 Organización Social</option>
                        <option value="cierre_visita">✅ Cierre de Visita</option>
                    @endif
                </select>
                <button type="submit" class="btn btn-success">Ir</button>
            </div>
        </form>
    @endif

    <!-- Acciones -->
    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('visitas_social.detalleSocial', $visita->id) }}" class="btn btn-info">🔍 Ver detalle</a>
        <a href="{{ route('visitas_social.indexSocial') }}" class="btn btn-secondary">⬅ Volver al listado</a>
    </div>

    <hr>

    <!-- Otras visitas -->
    <h4 class="mt-4 text-success">📘 Otras visitas sociales a esta plantación</h4>
    @if ($otrasVisitasSociales->count() > 0)
        <div class="card">
            <ul class="list-group list-group-flush">
                @foreach ($otrasVisitasSociales as $otraVisitaSocial)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            📝 Visita Social #{{ $otraVisitaSocial->id }} - Estado: 
                            <strong>{{ ucfirst($otraVisitaSocial->estado) }}</strong>
                        </span>
                        <a href="{{ route('visitas_social.showSocial', $otraVisitaSocial->id) }}" class="btn btn-sm btn-outline-primary">
                            Ver detalle
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <p class="text-muted">No hay otras visitas sociales registradas para esta plantación.</p>
    @endif

    <!-- Mapa -->
    <h4 class="mt-4 text-success">📍 Ubicación exacta en el mapa</h4>
    <div id="map" style="height: 400px;" class="mb-4 border rounded shadow-sm"></div>
</div>

<!-- Leaflet CSS/JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const geo = "{{ $visita->plantacion->geolocalizacion ?? '' }}";

        if (!geo || !geo.includes(',')) {
            alert("No hay geolocalización disponible para esta visita.");
            return;
        }

        const [lat, lon] = geo.split(',').map(coord => parseFloat(coord.trim()));

        const map = L.map('map').setView([lat, lon], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        L.marker([lat, lon]).addTo(map)
            .bindPopup("📍 Ubicación registrada")
            .openPopup();
    });
</script>
@endsection

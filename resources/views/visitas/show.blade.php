@extends('layouts.app')

@section('content')

<style>

    .container{
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
        width: 60%;
        align: center !important;
        align-items: center !
    }

    .card{
        width: 200%;
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
    <div class="container" >
        <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h5>Modo Offline</h5>
                            <p>Trabaja sin conexión a internet</p>
                           <a href="{{ url('/offline/area?visita_id=' . $visita->id) }}" 
                               class="btn btn-dark" 
                               target="_blank" 
                               rel="noopener noreferrer">
                                Continuar sin conexión
                            </a>



                        </div>
                    </div>
        <h2 class="mb-4">Detalle ded Visita</h2>

        <div class="card">
            <div class="card-body"> 
                <p><strong>Fecha:</strong> {{ $visita->fecha }}</p>
                <p><strong>Proveedor:</strong> {{ $visita->proveedor->proveedor_nombre }}</p>
                <p><strong>Técnico de campo:</strong> {{ $visita->tecnico->name }}</p>
                <p><strong>Tipo de visita:</strong> {{ $visita->tipo_visita }}</p>
                <p><strong>Ubicación:</strong> {{ $visita->ubicacion }}</p>
                <p><strong>Recibió la visita:</strong> {{ $visita->recibio_visita }}</p>
                <p><strong>Estado de visita:</strong> {{ $visita->estado }}</p>
            </div>
        </div>
        @if(session('info'))
        <div class="alert alert-warning">
            {{ session('info') }} 
        </div>
    @endif
    <!-- Botón dinámico según el estado de la visita -->
       @if ($visita->estado !== 'finalizada')
            <form id="formRedireccion" class="mt-4">
                <label for="seccion" class="form-label">📋 Ir a sección:</label>
                <div class="input-group">
                    <select id="seccion" class="form-select" required>
                        <option value="">Seleccione una sección</option>

                        @if ($visita->estado === 'pendiente' || $visita->estado === 'en_ejecucion')
                            <option value="{{ route('areas.create', ['visita_id' => $visita->id]) }}">📍 Área</option>
                            <option value="{{ route('fertilizaciones.create', ['visita_id' => $visita->id]) }}">💧 Fertilización</option>
                            <option value="{{ route('polinizaciones.create', ['visita_id' => $visita->id]) }}">🌸 Polinización</option>
                            <option value="{{ route('sanidades.create', ['visita_id' => $visita->id]) }}">🦠 Sanidad</option>
                            <option value="{{ route('suelos.create', ['visita_id' => $visita->id]) }}">🧪 Análisis de Suelo</option>
                            <option value="{{ route('labores_cultivo.create', ['visita_id' => $visita->id]) }}">🚜 Labores de Cultivo</option>
                            <option value="{{ route('evaluacion.create', ['visita_id' => $visita->id]) }}">🌴 Evaluación de Cosecha en Campo</option>
                        @endif
                    </select>
                    <button type="submit" class="btn btn-primary">Ir</button>
                </div>
            </form>

            <script>
                document.getElementById('formRedireccion').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const url = document.getElementById('seccion').value;
                    if (url) window.location.href = url;
                });
            </script>
        @endif


        <a href="{{ route('visitas.detalle', $visita->id) }}" class="btn btn-info btn-sm">🔍 Ver detalle</a>


        <a href="{{ route('visitas.index') }}" class="btn btn-secondary mt-3">Volver al listado</a>
        <hr>
        <h4 class="mt-4">📘 Otras visitas a esta plantación</h4>

        @if ($visita->plantacion && $visita->plantacion->visitas->count() > 1)
            <div class="card"><ul class="list-group">
                @foreach ($visita->plantacion->visitas->where('id', '!=', $visita->id) as $otraVisita)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            📝 Visita #{{ $otraVisita->id }} - Estado: <strong>{{ ucfirst($otraVisita->estado) }}</strong>
                        </span>
                        <a href="{{ route('visitas.show', $otraVisita->id) }}" class="btn btn-sm btn-outline-primary">
                            Ver detalle
                        </a>
                    </li>
                @endforeach
            </ul>
            </div>
        @else
            <p class="text-muted">No hay otras visitas registradas para esta plantación.</p>
        @endif

    <h4 class="mt-4">Ubicación exacta en el mapa</h4>
<div id="map" style="height: 400px;" class="mb-4 border rounded shadow-sm"></div>

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
            .bindPopup("Ubicación registrada")
            .openPopup();
    });
</script>


</div>
@endsection

@extends('layouts.app')

@section('content')

<style>
    /* Estilos generales del contenedor y título */
    .container {
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
        border-radius: 8px; /* Añadido para consistencia */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Añadido para consistencia */
        max-width: 900px !important; /* Limita el ancho en pantallas muy grandes */

        margin-top: 25px; /* Margen superior para separación */
    }

    .title {
        text-align: center;
        font-family: Arial Black;
        font-weight: bold;
        font-size: 30px;
        color: #fdffe5;
        text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
        margin-bottom: 25px;
    }

    .info-visita span {
        color: wheat;
    }

    /* Estilos para los acordeones de información previa */
    .accordion-item .accordion-button {
        background-color: darkseagreen !important;
        color: aliceblue !important;
        font-weight: bold;
    }
    .accordion-item .accordion-body {
        background-color: rgb(209, 241, 209) !important;
        color: rgb(31, 32, 34);
    }
    .area-info-card, .fertilizacion-info-card, .polinizacion-info-card, .sanidad-info-card {
        background-color: #f0fdf0;
        border: 1px solid #d4edda;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .area-info-card ul, .fertilizacion-info-card ul, .polinizacion-info-card ul, .sanidad-info-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .area-info-card li, .fertilizacion-info-card li, .polinizacion-info-card li, .sanidad-info-card li {
        padding: 5px 0;
        border-bottom: 1px dashed #e2e6ea;
    }
    .area-info-card li:last-child, .fertilizacion-info-card li:last-child, .polinizacion-info-card li:last-child, .sanidad-info-card li:last-child {
        border-bottom: none;
    }

    /* Estilos para botones al final del formulario */
    .button-group {
        display: flex;
        flex-direction: column; /* Apila los botones en móvil */
        gap: 15px; /* Espacio entre botones */
        margin-top: 30px;
    }

    /* Media Queries para Responsividad (móviles) */
    @media (max-width: 968px) {

         .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
    }
        .button-group-top {
            flex-direction: row;
            justify-content: flex-start;
        }

         .container.offline-form-container {
        padding: 15px;
            margin-top: 15px;
            border-radius: 0;
            box-shadow: none;
            width: 123%;
            max-width: none;
            margin-left: -60px !important;
    }

    .title{
    text-align: center;
    font-family: Arial Black;
    font-weight: bold;
    font-size: 30px;
    color: #fdffe5;
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    margin-bottom: 25px;
}

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
    <h3 class="title">🧪Información previa de plantación - Análisis de Suelo y Foliar 🧬</h3><h3> <br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span><br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
    <span style="color: wheat">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span>
</h3>
    <form id="formRedireccion" class="mt-4">
    <p> <strong>Seleccione la Zona a Dirigirse</strong></p>
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
                    <option value="{{ route('evaluacion.create', ['visita_id' => $visita->id]) }}">🌴 Evaluación de Cosecha</option>
                    <option value="{{ route('cierre-visitas.create', ['visita_id' => $visita->id]) }}">🔏 Cierre de Visita</option>
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
    </script><br><br>

    {{-- Acordeones con formularios anteriores --}}
    <div class="accordion mb-4" id="acordeonSuelo">

        {{-- Área --}}
         <div class="accordion mb-4" id="accordionArea">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingArea">
                    <button style="background-color: darkseagreen !important; color: aliceblue" 
                            class="accordion-button" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseArea" 
                            aria-controls="collapseArea">
                        📍 Información del Área(s)
                    </button>
                </h2>
                <div id="collapseArea" class="accordion-collapse collapse show" 
                    aria-labelledby="headingArea" data-bs-parent="#accordionArea">
                    <div class="accordion-body" style="background-color: rgb(209, 241, 209) !important; color: rgb(31, 32, 34)">
                        
                        @if ($visita->areas->count() > 0)
                            @php
                                $primerArea = $visita->areas->first();
                                $areaTotal = $primerArea->area_total_finca_hectareas ?? 0;
                                $palmasTotal = $primerArea->numero_palmas_total_finca ?? 0;
                                $ciclosCosecha = $primerArea->ciclos_cosecha ?? 0;
                                $produccionTotal = $primerArea->produccion_toneladas_por_mes ?? 0;
                                
                                // Calcular desglose de palmas
                                $totalDesarrollo = $visita->areas->sum('numero_palmas_desarrollo');
                                $totalProduccion = $visita->areas->sum('numero_palmas_produccion');
                                $totalOrdenPlantis = $visita->areas->where('aplica_orden_plantis', true)->sum('numero_plantas_orden_plantis');
                            @endphp
                            
                            <!-- ⭐⭐ INFORMACIÓN GENERAL DE LA FINCA ⭐⭐ -->
                            <div class="mb-4">
                                <div class="card shadow-sm border-success">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-chart-bar me-2"></i>
                                            Información General de la Finca
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-box-general mb-3">
                                                    <label class="fw-bold">
                                                        <i class="fas fa-ruler-combined me-2"></i>
                                                        Área Total Finca:
                                                    </label>
                                                    <div class="info-value">
                                                        {{ number_format($areaTotal, 2) }} Ha
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-box-general mb-3">
                                                    <label class="fw-bold">
                                                        <i class="fas fa-tree me-2"></i>
                                                        Total de Palmas:
                                                    </label>
                                                    <div class="info-value">
                                                        {{ number_format($palmasTotal, 0) }}
                                                        <small class="text-muted ms-2">(incluye Orden Plantis)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-box-general mb-3">
                                                    <label class="fw-bold">
                                                        <i class="fas fa-sync-alt me-2"></i>
                                                        Ciclos de Cosecha:
                                                    </label>
                                                    <div class="info-value">
                                                        {{ $ciclosCosecha }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-box-general mb-3">
                                                    <label class="fw-bold">
                                                        <i class="fas fa-weight-hanging me-2"></i>
                                                        Producción Total:
                                                    </label>
                                                    <div class="info-value">
                                                        {{ number_format($produccionTotal, 2) }} Ton/Mes
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Desglose del total de palmas -->
                                        <div class="row mt-3 bg-light p-3 rounded">
                                            <div class="col-12">
                                                <h6 class="mb-2">
                                                    <i class="fas fa-list-ol me-2"></i>
                                                    Desglose del Total de Palmas
                                                </h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <small class="d-block">
                                                            <span class="badge bg-info me-2">🌱</span>
                                                            <strong>Desarrollo:</strong> {{ number_format($totalDesarrollo, 0) }}
                                                        </small>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="d-block">
                                                            <span class="badge bg-success me-2">🌳</span>
                                                            <strong>Producción:</strong> {{ number_format($totalProduccion, 0) }}
                                                        </small>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="d-block">
                                                            <span class="badge bg-warning me-2">📋</span>
                                                            <strong>Orden Plantis:</strong> {{ number_format($totalOrdenPlantis, 0) }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ⭐⭐ ÁREAS INDIVIDUALES ⭐⭐ -->
                            <div>
                                <h5 class="mb-3 mt-4">
                                    <i class="fas fa-map-marked-alt me-2"></i>
                                    Áreas Individuales ({{ $visita->areas->count() }})
                                </h5>
                                
                                <div class="row">
                                    @foreach ($visita->areas as $index => $area)
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100 shadow-sm border-primary">
                                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">
                                                            <i class="fas fa-seedling me-2"></i>
                                                            Área #{{ $index + 1 }}
                                                        </h6>
                                                        <small class="opacity-75">
                                                            ID: {{ substr($area->id, 0, 8) }}
                                                        </small>
                                                    </div>
                                                    <span class="badge {{ $area->estado === 'produccion' ? 'bg-success' : 'bg-info' }}">
                                                        {{ $area->estado === 'produccion' ? 'Producción' : 'Desarrollo' }}
                                                    </span>
                                                </div>
                                                
                                                <div class="card-body">
                                                    <!-- Información básica -->
                                                    <div class="row mb-2">
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Variedad</small>
                                                            <strong>{{ $area->variedad ?? 'N/A' }}</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Material</small>
                                                            <strong>{{ $area->material ?? 'N/A' }}</strong>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <small class="text-muted d-block">Año de Siembra</small>
                                                            <strong>{{ $area->anio_siembra ?? 'N/A' }}</strong>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Información específica según estado -->
                                                    @if($area->estado === 'desarrollo')
                                                        <div class="bg-info bg-opacity-10 p-2 rounded mb-2">
                                                            <h6 class="text-info mb-2">
                                                                <i class="fas fa-seedling me-2"></i>
                                                                Información de Desarrollo
                                                            </h6>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <small class="text-muted d-block">Área (Ha)</small>
                                                                    <strong>{{ number_format($area->area_palmas_desarrollo_hectareas ?? 0, 2) }}</strong>
                                                                </div>
                                                                <div class="col-6">
                                                                    <small class="text-muted d-block">N° Palmas</small>
                                                                    <strong>{{ number_format($area->numero_palmas_desarrollo ?? 0, 0) }}</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($area->estado === 'produccion')
                                                        <div class="bg-success bg-opacity-10 p-2 rounded mb-2">
                                                            <h6 class="text-success mb-2">
                                                                <i class="fas fa-tree me-2"></i>
                                                                Información de Producción
                                                            </h6>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <small class="text-muted d-block">Área (Ha)</small>
                                                                    <strong>{{ number_format($area->area_palmas_produccion_hectareas ?? 0, 2) }}</strong>
                                                                </div>
                                                                <div class="col-6">
                                                                    <small class="text-muted d-block">N° Palmas</small>
                                                                    <strong>{{ number_format($area->numero_palmas_produccion ?? 0, 0) }}</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Información de Orden Plantis -->
                                                    @if($area->aplica_orden_plantis)
                                                        <div class="bg-warning bg-opacity-10 p-2 rounded">
                                                            <h6 class="text-warning mb-2">
                                                                <i class="fas fa-clipboard-check me-2"></i>
                                                                Orden Plantis
                                                            </h6>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <small class="text-muted d-block">N° Orden</small>
                                                                    <strong>{{ $area->orden_plantis_numero ?? 'N/A' }}</strong>
                                                                </div>
                                                                <div class="col-6">
                                                                    <small class="text-muted d-block">N° Plantas</small>
                                                                    <strong>{{ number_format($area->numero_plantas_orden_plantis ?? 0, 0) }}</strong>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-1">
                                                                <div class="col-12">
                                                                    <small class="text-muted d-block">Estado</small>
                                                                    <span class="badge {{ $area->estado_oren_plantis === 'produccion' ? 'bg-success' : 'bg-info' }}">
                                                                        {{ $area->estado_oren_plantis === 'produccion' ? 'Producción' : 'Desarrollo' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="text-center mt-2">
                                                            <small class="text-muted">
                                                                <i class="fas fa-times-circle me-1"></i>
                                                                No aplica Orden Plantis
                                                            </small>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $area->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                    <a href="{{ route('areas.edit', $area->id) }}" 
                                                    class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit me-1"></i> Editar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                        @else
                            <p class="text-muted">No se ha registrado área para esta visita.</p>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>

        {{-- Fertilizaciones --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFert">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFert">
                    💧 Fertilizaciones registradas
                </button>
            </h2>
            <div id="collapseFert" class="accordion-collapse collapse" data-bs-parent="#acordeonSanidad">
                <div class="accordion-body">
                    @if ($visita->fertilizaciones->count())
                        @foreach ($visita->fertilizaciones as $fertilizacion)
                            <div class="fertilizacion-info-card mb-3">
                                <strong>Fecha General:</strong> {{ $fertilizacion->fecha_fertilizacion }}
                                <ul class="list-group mt-2">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Fertilizante</th>
                                                    <th>Cantidad (kg)</th>
                                                    <th>Fecha de Aplicación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($fertilizacion->fertilizantes as $fertilizante)
                                                    <tr>
                                                        <td>{{ ucfirst($fertilizante->fertilizante) }}</td>
                                                        <td class="text-right">{{ number_format($fertilizante->cantidad, 2) }}</td>
                                                        <td>{{ $fertilizante->fecha_aplicacion ? \Carbon\Carbon::parse($fertilizante->fecha_aplicacion)->format('d/m/Y') : 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </ul>
                                <div class="d-flex justify-content-end mt-2">
                                    <a href="{{ route('fertilizaciones.edit', $fertilizacion->id) }}" class="btn btn-warning btn-sm">✏️ Editar esta fertilización</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay fertilizaciones registradas.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Polinizaciones --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPol">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePol">
                    🌸 Polinizaciones registradas
                </button>
            </h2>
            <div id="collapsePol" class="accordion-collapse collapse" data-bs-parent="#acordeonSanidad">
                <div class="accordion-body">
                    @if ($visita->polinizaciones->count())
                        <ul class="list-group">
                            @foreach ($visita->polinizaciones as $poli)
                                <li class="list-group-item polinizacion-info-card">
                                   <div class="table-responsive my-3">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Pases</th>
                                                <th>Ciclos</th>
                                                <th>ANA</th>
                                                <th>Cantidad ANA</th>
                                                <th>Talco</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($poli->fecha)->format('d/m/Y') }}</td>
                                                <td class="text-center">{{ $poli->n_pases }}</td>
                                                <td class="text-center">{{ $poli->ciclos_ronda }}</td>
                                                <td>{{ $poli->nombre_ana }}</td>
                                                <td>{{ $poli->ana }} ({{ $poli->tipo_ana }})</td>
                                                <td class="text-center">{{ $poli->talco }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('polinizaciones.edit', $poli->id) }}" 
                                                    class="btn btn-warning btn-sm">
                                                    ✏️ Editar
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No hay polinizaciones registradas.</p>
                    @endif
                </div>
            </div>
        </div>

       {{-- Sanidad --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSanidad">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSanidad">
                    🧪 Sanidades registradas
                </button>
            </h2>
            <div id="collapseSanidad" class="accordion-collapse collapse" data-bs-parent="#acordeonSuelo">
                <div class="accordion-body">
                    @if ($visita->sanidades->count() > 0)
                        @foreach ($visita->sanidades as $sanidad)
                            <div class="sanidad-info-card mb-3 p-3 border rounded shadow-sm">
                                <h5>Sanidad #{{ $loop->index + 1 }}</h5>
                                <ul class="list-unstyled">
                                    
                                    {{-- Enfermedades (nuevas relaciones) --}}
                                    @if ($sanidad->enfermedades && $sanidad->enfermedades->count())
                                        <li>
                                            <strong>Enfermedades:</strong>
                                            <ul class="mb-2" style="list-style: none; padding-left: 0;">
                                                @foreach ($sanidad->enfermedades as $enf)
                                                    <li>
                                                        {{ $enf->nombre_enfermedad }} - 
                                                        <strong>Estado:</strong> {{ $enf->estado ?? '-' }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif

                                    {{-- Plagas (nuevas relaciones) --}}
                                    @if ($sanidad->plagas && $sanidad->plagas->count())
                                        <li>
                                            <strong>Plagas:</strong>
                                            <ul class="mb-2" style="list-style: none; padding-left: 0;">
                                                @foreach ($sanidad->plagas as $pla)
                                                    <li>
                                                        {{ $pla->nombre_plaga }} - 
                                                        <strong>Estado:</strong> {{ $pla->estado ?? '-' }}- <strong>Tipo Larva:</strong> {{ $pla->instar ?? '-' }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif

                                    {{-- Enfermedades (legacy) --}}
                                    @php
                                        $legacyEnfermedades = [
                                            'opsophanes' => 'Opsophanes',
                                            'pudricion_cogollo' => 'Pudrición del cogollo',
                                            'raspador' => 'Raspador',
                                            'palmarum' => 'Palmarum',
                                            'strategus' => 'Strategus',
                                            'leptopharsa' => 'Leptopharsa',
                                            'pestalotiopsis' => 'Pestalotiopsis',
                                            'pudricion_basal' => 'Pudrición basal',
                                            'pudricion_estipe' => 'Pudrición estipe',
                                        ];
                                    @endphp
                                    @if (collect($legacyEnfermedades)->some(fn($_, $key) => $sanidad->$key))
                                        <li>
                                            <strong>Enfermedades (legacy):</strong>
                                            <ul class="mb-2" style="list-style: none; padding-left: 0;">
                                                @foreach ($legacyEnfermedades as $field => $label)
                                                    @if ($sanidad->$field)
                                                        <li>{{ $label }}: {{ $sanidad->$field }}%</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif

                                    {{-- Plagas (legacy) --}}
                                    @if ($sanidad->plaga)
                                        <li>
                                            <strong>Plaga (legacy):</strong> 
                                            {{ $sanidad->plaga }} 
                                            @if($sanidad->estado_plaga) - ({{ $sanidad->estado_plaga }}) @endif
                                        </li>
                                    @endif

                                    {{-- Otros datos --}}
                                    <li><strong>Otros:</strong> {{ $sanidad->otros ?? '-' }}</li>
                                    <li><strong>Observaciones:</strong> {{ $sanidad->observaciones ?? 'Sin observaciones' }}</li>
                                    <li><strong>Censo de enfermedades:</strong> {{ $sanidad->censo_enfermedades ? 'Sí' : 'No' }}</li>
                                    <li><strong>Ciclos lectura enfermedades:</strong> {{ $sanidad->ciclos_lectura_enfermedades ?? '-' }}</li>
                                    <li><strong>Ciclos lectura plagas:</strong> {{ $sanidad->ciclos_lectura_plagas ?? '-' }}</li>
                                </ul>

                                {{-- Mostrar información de trampas si existen --}}
                                @if ($sanidad->trampas->count() > 0)
                                    <h6 class="mt-4">Datos de Trampas de Palmarum</h6>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($sanidad->trampas as $trampa)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>Ciclos: {{ $trampa->ciclos ?? '-' }}</span>
                                                <span>Machos capturados: {{ $trampa->machos_capturados ?? '-' }}</span>
                                                <span>Hembras capturadas: {{ $trampa->hembras_capturadas ?? '-' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted mt-2">No se registraron trampas de Palmarum para esta sanidad.</p>
                                @endif

                                <div class="d-flex justify-content-end mt-2">
                                    <a href="{{ route('sanidades.edit', $sanidad->id) }}" class="btn btn-warning btn-sm">✏️ Editar esta sanidad</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay datos de sanidad registrados.</p>
                    @endif
                </div>
            </div>
        </div>

    <h3 class="title">🧬Formulario de Análisis de Suelo y Foliar </h3>

            @if ($visita->suelo) {{-- ✅ Se mantiene $visita->suelo (singular) si solo hay un registro de suelo por visita --}}
            {{-- Mostrar detalles del suelo con botón para editar --}}
            <div class="card mb-4">
                <div class="card-header">🧾 Análisis de Suelo Registrado</div>
                <div class="card-body">
                    <p><strong>Análisis foliar:</strong> {{ ucfirst($visita->suelo->analisis_foliar) }}</p>
                    <p><strong>Análisis suelo:</strong> {{ ucfirst($visita->suelo->alanisis_suelo) }}</p>
                    <p><strong>Tipo de suelo:</strong> {{ ucfirst($visita->suelo->tipo_suelo) }}</p>
                    <a href="{{ route('suelos.edit', $visita->suelo->id) }}" class="btn btn-warning">✏️ Editar análisis</a>
                    <form method="POST" action="{{ route('suelos.destroy', $visita->suelo->id) }}" class="d-inline" onsubmit="return confirm('¿Deseas eliminar este análisis de suelo?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">🗑️ Eliminar análisis</button>
                    </form>
                </div>
            </div>
        @else
            {{-- Mostrar formulario si no hay registro --}}
            <form method="POST" action="{{ route('suelos.store') }}">
                @csrf
                <input type="hidden" name="visita_id" value="{{ $visita->id }}">

                <div class="mb-3">
                    <label>¿Análisis foliar?</label>
                    <select name="analisis_foliar" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>¿Análisis de suelo?</label>
                    <select name="analisis_suelo" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tipo de suelo</label>
                    <select id="tipo_suelo_select" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="arenoso">Arenoso</option>
                        <option value="arcilloso">Arcilloso</option>
                        <option value="limoso">Limoso</option>
                        <option value="franco">Franco</option>
                        <option value="Franco-Arenoso">Franco-Arenoso</option>
                        <option value="Franco-Arcilloso">Franco-Arcilloso</option>
                        <option value="otro">Otro</option>
                    </select>

                    {{-- Campo que realmente se enviará al backend --}}
                    <input type="hidden" name="tipo_suelo" id="tipo_suelo_real">

                    {{-- Input visible cuando escogen "otro" --}}
                    <input
                        type="text"
                        id="tipo_suelo_otro"
                        class="form-control mt-2"
                        placeholder="Especifique el tipo de suelo"
                        style="display:none;"
                    >
                </div>


                <button type="submit" class="btn btn-primary">💾 Guardar análisis de suelo</button>
            </form>
        @endif
           <button> <a href="{{ route('labores_cultivo.create', ['visita_id' => $visita->id]) }}" >
                ➡️ Continuar con Labores de Cultivo
            </a> </button>         
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const select = document.getElementById('tipo_suelo_select');
    const inputOtro = document.getElementById('tipo_suelo_otro');
    const real = document.getElementById('tipo_suelo_real');

    select.addEventListener('change', function () {

        if (this.value === 'otro') {
            inputOtro.style.display = 'block';
            real.value = ''; // limpia campo real hasta que escriba
        } else {
            inputOtro.style.display = 'none';
            real.value = this.value; // guarda valor normal
        }
    });

    inputOtro.addEventListener('input', function () {
        real.value = this.value; // lo que escriban se guarda en el mismo campo
    });
});
</script>

@endsection

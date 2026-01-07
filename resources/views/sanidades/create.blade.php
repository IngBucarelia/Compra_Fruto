@extends('layouts.app')

@section('content')
<style>
    /* Estilos generales del contenedor y título */
    .container {
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
        border-radius: 8px; /* Añadido para consistencia */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Añadido para consistencia */
        max-width: 800px; /* Limita el ancho en pantallas muy grandes */
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
    .area-info-card, .fertilizacion-info-card, .polinizacion-info-card {
        background-color: #f0fdf0;
        border: 1px solid #d4edda;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .area-info-card ul, .fertilizacion-info-card ul, .polinizacion-info-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .area-info-card li, .fertilizacion-info-card li, .polinizacion-info-card li {
        padding: 5px 0;
        border-bottom: 1px dashed #e2e6ea;
    }
    .area-info-card li:last-child, .fertilizacion-info-card li:last-child, .polinizacion-info-card li:last-child {
        border-bottom: none;
    }

    /* Estilos para el formulario de sanidad dinámico */
    .enfermedad-group {
        border: 1px solid #c3e6cb;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        background-color: #f8fdf8;
        position: relative; /* Para el botón de eliminar */
    }
    .enfermedad-group .remove-enfermedad-btn {
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        font-size: 0.9em;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        float: right;
        margin-top: -10px;
        margin-right: -10px;
    }
    .enfermedad-group .remove-enfermedad-btn:hover {
        background-color: #c82333;
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
<div class="container">
    <h3 class="title">🧪 Información previa de plantación - Sanidad 🦠</h3>
    <h3>
        <br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span> <br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
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
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Acordeón con información anterior --}}
    <div class="accordion mb-4" id="acordeonSanidad">

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

    </div>
    <h3 class="my-4 text-center">🧪 Registro de Sanidad - {{ $visita->proveedor->proveedor_nombre }}</h3>

   {{-- Formulario SANIDAD --}}
<form id="sanidadForm" method="POST" action="{{ route('sanidades.store') }}">
    @csrf
    <input type="hidden" name="visita_id" value="{{ $visita->id }}">

    {{-- Hidden inputs para compatibilidad con los campos antiguos en la BD --}}
    <input type="hidden" name="opsophanes" id="opsophanes_hidden" value="{{ old('opsophanes', $visita->sanidades->first()->opsophanes ?? '') }}">
    <input type="hidden" name="pudricion_cogollo" id="pudricion_cogollo_hidden" value="{{ old('pudricion_cogollo', $visita->sanidades->first()->pudricion_cogollo ?? '') }}">
    <input type="hidden" name="raspador" id="raspador_hidden" value="{{ old('raspador', $visita->sanidades->first()->raspador ?? '') }}">
    <input type="hidden" name="palmarum" id="palmarum_hidden" value="{{ old('palmarum', $visita->sanidades->first()->palmarum ?? '') }}">
    <input type="hidden" name="strategus" id="strategus_hidden" value="{{ old('strategus', $visita->sanidades->first()->strategus ?? '') }}">
    <input type="hidden" name="leptopharsa" id="leptopharsa_hidden" value="{{ old('leptopharsa', $visita->sanidades->first()->leptopharsa ?? '') }}">
    <input type="hidden" name="pestalotiopsis" id="pestalotiopsis_hidden" value="{{ old('pestalotiopsis', $visita->sanidades->first()->pestalotiopsis ?? '') }}">
    <input type="hidden" name="pudricion_basal" id="pudricion_basal_hidden" value="{{ old('pudricion_basal', $visita->sanidades->first()->pudricion_basal ?? '') }}">
    <input type="hidden" name="pudricion_estipe" id="pudricion_estipe_hidden" value="{{ old('pudricion_estipe', $visita->sanidades->first()->pudricion_estipe ?? '') }}">

    {{-- Hidden legacy para PLAGA (retrocompatibilidad con store actual) --}}
    <input type="hidden" name="plaga" id="plaga_hidden" value="{{ old('plaga', $visita->sanidades->first()->plaga ?? '') }}">
    <input type="hidden" name="estado_plaga" id="estado_plaga_hidden" value="{{ old('estado_plaga', $visita->sanidades->first()->estado_plaga ?? '') }}">

    {{-- Sección de Campos Dinámicos para Enfermedades y Plagas --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Enfermedades y Plagas</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6 form-check">
                    <input type="checkbox" class="form-check-input" id="censo_enfermedades_check" name="censo_enfermedades" value="1"
                        {{ old('censo_enfermedades', $visita->sanidades->first()->censo_enfermedades ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="censo_enfermedades_check">¿Se realizó censo de enfermedades?</label>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label for="ciclos_lectura_enfermedades">Ciclos de Lectura (Enfermedades):</label>
                    <input type="text" name="ciclos_lectura_enfermedades" id="ciclos_lectura_enfermedades" class="form-control"
                        value="{{ old('ciclos_lectura_enfermedades', $visita->sanidades->first()->ciclos_lectura_enfermedades ?? '') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="ciclos_lectura_plagas">Ciclos de Lectura (Plagas):</label>
                    <input type="text" name="ciclos_lectura_plagas" id="ciclos_lectura_plagas" class="form-control"
                        value="{{ old('ciclos_lectura_plagas', $visita->sanidades->first()->ciclos_lectura_plagas ?? '') }}">
                </div>
            </div>

            <hr>

            {{-- Registro dinámico de Enfermedades --}}
            <h6 class="mb-3">Registro de Enfermedades</h6>
            <div id="enfermedades-container">
                {{-- Aquí se añadirán filas dinámicas (select enfermedad + % ) --}}
            </div>
            <button type="button" class="btn btn-sm btn-info mb-3" onclick="addEnfermedad()">+ Añadir enfermedad</button>

            <hr>

            {{-- Registro dinámico de Plagas --}}
            <h6 class="mb-3">Registro de Plagas</h6>
            <div id="plagas-container">
                {{-- Aquí se añadirán filas dinámicas (select plaga + estado) --}}
            </div>
            <button type="button" class="btn btn-sm btn-info mb-3" onclick="addPlaga()">+ Añadir plaga</button>
        </div>
    </div>

    {{-- Sección de Trampas R. palmarum --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Registro de Trampas para R. palmarum</h5>
        </div>
        <div class="card-body">
            <div id="trampas-container">
                {{-- Los campos de trampas se añadirán aquí mediante JavaScript --}}
            </div>
            <button type="button" class="btn btn-sm btn-info" onclick="addTrampa()">+ Añadir Trampa</button>
        </div>
    </div>

    {{-- Sección de Otros y Observaciones --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Otros y Observaciones</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Otros (descripción):</label>
                    <input type="text" name="otros" class="form-control" value="{{ old('otros', $visita->sanidades->first()->otros ?? '') }}">
                </div>
                <div class="col-12 mb-3">
                    <label>Observaciones:</label>
                    <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $visita->sanidades->first()->observaciones ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="button-group text-center">
        <button type="submit" class="btn btn-primary me-2">💾 Guardar sanidad</button>
        <a href="{{ route('suelos.create', ['visita_id' => $visita->id]) }}" class="btn btn-success me-2">
            ➡️ Continuar con Análisis de Suelo
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<hr class="my-5">

{{-- Sección para mostrar Sanidades registradas --}}
@if ($visita->sanidades->count())
    <div class="container mt-5">
        <h4 class="title text-center">🦠 Sanidades registradas</h4>
        <ul class="list-group">
            @foreach ($visita->sanidades as $sanidad)
                <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap">
                    <div>
                        {{-- Datos generales --}}
                        <strong>Censo de enfermedades:</strong> {{ $sanidad->censo_enfermedades ? 'Sí' : 'No' }}<br>
                        <strong>Ciclos de lectura (Enfermedades):</strong> {{ $sanidad->ciclos_lectura_enfermedades ?? '-' }}<br>
                        <strong>Ciclos de lectura (Plagas):</strong> {{ $sanidad->ciclos_lectura_plagas ?? '-' }}<br>

                        {{-- Enfermedades (nuevas relaciones) --}}
                        @if ($sanidad->enfermedades && $sanidad->enfermedades->count())
                            <h6 class="mt-2 mb-1">Enfermedades:</h6>
                            <ul class="mb-2" style="list-style: none; padding-left: 0;">
                                @foreach ($sanidad->enfermedades as $enf)
                                    <li><strong>{{ $enf->nombre_enfermedad }}:</strong> {{ $enf->estado ?? '-' }}%</li>
                                @endforeach
                            </ul>
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
                            <h6 class="mt-2 mb-1">Enfermedades (legacy):</h6>
                            <ul class="mb-2" style="list-style: none; padding-left: 0;">
                                @foreach ($legacyEnfermedades as $field => $label)
                                    @if ($sanidad->$field)
                                        <li><strong>{{ $label }}</strong> {{ $sanidad->$field }}%</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                        {{-- Plagas (nuevas relaciones) --}}
                        @if ($sanidad->plagas && $sanidad->plagas->count())
                            <h6 class="mt-2 mb-1">Plagas:</h6>
                            <ul class="mb-2" style="list-style: none; padding-left: 0;">
                                @foreach ($sanidad->plagas as $pla)
                                    <li><strong>{{ $pla->nombre_plaga }}: </strong> :  estado -    {{ $pla->estado ?? '-' }}</li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- Plagas (legacy) --}}
                        @if ($sanidad->plaga)
                            <h6 class="mt-2 mb-1">Plaga (legacy):</h6>
                            <p>{{ $sanidad->plaga }} @if($sanidad->estado_plaga) - ({{ $sanidad->estado_plaga }}) @endif</p>
                        @endif

                        {{-- Trampas --}}
                        @if ($sanidad->trampas && $sanidad->trampas->count())
                            <h6 class="mt-2 mb-1">Trampas R. palmarum:</h6>
                            <ul style="list-style: none; padding-left: 0;">
                                @foreach($sanidad->trampas as $trampa)
                                    <li>
                                        <strong>Ciclos:</strong> {{ $trampa->ciclos ?? '-' }},
                                        <strong>Machos:</strong> {{ $trampa->machos_capturados ?? '-' }},
                                        <strong>Hembras:</strong> {{ $trampa->hembras_capturadas ?? '-' }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- Otros / observaciones --}}
                        @if ($sanidad->otros)
                            <strong>Otros (descripción):</strong> {{ $sanidad->otros }}<br>
                        @endif
                        @if ($sanidad->observaciones)
                            <strong>Observaciones:</strong> {{ $sanidad->observaciones }}
                        @endif
                    </div>

                    {{-- Acciones --}}
                    <div class="d-flex flex-column align-items-end mt-2 mt-md-0">
                        <a href="{{ route('sanidades.edit', $sanidad->id) }}" class="btn btn-sm btn-warning mb-2">✏️ Editar Registro</a>
                        <form method="POST" action="{{ route('sanidades.destroy', $sanidad->id) }}" onsubmit="return confirm('¿Deseas eliminar esta sanidad?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">🗑️ Eliminar</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@else
    <p class="text-muted text-center mt-4">No se han registrado sanidades aún.</p>
@endif


</div>

<script>
    // --- Mapeo (legacy) para los campos de enfermedades que antes estaban fijos en la tabla sanidades.
    // Se mantienen hidden inputs con estos nombres para compatibilidad.
    const diseaseFieldMap = {
        'Opsophanes': 'opsophanes',
        'Pudrición del cogollo': 'pudricion_cogollo',
        'Raspador': 'raspador',
        'Palmarum': 'palmarum',
        'Strategus': 'strategus',
        'Leptopharsa': 'leptopharsa',
        'Pestalotiopsis': 'pestalotiopsis',
        'Pudrición basal': 'pudricion_basal',
        'Pudrición estipe': 'pudricion_estipe',
    };

    // Arrays locales que representan filas dinámicas en el formulario
    let dynamicDiseases = [];
    let currentEnfermedadIndex = 0;

    let dynamicPlagas = [];
    let currentPlagaIndex = 0;

    // --- Funciones para mantener los hidden inputs legacy sincronizados ---
    function resetHiddenDiseaseInputs() {
        for (const key in diseaseFieldMap) {
            const fieldName = diseaseFieldMap[key];
            const hiddenInput = document.getElementById(`${fieldName}_hidden`);
            if (hiddenInput) {
                hiddenInput.value = '';
            }
        }
    }

    function updateHiddenDiseaseInputs() {
        // Limpia y vuelve a escribir según las enfermedades dinámicas seleccionadas.
        // Si hay varias entradas con la misma enfermedad, la última sobrescribirá.
        resetHiddenDiseaseInputs();
        dynamicDiseases.forEach(entry => {
            const fieldName = diseaseFieldMap[entry.name];
            if (fieldName) {
                const hiddenInput = document.getElementById(`${fieldName}_hidden`);
                if (hiddenInput) {
                    hiddenInput.value = entry.percentage;
                }
            }
        });
    }

    function updateHiddenPlagaInputs() {
        // Rellena los hidden legacy 'plaga' y 'estado_plaga' con la primera plaga dinámica (retrocompatibilidad).
        const hiddenPlaga = document.getElementById('plaga_hidden');
        const hiddenEstado = document.getElementById('estado_plaga_hidden');
        if (!hiddenPlaga || !hiddenEstado) return;

        if (dynamicPlagas.length > 0) {
            hiddenPlaga.value = dynamicPlagas[0].name || '';
            hiddenEstado.value = dynamicPlagas[0].estado || '';
        } else {
            hiddenPlaga.value = '';
            hiddenEstado.value = '';
        }
    }

    // --- Enfermedades dinámicas (UI) ---
    function addEnfermedad(defaultName = '', defaultPercentage = '') {
    const container = document.getElementById('enfermedades-container');
    const group = document.createElement('div');
    group.classList.add('enfermedad-group', 'mb-3');
    group.setAttribute('data-index', currentEnfermedadIndex);

    const uniqueId = `enfermedad_${currentEnfermedadIndex}`;

    group.innerHTML = `
        <div class="row gx-2 align-items-end">
            <div class="col-md-5 mb-2">
                <label for="${uniqueId}_nombre" class="form-label">Enfermedad:</label>
                <select name="enfermedades[${currentEnfermedadIndex}][nombre]" 
                        id="${uniqueId}_nombre" 
                        class="form-control" 
                        onchange="handleDiseaseChange(this)" required>
                    <option value="">Seleccione enfermedad</option>
                    ${Object.keys(diseaseFieldMap).map(opt => 
                        `<option value="${opt}">${opt}</option>`).join('')}
                </select>
            </div>
            <div class="col-md-5 mb-2">
                <label for="${uniqueId}_porcentaje" class="form-label">Afectación (%):</label>
                <input type="number" 
                       name="enfermedades[${currentEnfermedadIndex}][estado]" 
                       id="${uniqueId}_porcentaje" 
                       class="form-control" 
                       min="0" max="100" 
                       value="${defaultPercentage}" 
                       onchange="handlePercentageChange(this)">
            </div>
            <div class="col-md-2 mb-2 d-grid">
                <button type="button" class="btn btn-danger" onclick="removeEnfermedad(this)">✖️</button>
            </div>
        </div>
    `;
    container.appendChild(group);

    // Agregar al array local
    dynamicDiseases.push({
        id: currentEnfermedadIndex,
        name: defaultName || '',
        percentage: defaultPercentage || ''
    });

    // Setear valores iniciales
    if (defaultName) {
        group.querySelector(`#${uniqueId}_nombre`).value = defaultName;
    }

    currentEnfermedadIndex++;
    updateHiddenDiseaseInputs();
}



    function handleDiseaseChange(selectElement) {
        const index = parseInt(selectElement.closest('.enfermedad-group').getAttribute('data-index'));
        const newName = selectElement.value;
        const entry = dynamicDiseases.find(d => d.id === index);
        if (entry) {
            entry.name = newName;
            updateHiddenDiseaseInputs();
        }
    }

    function handlePercentageChange(inputElement) {
        const index = parseInt(inputElement.closest('.enfermedad-group').getAttribute('data-index'));
        const newPercentage = inputElement.value;
        const entry = dynamicDiseases.find(d => d.id === index);
        if (entry) {
            entry.percentage = newPercentage;
            updateHiddenDiseaseInputs();
        }
    }

    function removeEnfermedad(button) {
        const group = button.closest('.enfermedad-group');
        const indexToRemove = parseInt(group.getAttribute('data-index'));

        dynamicDiseases = dynamicDiseases.filter(entry => entry.id !== indexToRemove);
        group.remove();
        updateHiddenDiseaseInputs();

        if (dynamicDiseases.length === 0) {
            addEnfermedad();
        }
    }

    // --- Plagas dinámicas (UI) ---
    function addPlaga(defaultName = '', defaultEstado = '', defaultInstar = '') {
    const container = document.getElementById('plagas-container');
    const group = document.createElement('div');
    group.classList.add('plaga-group', 'mb-3');
    group.setAttribute('data-index', currentPlagaIndex);

    const uniqueId = `plaga_${currentPlagaIndex}`;

    group.innerHTML = `
        <div class="row gx-2 align-items-end">
            
            <!-- Select PLAGA -->
            <div class="col-md-4 mb-2">
                <label class="form-label">Plaga:</label>
                <select name="plagas[${currentPlagaIndex}][nombre]" id="${uniqueId}_nombre" class="form-control" onchange="handlePlagaChange(this)">
                        <option value="">Seleccione plaga</option>
                        <option value="Leptopharsa gibbicarina">Leptopharsa gibbicarina</option>
                        <option value="Stenoma cecropia">Stenoma cecropia</option>
                        <option value="Leucothyreus femaratus">Leucothyreus femaratus</option>
                        <option value="Brassolis sophorae">Brassolis sophorae</option>
                        <option value="Euprosterna eleasa">Euprosterna eleasa</option>
                        <option value="Sibine fusca">Sibine fusca</option>
                        <option value="Opsiphanes cassina">Opsiphanes cassina</option>
                        <option value="Automeris liberia">Automeris liberia</option>
                        <option value="Dirphia gragatus">Dirphia gragatus</option>
                        <option value="Cephaloleia vagelineata">Cephaloleia vagelineata</option>
                        <option value="Demotispa neivai">Demotispa neivai</option>
                        <option value="Loxotoma elegans">Loxotoma elegans</option>
                        <option value="Hispoleptis subfasciata">Hispoleptis subfasciata</option>
                        <option value="Haplaxius crudus">Haplaxius crudus</option>
                        <option value="Rhynchophorus palmarum">Rhynchophorus palmarum</option>
                        <option value="Strategus aloeus">Strategus aloeus</option>
                        <option value="Sagalassa valida">Sagalassa valida</option>
                    </select>
            </div>

            <!-- Select ESTADO GENERAL -->
            <div class="col-md-4 mb-2">
                <label class="form-label">Estado:</label>
                <select name="plagas[${currentPlagaIndex}][estado]" 
                        class="form-control estado-select"
                        onchange="toggleInstarSelect(this)">
                    <option value="">Seleccione estado</option>
                    <option value="Adulto">Adulto</option>
                    <option value="Larva">Larva</option>
                    <option value="Huevo">Huevo</option>
                </select>
            </div>

            <!-- SELECT INSTAR (solo visible si estado = larva) -->
            <div class="col-md-3 mb-2 instar-wrapper" style="display:none;">
                <label class="form-label">Instar:</label>
                <select name="plagas[${currentPlagaIndex}][instar]" class="form-control instar-select">
                    <option value="">Seleccione instar</option>
                    <option value="Instar I">Instar I</option>
                    <option value="Instar II">Instar II</option>
                    <option value="Instar III">Instar III</option>
                    <option value="Instar IV">Instar IV</option>
                    <option value="Instar V">Instar V</option>
                    <option value="Instar VI">Instar VI</option>
                    <option value="Instar VII">Instar VII</option>
                    <option value="Instar VIII">Instar VIII</option>
                    <option value="Instar IX">Instar IX</option>
                </select>
            </div>

            <!-- BOTÓN ELIMINAR -->
            <div class="col-md-1 mb-2 d-grid">
                <button type="button" class="btn btn-danger" onclick="removePlaga(this)">✖️</button>
            </div>

        </div>
    `;

    container.appendChild(group);

    dynamicPlagas.push({
        id: currentPlagaIndex,
        name: defaultName || '',
        estado: defaultEstado || '',
        instar: defaultInstar || ''
    });

    currentPlagaIndex++;
    updateHiddenPlagaInputs();
}



    function handlePlagaChange(selectElement) {
        const index = parseInt(selectElement.closest('.plaga-group').getAttribute('data-index'));
        const newName = selectElement.value;
        const entry = dynamicPlagas.find(p => p.id === index);
        if (entry) {
            entry.name = newName;
            updateHiddenPlagaInputs();
        }
    }

    function handlePlagaEstadoChange(selectElement) {
        const index = parseInt(selectElement.closest('.plaga-group').getAttribute('data-index'));
        const newEstado = selectElement.value;
        const entry = dynamicPlagas.find(p => p.id === index);
        if (entry) {
            entry.estado = newEstado;
            updateHiddenPlagaInputs();
        }
    }

    function removePlaga(button) {
        const group = button.closest('.plaga-group');
        const indexToRemove = parseInt(group.getAttribute('data-index'));

        dynamicPlagas = dynamicPlagas.filter(entry => entry.id !== indexToRemove);
        group.remove();
        updateHiddenPlagaInputs();

        if (dynamicPlagas.length === 0) {
            addPlaga();
        }
    }

    // --- Trampas R. palmarum (mantengo como antes) ---
    let currentTrampaIndex = 0;

    function addTrampa() {
        const container = document.getElementById('trampas-container');
        const group = document.createElement('div');
        group.classList.add('trampa-group', 'mb-3', 'p-3', 'border', 'rounded', 'bg-light');
        const uniqueId = `trampa_${currentTrampaIndex}`;

        group.innerHTML = `
            <div class="d-flex justify-content-end mb-2">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeTrampa(this)">✖️ Eliminar</button>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="${uniqueId}_ciclos" class="form-label">Ciclos:</label>
                    <input type="text" name="trampas[${currentTrampaIndex}][ciclos]" id="${uniqueId}_ciclos" class="form-control">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="${uniqueId}_machos" class="form-label">Machos Capturados:</label>
                    <input type="number" name="trampas[${currentTrampaIndex}][machos]" id="${uniqueId}_machos" class="form-control" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="${uniqueId}_hembras" class="form-label">Hembras Capturadas:</label>
                    <input type="number" name="trampas[${currentTrampaIndex}][hembras]" id="${uniqueId}_hembras" class="form-control" min="0">
                </div>
            </div>
        `;
        container.appendChild(group);
        currentTrampaIndex++;
    }

    function removeTrampa(button) {
        const group = button.closest('.trampa-group');
        group.remove();
    }

    // --- Carga inicial de datos (precarga si existe una sanidad previa) ---
    document.addEventListener('DOMContentLoaded', function() {
        // existingSanidad viene del servidor (puede ser null)
        const existingSanidad = @json($visita->sanidades->first());

        // Cargar Enfermedades legacy (a partir de campos fijos en la sanidad)
        const containerEnfermedades = document.getElementById('enfermedades-container');
        containerEnfermedades.innerHTML = '';
        dynamicDiseases = [];

        if (existingSanidad) {
            for (const key in diseaseFieldMap) {
                const fieldName = diseaseFieldMap[key];
                const percentage = existingSanidad[fieldName];
                if (percentage !== '' && percentage !== null && percentage !== undefined) {
                    // Añade la fila y setea el select al nombre (key) y porcentaje
                    addEnfermedad(key, percentage);
                }
            }
        }

        // Si no hay enfermedades precargadas, añadir una fila vacía
        if (dynamicDiseases.length === 0) {
            addEnfermedad();
        }

        // Cargar Plagas (legacy) si hay valores en los campos plaga / estado_plaga
        const containerPlagas = document.getElementById('plagas-container');
        containerPlagas.innerHTML = '';
        dynamicPlagas = [];

        if (existingSanidad && (existingSanidad.plaga || existingSanidad.estado_plaga)) {
            addPlaga(existingSanidad.plaga || '', existingSanidad.estado_plaga || '');
        } else {
            // Por defecto una fila vacía
            addPlaga();
        }

        // Cargar trampas si existieran (mantengo tu código)
        const trampasData = @json($visita->sanidades->first()->trampas ?? []);
        const containerTrampas = document.getElementById('trampas-container');
        containerTrampas.innerHTML = '';

        if (Array.isArray(trampasData) && trampasData.length > 0) {
            trampasData.forEach(trampa => {
                const group = document.createElement('div');
                group.classList.add('trampa-group', 'mb-3', 'p-3', 'border', 'rounded', 'bg-light');
                const uniqueId = `trampa_${currentTrampaIndex}`;

                group.innerHTML = `
                    <div class="d-flex justify-content-end mb-2">
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeTrampa(this)">✖️ Eliminar</button>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="${uniqueId}_ciclos" class="form-label">Ciclos:</label>
                            <input type="text" name="trampas[${currentTrampaIndex}][ciclos]" id="${uniqueId}_ciclos" class="form-control" value="${trampa.ciclos ?? ''}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="${uniqueId}_machos" class="form-label">Machos Capturados:</label>
                            <input type="number" name="trampas[${currentTrampaIndex}][machos]" id="${uniqueId}_machos" class="form-control" min="0" value="${trampa.machos_capturados ?? ''}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="${uniqueId}_hembras" class="form-label">Hembras Capturadas:</label>
                            <input type="number" name="trampas[${currentTrampaIndex}][hembras]" id="${uniqueId}_hembras" class="form-control" min="0" value="${trampa.hembras_capturadas ?? ''}">
                        </div>
                    </div>
                `;
                containerTrampas.appendChild(group);
                currentTrampaIndex++;
            });
        }

        // Asegurar que los hidden legacy estén sincronizados al primer render
        updateHiddenDiseaseInputs();
        updateHiddenPlagaInputs();
    });
    function toggleInstarSelect(selectElement) {
    const row = selectElement.closest('.row');
    const instarBox = row.querySelector('.instar-wrapper');

    if (selectElement.value === 'Larva') {
        instarBox.style.display = 'block';
    } else {
        instarBox.style.display = 'none';
        row.querySelector('.instar-select').value = '';
    }
}

function handlePlagaEstadoChange(selectElement) {
    updateHiddenPlagaInputs();
}

</script>
<script>

// --------------------------------------
// ARRAYS LOCALES
// --------------------------------------
let dynamicDiseases = [];
let currentEnfermedadIndex = 0;

let dynamicPlagas = [];
let currentPlagaIndex = 0;

let dynamicTrampas = [];
let currentTrampaIndex = 0;

// --------------------------------------
// LEGACY MAP
// --------------------------------------
const diseaseFieldMap = {
    'Opsophanes': 'opsophanes',
    'Pudrición del cogollo': 'pudricion_cogollo',
    'Raspador': 'raspador',
    'Palmarum': 'palmarum',
    'Strategus': 'strategus',
    'Leptopharsa': 'leptopharsa',
    'Pestalotiopsis': 'pestalotiopsis',
    'Pudrición basal': 'pudricion_basal',
    'Pudrición estipe': 'pudricion_estipe',
};

function resetHiddenDiseaseInputs() {
    Object.values(diseaseFieldMap).forEach(field => {
        document.getElementById(field + "_hidden").value = "";
    });
}

function updateHiddenDiseaseInputs() {
    resetHiddenDiseaseInputs();
    dynamicDiseases.forEach(item => {
        if (diseaseFieldMap[item.name]) {
            document.getElementById(diseaseFieldMap[item.name] + "_hidden").value = item.percentage;
        }
    });
}

function updateHiddenPlagaInputs() {
    const h1 = document.getElementById("plaga_hidden");
    const h2 = document.getElementById("estado_plaga_hidden");

    if (dynamicPlagas.length === 0) {
        h1.value = "";
        h2.value = "";
        return;
    }

    h1.value = dynamicPlagas[0].name;
    h2.value = dynamicPlagas[0].estado;
}

// --------------------------------------
// ENFERMEDADES
// --------------------------------------
function addEnfermedad(defaultName = "", defaultPercentage = "") {
    const container = document.getElementById("enfermedades-container");

    const html = `
        <div class="row mb-2" data-enf="${currentEnfermedadIndex}">
            <div class="col-md-5">
                <select class="form-control enf-name">
                    <option value="">Seleccione</option>
                    ${Object.keys(diseaseFieldMap)
                        .map(name => `<option value="${name}">${name}</option>`).join("")}
                </select>
            </div>
            <div class="col-md-5">
                <input type="number" class="form-control enf-percentage" min="0" max="100" value="${defaultPercentage}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-danger w-100" onclick="removeEnfermedad(${currentEnfermedadIndex})">X</button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML("beforeend", html);

    dynamicDiseases.push({
        id: currentEnfermedadIndex,
        name: defaultName,
        percentage: defaultPercentage,
    });

    currentEnfermedadIndex++;

    updateHiddenDiseaseInputs();
}

function removeEnfermedad(id) {
    document.querySelector(`[data-enf="${id}"]`).remove();
    dynamicDiseases = dynamicDiseases.filter(e => e.id !== id);
    updateHiddenDiseaseInputs();
}

document.addEventListener("change", e => {
    if (e.target.classList.contains("enf-name")) {
        const row = e.target.closest("[data-enf]");
        const id = parseInt(row.getAttribute("data-enf"));

        const item = dynamicDiseases.find(x => x.id === id);
        if (item) {
            item.name = e.target.value;
            updateHiddenDiseaseInputs();
        }
    }

    if (e.target.classList.contains("enf-percentage")) {
        const row = e.target.closest("[data-enf]");
        const id = parseInt(row.getAttribute("data-enf"));

        const item = dynamicDiseases.find(x => x.id === id);
        if (item) {
            item.percentage = e.target.value;
            updateHiddenDiseaseInputs();
        }
    }
});

// --------------------------------------
// PLAGAS
// --------------------------------------


function removePlaga(id) {
    document.querySelector(`[data-plaga="${id}"]`).remove();
    dynamicPlagas = dynamicPlagas.filter(p => p.id !== id);
    updateHiddenPlagaInputs();
}

document.addEventListener("input", e => {
    if (e.target.classList.contains("plaga-name")) {
        const row = e.target.closest("[data-plaga]");
        const id = parseInt(row.getAttribute("data-plaga"));
        const item = dynamicPlagas.find(x => x.id === id);
        item.name = e.target.value;
        updateHiddenPlagaInputs();
    }
    if (e.target.classList.contains("plaga-estado")) {
        const row = e.target.closest("[data-plaga]");
        const id = parseInt(row.getAttribute("data-plaga"));
        const item = dynamicPlagas.find(x => x.id === id);
        item.estado = e.target.value;
        updateHiddenPlagaInputs();
    }
});

// --------------------------------------
// TRAMPAS
// --------------------------------------
function addTrampa(defaultCiclos = "", defaultMachos = "", defaultHembras = "") {
    const container = document.getElementById("trampas-container");

    const html = `
        <div class="row mb-2" data-trampa="${currentTrampaIndex}">
            <div class="col-md-3"><input class="form-control trampa-ciclos" value="${defaultCiclos}" placeholder="Ciclos"></div>
            <div class="col-md-3"><input class="form-control trampa-machos" value="${defaultMachos}" placeholder="Machos"></div>
            <div class="col-md-3"><input class="form-control trampa-hembras" value="${defaultHembras}" placeholder="Hembras"></div>
            <div class="col-md-2">
                <button class="btn btn-danger w-100" onclick="removeTrampa(${currentTrampaIndex})">X</button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML("beforeend", html);

    dynamicTrampas.push({
        id: currentTrampaIndex,
        ciclos: defaultCiclos,
        machos: defaultMachos,
        hembras: defaultHembras
    });

    currentTrampaIndex++;
}

function removeTrampa(id) {
    document.querySelector(`[data-trampa="${id}"]`).remove();
    dynamicTrampas = dynamicTrampas.filter(t => t.id !== id);
}

document.addEventListener("input", e => {
    const row = e.target.closest("[data-trampa]");
    if (!row) return;

    const id = parseInt(row.getAttribute("data-trampa"));
    const item = dynamicTrampas.find(x => x.id === id);

    if (e.target.classList.contains("trampa-ciclos")) item.ciclos = e.target.value;
    if (e.target.classList.contains("trampa-machos")) item.machos = e.target.value;
    if (e.target.classList.contains("trampa-hembras")) item.hembras = e.target.value;
});

</script>
<script>

let dynamicPlagas = [];
let currentPlagaIndex = 0;

const INSTARES = [
    "Instar I","Instar II","Instar III","Instar IV",
    "Instar V","Instar VI","Instar VII","Instar VIII","Instar IX"
];

// --------------------------------------
// CREAR FILA PLAGA
// --------------------------------------
function addPlaga(defaultName = '', defaultEstado = '', defaultInstar = '') {
    const container = document.getElementById('plagas-container');
    const group = document.createElement('div');
    group.classList.add('plaga-group', 'mb-3');
    group.setAttribute('data-index', currentPlagaIndex);

    const uniqueId = `plaga_${currentPlagaIndex}`;

    group.innerHTML = `
        <div class="row gx-2 align-items-end">
            
            <!-- Select PLAGA -->
            <div class="col-md-4 mb-2">
                <label class="form-label">Plaga:</label>
                <select name="plagas[${currentPlagaIndex}][nombre]" 
                        class="form-control plaga-select"
                        onchange="handlePlagaEstadoChange(this)">
                    <option value="">Seleccione plaga</option>
                    <option value="Adulto">Adulto</option>
                    <option value="Larva">Larva</option>
                    <option value="Huevo">Huevo</option>
                </select>
            </div>

            <!-- Select ESTADO GENERAL -->
            <div class="col-md-4 mb-2">
                <label class="form-label">Estado:</label>
                <select name="plagas[${currentPlagaIndex}][estado]" 
                        class="form-control estado-select"
                        onchange="toggleInstarSelect(this)">
                    <option value="">Seleccione estado</option>
                    <option value="Adulto">Adulto</option>
                    <option value="Larva">Larva</option>
                    <option value="Huevo">Huevo</option>
                </select>
            </div>

            <!-- SELECT INSTAR (solo visible si estado = larva) -->
            <div class="col-md-3 mb-2 instar-wrapper" style="display:none;">
                <label class="form-label">Instar:</label>
                <select name="plagas[${currentPlagaIndex}][instar]" class="form-control instar-select">
                    <option value="">Seleccione instar</option>
                    <option value="Instar I">Instar I</option>
                    <option value="Instar II">Instar II</option>
                    <option value="Instar III">Instar III</option>
                    <option value="Instar IV">Instar IV</option>
                    <option value="Instar V">Instar V</option>
                    <option value="Instar VI">Instar VI</option>
                    <option value="Instar VII">Instar VII</option>
                    <option value="Instar VIII">Instar VIII</option>
                    <option value="Instar IX">Instar IX</option>
                </select>
            </div>

            <!-- BOTÓN ELIMINAR -->
            <div class="col-md-1 mb-2 d-grid">
                <button type="button" class="btn btn-danger" onclick="removePlaga(this)">✖️</button>
            </div>

        </div>
    `;

    container.appendChild(group);

    dynamicPlagas.push({
        id: currentPlagaIndex,
        name: defaultName || '',
        estado: defaultEstado || '',
        instar: defaultInstar || ''
    });

    currentPlagaIndex++;
    updateHiddenPlagaInputs();
}

// --------------------------------------
// ELIMINAR PLAGA
// --------------------------------------
function removePlaga(id) {
    document.querySelector(`[data-plaga="${id}"]`).remove();
    dynamicPlagas = dynamicPlagas.filter(p => p.id !== id);
    updateLegacyPlagaHidden();
}

// --------------------------------------
// EVENTOS
// --------------------------------------
document.addEventListener("input", function(e) {
    const row = e.target.closest("[data-plaga]");
    if (!row) return;

    const id = parseInt(row.getAttribute("data-plaga"));
    const item = dynamicPlagas.find(p => p.id === id);

    if (e.target.classList.contains("plaga-name")) {
        item.name = e.target.value;
    }
    if (e.target.classList.contains("plaga-instar")) {
        item.instar = e.target.value;
    }

    updateLegacyPlagaHidden();
});

document.addEventListener("change", function(e) {
    if (!e.target.classList.contains("plaga-estado")) return;

    const row = e.target.closest("[data-plaga]");
    const id = parseInt(row.getAttribute("data-plaga"));
    const item = dynamicPlagas.find(p => p.id === id);

    item.estado = e.target.value;

    const instarDiv = row.querySelector(".instar-container");

    // SI ESTADO ES LARVA → mostrar select de instar
    if (e.target.value === "Larva") {
        instarDiv.style.display = "block";
    } else {
        instarDiv.style.display = "none";
        item.instar = ""; // limpiar instar
        row.querySelector(".plaga-instar").value = "";
    }

    updateLegacyPlagaHidden();
});

// --------------------------------------
// ACTUALIZAR HIDDEN LEGACY
// --------------------------------------
function updateLegacyPlagaHidden() {

    let h1 = document.getElementById("plaga_hidden");
    let h2 = document.getElementById("estado_plaga_hidden");

    if (dynamicPlagas.length == 0) {
        h1.value = "";
        h2.value = "";
        return;
    }

    // Solo enviamos la PRIMERA (como funcionaba legacy)
    h1.value = dynamicPlagas[0].name;
    h2.value = dynamicPlagas[0].estado;
}

// === Mostrar instares cuando el estado sea "Larva" ===
function handleEstadoPlagaChange(selectElement) {
    const row = selectElement.closest('.plaga-group');
    const instarContainer = row.querySelector('.instar-container');

    if (selectElement.value === "Larva") {
        instarContainer.style.display = "block";
    } else {
        instarContainer.style.display = "none";
        // Limpia valores si se cambia de Larva a otro estado
        instarContainer.querySelectorAll("input").forEach(i => i.value = "");
    }
}


</script>


@endsection

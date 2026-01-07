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


    @media (max-width: 968px) {

         .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929); 
        width: 123%;/* Color de fondo específico para este formulario */
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
<div class="container" style="width: 110%" >
<h3 class="title">🌸 Información de previa plantación - Polinización 🌸</h3><h3><br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span> <br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
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
    {{-- Acordeón: Área --}}
    <div class="accordion mb-4" id="accordionPolinizacion" style="background-color: darkseagreen !important">

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


        {{-- Acordeón: Fertilizaciones --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFertilizaciones">
                <button style="background-color: darkseagreen !important; color:aliceblue" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFertilizaciones" aria-expanded="false">
                    💧 Fertilizaciones registradas
                </button>
            </h2>
            <div id="collapseFertilizaciones" class="accordion-collapse collapse" data-bs-parent="#accordionPolinizacion">
                <div class="accordion-body" style="background-color: rgb(209, 241, 209) !important; color:rgb(31, 32, 34)">
                    @if ($visita->fertilizaciones->count())
                        @foreach ($visita->fertilizaciones as $fertilizacion)
                            <div class="mb-3">
                                <h6>📅 Fecha Registro <br> {{ $fertilizacion->fecha_fertilizacion }}</h6>
                                <ul class="list-group">
                                    
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
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay fertilizaciones registradas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Verificación de áreas híbridas -->
    @php
        $tieneAreasHibridas = false;
        foreach ($visita->areas as $area) {
            if (strtolower($area->variedad) === 'hibrido') {
                $tieneAreasHibridas = true;
                break;
            }
        }
    @endphp

    @if($tieneAreasHibridas)
        <!-- Mostrar formulario de polinización si hay áreas híbridas -->
        <h3>🌾Formulario de Registro de Polinización - {{ $visita->proveedor->proveedor_nombre }}</h3>
        <form method="POST" action="{{ route('polinizaciones.store') }}">
            @csrf
            <input type="hidden" name="visita_id" value="{{ $visita->id }}">

            <div class="mb-3">
                <label>📅 Fecha de polinización:</label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>🔁 Número de pases:</label>
                <input type="number" name="n_pases" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>🔄 Ciclos por ronda:</label>
                <input type="number" name="ciclos_ronda" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>💊 Cantidad de ANA aplicada:</label><br>
                 <span style="font-size: 11px">Escriba numero (cantidad) y unidad de medida</span>
                <input type="text" name="ana" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>💧 Tipo de ANA:</label>
                <select name="tipo_ana" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="solido">Sólido</option>
                    <option value="liquido">Líquido</option>
                </select>
            </div>
            <div class="mb-3">
                <label>💊 Nombre de ANA aplicada:</label>
                <input type="text"  name="nombre_ana" class="form-control" >
            </div>
            <div class="mb-3">
                <label>🌫️ Cantidad de talco aplicado:</label><br>
                <span style="font-size: 11px">Escriba numero (cantidad) y unidad de medida</span>
                <input type="text"  name="talco" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">💾 Guardar polinización</button>
        </form>
            @else
            <div class="alert alert-info mt-4">
                <h4>⚠️ No aplica polinización</h4>
                <p>Ninguna de las áreas registradas tiene material "Híbrido".</p>
                
                <!-- Botón para continuar a Sanidad -->
                <a href="{{ route('sanidades.create', ['visita_id' => $visita->id]) }}" 
                   class="btn btn-primary mt-2">
                    → Continuar a Sanidad
                </a>
                
                <!-- Opcional: Botón para volver -->
                <a href="{{ route('visitas.show', $visita->id) }}" 
                   class="btn btn-secondary mt-2 ms-2">
                    ⬅️ Volver
                </a>
            </div>
        @endif

    <a href="{{ route('visitas.show', $visita->id) }}" class="btn btn-secondary mt-4">
        ⬅️ Volver al detalle de la visita
    </a>


    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
</div>


    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

        <div class="container" style="background-color: whitesmoke; padding: 20px;">
    @if ($visita->polinizaciones->count())
        <div class="mb-4 mt-5">
            <h5>🌸 Polinizaciones registradas</h5>
            <ul class="list-group">
                @foreach ($visita->polinizaciones as $poli)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            📅 <strong>{{ $poli->fecha }}</strong><br>
                            N° Pases: {{ $poli->n_pases }} | 
                            Ciclos: {{ $poli->ciclos_ronda }} | 
                            ANA: {{ $poli->ana }} ({{ ucfirst($poli->tipo_ana) }}) | 
                            Talco: {{ $poli->talco }}
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('polinizaciones.edit', $poli->id) }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                            <form action="{{ route('polinizaciones.destroy', $poli->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta polinización?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    </div>



</div>
@endsection

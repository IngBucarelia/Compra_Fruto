@extends('layouts.app')

@section('content')
<style>
    /* Puedes pegar el contenido del bloque 'Estilos CSS para Formularios Responsivos (Reutilizable)' aquí
       si no lo tienes en un archivo CSS global enlazado en layouts/app.blade.php. */
    .container {
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
        border-radius: 8px; /* Añadido para consistencia */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Añadido para consistencia */
        max-width: 800px; /* Limita el ancho en pantallas muy grandes */
        margin-top: 25px; /* Margen superior para separación */
    }
    /* Estilos específicos para este formulario si los necesitas */
    .container.form-container { /* Nueva clase para el contenedor principal */
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        max-width: 800px; /* Limita el ancho en pantallas muy grandes */
        
        margin-top: 25px;
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

    /* Estilos para los acordeones de área */
    .accordion-item .accordion-button {
        background-color: darkseagreen !important;
        color: aliceblue !important;
        font-weight: bold;
    }
    .accordion-item .accordion-body {
        background-color: rgb(209, 241, 209) !important;
        color: rgb(31, 32, 34);
    }
    .area-info-card {
        background-color: #f0fdf0;
        border: 1px solid #d4edda;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .area-info-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .area-info-card li {
        padding: 5px 0;
        border-bottom: 1px dashed #e2e6ea;
    }
    .area-info-card li:last-child {
        border-bottom: none;
    }

    /* Estilos para el formulario de fertilización */
    .fertilizante-group {
        border: 1px solid #c3e6cb;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        background-color: #f8fdf8;
        position: relative;
    }
    .fertilizante-group .remove-fertilizante-btn {
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
        position: absolute;
        top: 10px;
        right: 10px;
    }
    .fertilizante-group .remove-fertilizante-btn:hover {
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

<div class="container form-container">

    <h3 class="title">🌴🌴 Información Previa de plantación - Fertilización 🌴🌴</h3><br>
    {{-- ✅ Mostrar errores generales del servidor (ej. de la excepción catch) --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- ✅ Mostrar errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <h5 class="alert-heading">¡Errores de Validación!</h5>
            <p>Por favor, corrige los siguientes problemas:</p>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif <br>
    <h3>
        <br>
        Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span> <br>
        Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br>
        Plantación: <span style="color: wheat">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span>
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
    </script>
    <br><br>

    <div class="accordion mb-4" id="accordionArea">
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



<!-- Agregar FontAwesome para los iconos si no los tienes -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </div>

    <h3 class="title">Formulario de Fertilización para: {{ $visita->proveedor->proveedor_nombre }}</h3>

    <form method="POST" action="{{ route('fertilizaciones.store') }}">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        {{-- ✅ CAMBIO: Campo de fecha de fertilización general RESTAURADO --}}
        <div class="mb-3">
            <label for="fecha_fertilizacion">Fecha General de la Fertilización:</label>
            <input type="date" name="fecha_fertilizacion" id="fecha_fertilizacion" class="form-control" required value="{{ old('fecha_fertilizacion') }}">
        </div>

        <h5>Fertilizantes aplicados</h5>
        <div id="fertilizantes-container">
            {{-- Primer grupo de fertilizantes con fecha y unidad de medida --}}
            <div class="fertilizante-group mb-3">
                <button type="button" class="remove-fertilizante-btn" onclick="removeFertilizante(this)">✖️</button>
                <div class="mb-2">
                    <label for="fertilizante_fecha_0">Fecha de aplicación:</label>
                    <input type="date" name="fertilizantes[0][fecha_aplicacion]" id="fertilizante_fecha_0" class="form-control" required value="{{ old('fertilizantes.0.fecha_aplicacion') }}">
                </div>
                <div class="mb-2">
                    <label for="fertilizante_nombre_0">Fertilizante:</label>
                    <select name="fertilizantes[0][nombre]" id="fertilizante_nombre_0" class="form-control fertilizante-select" required onchange="toggleOtroFertilizante(this)">
                        <option value="">Seleccione fertilizante</option>
                        <option value="compost" {{ old('fertilizantes.0.nombre') == 'compost' ? 'selected' : '' }}>Compost</option>
                        <option value="npk" {{ old('fertilizantes.0.nombre') == 'npk' ? 'selected' : '' }}>NPK</option>
                        <option value="Grado Palmero (Yara)" {{ old('fertilizantes.0.nombre') == 'Grado Palmero (Yara)' ? 'selected' : '' }}>Grado Palmero (Yara)</option>
                        <option value="Kieserita" {{ old('fertilizantes.0.nombre') == 'Kieserita' ? 'selected' : '' }}>Kieserita</option>
                        <option value="Mezcla Fisica" {{ old('fertilizantes.0.nombre') == 'Mezcla Fisica' ? 'selected' : '' }}>Mezcla Física</option>
                        <option value="Borato 48" {{ old('fertilizantes.0.nombre') == 'Borato 48' ? 'selected' : '' }}>Borato 48</option>
                        <option value="DAP" {{ old('fertilizantes.0.nombre') == 'DAP' ? 'selected' : '' }}>DAP</option>
                        <option value="KCl" {{ old('fertilizantes.0.nombre') == 'KCl' ? 'selected' : '' }}>KCl</option>
                        <option value="Nitrax" {{ old('fertilizantes.0.nombre') == 'Nitrax' ? 'selected' : '' }}>Nitrax</option>
                        <option value="Mezcla por el Productor" {{ old('fertilizantes.0.nombre') == 'Mezcla por el Productor' ? 'selected' : '' }}>Mezcla por el Productor</option>
                        <option value="KMAG" {{ old('fertilizantes.0.nombre') == 'KMAG' ? 'selected' : '' }}>KMAG</option>
                        <option value="Caldolomita" {{ old('fertilizantes.0.nombre') == 'Caldolomita' ? 'selected' : '' }}>Caldolomita</option>
                        <option value="Enmienda Paz del Rio" {{ old('fertilizantes.0.nombre') == 'Enmienda Paz del Rio' ? 'selected' : '' }}>Enmienda Paz del Rio</option>
                        <option value="Mezcla 14-4-29-4 (Acepalma)" {{ old('fertilizantes.0.nombre') == 'Mezcla 14-4-29-4 (Acepalma)' ? 'selected' : '' }}>Mezcla 14-4-29-4 (Acepalma)</option>
                        <option value="UREA" {{ old('fertilizantes.0.nombre') == 'UREA' ? 'selected' : '' }}>UREA</option>
                        <option value="Nitrabor" {{ old('fertilizantes.0.nombre') == 'Nitrabor' ? 'selected' : '' }}>Nitrabor</option>
                        <option value="Grado 13-6-23-6 (Monomeros)" {{ old('fertilizantes.0.nombre') == 'Grado 13-6-23-6 (Monomeros)' ? 'selected' : '' }}>Grado 13-6-23-6 (Monomeros)</option>
                        <!-- ✅ NUEVA OPCIÓN "OTRO" -->
                        <option value="otro" {{ old('fertilizantes.0.nombre') == 'otro' ? 'selected' : '' }}>Otro (especificar)</option>
                    </select>
                    <div id="otro-fertilizante-container-0" class="mt-2" style="display: {{ old('fertilizantes.0.nombre') == 'otro' ? 'block' : 'none' }};">
                        <label for="otro_fertilizante_0">Especifique el fertilizante:</label>
                        <input type="text" name="fertilizantes[0][otro_fertilizante]" id="otro_fertilizante_0" class="form-control" 
                               placeholder="Nombre del fertilizante" value="{{ old('fertilizantes.0.otro_fertilizante') }}">
                    </div>
                </div>
                <div class="mb-2">
                    <label for="fertilizante_cantidad_0">Cantidad:</label>
                    <input type="number" name="fertilizantes[0][cantidad]" id="fertilizante_cantidad_0" class="form-control" placeholder="Cantidad" required min="0" step="0.01" value="{{ old('fertilizantes.0.cantidad') }}">
                </div>
                <div class="mb-0">
                    <label for="fertilizante_unidad_0">Unidad de Medida:</label>
                    <select name="fertilizantes[0][unidad_medida]" id="fertilizante_unidad_0" class="form-control" required>
                        <option value="">Seleccione unidad</option>
                        <option value="kg" {{ old('fertilizantes.0.unidad_medida') == 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                        <option value="g" {{ old('fertilizantes.0.unidad_medida') == 'g' ? 'selected' : '' }}>Gramos (g)</option>
                        <option value="ton" {{ old('fertilizantes.0.unidad_medida') == 'ton' ? 'selected' : '' }}>Toneladas (ton)</option>
                        <option value="lb" {{ old('fertilizantes.0.unidad_medida') == 'lb' ? 'selected' : '' }}>Libras (lb)</option>
                        <option value="litros" {{ old('fertilizantes.0.unidad_medida') == 'litros' ? 'selected' : '' }}>Litros (L)</option>
                        <option value="ml" {{ old('fertilizantes.0.unidad_medida') == 'ml' ? 'selected' : '' }}>Mililitros (ml)</option>
                        <option value="unidades" {{ old('fertilizantes.0.unidad_medida') == 'unidades' ? 'selected' : '' }}>Unidades</option>
                        <option value="sacos" {{ old('fertilizantes.0.unidad_medida') == 'sacos' ? 'selected' : '' }}>Sacos</option>
                        <option value="bultos" {{ old('fertilizantes.0.unidad_medida') == 'bultos' ? 'selected' : '' }}>Bultos</option>
                        <option value="hectolitro" {{ old('fertilizantes.0.unidad_medida') == 'hectolitro' ? 'selected' : '' }}>Hectolitro (hl)</option>
                        <option value="galones" {{ old('fertilizantes.0.unidad_medida') == 'galones' ? 'selected' : '' }}>Galones</option>
                        <option value="bolsas" {{ old('fertilizantes.0.unidad_medida') == 'bolsas' ? 'selected' : '' }}>Bolsas</option>
                        <option value="dosis" {{ old('fertilizantes.0.unidad_medida') == 'dosis' ? 'selected' : '' }}>Dosis</option>
                        <option value="ha" {{ old('fertilizantes.0.unidad_medida') == 'ha' ? 'selected' : '' }}>Por hectárea (ha)</option>
                        <option value="mz" {{ old('fertilizantes.0.unidad_medida') == 'mz' ? 'selected' : '' }}>Por manzana (mz)</option>
                    </select>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-info mb-3" onclick="agregarFertilizante()">+ Añadir otro fertilizante</button>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">Guardar fertilización</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <hr>
    <div class="container" style="background-color: whitesmoke; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h4 class="mt-4">📋 Fertilizaciones registradas</h4>

    @if ($visita->fertilizaciones->count())
        @foreach ($visita->fertilizaciones as $fertilizacion)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <strong>🗓 Fecha:</strong> {{ $fertilizacion->fecha_fertilizacion }}
                    </div>
                    <div>
                        <a href="{{ route('fertilizaciones.edit', $fertilizacion->id) }}" class="btn btn-sm btn-outline-warning">✏️ Editar</a>
                        <form action="{{ route('fertilizaciones.destroy', $fertilizacion->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta fertilización?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">🗑️ Eliminar</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($fertilizacion->fertilizantes as $fertilizante)
                            <li class="list-group-item d-flex justify-content-between flex-wrap">
                                <div>
                                    {{-- ✅ CAMBIO: $fertilizante->fertilizante para mostrar el nombre --}}
                                    <strong>{{ ucfirst($fertilizante->fertilizante) }}</strong> ({{ $fertilizante->fecha_aplicacion ?? 'N/A' }})
                                </div>
                                <span>{{ $fertilizante->cantidad }} {{ $fertilizante->unidad_medida ?? 'N/A' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-muted">No hay fertilizaciones registradas aún.</p>
    @endif
    </div>

</div>

<script>
    let fertilizanteIndex = 1;

    // ✅ FUNCIÓN MEJORADA: Ahora incluye todas las opciones del primer select
    function agregarFertilizante() {
        const container = document.getElementById('fertilizantes-container');
        const grupo = document.createElement('div');
        grupo.classList.add('fertilizante-group', 'mb-3');
        
        grupo.innerHTML = `
            <button type="button" class="remove-fertilizante-btn" onclick="removeFertilizante(this)">✖️</button>
            <div class="mb-2">
                <label for="fertilizante_fecha_${fertilizanteIndex}">Fecha de aplicación:</label>
                <input type="date" name="fertilizantes[${fertilizanteIndex}][fecha_aplicacion]" id="fertilizante_fecha_${fertilizanteIndex}" class="form-control" required>
            </div>
            <div class="mb-2">
                <label for="fertilizante_nombre_${fertilizanteIndex}">Fertilizante:</label>
                <select name="fertilizantes[${fertilizanteIndex}][nombre]" id="fertilizante_nombre_${fertilizanteIndex}" class="form-control fertilizante-select" required onchange="toggleOtroFertilizante(this)">
                    <option value="">Seleccione fertilizante</option>
                    <option value="compost">Compost</option>
                    <option value="npk">NPK</option>
                    <option value="Grado Palmero (Yara)">Grado Palmero (Yara)</option>
                    <option value="Kieserita">Kieserita</option>
                    <option value="Mezcla Fisica">Mezcla Física</option>
                    <option value="Borato 48">Borato 48</option>
                    <option value="DAP">DAP</option>
                    <option value="KCl">KCl</option>
                    <option value="Nitrax">Nitrax</option>
                    <option value="Mezcla por el Productor">Mezcla por el Productor</option>
                    <option value="KMAG">KMAG</option>
                    <option value="Caldolomita">Caldolomita</option>
                    <option value="Enmienda Paz del Rio">Enmienda Paz del Rio</option>
                    <option value="Mezcla 14-4-29-4 (Acepalma)">Mezcla 14-4-29-4 (Acepalma)</option>
                    <option value="UREA">UREA</option>
                    <option value="Nitrabor">Nitrabor</option>
                    <option value="Grado 13-6-23-6 (Monomeros)">Grado 13-6-23-6 (Monomeros)</option>
                    <option value="otro">Otro (especificar)</option>
                </select>
                <div id="otro-fertilizante-container-${fertilizanteIndex}" class="mt-2" style="display: none;">
                    <label for="otro_fertilizante_${fertilizanteIndex}">Especifique el fertilizante:</label>
                    <input type="text" name="fertilizantes[${fertilizanteIndex}][otro_fertilizante]" id="otro_fertilizante_${fertilizanteIndex}" class="form-control" placeholder="Nombre del fertilizante">
                </div>
            </div>
            <div class="mb-2">
                <label for="fertilizante_cantidad_${fertilizanteIndex}">Cantidad:</label>
                <input type="number" name="fertilizantes[${fertilizanteIndex}][cantidad]" id="fertilizante_cantidad_${fertilizanteIndex}" class="form-control" placeholder="Cantidad" required min="0" step="0.01">
            </div>
            <div class="mb-0">
                <label for="fertilizante_unidad_${fertilizanteIndex}">Unidad de Medida:</label>
                <select name="fertilizantes[${fertilizanteIndex}][unidad_medida]" id="fertilizante_unidad_${fertilizanteIndex}" class="form-control" required>
                    <option value="">Seleccione unidad</option>
                    <option value="kg">Kilogramos (kg)</option>
                    <option value="g">Gramos (g)</option>
                    <option value="ton">Toneladas (ton)</option>
                    <option value="lb">Libras (lb)</option>
                    <option value="litros">Litros (L)</option>
                    <option value="ml">Mililitros (ml)</option>
                    <option value="unidades">Unidades</option>
                    <option value="sacos">Sacos</option>
                    <option value="bultos">Bultos</option>
                    <option value="hectolitro">Hectolitro (hl)</option>
                    <option value="galones">Galones</option>
                    <option value="bolsas">Bolsas</option>
                    <option value="dosis">Dosis</option>
                    <option value="ha">Por hectárea (ha)</option>
                    <option value="mz">Por manzana (mz)</option>
                </select>
            </div>
        `;
        
        container.appendChild(grupo);
        fertilizanteIndex++;
    }

    function removeFertilizante(button) {
        const group = button.closest('.fertilizante-group');
        if (group) {
            group.remove();
        }
    }
    
    // ✅ FUNCIÓN PARA MOSTRAR/OCULTAR EL CAMPO "OTRO"
    function toggleOtroFertilizante(selectElement) {
        const index = selectElement.id.split('_').pop();
        const otroContainer = document.getElementById(`otro-fertilizante-container-${index}`);
        
        if (selectElement.value === 'otro') {
            otroContainer.style.display = 'block';
        } else {
            otroContainer.style.display = 'none';
            // Limpiar el campo cuando se selecciona otra opción
            document.getElementById(`otro_fertilizante_${index}`).value = '';
        }
    }
    
    // ✅ INICIALIZAR LOS SELECTS EXISTENTES AL CARGAR LA PÁGINA
    document.addEventListener('DOMContentLoaded', function() {
        // Para el formulario inicial
        const selectInicial = document.getElementById('fertilizante_nombre_0');
        if (selectInicial && selectInicial.value === 'otro') {
            document.getElementById('otro-fertilizante-container-0').style.display = 'block';
        }
    });
</script>
@endsection
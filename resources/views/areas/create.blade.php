@extends('layouts.app')

@section('content')

{{-- Incluir los estilos responsivos para formularios --}}
{{-- Estos estilos deberían estar en un archivo CSS global (ej. public/css/offline-forms.css) --}}
{{-- y enlazados en layouts/app.blade.php. Si no, puedes pegarlos directamente aquí. --}}
{{-- Para este ejemplo, asumo que ya están enlazados globalmente o los pegarás aquí. --}}
<style>
    /* Puedes pegar el contenido del bloque 'Estilos CSS para Formularios Responsivos (Reutilizable)' aquí
       si no lo tienes en un archivo CSS global enlazado en layouts/app.blade.php. */

    /* Estilos específicos para este formulario si los necesitas */
    .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
    }
    .offline-form-container h2.title {
        text-align: center;
        font-family: Arial Black;
        font-weight: bold;
        font-size: 30px;
        color: #fdffe5;
        text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    }
    .info-visita span {
        color: wheat;
    }
    .button-group-top {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 30px;
    }
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

    .title{
    text-align: center;
    font-family: Arial Black;
    font-weight: bold;
    font-size: 30px;
    color: #fdffe5;
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    margin-bottom: 25px;
}

    /* Estilos para los formularios de área dinámicos */
    .area-form-card {
        background-color: #a5b8a5; /* Un color claro para las tarjetas de formulario */
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        position: relative; /* Para el botón de eliminar */
    }
    .area-form-card .remove-area-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        font-size: 1.2em;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
    .area-form-card .remove-area-btn:hover {
        background-color: #c82333;
    }
    .area-form-card h4 {
        margin-bottom: 20px;
        color: #28a745;
        border-bottom: 1px dashed #729079;
        padding-bottom: 10px;
    }
</style>

<div class="container offline-form-container" style="width: 80%">

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
    @endif 

    <h3 class="title">🌴🌴 Información de plantación - Área 🌴🌴</h3>
    <div class="info-visita mb-4">
        <h3>
            <br>
            Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span> <br>
            Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br>
            Plantación: <span style="color: wheat">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span>
        </h3>
    </div>

    {{-- Selector de sección --}}
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

    {{-- RESUMEN GENERAL FIJO --}}
    <div class="card summary-card mb-4">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">📊 Resumen General de la Plantación</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="summary-item">
                        <label class="fw-bold">Área Total a Estudiar (Ha):</label>
                        <input type="number" step="0.01" id="area_total_finca_hectareas" name="area_total_finca_hectareas" 
                            class="form-control" min="0" readonly 
                            placeholder="Se calculará automáticamente" 
                            value="{{ $areasPenultima->sum('area') }}"> <small class="text-muted">Sumatoria automática de todas las áreas</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-item">
                        <label class="fw-bold">Número Total de Palmas:</label>
                        <input type="number" id="numero_palmas_total_finca" name="numero_palmas_total_finca" 
                               class="form-control" min="0" readonly
                               placeholder="Se calculará automáticamente">
                        <small class="text-muted">Sumatoria automática de todas las palmas</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-item">
                        <label class="fw-bold">Ciclos de Cosecha:</label>
                        <input type="number" id="ciclos_cosecha_total" name="ciclos_cosecha_total" 
                               class="form-control" min="0" required value="{{ $visita->ciclos_cosecha_total ?? '' }}">
                        <small class="text-muted">Ciclos totales de cosecha</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="summary-item">
                        <label class="fw-bold">Producción (Ton/Mes):</label>
                        <input type="number" step="0.01" id="produccion_total" name="produccion_total" 
                               class="form-control" min="0" required>
                        <small class="text-muted">Producción total mensual</small>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <strong>📈 Sumatorias Calculadas:</strong>
                        <span id="sumatoria_info">Agregue áreas para calcular automáticamente</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mostrar las áreas registradas si existen --}}
   {{-- Verificar si hay áreas ACTUALES o PREVIAS --}}
@if ($tieneAreasActuales)
    {{-- Si la visita actual ya tiene áreas, mostrarlas --}}
    <div class="alert alert-success">
        <strong>Áreas ya registradas en esta visita:</strong>
        @foreach ($visita->areas as $area)
            <ul class="mb-0 mt-3 border-top pt-3">
                <li><strong>Variedad:</strong> {{ $area->variedad }}</li>
                <li><strong>Material:</strong> {{ $area->material }}</li>
                <li><strong>Estado:</strong> {{ $area->estado }}</li>
                <li><strong>Año de siembra:</strong> {{ $area->anio_siembra }}</li>
                <li><strong>Área Desarrollo (Ha):</strong> {{ $area->area_palmas_desarrollo_hectareas ?? 'N/A' }}</li>
                <li><strong>Palmas Desarrollo:</strong> {{ $area->numero_palmas_desarrollo ?? 'N/A' }}</li>
                <li><strong>Área Producción (Ha):</strong> {{ $area->area_palmas_produccion_hectareas ?? 'N/A' }}</li>
                <li><strong>Palmas Producción:</strong> {{ $area->numero_palmas_produccion ?? 'N/A' }}</li>
                <li><strong>Aplica Orden Plantis:</strong> {{ $area->aplica_orden_plantis ? 'Sí' : 'No' }}</li>
                @if ($area->aplica_orden_plantis)
                    <li><strong>Orden Plantis N°:</strong> {{ $area->orden_plantis_numero ?? 'N/A' }}</li>
                    <li><strong>Número de Plantas (Orden Plantis):</strong> {{ $area->numero_plantas_orden_plantis ?? 'N/A' }}</li>
                    <li><strong>Estado Orden Plantis:</strong> {{ $area->estado_oren_plantis ?? 'N/A' }}</li>
                @endif
                <div class="d-flex justify-content-end mt-2">
                    <a href="{{ route('areas.edit', $area->id) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                </div>
            </ul>
        @endforeach
    </div>
    <div class="button-group-top">
        <a href="{{ route('fertilizaciones.create', ['visita_id' => $visita->id]) }}" class="btn btn-primary">
            ➡️ Continuar con fertilización
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">⬅️ Volver al Dashboard</a>
    </div>

@elseif ($tieneAreasPrevias)

    {{-- Si NO hay áreas actuales pero SÍ hay de la visita anterior, mostrarlas para precargar --}}
    <div class="alert alert-info">
        <strong>📋 Áreas de la visita anterior (Visita ID: {{ $areasPenultima->first()->visita_id ?? 'N/A' }}):</strong>
        <p class="mb-2">Puedes usar estos datos como base para esta visita.</p>
        @foreach ($areasPenultima as $area)
            <ul class="mb-0 mt-3 border-top pt-3" style="background-color: #e7f3ff; padding: 10px; border-radius: 5px;">
                <li><strong>Variedad:</strong> {{ $area->variedad }}</li>
                <li><strong>Material:</strong> {{ $area->material }}</li>
                <li><strong>Estado:</strong> {{ $area->estado }}</li>
                <li><strong>Año de siembra:</strong> {{ $area->anio_siembra }}</li>
                <li><strong>Área Desarrollo (Ha):</strong> {{ $area->area_palmas_desarrollo_hectareas ?? 'N/A' }}</li>
                <li><strong>Palmas Desarrollo:</strong> {{ $area->numero_palmas_desarrollo ?? 'N/A' }}</li>
                <li><strong>Área Producción (Ha):</strong> {{ $area->area_palmas_produccion_hectareas ?? 'N/A' }}</li>
                <li><strong>Palmas Producción:</strong> {{ $area->numero_palmas_produccion ?? 'N/A' }}</li>
                <li><strong>Aplica Orden Plantis:</strong> {{ $area->aplica_orden_plantis ? 'Sí' : 'No' }}</li>
                @if ($area->aplica_orden_plantis)
                    <li><strong>Orden Plantis N°:</strong> {{ $area->orden_plantis_numero ?? 'N/A' }}</li>
                    <li><strong>Número de Plantas:</strong> {{ $area->numero_plantas_orden_plantis ?? 'N/A' }}</li>
                    <li><strong>Estado Orden Plantis:</strong> {{ $area->estado_oren_plantis ?? 'N/A' }}</li>
                @endif
            </ul>
        @endforeach
    </div>

    {{-- Formulario para añadir nuevas áreas (precargado con datos anteriores) --}}
    <form method="POST" action="{{ route('areas.store') }}" id="areaForm">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">
        
        <input type="hidden" name="area_total_finca_hectareas" id="hidden_area_total_finca_hectareas">
        <input type="hidden" name="numero_palmas_total_finca" id="hidden_numero_palmas_total_finca">
        <input type="hidden" name="ciclos_cosecha_total" id="hidden_ciclos_cosecha_total">
        <input type="hidden" name="produccion_total" id="hidden_produccion_total">

        <div id="areaFormsContainer">
            {{-- Aquí se clonarán los formularios de área --}}
        </div>

        <button type="button" class="btn btn-info mt-3 mb-3" id="addAreaBtn">➕ Añadir otra Área</button>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">Guardar y continuar</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">⬅️ Cancelar</a>
        </div>
    </form>

    {{-- Script para precargar datos de áreas anteriores --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Precargar áreas de la visita anterior
            const areasAnteriores = @json($areasPenultima);
            
            if (areasAnteriores && areasAnteriores.length > 0) {
                areasAnteriores.forEach(area => {
                    addAreaForm({
                        variedad: area.variedad,
                        material: area.material,
                        estado: area.estado,
                        anio_siembra: area.anio_siembra,
                        area_palmas_desarrollo_hectareas: area.area_palmas_desarrollo_hectareas,
                        numero_palmas_desarrollo: area.numero_palmas_desarrollo,
                        area_palmas_produccion_hectareas: area.area_palmas_produccion_hectareas,
                        numero_palmas_produccion: area.numero_palmas_produccion,
                        aplica_orden_plantis: area.aplica_orden_plantis ? '1' : '0',
                        orden_plantis_numero: area.orden_plantis_numero,
                        numero_plantas_orden_plantis: area.numero_plantas_orden_plantis,
                        estado_oren_plantis: area.estado_oren_plantis
                    });
                });
            }
        });
    </script>

@else
    {{-- NO hay áreas actuales NI previas → Formulario vacío --}}
    <div class="alert alert-warning">
        <strong>⚠️ No hay áreas registradas previamente.</strong>
        <p>Comienza agregando la primera área de esta plantación.</p>
    </div>

    <form method="POST" action="{{ route('areas.store') }}" id="areaForm">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">
        
        <input type="hidden" name="area_total_finca_hectareas" id="hidden_area_total_finca_hectareas">
        <input type="hidden" name="numero_palmas_total_finca" id="hidden_numero_palmas_total_finca">
        <input type="hidden" name="ciclos_cosecha_total" id="hidden_ciclos_cosecha_total">
        <input type="hidden" name="produccion_total" id="hidden_produccion_total">

        <div id="areaFormsContainer"></div>

        <button type="button" class="btn btn-info mt-3 mb-3" id="addAreaBtn">➕ Añadir otra Área</button>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">Guardar y continuar</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">⬅️ Cancelar</a>
        </div>
    </form>
@endif
</div>

<style>
.summary-card {
    border: 2px solid #28a745;
    border-radius: 10px;
}

.summary-item {
    margin-bottom: 15px;
}

.summary-item label {
    color: #28a745;
    font-size: 0.9rem;
}

.area-form-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    background: #f9f9f9;
    position: relative;
}

.area-form-card h4 {
    color: #28a745;
    margin-bottom: 15px;
}

.remove-area-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
}

.dynamic-fields {
    background: #e9f7ef;
    padding: 15px;
    border-radius: 5px;
    margin-top: 10px;
    border-left: 4px solid #28a745;
}

.summary-item input[readonly] {
    background-color: #e9ecef;
    cursor: not-allowed;
}
</style>

<script>
    let areaIndex = 0;

    // Función para actualizar sumatorias
    function updateSummaries() {
        let totalAreaHectareas = 0;
        let totalPalmas = 0;
        let totalAreaDesarrolloHa = 0;
        let totalAreaProduccionHa = 0;
        let totalPalmasDesarrollo = 0;
        let totalPalmasProduccion = 0;
        let totalPlantasOrdenPlantis = 0;
        let areasCount = 0;

        // Calcular sumatorias de todos los formularios
        document.querySelectorAll('.area-form-card').forEach(card => {
            const estado = card.querySelector('select[name*="[estado]"]')?.value;
            let areaHa = 0;
            let palmas = 0;
            let areaDesarrolloHa = 0;
            let areaProduccionHa = 0;
            let palmasDesarrollo = 0;
            let palmasProduccion = 0; 
            let plantasOrdenPlantis = 0;

            if (estado === 'desarrollo') {
                areaHa = parseFloat(card.querySelector('input[name*="[area_palmas_desarrollo_hectareas]"]')?.value) || 0;
                palmas = parseFloat(card.querySelector('input[name*="[numero_palmas_desarrollo]"]')?.value) || 0;
                areaDesarrolloHa = areaHa;
                palmasDesarrollo = palmas;
            } else if (estado === 'produccion') {
                areaHa = parseFloat(card.querySelector('input[name*="[area_palmas_produccion_hectareas]"]')?.value) || 0;
                palmas = parseFloat(card.querySelector('input[name*="[numero_palmas_produccion]"]')?.value) || 0;
                areaProduccionHa = areaHa;
                palmasProduccion = palmas;
            }

            const aplicaOrdenPlantis = card.querySelector('select[name*="[aplica_orden_plantis]"]')?.value;
            if (aplicaOrdenPlantis === '1') {
                plantasOrdenPlantis = parseFloat(card.querySelector('input[name*="[numero_plantas_orden_plantis]"]')?.value) || 0;
                totalPlantasOrdenPlantis += plantasOrdenPlantis;
            }
            
            totalAreaHectareas += areaHa;
            totalPalmas += palmas;
            totalAreaDesarrolloHa += areaDesarrolloHa;
            totalAreaProduccionHa += areaProduccionHa;
            totalPalmasDesarrollo += palmasDesarrollo;
            totalPalmasProduccion += palmasProduccion;
            areasCount++;
        });

        totalPalmas += totalPlantasOrdenPlantis;

        // Obtener valores de los campos manuales del resumen
        const ciclosCosechaTotal = document.getElementById('ciclos_cosecha_total')?.value || 0;
        const produccionTotal = document.getElementById('produccion_total')?.value || 0;

        // Actualizar campos del resumen (automáticamente)
        document.getElementById('area_total_finca_hectareas').value = totalAreaHectareas.toFixed(2);
        document.getElementById('numero_palmas_total_finca').value = totalPalmas;

        // Actualizar campos ocultos para envío al servidor
        document.getElementById('hidden_area_total_finca_hectareas').value = totalAreaHectareas.toFixed(2);
        document.getElementById('hidden_numero_palmas_total_finca').value = totalPalmas;
        document.getElementById('hidden_ciclos_cosecha_total').value = ciclosCosechaTotal;
        document.getElementById('hidden_produccion_total').value = produccionTotal;

        // Actualizar información de sumatorias
        const sumatoriaInfo = document.getElementById('sumatoria_info');
        if (areasCount > 0) {
            sumatoriaInfo.innerHTML = `
                <strong>📊 Resumen Detallado:</strong><br>
                • Áreas registradas: ${areasCount}<br>
                • Total Área: ${totalAreaHectareas.toFixed(2)} Ha<br>
                • Total Palmas: ${totalPalmas.toLocaleString()}<br>
                • Área Desarrollo: ${totalAreaDesarrolloHa.toFixed(2)} Ha (${totalPalmasDesarrollo.toLocaleString()} palmas)<br>
                • Área Producción: ${totalAreaProduccionHa.toFixed(2)} Ha (${totalPalmasProduccion.toLocaleString()} palmas)<br>
                • Ciclos Cosecha: ${ciclosCosechaTotal}<br>
                • Producción Total: ${produccionTotal} Ton/Mes
            `;
        } else {
            sumatoriaInfo.textContent = 'Agregue áreas para calcular automáticamente';
        }
    }

    // Función para mostrar/ocultar campos según estado
    function toggleEstadoFields(selectElement, index) {
        const estado = selectElement.value;
        const desarrolloFields = document.getElementById(`desarrolloFields_${index}`);
        const produccionFields = document.getElementById(`produccionFields_${index}`);

        if (estado === 'desarrollo') {
            desarrolloFields.style.display = 'block';
            produccionFields.style.display = 'none';
            // Limpiar campos de producción
            document.getElementById(`area_palmas_produccion_hectareas_${index}`).value = '';
            document.getElementById(`numero_palmas_produccion_${index}`).value = '';
        } else if (estado === 'produccion') {
            desarrolloFields.style.display = 'none';
            produccionFields.style.display = 'block';
            // Limpiar campos de desarrollo
            document.getElementById(`area_palmas_desarrollo_hectareas_${index}`).value = '';
            document.getElementById(`numero_palmas_desarrollo_${index}`).value = '';
        }
        
        updateSummaries(); // Actualizar sumatorias después de cambiar estado
    }

    // Función para clonar y añadir un nuevo formulario de área
    // Función para clonar y añadir un nuevo formulario de área
function addAreaForm(initialData = {}) {
    const container = document.getElementById('areaFormsContainer');
    const template = document.createElement('div');
    template.className = 'area-form-card';
    template.setAttribute('data-index', areaIndex);

    template.innerHTML = `
        <h4>Nueva Área #${areaIndex + 1}</h4>
        ${areaIndex > 0 ? '<button type="button" class="remove-area-btn" onclick="removeAreaForm(this)">✖️</button>' : ''}
        
        <div class="mb-3">
            <label for="variedad_${areaIndex}">Variedad:</label>
            <select name="areas[${areaIndex}][variedad]" id="variedad_${areaIndex}" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="guinense">Guineense</option>
                <option value="hibrido">Híbrido</option>
            </select>
        </div> 
        
        <div class="mb-3">
            <label for="material_${areaIndex}">Material:</label>
            <select name="areas[${areaIndex}][material]" id="material_${areaIndex}" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="cuari x lame">cuari x lame</option>
                <option value="cuari x lame – fortuna">cuari x lame – fortuna</option>
                <option value="manicore">manicore</option>
                <option value="taisha">taisha</option>
                <option value="amazon">amazon</option>
                <option value="unipalma">unipalma</option>
                <option value="deli x lame">deli x lame</option>
                <option value="deli x yangambi">deli x yangambi</option>
                <option value="kigoma">kigoma</option>
                <option value=" # s"> # s</option>
                <option value="# pc"># pc</option>
                <option value="# c"># c</option>
                <option value="paraiso">paraiso</option>
                <option value="otro">otro</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="estado_${areaIndex}">Estado:</label>
            <select name="areas[${areaIndex}][estado]" id="estado_${areaIndex}" class="form-control" required onchange="toggleEstadoFields(this, ${areaIndex})">
                <option value="">Seleccione</option>
                <option value="desarrollo">Desarrollo</option>
                <option value="produccion">Producción</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="anio_siembra_${areaIndex}">Año de siembra:</label>
            <input type="number" 
                name="areas[${areaIndex}][anio_siembra]" 
                id="anio_siembra_${areaIndex}"                
                placeholder="ej. 2025" 
                required>
        </div>

        {{-- Campos dinámicos según estado --}}
        <div id="desarrolloFields_${areaIndex}" class="dynamic-fields" style="display: none;">
            <div class="mb-3">
                <label for="area_palmas_desarrollo_hectareas_${areaIndex}">Área de palmas en desarrollo (Hectáreas):</label>
                <input type="number" step="0.01" name="areas[${areaIndex}][area_palmas_desarrollo_hectareas]" 
                    id="area_palmas_desarrollo_hectareas_${areaIndex}" class="form-control" min="0"
                    oninput="updateSummaries()">
            </div>
            <div class="mb-3">
                <label for="numero_palmas_desarrollo_${areaIndex}">Número de palmas (Desarrollo):</label>
                <input type="number" name="areas[${areaIndex}][numero_palmas_desarrollo]" 
                    id="numero_palmas_desarrollo_${areaIndex}" class="form-control" min="0"
                    oninput="updateSummaries()">
            </div>
        </div>

        <div id="produccionFields_${areaIndex}" class="dynamic-fields" style="display: none;">
            <div class="mb-3">
                <label for="area_palmas_produccion_hectareas_${areaIndex}">Área de palmas en producción (Hectáreas):</label>
                <input type="number" step="0.01" name="areas[${areaIndex}][area_palmas_produccion_hectareas]" 
                    id="area_palmas_produccion_hectareas_${areaIndex}" class="form-control" min="0"
                    oninput="updateSummaries()">
            </div>
            <div class="mb-3">
                <label for="numero_palmas_produccion_${areaIndex}">Número de palmas (Producción):</label>
                <input type="number" name="areas[${areaIndex}][numero_palmas_produccion]" 
                    id="numero_palmas_produccion_${areaIndex}" class="form-control" min="0"
                    oninput="updateSummaries()">
            </div>
        </div>

        {{-- SOLO MANTENER EL CAMPO 'area' QUE ES NECESARIO --}}
        <input type="hidden" name="areas[${areaIndex}][area]" value="0">

        {{-- Orden Plantis (siempre visible) --}}
        <div class="mb-3">
            <label for="aplica_orden_plantis_${areaIndex}">¿Aplica Orden Plantis?</label>
            <select name="areas[${areaIndex}][aplica_orden_plantis]" id="aplica_orden_plantis_${areaIndex}" 
                    class="form-control" required onchange="toggleOrdenPlantisFields(this, ${areaIndex})">
                <option value="">Seleccione</option>
                <option value="0">No</option>
                <option value="1">Sí</option>
            </select>
        </div>

        <div id="ordenPlantisFields_${areaIndex}" style="display: none;">
            <div class="mb-3">
                <input type="hidden" name="areas[${areaIndex}][orden_plantis_numero] value="1" 
                    id="orden_plantis_numero_${areaIndex}" class="form-control" placeholder="Número de orden">
            </div>
            <div class="mb-3">
                <label for="numero_plantas_orden_plantis_${areaIndex}">Número de Plantas (Orden Plantis):</label>
                <input type="number" name="areas[${areaIndex}][numero_plantas_orden_plantis]" 
                    id="numero_plantas_orden_plantis_${areaIndex}" class="form-control" min="0"
                    oninput="updateSummaries()">
            </div>
            <div class="mb-3">
                <label for="estado_oren_plantis_${areaIndex}">Estado Orden Plantis:</label>
                <select name="areas[${areaIndex}][estado_oren_plantis]" id="estado_oren_plantis_${areaIndex}" class="form-control">
                    <option value="">Seleccione</option>
                    <option value="desarrollo">Desarrollo</option>
                    <option value="produccion">Producción</option>
                </select>
            </div>
        </div>
    `;

    container.appendChild(template);

    // Resto del código permanece igual...
    // Pre-llenar datos si se proporcionan
    if (Object.keys(initialData).length > 0) {
        for (const key in initialData) {
            const input = template.querySelector(`[name="areas[${areaIndex}][${key}]"]`);
            if (input) {
                if (input.type === 'select-one') {
                    const optionExists = Array.from(input.options).some(option => option.value === String(initialData[key]));
                    if (optionExists) {
                        input.value = initialData[key];
                    }
                } else {
                    input.value = initialData[key];
                }
            }
        }
    }

    // Ejecutar funciones de toggle para el nuevo formulario
    const estadoSelect = document.getElementById(`estado_${areaIndex}`);
    const aplicaOrdenPlantisSelect = document.getElementById(`aplica_orden_plantis_${areaIndex}`);
    
    if (estadoSelect && estadoSelect.value) {
        toggleEstadoFields(estadoSelect, areaIndex);
    }
    if (aplicaOrdenPlantisSelect && aplicaOrdenPlantisSelect.value === '1') {
        toggleOrdenPlantisFields(aplicaOrdenPlantisSelect, areaIndex);
    }

    areaIndex++;
    updateSummaries(); // Actualizar sumatorias
}

    // Función para eliminar un formulario de área
    function removeAreaForm(button) {
        const card = button.closest('.area-form-card');
        if (card) {
            card.remove();
            updateSummaries(); // Actualizar sumatorias después de eliminar
        }
    }

    // Función para mostrar/ocultar campos de Orden Plantis
    function toggleOrdenPlantisFields(selectElement, index) {
        const aplicaOrdenPlantis = selectElement.value;
        const ordenPlantisFields = document.getElementById(`ordenPlantisFields_${index}`);
        const ordenPlantisNumero = document.getElementById(`orden_plantis_numero_${index}`);
        const numeroPlantasOrdenPlantis = document.getElementById(`numero_plantas_orden_plantis_${index}`);
        const estadoOrdenPlantis = document.getElementById(`estado_oren_plantis_${index}`);

        if (aplicaOrdenPlantis === '1') {
            ordenPlantisFields.style.display = 'block';
            if (ordenPlantisNumero) ordenPlantisNumero.setAttribute('required', 'required');
            if (numeroPlantasOrdenPlantis) numeroPlantasOrdenPlantis.setAttribute('required', 'required');
            if (estadoOrdenPlantis) estadoOrdenPlantis.setAttribute('required', 'required');
        } else {
            ordenPlantisFields.style.display = 'none';
            if (ordenPlantisNumero) ordenPlantisNumero.removeAttribute('required');
            if (numeroPlantasOrdenPlantis) numeroPlantasOrdenPlantis.removeAttribute('required');
            if (estadoOrdenPlantis) estadoOrdenPlantis.removeAttribute('required');
            if (ordenPlantisNumero) ordenPlantisNumero.value = '';
            if (numeroPlantasOrdenPlantis) numeroPlantasOrdenPlantis.value = '';
            if (estadoOrdenPlantis) estadoOrdenPlantis.value = '';
        }
    }

    // Inicializar cuando el DOM esté listo
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM cargado - Inicializando formularios');
        
        // Añadir el primer formulario de área al cargar la página
        addAreaForm();

        // Manejar el botón "Añadir otra Área"
        const addAreaBtn = document.getElementById('addAreaBtn');
        if (addAreaBtn) {
            addAreaBtn.addEventListener('click', function() {
                console.log('Botón clickeado - Añadiendo nueva área');
                addAreaForm();
            });
        }

        // Actualizar sumatorias cuando cambien los campos manuales
        document.getElementById('ciclos_cosecha_total')?.addEventListener('input', updateSummaries);
        document.getElementById('produccion_total')?.addEventListener('input', updateSummaries);

        // Validación del formulario antes del envío
        const areaForm = document.getElementById('areaForm');
        if (areaForm) {
            areaForm.addEventListener('submit', function(e) {
                if (!validateFormBeforeSubmit()) {
                    e.preventDefault();
                }
            });
        }

        // Si hay errores de validación, rellenar los formularios con los datos antiguos
        @if ($errors->any() && old('areas'))
            document.getElementById('areaFormsContainer').innerHTML = '';
            areaIndex = 0;

            @foreach (old('areas') as $oldArea)
                addAreaForm(@json($oldArea));
            @endforeach

            // Re-evaluar el estado de los campos condicionales
            document.querySelectorAll('.area-form-card').forEach((card, idx) => {
                const estadoSelect = card.querySelector(`#estado_${idx}`);
                const ordenPlantisSelect = card.querySelector(`#aplica_orden_plantis_${idx}`);
                if (estadoSelect && estadoSelect.value) toggleEstadoFields(estadoSelect, idx);
                if (ordenPlantisSelect && ordenPlantisSelect.value === '1') toggleOrdenPlantisFields(ordenPlantisSelect, idx);
            });

            // Rellenar campos del resumen si existen
            @if(old('ciclos_cosecha_total'))
                document.getElementById('ciclos_cosecha_total').value = {{ old('ciclos_cosecha_total') }};
            @endif
            @if(old('produccion_total'))
                document.getElementById('produccion_total').value = {{ old('produccion_total') }};
            @endif

            updateSummaries(); // Forzar actualización de sumatorias
        @endif
    });

    // Función de validación
    function validateFormBeforeSubmit() {
        let isValid = true;
        const errorMessages = [];
        
        document.querySelectorAll('.area-form-card').forEach((card, index) => {
            const estado = card.querySelector('select[name*="[estado]"]')?.value;
            const areaIndex = index;
            
            if (!estado) {
                isValid = false;
                errorMessages.push(`El estado del área #${areaIndex + 1} es obligatorio`);
            } else if (estado === 'desarrollo') {
                const areaHa = card.querySelector('input[name*="[area_palmas_desarrollo_hectareas]"]')?.value;
                const numPalmas = card.querySelector('input[name*="[numero_palmas_desarrollo]"]')?.value;
                
                if (!areaHa || areaHa <= 0) {
                    isValid = false;
                    errorMessages.push(`El área de desarrollo (Ha) del área #${areaIndex + 1} es obligatoria`);
                }
                if (!numPalmas || numPalmas <= 0) {
                    isValid = false;
                    errorMessages.push(`El número de palmas en desarrollo del área #${areaIndex + 1} es obligatorio`);
                }
            } else if (estado === 'produccion') {
                const areaHa = card.querySelector('input[name*="[area_palmas_produccion_hectareas]"]')?.value;
                const numPalmas = card.querySelector('input[name*="[numero_palmas_produccion]"]')?.value;
                
                if (!areaHa || areaHa <= 0) {
                    isValid = false;
                    errorMessages.push(`El área de producción (Ha) del área #${areaIndex + 1} es obligatoria`);
                }
                if (!numPalmas || numPalmas <= 0) {
                    isValid = false;
                    errorMessages.push(`El número de palmas en producción del área #${areaIndex + 1} es obligatorio`);
                }
            }
        });
        
        if (!isValid) {
            alert('Por favor complete todos los campos requeridos:\n\n' + errorMessages.join('\n'));
            return false;
        }
        return true;
    }
</script>
@endsection
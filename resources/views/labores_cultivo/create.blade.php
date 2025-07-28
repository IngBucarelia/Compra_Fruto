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
        margin-left: -35px !important; /* Centra el contenedor */
        
        margin-top: 25px; /* Margen superior para separación */
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
.area-info-card, .fertilizacion-info-card, .polinizacion-info-card, .sanidad-info-card, .suelo-info-card {
    background-color: #f0fdf0;
    border: 1px solid #d4edda;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.area-info-card ul, .fertilizacion-info-card ul, .polinizacion-info-card ul, .sanidad-info-card ul, .suelo-info-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.area-info-card li, .fertilizacion-info-card li, .polinizacion-info-card li, .sanidad-info-card li, .suelo-info-card li {
    padding: 5px 0;
    border-bottom: 1px dashed #e2e6ea;
}
.area-info-card li:last-child, .fertilizacion-info-card li:last-child, .polinizacion-info-card li:last-child, .sanidad-info-card li:last-child, .suelo-info-card li:last-child {
    border-bottom: none;
}

/* Estilos para el formulario dinámico de labores (ahora mostrando todos los campos) */
.labor-form-block {
    border: 1px solid #a7d9b4; /* Borde más distintivo para cada bloque de formulario */
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px; /* Más espacio entre bloques */
    background-color: #f0fff0; /* Fondo más claro para los bloques */
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.labor-form-block .remove-block-btn {
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px; /* Más grande */
    height: 30px; /* Más grande */
    font-size: 1.1em;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    position: absolute; /* Posicionamiento absoluto */
    top: 10px;
    right: 10px;
    z-index: 10; /* Asegura que esté por encima de otros elementos */
}
.labor-form-block .remove-block-btn:hover {
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
@media (max-width: 767.98px) {
    .container {
        padding: 15px;
        margin-top: 15px;
        border-radius: 0;
        box-shadow: none;
        width: 100%;
        max-width: none;
        margin-left: 0; /* Asegura que no haya margen negativo en móvil */
    }
    .title {
        font-size: 1.8em;
        margin-bottom: 20px;
    }
    .form-control {
        padding: 10px;
        font-size: 0.95em;
    }
    .btn {
        width: 100%;
        padding: 12px 15px;
        font-size: 1em;
    }
    .button-group {
        flex-direction: column;
        gap: 10px;
    }
}
/* Media Query para pantallas medianas y grandes (desktop/tablet) */
@media (min-width: 768px) {
    .button-group {
        flex-direction: row;
        justify-content: flex-start;
        gap: 20px;
    }
}
</style>
<div class="container" >

    <h3 class="title">🚜 Información de plantación - Labores de Cultivo</h3><h3>🚜<br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span> <br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
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


    <div class="accordion mb-4" id="acordeonLabores">

        {{-- Área --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-expanded="true">
                    📍 Área(s) registrada(s)
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" data-bs-parent="#acordeonLabores">
                <div class="accordion-body">
                    @if ($visita->areas->count() > 0)
                        @foreach ($visita->areas as $area)
                            <div class="area-info-card mb-3">
                                <h5>Área #{{ $loop->index + 1 }} - Material: {{ $area->material }}</h5>
                                <ul>
                                    <li><strong>Material:</strong> {{ $area->material }}</li>
                                    <li><strong>Estado:</strong> {{ $area->estado }}</li>
                                    <li><strong>Año siembra:</strong> {{ $area->anio_siembra }}</li>
                                    <li><strong>Área (m²):</strong> {{ $area->area }}</li>
                                    <li><strong>Área total en finca (Hectáreas):</strong> {{ $area->area_total_finca_hectareas ?? 'N/A' }}</li>
                                    <li><strong>Número de palmas (Total Finca):</strong> {{ $area->numero_palmas_total_finca ?? 'N/A' }}</li>
                                    <li><strong>Área de palmas en desarrollo (Hectáreas):</strong> {{ $area->area_palmas_desarrollo_hectareas ?? 'N/A' }}</li>
                                    <li><strong>Número de palmas (Desarrollo):</strong> {{ $area->numero_palmas_desarrollo ?? 'N/A' }}</li>
                                    <li><strong>Área de palmas en producción (Hectáreas):</strong> {{ $area->area_palmas_produccion_hectareas ?? 'N/A' }}</li>
                                    <li><strong>Número de palmas (Producción):</strong> {{ $area->numero_palmas_produccion ?? 'N/A' }}</li>
                                    <li><strong>Ciclos de Cosecha:</strong> {{ $area->ciclos_cosecha ?? 'N/A' }}</li>
                                    <li><strong>Producción (Toneladas por Mes):</strong> {{ $area->produccion_toneladas_por_mes ?? 'N/A' }}</li>
                                    <li><strong>Aplica Orden Plantis:</strong> {{ $area->aplica_orden_plantis ? 'Sí' : 'No' }}</li>
                                    @if ($area->aplica_orden_plantis)
                                        <li><strong>Orden Plantis N°:</strong> {{ $area->orden_plantis_numero ?? 'N/A' }}</li>
                                        <li><strong>Número de Plantas (Orden Plantis):</strong> {{ $area->numero_plantas_orden_plantis ?? 'N/A' }}</li>
                                        <li><strong>Estado Orden Plantis:</strong> {{ $area->estado_oren_plantis ?? 'N/A' }}</li>
                                    @endif
                                </ul>
                                <div class="d-flex justify-content-end mt-2">
                                    <a href="{{ route('areas.edit', $area->id) }}" class="btn btn-warning btn-sm">✏️ Editar esta área</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No se ha registrado información de área.</p>
                    @endif
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
                                                <th>Talco</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($poli->fecha)->format('d/m/Y') }}</td>
                                                <td class="text-center">{{ $poli->n_pases }}</td>
                                                <td class="text-center">{{ $poli->ciclos_ronda }}</td>
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
                    🧪 Sanidad registrada
                </button>
            </h2>
            <div id="collapseSanidad" class="accordion-collapse collapse" data-bs-parent="#acordeonLabores">
                <div class="accordion-body">
                    @if ($visita->sanidades->count() > 0)
                        @foreach ($visita->sanidades as $sanidad)
                            <div class="sanidad-info-card mb-3">
                                <h5>Sanidad #{{ $loop->index + 1 }}</h5>
                                <ul>
                                    <li><strong>Opsophanes:</strong> {{ $sanidad->opsophanes ?? '-' }}%</li>
                                    <li><strong>Pudrición cogollo:</b> {{ $sanidad->pudricion_cogollo ?? '-' }}%</li>
                                    <li><strong>Raspador:</strong> {{ $sanidad->raspador ?? '-' }}%</li>
                                    <li><strong>Palmarum:</strong> {{ $sanidad->palmarum ?? '-' }}%</li>
                                    <li><strong>Strategus:</strong> {{ $sanidad->strategus ?? '-' }}%</li>
                                    <li><strong>Leptopharsa:</strong> {{ $sanidad->leptopharsa ?? '-' }}%</li>
                                    <li><strong>Pestalotiopsis:</strong> {{ $sanidad->pestalotiopsis ?? '-' }}%</li>
                                    <li><strong>Pudrición basal:</strong> {{ $sanidad->pudricion_basal ?? '-' }}%</li>
                                    <li><strong>Pudrición estipe:</strong> {{ $sanidad->pudricion_estipe ?? '-' }}%</li>
                                    <li><strong>Otros:</strong> {{ $sanidad->otros ?? '-' }}</li>
                                    <li><strong>Observaciones:</strong> {{ $sanidad->observaciones ?? 'Sin observaciones' }}</li>
                                </ul>
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

        {{-- Suelo --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSuelo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSuelo">
                    🧬 Análisis de Suelo
                </button>
            </h2>
            <div id="collapseSuelo" class="accordion-collapse collapse" data-bs-parent="#acordeonLabores">
                <div class="accordion-body">
                    @if ($visita->suelo)
                        <div class="suelo-info-card mb-3">
                            <ul>
                                <li><strong>Análisis foliar:</strong> {{ ucfirst($visita->suelo->analisis_foliar) }}</li>
                                <li><strong>Análisis suelo:</strong> {{ ucfirst($visita->suelo->analisis_suelo) }}</li>
                                <li><strong>Tipo de suelo:</strong> {{ ucfirst($visita->suelo->tipo_suelo) }}</li>
                            </ul>
                            <div class="d-flex justify-content-end mt-2">
                                <a href="{{ route('suelos.edit', $visita->suelo->id) }}" class="btn btn-warning btn-sm">✏️ Editar este análisis</a>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado análisis de suelo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <h3>🚜 Formulario Labores de Cultivo </h3>

    {{-- Formulario Labores de Cultivo --}}
    <form id="laboresCultivoForm" method="POST" action="{{ route('labores_cultivo.store') }}">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        {{-- Contenedor para los bloques de formularios de labores dinámicos --}}
        <div id="labores-forms-container">
            {{-- Los bloques de formularios se añadirán aquí mediante JavaScript --}}
        </div>

        <button type="button" class="btn btn-info mb-3" onclick="addLaborFormBlock()">+ Añadir Formulario de Labor</button>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">💾 Guardar labores</button>
            <a href="{{ route('evaluacion.create', ['visita_id' => $visita->id]) }}" class="btn btn-success">
                ➕ Registrar evaluación de cosecha
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

    {{-- Mostrar registros si existen --}}
    @if ($visita->laboresCultivo->count() > 0)
        <hr>
        <h4 class="title">📋 Labores registradas</h4>

        @php
            $laboresLabels = [
                'polinizacion' => 'Polinización',
                'limpieza_calle' => 'Limpieza de calle',
                'limpieza_plato' => 'Limpieza de plato',
                'poda' => 'Poda',
                'fertilizacion' => 'Fertilización',
                'enmiendas' => 'Enmiendas',
                'ubicacion_tusa_fibra' => 'Ubicación tusa/fibra',
                'ubicacion_hoja' => 'Ubicación hoja en Barrera',
                'lugar_ubicacion_hoja' => 'Ubicación hoja en Plato',
                'plantas_nectariferas' => 'Plantas nectaríferas',
                'cobertura' => 'Cobertura',
                'labor_cosecha' => 'Labor cosecha',
                'calidad_fruta' => 'Calidad fruta',
                'recoleccion_fruta' => 'Recolección fruta',
                'drenajes' => 'Drenajes',
            ];
        @endphp

        @foreach ($visita->laboresCultivo as $laborEntry)
            <ul class="list-group mb-4 labor-form-block">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Tipo de Planta</span>
                    <strong>{{ ucfirst($laborEntry->tipo_planta ?? 'N/A') }}</strong>
                </li>
                <li class="list-group-item">
                    <span><strong>Observaciones:</strong></span>
                    <p>{{ $laborEntry->observaciones ?? 'No registradas' }}</p>
                </li>
                @foreach ($laboresLabels as $campo => $label)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $label }}</span>
                        <strong>{{ $laborEntry->$campo ?? '0' }}%</strong>
                    </li>
                @endforeach
                <div class="button-group mt-2 d-flex justify-content-end">
                    <a href="{{ route('labores_cultivo.edit', $laborEntry->id) }}" class="btn btn-warning btn-sm me-2">
                        ✏️ Editar este registro
                    </a>
                    <form method="POST" action="{{ route('labores_cultivo.destroy', $laborEntry->id) }}" class="d-inline" onsubmit="return confirm('¿Deseas eliminar este registro de Labor de Cultivo?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">🗑️ Eliminar </button>
                    </form>
                </div>
            </ul>
        @endforeach
        <div class="button-group">
            <a href="{{ route('visitas.show', $visita->id) }}" class="btn btn-secondary">
                ⬅️ Volver al detalle de la visita
            </a>
        </div>
    @else
        <p class="text-muted text-center mt-4">No se han registrado labores de cultivo aún.</p>
    @endif

</div>

<script>
    // Mapeo de nombres de labores a los nombres de los campos de la base de datos
    const laborFields = [
        'polinizacion', 'limpieza_calle', 'limpieza_plato', 'poda', 'fertilizacion',
        'enmiendas', 'ubicacion_tusa_fibra', 'ubicacion_hoja', 'lugar_ubicacion_hoja',
        'plantas_nectariferas', 'cobertura', 'labor_cosecha', 'calidad_fruta',
        'recoleccion_fruta', 'drenajes'
    ];

    // Para mantener un índice único para cada bloque de formulario dinámico
    let formBlockIndex = 0;

    /**
     * Añade un nuevo bloque de formulario de labores completo.
     * @param {Object} [data={}] Objeto con datos para precargar los campos.
     */
    function addLaborFormBlock(data = {}) {
        const container = document.getElementById('labores-forms-container');
        const block = document.createElement('div');
        block.classList.add('labor-form-block');
        block.setAttribute('data-index', formBlockIndex); // Identificador único para el bloque

        let laborInputsHtml = '';
        laborFields.forEach(field => {
            const label = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()); // Formato de etiqueta
            const value = data[field] !== undefined ? data[field] : ''; // Precargar valor
            laborInputsHtml += `
                <div class="mb-3">
                    <label for="labores_${formBlockIndex}_${field}" class="form-label">${label} (%):</label>
                    <input type="number" name="labores[${formBlockIndex}][${field}]" id="labores_${formBlockIndex}_${field}" class="form-control" min="0" max="100" value="${value}">
                </div>
            `;
        });

        const tipoPlantaValue = data.tipo_planta !== undefined ? data.tipo_planta : '';
        const observacionesValue = data.observaciones !== undefined ? data.observaciones : '';

        block.innerHTML = `
            <button type="button" class="remove-block-btn" onclick="removeLaborFormBlock(this)">✖️</button>
            <div class="mb-3">
                <label for="labores_${formBlockIndex}_tipo_planta" class="form-label">Tipo de Planta:</label>
                <select name="labores[${formBlockIndex}][tipo_planta]" id="labores_${formBlockIndex}_tipo_planta" class="form-select" required>
                    <option value="">Seleccione el tipo de planta</option>
                    <option value="guinense" ${tipoPlantaValue === 'guinense' ? 'selected' : ''}>Guinense</option>
                    <option value="hibrido" ${tipoPlantaValue === 'hibrido' ? 'selected' : ''}>Híbrido</option>
                </select>
            </div>
            ${laborInputsHtml}
            <div class="mb-3">
                <label for="labores_${formBlockIndex}_observaciones" class="form-label">Observaciones (Opcional):</label>
                <textarea name="labores[${formBlockIndex}][observaciones]" id="labores_${formBlockIndex}_observaciones" class="form-control" rows="2">${observacionesValue}</textarea>
            </div>
        `;
        container.appendChild(block);
        formBlockIndex++;
    }

    /**
     * Elimina un bloque de formulario de labores.
     * @param {HTMLButtonElement} button El botón "X" que fue clickeado.
     */
    function removeLaborFormBlock(button) {
        if (confirm('¿Estás seguro de que quieres eliminar este bloque de formulario de labor?')) {
            button.closest('.labor-form-block').remove();
            // No es necesario reindexar los nombres de los campos aquí si el backend los procesa como un array.
            // Si el backend espera índices secuenciales, se necesitaría una lógica de reindexación más compleja.
            // Para Laravel, `labores[]` suele manejar esto bien.
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const existingLabores = @json($visita->laboresCultivo); // Esto ahora será una colección (array)

        if (existingLabores && existingLabores.length > 0) {
            // Si hay registros existentes, precargarlos
            existingLabores.forEach(laborEntry => {
                addLaborFormBlock(laborEntry);
            });
        } else {
            // Si no hay registros existentes, añadir un bloque vacío por defecto
            addLaborFormBlock();
        }
    });
</script>
@endsection

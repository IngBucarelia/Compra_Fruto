@extends('layouts.app')

@section('content')

<style>
/* Estilos generales del contenedor y título */
.container{
    background-color: rgba(129, 165, 114, 0.929);
    padding: 20px;
    border-radius: 8px; /* Añadido para consistencia */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Añadido para consistencia */
    max-width: 800px; /* Limita el ancho en pantallas muy grandes */
    margin-left: auto; /* Centra el contenedor */
    margin-right: auto; /* Centra el contenedor */
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
.area-info-card, .fertilizacion-info-card, .polinizacion-info-card, .sanidad-info-card, .suelo-info-card, .labores-info-card, .evaluacion-info-card {
    background-color: #f0fdf0;
    border: 1px solid #d4edda;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.area-info-card ul, .fertilizacion-info-card ul, .polinizacion-info-card ul, .sanidad-info-card ul, .suelo-info-card ul, .labores-info-card ul, .evaluacion-info-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.area-info-card li, .fertilizacion-info-card li, .polinizacion-info-card li, .sanidad-info-card li, .suelo-info-card li, .labores-info-card li, .evaluacion-info-card li {
    padding: 5px 0;
    border-bottom: 1px dashed #e2e6ea;
}
.area-info-card li:last-child, .fertilizacion-info-card li:last-child, .polinizacion-info-card li:last-child, .sanidad-info-card li:last-child, .suelo-info-card li:last-child, .labores-info-card li:last-child, .evaluacion-info-card li:last-child {
    border-bottom: none;
}

/* Estilos para el formulario dinámico de evaluación */
.evaluacion-form-block {
    border: 1px solid #d4edda;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    background-color: #f0fff0;
    position: relative;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.evaluacion-form-block .remove-block-btn {
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    font-size: 1.1em;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 10;
}
.evaluacion-form-block .remove-block-btn:hover {
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
        margin-left: 0;
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
    <h3 class="title">🌴 Información previa de plantación - Evaluación de Cosecha en Campo 🌴</h3><h3><br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span><br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
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


    {{-- Acordeón con formularios anteriores --}}
    <div class="accordion mb-4" id="acordeonEvaluacion">

        {{-- Área --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-expanded="true">
                    📍 Área(s) registrada(s)
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" data-bs-parent="#acordeonEvaluacion">
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
                                    <li><strong>Orden Plantis:</strong> {{ $area->orden_plantis_numero }}</li>
                                    <li><strong>Estado Orden:</strong> {{ $area->estado_oren_plantis }}</li>
                                </ul>
                                <div class="d-flex justify-content-end mt-2">
                                    <a href="{{ route('areas.edit', $area->id) }}" class="btn btn-warning btn-sm">✏️ Editar esta área</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No se ha registrado área.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Fertilizaciones --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFert">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFert">
                    💧 Fertilizaciones
                </button>
            </h2>
            <div id="collapseFert" class="accordion-collapse collapse" data-bs-parent="#acordeonEvaluacion">
                <div class="accordion-body">
                    @if ($visita->fertilizaciones->count())
                        @foreach ($visita->fertilizaciones as $fertilizacion)
                            <div class="fertilizacion-info-card mb-3">
                                <strong>Fecha:</strong> {{ $fertilizacion->fecha_fertilizacion }}
                                <ul>
                                    @foreach ($fertilizacion->detalles as $f)
                                        <li>{{ ucfirst($f->fertilizante) }} - {{ $f->cantidad }} kg</li>
                                    @endforeach
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
                    🌸 Polinizaciones
                </button>
            </h2>
            <div id="collapsePol" class="accordion-collapse collapse" data-bs-parent="#acordeonEvaluacion">
                <div class="accordion-body">
                    @if ($visita->polinizaciones->count())
                        <ul class="list-group">
                            @foreach ($visita->polinizaciones as $p)
                                <li class="list-group-item polinizacion-info-card">
                                    📅 {{ $p->fecha }} | Pases: <strong>{{ $p->n_pases }}</strong>, Ronda: <strong>{{ $p->ciclos_ronda }}</strong>, ANA: <strong>{{ $p->ana }}</strong> ({{ $p->tipo_ana }}), Talco: <strong>{{ $p->talco }}</strong>
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
                    🧪 Sanidad
                </button>
            </h2>
            <div id="collapseSanidad" class="accordion-collapse collapse" data-bs-parent="#acordeonEvaluacion">
                <div class="accordion-body">
                    @if ($visita->sanidad)
                        <div class="sanidad-info-card mb-3">
                            <ul>
                                <li><strong>Opsophanes:</strong> {{ $visita->sanidad->opsophanes }}%</li>
                                <li><strong>Pudrición Cogollo:</strong> {{ $visita->sanidad->pudricion_cogollo }}%</li>
                                <li><strong>Raspador:</strong> {{ $visita->sanidad->raspador }}%</li>
                                <li><strong>Palmarum:</strong> {{ $visita->sanidad->palmarum }}%</li>
                                <li><strong>Strategus:</strong> {{ $visita->sanidad->strategus }}%</li>
                                <li><strong>Leptoparsa:</strong> {{ $visita->sanidad->leptopharsa }}%</li>
                                <li><strong>Pestalotiopsis:</strong> {{ $visita->sanidad->pestalotiopsis }}%</li>
                                <li><strong>Pudrición Basal:</strong> {{ $visita->sanidad->pudricion_basal }}%</li>
                                <li><strong>Pudrición Estipe:</strong> {{ $visita->sanidad->pudricion_estipe }}%</li>
                                <li><strong>Otros:</strong> {{ $visita->sanidad->otros }}</li>
                                <li><strong>Observaciones:</strong> {{ $visita->sanidad->observaciones }}</li>
                            </ul>
                            <div class="d-flex justify-content-end mt-2">
                                <a href="{{ route('sanidades.edit', $visita->sanidad->id) }}" class="btn btn-warning btn-sm">✏️ Editar esta sanidad</a>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">No hay registro de sanidad.</p>
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
            <div id="collapseSuelo" class="accordion-collapse collapse" data-bs-parent="#acordeonEvaluacion">
                <div class="accordion-body">
                    @if ($visita->suelo)
                        <div class="suelo-info-card mb-3">
                            <ul>
                                <li><strong>Análisis foliar:</strong> {{ ucfirst($visita->suelo->analisis_foliar) }}</li>
                                <li><strong>Análisis suelo:</strong> {{ ucfirst($visita->suelo->alanalisis_suelo) }}</li>
                                <li><strong>Tipo suelo:</strong> {{ ucfirst($visita->suelo->tipo_suelo) }}</li>
                            </ul>
                            <div class="d-flex justify-content-end mt-2">
                                <a href="{{ route('suelos.edit', $visita->suelo->id) }}" class="btn btn-warning btn-sm">✏️ Editar este análisis</a>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">No hay análisis de suelo registrado.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Labores de Cultivo --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingLabores">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLabores">
                    🛠️ Labores de Cultivo
                </button>
            </h2>
            <div id="collapseLabores" class="accordion-collapse collapse" data-bs-parent="#acordeonEvaluacion">
                <div class="accordion-body">
                    @if ($visita->laboresCultivo->count() > 0)
                        @foreach ($visita->laboresCultivo as $laborEntry)
                            <div class="labores-info-card mb-3">
                                <h5>Labores para: {{ ucfirst($laborEntry->tipo_planta ?? 'N/A') }}</h5>
                                <ul>
                                    <li><strong>Observaciones:</strong> {{ $laborEntry->observaciones ?? 'No registradas' }}</li>
                                    @php
                                        $laboresLabels = [
                                            'polinizacion' => 'Polinización',
                                            'limpieza_calle' => 'Limpieza de calle',
                                            'limpieza_plato' => 'Limpieza de plato',
                                            'poda' => 'Poda',
                                            'fertilizacion' => 'Fertilización',
                                            'enmiendas' => 'Enmiendas',
                                            'ubicacion_tusa_fibra' => 'Ubicación tusa/fibra',
                                            'ubicacion_hoja' => 'Ubicación hoja',
                                            'lugar_ubicacion_hoja' => 'Lugar ubicación hoja',
                                            'plantas_nectariferas' => 'Plantas nectaríferas',
                                            'cobertura' => 'Cobertura',
                                            'labor_cosecha' => 'Labor cosecha',
                                            'calidad_fruta' => 'Calidad fruta',
                                            'recoleccion_fruta' => 'Recolección fruta',
                                            'drenajes' => 'Drenajes',
                                        ];
                                    @endphp
                                    @foreach ($laboresLabels as $campo => $label)
                                        <li><strong>{{ $label }}:</strong> {{ $laborEntry->$campo ?? '0' }}%</li>
                                    @endforeach
                                </ul>
                                <div class="d-flex justify-content-end mt-2">
                                    <a href="{{ route('labores_cultivo.edit', $laborEntry->id) }}" class="btn btn-warning btn-sm">✏️ Editar este registro</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No se han registrado labores de cultivo.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <h3 class="title">🌴 Formulario Evaluación de Cosecha en Campo </h3>

    {{-- Formulario de Evaluación de Cosecha (ahora dinámico) --}}
    <form id="evaluacionCosechaForm" method="POST" action="{{ route('evaluacion.store') }}">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        {{-- Contenedor para los bloques de formularios de evaluación dinámicos --}}
        <div id="evaluacion-forms-container">
            {{-- Los bloques de formularios se añadirán aquí mediante JavaScript --}}
        </div>

        <button type="button" class="btn btn-info mb-3" onclick="addEvaluacionFormBlock()">+ Añadir Otra Evaluación</button>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">💾 Guardar evaluación</button>
            <a href="{{ route('cierre-visitas.create', ['visita_id' => $visita->id]) }}" class="btn btn-success">
                📌 Finalizar Visita
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

    {{-- Mostrar registros si existen --}}
    @if ($visita->evaluacionCosechaCampo->count() > 0)
        <hr>
        <h4 class="title">📋 Evaluaciones registradas</h4>

        @php
            $evaluacionLabels = [
                'variedad_fruto' => 'Variedad del fruto',
                'cantidad_racimos' => 'Cantidad de racimos',
                'verde' => 'Verde',
                'maduro' => 'Maduro',
                'sobremaduro' => 'Sobremaduro',
                'pedunculo' => 'Pedúnculo',
                'conformacion' => 'Conformación', // Nuevo campo
            ];
        @endphp

        @foreach ($visita->evaluacionCosechaCampo as $evaluacionEntry)
            <ul class="list-group mb-4 evaluacion-info-card">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Variedad del fruto</span>
                    <strong>{{ ucfirst($evaluacionEntry->variedad_fruto ?? 'N/A') }}</strong>
                </li>
                <li class="list-group-item">
                    <span><strong>Cantidad de racimos:</strong></span>
                    <p>{{ $evaluacionEntry->cantidad_racimos ?? '0' }}</p>
                </li>
                @foreach (['verde', 'maduro', 'sobremaduro', 'pedunculo'] as $campo)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $evaluacionLabels[$campo] }}</span>
                        <strong>{{ $evaluacionEntry->$campo ?? '0' }}%</strong>
                    </li>
                @endforeach
                @if ($evaluacionEntry->variedad_fruto === 'hibrido')
                    <li class="list-group-item">
                        <span><strong>Conformación:</strong></span>
                        <p>{{ $evaluacionEntry->conformacion ?? 'No especificada' }}</p>
                    </li>
                @endif
                <li class="list-group-item">
                    <span><strong>Observaciones:</strong></span>
                    <p>{{ $evaluacionEntry->observaciones ?? 'No registradas' }}</p>
                </li>
                <div class="button-group mt-2 d-flex justify-content-end">
                    <a href="{{ route('evaluacion.edit', $evaluacionEntry->id) }}" class="btn btn-warning btn-sm me-2">
                        ✏️ Editar este registro
                    </a>
                    <form method="POST" action="{{ route('evaluacion.destroy', $evaluacionEntry->id) }}" class="d-inline" onsubmit="return confirm('¿Deseas eliminar este registro de Evaluación?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">🗑️ Eliminar</button>
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
        <p class="text-muted text-center mt-4">No se han registrado evaluaciones de cosecha aún.</p>
    @endif

</div>

<script>
    // Para mantener un índice único para cada bloque de formulario dinámico
    let evaluacionFormBlockIndex = 0;

    /**
     * Añade un nuevo bloque de formulario de evaluación completo.
     * @param {Object} [data={}] Objeto con datos para precargar los campos.
     */
    function addEvaluacionFormBlock(data = {}) {
        const container = document.getElementById('evaluacion-forms-container');
        const block = document.createElement('div');
        block.classList.add('evaluacion-form-block');
        block.setAttribute('data-index', evaluacionFormBlockIndex);

        const variedadFrutoValue = data.variedad_fruto !== undefined ? data.variedad_fruto : '';
        const cantidadRacimosValue = data.cantidad_racimos !== undefined ? data.cantidad_racimos : '';
        const verdeValue = data.verde !== undefined ? data.verde : '';
        const maduroValue = data.maduro !== undefined ? data.maduro : '';
        const sobremaduroValue = data.sobremaduro !== undefined ? data.sobremaduro : '';
        const pedunculoValue = data.pedunculo !== undefined ? data.pedunculo : '';
        const conformacionValue = data.conformacion !== undefined ? data.conformacion : '';
        const observacionesValue = data.observaciones !== undefined ? data.observaciones : '';

        block.innerHTML = `
            <button type="button" class="remove-block-btn" onclick="removeEvaluacionFormBlock(this)">✖️</button>
            <div class="mb-3">
                <label for="evaluaciones_${evaluacionFormBlockIndex}_variedad_fruto" class="form-label">Variedad del fruto:</label>
                <select name="evaluaciones[${evaluacionFormBlockIndex}][variedad_fruto]" id="evaluaciones_${evaluacionFormBlockIndex}_variedad_fruto" class="form-select" required onchange="toggleConformacion(this, ${evaluacionFormBlockIndex})">
                    <option value="">Seleccione</option>
                    <option value="guinense" ${variedadFrutoValue === 'guinense' ? 'selected' : ''}>Guinense</option>
                    <option value="hibrido" ${variedadFrutoValue === 'hibrido' ? 'selected' : ''}>Híbrido</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="evaluaciones_${evaluacionFormBlockIndex}_cantidad_racimos" class="form-label">Cantidad de racimos:</label>
                <input type="number" name="evaluaciones[${evaluacionFormBlockIndex}][cantidad_racimos]" id="evaluaciones_${evaluacionFormBlockIndex}_cantidad_racimos" class="form-control" min="0" value="${cantidadRacimosValue}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="evaluaciones_${evaluacionFormBlockIndex}_verde" class="form-label">Verde (%):</label>
                    <input type="number" name="evaluaciones[${evaluacionFormBlockIndex}][verde]" id="evaluaciones_${evaluacionFormBlockIndex}_verde" class="form-control" min="0" max="100" value="${verdeValue}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="evaluaciones_${evaluacionFormBlockIndex}_maduro" class="form-label">Maduro (%):</label>
                    <input type="number" name="evaluaciones[${evaluacionFormBlockIndex}][maduro]" id="evaluaciones_${evaluacionFormBlockIndex}_maduro" class="form-control" min="0" max="100" value="${maduroValue}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="evaluaciones_${evaluacionFormBlockIndex}_sobremaduro" class="form-label">Sobremaduro (%):</label>
                    <input type="number" name="evaluaciones[${evaluacionFormBlockIndex}][sobremaduro]" id="evaluaciones_${evaluacionFormBlockIndex}_sobremaduro" class="form-control" min="0" max="100" value="${sobremaduroValue}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="evaluaciones_${evaluacionFormBlockIndex}_pedunculo" class="form-label">Pedúnculo (%):</label>
                    <input type="number" name="evaluaciones[${evaluacionFormBlockIndex}][pedunculo]" id="evaluaciones_${evaluacionFormBlockIndex}_pedunculo" class="form-control" min="0" max="100" value="${pedunculoValue}" required>
                </div>
            </div>

            {{-- Nuevo campo "Conformación" --}}
            <div class="mb-3 conformacion-group" id="conformacion_group_${evaluacionFormBlockIndex}" style="display: ${variedadFrutoValue === 'hibrido' ? 'block' : 'none'};">
                <label for="evaluaciones_${evaluacionFormBlockIndex}_conformacion" class="form-label">Conformación:</label>
                <input type="text" name="evaluaciones[${evaluacionFormBlockIndex}][conformacion]" id="evaluaciones_${evaluacionFormBlockIndex}_conformacion" class="form-control" value="${conformacionValue}">
            </div>

            <div class="mb-3">
                <label for="evaluaciones_${evaluacionFormBlockIndex}_observaciones" class="form-label">Observaciones (Opcional):</label>
                <textarea name="evaluaciones[${evaluacionFormBlockIndex}][observaciones]" id="evaluaciones_${evaluacionFormBlockIndex}_observaciones" class="form-control" rows="2">${observacionesValue}</textarea>
            </div>
        `;
        container.appendChild(block);

        // Llamar a toggleConformacion para asegurar la visibilidad correcta al añadir
        toggleConformacion(block.querySelector(`#evaluaciones_${evaluacionFormBlockIndex}_variedad_fruto`), evaluacionFormBlockIndex);

        evaluacionFormBlockIndex++;
    }

    /**
     * Elimina un bloque de formulario de evaluación.
     * @param {HTMLButtonElement} button El botón "X" que fue clickeado.
     */
    function removeEvaluacionFormBlock(button) {
        if (confirm('¿Estás seguro de que quieres eliminar este bloque de formulario de evaluación?')) {
            button.closest('.evaluacion-form-block').remove();
            // Si no quedan bloques, añadir uno vacío por defecto
            if (document.querySelectorAll('.evaluacion-form-block').length === 0) {
                addEvaluacionFormBlock();
            }
        }
    }

    /**
     * Muestra u oculta el campo "Conformación" basado en la selección de "Variedad del fruto".
     * @param {HTMLSelectElement} selectElement El elemento select de "Variedad del fruto".
     * @param {number} index El índice del bloque de formulario.
     */
    function toggleConformacion(selectElement, index) {
        const conformacionGroup = document.getElementById(`conformacion_group_${index}`);
        if (conformacionGroup) {
            if (selectElement.value === 'hibrido') {
                conformacionGroup.style.display = 'block';
                conformacionGroup.querySelector('input').setAttribute('required', 'required'); // Hacerlo requerido si es híbrido
            } else {
                conformacionGroup.style.display = 'none';
                conformacionGroup.querySelector('input').removeAttribute('required'); // No requerido si no es híbrido
                conformacionGroup.querySelector('input').value = ''; // Limpiar valor si se oculta
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const existingEvaluaciones = @json($visita->evaluacionCosechaCampo);

        if (existingEvaluaciones && existingEvaluaciones.length > 0) {
            // Si hay registros existentes, precargarlos
            existingEvaluaciones.forEach(evaluacionEntry => {
                addEvaluacionFormBlock(evaluacionEntry);
            });
        } else {
            // Si no hay registros existentes, añadir un bloque vacío por defecto
            addEvaluacionFormBlock();
        }
    });
</script>
@endsection

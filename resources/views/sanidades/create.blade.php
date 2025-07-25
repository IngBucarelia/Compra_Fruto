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
        margin-left: auto; /* Centra el contenedor */
        margin-right: auto; /* Centra el contenedor */
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
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-expanded="true">
                    📍 Área(s) registrada(s)
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" data-bs-parent="#acordeonSanidad">
                <div class="accordion-body">
                    @if ($visita->areas->count() > 0)
                        @foreach ($visita->areas as $area)
                            <div class="area-info-card mb-3">
                                <h5>Área - Material: {{ $area->material }}</h5>
                                <ul>
                                    <li><strong>Estado:</strong> {{ $area->estado }}</li>
                                    <li><strong>Año siembra:</strong> {{ $area->anio_siembra }}</li>
                                    <li><strong>Área (m²):</strong> {{ $area->area }}</li>
                                    <li><strong>Área Total Finca (Ha):</strong> {{ $area->area_total_finca_hectareas ?? 'N/A' }}</li>
                                    <li><strong>Palmas Total Finca:</strong> {{ $area->numero_palmas_total_finca ?? 'N/A' }}</li>
                                    <li><strong>Área Palmas Desarrollo (Ha):</strong> {{ $area->area_palmas_desarrollo_hectareas ?? 'N/A' }}</li>
                                    <li><strong>Palmas Desarrollo:</strong> {{ $area->numero_palmas_desarrollo ?? 'N/A' }}</li>
                                    <li><strong>Área Palmas Producción (Ha):</strong> {{ $area->area_palmas_produccion_hectareas ?? 'N/A' }}</li>
                                    <li><strong>Palmas Producción:</strong> {{ $area->numero_palmas_produccion ?? 'N/A' }}</li>
                                    <li><strong>Ciclos de Cosecha:</strong> {{ $area->ciclos_cosecha ?? 'N/A' }}</li>
                                    <li><strong>Producción Toneladas/Mes:</strong> {{ $area->produccion_toneladas_por_mes ?? 'N/A' }}</li>
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
                                    @foreach ($fertilizacion->fertilizantes as $f)
                                        <li class="list-group-item">
                                            <strong>{{ ucfirst($f->fertilizante) }}</strong> - {{ $f->cantidad }} {{ $f->unidad_medida }} (Fecha Aplicación: {{ $f->fecha_aplicacion }})
                                        </li>
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
                    🌸 Polinizaciones registradas
                </button>
            </h2>
            <div id="collapsePol" class="accordion-collapse collapse" data-bs-parent="#acordeonSanidad">
                <div class="accordion-body">
                    @if ($visita->polinizaciones->count())
                        <ul class="list-group">
                            @foreach ($visita->polinizaciones as $poli)
                                <li class="list-group-item polinizacion-info-card">
                                    📅 {{ $poli->fecha }} | Pases: <strong>{{ $poli->n_pases }}</strong>,
                                    Ronda: <strong>{{ $poli->ciclos_ronda }}</strong>,
                                    ANA: <strong>{{ $poli->ana }}</strong> ({{ $poli->tipo_ana }}),
                                    Talco: <strong>{{ $poli->talco }}</strong>
                                    <div class="d-flex justify-content-end mt-2">
                                        <a href="{{ route('polinizaciones.edit', $poli->id) }}" class="btn btn-warning btn-sm">✏️ Editar esta polinización</a>
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
    <h3>🧪 Registro de Sanidad - {{ $visita->proveedor->proveedor_nombre }}</h3>

    {{-- Formulario SANIDAD --}}
    <form id="sanidadForm" method="POST" action="{{ route('sanidades.store') }}">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        {{-- Hidden inputs para los campos de la base de datos --}}
        <input type="hidden" name="opsophanes" id="opsophanes_hidden" value="{{ old('opsophanes', $visita->sanidades->first()->opsophanes ?? '') }}">
        <input type="hidden" name="pudricion_cogollo" id="pudricion_cogollo_hidden" value="{{ old('pudricion_cogollo', $visita->sanidades->first()->pudricion_cogollo ?? '') }}">
        <input type="hidden" name="raspador" id="raspador_hidden" value="{{ old('raspador', $visita->sanidades->first()->raspador ?? '') }}">
        <input type="hidden" name="palmarum" id="palmarum_hidden" value="{{ old('palmarum', $visita->sanidades->first()->palmarum ?? '') }}">
        <input type="hidden" name="strategus" id="strategus_hidden" value="{{ old('strategus', $visita->sanidades->first()->strategus ?? '') }}">
        <input type="hidden" name="leptopharsa" id="leptopharsa_hidden" value="{{ old('leptopharsa', $visita->sanidades->first()->leptopharsa ?? '') }}">
        <input type="hidden" name="pestalotiopsis" id="pestalotiopsis_hidden" value="{{ old('pestalotiopsis', $visita->sanidades->first()->pestalotiopsis ?? '') }}">
        <input type="hidden" name="pudricion_basal" id="pudricion_basal_hidden" value="{{ old('pudricion_basal', $visita->sanidades->first()->pudricion_basal ?? '') }}">
        <input type="hidden" name="pudricion_estipe" id="pudricion_estipe_hidden" value="{{ old('pudricion_estipe', $visita->sanidades->first()->pudricion_estipe ?? '') }}">

        <div id="enfermedades-container">
            {{-- Los campos dinámicos se añadirán aquí mediante JavaScript --}}
        </div>

        <button type="button" class="btn btn-info mb-3" onclick="addEnfermedad()">+ Añadir enfermedad</button>

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

        <div class="button-group">
            <button type="submit" class="btn btn-primary">💾 Guardar sanidad</button>
            <a href="{{ route('suelos.create', ['visita_id' => $visita->id]) }}" class="btn btn-success">
                ➡️ Continuar con Análisis de Suelo
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    <hr>

    @if ($visita->sanidades->count())
        <div class="container">
            <h4 class="title">🦠 Sanidades registradas</h4>
            <ul class="list-group">
                @foreach ($visita->sanidades as $sanidad)
                    <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap">
                        <div>
                            {{-- Mostrar los campos individuales de la BD --}}
                            <strong>Opsophanes:</strong> {{ $sanidad->opsophanes ?? '-' }}% <br>
                            <strong>P. Cogollo:</strong> {{ $sanidad->pudricion_cogollo ?? '-' }}% <br>
                            <strong>Raspador:</strong> {{ $sanidad->raspador ?? '-' }}% <br>
                            <strong>Palmarum:</strong> {{ $sanidad->palmarum ?? '-' }}% <br>
                            <strong>Strategus:</strong> {{ $sanidad->strategus ?? '-' }}% <br>
                            <strong>Leptopharsa:</strong> {{ $sanidad->leptopharsa ?? '-' }}% <br>
                            <strong>Pestalotiopsis:</strong> {{ $sanidad->pestalotiopsis ?? '-' }}% <br>
                            <strong>P. Basal:</strong> {{ $sanidad->pudricion_basal ?? '-' }}% <br>
                            <strong>P. Estipe:</strong> {{ $sanidad->pudricion_estipe ?? '-' }}% <br>
                            
                            @if ($sanidad->otros)
                                <strong>Otros (descripción):</strong> {{ $sanidad->otros }}<br>
                            @endif
                            @if ($sanidad->observaciones)
                                <strong>Observaciones:</strong> {{ $sanidad->observaciones }}
                            @endif
                        </div>
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
    // Mapeo de nombres de enfermedades a los nombres de los campos hidden
    const diseaseFieldMap = {
        'Opsophanes': 'opsophanes',
        'Pudrición del cogollo': 'pudricion_cogollo',
        'Raspador': 'raspador',
        'Palmarum': 'palmarum',
        'Strategus': 'strategus',
        'Leptopharsa': 'leptopharsa', // Asegúrate de que este nombre coincida con tu columna real
        'Pestalotiopsis': 'pestalotiopsis',
        'Pudrición basal': 'pudricion_basal',
        'Pudrición estipe': 'pudricion_estipe',
    };

    // Array para mantener el estado de los campos dinámicos en el frontend
    let dynamicDiseases = [];
    let currentEnfermedadIndex = 0; // Para asignar IDs únicos a los elementos dinámicos

    /**
     * Resetea todos los campos hidden de enfermedades a null.
     */
    function resetHiddenDiseaseInputs() {
        for (const key in diseaseFieldMap) {
            const fieldName = diseaseFieldMap[key];
            const hiddenInput = document.getElementById(`${fieldName}_hidden`);
            if (hiddenInput) {
                hiddenInput.value = ''; // O null, dependiendo de cómo lo maneje Laravel para nullable
            }
        }
    }

    /**
     * Actualiza los valores de los campos hidden basándose en los datos de dynamicDiseases.
     */
    function updateHiddenDiseaseInputs() {
        resetHiddenDiseaseInputs(); // Primero, resetea todos

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

    /**
     * Añade un nuevo grupo de campos para una enfermedad.
     * @param {string} defaultName Nombre predeterminado de la enfermedad (para precarga).
     * @param {number} defaultPercentage Porcentaje predeterminado (para precarga).
     */
    function addEnfermedad(defaultName = '', defaultPercentage = '') {
        const container = document.getElementById('enfermedades-container');
        const group = document.createElement('div');
        group.classList.add('enfermedad-group', 'mb-3');
        group.setAttribute('data-index', currentEnfermedadIndex); // Para identificar el grupo

        const uniqueId = `enfermedad_${currentEnfermedadIndex}`;

        group.innerHTML = `
            <button type="button" class="remove-enfermedad-btn" onclick="removeEnfermedad(this)">✖️</button>
            <div class="mb-2">
                <label for="${uniqueId}_nombre">Enfermedad:</label>
                <select name="dynamic_enfermedad_nombre_${currentEnfermedadIndex}" id="${uniqueId}_nombre" class="form-control" required onchange="handleDiseaseChange(this)">
                    <option value="">Seleccione enfermedad</option>
                    <option value="Opsophanes">Opsophanes</option>
                    <option value="Pudrición del cogollo">Pudrición del cogollo</option>
                    <option value="Raspador">Raspador</option>
                    <option value="Palmarum">Palmarum</option>
                    <option value="Strategus">Strategus</option>
                    <option value="Leptopharsa">Leptopharsa</option>
                    <option value="Pestalotiopsis">Pestalotiopsis</option>
                    <option value="Pudrición basal">Pudrición basal</option>
                    <option value="Pudrición estipe">Pudrición estipe</option>
                </select>
            </div>
            <div class="mb-0">
                <label for="${uniqueId}_porcentaje">Porcentaje de afectación (%):</label>
                <input type="number" name="dynamic_enfermedad_porcentaje_${currentEnfermedadIndex}" id="${uniqueId}_porcentaje" class="form-control" min="0" max="100" oninput="handlePercentageChange(this)">
            </div>
        `;
        container.appendChild(group);

        // Preseleccionar valores si se proporcionan (para old input o edición)
        const selectElement = group.querySelector(`#${uniqueId}_nombre`);
        const percentageInput = group.querySelector(`#${uniqueId}_porcentaje`);

        if (defaultName) {
            selectElement.value = defaultName;
        }
        if (defaultPercentage !== '') {
            percentageInput.value = defaultPercentage;
        }

        // Añadir al array de estado dinámico
        dynamicDiseases.push({
            id: currentEnfermedadIndex,
            name: defaultName,
            percentage: defaultPercentage
        });

        currentEnfermedadIndex++;
        updateHiddenDiseaseInputs(); // Actualizar los campos hidden al añadir
    }

    /**
     * Maneja el cambio en el select de enfermedad.
     * @param {HTMLSelectElement} selectElement El elemento select que cambió.
     */
    function handleDiseaseChange(selectElement) {
        const index = parseInt(selectElement.closest('.enfermedad-group').getAttribute('data-index'));
        const newName = selectElement.value;
        const entry = dynamicDiseases.find(d => d.id === index);
        if (entry) {
            entry.name = newName;
            updateHiddenDiseaseInputs();
        }
    }

    /**
     * Maneja el cambio en el input de porcentaje.
     * @param {HTMLInputElement} inputElement El elemento input que cambió.
     */
    function handlePercentageChange(inputElement) {
        const index = parseInt(inputElement.closest('.enfermedad-group').getAttribute('data-index'));
        const newPercentage = inputElement.value;
        const entry = dynamicDiseases.find(d => d.id === index);
        if (entry) {
            entry.percentage = newPercentage;
            updateHiddenDiseaseInputs();
        }
    }

    /**
     * Elimina un grupo de campos de enfermedad.
     * @param {HTMLButtonElement} button El botón "X" que fue clickeado.
     */
    function removeEnfermedad(button) {
        const group = button.closest('.enfermedad-group');
        const indexToRemove = parseInt(group.getAttribute('data-index'));

        dynamicDiseases = dynamicDiseases.filter(entry => entry.id !== indexToRemove);
        group.remove();
        updateHiddenDiseaseInputs(); // Actualizar los campos hidden al eliminar

        // Si no quedan campos dinámicos, añadir uno vacío por defecto
        if (dynamicDiseases.length === 0) {
            addEnfermedad();
        }
    }

    // Lógica para precargar datos si hay errores de validación (old input)
    document.addEventListener('DOMContentLoaded', function() {
        const oldSanidadData = @json(old()); // Obtener todos los old input
        const oldEnfermedades = oldSanidadData.enfermedades_registradas || []; // Asumiendo que Laravel aún enviaría esto si el campo existiera en el request

        const existingSanidad = @json($visita->sanidades->first()); // Asumiendo que solo hay una sanidad por visita, o la primera

        // Priorizar old input sobre datos existentes si hay errores de validación
        let dataToLoad = [];
        if (oldEnfermedades.length > 0) {
            // Si hay old input de enfermedades_registradas, usarlos
            // Necesitamos mapear de nuevo a la estructura de hidden inputs
            for (const key in diseaseFieldMap) {
                const fieldName = diseaseFieldMap[key];
                const percentage = oldSanidadData[fieldName]; // Obtener el valor del campo hidden
                if (percentage !== '' && percentage !== null) { // Solo si tiene un valor
                    dataToLoad.push({ name: key, percentage: percentage });
                }
            }
        } else if (existingSanidad) {
            // Si no hay old input, cargar desde la base de datos
            for (const key in diseaseFieldMap) {
                const fieldName = diseaseFieldMap[key];
                const percentage = existingSanidad[fieldName];
                if (percentage !== '' && percentage !== null) {
                    dataToLoad.push({ name: key, percentage: percentage });
                }
            }
        }

        // Limpiar el contenedor antes de añadir los elementos precargados
        const container = document.getElementById('enfermedades-container');
        container.innerHTML = '';
        dynamicDiseases = []; // Resetear el array de estado

        if (dataToLoad.length > 0) {
            dataToLoad.forEach(entry => {
                addEnfermedad(entry.name, entry.percentage);
            });
        } else {
            // Si no hay datos para precargar, añadir un grupo vacío por defecto
            addEnfermedad();
        }
    });
</script>
@endsection

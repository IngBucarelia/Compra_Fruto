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
        .button-group-top {
            flex-direction: row;
            justify-content: flex-start;
        }

         .container.offline-form-container {
        padding: 15px;
            margin-top: 15px;
            border-radius: 0;
            box-shadow: none;
            width: 100%;
            max-width: none;
            margin-left: -35px !important;
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

<div class="container offline-form-container">

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
            Fecha Visita: <span style="color: wheat" >{{ $visita->fecha}}</span> <br>
            Proveedor:<span style="color: wheat" > {{ $visita->proveedor->proveedor_nombre }} </span><br>
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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mostrar las áreas registradas si existen --}}
    @if ($visita->areas->count() > 0)
        <div class="alert alert-success">
            <strong>Áreas ya registradas:</strong>
            @foreach ($visita->areas as $area) {{-- ✅ Iterar sobre $visita->areas --}}
                <ul class="mb-0 mt-3 border-top pt-3">
                    <li><strong>Material:</strong> {{ $area->material }}</li>
                    <li><strong>Estado:</strong> {{ $area->estado }}</li>
                    <li><strong>Año de siembra:</strong> {{ $area->anio_siembra }}</li>
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
    @else
        {{-- Formulario para añadir nuevas áreas --}}
        <form method="POST" action="{{ route('areas.store') }}" id="areaForm">
            @csrf
            <input type="hidden" name="visita_id" value="{{ $visita->id }}">

            <div id="areaFormsContainer">
                {{-- Aquí se clonarán los formularios de área --}}
                {{-- El primer formulario se generará con JavaScript al cargar la página --}}
            </div>

            <button type="button" class="btn btn-info mt-3 mb-3" id="addAreaBtn">➕ Añadir otra Área</button>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">Guardar y continuar</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">⬅️ Cancelar</a>
            </div>
        </form>
    @endif
</div>

<script>
    let areaIndex = 0; // Para mantener un índice único para cada formulario de área

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
                <label for="material_${areaIndex}">Material:</label>
                <select name="areas[${areaIndex}][material]" id="material_${areaIndex}" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="guinense">Guinense</option>
                    <option value="hibrido">Híbrido</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="estado_${areaIndex}">Estado:</label>
                <select name="areas[${areaIndex}][estado]" id="estado_${areaIndex}" class="form-control" required>
                    <option value="desarrollo">Desarrollo</option>
                    <option value="produccion">Producción</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="anio_siembra_${areaIndex}">Año de siembra:</label>
                <input type="date" name="areas[${areaIndex}][anio_siembra]" id="anio_siembra_${areaIndex}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="area_m2_${areaIndex}">Área (m²):</label>
                <input type="number" name="areas[${areaIndex}][area]" id="area_m2_${areaIndex}" class="form-control" required min="0">
            </div>

            <div class="mb-3">
                <label for="area_total_finca_hectareas_${areaIndex}">Área total en finca (Hectáreas):</label>
                <input type="number" step="0.01" name="areas[${areaIndex}][area_total_finca_hectareas]" id="area_total_finca_hectareas_${areaIndex}" class="form-control" min="0">
            </div>
            <div class="mb-3">
                <label for="numero_palmas_total_finca_${areaIndex}">Número de palmas (Total Finca):</label>
                <input type="number" name="areas[${areaIndex}][numero_palmas_total_finca]" id="numero_palmas_total_finca_${areaIndex}" class="form-control" min="0">
            </div>

            <div class="mb-3">
                <label for="area_palmas_desarrollo_hectareas_${areaIndex}">Área de palmas en desarrollo (Hectáreas):</label>
                <input type="number" step="0.01" name="areas[${areaIndex}][area_palmas_desarrollo_hectareas]" id="area_palmas_desarrollo_hectareas_${areaIndex}" class="form-control" min="0">
            </div>
            <div class="mb-3">
                <label for="numero_palmas_desarrollo_${areaIndex}">Número de palmas (Desarrollo):</label>
                <input type="number" name="areas[${areaIndex}][numero_palmas_desarrollo]" id="numero_palmas_desarrollo_${areaIndex}" class="form-control" min="0">
            </div>

            <div class="mb-3">
                <label for="area_palmas_produccion_hectareas_${areaIndex}">Área de palmas en producción (Hectáreas):</label>
                <input type="number" step="0.01" name="areas[${areaIndex}][area_palmas_produccion_hectareas]" id="area_palmas_produccion_hectareas_${areaIndex}" class="form-control" min="0">
            </div>
            <div class="mb-3">
                <label for="numero_palmas_produccion_${areaIndex}">Número de palmas (Producción):</label>
                <input type="number" name="areas[${areaIndex}][numero_palmas_produccion]" id="numero_palmas_produccion_${areaIndex}" class="form-control" min="0">
            </div>

            <div class="mb-3">
                <label for="ciclos_cosecha_${areaIndex}">Ciclos de Cosecha:</label>
                <input type="number" name="areas[${areaIndex}][ciclos_cosecha]" id="ciclos_cosecha_${areaIndex}" class="form-control" min="0">
            </div>
            <div class="mb-3">
                <label for="produccion_toneladas_por_mes_${areaIndex}">Producción (Toneladas por Mes):</label>
                <input type="number" step="0.01" name="areas[${areaIndex}][produccion_toneladas_por_mes]" id="produccion_toneladas_por_mes_${areaIndex}" class="form-control" min="0">
            </div>

            <div class="mb-3">
                <label for="aplica_orden_plantis_${areaIndex}">¿Aplica Orden Plantis?</label>
                <select name="areas[${areaIndex}][aplica_orden_plantis]" id="aplica_orden_plantis_${areaIndex}" class="form-control" required onchange="toggleOrdenPlantisFields(this, ${areaIndex})">
                    <option value="0">No</option>
                    <option value="1">Sí</option>
                </select>
            </div>

            <div id="ordenPlantisFields_${areaIndex}" style="display: none;">
                <div class="mb-3">
                    <label for="orden_plantis_numero_${areaIndex}">Orden Plantis Número:</label>
                    <input type="number" name="areas[${areaIndex}][orden_plantis_numero]" id="orden_plantis_numero_${areaIndex}" class="form-control" min="0">
                </div>
                <div class="mb-3">
                    <label for="numero_plantas_orden_plantis_${areaIndex}">Número de Plantas (Orden Plantis):</label>
                    <input type="number" name="areas[${areaIndex}][numero_plantas_orden_plantis]" id="numero_plantas_orden_plantis_${areaIndex}" class="form-control" min="0">
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

        // Pre-llenar datos si se proporcionan (ej. para errores de validación)
        if (Object.keys(initialData).length > 0) {
            for (const key in initialData) {
                const input = template.querySelector(`[name="areas[${areaIndex}][${key}]"]`);
                if (input) {
                    if (input.type === 'select-one') {
                        // Para selects, asegurarse de que la opción exista antes de asignar
                        const optionExists = Array.from(input.options).some(option => option.value === String(initialData[key]));
                        if (optionExists) {
                            input.value = initialData[key];
                        }
                    } else if (input.type === 'date') {
                        // Formatear la fecha si es necesario
                        input.value = initialData[key].split(' ')[0]; // Tomar solo la parte de la fecha
                    } else {
                        input.value = initialData[key];
                    }
                }
            }
        }

        // Re-ejecutar toggleOrdenPlantisFields para el nuevo formulario si aplica_orden_plantis es 1
        const aplicaOrdenPlantisSelect = document.getElementById(`aplica_orden_plantis_${areaIndex}`);
        if (aplicaOrdenPlantisSelect && aplicaOrdenPlantisSelect.value === '1') {
            toggleOrdenPlantisFields(aplicaOrdenPlantisSelect, areaIndex);
        }

        areaIndex++; // Incrementar el índice para el próximo formulario
    }

    // Función para eliminar un formulario de área
    function removeAreaForm(button) {
        const card = button.closest('.area-form-card');
        if (card) {
            card.remove();
        }
    }

    // Función para mostrar/ocultar campos de Orden Plantis (ahora toma el elemento select y el índice)
    function toggleOrdenPlantisFields(selectElement, index) {
        const aplicaOrdenPlantis = selectElement.value;
        const ordenPlantisFields = document.getElementById(`ordenPlantisFields_${index}`);
        const ordenPlantisNumero = document.getElementById(`orden_plantis_numero_${index}`);
        const numeroPlantasOrdenPlantis = document.getElementById(`numero_plantas_orden_plantis_${index}`);
        const estadoOrdenPlantis = document.getElementById(`estado_oren_plantis_${index}`);

        if (aplicaOrdenPlantis === '1') {
            ordenPlantisFields.style.display = 'block';
            ordenPlantisNumero.setAttribute('required', 'required');
            numeroPlantasOrdenPlantis.setAttribute('required', 'required');
            estadoOrdenPlantis.setAttribute('required', 'required');
        } else {
            ordenPlantisFields.style.display = 'none';
            ordenPlantisNumero.removeAttribute('required');
            numeroPlantasOrdenPlantis.removeAttribute('required');
            estadoOrdenPlantis.removeAttribute('required');
            // Limpiar valores cuando se ocultan los campos
            ordenPlantisNumero.value = '';
            numeroPlantasOrdenPlantis.value = '';
            estadoOrdenPlantis.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Añadir el primer formulario de área al cargar la página
        addAreaForm();

        // Manejar el botón "Añadir otra Área"
        document.getElementById('addAreaBtn').addEventListener('click', function() {
            addAreaForm();
        });

        // Si hay errores de validación, rellenar los formularios con los datos antiguos
        @if ($errors->any() && old('areas'))
            // Limpiar el formulario inicial generado automáticamente
            document.getElementById('areaFormsContainer').innerHTML = '';
            areaIndex = 0; // Resetear el índice

            @foreach (old('areas') as $oldArea)
                addAreaForm(@json($oldArea));
            @endforeach
            // Re-evaluar el estado de los campos condicionales para los formularios rellenados
            document.querySelectorAll('.area-form-card').forEach((card, idx) => {
                const select = card.querySelector(`#aplica_orden_plantis_${idx}`);
                if (select) {
                    toggleOrdenPlantisFields(select, idx);
                }
            });
        @endif
    });
</script>
@endsection

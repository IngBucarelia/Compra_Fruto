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
        margin-left: -35px !important; /* Centra el contenedor */
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
        float: right; /* Alinea a la derecha */
        margin-top: -10px; /* Ajusta la posición */
        margin-right: -10px;
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
    @media (max-width: 967.98px) {
        .container.offline-form-container {
        padding: 15px;
            margin-top: 15px;
            border-radius: 0;
            box-shadow: none;
            width: 100%;
            max-width: none;
            margin-left: -35px !important;
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
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button style="background-color: darkseagreen !important; color:aliceblue" class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-controls="collapseArea">
                    📍 Información del Área(s)
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" aria-labelledby="headingArea" data-bs-parent="#accordionArea">
                <div class="accordion-body" style="background-color: rgb(209, 241, 209) !important; color:rgb(31, 32, 34)">
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
                        <p class="text-muted">No se ha registrado área para esta visita.</p>
                    @endif
                </div>
            </div>
        </div>
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
                    {{-- ✅ CAMBIO: name="fertilizantes[0][nombre]" para coincidir con la validación del controlador --}}
                    <select name="fertilizantes[0][nombre]" id="fertilizante_nombre_0" class="form-control" required>
                        <option value="">Seleccione fertilizante</option>
                        <option value="urea" {{ old('fertilizantes.0.nombre') == 'urea' ? 'selected' : '' }}>Urea</option>
                        <option value="compost" {{ old('fertilizantes.0.nombre') == 'compost' ? 'selected' : '' }}>Compost</option>
                        <option value="npk" {{ old('fertilizantes.0.nombre') == 'npk' ? 'selected' : '' }}>NPK</option>
                        <option value="otro" {{ old('fertilizantes.0.nombre') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
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
                        <option value="litros" {{ old('fertilizantes.0.unidad_medida') == 'litros' ? 'selected' : '' }}>Litros</option>
                        <option value="gramos" {{ old('fertilizantes.0.unidad_medida') == 'gramos' ? 'selected' : '' }}>Gramos</option>
                        <option value="unidades" {{ old('fertilizantes.0.unidad_medida') == 'unidades' ? 'selected' : '' }}>Unidades</option>
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
    let fertilizanteIndex = 1; // Un índice más descriptivo

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
                {{-- ✅ CAMBIO: name="fertilizantes[${fertilizanteIndex}][nombre]" para coincidir con la validación del controlador --}}
                <select name="fertilizantes[${fertilizanteIndex}][nombre]" id="fertilizante_nombre_${fertilizanteIndex}" class="form-control" required>
                    <option value="">Seleccione fertilizante</option>
                    <option value="urea">Urea</option>
                    <option value="compost">Compost</option>
                    <option value="npk">NPK</option>
                    <option value="otro">Otro</option>
                </select>
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
                    <option value="litros">Litros</option>
                    <option value="gramos">Gramos</option>
                    <option value="unidades">Unidades</option>
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
</script>
@endsection

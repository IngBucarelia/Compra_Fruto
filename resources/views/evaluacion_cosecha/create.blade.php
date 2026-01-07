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
            <div id="collapseArea" class="accordion-collapse collapse show" aria-labelledby="headingArea" data-bs-parent="#accordionArea">
                <div class="accordion-body" style="background-color: rgb(209, 241, 209) !important; color:rgb(31, 32, 34)">
                    @if ($visita->areas->count() > 0)
                        {{-- RESUMEN GENERAL --}}
                        @php
                            // Tomar los valores del primer área (ya que son los mismos para todas las áreas)
                            $primerArea = $visita->areas->first();
                            $areaTotal = $primerArea->area_total_finca_hectareas ?? 'N/A';
                            $palmasTotal = $primerArea->numero_palmas_total_finca ?? 'N/A';
                            $ciclosCosecha = $primerArea->ciclos_cosecha ?? 'N/A';
                            $produccionTotal = $primerArea->produccion_toneladas_por_mes ?? 'N/A';
                        @endphp
                        
                        <div class="resumen-general-card mb-4 p-3 border rounded" style="background-color: #e8f5e8;">
                            <h4 class="text-success mb-3">📊 Resumen General de la Plantación</h4>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="summary-item">
                                        <label class="fw-bold">Área Total a Estudiar (Ha):</label>
                                        <p class="mb-0">
                                            @if($areaTotal !== 'N/A')
                                                {{ number_format($areaTotal, 2) }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="summary-item">
                                        <label class="fw-bold">Número Total de Palmas:</label>
                                        <p class="mb-0">
                                            @if($palmasTotal !== 'N/A')
                                                {{ number_format($palmasTotal, 0) }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="summary-item">
                                        <label class="fw-bold">Ciclos de Cosecha:</label>
                                        <p class="mb-0">{{ $ciclosCosecha }}</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="summary-item">
                                        <label class="fw-bold">Producción (Ton/Mes):</label>
                                        <p class="mb-0">
                                            @if($produccionTotal !== 'N/A')
                                                {{ number_format($produccionTotal, 2) }}
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ÁREAS INDIVIDUALES --}}
                        <h5 class="mb-3">🌴 Áreas Registradas</h5>
                        @foreach ($visita->areas as $index => $area)
                            <div class="area-info-card mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="mb-0">Área #{{ $index + 1 }} - {{ $area->variedad }} - {{ $area->material }}</h5>
                                    <span class="badge {{ $area->estado === 'produccion' ? 'bg-success' : 'bg-primary' }}">
                                        {{ ucfirst($area->estado) }}
                                    </span>
                                </div>
                                
                                <div class="row">
                                    {{-- INFORMACIÓN BÁSICA --}}
                                    <div class="col-md-6">
                                        <h6 class="text-muted border-bottom pb-1">Información Básica</h6>
                                        <ul class="list-unstyled">
                                            <li><strong>Año siembra:</strong> {{ date('Y', strtotime($area->anio_siembra)) }}</li>
                                            <li><strong></strong> {{ $area->area }}</li>
                                            <li><strong>Aplica Orden Plantis:</strong> {{ $area->aplica_orden_plantis ? 'Sí' : 'No' }}</li>
                                            @if ($area->aplica_orden_plantis)
                                                <li><strong>Orden Plantis N°:</strong> {{ $area->orden_plantis_numero ?? 'N/A' }}</li>
                                                <li><strong>Número de Plantas (Orden Plantis):</strong> {{ $area->numero_plantas_orden_plantis ?? 'N/A' }}</li>
                                                <li><strong>Estado Orden Plantis:</strong> {{ $area->estado_oren_plantis ?? 'N/A' }}</li>
                                            @endif
                                        </ul>
                                    </div>

                                    {{-- INFORMACIÓN ESPECÍFICA SEGÚN ESTADO --}}
                                    <div class="col-md-6">
                                        @if($area->estado === 'desarrollo')
                                            <h6 class="text-muted border-bottom pb-1">🌱 Información de Desarrollo</h6>
                                            <ul class="list-unstyled">
                                                <li><strong>Área Desarrollo (Ha):</strong> {{ $area->area_palmas_desarrollo_hectareas ?? 'N/A' }}</li>
                                                <li><strong>Palmas Desarrollo:</strong> {{ $area->numero_palmas_desarrollo ?? 'N/A' }}</li>
                                                <li><strong>Área Producción (Ha):</strong> <span class="text-muted">N/A</span></li>
                                                <li><strong>Palmas Producción:</strong> <span class="text-muted">N/A</span></li>
                                            </ul>
                                        @elseif($area->estado === 'produccion')
                                            <h6 class="text-muted border-bottom pb-1">🌴 Información de Producción</h6>
                                            <ul class="list-unstyled">
                                                <li><strong>Área Desarrollo (Ha):</strong> <span class="text-muted">N/A</span></li>
                                                <li><strong>Palmas Desarrollo:</strong> <span class="text-muted">N/A</span></li>
                                                <li><strong>Área Producción (Ha):</strong> {{ $area->area_palmas_produccion_hectareas ?? 'N/A' }}</li>
                                                <li><strong>Palmas Producción:</strong> {{ $area->numero_palmas_produccion ?? 'N/A' }}</li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>

                                {{-- DATOS DEL RESUMEN (comunes a todas las áreas) --}}
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <h6 class="text-muted border-bottom pb-1">📈 Datos Generales</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <small><strong>Área Total Finca (Ha):</strong> 
                                                    @if($area->area_total_finca_hectareas)
                                                        {{ number_format($area->area_total_finca_hectareas, 2) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="col-md-3">
                                                <small><strong>Palmas Total Finca:</strong> 
                                                    @if($area->numero_palmas_total_finca)
                                                        {{ number_format($area->numero_palmas_total_finca, 0) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="col-md-3">
                                                <small><strong>Ciclos de Cosecha:</strong> {{ $area->ciclos_cosecha ?? 'N/A' }}</small>
                                            </div>
                                            <div class="col-md-3">
                                                <small><strong>Producción (Ton/Mes):</strong> 
                                                    @if($area->produccion_toneladas_por_mes)
                                                        {{ number_format($area->produccion_toneladas_por_mes, 2) }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-3">
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
                                <li><strong>Análisis suelo:</strong> {{ ucfirst($visita->suelo->analisis_suelo) }}</li>
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
                        <select name="conformacion" id="conformacion" class="form-control">
                            <option value="">Seleccione una clase</option>
                            <option value="clase 1" {{ ($evaluacionEntry->conformacion ?? '') === 'clase 1' ? 'selected' : '' }}>Clase 1</option>
                            <option value="clase 2" {{ ($evaluacionEntry->conformacion ?? '') === 'clase 2' ? 'selected' : '' }}>Clase 2</option>
                            <option value="clase 3" {{ ($evaluacionEntry->conformacion ?? '') === 'clase 3' ? 'selected' : '' }}>Clase 3</option>
                            <option value="clase 4" {{ ($evaluacionEntry->conformacion ?? '') === 'clase 4' ? 'selected' : '' }}>Clase 4</option>
                        </select>
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
    // Índice único para bloques
    let evaluacionFormBlockIndex = 0;

    // Clases disponibles para conformación
    const CONFORMACION_CLASSES = ['clase 1','clase 2','clase 3','clase 4'];

    /**
     * Añade un nuevo bloque de formulario de evaluación completo.
     * Precarga conformación si data.conformacion viene en formato "clase 1:20%, clase 3:50%".
     */
    function addEvaluacionFormBlock(data = {}) {
        const container = document.getElementById('evaluacion-forms-container');
        const index = evaluacionFormBlockIndex;
        const block = document.createElement('div');
        block.classList.add('evaluacion-form-block');
        block.setAttribute('data-index', index);

        const variedadFrutoValue = data.variedad_fruto ?? '';
        const cantidadRacimosValue = data.cantidad_racimos ?? '';
        const verdeValue = data.verde ?? '';
        const maduroValue = data.maduro ?? '';
        const sobremaduroValue = data.sobremaduro ?? '';
        const pedunculoValue = data.pedunculo ?? '';
        const conformacionValue = data.conformacion ?? ''; // string legacy o vacío
        const observacionesValue = data.observaciones ?? '';

        // HTML del bloque (incluye el hidden input para enviar el string final)
        block.innerHTML = `
            <button type="button" class="remove-block-btn" onclick="removeEvaluacionFormBlock(this)">✖️</button>

            <div class="mb-3">
                <label for="evaluaciones_${index}_variedad_fruto" class="form-label">Variedad del fruto:</label>
                <select name="evaluaciones[${index}][variedad_fruto]" id="evaluaciones_${index}_variedad_fruto" class="form-select" required onchange="toggleConformacion(this, ${index})">
                    <option value="">Seleccione</option>
                    <option value="guinense" ${variedadFrutoValue === 'guinense' ? 'selected' : ''}>Guinense</option>
                    <option value="hibrido" ${variedadFrutoValue === 'hibrido' ? 'selected' : ''}>Híbrido</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="evaluaciones_${index}_cantidad_racimos" class="form-label">Cantidad de racimos:</label>
                <input type="number" name="evaluaciones[${index}][cantidad_racimos]" id="evaluaciones_${index}_cantidad_racimos" class="form-control" min="0" value="${cantidadRacimosValue}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="evaluaciones_${index}_verde" class="form-label">Verde (%):</label>
                    <input type="number" name="evaluaciones[${index}][verde]" id="evaluaciones_${index}_verde" class="form-control" min="0" max="100" value="${verdeValue}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="evaluaciones_${index}_maduro" class="form-label">Maduro (%):</label>
                    <input type="number" name="evaluaciones[${index}][maduro]" id="evaluaciones_${index}_maduro" class="form-control" min="0" max="100" value="${maduroValue}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="evaluaciones_${index}_sobremaduro" class="form-label">Sobremaduro (%):</label>
                    <input type="number" name="evaluaciones[${index}][sobremaduro]" id="evaluaciones_${index}_sobremaduro" class="form-control" min="0" max="100" value="${sobremaduroValue}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="evaluaciones_${index}_pedunculo" class="form-label">Pedúnculo (%):</label>
                    <input type="number" name="evaluaciones[${index}][pedunculo]" id="evaluaciones_${index}_pedunculo" class="form-control" min="0" max="100" value="${pedunculoValue}" required>
                </div>
            </div>

            <!-- CONFORMACIÓN: visible solo si variedad === 'hibrido' -->
            <div class="mb-3 conformacion-group" id="conformacion_group_${index}" style="display: ${variedadFrutoValue === 'hibrido' ? 'block' : 'none'};">
                <label class="form-label">Conformación (múltiple):</label>
                <div class="conformacion-multiple" id="conformacion_multiple_${index}">
                    ${CONFORMACION_CLASSES.map(clase => `
                        <div class="d-flex mb-2 align-items-center">
                            <input 
                                type="checkbox" 
                                class="form-check-input me-2 conformacion-checkbox"
                                data-index="${index}"
                                id="conformacion_${index}_${escapeForId(clase)}"
                                value="${clase}"
                                onchange="updateConformacionString(${index})"
                            >
                            <label for="conformacion_${index}_${escapeForId(clase)}" class="me-2 mb-0">${clase}</label>
                            <input 
                                type="number" 
                                min="0" 
                                max="100" 
                                class="form-control conformacion-percent"
                                style="width: 100px;"
                                placeholder="%"
                                data-clase="${clase}"
                                data-index="${index}"
                                oninput="updateConformacionString(${index})"
                            >
                        </div>
                    `).join('')}
                </div>

                <!-- hidden input que recibe el string final -->
                <input type="hidden" name="evaluaciones[${index}][conformacion]" id="evaluaciones_${index}_conformacion" value="">
            </div>

            <div class="mb-3">
                <label for="evaluaciones_${index}_observaciones" class="form-label">Observaciones (Opcional):</label>
                <textarea name="evaluaciones[${index}][observaciones]" id="evaluaciones_${index}_observaciones" class="form-control" rows="2">${observacionesValue}</textarea>
            </div>
        `;

        container.appendChild(block);

        // Si vino string de conformacion (ej: "clase 1:20%, clase 3:50%"), precargar checks y porcentajes
        if (conformacionValue && conformacionValue.trim() !== '') {
            preloadConformacion(index, conformacionValue);
        } else {
            // inicializar hidden en blanco para este bloque
            document.getElementById(`evaluaciones_${index}_conformacion`).value = '';
        }

        // asegurar visibilidad correcta y actualización del string
        toggleConformacion(document.getElementById(`evaluaciones_${index}_variedad_fruto`), index);
        updateConformacionString(index);

        evaluacionFormBlockIndex++;
    }

    // Elimina bloque y si quedan 0 añade uno vacío
    function removeEvaluacionFormBlock(button) {
        if (!confirm('¿Estás seguro de que quieres eliminar este bloque de formulario de evaluación?')) return;
        button.closest('.evaluacion-form-block').remove();
        if (document.querySelectorAll('.evaluacion-form-block').length === 0) {
            addEvaluacionFormBlock();
        }
    }

    /**
     * Muestra u oculta la sección conformación dependiendo de la variedad.
     * Mantiene limpieza de inputs si se oculta.
     */
    function toggleConformacion(selectElement, index) {
        const group = document.getElementById(`conformacion_group_${index}`);
        if (!group) return;

        if (selectElement.value === 'hibrido') {
            group.style.display = 'block';
        } else {
            // ocultar y limpiar
            group.style.display = 'none';
            document.querySelectorAll(`.conformacion-checkbox[data-index="${index}"]`).forEach(cb => cb.checked = false);
            document.querySelectorAll(`.conformacion-percent[data-index="${index}"]`).forEach(inp => inp.value = '');
            updateConformacionString(index);
        }
    }

    /**
     * Construye el string final de conformacion a partir de checkboxes + porcentajes
     * y lo coloca en el hidden input correspondiente.
     * Formato: "clase 1:20%, clase 3:50%"
     */
    function updateConformacionString(index) {
        const checkboxes = document.querySelectorAll(`.conformacion-checkbox[data-index="${index}"]`);
        const parts = [];

        checkboxes.forEach(cb => {
            if (cb.checked) {
                const clase = cb.value;
                const percentInput = document.querySelector(`.conformacion-percent[data-index="${index}"][data-clase="${clase}"]`);
                let percent = percentInput && percentInput.value !== '' ? String(percentInput.value).trim() : '';
                // normalizar y evitar valores incorrectos
                if (percent !== '') {
                    // limitar entre 0 y 100
                    let n = parseFloat(percent);
                    if (isNaN(n)) n = 0;
                    if (n < 0) n = 0;
                    if (n > 100) n = 100;
                    percent = `${n}%`;
                } else {
                    percent = '0%';
                }
                parts.push(`${clase}:${percent}`);
            }
        });

        const hidden = document.getElementById(`evaluaciones_${index}_conformacion`);
        if (hidden) hidden.value = parts.join(', ');
    }

    /**
     * Precarga string de conformación en checkboxes y porcentajes del bloque.
     * Acepta formatos como "clase 1:20%, clase 3:50%" (espacios tolerados).
     */
    function preloadConformacion(index, conformacionString) {
        if (!conformacionString) return;
        // parsear
        const pairs = conformacionString.split(',').map(s => s.trim()).filter(s => s.length > 0);
        const map = {}; // clase => porcentaje (sin %)
        pairs.forEach(pair => {
            const [rawClase, rawPercent] = pair.split(':').map(x => x && x.trim());
            if (!rawClase) return;
            let percent = '';
            if (rawPercent) {
                percent = rawPercent.replace('%', '').trim();
            }
            map[rawClase] = percent;
        });

        // setear checkboxes y percent inputs si coinciden clases
        CONFORMACION_CLASSES.forEach(clase => {
            const cb = document.querySelector(`.conformacion-checkbox[data-index="${index}"][value="${clase}"]`);
            const inp = document.querySelector(`.conformacion-percent[data-index="${index}"][data-clase="${clase}"]`);
            if (!cb || !inp) return;
            if (map.hasOwnProperty(clase)) {
                cb.checked = true;
                inp.value = map[clase] ?? '';
            } else {
                cb.checked = false;
                inp.value = '';
            }
        });

        // actualizar el hidden
        updateConformacionString(index);
    }

    // Util para ids seguros
    function escapeForId(str) {
        return str.replace(/\s+/g, '_').replace(/[^A-Za-z0-9_]/g, '');
    }

    // DOMContentLoaded: precargar entradas existentes o añadir una vacía
    document.addEventListener('DOMContentLoaded', function() {
        const existingEvaluaciones = @json($visita->evaluacionCosechaCampo);

        if (existingEvaluaciones && existingEvaluaciones.length > 0) {
            existingEvaluaciones.forEach(evaluacionEntry => {
                // nota: aseguramos que la propiedad conformacion venga como string si existe
                addEvaluacionFormBlock({
                    ...evaluacionEntry,
                    conformacion: evaluacionEntry.conformacion ?? ''
                });
            });
        } else {
            addEvaluacionFormBlock();
        }
    });
</script>

@endsection

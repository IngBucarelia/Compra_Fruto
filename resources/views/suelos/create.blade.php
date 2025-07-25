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
    .area-info-card, .fertilizacion-info-card, .polinizacion-info-card, .sanidad-info-card {
        background-color: #f0fdf0;
        border: 1px solid #d4edda;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .area-info-card ul, .fertilizacion-info-card ul, .polinizacion-info-card ul, .sanidad-info-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .area-info-card li, .fertilizacion-info-card li, .polinizacion-info-card li, .sanidad-info-card li {
        padding: 5px 0;
        border-bottom: 1px dashed #e2e6ea;
    }
    .area-info-card li:last-child, .fertilizacion-info-card li:last-child, .polinizacion-info-card li:last-child, .sanidad-info-card li:last-child {
        border-bottom: none;
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
    <h3 class="title">🧪Información previa de plantación - Análisis de Suelo y Foliar 🧬</h3><h3> <br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span><br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
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

    {{-- Acordeones con formularios anteriores --}}
    <div class="accordion mb-4" id="acordeonSuelo">

        {{-- Área --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-expanded="true">
                    📍 Área(s) registrada(s)
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" data-bs-parent="#acordeonSuelo">
                <div class="accordion-body">
                    @if ($visita->areas->count() > 0) {{-- ✅ Cambiado a $visita->areas->count() --}}
                        @foreach ($visita->areas as $area) {{-- ✅ Iterar sobre las áreas --}}
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
            <div id="collapseFert" class="accordion-collapse collapse" data-bs-parent="#acordeonSuelo">
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
            <div id="collapsePol" class="accordion-collapse collapse" data-bs-parent="#acordeonSuelo">
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

        {{-- Sanidad --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSanidad">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSanidad">
                    🧪 Sanidad registrada
                </button>
            </h2>
            <div id="collapseSanidad" class="accordion-collapse collapse" data-bs-parent="#acordeonSuelo">
                <div class="accordion-body">
                    @if ($visita->sanidades->count() > 0) {{-- ✅ Cambiado a $visita->sanidades->count() --}}
                        @foreach ($visita->sanidades as $sanidad) {{-- ✅ Iterar sobre las sanidades --}}
                            <div class="sanidad-info-card mb-3">
                                <h5>Sanidad #{{ $loop->index + 1 }}</h5>
                                <ul>
                                    <li><strong>Opsophanes:</strong> {{ $sanidad->opsophanes ?? '-' }}%</li>
                                    <li><strong>Pudrición cogollo:</strong> {{ $sanidad->pudricion_cogollo ?? '-' }}%</li>
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

    </div>
    <h3 class="title">🧬Formulario de Análisis de Suelo y Foliar </h3>

            @if ($visita->suelo) {{-- ✅ Se mantiene $visita->suelo (singular) si solo hay un registro de suelo por visita --}}
            {{-- Mostrar detalles del suelo con botón para editar --}}
            <div class="card mb-4">
                <div class="card-header">🧾 Análisis de Suelo Registrado</div>
                <div class="card-body">
                    <p><strong>Análisis foliar:</strong> {{ ucfirst($visita->suelo->analisis_foliar) }}</p>
                    <p><strong>Análisis suelo:</strong> {{ ucfirst($visita->suelo->alanisis_suelo) }}</p>
                    <p><strong>Tipo de suelo:</strong> {{ ucfirst($visita->suelo->tipo_suelo) }}</p>
                    <a href="{{ route('suelos.edit', $visita->suelo->id) }}" class="btn btn-warning">✏️ Editar análisis</a>
                    <form method="POST" action="{{ route('suelos.destroy', $visita->suelo->id) }}" class="d-inline" onsubmit="return confirm('¿Deseas eliminar este análisis de suelo?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">🗑️ Eliminar análisis</button>
                    </form>
                </div>
            </div>
        @else
            {{-- Mostrar formulario si no hay registro --}}
            <form method="POST" action="{{ route('suelos.store') }}">
                @csrf
                <input type="hidden" name="visita_id" value="{{ $visita->id }}">

                <div class="mb-3">
                    <label>¿Análisis foliar?</label>
                    <select name="analisis_foliar" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>¿Análisis de suelo?</label>
                    <select name="alanisis_suelo" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tipo de suelo</label>
                    <select name="tipo_suelo" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="arenoso">Arenoso</option>
                        <option value="arcilloso">Arcilloso</option>
                        <option value="franco">Franco</option>
                        <option value="limoso">Limoso</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">💾 Guardar análisis de suelo</button>
            </form>
        @endif
           <button> <a href="{{ route('labores_cultivo.create', ['visita_id' => $visita->id]) }}" >
                ➡️ Continuar con Labores de Cultivo
            </a> </button>         
</div>
@endsection

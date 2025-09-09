@extends('layouts.app')

@section('content')

<style>
    .container{
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
        width: 120%;
        border-radius: 8px; /* Añadido para consistencia */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Añadido para consistencia */
        max-width: 900px !important; /* Ajusta el ancho para mejor visualización */
        
        margin-top: 25px; /* Margen superior para separación */
    }

    .info-header {
        text-align: center;
        font-family: Arial Black;
        font-weight: bold;
        font-size: 24px;
        color: #fdffe5;
        text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
        margin-bottom: 20px;
    }

    .info-detail span {
        color: wheat;
    }
    

    .accordion-item .accordion-button {
        background-color: darkseagreen !important;
        color: aliceblue !important;
        font-weight: bold;
    }
    .accordion-item .accordion-body {
        background-color: rgb(209, 241, 209) !important;
        color: rgb(31, 32, 34);
    }

    .data-card {
        background-color: #f0fff0;
        border: 1px solid #d4edda;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .data-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .data-card li {
        padding: 5px 0;
        border-bottom: 1px dashed #e2e6ea;
    }
    .data-card li:last-child {
        border-bottom: none;
    }

    .firma-img, .img-thumb {
        max-height: 150px; /* Ajustado para mejor visualización */
        width: auto;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .img-thumb {
        max-width: 100%; /* Asegura que no se desborde en columnas pequeñas */
        height: auto;
    }

    /* Media Queries para Responsividad */
    @media (max-width: 767.98px) {
        .container {
            margin-left: -35px !important;
            width: 100%;
            padding: 15px;
            border-radius: 0;
            box-shadow: none;
        }
        .info-header {
            font-size: 20px;
        }
        .accordion-button {
            font-size: 0.9em;
        }
        .data-card {
            padding: 10px;
        }
        .firma-img, .img-thumb {
            max-height: 100px;
        }
    }
</style>

<div class="container">
    <h3 class="info-header">🌴🌴 Información de plantación - Detalle Completo de Visita Realizado el {{ $visita->fecha }}</h3>
    <h4 class="info-detail">Proveedor: <span style="color: wheat">{{ $visita->proveedor->proveedor_nombre }}</span><br>
        Plantación: <span style="color: wheat">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span>
    </h4>
    <div class="accordion mt-4" id="acordeonDetalleVisita">

        {{-- Área --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAreas">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAreas" aria-expanded="true">
                    📍 Área(s) registrada(s)
                </button>
            </h2>
            <div id="collapseAreas" class="accordion-collapse collapse show" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->areas->count() > 0) {{-- ✅ CAMBIO: Iterar sobre la colección 'areas' --}}
                        @foreach ($visita->areas as $area)
                            <div class="data-card mb-3">
                                <h5>Área #{{ $loop->index + 1 }} - Material: {{ $area->material }}</h5>
                                <ul>
                                    <li><strong>Material:</strong> {{ $area->material }}</li>
                                    <li><strong>Estado:</strong> {{ $area->estado }}</li>
                                    <li><strong>Año siembra:</strong> {{ $area->anio_siembra }}</li>
                                    <li><strong>Área (m²):</strong> {{ $area->area }}</li>
                                    <li><strong>Orden Plantis:</strong> {{ $area->orden_plantis_numero }}</li>
                                    <li><strong>Estado orden Plantis:</strong> {{ $area->estado_oren_plantis }}</li>
                                    <li><strong>Área total finca (hectáreas):</strong> {{ $area->area_total_finca_hectareas }}</li>
                                    <li><strong>Número de palmas total finca:</strong> {{ $area->numero_palmas_total_finca }}</li>
                                    <li><strong>Área palmas desarrollo (hectáreas):</strong> {{ $area->area_palmas_desarrollo_hectareas }}</li>
                                    <li><strong>Número de palmas desarrollo:</strong> {{ $area->numero_palmas_desarrollo }}</li>
                                    <li><strong>Área palmas producción (hectáreas):</strong> {{ $area->area_palmas_produccion_hectareas }}</li>
                                    <li><strong>Número de palmas producción:</strong> {{ $area->numero_palmas_produccion }}</li>
                                    <li><strong>Ciclos de cosecha:</strong> {{ $area->ciclos_cosecha }}</li>
                                    <li><strong>Producción (toneladas/mes):</strong> {{ $area->produccion_toneladas_por_mes }}</li>
                                    <li><strong>Aplica orden plantis:</strong> {{ $area->aplica_orden_plantis ? 'Sí' : 'No' }}</li>
                                    <li><strong>Número plantas orden plantis:</strong> {{ $area->numero_plantas_orden_plantis }}</li>
                                </ul>
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
                                                        <strong>Estado:</strong> {{ $enf->estado ?? '-' }}%
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
                                                        <strong>Estado:</strong> {{ $pla->estado ?? '-' }}
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
        </div>


        {{-- Suelo --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSuelo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSuelo">
                    🧬 Análisis de Suelo
                </button>
            </h2>
            <div id="collapseSuelo" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->suelo)
                        <div class="data-card">
                            <ul>
                                <li><strong>Análisis foliar:</strong> {{ ucfirst($visita->suelo->analisis_foliar) }}</li>
                                <li><strong>Análisis suelo:</strong> {{ ucfirst($visita->suelo->analisis_suelo) }}</li>
                                <li><strong>Tipo de suelo:</strong> {{ ucfirst($visita->suelo->tipo_suelo) }}</li>
                            </ul>
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado análisis de suelo.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Labores de Cultivo --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingLabores">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLabores">
                    🌾 Labores de Cultivo
                </button>
            </h2>
            <div id="collapseLabores" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->laboresCultivo->count() > 0) {{-- ✅ CAMBIO: Iterar sobre la colección 'laboresCultivo' --}}
                        @foreach ($visita->laboresCultivo as $labor)
                            <div class="data-card mb-3">
                                <h5>Labor para: {{ ucfirst($labor->tipo_planta ?? 'N/A') }}</h5>
                                <ul>
                                    <li><strong>Observaciones:</strong> {{ $labor->observaciones ?? 'No registradas' }}</li>
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
                                        @if (isset($labor->$campo))
                                            <li><strong>{{ $label }}:</strong> {{ $labor->$campo }}%</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No se han registrado labores de cultivo.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Evaluación de Cosecha en Campo --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingEvaluacionCosecha">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEvaluacionCosecha">
                    🍌 Evaluación de Cosecha en Campo
                </button>
            </h2>
            <div id="collapseEvaluacionCosecha" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->evaluacionCosechaCampo->count() > 0) {{-- ✅ CAMBIO: Iterar sobre la colección 'evaluacionCosechaCampo' --}}
                        @foreach ($visita->evaluacionCosechaCampo as $evaluacion)
                            <div class="data-card mb-3">
                                <h5>Evaluación #{{ $loop->index + 1 }} - Variedad: {{ ucfirst($evaluacion->variedad_fruto) }}</h5>
                                <ul>
                                    <li><strong>Variedad de Fruto:</strong> {{ ucfirst($evaluacion->variedad_fruto) }}</li>
                                    <li><strong>Cantidad de Racimos:</strong> {{ $evaluacion->cantidad_racimos }}</li>
                                    <li><strong>Verde:</strong> {{ $evaluacion->verde }}%</li>
                                    <li><strong>Maduro:</strong> {{ $evaluacion->maduro }}%</li>
                                    <li><strong>Sobre Maduro:</strong> {{ $evaluacion->sobremaduro }}%</li>
                                    <li><strong>Pedúnculo:</strong> {{ $evaluacion->pedunculo }}%</li>
                                    @if ($evaluacion->variedad_fruto === 'hibrido') {{-- ✅ NUEVO CAMPO CONDICIONAL --}}
                                        <li><strong>Conformación:</strong> {{ $evaluacion->conformacion ?? 'No especificada' }}</li>
                                    @endif
                                    <li><strong>Observaciones:</strong> {{ $evaluacion->observaciones ?? 'No registradas' }}</li>
                                </ul>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No se ha registrado evaluación de cosecha.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cierre de Visita --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCierre">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCierre">
                    🔏 Cierre de Visita
                </button>
            </h2>
            <div id="collapseCierre" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->cierreVisita)
                        <div class="data-card">
                            <ul>
                                <li><strong>Fecha de Cierre:</strong> {{ $visita->cierreVisita->fecha_cierre ? $visita->cierreVisita->fecha_cierre->format('d/m/Y') : 'N/A' }}</li>
                                <li><strong>Estado de la Visita:</strong> {{ $visita->cierreVisita->estado_visita ?? 'N/A' }}</li>
                                <li><strong>Observaciones Finales:</strong> {{ $visita->cierreVisita->observaciones_finales ?? 'No registradas' }}</li>
                                <li><strong>Recomendaciones:</strong> {{ $visita->cierreVisita->recomendaciones ?? 'No se especificaron' }}</li>
                                <li><strong>Finalizada En:</strong> {{ $visita->cierreVisita->finalizada_en ? $visita->cierreVisita->finalizada_en->format('d/m/Y H:i') : 'N/A' }}</li>
                                <li><strong>Responsable cierre:</strong> {{ $visita->tecnico->name ?? 'N/A' }}</li>
                            </ul>

                            {{-- Firmas --}}
                            @if ($visita->cierreVisita->firma_responsable)
                                <div class="mt-3">
                                    <strong>📄 Firma Responsable de Visita:</strong><br>
                                    <img src="{{ $visita->cierreVisita->firma_responsable }}" alt="Firma Responsable" class="firma-img">
                                </div>
                            @endif
                            @if ($visita->cierreVisita->firma_recibe)
                                <div class="mt-3">
                                    <strong>📄 Firma de quien recibió la visita:</strong><br>
                                    <img src="{{ $visita->cierreVisita->firma_recibe }}" alt="Firma Recibe" class="firma-img">
                                </div>
                            @endif
                            @if ($visita->cierreVisita->firma_testigo)
                                <div class="mt-3">
                                    <strong>📄 Firma del testigo:</strong><br>
                                    <img src="{{ $visita->cierreVisita->firma_testigo }}" alt="Firma Testigo" class="firma-img">
                                </div>
                            @endif

                             {{-- Imágenes finales --}}
                                @php
                                    // Manejo seguro del campo 'imagenes'
                                    $imagenes = [];
                                    if ($visita->cierreVisita && $visita->cierreVisita->imagenes) {
                                        $imagenes = is_array($visita->cierreVisita->imagenes) 
                                            ? $visita->cierreVisita->imagenes 
                                            : json_decode($visita->cierreVisita->imagenes, true) ?? [];
                                    }
                                @endphp
                                
                                {{-- Verificamos si hay imágenes --}}
                                @if (count($imagenes) > 0)
                                    <div class="mt-4">
                                        <strong>🖼️ Tomas destacadas durante la visita:</strong><br>
                                        <div class="row">
                                            @foreach ($imagenes as $img)
                                                <div class="col-md-4 col-6 mb-3">
                                                    <img src="{{ $img }}" class="img-fluid rounded shadow img-thumb">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado el cierre de visita.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <br><br>
    <!-- Botones de exportación -->
    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
        <button onclick="descargarPDFConSweet()" class="btn btn-danger">
            📥 Exportar PDF
        </button>
        <button onclick="descargarExcelConSweet()" class="btn btn-success">
            📊 Exportar a Excel
        </button>
        <a href="{{ route('visitas.index') }}" class="btn btn-secondary">⬅️ Volver</a>
    </div>

    <!-- Iframes ocultos para la descarga -->
    <iframe id="descargaPDFiframe" style="display:none;"></iframe>
    <iframe id="descargaExcelIframe" style="display: none;"></iframe>

    <!-- SweetAlert y Scripts de descarga -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function descargarPDFConSweet() {
    Swal.fire({
        title: 'Generando PDF...',
        text: 'Esto puede tardar unos segundos',
        imageUrl: '{{ asset('images/loader.gif') }}',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false
    });

    const url = "{{ route('visitas.exportar.pdf', $visita->id) }}";

    // Llamamos a la URL en una nueva pestaña para evitar que el navegador
    // bloquee la descarga. El servidor debe enviar la cabecera 'Content-Disposition'
    // para forzar la descarga del archivo.
    window.open(url, '_blank');

    // Esperamos un momento antes de cerrar el SweetAlert.
    // Aunque el archivo se descargue, la ventana emergente de SweetAlert debe cerrarse
    // manualmente ya que no tiene forma de saber cuando el archivo se ha descargado.
    setTimeout(() => {
        Swal.close();
    }, 5000); // 5 segundos es usualmente suficiente.
}

        function descargarExcelConSweet() {
            Swal.fire({
                title: 'Generando Excel...',
                text: 'Esto puede tardar unos segundos',
                imageUrl: '{{ asset('images/loader.gif') }}',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });

            document.getElementById('descargaExcelIframe').src = "{{ route('visitas.exportar.excel', $visita->id) }}";

            setTimeout(() => {
                Swal.close();
            }, 4000); // Ajusta si se demora más
        }
    </script>
</div>
@endsection

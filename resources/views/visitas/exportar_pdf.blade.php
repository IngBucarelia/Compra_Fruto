<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Visita</title>
    <style>
        /* La fuente DejaVu Sans es crucial para que Dompdf muestre caracteres especiales como emojis */
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1, h2 { color: #2F4F4F; margin-bottom: 5px; }
        .section { margin-bottom: 25px; }
        .section h2 { border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 10px; }
        table th, table td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        ul { margin: 5px 0 10px 20px; padding: 0; }
        /* Ajusta el tamaño de las miniaturas de imagen para el PDF */
        .img-thumb { max-height: 920px; max-width: 720px; margin: 5px; display: inline-block; border: 1px solid #eee; }
        .firma-img { max-height: 100px; max-width: 200px; margin: 5px; display: block; border: 1px solid #eee; }
        .title-bar { background: #e0f7fa; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .data-card {
            background-color: #f0fff0;
            border: 1px solid #d4edda;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .data-card h5 {
            margin-top: 0;
            margin-bottom: 8px;
            color: #2F4F4F;
        }

        .company-logo {
            width: 180px; /* Ancho fijo */
            height: 60px; /* Alto fijo (ajusta según proporciones de tu logo) */
            object-fit: contain; /* Mantiene las proporciones */
            margin: 0 auto; /* Centrado horizontal */
            display: block; /* Para que funcione el margin auto */
        }
    </style>
</head>
<body>
    

    <div class="title-bar">
        <div class="logo-container">
        <img src="{{ public_path('images/logo2.webp') }}" class="company-logo" alt="Logo de la empresa">
    </div>
        <h1> Detalle completo de la Visita Técnica</h1>
        <p><strong>Proveedor:</strong> {{ $visita->proveedor->proveedor_nombre }}</p>
        <p><strong>Plantación:</strong> {{ $visita->plantacion->nombre ?? 'No registrada' }}</p>
        <p><strong>Responsable de Visita:</strong> {{ $visita->tecnico_campo->name ?? 'No registrada' }}</p>
        <p><strong>Recibió la Visita :</strong> {{ $visita->recibio_visita ?? 'No registrada' }}</p>
        <p><strong>Fecha de visita:</strong> {{ $visita->fecha }}</p>
    </div>

    {{-- Área --}}
    <div class="section">
        <h2> Área(s) registrada(s)</h2>
        @forelse ($visita->areas as $area) {{-- ✅ CAMBIO CLAVE: Iterar sobre la colección 'areas' --}}
            <div class="data-card">
                <h5>Área #{{ $loop->index + 1 }} - Material: {{ $area->material }}</h5>
                <table>
                    <tr><th>Variedad</th><td>{{ $area->variedad }}</td></tr>
                    <tr><th>Material</th><td>{{ $area->material }}</td></tr>
                    <tr><th>Estado</th><td>{{ $area->estado }}</td></tr>
                    <tr><th>Año de siembra</th><td>{{ $area->anio_siembra }}</td></tr>
                    <tr><th>Área (m²)</th><td>{{ $area->area }}</td></tr>
                    <tr><th>Orden Plantis</th><td>{{ $area->orden_plantis_numero }}</td></tr>
                    <tr><th>Estado Orden Plantis</th><td>{{ $area->estado_oren_plantis }}</td></tr>
                    <tr><th>Área total finca (ha)</th><td>{{ $area->area_total_finca_hectareas }}</td></tr>
                    <tr><th>Número de palmas total finca</th><td>{{ $area->numero_palmas_total_finca }}</td></tr>
                    <tr><th>Área palmas en desarrollo (ha)</th><td>{{ $area->area_palmas_desarrollo_hectareas }}</td></tr>
                    <tr><th>Número de palmas en desarrollo</th><td>{{ $area->numero_palmas_desarrollo }}</td></tr>
                    <tr><th>Área palmas en producción (ha)</th><td>{{ $area->area_palmas_produccion_hectareas }}</td></tr>
                    <tr><th>Número de palmas en producción</th><td>{{ $area->numero_palmas_produccion }}</td></tr>
                    <tr><th>Ciclos de cosecha</th><td>{{ $area->ciclos_cosecha }}</td></tr>
                    <tr><th>Producción (toneladas/mes)</th><td>{{ $area->produccion_toneladas_por_mes }}</td></tr>
                    <tr><th>¿Aplica Orden Plantis?</th><td>{{ $area->aplica_orden_plantis ? 'Sí' : 'No' }}</td></tr>
                    <tr><th>Número de plantas Orden Plantis</th><td>{{ $area->numero_plantas_orden_plantis }}</td></tr>
                </table>

            </div>
        @empty
            <p>No se registró información de área.</p>
        @endforelse
    </div>

    {{-- Fertilizaciones --}}
    <div class="section">
        <h2> Fertilizaciones</h2>
        @forelse ($visita->fertilizaciones as $fert)
            <div class="data-card">
                <p><strong>Fecha en que se Aplico:</strong> {{ $fert->fecha_fertilizacion }}</p>
                <ul>
                    @foreach ($fert->detalles as $f)
                        <li>{{ ucfirst($f->fertilizante) }} - {{ $f->cantidad }} kg</li>
                    @endforeach
                </ul>
            </div>
        @empty
            <p>No hay fertilizaciones registradas.</p>
        @endforelse
    </div>

    {{-- Polinizaciones --}}
    <div class="section">
        <h2> Polinizaciones</h2>
        @forelse ($visita->polinizaciones as $poli)
            <div class="data-card">
                <p>
                     {{ $poli->fecha }} |
                    Pases: {{ $poli->n_pases }} |
                    Ronda: {{ $poli->ciclos_ronda }} |
                    ANA: {{ $poli->ana }} ({{ $poli->tipo_ana }}) |
                    Talco: {{ $poli->talco }}
                </p>
            </div>
        @empty
            <p>No hay polinizaciones registradas.</p>
        @endforelse
    </div>

    
<div class="section">
    <h2>Sanidad</h2>

    @if ($visita->sanidades->count() > 0)
        @foreach ($visita->sanidades as $sanidad)
            <div class="data-card mb-4">
                <h4>Sanidad #{{ $loop->index + 1 }}</h4>
                <table>
                    {{-- Otros datos generales --}}
                    <tr>
                        <th>Otros</th>
                        <td>{{ $sanidad->otros ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Observaciones</th>
                        <td>{{ $sanidad->observaciones ?? 'Sin observaciones' }}</td>
                    </tr>
                    <tr>
                        <th>Censo de enfermedades</th>
                        <td>{{ $sanidad->censo_enfermedades ? 'Sí' : 'No' }}</td>
                    </tr>
                    <tr>
                        <th>Ciclos lectura enfermedades</th>
                        <td>{{ $sanidad->ciclos_lectura_enfermedades ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Ciclos lectura plagas</th>
                        <td>{{ $sanidad->ciclos_lectura_plagas ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            {{-- Enfermedades nuevas --}}
            @if ($sanidad->enfermedades && $sanidad->enfermedades->count())
                <div class="data-card mb-3">
                    <h4>Enfermedades</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sanidad->enfermedades as $enf)
                                <tr>
                                    <td>{{ $enf->nombre_enfermedad }}</td>
                                    <td>{{ $enf->estado ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Plagas nuevas --}}
            @if ($sanidad->plagas && $sanidad->plagas->count())
                <div class="data-card mb-3">
                    <h4>Plagas</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sanidad->plagas as $pla)
                                <tr>
                                    <td>{{ $pla->nombre_plaga }}</td>
                                    <td>{{ $pla->estado ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Enfermedades legacy --}}
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
                <div class="data-card mb-3">
                    <h4>Enfermedades (legacy)</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($legacyEnfermedades as $field => $label)
                                @if ($sanidad->$field)
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>{{ $sanidad->$field }}%</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Plaga legacy --}}
            @if ($sanidad->plaga)
                <div class="data-card mb-3">
                    <h4>Plaga (legacy)</h4>
                    <p>{{ $sanidad->plaga }} @if($sanidad->estado_plaga) - ({{ $sanidad->estado_plaga }}) @endif</p>
                </div>
            @endif

            {{-- Trampas --}}
            @if ($sanidad->trampas->count() > 0)
                <div class="data-card mb-4">
                    <h4>Datos de Trampas de Palmarum</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Ciclos</th>
                                <th>Machos Capturados</th>
                                <th>Hembras Capturadas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sanidad->trampas as $trampa)
                                <tr>
                                    <td>{{ $trampa->ciclos ?? '-' }}</td>
                                    <td>{{ $trampa->machos_capturados ?? '-' }}</td>
                                    <td>{{ $trampa->hembras_capturadas ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No se registraron trampas de Palmarum para esta sanidad.</p>
            @endif

        @endforeach
    @else
        <p>No hay datos de sanidad.</p>
    @endif
</div>



    {{-- Suelo --}}
    <div class="section">
        <h2> Análisis de Suelo</h2>
        @if ($visita->suelo)
            <div class="data-card">
                <table>
                    <tr><th>Análisis Foliar</th><td>{{ ucfirst($visita->suelo->analisis_foliar) }}</td></tr>
                    <tr><th>Análisis Suelo</th><td>{{ ucfirst($visita->suelo->analisis_suelo) }}</td></tr>
                    <tr><th>Tipo de Suelo</th><td>{{ ucfirst($visita->suelo->tipo_suelo) }}</td></tr>
                </table>
            </div>
        @else
            <p>No se registró análisis de suelo.</p>
        @endif
    </div>

    {{-- Labores de Cultivo --}}
    <div class="section">
        <h2> Labores de Cultivo</h2>
        @forelse ($visita->laboresCultivo as $labor) {{-- ✅ CAMBIO CLAVE: Iterar sobre la colección 'laboresCultivo' --}}
            <div class="data-card">
                <h5>Labor para: {{ ucfirst($labor->tipo_planta ?? 'N/A') }}</h5>
                <table>
                    <tr><th>Observaciones</th><td>{{ $labor->observaciones ?? 'No registradas' }}</td></tr>
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
                    @foreach ($laboresLabels as $campo => $label)
                        @if (isset($labor->$campo))
                            <tr><th>{{ $label }}</th><td>{{ $labor->$campo }}%</td></tr>
                        @endif
                    @endforeach
                </table>
            </div>
        @empty
            <p>No se registraron labores.</p>
        @endforelse
    </div>

    {{-- Evaluación Cosecha --}}
    <div class="section">
        <h2> Evaluación de Cosecha en Campo</h2>
        @forelse ($visita->evaluacionCosechaCampo as $evaluacion) {{-- ✅ CAMBIO CLAVE: Iterar sobre la colección 'evaluacionCosechaCampo' --}}
            <div class="data-card">
                <h5>Evaluación #{{ $loop->index + 1 }} - Variedad: {{ ucfirst($evaluacion->variedad_fruto) }}</h5>
                <table>
                    <tr><th>Variedad Fruto</th><td>{{ ucfirst($evaluacion->variedad_fruto) }}</td></tr>
                    <tr><th>Cantidad Racimos</th><td>{{ $evaluacion->cantidad_racimos }}</td></tr>
                    <tr><th>Verde</th><td>{{ $evaluacion->verde }}%</td></tr>
                    <tr><th>Maduro</th><td>{{ $evaluacion->maduro }}%</td></tr>
                    <tr><th>Sobre Maduro</th><td>{{ $evaluacion->sobremaduro }}%</td></tr>
                    <tr><th>Pedúnculo</th><td>{{ $evaluacion->pedunculo }}%</td></tr>
                    @if ($evaluacion->variedad_fruto === 'hibrido') {{-- ✅ NUEVO CAMPO CONDICIONAL --}}
                        <tr><th>Conformación</th><td>{{ $evaluacion->conformacion ?? 'No especificada' }}</td></tr>
                    @endif
                    <tr><th>Observaciones</th><td>{{ $evaluacion->observaciones ?? 'No registradas' }}</td></tr>
                </table>
            </div>
        @empty
            <p>No se ha registrado evaluación.</p>
        @endforelse
    </div>

    {{-- Cierre de Visita --}}
<div class="section">
    <h2> Cierre de Visita</h2>
    @if ($visita->cierreVisita)
        <div class="data-card">
            <table>
                <tr><th>Fecha de Cierre</th><td>{{ $visita->cierreVisita->fecha_cierre ? $visita->cierreVisita->fecha_cierre->format('d/m/Y') : 'N/A' }}</td></tr>
                <tr><th>Estado de la Visita</th><td>{{ $visita->cierreVisita->estado_visita ?? 'N/A' }}</td></tr>
                <tr><th>Observaciones Finales</th><td>{{ $visita->cierreVisita->observaciones_finales ?? 'N/A' }}</td></tr>
                <tr><th>Recomendaciones</th><td>{{ $visita->cierreVisita->recomendaciones ?? 'N/A' }}</td></tr>
                <tr><th>Finalizada En</th><td>{{ $visita->cierreVisita->finalizada_en ? $visita->cierreVisita->finalizada_en->format('d/m/Y H:i') : 'N/A' }}</td></tr>
                <tr><th>Responsable cierre</th><td>{{ $visita->tecnico->name ?? 'N/A' }}</td></tr>
            </table>

            {{-- Firmas --}}
                @if ($visita->cierreVisita->firma_responsable)
                    <p><strong>Firma Responsable:</strong></p>
                    <img src="{{ $visita->cierreVisita->firma_responsable }}" class="firma-img" alt="Firma Responsable">
                @endif
                @if ($visita->cierreVisita->firma_recibe)
                    <p><strong>Firma de quien recibe:</strong></p>
                    <img src="{{ $visita->cierreVisita->firma_recibe }}" class="firma-img" alt="Firma Recibe">
                @endif
                @if ($visita->cierreVisita->firma_testigo)
                    <p><strong>Firma del testigo:</strong></p>
                    <img src="{{ $visita->cierreVisita->firma_testigo }}" class="firma-img" alt="Firma Testigo">
                @endif

                {{-- Imágenes finales --}}
                @if ($visita->cierreVisita && $visita->cierreVisita->imagenes)
                    @php
                        $imagenes = is_array($visita->cierreVisita->imagenes) 
                            ? $visita->cierreVisita->imagenes 
                            : json_decode($visita->cierreVisita->imagenes, true);
                    @endphp

                    @if (!empty($imagenes))
                        <div style="page-break-before: always;">
                            <p><strong>Tomas Destacadas Durante La Visita:</strong></p><br><br>
                            @foreach ($imagenes as $img)
                                <img src="{{ $img }}" style="max-width:250px; max-height:250px; margin:10px;" alt="Imagen de la visita">
                            @endforeach
                        </div>
                    @endif
                @endif

            </div>
    @else
        <p>No se ha registrado el cierre.</p>
    @endif
</div>
</body>
</html>

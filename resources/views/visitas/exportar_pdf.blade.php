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
        .img-thumb { max-height: 120px; max-width: 120px; margin: 5px; display: inline-block; border: 1px solid #eee; }
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
    </style>
</head>
<body>
    <div class="title-bar">
        <h1>🌴 Detalle completo de la Visita Técnica</h1>
        <p><strong>Proveedor:</strong> {{ $visita->proveedor->proveedor_nombre }}</p>
        <p><strong>Plantación:</strong> {{ $visita->plantacion->nombre ?? 'No registrada' }}</p>
        <p><strong>Fecha de visita:</strong> {{ $visita->fecha }}</p>
    </div>

    {{-- Área --}}
    <div class="section">
        <h2>📍 Área(s) registrada(s)</h2>
        @forelse ($visita->areas as $area) {{-- ✅ CAMBIO CLAVE: Iterar sobre la colección 'areas' --}}
            <div class="data-card">
                <h5>Área #{{ $loop->index + 1 }} - Material: {{ $area->material }}</h5>
                <table>
                    <tr><th>Material</th><td>{{ $area->material }}</td></tr>
                    <tr><th>Estado</th><td>{{ $area->estado }}</td></tr>
                    <tr><th>Año siembra</th><td>{{ $area->anio_siembra }}</td></tr>
                    <tr><th>Área (m²)</th><td>{{ $area->area }}</td></tr>
                    <tr><th>Orden Plantis</th><td>{{ $area->orden_plantis_numero }}</td></tr>
                    <tr><th>Estado Plantis</th><td>{{ $area->estado_oren_plantis }}</td></tr>
                </table>
            </div>
        @empty
            <p>No se registró información de área.</p>
        @endforelse
    </div>

    {{-- Fertilizaciones --}}
    <div class="section">
        <h2>💧 Fertilizaciones</h2>
        @forelse ($visita->fertilizaciones as $fert)
            <div class="data-card">
                <p><strong>Fecha:</strong> {{ $fert->fecha_fertilizacion }}</p>
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
        <h2>🌸 Polinizaciones</h2>
        @forelse ($visita->polinizaciones as $poli)
            <div class="data-card">
                <p>
                    📅 {{ $poli->fecha }} |
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

    {{-- Sanidad --}}
    <div class="section">
        <h2>🧪 Sanidad</h2>
        @if ($visita->sanidad)
            <div class="data-card">
                <table>
                    <tr><th>Opsophanes</th><td>{{ $visita->sanidad->opsophanes }}%</td></tr>
                    <tr><th>P. Cogollo</th><td>{{ $visita->sanidad->pudricion_cogollo }}%</td></tr>
                    <tr><th>Raspador</th><td>{{ $visita->sanidad->raspador }}%</td></tr>
                    <tr><th>Palmarum</th><td>{{ $visita->sanidad->palmarum }}%</td></tr>
                    <tr><th>Strategus</th><td>{{ $visita->sanidad->strategus }}%</td></tr>
                    <tr><th>Leptoparsa</th><td>{{ $visita->sanidad->leptopharsa }}%</td></tr>
                    <tr><th>Pestalotiopsis</th><td>{{ $visita->sanidad->pestalotiopsis }}%</td></tr>
                    <tr><th>P. Basal</th><td>{{ $visita->sanidad->pudricion_basal }}%</td></tr>
                    <tr><th>P. Estipe</th><td>{{ $visita->sanidad->pudricion_estipe }}%</td></tr>
                    <tr><th>Otros</th><td>{{ $visita->sanidad->otros }}</td></tr>
                    <tr><th>Observaciones</th><td>{{ $visita->sanidad->observaciones }}</td></tr>
                </table>
            </div>
        @else
            <p>No hay datos de sanidad.</p>
        @endif
    </div>

    {{-- Suelo --}}
    <div class="section">
        <h2>🧬 Análisis de Suelo</h2>
        @if ($visita->suelo)
            <div class="data-card">
                <table>
                    <tr><th>Análisis Foliar</th><td>{{ ucfirst($visita->suelo->analisis_foliar) }}</td></tr>
                    <tr><th>Análisis Suelo</th><td>{{ ucfirst($visita->suelo->alanalisis_suelo) }}</td></tr>
                    <tr><th>Tipo de Suelo</th><td>{{ ucfirst($visita->suelo->tipo_suelo) }}</td></tr>
                </table>
            </div>
        @else
            <p>No se registró análisis de suelo.</p>
        @endif
    </div>

    {{-- Labores de Cultivo --}}
    <div class="section">
        <h2>🌾 Labores de Cultivo</h2>
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
        <h2>🍌 Evaluación de Cosecha en Campo</h2>
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
        <h2>🔏 Cierre de Visita</h2>
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
                    <p><strong>📄 Firma Responsable:</strong></p>
                    <img src="{{ $visita->cierreVisita->firma_responsable }}" class="firma-img" alt="Firma Responsable">
                @endif
                @if ($visita->cierreVisita->firma_recibe)
                    <p><strong>📄 Firma de quien recibe:</strong></p>
                    <img src="{{ $visita->cierreVisita->firma_recibe }}" class="firma-img" alt="Firma Recibe">
                @endif
                @if ($visita->cierreVisita->firma_testigo)
                    <p><strong>📄 Firma del testigo:</strong></p>
                    <img src="{{ $visita->cierreVisita->firma_testigo }}" class="firma-img" alt="Firma Testigo">
                @endif

                {{-- Imágenes finales --}}
                @if (is_array($visita->cierreVisita->imagenes) && count($visita->cierreVisita->imagenes))
                    <div>
                        <p><strong>🖼️ Imágenes Finales:</strong></p>
                        @foreach ($visita->cierreVisita->imagenes as $img)
                            <img src="{{ $img }}" class="img-thumb" alt="Imagen de la visita">
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <p>No se ha registrado el cierre.</p>
        @endif
    </div>
</body>
</html>

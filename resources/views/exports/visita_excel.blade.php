<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Visita</title>
    <style>
        /* Estilos básicos para la vista HTML que Maatwebsite/Excel intentará interpretar */
        body { font-family: sans-serif; font-size: 10px; }
        h1, h2, h3 { color: #2F4F4F; margin-bottom: 5px; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 10px; }
        table th, table td { border: 1px solid #ccc; padding: 4px; text-align: left; vertical-align: top; }
        th { background-color: #f0f0f0; }
        ul { margin: 0; padding-left: 15px; list-style-type: disc; }
        li { margin-bottom: 2px; }
        .section-header { background-color: #e0f7fa; padding: 8px; margin-top: 20px; margin-bottom: 10px; border-radius: 3px; font-weight: bold; }
        .data-block { margin-bottom: 15px; border: 1px solid #eee; padding: 10px; background-color: #f9f9f9; }
        .data-block h4 { margin-top: 0; margin-bottom: 8px; color: #4CAF50; }
    </style>
</head>
<body>
    <h1>Detalle Completo de la Visita Técnica</h1>

    <table>
        <tr><th>Proveedor</th><td>{{ $visita->proveedor->proveedor_nombre }}</td></tr>
        <tr><th>Plantación</th><td>{{ $visita->plantacion->nombre ?? 'No registrada' }}</td></tr>
        <tr><th>Fecha de Visita</th><td>{{ $visita->fecha }}</td></tr>
        <tr><th>Estado de Visita</th><td>{{ ucfirst($visita->estado) }}</td></tr>
    </table>

    <div class="section-header">📍 Área(s) registrada(s)</div>
    @forelse ($visita->areas as $area)
        <div class="data-block">
            <h4>Área #{{ $loop->index + 1 }} - Material: {{ $area->material }}</h4>
            <table>
                <tr><th>Material</th><td>{{ $area->material }}</td></tr>
                <tr><th>Estado</th><td>{{ $area->estado }}</td></tr>
                <tr><th>Año siembra</th><td>{{ $area->anio_siembra }}</td></tr>
                <tr><th>Área (m²)</th><td>{{ $area->area }}</td></tr>
                <tr><th>Área total en finca (Ha)</th><td>{{ $area->area_total_finca_hectareas ?? 'N/A' }}</td></tr>
                <tr><th>Palmas total finca</th><td>{{ $area->numero_palmas_total_finca ?? 'N/A' }}</td></tr>
                <tr><th>Área desarrollo (Ha)</th><td>{{ $area->area_palmas_desarrollo_hectareas ?? 'N/A' }}</td></tr>
                <tr><th>Palmas desarrollo</th><td>{{ $area->numero_palmas_desarrollo ?? 'N/A' }}</td></tr>
                <tr><th>Área producción (Ha)</th><td>{{ $area->area_palmas_produccion_hectareas ?? 'N/A' }}</td></tr>
                <tr><th>Palmas producción</th><td>{{ $area->numero_palmas_produccion ?? 'N/A' }}</td></tr>
                <tr><th>Ciclos de Cosecha</th><td>{{ $area->ciclos_cosecha ?? 'N/A' }}</td></tr>
                <tr><th>Producción (Ton/Mes)</th><td>{{ $area->produccion_toneladas_por_mes ?? 'N/A' }}</td></tr>
                <tr><th>Aplica Orden Plantis</th><td>{{ $area->aplica_orden_plantis ? 'Sí' : 'No' }}</td></tr>
                @if ($area->aplica_orden_plantis)
                    <tr><th>Orden Plantis N°</th><td>{{ $area->orden_plantis_numero ?? 'N/A' }}</td></tr>
                    <tr><th>N° Plantas (Orden Plantis)</th><td>{{ $area->numero_plantas_orden_plantis ?? 'N/A' }}</td></tr>
                    <tr><th>Estado Orden Plantis</th><td>{{ $area->estado_oren_plantis ?? 'N/A' }}</td></tr>
                @endif
            </table>
        </div>
    @empty
        <p>No se registró información de área.</p>
    @endforelse

    <div class="section-header">💧 Fertilizaciones</div>
    @forelse ($visita->fertilizaciones as $fert)
        <div class="data-block">
            <h4>Fertilización - Fecha: {{ $fert->fecha_fertilizacion }}</h4>
            <table>
                <thead>
                    <tr><th>Fertilizante</th><th>Cantidad (kg)</th></tr>
                </thead>
                <tbody>
                    @foreach ($fert->detalles as $f)
                        <tr><td>{{ ucfirst($f->fertilizante) }}</td><td>{{ $f->cantidad }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p>No hay fertilizaciones registradas.</p>
    @endforelse

    <div class="section-header">🌸 Polinizaciones</div>
    @forelse ($visita->polinizaciones as $poli)
        <div class="data-block">
            <h4>Polinización - Fecha: {{ $poli->fecha }}</h4>
            <table>
                <tr><th>N° Pases</th><td>{{ $poli->n_pases }}</td></tr>
                <tr><th>Ciclos Ronda</th><td>{{ $poli->ciclos_ronda }}</td></tr>
                <tr><th>ANA</th><td>{{ $poli->ana }} ({{ $poli->tipo_ana }})</td></tr>
                <tr><th>Talco</th><td>{{ $poli->talco }}</td></tr>
            </table>
        </div>
    @empty
        <p>No hay polinizaciones registradas.</p>
    @endforelse

    <div class="section-header">🧪 Sanidad</div>
    @if ($visita->sanidad)
        <div class="data-block">
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

    <div class="section-header">🧬 Análisis de Suelo</div>
    @if ($visita->suelo)
        <div class="data-block">
            <table>
                <tr><th>Análisis Foliar</th><td>{{ ucfirst($visita->suelo->analisis_foliar) }}</td></tr>
                <tr><th>Análisis Suelo</th><td>{{ ucfirst($visita->suelo->alanalisis_suelo) }}</td></tr>
                <tr><th>Tipo de Suelo</th><td>{{ ucfirst($visita->suelo->tipo_suelo) }}</td></tr>
            </table>
        </div>
    @else
        <p>No se registró análisis de suelo.</p>
    @endif

    <div class="section-header">🌾 Labores de Cultivo</div>
    @forelse ($visita->laboresCultivo as $labor)
        <div class="data-block">
            <h4>Labor para: {{ ucfirst($labor->tipo_planta ?? 'N/A') }}</h4>
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

    <div class="section-header">🍌 Evaluación de Cosecha en Campo</div>
    @forelse ($visita->evaluacionCosechaCampo as $evaluacion)
        <div class="data-block">
            <h4>Evaluación #{{ $loop->index + 1 }} - Variedad: {{ ucfirst($evaluacion->variedad_fruto) }}</h4>
            <table>
                <tr><th>Variedad Fruto</th><td>{{ ucfirst($evaluacion->variedad_fruto) }}</td></tr>
                <tr><th>Cantidad Racimos</th><td>{{ $evaluacion->cantidad_racimos }}</td></tr>
                <tr><th>Verde</th><td>{{ $evaluacion->verde }}%</td></tr>
                <tr><th>Maduro</th><td>{{ $evaluacion->maduro }}%</td></tr>
                <tr><th>Sobre Maduro</th><td>{{ $evaluacion->sobremaduro }}%</td></tr>
                <tr><th>Pedúnculo</th><td>{{ $evaluacion->pedunculo }}%</td></tr>
                @if ($evaluacion->variedad_fruto === 'hibrido')
                    <tr><th>Conformación</th><td>{{ $evaluacion->conformacion ?? 'No especificada' }}</td></tr>
                @endif
                <tr><th>Observaciones</th><td>{{ $evaluacion->observaciones ?? 'No registradas' }}</td></tr>
            </table>
        </div>
    @empty
        <p>No se ha registrado evaluación.</p>
    @endforelse

    <div class="section-header">🔏 Cierre de Visita</div>
    @if ($visita->cierreVisita)
        <div class="data-block">
            <table>
                <tr><th>Fecha de Cierre</th><td>{{ $visita->cierreVisita->fecha_cierre ? $visita->cierreVisita->fecha_cierre->format('d/m/Y') : 'N/A' }}</td></tr>
                <tr><th>Estado de la Visita</th><td>{{ $visita->cierreVisita->estado_visita ?? 'N/A' }}</td></tr>
                <tr><th>Observaciones Finales</th><td>{{ $visita->cierreVisita->observaciones_finales ?? 'N/A' }}</td></tr>
                <tr><th>Recomendaciones</th><td>{{ $visita->cierreVisita->recomendaciones ?? 'N/A' }}</td></tr>
                <tr><th>Finalizada En</th><td>{{ $visita->cierreVisita->finalizada_en ? $visita->cierreVisita->finalizada_en->format('d/m/Y H:i') : 'N/A' }}</td></tr>
                <tr><th>Responsable cierre</th><td>{{ $visita->tecnico->name ?? 'N/A' }}</td></tr>
            </table>

            <h3>Firmas</h3>
            @if ($visita->cierreVisita->firma_responsable)
                <p><strong>Firma Responsable:</strong></p>
                <img src="{{ $visita->cierreVisita->firma_responsable }}" style="max-height: 100px; max-width: 200px; border: 1px solid #ccc;">
            @endif
            @if ($visita->cierreVisita->firma_recibe)
                <p><strong>Firma de quien recibe:</strong></p>
                <img src="{{ $visita->cierreVisita->firma_recibe }}" style="max-height: 100px; max-width: 200px; border: 1px solid #ccc;">
            @endif
            @if ($visita->cierreVisita->firma_testigo)
                <p><strong>Firma del testigo:</strong></p>
                <img src="{{ $visita->cierreVisita->firma_testigo }}" style="max-height: 100px; max-width: 200px; border: 1px solid #ccc;">
            @endif

            <h3>Imágenes Finales</h3>
            @if (is_array($visita->cierreVisita->imagenes) && count($visita->cierreVisita->imagenes))
                @foreach ($visita->cierreVisita->imagenes as $img)
                    <img src="{{ $img }}" style="max-height: 120px; max-width: 120px; margin: 5px; border: 1px solid #eee;">
                @endforeach
            @endif
        </div>
    @else
        <p>No se ha registrado el cierre.</p>
    @endif
</body>
</html>

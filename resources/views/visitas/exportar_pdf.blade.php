<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Visita</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1, h2 { color: #2F4F4F; margin-bottom: 5px; }
        .section { margin-bottom: 25px; }
        .section h2 { border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; margin-bottom: 10px; }
        table th, table td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        ul { margin: 5px 0 10px 20px; padding: 0; }
        .img-thumb { height: 100px; margin: 5px; display: inline-block; }
        .title-bar { background: #e0f7fa; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
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
        <h2>📍 Área registrada</h2>
        @if ($visita->area)
            <table>
                <tr><th>Material</th><td>{{ $visita->area->material }}</td></tr>
                <tr><th>Estado</th><td>{{ $visita->area->estado }}</td></tr>
                <tr><th>Año siembra</th><td>{{ $visita->area->anio_siembra }}</td></tr>
                <tr><th>Área (m²)</th><td>{{ $visita->area->area }}</td></tr>
                <tr><th>Orden Plantis</th><td>{{ $visita->area->orden_plantis_numero }}</td></tr>
                <tr><th>Estado Plantis</th><td>{{ $visita->area->estado_orden_plantis }}</td></tr>
            </table>
        @else
            <p>No se registró información de área.</p>
        @endif
    </div>

    {{-- Fertilizaciones --}}
    <div class="section">
        <h2>💧 Fertilizaciones</h2>
        @forelse ($visita->fertilizaciones as $fert)
            <p><strong>Fecha:</strong> {{ $fert->fecha_fertilizacion }}</p>
            <ul>
                @foreach ($fert->fertilizantes as $f)
                    <li>{{ ucfirst($f->nombre) }} - {{ $f->cantidad }} kg</li>
                @endforeach
            </ul>
        @empty
            <p>No hay fertilizaciones registradas.</p>
        @endforelse
    </div>

    {{-- Polinizaciones --}}
    <div class="section">
        <h2>🌸 Polinizaciones</h2>
        @forelse ($visita->polinizaciones as $poli)
            <p>
                📅 {{ $poli->fecha }} |
                Pases: {{ $poli->n_pases }} |
                Ronda: {{ $poli->ciclos_ronda }} |
                ANA: {{ $poli->ana }} ({{ $poli->tipo_ana }}) |
                Talco: {{ $poli->talco }}
            </p>
        @empty
            <p>No hay polinizaciones registradas.</p>
        @endforelse
    </div>

    {{-- Sanidad --}}
    <div class="section">
        <h2>🧪 Sanidad</h2>
        @if ($visita->sanidad)
            <table>
                <tr><th>Opsophanes</th><td>{{ $visita->sanidad->opsophanes }}%</td></tr>
                <tr><th>P. Cogollo</th><td>{{ $visita->sanidad->pudricion_cogollo }}%</td></tr>
                <tr><th>Raspador</th><td>{{ $visita->sanidad->raspador }}%</td></tr>
                <tr><th>Palmarum</th><td>{{ $visita->sanidad->palmarum }}%</td></tr>
                <tr><th>Strategus</th><td>{{ $visita->sanidad->strategus }}%</td></tr>
                <tr><th>Leptoparsha</th><td>{{ $visita->sanidad->leptoparsha }}%</td></tr>
                <tr><th>Pestalotiopsis</th><td>{{ $visita->sanidad->pestalotiopsis }}%</td></tr>
                <tr><th>P. Basal</th><td>{{ $visita->sanidad->pudricion_basal }}%</td></tr>
                <tr><th>P. Estipe</th><td>{{ $visita->sanidad->pudricion_estipe }}%</td></tr>
                <tr><th>Otros</th><td>{{ $visita->sanidad->otros }}</td></tr>
                <tr><th>Observaciones</th><td>{{ $visita->sanidad->observaciones }}</td></tr>
            </table>
        @else
            <p>No hay datos de sanidad.</p>
        @endif
    </div>

    {{-- Suelo --}}
    <div class="section">
        <h2>🧬 Análisis de Suelo</h2>
        @if ($visita->suelo)
            <table>
                <tr><th>Análisis Foliar</th><td>{{ ucfirst($visita->suelo->analisis_foliar) }}</td></tr>
                <tr><th>Análisis Suelo</th><td>{{ ucfirst($visita->suelo->alanisis_suelo) }}</td></tr>
                <tr><th>Tipo de Suelo</th><td>{{ ucfirst($visita->suelo->tipo_suelo) }}</td></tr>
            </table>
        @else
            <p>No se registró análisis de suelo.</p>
        @endif
    </div>

    {{-- Labores de Cultivo --}}
    <div class="section">
        <h2>🌾 Labores de Cultivo</h2>
        @if ($visita->laboresCultivo)
            <table>
                @foreach ($visita->laboresCultivo->toArray() as $key => $value)
                    @if ($key !== 'id' && $key !== 'visita_id' && $key !== 'created_at' && $key !== 'updated_at')
                        <tr><th>{{ ucwords(str_replace('_', ' ', $key)) }}</th><td>{{ $value }}%</td></tr>
                    @endif
                @endforeach
            </table>
        @else
            <p>No se registraron labores.</p>
        @endif
    </div>

    {{-- Evaluación Cosecha --}}
    <div class="section">
        <h2>🍌 Evaluación de Cosecha en Campo</h2>
        @if ($visita->evaluacionCosechaCampo)
            <table>
                <tr><th>Variedad Fruto</th><td>{{ ucfirst($visita->evaluacionCosechaCampo->variedad_fruto) }}</td></tr>
                <tr><th>Cantidad Racimos</th><td>{{ $visita->evaluacionCosechaCampo->cantidad_racimos }}</td></tr>
                <tr><th>Verde</th><td>{{ $visita->evaluacionCosechaCampo->verde }}%</td></tr>
                <tr><th>Maduro</th><td>{{ $visita->evaluacionCosechaCampo->maduro }}%</td></tr>
                <tr><th>Sobre Maduro</th><td>{{ $visita->evaluacionCosechaCampo->sobremaduro }}%</td></tr>
                <tr><th>Pedúnculo</th><td>{{ $visita->evaluacionCosechaCampo->pedunculo }}%</td></tr>
                <tr><th>Observaciones</th><td>{{ $visita->evaluacionCosechaCampo->observaciones }}</td></tr>
            </table>
        @else
            <p>No se ha registrado evaluación.</p>
        @endif
    </div>

    {{-- Cierre de Visita --}}
    <div class="section">
        <h2>🔏 Cierre de Visita</h2>
        @if ($visita->cierreVisita)
            <table>
                <tr><th>Responsable</th><td>{{ $visita->cierreVisita->nombre_responsable }}</td></tr>
                <tr><th>Recomendaciones</th><td>{{ $visita->cierreVisita->recomendaciones ?? 'N/A' }}</td></tr>
                <tr><th>Observaciones</th><td>{{ $visita->cierreVisita->observaciones_finales ?? 'N/A' }}</td></tr>
                <tr><th>Fecha</th><td>{{ $visita->cierreVisita->created_at->format('d/m/Y') }}</td></tr>
            </table>

            {{-- Firma --}}
            @if ($visita->cierreVisita->firma_responsable)
                <p><strong>📄 Firma Responsable:</strong></p>
                <img src="{{ public_path('storage/' . $visita->cierreVisita->firma_responsable) }}" style="height:100px;">
            @endif

            {{-- Imágenes finales --}}
            @if ($visita->cierreVisita->imagenes)
                @php $imagenes = is_string($visita->cierreVisita->imagenes) ? json_decode($visita->cierreVisita->imagenes, true) : $visita->cierreVisita->imagenes; @endphp
                @if (is_array($imagenes) && count($imagenes))
                    <div>
                        <p><strong>🖼️ Imágenes Finales:</strong></p>
                        @foreach ($imagenes as $img)
                            <img src="{{ public_path('storage/' . $img) }}" class="img-thumb">
                        @endforeach
                    </div>
                @endif
            @endif
        @else
            <p>No se ha registrado el cierre.</p>
        @endif
    </div>
</body>
</html>

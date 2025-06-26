<table>
    {{-- ENCABEZADO --}}
    <tr><th colspan="2" style="background-color: #d9edf7; font-size: 16px;">📋 Detalle Completo de Visita</th></tr>
    <tr><td><strong>Proveedor</strong></td><td>{{ $visita->proveedor->proveedor_nombre }}</td></tr>
    <tr><td><strong>Plantación</strong></td><td>{{ $visita->plantacion->nombre ?? 'No registrada' }}</td></tr>
    <tr><td><strong>Fecha Visita</strong></td><td>{{ $visita->fecha }}</td></tr>
    <tr><td colspan="2"></td></tr>

    {{-- ÁREA --}}
    @if ($visita->area)
        <tr><th colspan="2" style="background-color: #dff0d8;">📍 Área</th></tr>
        <tr><td>Material</td><td>{{ $visita->area->material }}</td></tr>
        <tr><td>Estado</td><td>{{ $visita->area->estado }}</td></tr>
        <tr><td>Año Siembra</td><td>{{ $visita->area->anio_siembra }}</td></tr>
        <tr><td>Área (m²)</td><td>{{ $visita->area->area }}</td></tr>
        <tr><td>Orden Plantis</td><td>{{ $visita->area->orden_plantis_numero }}</td></tr>
        <tr><td>Estado Orden Plantis</td><td>{{ $visita->area->estado_orden_plantis }}</td></tr>
    @endif

    {{-- FERTILIZACIONES --}}
    @if ($visita->fertilizaciones->count())
        <tr><th colspan="2" style="background-color: #dff0d8;">💧 Fertilizaciones</th></tr>
        @foreach ($visita->fertilizaciones as $fertilizacion)
            <tr><td colspan="2"><strong>Fecha:</strong> {{ $fertilizacion->fecha_fertilizacion }}</td></tr>
            @foreach ($fertilizacion->fertilizantes as $f)
                <tr><td>{{ ucfirst($f->fertilizante) }}</td><td>{{ $f->cantidad }} kg</td></tr>
            @endforeach
        @endforeach
    @endif

    {{-- POLINIZACIONES --}}
    @if ($visita->polinizaciones->count())
        <tr><th colspan="2" style="background-color: #dff0d8;">🌸 Polinizaciones</th></tr>
        @foreach ($visita->polinizaciones as $poli)
            <tr>
                <td>Fecha: {{ $poli->fecha }}</td>
                <td>
                    Pases: {{ $poli->n_pases }},
                    Ronda: {{ $poli->ciclos_ronda }},
                    ANA: {{ $poli->ana }} ({{ $poli->tipo_ana }}),
                    Talco: {{ $poli->talco }}
                </td>
            </tr>
        @endforeach
    @endif

    {{-- SANIDAD --}}
    @if ($visita->sanidad)
        <tr><th colspan="2" style="background-color: #dff0d8;">🧪 Sanidad</th></tr>
        <tr><td>Opsophanes</td><td>{{ $visita->sanidad->opsophanes }}%</td></tr>
        <tr><td>Pudrición Cogollo</td><td>{{ $visita->sanidad->pudricion_cogollo }}%</td></tr>
        <tr><td>Raspador</td><td>{{ $visita->sanidad->raspador }}%</td></tr>
        <tr><td>Palmarum</td><td>{{ $visita->sanidad->palmarum }}%</td></tr>
        <tr><td>Strategus</td><td>{{ $visita->sanidad->strategus }}%</td></tr>
        <tr><td>Leptoparsha</td><td>{{ $visita->sanidad->leptoparsha }}%</td></tr>
        <tr><td>Pestalotiopsis</td><td>{{ $visita->sanidad->pestalotiopsis }}%</td></tr>
        <tr><td>Pudrición Basal</td><td>{{ $visita->sanidad->pudricion_basal }}%</td></tr>
        <tr><td>Pudrición Estipe</td><td>{{ $visita->sanidad->pudricion_estipe }}%</td></tr>
        <tr><td>Otros</td><td>{{ $visita->sanidad->otros }}</td></tr>
        <tr><td>Observaciones</td><td>{{ $visita->sanidad->observaciones }}</td></tr>
    @endif

    {{-- SUELO --}}
    @if ($visita->suelo)
        <tr><th colspan="2" style="background-color: #dff0d8;">🧬 Análisis de Suelo</th></tr>
        <tr><td>Análisis foliar</td><td>{{ $visita->suelo->analisis_foliar }}</td></tr>
        <tr><td>Análisis suelo</td><td>{{ $visita->suelo->alanisis_suelo }}</td></tr>
        <tr><td>Tipo de suelo</td><td>{{ $visita->suelo->tipo_suelo }}</td></tr>
    @endif

    {{-- LABORES DE CULTIVO --}}
    @if ($visita->laboresCultivo)
        <tr><th colspan="2" style="background-color: #dff0d8;">🌾 Labores de Cultivo</th></tr>
        @php $labores = [
            'polinizacion', 'limpieza_calle', 'limpieza_plato', 'poda', 'fertilizacion', 'enmiendas',
            'ubicacion_tusa_fibra', 'ubicacion_hoja', 'lugar_ubicacion_hoja', 'plantas_nectariferas',
            'cobertura', 'labor_cosecha', 'calidad_fruta', 'recoleccion_fruta', 'drenajes'
        ]; @endphp
        @foreach ($labores as $campo)
            <tr><td>{{ ucwords(str_replace('_', ' ', $campo)) }}</td><td>{{ $visita->laboresCultivo->$campo }}%</td></tr>
        @endforeach
    @endif

    {{-- EVALUACIÓN DE COSECHA --}}
    @if ($visita->evaluacionCosechaCampo)
        <tr><th colspan="2" style="background-color: #dff0d8;">🍌 Evaluación de Cosecha</th></tr>
        <tr><td>Variedad de fruto</td><td>{{ ucfirst($visita->evaluacionCosechaCampo->variedad_fruto) }}</td></tr>
        <tr><td>Cantidad de racimos</td><td>{{ $visita->evaluacionCosechaCampo->cantidad_racimos }}</td></tr>
        <tr><td>Verde</td><td>{{ $visita->evaluacionCosechaCampo->verde }}%</td></tr>
        <tr><td>Maduro</td><td>{{ $visita->evaluacionCosechaCampo->maduro }}%</td></tr>
        <tr><td>Sobremaduro</td><td>{{ $visita->evaluacionCosechaCampo->sobremaduro }}%</td></tr>
        <tr><td>Pedúnculo</td><td>{{ $visita->evaluacionCosechaCampo->pedunculo }}%</td></tr>
        <tr><td>Observaciones</td><td>{{ $visita->evaluacionCosechaCampo->observaciones }}</td></tr>
    @endif

    {{-- CIERRE DE VISITA --}}
    @if ($visita->cierreVisita)
        <tr><th colspan="2" style="background-color: #dff0d8;">🔏 Cierre de Visita</th></tr>
        <tr><td>Responsable</td><td>{{ $visita->cierreVisita->nombre_responsable }}</td></tr>
        <tr><td>Recomendaciones</td><td>{{ $visita->cierreVisita->recomendaciones }}</td></tr>
        <tr><td>Observaciones</td><td>{{ $visita->cierreVisita->observaciones_finales }}</td></tr>
        <tr><td>Fecha</td><td>{{ $visita->cierreVisita->created_at->format('d/m/Y') }}</td></tr>
       
        
    @endif
</table>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Visita Técnica</title>
    <style>
        /* ===== ESTILOS BASE ===== */
        @page {
            margin: 0;
            padding: 0;
        }
        
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 13px; /* AUMENTADO */
            color: #222;
            margin: 0 !important; /* QUITAR margin: 50px */
            padding: 0;
            line-height: 1.5;
        }
        
        /* ===== HEADER FIJADO ===== */
        .pdf-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 45mm;
            z-index: 1; 
            background-color: white;
        }
        
        .header-image {
           
            width: 100%;
            height: 45mm;
            object-fit: cover;
            
        }
        
        /* ===== FOOTER FIJADO ===== */
        .pdf-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 25mm;
            z-index: 1000; /* CAMBIADO de -1000 a 1000 */
            background-color: white;
        }
        
        .footer-image {
            width: 100%;
            height: 25mm;
            object-fit: cover;
        }
        
        /* ===== MÁRGENES DE CONTENIDO CORREGIDAS ===== */
        .content {
             top: 150px;
            margin: 50mm 20mm 30mm 20mm; /* MÁS MARGEN LATERAL */
            position: relative;
            padding-top: 10mm;
            margin-top: 250px !important; /* ESPACIO EXTRA DEBAJO DEL HEADER */
        }
        
        /* ===== TÍTULOS Y ENCABEZADOS ===== */
        h1 {
            font-size: 18px; /* AUMENTADO */
            font-weight: bold;
            text-align: center;
            color: #2F4F4F;
            margin: 0 0 15px 0;
            font-family: helvetica, sans-serif;
        }
        
        h2 {
            font-size: 16px; /* AUMENTADO */
            font-weight: bold;
            color: #2F4F4F;
            margin: 25px 0 15px 0;
            font-family: helvetica, sans-serif;
            border-bottom: 2px solid #ccc;
            padding-bottom: 6px;
        }
        
        h3 {
            font-size: 15px; /* AUMENTADO */
            font-weight: bold;
            color: #2F4F4F;
            margin: 20px 0 12px 0;
            font-family: helvetica, sans-serif;
        }
        
        h4 {
            font-size: 14px; /* AUMENTADO */
            font-weight: bold;
            color: #2F4F4F;
            margin: 15px 0 10px 0;
            font-family: helvetica, sans-serif;
        }
        
        h5 {
            font-size: 13px; /* AUMENTADO */
            font-weight: bold;
            color: #2F4F4F;
            margin: 12px 0 8px 0;
            font-family: helvetica, sans-serif;
        }
        
        /* ===== TABLAS ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px; /* AUMENTADO */
            margin: 10px 0 15px 0;
            page-break-inside: avoid;
        }
        
        table.grid-table {
            border: 1px solid #ccc;
        }
        
        table.grid-table th {
            background-color: #006400;
            color: white;
            font-weight: bold;
            text-align: left;
            padding: 8px 10px; /* MÁS PADDING */
            border: 1px solid #ccc;
            font-size: 12px;
        }
        
        table.grid-table td {
            padding: 6px 10px; /* MÁS PADDING */
            border: 1px solid #ccc;
            vertical-align: top;
        }
        
        table.data-table {
            border: none;
            font-size: 12px;
        }
        
        table.data-table tr td:first-child {
            font-weight: bold;
            background-color: #f8f9fa;
            width: 40%;
            padding: 6px 10px;
            border-right: 2px solid #e9ecef;
        }
        
        table.data-table tr td {
            padding: 6px 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        /* ===== DATA CARDS ===== */
        .data-card {
            background-color: #f8fff8;
            border: 1px solid #d4edda;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 15px;
            page-break-inside: avoid;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .data-card h5 {
            background-color: #e8f5e9;
            padding: 8px 12px;
            margin: -12px -12px 12px -12px;
            border-radius: 6px 6px 0 0;
            color: #2F4F4F;
            font-size: 13px;
        }
        
        /* ===== TITLE BAR ===== */
        .title-bar {
            background-color: #e0f7fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #b2ebf2;
        }
        
        .title-bar h1 {
            margin-top: 0;
        }
        
        /* ===== EMPRESA HEADER ===== */
        .company-header {
            text-align: center;
            margin: 25px 0;
            padding: 15px 0;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #fafafa;
        }
        
        .company-header h3 {
            font-size: 16px;
            margin: 8px 0;
            color: #000;
        }
        
        .company-header h4 {
            font-size: 14px;
            margin: 8px 0;
            color: #000;
        }
        
        .company-header p {
            font-size: 12px;
            margin: 8px 0;
            color: #666;
        }
        
        /* ===== TEXTOS ===== */
        .intro-text {
            font-size: 13px;
            line-height: 1.6;
            text-align: justify;
            margin-bottom: 20px;
        }
        
        .observations {
            font-size: 12px;
            font-style: italic;
            color: #666;
            margin: 8px 0;
            padding-left: 15px;
            border-left: 3px solid #6c757d;
        }
        
        /* ===== IMÁGENES ===== */
        .company-logo {
            width: 200px;
            height: auto;
            max-height: 70px;
            display: block;
            margin: 0 auto 15px auto;
        }
        
        .firma-img {
            max-height: 80px; /* MÁS GRANDE */
            max-width: 200px; /* MÁS GRANDE */
            margin: 10px auto;
            display: block;
            border: 1px solid #ddd;
            background-color: white;
        }
        
        /* ===== SECCIONES ===== */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section-break {
            page-break-before: always;
            margin-top: 25mm;
        }
        
        /* ===== FIRMAS ===== */
        .firmas-container {
            margin-top: 30px;
            text-align: center;
            page-break-inside: avoid;
            padding: 20px;
            border-top: 2px solid #dee2e6;
        }
        
        .firma-item {
            display: inline-block;
            margin: 0 40px;
            text-align: center;
            vertical-align: top;
            min-width: 150px;
        }
        
        .firma-line {
            width: 150px;
            border-top: 2px solid #000;
            margin: 8px auto;
        }
        
        .firma-label {
            font-size: 12px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #495057;
        }
        
        /* ===== IMÁGENES DE VISITA - NUEVO DISEÑO ===== */
        .galeria-imagenes {
            text-align: center;
            margin: 20px auto;
            width: 100%;
        }
        
        .fila-imagenes {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .imagen-container {
            flex: 0 0 auto;
            text-align: center;
            margin: 0;
        }
        
        .imagen-visita {
            max-width: 120mm; /* MÁS GRANDE */
            max-height: 85mm; /* MÁS GRANDE */
            width: auto;
            height: auto;
            border: 2px solid #e0e0e0;
            border-radius: 4px;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        
        .imagen-numero {
            font-size: 11px;
            margin-top: 8px;
            color: #666;
            font-weight: bold;
        }
        
        /* ===== PAGINACIÓN ===== */
        .page-number {
            position: fixed;
            bottom: 35mm; /* AJUSTADO PARA NO CHOCAR CON FOOTER */
            right: 25mm;
            font-size: 11px;
            color: #666;
            z-index: 2000;
            background-color: white;
            padding: 2px 8px;
            border-radius: 3px;
            border: 1px solid #ddd;
        }
        
        /* ===== UTILIDADES ===== */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .text-italic { font-style: italic; }
        .mb-5 { margin-bottom: 5mm; }
        .mb-10 { margin-bottom: 10mm; }
        .mt-5 { margin-top: 5mm; }
        .mt-10 { margin-top: 10mm; }
        .p-5 { padding: 5mm; }
        
        /* ===== SEPARADORES ===== */
        hr.separador {
            border: none;
            border-top: 2px dashed #dee2e6;
            margin: 20px 0;
        }
        
        /* ===== LISTAS ===== */
        ul {
            margin: 8px 0 15px 25px;
            padding: 0;
            font-size: 12px;
        }
        
        li {
            margin-bottom: 5px;
        }
        
        /* ===== CLASES PARA EVITAR CORTES ===== */
        .no-break {
            page-break-inside: avoid;
        }
        
        .break-before {
            page-break-before: always;
        }
        
        .break-after {
            page-break-after: always;
        }
        
        /* ===== CLASES PARA MÁRGENES ESPECÍFICOS ===== */
        .mt-20 { margin-top: 20mm; }
        .mb-20 { margin-bottom: 20mm; }
        .pt-10 { padding-top: 10mm; }
        .pb-10 { padding-bottom: 10mm; }
    </style>
</head>
<body>
    {{-- ===== HEADER IMAGE ===== --}}
    @if(file_exists(public_path('images/header.png')))
    <div class="pdf-header">
        <img src="{{ public_path('images/header.png') }}" class="header-image" alt="Header">
    </div>
    @else
    <div class="title-bar">
        <h1>INFORME DE VISITA TÉCNICA</h1>
        <p style="font-size: 14px; margin: 5px 0 0 0;">Palmas Oleaginosas Bucarelia</p>
    </div>
    @endif
    
    {{-- ===== FOOTER IMAGE ===== --}}
    @if(file_exists(public_path('images/footer.png')))
    <div class="pdf-footer">
        <img src="{{ public_path('images/footer.png') }}" class="footer-image" alt="Footer">
    </div>
    @endif
    
    {{-- ===== CONTENIDO PRINCIPAL ===== --}}
    <div class="content">
        
        {{-- ===== PÁGINA 1: TÍTULO E INFORMACIÓN GENERAL ===== --}}
        
        {{-- Logo de empresa --}}
        @if(file_exists(public_path('images/logo2.webp')))
        <div class="text-center mb-10">
            <img src="{{ public_path('images/logo2.webp') }}"  style="margin-top: -250px !important;" class="company-logo" alt="Logo">
        <br> <h1 style="margin-top: -150px !important;">INFORME DE VISITA TÉCNICA</h1></div>
        @endif
        
        {{-- Título principal --}}
       
        
        {{-- Tabla de información general --}}
        <table class="grid-table" style="margin-top: -150px !important;">
            <thead>
                <tr>
                    <th>PROVEEDOR</th>
                    <th>PLANTACIÓN</th>
                    <th>UBICACIÓN</th>
                    <th>TÉCNICO</th>
                    <th>FECHA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $visita->proveedor->proveedor_nombre ?? 'No especificado' }}</td>
                    <td>{{ $visita->plantacion->nombre ?? 'No especificado' }}</td>
                    <td>{{ $visita->ubicacion ?? 'No especificada' }}</td>
                    <td>{{ $visita->tecnico_campo->name ?? 'No asignado' }}</td>
                    <td>{{ $visita->fecha ? \Carbon\Carbon::parse($visita->fecha)->format('d/m/Y') : 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
        
        {{-- Sección 1: INTRODUCCIÓN --}}
        <div class="section">
            <h2>1. INTRODUCCIÓN</h2>
            <div class="intro-text">
                Palmas Oleaginosas Bucarelia, en pro de seguir apoyando a sus proveedores de fruto en el fortalecimiento de ser productivos y sostenibles, ha decidido continuar en el año 2025, el convenio con el centro de investigación de Cenipalma, con el fin de generar un impacto positivo en los proveedores de racimo de fruto fresca (RFF) de la compañía. Este convenio tiene como finalidad aumentar la productividad de nuestros proveedores de RFF, basados en una agricultura sostenible y amigable con el medio ambiente. Con este objetivo se continúa con las visitas de acompañamiento técnico, agronómico, social y ambiental, brindando apoyo en las labores relacionadas con estos componentes; así mismo impulsar a los proveedores a la adopción de nuevas tecnologías en sus plantaciones, con el único de fin de alcanzar las metas propuestas y alcanzar la sostenibilidad de sus cultivos.
            </div>
        </div>
        
        {{-- Sección 2: INFORMACIÓN GENERAL DE LA FINCA --}}
        <div class="section">
            <h2>2. INFORMACIÓN GENERAL DE LA FINCA</h2>
            
            @php
                $totalPalmas = 0;
                $palmasDesarrollo = 0;
                $palmasProduccion = 0;
                $palmasOrdenPlantis = 0;
                $produccionTotal = 0;
                $areaTotal = 0;
                
                foreach($visita->areas as $area) {
                    $totalPalmas += intval($area->numero_palmas_total_finca) ?? 0;
                    $palmasDesarrollo += intval($area->numero_palmas_desarrollo) ?? 0;
                    $palmasProduccion += intval($area->numero_palmas_produccion) ?? 0;
                    
                    if($area->aplica_orden_plantis) {
                        $palmasOrdenPlantis += intval($area->numero_plantas_orden_plantis) ?? 0;
                    }
                    
                    $produccionTotal += floatval($area->produccion_toneladas_por_mes) ?? 0;
                    $areaTotal += floatval($area->area_total_finca_hectareas) ?? 0;
                }
                
                $ciclosCosecha = $visita->areas->first()->ciclos_cosecha ?? 'N/A';
                $areaTotalDisplay = $areaTotal > 0 ? number_format($areaTotal, 2) . ' Ha' : 'N/A';
                $produccionTotalDisplay = $produccionTotal > 0 ? number_format($produccionTotal, 2) . ' Ton/Mes' : 'N/A';
            @endphp
            
            <table class="data-table">
                <tr>
                    <td>Área Total Finca</td>
                    <td>{{ $areaTotalDisplay }}</td>
                </tr>
                <tr>
                    <td>Total de Palmas</td>
                    <td>{{ $totalPalmas }} (incluye Orden Plantis)</td>
                </tr>
                <tr>
                    <td>Ciclos de Cosecha</td>
                    <td>{{ $ciclosCosecha }}</td>
                </tr>
                <tr>
                    <td>Producción Total</td>
                    <td>{{ $produccionTotalDisplay }}</td>
                </tr>
                <tr>
                    <td>Palmas en Desarrollo</td>
                    <td>{{ $palmasDesarrollo }}</td>
                </tr>
                <tr>
                    <td>Palmas en Producción</td>
                    <td>{{ $palmasProduccion }}</td>
                </tr>
                <tr>
                    <td>Palmas Orden Plantis</td>
                    <td>{{ $palmasOrdenPlantis }}</td>
                </tr>
            </table>
        </div>
        
        {{-- ===== PÁGINA 2: ÁREAS INDIVIDUALES ===== --}}
        <div class="break-before mt-20"></div>
        
        {{-- Encabezado de empresa --}}
        <div class="company-header" style="margin-top: -150px !important;">
            <h3>Palmas Oleaginosas</h3>
            <h4>BUCARELIA S.A.S</h4>
            <p>Nit. 860.009.787-9</p>
        </div>
        
        {{-- Sección 3: ÁREAS INDIVIDUALES --}}
        <div class="section">
            <h2>3. ÁREAS INDIVIDUALES</h2>
            
            @forelse ($visita->areas as $index => $area)
                <div class="data-card no-break">
                    <h5>Área #{{ $index + 1 }} - {{ $area->variedad ?? 'híbrido' }}</h5>
                    
                    <table class="data-table">
                        <tr>
                            <td>Variedad</td>
                            <td>{{ $area->variedad ?? 'híbrido' }}</td>
                        </tr>
                        <tr>
                            <td>Material</td>
                            <td>{{ $area->material ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Estado</td>
                            <td>{{ $area->estado == 'produccion' ? 'Producción' : 'Desarrollo' }}</td>
                        </tr>
                        <tr>
                            <td>Año Siembra</td>
                            <td>{{ $area->anio_siembra ? strval($area->anio_siembra) : '—' }}</td>
                        </tr>
                        <tr>
                            <td>Área (m²)</td>
                            <td>{{ $area->area ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Área Total Finca (Ha)</td>
                            <td>{{ $area->area_total_finca_hectareas ? number_format($area->area_total_finca_hectareas, 2) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>N° Palmas Total Finca</td>
                            <td>{{ $area->numero_palmas_total_finca ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Área Desarrollo (Ha)</td>
                            <td>{{ $area->area_palmas_desarrollo_hectareas ? number_format($area->area_palmas_desarrollo_hectareas, 2) : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>N° Palmas Desarrollo</td>
                            <td>{{ $area->numero_palmas_desarrollo ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Aplica Orden Plantis</td>
                            <td>
                                @if($area->aplica_orden_plantis === true || $area->aplica_orden_plantis === 'true' || $area->aplica_orden_plantis === 1 || $area->aplica_orden_plantis === 'si' || $area->aplica_orden_plantis === 'Sí')
                                    Sí
                                @elseif($area->aplica_orden_plantis === false || $area->aplica_orden_plantis === 'false' || $area->aplica_orden_plantis === 0 || $area->aplica_orden_plantis === 'no' || $area->aplica_orden_plantis === 'No')
                                    No
                                @else
                                    {{ $area->aplica_orden_plantis ?? 'N/A' }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Orden Plantis N°</td>
                            <td>{{ $area->orden_plantis_numero ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Estado Orden Plantis</td>
                            <td>{{ $area->estado_oren_plantis ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>N° Plantas Orden Plantis</td>
                            <td>{{ $area->numero_plantas_orden_plantis ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
                
                @if(!$loop->last)
                <hr class="separador">
                @endif
                
            @empty
                <p>No se registró información de área.</p>
            @endforelse
        </div>
        
        {{-- ===== PÁGINA 3: NUTRICIÓN ===== --}}
        <div class="break-before mt-20"></div>
        
        {{-- Sección 4: NUTRICIÓN --}}
        <div class="section" style="margin-top: -150px !important;">
            <h2>4. NUTRICIÓN EN EL CULTIVO DE PALMA DE ACEITE</h2>
            
            <div class="intro-text">
                En el cultivo de palma de aceite, uno de los componentes más importantes es la nutrición, ya que de ello depende directamente la sostenibilidad productiva del cultivo a corto, mediano y largo plazo. Para alcanzar las metas y rendimientos esperados en producción, es necesario realizar y dar cumplimiento al plan de nutrición. De esta manera también fortalecemos la tolerancia del cultivo a diversos ataques relacionados con plagas y enfermedades y o factores climáticos.
            </div>
        </div>
        
        {{-- Sección 4.1: FERTILIZACIONES --}}
        @if($visita->fertilizaciones && $visita->fertilizaciones->count() > 0)
        <div class="section">
            <h3>4.1. FERTILIZACIONES APLICADAS</h3>
            
            @foreach($visita->fertilizaciones as $index => $fert)
                <div class="data-card no-break">
                    <h5>Aplicación #{{ $index + 1 }} - {{ $fert->fecha_fertilizacion ? \Carbon\Carbon::parse($fert->fecha_fertilizacion)->format('d/m/Y') : 'N/A' }}</h5>
                    
                    @if($fert->detalles && $fert->detalles->count() > 0)
                    <table class="grid-table">
                        <thead>
                            <tr>
                                <th>Fertilizante</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Fecha Aplicación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fert->detalles as $f)
                            <tr>
                                <td>{{ $f->fertilizante ?? $f->nombre ?? 'N/A' }}</td>
                                <td>{{ $f->cantidad ?? 'N/A' }}</td>
                                <td>{{ $f->unidad_medida ?? 'N/A' }}</td>
                                <td>{{ $f->fecha_aplicacion ? \Carbon\Carbon::parse($f->fecha_aplicacion)->format('d/m/Y') : 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
        
        {{-- Sección 4.2: POLINIZACIONES --}}
        @if($visita->polinizaciones && $visita->polinizaciones->count() > 0)
        <div class="section">
            <h3>4.2. POLINIZACIONES</h3>
            
            @foreach($visita->polinizaciones as $index => $poli)
                <div class="data-card no-break">
                    <table class="data-table">
                        <tr>
                            <td>Fecha</td>
                            <td>{{ $poli->fecha ? \Carbon\Carbon::parse($poli->fecha)->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>N° Pases</td>
                            <td>{{ $poli->n_pases ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>Ciclos</td>
                            <td>{{ $poli->ciclos_ronda ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td>ANA</td>
                            <td>{{ $poli->ana ?? 'N/A' }} ({{ $poli->tipo_ana ?? 'N/A' }})</td>
                        </tr>
                        <tr>
                            <td>Talco</td>
                            <td>{{ $poli->talco ?? 'N/A' }} kg</td>
                        </tr>
                    </table>
                </div>
            @endforeach
        </div>
        @endif
        
        {{-- ===== PÁGINA 4: SANIDAD ===== --}}
        <div class="break-before mt-20"></div>
        
        {{-- Sección 5: ESTADO SANITARIO --}}
        @if($visita->sanidades && $visita->sanidades->count() > 0)
        <div class="section" style="margin-top: -150px !important;">
            <h2>5. ESTADO SANITARIO</h2>
            
            @foreach($visita->sanidades as $sanidad)
                <div class="data-card no-break">
                    @php
                        $sanidadData = [];
                        if(isset($sanidad->censo_enfermedades)) {
                            $sanidadData[] = ['Censo de enfermedades', $sanidad->censo_enfermedades ? 'Sí' : 'No'];
                        }
                        if(!empty($sanidad->ciclos_lectura_enfermedades)) {
                            $sanidadData[] = ['Ciclos lectura enfermedades', $sanidad->ciclos_lectura_enfermedades];
                        }
                        if(!empty($sanidad->ciclos_lectura_plagas)) {
                            $sanidadData[] = ['Ciclos lectura plagas', $sanidad->ciclos_lectura_plagas];
                        }
                        if(!empty($sanidad->otros)) {
                            $sanidadData[] = ['Otros', $sanidad->otros];
                        }
                    @endphp
                    
                    @if(count($sanidadData) > 0)
                    <table class="data-table">
                        @foreach($sanidadData as $data)
                        <tr>
                            <td>{{ $data[0] }}</td>
                            <td>{{ $data[1] }}</td>
                        </tr>
                        @endforeach
                    </table>
                    @endif
                    
                    {{-- Enfermedades Detectadas --}}
                    @if($sanidad->enfermedades && $sanidad->enfermedades->count() > 0)
                    <h4>Enfermedades Detectadas:</h4>
                    <table class="grid-table">
                        <thead>
                            <tr style="background-color: #960000; color: white;">
                                <th>Nombre</th>
                                <th>Estado (%)</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sanidad->enfermedades as $enf)
                            <tr>
                                <td>{{ $enf->nombre_enfermedad ?? '-' }}</td>
                                <td>{{ $enf->estado ?? '-' }}</td>
                                <td>{{ $enf->observaciones ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    
                    {{-- Plagas Detectadas --}}
                    @if($sanidad->plagas && $sanidad->plagas->count() > 0)
                    <h4>Plagas Detectadas:</h4>
                    <table class="grid-table">
                        <thead>
                            <tr style="background-color: #960000; color: white;">
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Instar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sanidad->plagas as $pla)
                            <tr>
                                <td>{{ $pla->nombre_plaga ?? '-' }}</td>
                                <td>{{ $pla->estado ?? '-' }}</td>
                                <td>{{ $pla->instar ?? 'No es Estado Larva ó No Registra Instar' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    
                    {{-- Trampas de Palmarum --}}
                    @if($sanidad->trampas && $sanidad->trampas->count() > 0)
                    <h4>Trampas de Palmarum:</h4>
                    <table class="grid-table">
                        <thead>
                            <tr style="background-color: #646464; color: white;">
                                <th>Ciclos</th>
                                <th>Machos Capturados</th>
                                <th>Hembras Capturadas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sanidad->trampas as $trampa)
                            <tr>
                                <td>{{ $trampa->ciclos ?? '-' }}</td>
                                <td>{{ $trampa->machos_capturados ?? '-' }}</td>
                                <td>{{ $trampa->hembras_capturadas ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    
                    {{-- Observaciones de Sanidad --}}
                    @if(!empty($sanidad->observaciones))
                    <div class="observations">
                        <strong>Observaciones de Sanidad:</strong><br>
                        {{ $sanidad->observaciones }}
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
        
        {{-- ===== PÁGINA 5: DESCRIPCIÓN Y LABORES ===== --}}
        <div class="break-before mt-20"></div>
        
        {{-- Sección 9: DESCRIPCIÓN DE LA VISITA --}}
        <div class="section" style="margin-top: -150px !important;">
            <h2>9. DESCRIPCIÓN DE LA VISITA</h2>
            
            @php
                $fechaVisita = $visita->fecha ? \Carbon\Carbon::parse($visita->fecha)->format('d/m/Y') : 'N/A';
                $nombreFinca = $visita->plantacion->nombre ?? 'No especificado';
            @endphp
            
            <div class="intro-text">
                La visita se realizó una visita de campo el {{ $fechaVisita }}, en compañía del administrador y representantes de la unidad de asistencia. La visita se realizó en la plantación {{ $nombreFinca }} con el objetivo de hacer un diagnóstico de las labores del cultivo relacionadas con la cosecha, labores de mantenimiento, y sanidad del cultivo.
            </div>
        </div>
        
        {{-- Sección 7: LABORES DE CULTIVO --}}
        @if($visita->laboresCultivo && $visita->laboresCultivo->count() > 0)
        <div class="section">
            <h2>7. LABORES DE CULTIVO</h2>
            
            @foreach($visita->laboresCultivo as $index => $labor)
                <div class="data-card no-break">
                    <table class="data-table">
                        @if(!empty($labor->tipo_planta))
                        <tr>
                            <td>Tipo Planta</td>
                            <td>{{ $labor->tipo_planta }}</td>
                        </tr>
                        @endif
                        
                        @if(isset($labor->polinizacion))
                        <tr>
                            <td>Polinización</td>
                            <td>{{ $labor->polinizacion }}%</td>
                        </tr>
                        @endif
                        
                        @if(isset($labor->limpieza_calle))
                        <tr>
                            <td>Limpieza Calle</td>
                            <td>{{ $labor->limpieza_calle }}%</td>
                        </tr>
                        @endif
                        
                        @if(isset($labor->limpieza_plato))
                        <tr>
                            <td>Limpieza Plato</td>
                            <td>{{ $labor->limpieza_plato }}%</td>
                        </tr>
                        @endif
                        
                        @if(isset($labor->poda))
                        <tr>
                            <td>Poda</td>
                            <td>{{ $labor->poda }}%</td>
                        </tr>
                        @endif
                        
                        @if(isset($labor->fertilizacion))
                        <tr>
                            <td>Fertilización</td>
                            <td>{{ $labor->fertilizacion }}%</td>
                        </tr>
                        @endif
                        
                        @if(isset($labor->enmiendas))
                        <tr>
                            <td>Enmiendas</td>
                            <td>{{ $labor->enmiendas }}%</td>
                        </tr>
                        @endif
                        
                        @if(isset($labor->cobertura))
                        <tr>
                            <td>Cobertura</td>
                            <td>{{ $labor->cobertura }}%</td>
                        </tr>
                        @endif
                        
                        @if(isset($labor->drenajes))
                        <tr>
                            <td>Drenajes</td>
                            <td>{{ $labor->drenajes }}%</td>
                        </tr>
                        @endif
                        
                        @if(isset($labor->plantas_nectariferas))
                        <tr>
                            <td>Plantas Nectaríferas</td>
                            <td>{{ $labor->plantas_nectariferas }}%</td>
                        </tr>
                        @endif
                        
                        @if(!empty($labor->labor_cosecha))
                        <tr>
                            <td>Labor Cosecha</td>
                            <td>{{ $labor->labor_cosecha }}</td>
                        </tr>
                        @endif
                        
                        @if(!empty($labor->calidad_fruta))
                        <tr>
                            <td>Calidad Fruta</td>
                            <td>{{ $labor->calidad_fruta }}</td>
                        </tr>
                        @endif
                    </table>
                    
                    @if(!empty($labor->observaciones))
                    <div class="observations">
                        <strong>Observaciones:</strong> {{ $labor->observaciones }}
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
        
        {{-- Sección 8: EVALUACIÓN DE COSECHA --}}
        @if($visita->evaluacionCosechaCampo && $visita->evaluacionCosechaCampo->count() > 0)
        <div class="section">
            <h2>8. EVALUACIÓN DE COSECHA</h2>
            
            @foreach($visita->evaluacionCosechaCampo as $index => $evaluacion)
                <div class="data-card no-break">
                    <table class="data-table">
                        @if(!empty($evaluacion->variedad_fruto))
                        <tr>
                            <td>Variedad Fruto</td>
                            <td>{{ $evaluacion->variedad_fruto }}</td>
                        </tr>
                        @endif
                        
                        @if(!empty($evaluacion->cantidad_racimos))
                        <tr>
                            <td>Cantidad Racimos</td>
                            <td>{{ $evaluacion->cantidad_racimos }}</td>
                        </tr>
                        @endif
                        
                        @if(isset($evaluacion->verde))
                        <tr>
                            <td>Verde</td>
                            <td>{{ $evaluacion->verde }}%</td>
                        </tr>
                        @endif
                        
                        @if(isset($evaluacion->maduro))
                        <tr>
                            <td>Maduro</td>
                            <td>{{ $evaluacion->maduro }}%</td>
                        </tr>
                        @endif
                        
                        @if(isset($evaluacion->sobremaduro))
                        <tr>
                            <td>Sobremaduro</td>
                            <td>{{ $evaluacion->sobremaduro }}%</td>
                        </tr>
                        @endif
                        
                        @if(isset($evaluacion->pedunculo))
                        <tr>
                            <td>Pedúnculo</td>
                            <td>{{ $evaluacion->pedunculo }}%</td>
                        </tr>
                        @endif
                        
                        @if(!empty($evaluacion->conformacion))
                        <tr>
                            <td>Conformación</td>
                            <td>{{ $evaluacion->conformacion }}</td>
                        </tr>
                        @endif
                    </table>
                    
                    @if(!empty($evaluacion->observaciones))
                    <div class="observations">
                        <strong>Observaciones:</strong> {{ $evaluacion->observaciones }}
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
        @endif
        
        {{-- ===== PÁGINA 6: CIERRE Y FIRMAS ===== --}}
        <div class="break-before mt-20"></div>
        
        {{-- Sección 10: OBSERVACIONES FINALES --}}
        @if($visita->cierreVisita && !empty($visita->cierreVisita->observaciones_finales))
        <div class="section">
            <h2>10. OBSERVACIONES FINALES</h2>
            
            <div class="intro-text">
                {{ $visita->cierreVisita->observaciones_finales }}
            </div>
        </div>
        @endif
        
        {{-- Sección 11: RECOMENDACIONES --}}
        @if($visita->cierreVisita && !empty($visita->cierreVisita->recomendaciones))
        <div class="section">
            <h2>11. RECOMENDACIONES</h2>
            
            <div class="intro-text">
                {{ $visita->cierreVisita->recomendaciones }}
            </div>
        </div>
        @endif
        
        {{-- FIRMAS --}}
        @if($visita->cierreVisita && 
           (!empty($visita->cierreVisita->firma_responsable) || 
            !empty($visita->cierreVisita->firma_recibe) || 
            !empty($visita->cierreVisita->firma_testigo)))
        <div class="firmas-container no-break">
            <h3>FIRMAS</h3>
            
            <div style="margin-top: 25px;">
                @if(!empty($visita->cierreVisita->firma_responsable))
                <div class="firma-item">
                    <div class="firma-label">Técnico Responsable</div>
                    <img src="{{ $visita->cierreVisita->firma_responsable }}" class="firma-img" alt="Firma Responsable">
                    <div class="firma-line"></div>
                </div>
                @endif
                
                @if(!empty($visita->cierreVisita->firma_recibe))
                <div class="firma-item">
                    <div class="firma-label">Representante Finca</div>
                    <img src="{{ $visita->cierreVisita->firma_recibe }}" class="firma-img" alt="Firma Recibe">
                    <div class="firma-line"></div>
                </div>
                @endif
                
                @if(!empty($visita->cierreVisita->firma_testigo))
                <div class="firma-item">
                    <div class="firma-label">Testigo</div>
                    <img src="{{ $visita->cierreVisita->firma_testigo }}" class="firma-img" alt="Firma Testigo">
                    <div class="firma-line"></div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        {{-- ===== PÁGINA 7: IMÁGENES DE LA VISITA ===== --}}
        @if($visita->cierreVisita && $visita->cierreVisita->imagenes)
            @php
                $imagenes = is_array($visita->cierreVisita->imagenes) 
                    ? $visita->cierreVisita->imagenes 
                    : json_decode($visita->cierreVisita->imagenes, true);
            @endphp
            
            @if(!empty($imagenes))
            <div class="break-before mt-20"></div>
            
            <div class="section">
                <h2 style="text-align: center; border-bottom: none; margin-bottom: 30px;">REGISTRO FOTOGRÁFICO DE LA VISITA</h2>
                
                <div class="galeria-imagenes">
                    @php
                        $imagenesPorPagina = 2;
                        $totalImagenes = count($imagenes);
                    @endphp
                    
                    @for($i = 0; $i < $totalImagenes; $i += $imagenesPorPagina)
                        @if($i > 0)
                            <div class="break-before mt-20"></div>
                        @endif
                        
                        <div class="fila-imagenes">
                            @for($j = $i; $j < min($i + $imagenesPorPagina, $totalImagenes); $j++)
                                <div class="imagen-container">
                                    <img src="{{ $imagenes[$j] }}" class="imagen-visita" alt="Foto {{ $j + 1 }}">
                                    <div class="imagen-numero">Foto {{ $j + 1 }}</div>
                                </div>
                            @endfor
                        </div>
                    @endfor
                </div>
            </div>
            @endif
        @endif
        
    </div> {{-- Cierre del .content --}}
    
    {{-- PAGINACIÓN --}}
    <div class="page-number">
        Página <span class="pagenum"></span>
    </div>
</body>
</html>
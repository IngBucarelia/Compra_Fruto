<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Visita Ambiental</title>
    <style>
        /* ===== ESTILOS BASE ===== */
        @page {
            margin: 0;
            padding: 0;
        }
        
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px;
            color: #222;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }
        
        /* ===== HEADER FIJADO ===== */
        .pdf-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 40mm;
            background-color: white;
            z-index: 1000;
        }
        
        .header-image {
            width: 100%;
            height: 40mm;
            object-fit: contain;
        }
        
        /* ===== FOOTER FIJADO ===== */
        .pdf-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 25mm;
            background-color: white;
            z-index: 1000;
        }
        
        .footer-image {
            width: 100%;
            height: 25mm;
            object-fit: contain;
        }
        
        /* ===== CONTENIDO PRINCIPAL ===== */
        .content {
            margin-top: 45mm; /* Espacio para el header */
            margin-bottom: 30mm; /* Espacio para el footer */
            margin-left: 15mm;
            margin-right: 15mm;
            padding: 5mm;
            position: relative;
        }
        
        /* ===== TÍTULOS Y ENCABEZADOS ===== */
        h1 {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            color: #2F4F4F;
            margin: 10px 0 15px 0;
        }
        
        h2 {
            font-size: 14px;
            font-weight: bold;
            color: #2F4F4F;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
        }
        
        h3 {
            font-size: 13px;
            font-weight: bold;
            color: #2F4F4F;
            margin: 15px 0 8px 0;
        }
        
        h4 {
            font-size: 12px;
            font-weight: bold;
            color: #2F4F4F;
            margin: 12px 0 6px 0;
        }
        
        h5 {
            font-size: 11px;
            font-weight: bold;
            color: #2F4F4F;
            margin: 10px 0 5px 0;
        }
        
        /* ===== TABLAS ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin: 8px 0 12px 0;
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
            padding: 6px 8px;
            border: 1px solid #ccc;
        }
        
        table.grid-table td {
            padding: 5px 8px;
            border: 1px solid #ccc;
            vertical-align: top;
        }
        
        table.data-table {
            border: none;
        }
        
        table.data-table tr td:first-child {
            font-weight: bold;
            background-color: #f8f9fa;
            width: 40%;
            padding: 5px 8px;
            border-right: 1px solid #e9ecef;
        }
        
        table.data-table tr td {
            padding: 5px 8px;
            border-bottom: 1px solid #dee2e6;
        }
        
        /* ===== DATA CARDS ===== */
        .data-card {
            background-color: #f8fff8;
            border: 1px solid #d4edda;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        
        .data-card h5 {
            background-color: #e8f5e9;
            padding: 6px 10px;
            margin: -10px -10px 10px -10px;
            border-radius: 4px 4px 0 0;
            color: #2F4F4F;
            font-size: 11px;
        }
        
        /* ===== TEXTOS ===== */
        .intro-text {
            font-size: 11px;
            line-height: 1.5;
            text-align: justify;
            margin-bottom: 15px;
        }
        
        .observations {
            font-size: 10px;
            font-style: italic;
            color: #666;
            margin: 6px 0;
            padding-left: 10px;
            border-left: 2px solid #6c757d;
        }
        
        /* ===== IMÁGENES ===== */
        .company-logo {
            width: 150px;
            height: auto;
            max-height: 50px;
            display: block;
            margin: 0 auto 10px auto;
        }
        
        /* ===== SECCIONES ===== */
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        /* ===== CONTROL DE PÁGINAS ===== */
        .page-break {
            page-break-before: always;
        }
        
        /* ===== FIRMAS ===== */
        .firmas-container {
            margin-top: 20px;
            text-align: center;
            page-break-inside: avoid;
            padding: 15px;
            border-top: 1px solid #dee2e6;
        }
        
        .firma-item {
            display: inline-block;
            margin: 0 30px;
            text-align: center;
            vertical-align: top;
            min-width: 120px;
        }
        
        .firma-line {
            width: 120px;
            border-top: 1px solid #000;
            margin: 5px auto;
        }
        
        .firma-label {
            font-size: 10px;
            margin-bottom: 8px;
            font-weight: bold;
            color: #495057;
        }
        
        /* ===== PAGINACIÓN ===== */
        .page-number {
            position: fixed;
            bottom: 30mm;
            right: 20mm;
            font-size: 9px;
            color: #666;
            z-index: 2000;
            background-color: white;
            padding: 1px 5px;
            border-radius: 2px;
            border: 1px solid #ddd;
        }
        
        /* ===== UTILIDADES ===== */
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .mb-10 { margin-bottom: 10px; }
        .mt-10 { margin-top: 10px; }
        
        /* ===== BADGES ===== */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            border-radius: 2px;
            text-align: center;
            min-width: 25px;
        }
        
        .badge-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        /* ===== COMPONENTE GRID ===== */
        .componente-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            margin: 8px 0;
        }
        
        .componente-item {
            margin: 2px 0;
            font-size: 10px;
        }
        
        .componente-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            min-width: 140px;
        }
        
        .componente-value {
            color: #333;
            margin-left: 5px;
        }
        
        /* ===== ESTILOS PARA EVITAR CORTES ===== */
        .no-break {
            page-break-inside: avoid;
        }
        
        /* ===== ENCABEZADO DE EMPRESA EN PÁGINAS INTERNAS ===== */
        .company-header {
            text-align: center;
            margin: 15px 0 20px 0;
            padding: 10px 0;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            background-color: #fafafa;
        }
        
        .company-header h3 {
            font-size: 14px;
            margin: 5px 0;
            color: #000;
        }
        
        .company-header h4 {
            font-size: 12px;
            margin: 5px 0;
            color: #000;
        }
        
        .company-header p {
            font-size: 10px;
            margin: 5px 0;
            color: #666;
        }
        
        /* ===== ESTILOS PARA IMÁGENES DE EVIDENCIA ===== */
        .evidence-image {
            max-width: 80mm;
            max-height: 60mm;
            width: auto;
            height: auto;
            border: 1px solid #e0e0e0;
            border-radius: 3px;
        }
        
        /* ===== CLASES ESPECIALES ===== */
        .first-page-content {
            margin-top: 0;
        }
    </style>
</head>
<body>
    {{-- ===== HEADER IMAGE ===== --}}
    @if(file_exists(public_path('images/header.png')))
    <div class="pdf-header">
        <img src="{{ public_path('images/header.png') }}" class="header-image" alt="Header">
    </div>
    @else
    <div class="pdf-header" style="height: 25mm; display: flex; align-items: center; justify-content: center;">
        <div class="title-bar" style="width: 100%; text-align: center;">
            <h1 style="margin: 5px 0; font-size: 14px;">INFORME DE VISITA AMBIENTAL</h1>
            <p style="font-size: 11px; margin: 0;">Palmas Oleaginosas Bucarelia</p>
        </div>
    </div>
    @endif
    
    {{-- ===== FOOTER IMAGE ===== --}}
    @if(file_exists(public_path('images/footer.png')))
    <div class="pdf-footer">
        <img src="{{ public_path('images/footer.png') }}" class="footer-image" alt="Footer">
    </div>
    @endif
    
    {{-- ===== CONTENIDO PRINCIPAL ===== --}}
    <div class="content first-page-content">
        
        {{-- ===== PÁGINA 1 ===== --}}
        
        {{-- Logo de empresa --}}
        @if(file_exists(public_path('images/logo2.webp')))
        <div class="text-center mb-10">
            <img src="{{ public_path('images/logo2.webp') }}" class="company-logo" alt="Logo">
        </div>
        @endif
        
        <h1>INFORME DE VISITA AMBIENTAL</h1>
        
        {{-- Tabla de información general --}}
        <table class="grid-table">
            <thead>
                <tr>
                    <th>PROVEEDOR</th>
                    <th>PLANTACIÓN</th>
                    <th>UBICACIÓN</th>
                    <th>TÉCNICO</th>
                    <th>FECHA</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $visita->proveedor?->proveedor_nombre ?? 'No especificado' }}</td>
                    <td>{{ $visita->plantacion?->nombre ?? 'No especificado' }}</td>
                    <td>{{ $visita->plantacion?->ubicacion ?? 'No especificada' }}</td>
                    <td>{{ $visita->tecnico->nombre ?? 'No asignado' }}</td>
                    <td>{{ $visita->fecha_visita ? \Carbon\Carbon::parse($visita->fecha_visita)->format('d/m/Y') : 'N/A' }}</td>
                    <td>
                        @if($visita->estado === 'finalizada')
                            <span class="badge badge-success">FINALIZADA</span>
                        @else
                            <span class="badge badge-warning">{{ strtoupper($visita->estado) }}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        
        {{-- Sección 1: INTRODUCCIÓN --}}
        <div class="section">
            <h2>1. INTRODUCCIÓN</h2>
            <div class="intro-text">
                Palmas Oleaginosas Bucarelia, en su compromiso con la sostenibilidad ambiental y el desarrollo de una agricultura responsable, realiza visitas ambientales periódicas a las plantaciones de sus proveedores de racimo de fruta fresca (RFF). Estas visitas tienen como objetivo evaluar el cumplimiento de los componentes ambientales establecidos en el marco de la gestión ambiental, promoviendo prácticas agrícolas sostenibles y el manejo adecuado de los recursos naturales. El presente informe documenta los hallazgos de la visita ambiental realizada, con el fin de identificar oportunidades de mejora y fortalecer el desempeño ambiental de la plantación.
            </div>
        </div>
        
        {{-- Sección 2: RESÚMEN DE COMPONENTES AMBIENTALES --}}
        <div class="section">
            <h2>2. RESÚMEN DE COMPONENTES AMBIENTALES</h2>
            
            @php
                $componentes = [
                    '💧 Agua - Captación Legal' => $visita->aguaCaptacionLegal,
                    '🚰 Agua - Uso Eficiente' => $visita->aguaUsoEficiente,
                    '🌱 Suelo - Conservación' => $visita->sueloConservacion,
                    '⚡ Energía - Manejo' => $visita->energiaManejo,
                    '🤝 Gobernanza Hídrica' => $visita->gobernanzaHidrica,
                    '🌍 Emisiones GEI' => $visita->emisionesGei,
                    '🗑️ Residuos - Manejo' => $visita->residuosManejo,
                    '⚗️ Sustancias Químicas/Biológicas' => $visita->sustanciasQuimicasBiologicas,
                    '💦 Vertimiento - Manejo' => $visita->vertimientoManejo,
                    '🌳 Plantación HMP' => $visita->plantacionHmp,
                    '🦜 Plantación AVC' => $visita->plantacionAvc,
                    '🌿 Plantación Ecosistemas' => $visita->plantacionEcosistema
                ];
                
                $total = count($componentes);
                $registrados = 0;
                foreach ($componentes as $comp) { if ($comp) $registrados++; }
                $porcentaje = $total > 0 ? round(($registrados / $total) * 100) : 0;
            @endphp
            
            <table class="data-table">
                <tr>
                    <td>Total de Componentes Ambientales</td>
                    <td>{{ $total }}</td>
                </tr>
                <tr>
                    <td>Componentes Registrados</td>
                    <td>{{ $registrados }}</td>
                </tr>
                <tr>
                    <td>Porcentaje de Completitud</td>
                    <td>{{ $porcentaje }}%</td>
                </tr>
                <tr>
                    <td>Estado General</td>
                    <td>
                        @if($porcentaje >= 80)
                            <span class="badge badge-success">SATISFACTORIO</span>
                        @elseif($porcentaje >= 50)
                            <span class="badge badge-warning">REGULAR</span>
                        @else
                            <span class="badge badge-danger">DEFICIENTE</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        
        {{-- ===== PÁGINA 2: COMPONENTES AMBIENTALES 1-4 ===== --}}
        <div class="page-break"></div>
        
        {{-- Encabezado de empresa para páginas internas --}}
        <div class="company-header">
            <h3>Palmas Oleaginosas</h3>
            <h4>BUCARELIA S.A.S</h4>
            <p>Nit. 860.009.787-9</p>
        </div>
        
        {{-- Sección 3: COMPONENTES AMBIENTALES --}}
        <h2>3. COMPONENTES AMBIENTALES</h2>
        
        {{-- Componente 1: AGUA - CAPTACIÓN LEGAL --}}
        <div class="data-card no-break">
            <h5>3.1 💧 AGUA - CAPTACIÓN LEGAL</h5>
            
            @if($visita->aguaCaptacionLegal)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Permiso/Concesión:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->aguaCaptacionLegal->permiso_concesion ? 'success' : 'danger' }}">
                            {{ $visita->aguaCaptacionLegal->permiso_concesion ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Permiso Ocupación Cauce:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->aguaCaptacionLegal->permiso_ocupacion_cauce ? 'success' : 'danger' }}">
                            {{ $visita->aguaCaptacionLegal->permiso_ocupacion_cauce ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Permisos Captación:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->aguaCaptacionLegal->permisos_captacion ? 'success' : 'danger' }}">
                            {{ $visita->aguaCaptacionLegal->permisos_captacion ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Registro de Agua:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->aguaCaptacionLegal->registro_agua ? 'success' : 'danger' }}">
                            {{ $visita->aguaCaptacionLegal->registro_agua ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Cumple Manejo/Construcción:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->aguaCaptacionLegal->cumple_manejo_construccion ? 'success' : 'danger' }}">
                            {{ $visita->aguaCaptacionLegal->cumple_manejo_construccion ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
            </div>
            
            @if($visita->aguaCaptacionLegal->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->aguaCaptacionLegal->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de captación legal de agua.
            </div>
            @endif
        </div>
        
        {{-- Componente 2: AGUA - USO EFICIENTE --}}
        <div class="data-card no-break">
            <h5>3.2 🚰 AGUA - USO EFICIENTE</h5>
            
            @if($visita->aguaUsoEficiente)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Plan de Ahorro y Uso Eficiente:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->aguaUsoEficiente->plan_ahorro ? 'success' : 'danger' }}">
                            {{ $visita->aguaUsoEficiente->plan_ahorro ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Mantenimiento Sistemas:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->aguaUsoEficiente->mantenimiento_sistemas ? 'success' : 'danger' }}">
                            {{ $visita->aguaUsoEficiente->mantenimiento_sistemas ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Uso Información Técnica:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->aguaUsoEficiente->uso_informacion_balance ? 'success' : 'danger' }}">
                            {{ $visita->aguaUsoEficiente->uso_informacion_balance ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Mecanismo de Medición:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->aguaUsoEficiente->mecanismo_medicion ? 'success' : 'danger' }}">
                            {{ $visita->aguaUsoEficiente->mecanismo_medicion ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                @if($visita->aguaUsoEficiente->consumo_agua)
                <div class="componente-item">
                    <span class="componente-label">Consumo de Agua:</span>
                    <span class="componente-value">{{ number_format($visita->aguaUsoEficiente->consumo_agua, 2) }} m³/mes</span>
                </div>
                @endif
                
                @if($visita->aguaUsoEficiente->metodo_medicion)
                <div class="componente-item">
                    <span class="componente-label">Método de Medición:</span>
                    <span class="componente-value">
                        @switch($visita->aguaUsoEficiente->metodo_medicion)
                            @case('contador_agua') Contador de agua @break
                            @case('medidor_volumen') Medidor de volumen @break
                            @case('estimacion_manual') Estimación manual @break
                            @case('sistema_automatico') Sistema automático @break
                            @case('lectura_mensual') Lectura mensual @break
                            @default {{ $visita->aguaUsoEficiente->metodo_medicion }}
                        @endswitch
                    </span>
                </div>
                @endif
            </div>
            
            @if($visita->aguaUsoEficiente->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->aguaUsoEficiente->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de uso eficiente de agua.
            </div>
            @endif
        </div>
        
        {{-- Componente 3: SUELO - CONSERVACIÓN --}}
        <div class="data-card no-break">
            <h5>3.3 🌱 SUELO - CONSERVACIÓN</h5>
            
            @if($visita->sueloConservacion)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Uso de Fuego Preparación:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ !$visita->sueloConservacion->uso_fuego_preparacion ? 'success' : 'danger' }}">
                            {{ $visita->sueloConservacion->uso_fuego_preparacion ? 'SÍ' : 'NO' }}
                        </span>
                        @if($visita->sueloConservacion->uso_fuego_preparacion)
                            <span style="color: #dc3545; font-size: 9px;">(Práctica no recomendada)</span>
                        @endif
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Control Coberturas Invasoras:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->sueloConservacion->control_coberturas_invasoras ? 'success' : 'warning' }}">
                            {{ $visita->sueloConservacion->control_coberturas_invasoras ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Siembra Coberturas:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->sueloConservacion->siembra_coberturas ? 'success' : 'warning' }}">
                            {{ $visita->sueloConservacion->siembra_coberturas ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                @if($visita->sueloConservacion->siembra_coberturas)
                    @if($visita->sueloConservacion->area_cobertura)
                    <div class="componente-item">
                        <span class="componente-label">Área Cobertura:</span>
                        <span class="componente-value">{{ number_format($visita->sueloConservacion->area_cobertura, 2) }} Ha</span>
                    </div>
                    @endif
                    
                    @if($visita->sueloConservacion->tipo_cobertura)
                    <div class="componente-item">
                        <span class="componente-label">Tipo de Cobertura:</span>
                        <span class="componente-value">
                            @switch($visita->sueloConservacion->tipo_cobertura)
                                @case('leguminosas') Leguminosas (kudzu, canavalia, etc.) @break
                                @case('gramineas') Gramíneas (brachiaria, pennisetum, etc.) @break
                                @case('mixta') Mezcla de especies @break
                                @case('natural') Cobertura natural @break
                                @default {{ $visita->sueloConservacion->tipo_cobertura }}
                            @endswitch
                        </span>
                    </div>
                    @endif
                    
                    @if($visita->sueloConservacion->porcentaje_cobertura)
                    <div class="componente-item">
                        <span class="componente-label">Porcentaje Cobertura:</span>
                        <span class="componente-value">{{ number_format($visita->sueloConservacion->porcentaje_cobertura, 1) }}%</span>
                    </div>
                    @endif
                @endif
            </div>
            
            @if($visita->sueloConservacion->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->sueloConservacion->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de conservación de suelo.
            </div>
            @endif
        </div>
        
        {{-- Componente 4: ENERGÍA - MANEJO --}}
        <div class="data-card no-break">
            <h5>3.4 ⚡ ENERGÍA - MANEJO</h5>
            
            @if($visita->energiaManejo)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Registro Consumo Combustible:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->energiaManejo->registro_consumo_combustible ? 'success' : 'warning' }}">
                            {{ $visita->energiaManejo->registro_consumo_combustible ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Plan Uso Eficiente:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->energiaManejo->plan_uso_eficiente ? 'success' : 'warning' }}">
                            {{ $visita->energiaManejo->plan_uso_eficiente ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Seguimiento Indicadores:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->energiaManejo->seguimiento_indicadores ? 'success' : 'warning' }}">
                            {{ $visita->energiaManejo->seguimiento_indicadores ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                @if($visita->energiaManejo->consumo_energia_kwh)
                <div class="componente-item">
                    <span class="componente-label">Consumo Energía:</span>
                    <span class="componente-value">{{ number_format($visita->energiaManejo->consumo_energia_kwh, 2) }} kWh/mes</span>
                </div>
                @endif
            </div>
            
            @if($visita->energiaManejo->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->energiaManejo->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de manejo de energía.
            </div>
            @endif
        </div>
        
        {{-- ===== PÁGINA 3: COMPONENTES AMBIENTALES 5-8 ===== --}}
        <div class="page-break"></div>
        
        {{-- Encabezado de empresa --}}
        <div class="company-header">
            <h3>Palmas Oleaginosas</h3>
            <h4>BUCARELIA S.A.S</h4>
            <p>Nit. 860.009.787-9</p>
        </div>
        
        {{-- Componente 5: GOBERNANZA HÍDRICA --}}
        <div class="data-card no-break">
            <h5>3.5 🤝 GOBERNANZA HÍDRICA</h5>
            
            @if($visita->gobernanzaHidrica)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Canales de Comunicación:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->gobernanzaHidrica->canales_comunicacion ? 'success' : 'warning' }}">
                            {{ $visita->gobernanzaHidrica->canales_comunicacion ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Identifica Actores Afectados:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->gobernanzaHidrica->identifica_actores_afectados ? 'success' : 'warning' }}">
                            {{ $visita->gobernanzaHidrica->identifica_actores_afectados ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Participa en Actividades Gestión:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->gobernanzaHidrica->participa_actividades_gestion ? 'success' : 'warning' }}">
                            {{ $visita->gobernanzaHidrica->participa_actividades_gestion ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
            </div>
            
            @if($visita->gobernanzaHidrica->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->gobernanzaHidrica->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de gobernanza hídrica.
            </div>
            @endif
        </div>
        
        {{-- Componente 6: EMISIONES GEI --}}
        <div class="data-card no-break">
            <h5>3.6 🌍 EMISIONES GEI</h5>
            
            @if($visita->emisionesGei)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Cuantifica Emisiones:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->emisionesGei->cuantifica_emisiones ? 'success' : 'info' }}">
                            {{ $visita->emisionesGei->cuantifica_emisiones ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Implementa Acciones Reducción:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->emisionesGei->implementa_acciones_reduccion ? 'success' : 'warning' }}">
                            {{ $visita->emisionesGei->implementa_acciones_reduccion ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                @if($visita->emisionesGei->cuantifica_emisiones)
                    @if($visita->emisionesGei->combustible)
                    <div class="componente-item">
                        <span class="componente-label">Combustible:</span>
                        <span class="componente-value">{{ $visita->emisionesGei->combustible }}</span>
                    </div>
                    @endif
                    
                    @if($visita->emisionesGei->distancia)
                    <div class="componente-item">
                        <span class="componente-label">Distancia:</span>
                        <span class="componente-value">{{ number_format($visita->emisionesGei->distancia, 2) }} km</span>
                    </div>
                    @endif
                    
                    @if($visita->emisionesGei->huella_carbono)
                    <div class="componente-item">
                        <span class="componente-label">Huella de Carbono:</span>
                        <span class="componente-value">{{ number_format($visita->emisionesGei->huella_carbono, 2) }} tCO₂e</span>
                    </div>
                    @endif
                @endif
                
                @if($visita->emisionesGei->implementa_acciones_reduccion && $visita->emisionesGei->acciones_reduccion)
                <div class="componente-item">
                    <span class="componente-label">Acciones de Reducción:</span>
                    <span class="componente-value">{{ $visita->emisionesGei->acciones_reduccion }}</span>
                </div>
                @endif
            </div>
            
            @if($visita->emisionesGei->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->emisionesGei->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de emisiones GEI.
            </div>
            @endif
        </div>
        
        {{-- Componente 7: RESIDUOS - MANEJO --}}
        <div class="data-card no-break">
            <h5>3.7 🗑️ RESIDUOS - MANEJO</h5>
            
            @if($visita->residuosManejo)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Capacita Personal:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->residuosManejo->capacita_personal ? 'success' : 'warning' }}">
                            {{ $visita->residuosManejo->capacita_personal ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                @if($visita->residuosManejo->capacita_personal)
                    @if($visita->residuosManejo->personas_manipulan)
                    <div class="componente-item">
                        <span class="componente-label">Personas que Manipulan:</span>
                        <span class="componente-value">{{ $visita->residuosManejo->personas_manipulan }} personas</span>
                    </div>
                    @endif
                    
                    @if($visita->residuosManejo->personas_capacitadas)
                    <div class="componente-item">
                        <span class="componente-label">Personas Capacitadas:</span>
                        <span class="componente-value">{{ $visita->residuosManejo->personas_capacitadas }} personas</span>
                    </div>
                    @endif
                    
                    @if($visita->residuosManejo->porcentaje_capacitadas)
                    <div class="componente-item">
                        <span class="componente-label">Porcentaje Capacitadas:</span>
                        <span class="componente-value">{{ number_format($visita->residuosManejo->porcentaje_capacitadas, 1) }}%</span>
                    </div>
                    @endif
                @endif
                
                <div class="componente-item">
                    <span class="componente-label">Conoce Diferencias Residuos:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->residuosManejo->conoce_diferencias ? 'success' : 'warning' }}">
                            {{ $visita->residuosManejo->conoce_diferencias ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Puntos Ecológicos:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->residuosManejo->puntos_ecologicos ? 'success' : 'warning' }}">
                            {{ $visita->residuosManejo->puntos_ecologicos ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Pesa y Registra:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->residuosManejo->pesa_y_registra ? 'success' : 'warning' }}">
                            {{ $visita->residuosManejo->pesa_y_registra ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Acciones Minimizar Impacto:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->residuosManejo->acciones_minimizar_impacto ? 'success' : 'warning' }}">
                            {{ $visita->residuosManejo->acciones_minimizar_impacto ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
            </div>
            
            @if($visita->residuosManejo->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->residuosManejo->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de manejo de residuos.
            </div>
            @endif
        </div>
        
        {{-- Componente 8: SUSTANCIAS QUÍMICAS/BIOLÓGICAS --}}
        <div class="data-card no-break">
            <h5>3.8 ⚗️ SUSTANCIAS QUÍMICAS/BIOLÓGICAS</h5>
            
            @if($visita->sustanciasQuimicasBiologicas)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Cuenta con POES:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->sustanciasQuimicasBiologicas->cuenta_poes ? 'success' : 'warning' }}">
                            {{ $visita->sustanciasQuimicasBiologicas->cuenta_poes ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Personal Capacitado:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->sustanciasQuimicasBiologicas->personal_capacitado ? 'success' : 'warning' }}">
                            {{ $visita->sustanciasQuimicasBiologicas->personal_capacitado ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Almacenamiento Adecuado:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->sustanciasQuimicasBiologicas->almacenamiento_adecuado ? 'success' : 'danger' }}">
                            {{ $visita->sustanciasQuimicasBiologicas->almacenamiento_adecuado ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                @if($visita->sustanciasQuimicasBiologicas->imagen_poes)
                <div class="componente-item">
                    <span class="componente-label">Imagen POES:</span>
                    <span class="componente-value">
                        <span class="badge badge-success">Adjunta</span>
                    </span>
                </div>
                @endif
            </div>
            
            @if($visita->sustanciasQuimicasBiologicas->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->sustanciasQuimicasBiologicas->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de sustancias químicas/biológicas.
            </div>
            @endif
        </div>
        
        {{-- ===== PÁGINA 4: COMPONENTES AMBIENTALES 9-12 ===== --}}
        <div class="page-break"></div>
        
        {{-- Encabezado de empresa --}}
        <div class="company-header">
            <h3>Palmas Oleaginosas</h3>
            <h4>BUCARELIA S.A.S</h4>
            <p>Nit. 860.009.787-9</p>
        </div>
        
        {{-- Componente 9: VERTIMIENTO - MANEJO --}}
        <div class="data-card no-break">
            <h5>3.9 💦 VERTIMIENTO - MANEJO</h5>
            
            @if($visita->vertimientoManejo)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Permiso de Vertimientos:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->vertimientoManejo->permiso_vertimiento ? 'success' : 'danger' }}">
                            {{ $visita->vertimientoManejo->permiso_vertimiento ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                @if($visita->vertimientoManejo->permiso_vertimiento)
                    @if($visita->vertimientoManejo->numero_vertimientos_permitidos !== null)
                    <div class="componente-item">
                        <span class="componente-label">Vertimientos Permitidos:</span>
                        <span class="componente-value">{{ $visita->vertimientoManejo->numero_vertimientos_permitidos }}</span>
                    </div>
                    @endif
                    
                    @if($visita->vertimientoManejo->numero_vertimientos_totales !== null)
                    <div class="componente-item">
                        <span class="componente-label">Vertimientos Totales:</span>
                        <span class="componente-value">{{ $visita->vertimientoManejo->numero_vertimientos_totales }}</span>
                    </div>
                    @endif
                @endif
                
                <div class="componente-item">
                    <span class="componente-label">Cumple Obligación Permiso:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->vertimientoManejo->cumple_obligacion_permiso ? 'success' : 'danger' }}">
                            {{ $visita->vertimientoManejo->cumple_obligacion_permiso ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Realiza Triple Lavado:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->vertimientoManejo->realiza_triplelavado ? 'success' : 'warning' }}">
                            {{ $visita->vertimientoManejo->realiza_triplelavado ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
            </div>
            
            @if($visita->vertimientoManejo->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->vertimientoManejo->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de manejo de vertimientos.
            </div>
            @endif
        </div>
        
        {{-- Componente 10: PLANTACIÓN HMP --}}
        <div class="data-card no-break">
            <h5>3.10 🌳 PLANTACIÓN HMP</h5>
            
            @if($visita->plantacionHmp)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Implementa HMP:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->plantacionHmp->implementa_hmp ? 'success' : 'info' }}">
                            {{ $visita->plantacionHmp->implementa_hmp ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                @if($visita->plantacionHmp->implementa_hmp)
                    @if($visita->plantacionHmp->hectareas_hmp)
                    <div class="componente-item">
                        <span class="componente-label">Hectáreas HMP:</span>
                        <span class="componente-value">{{ number_format($visita->plantacionHmp->hectareas_hmp, 2) }} Ha</span>
                    </div>
                    @endif
                    
                    @if($visita->plantacionHmp->porcentaje_hmp)
                    <div class="componente-item">
                        <span class="componente-label">Porcentaje HMP:</span>
                        <span class="componente-value">{{ number_format($visita->plantacionHmp->porcentaje_hmp, 1) }}%</span>
                    </div>
                    @endif
                    
                    <div class="componente-item">
                        <span class="componente-label">Incluye HMP en Diseño:</span>
                        <span class="componente-value">
                            <span class="badge badge-{{ $visita->plantacionHmp->incluye_hmp_disenio ? 'success' : 'warning' }}">
                                {{ $visita->plantacionHmp->incluye_hmp_disenio ? 'SÍ' : 'NO' }}
                            </span>
                        </span>
                    </div>
                @endif
            </div>
            
            @if($visita->plantacionHmp->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->plantacionHmp->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de plantación HMP.
            </div>
            @endif
        </div>
        
        {{-- Componente 11: PLANTACIÓN AVC --}}
        <div class="data-card no-break">
            <h5>3.11 🦜 PLANTACIÓN AVC</h5>
            
            @if($visita->plantacionAvc)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Registros de Avistamientos:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->plantacionAvc->registros_avistamientos ? 'success' : 'info' }}">
                            {{ $visita->plantacionAvc->registros_avistamientos ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Identifica AVC/ARC:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->plantacionAvc->identifica_avc_arc ? 'success' : 'warning' }}">
                            {{ $visita->plantacionAvc->identifica_avc_arc ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                @if($visita->plantacionAvc->identifica_avc_arc)
                    @if(!empty($visita->plantacionAvc->especies_identificadas))
                    <div class="componente-item">
                        <span class="componente-label">Especies Identificadas:</span>
                        <span class="componente-value">{{ $visita->plantacionAvc->especies_identificadas }}</span>
                    </div>
                    @endif
                    
                    @if($visita->plantacionAvc->fecha_identificacion)
                    <div class="componente-item">
                        <span class="componente-label">Fecha Identificación:</span>
                        <span class="componente-value">{{ \Carbon\Carbon::parse($visita->plantacionAvc->fecha_identificacion)->format('d/m/Y') }}</span>
                    </div>
                    @endif
                    
                    @if($visita->plantacionAvc->tipo_identificacion)
                    <div class="componente-item">
                        <span class="componente-label">Tipo de Identificación:</span>
                        <span class="componente-value">
                            @php
                                $tipos = is_array($visita->plantacionAvc->tipo_identificacion) 
                                    ? $visita->plantacionAvc->tipo_identificacion 
                                    : json_decode($visita->plantacionAvc->tipo_identificacion, true);
                            @endphp
                            @if(is_array($tipos) && count($tipos) > 0)
                                {{ implode(', ', array_map(function($tipo) {
                                    return match($tipo) {
                                        'directa' => 'Directa',
                                        'indirecta' => 'Indirecta',
                                        'entrevistas' => 'Entrevistas',
                                        'trampas_camara' => 'Trampas Cámara',
                                        'huellas' => 'Huellas',
                                        'registros_acusticos' => 'Registros Acústicos',
                                        default => $tipo
                                    };
                                }, $tipos)) }}
                            @endif
                        </span>
                    </div>
                    @endif
                @endif
                
                <div class="componente-item">
                    <span class="componente-label">Implementa Medidas de Manejo:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->plantacionAvc->implementa_medidas_manejo ? 'success' : 'warning' }}">
                            {{ $visita->plantacionAvc->implementa_medidas_manejo ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
            </div>
            
            @if($visita->plantacionAvc->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->plantacionAvc->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de plantación AVC.
            </div>
            @endif
        </div>
        
        {{-- Componente 12: PLANTACIÓN ECOSISTEMAS --}}
        <div class="data-card no-break">
            <h5>3.12 🌿 PLANTACIÓN ECOSISTEMAS</h5>
            
            @if($visita->plantacionEcosistema)
            <div class="componente-grid">
                <div class="componente-item">
                    <span class="componente-label">Planes de Manejo Diferenciados:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->plantacionEcosistema->planes_manejo_diferenciados ? 'success' : 'warning' }}">
                            {{ $visita->plantacionEcosistema->planes_manejo_diferenciados ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Acciones de Conservación Fragmentos:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->plantacionEcosistema->acciones_conservacion_fragmentos ? 'success' : 'warning' }}">
                            {{ $visita->plantacionEcosistema->acciones_conservacion_fragmentos ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Implementa Planes de Manejo Diferenciado:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->plantacionEcosistema->implementa_planes_manejo_diferenciado ? 'success' : 'warning' }}">
                            {{ $visita->plantacionEcosistema->implementa_planes_manejo_diferenciado ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
                
                <div class="componente-item">
                    <span class="componente-label">Respeta Distancias Ronda Hídrica:</span>
                    <span class="componente-value">
                        <span class="badge badge-{{ $visita->plantacionEcosistema->respeta_distancias_ronda_hidrica ? 'success' : 'danger' }}">
                            {{ $visita->plantacionEcosistema->respeta_distancias_ronda_hidrica ? 'SÍ' : 'NO' }}
                        </span>
                    </span>
                </div>
            </div>
            
            @if($visita->plantacionEcosistema->observaciones)
            <div class="observations">
                <strong>Observaciones:</strong> {{ $visita->plantacionEcosistema->observaciones }}
            </div>
            @endif
            @else
            <div class="observations">
                No se ha registrado información de plantación ecosistemas.
            </div>
            @endif
        </div>
        
        {{-- ===== PÁGINA 5: CIERRE DE VISITA ===== --}}
        <div class="page-break"></div>
        
        {{-- Encabezado de empresa --}}
        <div class="company-header">
            <h3>Palmas Oleaginosas</h3>
            <h4>BUCARELIA S.A.S</h4>
            <p>Nit. 860.009.787-9</p>
        </div>
        
        @if($visita->cierreVisitaAmbiental)
        {{-- Sección 4: OBSERVACIONES FINALES --}}
        <div class="section">
            <h2>4. OBSERVACIONES FINALES</h2>
            
            @if($visita->cierreVisitaAmbiental->observaciones_finales)
            <div class="intro-text">
                {{ $visita->cierreVisitaAmbiental->observaciones_finales }}
            </div>
            @else
            <div class="intro-text">
                No se registraron observaciones finales para esta visita ambiental.
            </div>
            @endif
        </div>
        
        {{-- Sección 5: RECOMENDACIONES --}}
        <div class="section">
            <h2>5. RECOMENDACIONES</h2>
            
            @if($visita->cierreVisitaAmbiental->recomendaciones)
            <div class="intro-text">
                {{ $visita->cierreVisitaAmbiental->recomendaciones }}
            </div>
            @else
            <div class="intro-text">
                No se registraron recomendaciones específicas para esta visita ambiental.
            </div>
            @endif
        </div>
        
        {{-- Sección 6: DATOS DE CIERRE --}}
        <div class="section">
            <h2>6. DATOS DE CIERRE</h2>
            
            <table class="data-table">
                <tr>
                    <td>Fecha de Cierre</td>
                    <td>{{ $visita->cierreVisitaAmbiental->fecha_cierre ? \Carbon\Carbon::parse($visita->cierreVisitaAmbiental->fecha_cierre)->format('d/m/Y') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Estado de la Visita</td>
                    <td>{{ ucfirst($visita->cierreVisitaAmbiental->estado_visita) ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>Finalizada En</td>
                    <td>{{ $visita->cierreVisitaAmbiental->finalizada_en ? \Carbon\Carbon::parse($visita->cierreVisitaAmbiental->finalizada_en)->format('d/m/Y H:i') : 'N/A' }}</td>
                </tr>
            </table>
        </div>
        
        {{-- FIRMAS --}}
        @if($visita->cierreVisitaAmbiental->firma_responsable || $visita->cierreVisitaAmbiental->firma_recibe || $visita->cierreVisitaAmbiental->firma_testigo)
        <div class="firmas-container no-break">
            <h3>FIRMAS</h3>
            
            <div style="margin-top: 15px;">
                @if($visita->cierreVisitaAmbiental->firma_responsable)
                <div class="firma-item">
                    <div class="firma-label">Técnico Ambiental Responsable</div>
                    @php
                        $ruta_firma = storage_path('app/public/' . str_replace('storage/', '', $visita->cierreVisitaAmbiental->firma_responsable));
                    @endphp
                    @if(file_exists($ruta_firma))
                        <img src="{{ $ruta_firma }}" style="max-height: 50px; max-width: 150px; margin: 5px auto; display: block;" alt="Firma Responsable">
                    @else
                        <div style="border: 1px dashed #ccc; width: 150px; height: 50px; margin: 5px auto;"></div>
                    @endif
                    <div class="firma-line"></div>
                </div>
                @endif
                
                @if($visita->cierreVisitaAmbiental->firma_recibe)
                <div class="firma-item">
                    <div class="firma-label">Representante del Proveedor</div>
                    @php
                        $ruta_firma = storage_path('app/public/' . str_replace('storage/', '', $visita->cierreVisitaAmbiental->firma_recibe));
                    @endphp
                    @if(file_exists($ruta_firma))
                        <img src="{{ $ruta_firma }}" style="max-height: 50px; max-width: 150px; margin: 5px auto; display: block;" alt="Firma Recibe">
                    @else
                        <div style="border: 1px dashed #ccc; width: 150px; height: 50px; margin: 5px auto;"></div>
                    @endif
                    <div class="firma-line"></div>
                </div>
                @endif
                
                @if($visita->cierreVisitaAmbiental->firma_testigo)
                <div class="firma-item">
                    <div class="firma-label">Testigo</div>
                    @php
                        $ruta_firma = storage_path('app/public/' . str_replace('storage/', '', $visita->cierreVisitaAmbiental->firma_testigo));
                    @endphp
                    @if(file_exists($ruta_firma))
                        <img src="{{ $ruta_firma }}" style="max-height: 50px; max-width: 150px; margin: 5px auto; display: block;" alt="Firma Testigo">
                    @else
                        <div style="border: 1px dashed #ccc; width: 150px; height: 50px; margin: 5px auto;"></div>
                    @endif
                    <div class="firma-line"></div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        {{-- IMÁGENES DE EVIDENCIA --}}
        @php
            $imagenes = [];
            if ($visita->cierreVisitaAmbiental && $visita->cierreVisitaAmbiental->imagenes) {
                $imagenes = is_array($visita->cierreVisitaAmbiental->imagenes) 
                    ? $visita->cierreVisitaAmbiental->imagenes 
                    : json_decode($visita->cierreVisitaAmbiental->imagenes, true) ?? [];
                
                $imagenes = array_map(function($img) {
                    $cleaned = str_replace('\/', '/', $img);
                    if (strpos($cleaned, 'storage/') !== 0) {
                        $cleaned = 'storage/' . ltrim($cleaned, '/');
                    }
                    return $cleaned;
                }, $imagenes);
            }
        @endphp
        
        @if(count($imagenes) > 0)
        <div class="page-break"></div>
        
        {{-- Encabezado de empresa --}}
        <div class="company-header">
            <h3>Palmas Oleaginosas</h3>
            <h4>BUCARELIA S.A.S</h4>
            <p>Nit. 860.009.787-9</p>
        </div>
        
        <div class="section">
            <h2 style="text-align: center; border-bottom: none; margin-bottom: 15px;">REGISTRO FOTOGRÁFICO DE LA VISITA AMBIENTAL</h2>
            
            <div style="text-align: center; font-size: 10px; color: #666; margin-bottom: 10px;">
                (Se adjuntan {{ count($imagenes) }} imágenes de evidencia)
            </div>
            
            @php
                $imagenesPorPagina = 2;
                $totalImagenes = count($imagenes);
            @endphp
            
            @for($i = 0; $i < $totalImagenes; $i += $imagenesPorPagina)
                @if($i > 0)
                    <div class="page-break"></div>
                    <div class="company-header">
                        <h3>Palmas Oleaginosas</h3>
                        <h4>BUCARELIA S.A.S</h4>
                        <p>Nit. 860.009.787-9</p>
                    </div>
                @endif
                
                <div style="display: flex; justify-content: center; gap: 15px; margin-bottom: 20px; page-break-inside: avoid;">
                    @for($j = $i; $j < min($i + $imagenesPorPagina, $totalImagenes); $j++)
                        <div style="text-align: center; margin: 0;">
                            @php
                                $ruta_completa = storage_path('app/public/' . str_replace('storage/', '', $imagenes[$j]));
                            @endphp
                            @if(file_exists($ruta_completa))
                                <img src="{{ $ruta_completa }}" class="evidence-image" alt="Foto {{ $j + 1 }}">
                            @else
                                <div style="width: 80mm; height: 60mm; background: #f5f5f5; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd; border-radius: 3px;">
                                    <span style="font-size: 10px; color: #999;">Imagen no disponible</span>
                                </div>
                            @endif
                            <div style="font-size: 9px; margin-top: 5px; color: #666; font-weight: bold;">
                                Evidencia {{ $j + 1 }}
                            </div>
                        </div>
                    @endfor
                </div>
            @endfor
        </div>
        @endif
        @else
        <div class="section">
            <h2>4. CIERRE DE VISITA</h2>
            <div class="intro-text">
                La visita ambiental aún no ha sido cerrada. No se cuenta con observaciones finales, recomendaciones o firmas.
            </div>
        </div>
        @endif
        
    </div> {{-- Cierre del .content --}}
    
    {{-- PAGINACIÓN --}}
    <div class="page-number">
        Página <span class="pagenum"></span>
    </div>
</body>
</html>
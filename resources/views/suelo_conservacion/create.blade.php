@extends('layouts.app')

@section('content')
<div class="container" style="background-color: whitesmoke; border-radius:30px;width:85%">

<style>
    body {
        background-image: url('{{ asset('images/fondo_ambiental.png') }}');
    }
    .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
        border-radius: 12px;
    }
    .title {
        text-align: center;
        font-family: Arial Black, sans-serif;
        font-weight: bold;
        font-size: 28px;
        color: #427a3b;
        text-shadow: -1px 0 #d3d1d1, 0 1px #000;
        margin-bottom: 22px;
    }
    .card-component {
        background: #f1f6f1;
        border: 1px solid #d5e6d5;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 18px;
    }
    .card-header-green {
        background: linear-gradient(45deg,#28a745,#20c997);
        color: white;
        padding: 12px 16px;
        border-radius: 8px 8px 0 0;
        margin: -18px -18px 12px -18px;
        font-weight: 700;
    }
    .btn-group-top {
        display:flex;
        gap:10px;
        margin-top:12px;
    }
    label {
        font-weight: 600;
        color: #2f4f2f;
    }
    
    /* ESTILOS PARA ACORDEONES AMBIENTALES */
    .accordion-ambiental .accordion-button {
        background-color: #2e7d32 !important;
        color: white !important;
        font-weight: bold;
        padding: 12px 20px;
    }
    .accordion-ambiental .accordion-button:not(.collapsed) {
        background-color: #1b5e20 !important;
        box-shadow: inset 0 -1px 0 rgba(0,0,0,.125);
    }
    .accordion-ambiental .accordion-body {
        background-color: #e8f5e9 !important;
        padding: 20px;
    }
    .componente-card {
        border: 2px solid #4caf50;
        border-radius: 8px;
        margin-bottom: 15px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .componente-header {
        background: #4caf50;
        color: white;
        padding: 12px 15px;
        border-radius: 6px 6px 0 0;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .componente-body {
        padding: 20px;
    }
    .info-item {
        padding: 8px 0;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: bold;
        color: #2e7d32;
        min-width: 200px;
        flex-shrink: 0;
    }
    .info-value {
        flex-grow: 1;
    }
    .badge-componente {
        font-size: 0.85em;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .btn-acordeon {
        font-size: 0.85em;
        padding: 4px 10px;
        margin-left: 5px;
    }
</style>

<h2 class="title">Conservación, Prevención de la Erosión y Ecología del Suelo</h2>

{{-- ===================== ACORDEÓN PARA AGUA CAPTACIÓN LEGAL ===================== --}}
@if($visita->aguaCaptacionLegal || $visita->aguaUsoEficiente)
<div class="accordion mb-4 accordion-ambiental" id="accordionAgua">
    
    {{-- ACORDEÓN 1: AGUA CAPTACIÓN LEGAL --}}
    @if($visita->aguaCaptacionLegal)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingCaptacionLegal">
            <button class="accordion-button" type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapseCaptacionLegal" 
                    aria-expanded="true" aria-controls="collapseCaptacionLegal">
                💧 Agua - Captación Legal Registrada
            </button>
        </h2>
        <div id="collapseCaptacionLegal" class="accordion-collapse collapse show" 
             aria-labelledby="headingCaptacionLegal" data-bs-parent="#accordionAgua">
            <div class="accordion-body">
                @php $captacion = $visita->aguaCaptacionLegal; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>💧 Agua - Captación Legal</span>
                        <small class="opacity-75">
                            Registrado: {{ $captacion->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Permiso/Concesión:</span>
                                    <span class="badge-componente bg-{{ $captacion->permiso_concesion ? 'success' : 'danger' }}">
                                        {{ $captacion->permiso_concesion ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Permiso Ocupación Cauce:</span>
                                    <span class="badge-componente bg-{{ $captacion->permiso_ocupacion_cauce ? 'success' : 'danger' }}">
                                        {{ $captacion->permiso_ocupacion_cauce ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Permisos Captación:</span>
                                    <span class="badge-componente bg-{{ $captacion->permisos_captacion ? 'success' : 'danger' }}">
                                        {{ $captacion->permisos_captacion ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Registro de Agua:</span>
                                    <span class="badge-componente bg-{{ $captacion->registro_agua ? 'success' : 'danger' }}">
                                        {{ $captacion->registro_agua ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Cumple Manejo/Construcción:</span>
                                    <span class="badge-componente bg-{{ $captacion->cumple_manejo_construccion ? 'success' : 'danger' }}">
                                        {{ $captacion->cumple_manejo_construccion ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Gestionó Permiso Ocupación:</span>
                                    <span class="badge-componente bg-{{ $captacion->gestion_permiso_ocupacion ? 'success' : 'danger' }}">
                                        {{ $captacion->gestion_permiso_ocupacion ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Gestionó Permiso Captación:</span>
                                    <span class="badge-componente bg-{{ $captacion->gestion_permiso_captacion ? 'success' : 'danger' }}">
                                        {{ $captacion->gestion_permiso_captacion ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($captacion->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $captacion->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                            <a href="{{ route('aguaCaptacion.edit', $visita->id) }}" 
                               class="btn btn-sm btn-warning me-2 btn-acordeon">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('aguaCaptacion.create', $visita->id) }}" 
                               class="btn btn-sm btn-info btn-acordeon">
                                <i class="fas fa-eye"></i> Ver completo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    {{-- ACORDEÓN 2: AGUA USO EFICIENTE --}}
    @if($visita->aguaUsoEficiente)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingUsoEficiente">
            <button class="accordion-button {{ $visita->aguaCaptacionLegal ? 'collapsed' : '' }}" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapseUsoEficiente" 
                    aria-expanded="{{ $visita->aguaCaptacionLegal ? 'false' : 'true' }}" 
                    aria-controls="collapseUsoEficiente">
                🚰 Agua - Uso Eficiente Registrada
            </button>
        </h2>
        <div id="collapseUsoEficiente" 
             class="accordion-collapse collapse {{ $visita->aguaCaptacionLegal ? '' : 'show' }}" 
             aria-labelledby="headingUsoEficiente" data-bs-parent="#accordionAgua">
            <div class="accordion-body">
                @php $usoEficiente = $visita->aguaUsoEficiente; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>🚰 Agua - Uso Eficiente</span>
                        <small class="opacity-75">
                            Registrado: {{ $usoEficiente->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Plan de Ahorro y Uso Eficiente:</span>
                                    <span class="badge-componente bg-{{ $usoEficiente->plan_ahorro ? 'success' : 'danger' }}">
                                        {{ $usoEficiente->plan_ahorro ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Mantenimiento Sistemas:</span>
                                    <span class="badge-componente bg-{{ $usoEficiente->mantenimiento_sistemas ? 'success' : 'danger' }}">
                                        {{ $usoEficiente->mantenimiento_sistemas ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Uso Información Técnica:</span>
                                    <span class="badge-componente bg-{{ $usoEficiente->uso_informacion_balance ? 'success' : 'danger' }}">
                                        {{ $usoEficiente->uso_informacion_balance ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Mecanismo de Medición:</span>
                                    <span class="badge-componente bg-{{ $usoEficiente->mecanismo_medicion ? 'success' : 'danger' }}">
                                        {{ $usoEficiente->mecanismo_medicion ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                @if($usoEficiente->consumo_agua)
                                <div class="info-item">
                                    <span class="info-label">Consumo de Agua:</span>
                                    <span class="info-value">
                                        {{ number_format($usoEficiente->consumo_agua, 2) }} m³/mes
                                    </span>
                                </div>
                                @endif
                                
                                @if($usoEficiente->metodo_medicion)
                                <div class="info-item">
                                    <span class="info-label">Método de Medición:</span>
                                    <span class="info-value">
                                        @switch($usoEficiente->metodo_medicion)
                                            @case('contador_agua') 📊 Contador de agua @break
                                            @case('medidor_volumen') ⚖️ Medidor de volumen @break
                                            @case('estimacion_manual') 📝 Estimación manual @break
                                            @case('sistema_automatico') 🤖 Sistema automático @break
                                            @case('lectura_mensual') 📅 Lectura mensual @break
                                            @default {{ $usoEficiente->metodo_medicion }}
                                        @endswitch
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if(!empty($usoEficiente->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $usoEficiente->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                            <a href="{{ route('aguaUso.edit', $visita->id) }}" 
                               class="btn btn-sm btn-warning me-2 btn-acordeon">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('agua_uso_eficiente.create', $visita->id) }}" 
                               class="btn btn-sm btn-info btn-acordeon">
                                <i class="fas fa-eye"></i> Ver completo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
</div>

{{-- RESUMEN SI FALTAN COMPONENTES --}}
@if(!$visita->aguaCaptacionLegal || !$visita->aguaUsoEficiente)
<div class="alert alert-info mb-4">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Componentes faltantes:</strong>
    <ul class="mb-0 mt-2">
        @if(!$visita->aguaCaptacionLegal)
        <li>
            💧 <strong>Agua - Captación Legal</strong> no registrado
            <a href="{{ route('aguaCaptacion.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                Registrar ahora
            </a>
        </li>
        @endif
        @if(!$visita->aguaUsoEficiente)
        <li>
            🚰 <strong>Agua - Uso Eficiente</strong> no registrado
            <a href="{{ route('agua_uso_eficiente.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                Registrar ahora
            </a>
        </li>
        @endif
    </ul>
</div>
@endif

@else
{{-- SI NO HAY NINGÚN COMPONENTE REGISTRADO --}}
<div class="alert alert-warning mb-4">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>No hay componentes de agua registrados:</strong>
    <p class="mb-0 mt-2">
        Aún no se ha registrado información para <strong>Agua - Captación Legal</strong> ni <strong>Agua - Uso Eficiente</strong>.
        Se recomienda registrar estos componentes antes de continuar.
    </p>
    <div class="mt-3">
        <a href="{{ route('aguaCaptacion.create', $visita->id) }}" class="btn btn-primary me-2">
            💧 Registrar Captación Legal
        </a>
        <a href="{{ route('agua_uso_eficiente.create', $visita->id) }}" class="btn btn-success">
            🚰 Registrar Uso Eficiente
        </a>
    </div>
</div>
@endif

{{-- ===================== SELECTOR DE SECCIONES ===================== --}}
<div class="card-component">
    <form action="{{ route('redireccion_componente_ambiental', $visita->id) }}" method="GET">
        <div class="mb-3">
            <label for="componente" class="form-label fw-bold text-success">
                <i class="fas fa-map-signs me-2"></i>Seleccione un componente:
            </label>

            <div class="input-group input-group-lg">
                <select id="componente" name="seccion" class="form-select" required
                        style="border-radius: 10px 0 0 10px; border: 2px solid #e9ecef;">
                    <option value="">Seleccione un componente</option>
                    <option value="agua_captacion_legal">💧 Agua - Captación Legal</option>
                    <option value="agua_uso_eficiente">🚰 Agua - Uso Eficiente</option>
                    <option value="suelo_conservacion">🌱 Suelo - Conservación</option>
                    <option value="energia">⚡ Energía</option>
                    <option value="gobernanza_hidrica">🤝 Gobernanza Hídrica</option>
                    <option value="emisiones_gei">🏭 Emisiones GEI</option>
                    <option value="residuos_manejo">🗑️ Residuos - Manejo</option>
                    <option value="sustancias_manejo">🧪 Sustancias - Manejo</option>
                    <option value="vertimientos_manejo">💦 Vertimientos - Manejo</option>
                    <option value="hmp_manejo">☣️ HMP - Manejo</option>
                    <option value="avc_control">🛡️ AVC - Control</option>
                    <option value="ecosistema_proteccion">🌳 Ecosistema - Protección</option>
                    <option value="avc_no_reemplazo">🚫 AVC - No Reemplazo 🌲 No Deforestación</option>
                </select>
                <button type="submit" class="btn btn-success"
                        style="border-radius: 0 10px 10px 0;">
                    <i class="fas fa-arrow-right me-2"></i>Ir
                </button>
            </div>
        </div>
    </form><br>
    
    {{-- CONTINÚA CON TU FORMULARIO ACTUAL DE SUELO CONSERVACIÓN --}}
    <div class="card-header-green">Formulario de Registro</div>

    <form action="{{ route('suelo_conservacion.store') }}" method="POST" id="form-suelo-conservacion">
    @csrf
    <input type="hidden" name="visita_ambiental_id" value="{{ $visita->id }}">
    
    <!-- Obtener el área total de la última visita a la misma plantación -->
    @php
use App\Models\Area;
use App\Models\Visita;

$areaTotalFinca = 0;
$mensajeDebug = "";

if ($visita->plantacion_id) {
    $mensajeDebug .= "✅ Plantación ID encontrado: {$visita->plantacion_id}<br>";
    
    // 1. Buscar la última visita a la misma plantación
    $ultimaVisita = Visita::where('plantacion_id', $visita->plantacion_id)
        ->latest('fecha')  // Si el campo se llama solo 'fecha'
        ->first();
    
    if ($ultimaVisita) {
        $mensajeDebug .= "✅ Última visita encontrada - ID: {$ultimaVisita->id}, Fecha: {$ultimaVisita->fecha}<br>";
        
        // 2. Buscar el área total de esa visita
        $area = Area::where('visita_id', $ultimaVisita->id)->first();
        
        if ($area) {
            $mensajeDebug .= "✅ Área encontrada para la visita<br>";
            
            // Listar todos los campos del área para debug
            $camposArea = $area->getAttributes();
            $mensajeDebug .= "Campos disponibles en el área:<br>";
            foreach ($camposArea as $campo => $valor) {
                if (!is_null($valor) && $valor !== '') {
                    $mensajeDebug .= "- {$campo}: {$valor}<br>";
                }
            }
            
            // Intentar obtener el área total con diferentes nombres de campo
            $posiblesCamposArea = [
                'area_total_finca_hectareas',
                'area_total_finca',
                'area_total', 
                'total_area',
                'area',
                'hectareas',
                'area_hectareas',
                'area_total_hectareas'
            ];
            
            foreach ($posiblesCamposArea as $campoPosible) {
                if (isset($area->$campoPosible) && !empty($area->$campoPosible)) {
                    $areaTotalFinca = $area->$campoPosible;
                    $mensajeDebug .= "✅ Usando campo '{$campoPosible}': {$areaTotalFinca}<br>";
                    break;
                }
            }
            
            if ($areaTotalFinca <= 0) {
                $mensajeDebug .= "⚠️ No se encontró campo de área total con valor<br>";
            }
            
        } else {
            $mensajeDebug .= "❌ NO se encontró área para esta visita<br>";
            
            // Verificar si hay alguna área para cualquier visita de esta plantación
            $todasAreas = Area::join('visitas', 'areas.visita_id', '=', 'visitas.id')
                ->where('visitas.plantacion_id', $visita->plantacion_id)
                ->get();
            
            $mensajeDebug .= "Total de áreas encontradas en esta plantación: " . $todasAreas->count() . "<br>";
        }
        
    } else {
        $mensajeDebug .= "❌ NO se encontraron visitas para esta plantación<br>";
    }
    
} else {
    $mensajeDebug .= "❌ La visita ambiental NO tiene plantación_id<br>";
}

// Mostrar mensaje de debug (opcional, puedes comentarlo después)

@endphp




<input type="hidden" id="area_total_finca" value="{{ $areaTotalFinca }}">
    
    <!-- Campo oculto para el área total -->
    <input type="hidden" id="area_total_finca" value="{{ $areaTotalFinca }}">
    
    <!-- Mostrar información del área en pantalla (opcional) -->
    <div class="alert alert-info mb-3" id="info-area-total" style="{{ $areaTotalFinca > 0 ? '' : 'display: none;' }}">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Información de área:</strong> 
        Área total de la finca: <span id="area-total-display">{{ number_format($areaTotalFinca, 2) }}</span> Ha
        @if($ultimaVisita ?? false)
            <br><small>Obtenido de la última visita a esta plantación ({{ $ultimaVisita->fecha }})</small>
        @endif
    </div>

    <div class="mb-3">
        <label for="usa_fuego_preparacion" class="form-label fw-bold">¿Ha usado fuego para preparar el terreno?</label>
        <select name="usa_fuego_preparacion" id="usa_fuego_preparacion" class="form-select">
            <option value="">Seleccione...</option>
            <option value="1" {{ old('usa_fuego_preparacion') == '1' ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ old('usa_fuego_preparacion') == '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="control_coberturas_invasoras" class="form-label fw-bold">¿Realiza control de coberturas invasoras?</label>
        <select name="control_coberturas_invasoras" id="control_coberturas_invasoras" class="form-select">
            <option value="">Seleccione...</option>
            <option value="1" {{ old('control_coberturas_invasoras') == '1' ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ old('control_coberturas_invasoras') == '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="siembra_coberturas" class="form-label fw-bold">¿Siembra coberturas para manejo del suelo?</label>
        <select name="siembra_coberturas" id="siembra_coberturas" class="form-select" onchange="mostrarAreaCobertura(this.value)">
            <option value="">Seleccione...</option>
            <option value="1" {{ old('siembra_coberturas') == '1' ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ old('siembra_coberturas') == '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <!-- Campos adicionales que aparecen solo si selecciona "Sí" -->
    <div id="area-cobertura-container" style="display: {{ old('siembra_coberturas') == '1' ? 'block' : 'none' }};">
        <div class="card bg-light p-3 mb-3">
            <h6 class="fw-bold mb-3">🌿 Detalles de cobertura sembrada</h6>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="area_cobertura" class="form-label fw-bold">
                        <i class="fas fa-ruler-combined me-1"></i> Área con cobertura sembrada (hectáreas)
                    </label>
                    <div class="input-group">
                        <input type="number" 
                               name="area_cobertura" 
                               id="area_cobertura" 
                               class="form-control" 
                               value="{{ old('area_cobertura') }}"
                               step="0.01" 
                               min="0"
                               oninput="calcularPorcentajeCobertura()"
                               placeholder="Ej: 5.25">
                        <span class="input-group-text">Ha</span>
                    </div>
                    <small class="text-muted">Área total con cobertura vegetal sembrada</small>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tipo_cobertura" class="form-label fw-bold">
                        <i class="fas fa-leaf me-1"></i> Tipo de cobertura
                    </label>
                    <select name="tipo_cobertura" id="tipo_cobertura" class="form-select">
                        <option value="">Seleccione tipo...</option>
                        <option value="leguminosas" {{ old('tipo_cobertura') == 'leguminosas' ? 'selected' : '' }}>Leguminosas (kudzu, canavalia, etc.)</option>
                        <option value="gramineas" {{ old('tipo_cobertura') == 'gramineas' ? 'selected' : '' }}>Gramíneas (brachiaria, pennisetum, etc.)</option>
                        <option value="mixta" {{ old('tipo_cobertura') == 'mixta' ? 'selected' : '' }}>Mezcla de especies</option>
                        <option value="natural" {{ old('tipo_cobertura') == 'natural' ? 'selected' : '' }}>Cobertura natural</option>
                        <option value="otro" {{ old('tipo_cobertura') == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
            </div>

            <!-- Campo para "otro" tipo de cobertura -->
            <div id="otro-tipo-cobertura-container" style="display: {{ old('tipo_cobertura') == 'otro' ? 'block' : 'none' }};">
                <div class="mb-3">
                    <label for="otro_tipo_cobertura" class="form-label fw-bold">
                        <i class="fas fa-edit me-1"></i> Especifique otro tipo de cobertura
                    </label>
                    <input type="text" 
                           name="otro_tipo_cobertura" 
                           id="otro_tipo_cobertura" 
                           class="form-control" 
                           value="{{ old('otro_tipo_cobertura') }}"
                           placeholder="Describa el tipo de cobertura sembrada">
                </div>
            </div>

            <!-- Campo oculto para guardar el resultado de la fórmula -->
            <input type="hidden" name="porcentaje_cobertura" id="porcentaje_cobertura_hidden" value="">

            <!-- Mostrar el resultado de la fórmula -->
            <div class="alert alert-secondary" id="resultado-formula" style="display: none;">
                <i class="fas fa-calculator me-2"></i>
                <strong>Fórmula aplicada:</strong> 
                (Área cobertura / Área total finca) × 100
                <br>
                <strong>Resultado:</strong> 
                <span id="area-cobertura-display">0</span> Ha ÷ 
                <span id="area-total-formula">{{ number_format($areaTotalFinca, 2) }}</span> Ha × 100 = 
                <span id="porcentaje-cobertura-resultado" class="fw-bold">0%</span>
                <br>
                <small class="text-muted">Este porcentaje se guardará automáticamente</small>
            </div>

            <!-- Validación si no hay área total -->
            <div class="alert alert-warning" id="alerta-sin-area" style="{{ $areaTotalFinca <= 0 ? '' : 'display: none;' }}">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Atención:</strong> No se encontró información del área total de la finca. 
                La fórmula de porcentaje no se podrá calcular.
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="sigue_recomendaciones_comerciales" class="form-label fw-bold">¿Sigue recomendaciones de casas comerciales?</label>
        <select name="sigue_recomendaciones_comerciales" id="sigue_recomendaciones_comerciales" class="form-select">
            <option value="">Seleccione...</option>
            <option value="1" {{ old('sigue_recomendaciones_comerciales') == '1' ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ old('sigue_recomendaciones_comerciales') == '0' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary">Volver a la visita</a>
        <button type="submit" class="btn btn-success">Guardar componente</button>
    </div>
</form>

<script>
function mostrarAreaCobertura(valor) {
    const container = document.getElementById('area-cobertura-container');
    const areaTotalFinca = parseFloat(document.getElementById('area_total_finca').value) || 0;
    
    if (valor === '1') {
        container.style.display = 'block';
        
        if (areaTotalFinca > 0) {
            document.getElementById('area_cobertura').required = true;
            calcularPorcentajeCobertura();
        } else {
            document.getElementById('alerta-sin-area').style.display = 'block';
        }
        
        document.getElementById('tipo_cobertura').required = true;
    } else {
        container.style.display = 'none';
        document.getElementById('area_cobertura').required = false;
        document.getElementById('tipo_cobertura').required = false;
        document.getElementById('area_cobertura').value = '';
        document.getElementById('tipo_cobertura').value = '';
        document.getElementById('otro_tipo_cobertura').value = '';
        document.getElementById('resultado-formula').style.display = 'none';
        document.getElementById('porcentaje_cobertura_hidden').value = '';
    }
}

function calcularPorcentajeCobertura() {
    const areaCobertura = parseFloat(document.getElementById('area_cobertura').value) || 0;
    const areaTotalFinca = parseFloat(document.getElementById('area_total_finca').value) || 0;
    
    const resultadoDiv = document.getElementById('resultado-formula');
    const areaCoberturaDisplay = document.getElementById('area-cobertura-display');
    const areaTotalDisplay = document.getElementById('area-total-formula');
    const porcentajeResultado = document.getElementById('porcentaje-cobertura-resultado');
    const porcentajeHidden = document.getElementById('porcentaje_cobertura_hidden');
    
    if (areaTotalFinca > 0 && areaCobertura > 0) {
        const porcentaje = (areaCobertura / areaTotalFinca) * 100;
        
        // Mostrar valores formateados
        areaCoberturaDisplay.textContent = areaCobertura.toFixed(2);
        areaTotalDisplay.textContent = areaTotalFinca.toFixed(2);
        porcentajeResultado.textContent = porcentaje.toFixed(2) + '%';
        
        // Guardar en campo oculto
        porcentajeHidden.value = porcentaje.toFixed(2);
        
        // Cambiar color según porcentaje
        if (porcentaje >= 70) {
            resultadoDiv.className = 'alert alert-success';
        } else if (porcentaje >= 30) {
            resultadoDiv.className = 'alert alert-warning';
        } else {
            resultadoDiv.className = 'alert alert-danger';
        }
        
        resultadoDiv.style.display = 'block';
        document.getElementById('alerta-sin-area').style.display = 'none';
    } else if (areaTotalFinca <= 0) {
        resultadoDiv.style.display = 'none';
        document.getElementById('alerta-sin-area').style.display = 'block';
        porcentajeHidden.value = '';
    } else {
        resultadoDiv.style.display = 'none';
        porcentajeHidden.value = '';
    }
}

// Mostrar/ocultar campo "otro tipo de cobertura"
document.addEventListener('DOMContentLoaded', function() {
    const tipoCoberturaSelect = document.getElementById('tipo_cobertura');
    const otroTipoContainer = document.getElementById('otro-tipo-cobertura-container');
    
    if (tipoCoberturaSelect) {
        tipoCoberturaSelect.addEventListener('change', function() {
            if (this.value === 'otro') {
                otroTipoContainer.style.display = 'block';
                document.getElementById('otro_tipo_cobertura').required = true;
            } else {
                otroTipoContainer.style.display = 'none';
                document.getElementById('otro_tipo_cobertura').required = false;
            }
        });
    }
    
    // Inicializar si ya hay valores en old()
    const siembraCoberturas = document.getElementById('siembra_coberturas');
    if (siembraCoberturas) {
        mostrarAreaCobertura(siembraCoberturas.value);
        
        // Si hay un valor old() para tipo_cobertura, mostrar el campo "otro" si aplica
        const tipoCoberturaValue = "{{ old('tipo_cobertura') }}";
        if (tipoCoberturaValue === 'otro' && otroTipoContainer) {
            otroTipoContainer.style.display = 'block';
        }
        
        // Calcular porcentaje inicial si hay valores
        if (siembraCoberturas.value === '1') {
            setTimeout(calcularPorcentajeCobertura, 100);
        }
    }
    
    // Calcular cuando se escribe en el área
    const areaCoberturaInput = document.getElementById('area_cobertura');
    if (areaCoberturaInput) {
        areaCoberturaInput.addEventListener('input', calcularPorcentajeCobertura);
    }
});

// Validación antes de enviar
document.getElementById('form-suelo-conservacion').addEventListener('submit', function(e) {
    const siembraCoberturas = document.getElementById('siembra_coberturas').value;
    const areaTotalFinca = parseFloat(document.getElementById('area_total_finca').value) || 0;
    
    if (siembraCoberturas === '1') {
        const areaCobertura = document.getElementById('area_cobertura').value;
        const tipoCobertura = document.getElementById('tipo_cobertura').value;
        
        if (!areaCobertura.trim()) {
            e.preventDefault();
            alert('Por favor ingrese el área de cobertura sembrada');
            document.getElementById('area_cobertura').focus();
            return false;
        }
        
        if (!tipoCobertura) {
            e.preventDefault();
            alert('Por favor seleccione el tipo de cobertura');
            document.getElementById('tipo_cobertura').focus();
            return false;
        }
        
        if (tipoCobertura === 'otro') {
            const otroTipo = document.getElementById('otro_tipo_cobertura').value;
            if (!otroTipo.trim()) {
                e.preventDefault();
                alert('Por favor describa el tipo de cobertura');
                document.getElementById('otro_tipo_cobertura').focus();
                return false;
            }
        }
        
        // Validar que haya área total para calcular la fórmula
        if (areaTotalFinca <= 0) {
            if (!confirm('⚠️ No se encontró información del área total de la finca. ¿Desea continuar sin calcular el porcentaje de cobertura?')) {
                e.preventDefault();
                return false;
            }
        }
    }
});
</script>
</div>

</div>
@endsection

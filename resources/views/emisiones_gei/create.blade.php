@extends('layouts.app')

@section('content')
<div class="container offline-form-container" style="background-color: whitesmoke; border-radius:30px; width: 85%">
<style>
     body{
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
    .card-component { background:#f1f6f1; border-radius:10px; padding:18px; border:1px solid #d5e6d5; }
    .card-header-green {
        background: linear-gradient(45deg,#28a745,#20c997);
        color:white; padding:12px; border-radius:8px 8px 0 0; margin:-18px -18px 12px -18px; font-weight:700;
    }
</style>

    <h2 class="title">Emisiones Atmosféricas y GEI – Crear Registro</h2>
    
    {{-- ===================== ACORDEÓN PARA COMPONENTES ===================== --}}
@if($visita->aguaCaptacionLegal || $visita->aguaUsoEficiente || $visita->sueloConservacion || $visita->energiaManejo || $visita->gobernanzaHidrica)
<div class="accordion mb-4 accordion-ambiental" id="accordionAmbiental">
    
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
            aria-labelledby="headingCaptacionLegal" data-bs-parent="#accordionAmbiental">
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
            aria-labelledby="headingUsoEficiente" data-bs-parent="#accordionAmbiental">
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
    
    {{-- ACORDEÓN 3: SUELO CONSERVACIÓN --}}
    @if($visita->sueloConservacion)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSueloConservacion">
            <button class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapseSueloConservacion" 
                    aria-expanded="false" 
                    aria-controls="collapseSueloConservacion">
                🌱 Suelo - Conservación Registrada
            </button>
        </h2>
        <div id="collapseSueloConservacion" 
            class="accordion-collapse collapse" 
            aria-labelledby="headingSueloConservacion" data-bs-parent="#accordionAmbiental">
            <div class="accordion-body">
                @php $suelo = $visita->sueloConservacion; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>🌱 Suelo - Conservación</span>
                        <small class="opacity-75">
                            Registrado: {{ $suelo->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Uso de Fuego Preparación:</span>
                                    <span class="badge-componente bg-{{ $suelo->uso_fuego_preparacion ? 'danger' : 'success' }}">
                                        {{ $suelo->uso_fuego_preparacion ? '❌ Sí' : '✅ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Control Coberturas Invasoras:</span>
                                    <span class="badge-componente bg-{{ $suelo->control_coberturas_invasoras ? 'success' : 'danger' }}">
                                        {{ $suelo->control_coberturas_invasoras ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Siembra Coberturas:</span>
                                    <span class="badge-componente bg-{{ $suelo->siembra_coberturas ? 'success' : 'danger' }}">
                                        {{ $suelo->siembra_coberturas ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Sigue Recomendaciones Comerciales:</span>
                                    <span class="badge-componente bg-{{ $suelo->sigue_recomendaciones_comerciales ? 'success' : 'danger' }}">
                                        {{ $suelo->sigue_recomendaciones_comerciales ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                @if($suelo->siembra_coberturas)
                                    @if($suelo->area_cobertura)
                                    <div class="info-item">
                                        <span class="info-label">Área Cobertura:</span>
                                        <span class="info-value">
                                            {{ number_format($suelo->area_cobertura, 2) }} Ha
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($suelo->tipo_cobertura)
                                    <div class="info-item">
                                        <span class="info-label">Tipo de Cobertura:</span>
                                        <span class="info-value">
                                            @switch($suelo->tipo_cobertura)
                                                @case('leguminosas') Leguminosas (kudzu, canavalia, etc.) @break
                                                @case('gramineas') Gramíneas (brachiaria, pennisetum, etc.) @break
                                                @case('mixta') Mezcla de especies @break
                                                @case('natural') Cobertura natural @break
                                                @default {{ $suelo->tipo_cobertura ?? 'N/A' }}
                                            @endswitch
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($suelo->porcentaje_cobertura)
                                    <div class="info-item">
                                        <span class="info-label">Porcentaje Cobertura:</span>
                                        <span class="info-value">
                                            {{ number_format($suelo->porcentaje_cobertura, 1) }}%
                                        </span>
                                    </div>
                                    @endif
                                @else
                                    <div class="info-item">
                                        <span class="info-label">Cobertura Sembrada:</span>
                                        <span class="info-value text-muted">
                                            No aplica (No siembra coberturas)
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        @if(!empty($suelo->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $suelo->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                            <a href="{{ route('suelo_conservacion.edit', $visita->id) }}" 
                            class="btn btn-sm btn-warning me-2 btn-acordeon">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('suelo_conservacion.create', $visita->id) }}" 
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
    
    {{-- ACORDEÓN 4: ENERGÍA MANEJO --}}
    @if($visita->energiaManejo)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingEnergiaManejo">
            <button class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapseEnergiaManejo" 
                    aria-expanded="false" 
                    aria-controls="collapseEnergiaManejo">
                ⚡ Energía - Manejo Registrado
            </button>
        </h2>
        <div id="collapseEnergiaManejo" 
            class="accordion-collapse collapse" 
            aria-labelledby="headingEnergiaManejo" data-bs-parent="#accordionAmbiental">
            <div class="accordion-body">
                @php $energia = $visita->energiaManejo; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>⚡ Energía - Manejo</span>
                        <small class="opacity-75">
                            Registrado: {{ $energia->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Registro Consumo Combustible:</span>
                                    <span class="badge-componente bg-{{ $energia->registro_consumo_combustible ? 'success' : 'danger' }}">
                                        {{ $energia->registro_consumo_combustible ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Plan Uso Eficiente:</span>
                                    <span class="badge-componente bg-{{ $energia->plan_uso_eficiente ? 'success' : 'danger' }}">
                                        {{ $energia->plan_uso_eficiente ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                @if($energia->consumo_energia_kwh)
                                <div class="info-item">
                                    <span class="info-label">Consumo Energía:</span>
                                    <span class="info-value">
                                        {{ number_format($energia->consumo_energia_kwh, 2) }} kWh/mes
                                    </span>
                                </div>
                                @endif
                                
                                <div class="info-item">
                                    <span class="info-label">Seguimiento Indicadores:</span>
                                    <span class="badge-componente bg-{{ $energia->seguimiento_indicadores ? 'success' : 'danger' }}">
                                        {{ $energia->seguimiento_indicadores ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($energia->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $energia->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                            <a href="{{ route('energia.create', $visita->id) }}" 
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
    
    {{-- ACORDEÓN 5: GOBERNANZA HIDRICA (NUEVO) --}}
    @if($visita->gobernanzaHidrica)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingGobernanzaHidrica">
            <button class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapseGobernanzaHidrica" 
                    aria-expanded="false" 
                    aria-controls="collapseGobernanzaHidrica">
                🤝 Gobernanza Hídrica Registrada
            </button>
        </h2>
        <div id="collapseGobernanzaHidrica" 
            class="accordion-collapse collapse" 
            aria-labelledby="headingGobernanzaHidrica" data-bs-parent="#accordionAmbiental">
            <div class="accordion-body">
                @php $gobernanza = $visita->gobernanzaHidrica; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>🤝 Gobernanza Hídrica</span>
                        <small class="opacity-75">
                            Registrado: {{ $gobernanza->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Canales de Comunicación:</span>
                                    <span class="badge-componente bg-{{ $gobernanza->canales_comunicacion ? 'success' : 'danger' }}">
                                        {{ $gobernanza->canales_comunicacion ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Identifica Actores Afectados:</span>
                                    <span class="badge-componente bg-{{ $gobernanza->identifica_actores_afectados ? 'success' : 'danger' }}">
                                        {{ $gobernanza->identifica_actores_afectados ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Participa en Actividades Gestión:</span>
                                    <span class="badge-componente bg-{{ $gobernanza->participa_actividades_gestion ? 'success' : 'danger' }}">
                                        {{ $gobernanza->participa_actividades_gestion ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($gobernanza->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $gobernanza->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                            
                            <a href="{{ route('gobernanza.create', $visita->id) }}" 
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
@if(!$visita->aguaCaptacionLegal || !$visita->aguaUsoEficiente || !$visita->sueloConservacion || !$visita->energiaManejo || !$visita->gobernanzaHidrica)
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
        @if(!$visita->sueloConservacion)
        <li>
            🌱 <strong>Suelo - Conservación</strong> no registrado
            <a href="{{ route('suelo_conservacion.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                Registrar ahora
            </a>
        </li>
        @endif
        @if(!$visita->energiaManejo)
        <li>
            ⚡ <strong>Energía - Manejo</strong> no registrado
            <a href="{{ route('energia.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                Registrar ahora
            </a>
        </li>
        @endif
        @if(!$visita->gobernanzaHidrica)
        <li>
            🤝 <strong>Gobernanza Hídrica</strong> no registrado
            <a href="{{ route('gobernanza.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
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
    <strong>No hay componentes registrados:</strong>
    <p class="mb-0 mt-2">
        Aún no se ha registrado información para los componentes anteriores.
        Se recomienda registrar en orden.
    </p>
    <div class="mt-3">
        <a href="{{ route('aguaCaptacion.create', $visita->id) }}" class="btn btn-primary me-2">
            💧 Registrar Captación Legal
        </a>
        <a href="{{ route('agua_uso_eficiente.create', $visita->id) }}" class="btn btn-success me-2">
            🚰 Registrar Uso Eficiente
        </a>
        <a href="{{ route('suelo_conservacion.create', $visita->id) }}" class="btn btn-warning me-2">
            🌱 Registrar Suelo Conservación
        </a>
        <a href="{{ route('energia.create', $visita->id) }}" class="btn btn-info me-2">
            ⚡ Registrar Energía Manejo
        </a>
        <a href="{{ route('gobernanza.create', $visita->id) }}" class="btn btn-secondary">
            🤝 Registrar Gobernanza Hídrica
        </a>
    </div>
</div>
@endif
    <form action="{{ route('redireccion_componente_ambiental', $visitaId) }}" method="GET">

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

    <form action="{{ route('emisionesGei.store') }}" method="POST">
    @csrf

    <input type="hidden" name="visita_ambiental_id" value="{{ $visitaId }}">

    <div class="card-component">
        <div class="card-header-green">Evaluación GEI</div>

        {{-- Cuantifica emisiones --}}
        <div class="mb-3">
            <label>
                ¿Cuantifica las emisiones de GEI mediante inventarios y/o calculadoras de huella de carbono?
            </label>
            <select name="cuantifica_emisiones" id="cuantifica_emisiones" class="form-control" required>
                <option value="">Seleccione…</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        {{-- Inputs que aparecen solo si es SI --}}
        <div id="inputs-gei" style="display:none;">

            <div class="mb-3">
                <label>Consumo de combustible</label>
                <input type="number" step="0.01" name="combustible" id="combustible"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Distancia recorrida</label>
                <input type="number" step="0.01" name="distancia" id="distancia"
                       class="form-control">
            </div>

            <div class="mb-3">
                <label>Huella de carbono (combustible / distancia)</label>
                <input type="number" step="0.0001" name="huella_carbono" id="huella_carbono"
                       class="form-control" readonly>
            </div>

        </div>

        {{-- Implementa acciones --}}
        <div class="mb-3">
            <label>¿Implementa acciones para la reducción de GEI?</label>
            <select name="implementa_acciones_reduccion" class="form-control"  id="implementa_acciones_reduccion" required>
                <option value="">Seleccione…</option>
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="mb-3" id="acciones-reduccion-container" style="display:none;">
            <label>Especifique las acciones implementadas</label>
            <textarea name="acciones_reduccion"
                    class="form-control"
                    rows="3"
                    placeholder="Describa las acciones realizadas para la reducción de GEI"></textarea>
        </div>

        {{-- Observaciones --}}
        <div class="mb-3">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control"></textarea>
        </div>
    </div>

    <button class="btn btn-primary">Guardar</button>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const selectCuantifica = document.getElementById('cuantifica_emisiones');
    const inputsGei = document.getElementById('inputs-gei');
    const combustibleInput = document.getElementById('combustible');
    const distanciaInput = document.getElementById('distancia');
    const huellaInput = document.getElementById('huella_carbono');

    function calcularHuella() {
        const combustible = parseFloat(combustibleInput.value);
        const distancia = parseFloat(distanciaInput.value);

        if (combustible > 0 && distancia > 0) {
            huellaInput.value = (combustible / distancia).toFixed(4);
        } else {
            huellaInput.value = '';
        }
    }

    selectCuantifica.addEventListener('change', function () {
        if (this.value === '1') {
            inputsGei.style.display = 'block';
        } else {
            inputsGei.style.display = 'none';
            combustibleInput.value = '';
            distanciaInput.value = '';
            huellaInput.value = '';
        }
    });

    combustibleInput.addEventListener('input', calcularHuella);
    distanciaInput.addEventListener('input', calcularHuella);

});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const selectAcciones = document.getElementById('implementa_acciones_reduccion');
    const accionesContainer = document.getElementById('acciones-reduccion-container');
    const accionesTextarea = accionesContainer.querySelector('textarea');

    selectAcciones.addEventListener('change', function () {
        if (this.value === '1') {
            accionesContainer.style.display = 'block';
        } else {
            accionesContainer.style.display = 'none';
            accionesTextarea.value = '';
        }
    });

});
</script>

    <br><br>

    <a href="{{ route('visitasAmbientales.show', $visitaId) }}" class="btn btn-secondary">
        ⬅️ Volver a la visita
    </a>

</div>
@endsection

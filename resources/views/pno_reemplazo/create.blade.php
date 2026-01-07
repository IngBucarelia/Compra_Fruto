@extends('layouts.app')

@section('content')
@php
use App\Services\AreaService;
$areaTotalFinca = AreaService::obtenerAreaTotalFinca($visita);
@endphp

<div class="container offline-form-container" style="border-radius:20px; margin-top:15px;width: 85%">
<style>
    body {
        background-image: url('{{ asset('images/fondo_ambiental.png') }}');
    }
    .offline-form-container {
        background-color: rgba(129, 165, 114, 0.92);
        padding: 25px;
        border-radius: 15px;
    }
    .title {
        text-align: center;
        color: #fdffe5;
        font-size: 26px;
        font-weight: bold;
        text-shadow: -1px 0 #000, 0 1px #000;
        margin-bottom: 18px;
    }
    .card-component {
        background: #f1f6f1;
        padding: 18px;
        border-radius: 10px;
    }
    .restauracion-card {
        background: #e8f5e9;
        border-left: 4px solid #2e7d32;
        margin-top: 15px;
    }
</style>

<h2 class="title">Planificación Ambiental – No Reemplazo y No Deforestación</h2>
{{-- ===================== ACORDEÓN PARA COMPONENTES ===================== --}}
@if($visita->aguaCaptacionLegal || $visita->aguaUsoEficiente || $visita->sueloConservacion || $visita->energiaManejo || $visita->gobernanzaHidrica || $visita->emisionesGei || $visita->residuosManejo || $visita->sustanciasQuimicasBiologicas || $visita->vertimientoManejo || $visita->plantacionHmp || $visita->plantacionAvc || $visita->plantacionEcosistema)
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
    
    {{-- ACORDEÓN 5: GOBERNANZA HIDRICA --}}
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
    
    {{-- ACORDEÓN 6: EMISIONES GEI --}}
    @if($visita->emisionesGei)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingEmisionesGei">
            <button class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapseEmisionesGei" 
                    aria-expanded="false" 
                    aria-controls="collapseEmisionesGei">
                🌍 Emisiones GEI Registradas
            </button>
        </h2>
        <div id="collapseEmisionesGei" 
            class="accordion-collapse collapse" 
            aria-labelledby="headingEmisionesGei" data-bs-parent="#accordionAmbiental">
            <div class="accordion-body">
                @php $emisiones = $visita->emisionesGei; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>🌍 Emisiones GEI</span>
                        <small class="opacity-75">
                            Registrado: {{ $emisiones->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Cuantifica Emisiones:</span>
                                    <span class="badge-componente bg-{{ $emisiones->cuantifica_emisiones ? 'success' : 'danger' }}">
                                        {{ $emisiones->cuantifica_emisiones ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                @if($emisiones->cuantifica_emisiones)
                                    @if($emisiones->combustible)
                                    <div class="info-item">
                                        <span class="info-label">Combustible:</span>
                                        <span class="info-value">
                                            {{ $emisiones->combustible }}
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($emisiones->distancia)
                                    <div class="info-item">
                                        <span class="info-label">Distancia:</span>
                                        <span class="info-value">
                                            {{ number_format($emisiones->distancia, 2) }} km
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($emisiones->huella_carbono)
                                    <div class="info-item">
                                        <span class="info-label">Huella de Carbono:</span>
                                        <span class="info-value">
                                            {{ number_format($emisiones->huella_carbono, 2) }} tCO₂e
                                        </span>
                                    </div>
                                    @endif
                                @endif
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Implementa Acciones Reducción:</span>
                                    <span class="badge-componente bg-{{ $emisiones->implementa_acciones_reduccion ? 'success' : 'danger' }}">
                                        {{ $emisiones->implementa_acciones_reduccion ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                @if($emisiones->implementa_acciones_reduccion && !empty($emisiones->acciones_reduccion))
                                <div class="info-item">
                                    <span class="info-label">Acciones de Reducción:</span>
                                    <span class="info-value">
                                        {{ $emisiones->acciones_reduccion }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if(!empty($emisiones->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $emisiones->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                           
                            <a href="{{ route('emisiones_gei.create', $visita->id) }}" 
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
    
    {{-- ACORDEÓN 7: RESIDUOS MANEJO --}}
    @if($visita->residuosManejo)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingResiduosManejo">
            <button class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapseResiduosManejo" 
                    aria-expanded="false" 
                    aria-controls="collapseResiduosManejo">
                🗑️ Residuos - Manejo Registrado
            </button>
        </h2>
        <div id="collapseResiduosManejo" 
            class="accordion-collapse collapse" 
            aria-labelledby="headingResiduosManejo" data-bs-parent="#accordionAmbiental">
            <div class="accordion-body">
                @php $residuos = $visita->residuosManejo; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>🗑️ Residuos - Manejo</span>
                        <small class="opacity-75">
                            Registrado: {{ $residuos->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Capacita Personal:</span>
                                    <span class="badge-componente bg-{{ $residuos->capacita_personal ? 'success' : 'danger' }}">
                                        {{ $residuos->capacita_personal ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                @if($residuos->capacita_personal)
                                    @if($residuos->personas_manipulan)
                                    <div class="info-item">
                                        <span class="info-label">Personas que Manipulan:</span>
                                        <span class="info-value">
                                            {{ $residuos->personas_manipulan }} personas
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($residuos->personas_capacitadas)
                                    <div class="info-item">
                                        <span class="info-label">Personas Capacitadas:</span>
                                        <span class="info-value">
                                            {{ $residuos->personas_capacitadas }} personas
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($residuos->porcentaje_capacitadas)
                                    <div class="info-item">
                                        <span class="info-label">Porcentaje Capacitadas:</span>
                                        <span class="info-value">
                                            {{ number_format($residuos->porcentaje_capacitadas, 1) }}%
                                        </span>
                                    </div>
                                    @endif
                                @endif
                                
                                <div class="info-item">
                                    <span class="info-label">Conoce Diferencias Residuos:</span>
                                    <span class="badge-componente bg-{{ $residuos->conoce_diferencias ? 'success' : 'danger' }}">
                                        {{ $residuos->conoce_diferencias ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Certificado Final Respel:</span>
                                    <span class="badge-componente bg-{{ $residuos->certificado_final_respel ? 'success' : 'danger' }}">
                                        {{ $residuos->certificado_final_respel ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Manifiesto Transporte Respel:</span>
                                    <span class="badge-componente bg-{{ $residuos->manifiesto_transporte_respel ? 'success' : 'danger' }}">
                                        {{ $residuos->manifiesto_transporte_respel ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Puntos Ecológicos:</span>
                                    <span class="badge-componente bg-{{ $residuos->puntos_ecologicos ? 'success' : 'danger' }}">
                                        {{ $residuos->puntos_ecologicos ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Entrega a Transportador AUT:</span>
                                    <span class="badge-componente bg-{{ $residuos->entrega_transportador_aut ? 'success' : 'danger' }}">
                                        {{ $residuos->entrega_transportador_aut ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Disposición Empresa AUT:</span>
                                    <span class="badge-componente bg-{{ $residuos->disposicion_empresa_aut ? 'success' : 'danger' }}">
                                        {{ $residuos->disposicion_empresa_aut ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Acciones Minimizar Impacto:</span>
                                    <span class="badge-componente bg-{{ $residuos->acciones_minimizar_impacto ? 'success' : 'danger' }}">
                                        {{ $residuos->acciones_minimizar_impacto ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Certificado Relleno Sanitario:</span>
                                    <span class="badge-componente bg-{{ $residuos->certificado_relleno_sanitario ? 'success' : 'danger' }}">
                                        {{ $residuos->certificado_relleno_sanitario ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Residuos Aprovechables Gestión:</span>
                                    <span class="badge-componente bg-{{ $residuos->residuos_aprovechables_gestion ? 'success' : 'danger' }}">
                                        {{ $residuos->residuos_aprovechables_gestion ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Pesa y Registra:</span>
                                    <span class="badge-componente bg-{{ $residuos->pesa_y_registra ? 'success' : 'danger' }}">
                                        {{ $residuos->pesa_y_registra ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($residuos->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $residuos->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                            
                            <a href="{{ route('residuos.create', $visita->id) }}" 
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
    
    {{-- ACORDEÓN 8: SUSTANCIAS QUIMICAS BIOLOGICAS --}}
    @if($visita->sustanciasQuimicasBiologicas)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingSustanciasQuimicasBiologicas">
            <button class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapseSustanciasQuimicasBiologicas" 
                    aria-expanded="false" 
                    aria-controls="collapseSustanciasQuimicasBiologicas">
                ⚗️ Sustancias Químicas/Biológicas Registradas
            </button>
        </h2>
        <div id="collapseSustanciasQuimicasBiologicas" 
            class="accordion-collapse collapse" 
            aria-labelledby="headingSustanciasQuimicasBiologicas" data-bs-parent="#accordionAmbiental">
            <div class="accordion-body">
                @php $sustancias = $visita->sustanciasQuimicasBiologicas; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>⚗️ Sustancias Químicas/Biológicas</span>
                        <small class="opacity-75">
                            Registrado: {{ $sustancias->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Cuenta con POES:</span>
                                    <span class="badge-componente bg-{{ $sustancias->cuenta_poes ? 'success' : 'danger' }}">
                                        {{ $sustancias->cuenta_poes ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Personal Capacitado:</span>
                                    <span class="badge-componente bg-{{ $sustancias->personal_capacitado ? 'success' : 'danger' }}">
                                        {{ $sustancias->personal_capacitado ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Almacenamiento Adecuado:</span>
                                    <span class="badge-componente bg-{{ $sustancias->almacenamiento_adecuado ? 'success' : 'danger' }}">
                                        {{ $sustancias->almacenamiento_adecuado ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                @if($sustancias->imagen_poes)
                                <div class="info-item">
                                    <span class="info-label">Imagen POES:</span>
                                    <span class="info-value">
                                        <a href="{{ Storage::url($sustancias->imagen_poes) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Ver Imagen
                                        </a>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if(!empty($sustancias->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $sustancias->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                            
                            <a href="{{ route('sustancias.create', $visita->id) }}" 
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
    
    {{-- ACORDEÓN 9: VERTIMIENTO MANEJO --}}
    @if($visita->vertimientoManejo)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingVertimientoManejo">
            <button class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapseVertimientoManejo" 
                    aria-expanded="false" 
                    aria-controls="collapseVertimientoManejo">
                💦 Vertimiento - Manejo Registrado
            </button>
        </h2>
        <div id="collapseVertimientoManejo" 
            class="accordion-collapse collapse" 
            aria-labelledby="headingVertimientoManejo" data-bs-parent="#accordionAmbiental">
            <div class="accordion-body">
                @php $vertimiento = $visita->vertimientoManejo; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>💦 Vertimiento - Manejo</span>
                        <small class="opacity-75">
                            Registrado: {{ $vertimiento->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Permiso de Vertimientos:</span>
                                    <span class="badge-componente bg-{{ $vertimiento->permiso_vertimiento ? 'success' : 'danger' }}">
                                        {{ $vertimiento->permiso_vertimiento ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                @if($vertimiento->permiso_vertimiento)
                                    @if($vertimiento->numero_vertimientos_permitidos !== null)
                                    <div class="info-item">
                                        <span class="info-label">Vertimientos Permitidos:</span>
                                        <span class="info-value">
                                            {{ $vertimiento->numero_vertimientos_permitidos }}
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($vertimiento->numero_vertimientos_totales !== null)
                                    <div class="info-item">
                                        <span class="info-label">Vertimientos Totales:</span>
                                        <span class="info-value">
                                            {{ $vertimiento->numero_vertimientos_totales }}
                                        </span>
                                    </div>
                                    @endif
                                @endif
                                
                                <div class="info-item">
                                    <span class="info-label">Sistema Tratamiento Doméstico:</span>
                                    <span class="badge-componente bg-{{ $vertimiento->sistemas_tratamiento_domestico ? 'success' : 'danger' }}">
                                        {{ $vertimiento->sistemas_tratamiento_domestico ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Sistema Tratamiento Agroquímicos:</span>
                                    <span class="badge-componente bg-{{ $vertimiento->sistemas_tratamiento_agroquimicos ? 'success' : 'danger' }}">
                                        {{ $vertimiento->sistemas_tratamiento_agroquimicos ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Cumple Obligación Permiso:</span>
                                    <span class="badge-componente bg-{{ $vertimiento->cumple_obligacion_permiso ? 'success' : 'danger' }}">
                                        {{ $vertimiento->cumple_obligacion_permiso ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Gestión Permiso Vertimiento:</span>
                                    <span class="badge-componente bg-{{ $vertimiento->gestion_permiso_vertimiento ? 'success' : 'danger' }}">
                                        {{ $vertimiento->gestion_permiso_vertimiento ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Realiza Triple Lavado:</span>
                                    <span class="badge-componente bg-{{ $vertimiento->realiza_triplelavado ? 'success' : 'danger' }}">
                                        {{ $vertimiento->realiza_triplelavado ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($vertimiento->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $vertimiento->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                            <a href="{{ route('vertimientos.create', $visita->id) }}" 
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
    
    {{-- ACORDEÓN 10: PLANTACIÓN HMP --}}
    @if($visita->plantacionHmp)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingPlantacionHmp">
            <button class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapsePlantacionHmp" 
                    aria-expanded="false" 
                    aria-controls="collapsePlantacionHmp">
                🌳 Plantación HMP Registrada
            </button>
        </h2>
        <div id="collapsePlantacionHmp" 
            class="accordion-collapse collapse" 
            aria-labelledby="headingPlantacionHmp" data-bs-parent="#accordionAmbiental">
            <div class="accordion-body">
                @php $hmp = $visita->plantacionHmp; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>🌳 Plantación HMP</span>
                        <small class="opacity-75">
                            Registrado: {{ $hmp->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Implementa HMP:</span>
                                    <span class="badge-componente bg-{{ $hmp->implementa_hmp ? 'success' : 'danger' }}">
                                        {{ $hmp->implementa_hmp ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                @if($hmp->implementa_hmp)
                                    @if($hmp->hectareas_hmp)
                                    <div class="info-item">
                                        <span class="info-label">Hectáreas HMP:</span>
                                        <span class="info-value">
                                            {{ number_format($hmp->hectareas_hmp, 2) }} Ha
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($hmp->porcentaje_hmp)
                                    <div class="info-item">
                                        <span class="info-label">Porcentaje HMP:</span>
                                        <span class="info-value">
                                            {{ number_format($hmp->porcentaje_hmp, 1) }}%
                                        </span>
                                    </div>
                                    @endif
                                    
                                    <div class="info-item">
                                        <span class="info-label">Incluye HMP en Diseño:</span>
                                        <span class="badge-componente bg-{{ $hmp->incluye_hmp_disenio ? 'success' : 'danger' }}">
                                            {{ $hmp->incluye_hmp_disenio ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                @else
                                    <div class="info-item">
                                        <span class="info-label">Información HMP:</span>
                                        <span class="info-value text-muted">
                                            No aplica (No implementa HMP)
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-6">
                                @if($hmp->implementa_hmp && isset($areaTotalFinca) && $areaTotalFinca > 0)
                                <div class="info-item">
                                    <span class="info-label">Área Total Finca:</span>
                                    <span class="info-value">
                                        {{ number_format($areaTotalFinca, 2) }} Ha
                                    </span>
                                </div>
                                
                                @if($hmp->hectareas_hmp)
                                <div class="info-item">
                                    <span class="info-label">Porcentaje HMP vs Total:</span>
                                    <span class="info-value">
                                        {{ number_format(($hmp->hectareas_hmp / $areaTotalFinca) * 100, 1) }}%
                                    </span>
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
                        
                        @if(!empty($hmp->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $hmp->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                            <a href="{{ route('plantacion_hmp.edit', $visita->id) }}" 
                            class="btn btn-sm btn-warning me-2 btn-acordeon">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('plantacion_hmp.create', $visita->id) }}" 
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
    
    {{-- ACORDEÓN 11: PLANTACIÓN AVC --}}
    @if($visita->plantacionAvc)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingPlantacionAvc">
            <button class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapsePlantacionAvc" 
                    aria-expanded="false" 
                    aria-controls="collapsePlantacionAvc">
                🦜 Plantación AVC Registrada
            </button>
        </h2>
        <div id="collapsePlantacionAvc" 
            class="accordion-collapse collapse" 
            aria-labelledby="headingPlantacionAvc" data-bs-parent="#accordionAmbiental">
            <div class="accordion-body">
                @php $avc = $visita->plantacionAvc; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>🦜 Plantación AVC</span>
                        <small class="opacity-75">
                            Registrado: {{ $avc->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Registros de Avistamientos:</span>
                                    <span class="badge-componente bg-{{ $avc->registros_avistamientos ? 'success' : 'danger' }}">
                                        {{ $avc->registros_avistamientos ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Identifica AVC/ARC:</span>
                                    <span class="badge-componente bg-{{ $avc->identifica_avc_arc ? 'success' : 'danger' }}">
                                        {{ $avc->identifica_avc_arc ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                @if($avc->identifica_avc_arc)
                                    @if(!empty($avc->especies_identificadas))
                                    <div class="info-item">
                                        <span class="info-label">Especies Identificadas:</span>
                                        <span class="info-value">
                                            {{ $avc->especies_identificadas }}
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if($avc->fecha_identificacion)
                                    <div class="info-item">
                                        <span class="info-label">Fecha Identificación:</span>
                                        <span class="info-value">
                                            {{ $avc->fecha_identificacion->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    @endif
                                    
                                    @if(!empty($avc->ubicacion_identificacion))
                                    <div class="info-item">
                                        <span class="info-label">Ubicación Identificación:</span>
                                        <span class="info-value">
                                            {{ $avc->ubicacion_identificacion }}
                                        </span>
                                    </div>
                                    @endif
                                @endif
                            </div>
                            
                            <div class="col-md-6">
                                @if($avc->identifica_avc_arc && $avc->tipo_identificacion)
                                <div class="info-item">
                                    <span class="info-label">Tipo de Identificación:</span>
                                    <div class="info-value">
                                        @php
                                            $tipos = is_array($avc->tipo_identificacion) 
                                                ? $avc->tipo_identificacion 
                                                : json_decode($avc->tipo_identificacion, true);
                                        @endphp
                                        
                                        @if(is_array($tipos) && count($tipos) > 0)
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($tipos as $tipo)
                                                    @switch($tipo)
                                                        @case('directa') 
                                                            <span class="badge bg-primary">Directa</span>
                                                            @break
                                                        @case('indirecta')
                                                            <span class="badge bg-info">Indirecta</span>
                                                            @break
                                                        @case('entrevistas')
                                                            <span class="badge bg-success">Entrevistas</span>
                                                            @break
                                                        @case('trampas_camara')
                                                            <span class="badge bg-warning">Trampas Cámara</span>
                                                            @break
                                                        @case('huellas')
                                                            <span class="badge bg-secondary">Huellas</span>
                                                            @break
                                                        @case('registros_acusticos')
                                                            <span class="badge bg-dark">Registros Acústicos</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-light text-dark">{{ $tipo }}</span>
                                                    @endswitch
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No especificado</span>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                <div class="info-item">
                                    <span class="info-label">Implementa Medidas de Manejo:</span>
                                    <span class="badge-componente bg-{{ $avc->implementa_medidas_manejo ? 'success' : 'danger' }}">
                                        {{ $avc->implementa_medidas_manejo ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($avc->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $avc->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                            <a href="{{ route('plantacion_avc.edit', $visita->id) }}" 
                            class="btn btn-sm btn-warning me-2 btn-acordeon">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('plantacion_avc.create', $visita->id) }}" 
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
    
    {{-- ACORDEÓN 12: PLANTACIÓN ECOSISTEMAS (NUEVO) --}}
    @if($visita->plantacionEcosistema)
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingPlantacionEcosistema">
            <button class="accordion-button collapsed" 
                    type="button" 
                    data-bs-toggle="collapse" data-bs-target="#collapsePlantacionEcosistema" 
                    aria-expanded="false" 
                    aria-controls="collapsePlantacionEcosistema">
                🌿 Plantación Ecosistemas Registrada
            </button>
        </h2>
        <div id="collapsePlantacionEcosistema" 
            class="accordion-collapse collapse" 
            aria-labelledby="headingPlantacionEcosistema" data-bs-parent="#accordionAmbiental">
            <div class="accordion-body">
                @php $ecosistema = $visita->plantacionEcosistema; @endphp
                
                <div class="componente-card">
                    <div class="componente-header">
                        <span>🌿 Plantación Ecosistemas</span>
                        <small class="opacity-75">
                            Registrado: {{ $ecosistema->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    <div class="componente-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Planes de Manejo Diferenciados:</span>
                                    <span class="badge-componente bg-{{ $ecosistema->planes_manejo_diferenciados ? 'success' : 'danger' }}">
                                        {{ $ecosistema->planes_manejo_diferenciados ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Acciones de Conservación Fragmentos:</span>
                                    <span class="badge-componente bg-{{ $ecosistema->acciones_conservacion_fragmentos ? 'success' : 'danger' }}">
                                        {{ $ecosistema->acciones_conservacion_fragmentos ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">Implementa Planes de Manejo Diferenciado:</span>
                                    <span class="badge-componente bg-{{ $ecosistema->implementa_planes_manejo_diferenciado ? 'success' : 'danger' }}">
                                        {{ $ecosistema->implementa_planes_manejo_diferenciado ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label">Respeta Distancias Ronda Hídrica:</span>
                                    <span class="badge-componente bg-{{ $ecosistema->respeta_distancias_ronda_hidrica ? 'success' : 'danger' }}">
                                        {{ $ecosistema->respeta_distancias_ronda_hidrica ? '✅ Sí' : '❌ No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($ecosistema->observaciones))
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="info-item">
                                    <span class="info-label">Observaciones:</span>
                                    <div class="info-value mt-1 p-3 bg-light rounded">
                                        {{ $ecosistema->observaciones }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-3 d-flex justify-content-end">
                            <a href="{{ route('plantacion_ecosistemas.edit', $visita->id) }}" 
                            class="btn btn-sm btn-warning me-2 btn-acordeon">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('plantacion_ecosistemas.create', $visita->id) }}" 
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
@if(!$visita->aguaCaptacionLegal || !$visita->aguaUsoEficiente || !$visita->sueloConservacion || !$visita->energiaManejo || !$visita->gobernanzaHidrica || !$visita->emisionesGei || !$visita->residuosManejo || !$visita->sustanciasQuimicasBiologicas || !$visita->vertimientoManejo || !$visita->plantacionHmp || !$visita->plantacionAvc || !$visita->plantacionEcosistema)
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
        @if(!$visita->emisionesGei)
        <li>
            🌍 <strong>Emisiones GEI</strong> no registrado
            <a href="{{ route('emisiones_gei.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                Registrar ahora
            </a>
        </li>
        @endif
        @if(!$visita->residuosManejo)
        <li>
            🗑️ <strong>Residuos - Manejo</strong> no registrado
            <a href="{{ route('residuos.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                Registrar ahora
            </a>
        </li>
        @endif
        @if(!$visita->sustanciasQuimicasBiologicas)
        <li>
            ⚗️ <strong>Sustancias Químicas/Biológicas</strong> no registrado
            <a href="{{ route('sustancias_quimicas_biologicas.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                Registrar ahora
            </a>
        </li>
        @endif
        @if(!$visita->vertimientoManejo)
        <li>
            💦 <strong>Vertimiento - Manejo</strong> no registrado
            <a href="{{ route('vertimiento.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                Registrar ahora
            </a>
        </li>
        @endif
        @if(!$visita->plantacionHmp)
        <li>
            🌳 <strong>Plantación HMP</strong> no registrado
            <a href="{{ route('plantacion_hmp.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                Registrar ahora
            </a>
        </li>
        @endif
        @if(!$visita->plantacionAvc)
        <li>
            🦜 <strong>Plantación AVC</strong> no registrado
            <a href="{{ route('plantacion_avc.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                Registrar ahora
            </a>
        </li>
        @endif
        @if(!$visita->plantacionEcosistema)
        <li>
            🌿 <strong>Plantación Ecosistemas</strong> no registrado
            <a href="{{ route('plantacion_ecosistemas.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
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
        <a href="{{ route('gobernanza.create', $visita->id) }}" class="btn btn-secondary me-2">
            🤝 Registrar Gobernanza Hídrica
        </a>
        <a href="{{ route('emisiones_gei.create', $visita->id) }}" class="btn btn-dark me-2">
            🌍 Registrar Emisiones GEI
        </a>
        <a href="{{ route('residuos.create', $visita->id) }}" class="btn btn-danger me-2">
            🗑️ Registrar Residuos Manejo
        </a>
        <a href="{{ route('sustancias_quimicas_biologicas.create', $visita->id) }}" class="btn btn-purple me-2">
            ⚗️ Registrar Sustancias Químicas/Biológicas
        </a>
        <a href="{{ route('vertimiento.create', $visita->id) }}" class="btn btn-primary me-2">
            💦 Registrar Vertimiento Manejo
        </a>
        <a href="{{ route('plantacion_hmp.create', $visita->id) }}" class="btn btn-success me-2">
            🌳 Registrar Plantación HMP
        </a>
        <a href="{{ route('plantacion_avc.create', $visita->id) }}" class="btn btn-warning me-2">
            🦜 Registrar Plantación AVC
        </a>
        <a href="{{ route('plantacion_ecosistemas.create', $visita->id) }}" class="btn btn-success">
            🌿 Registrar Plantación Ecosistemas
        </a>
    </div>
</div>
@endif
<form action="{{ route('redireccion_componente_ambiental', $visita->id) }}" method="GET">
    <div class="mb-3">
        <label for="componente" class="form-label fw-bold text-success">
            <i class="fas fa-map-signs me-2"></i>Seleccione un componente:
        </label>
        <div class="input-group input-group-lg">
            <select id="componente" name="seccion" class="form-select" required style="border-radius: 10px 0 0 10px; border: 2px solid #e9ecef;">
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
            <button type="submit" class="btn btn-success" style="border-radius: 0 10px 10px 0;">
                <i class="fas fa-arrow-right me-2"></i>Ir
            </button>
        </div>
    </div>
</form><br>

<form action="{{ route('pno_reemplazo.store') }}" method="POST" id="form-no-reemplazo">
    @csrf
    <input type="hidden" name="visita_ambiental_id" value="{{ $visita->id }}">
    <input type="hidden" id="area_total_finca" value="{{ $areaTotalFinca }}">

    <div class="card-component">
        <!-- Mostrar área total si está disponible -->
        @if($areaTotalFinca > 0)
        <div class="alert alert-success mb-3">
            <i class="fas fa-ruler-combined me-2"></i>
            <strong>Área total de la finca:</strong> {{ number_format($areaTotalFinca, 2) }} Ha
        </div>
        @else
        <div class="alert alert-warning mb-3">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Nota:</strong> No se encontró información del área total de la finca
        </div>
        @endif

        <label>¿Cuenta con estudios de AVC y ARC?</label>
        <select name="cuenta_estudios_avc_arc" class="form-control" required>
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select><br>

        <label>¿Cuenta con evidencias de no haber reemplazado bosques?</label>
        <select name="evidencias_no_reemplazo_bosques" class="form-control" required>
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select><br>

        <label>¿Cuenta con permiso de aprovechamiento forestal?</label>
        <select name="permiso_aprovechamiento_forestal" class="form-control" required>
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select><br>

        <label>¿Ha realizado restauración o compensación?</label>
        <select name="restauracion_compensacion" id="restauracion_compensacion" class="form-control" required onchange="mostrarHectareasRestauracion(this.value)">
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select><br>

        <!-- CARD QUE APARECE SOLO SI ES "Sí" -->
        <div class="restauracion-card p-3" id="restauracion_card" style="display: none;">
            <h5 class="text-success mb-3">
                <i class="fas fa-tree me-2"></i>Detalles de Restauración/Compensación
            </h5>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-ruler me-1"></i> Hectáreas restauradas/compensadas
                    </label>
                    <div class="input-group">
                        <input type="number" 
                               name="hectareas_restauracion" 
                               id="hectareas_restauracion" 
                               class="form-control"
                               step="0.01" 
                               min="0"
                               max="{{ $areaTotalFinca }}"
                               placeholder="Ej: 5.25"
                               oninput="calcularPorcentajeRestauracion()">
                        <span class="input-group-text">Ha</span>
                    </div>
                    <small class="text-muted">Área total restaurada o compensada</small>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-calendar me-1"></i> Fecha de restauración
                    </label>
                    <input type="date" 
                           name="fecha_restauracion" 
                           id="fecha_restauracion" 
                           class="form-control"
                           max="{{ date('Y-m-d') }}">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-leaf me-1"></i> Tipo de restauración
                    </label>
                    <select name="tipo_restauracion" id="tipo_restauracion" class="form-control">
                        <option value="">Seleccione tipo...</option>
                        <option value="restauracion_ecologica">Restauración ecológica</option>
                        <option value="compensacion_ambiental">Compensación ambiental</option>
                        <option value="reforestacion">Reforestación</option>
                        <option value="regeneracion_natural">Regeneración natural asistida</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-map-marker-alt me-1"></i> Ubicación
                    </label>
                    <input type="text" 
                           name="ubicacion_restauracion" 
                           id="ubicacion_restauracion" 
                           class="form-control"
                           placeholder="Ej: Lote 3, zona de amortiguamiento">
                </div>
            </div>
            
            <!-- Campo para "otro" tipo de restauración -->
            <div id="otro_tipo_container" style="display: none;">
                <div class="mb-3">
                    <label class="fw-bold">
                        <i class="fas fa-edit me-1"></i> Especifique tipo de restauración
                    </label>
                    <input type="text" 
                           name="otro_tipo_restauracion" 
                           id="otro_tipo_restauracion" 
                           class="form-control"
                           placeholder="Describa el tipo de restauración">
                    </div>
            </div>
            
            <!-- CÁLCULO DE PORCENTAJE -->
            <div class="card bg-light p-3 mt-3">
                <h6 class="fw-bold">
                    <i class="fas fa-calculator me-2"></i>Cálculo de porcentaje de restauración
                </h6>
                
                <!-- Fórmula y resultado -->
                <div class="alert alert-secondary" id="resultado_calculo" style="display: none;">
                    <strong>Fórmula:</strong> 
                    (Hectáreas restauradas ÷ Área total finca) × 100
                    <br>
                    <strong>Cálculo:</strong> 
                    <span id="hectareas_display">0</span> Ha ÷ 
                    <span id="area_total_display">{{ number_format($areaTotalFinca, 2) }}</span> Ha × 100 = 
                    <span id="porcentaje_resultado" class="fw-bold">0%</span>
                </div>
                
                <!-- Campo oculto para guardar el porcentaje -->
                <input type="hidden" name="porcentaje_restauracion" id="porcentaje_restauracion">
                
                <!-- Mensaje si no hay área total -->
                <div class="alert alert-warning" id="alerta_sin_area" style="{{ $areaTotalFinca <= 0 ? '' : 'display: none;' }}">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No se puede calcular el porcentaje porque no se encontró el área total de la finca.
                </div>
            </div>
        </div>

        <label>¿La plantación está dentro de la frontera agrícola?</label>
        <select name="dentro_frontera_agricola" class="form-control" required>
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select><br>

        <label>Observaciones</label>
        <textarea name="observaciones" class="form-control"></textarea>
    </div>

    <br>
    <button class="btn btn-primary" type="submit">Guardar</button>
</form>

<br>
<a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary">Volver a la visita</a>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar si hay valor en old()
    const restauracionSelect = document.getElementById('restauracion_compensacion');
    if (restauracionSelect && restauracionSelect.value === '1') {
        mostrarHectareasRestauracion('1');
    }
    
    // Mostrar/ocultar campo "otro tipo"
    const tipoRestauracion = document.getElementById('tipo_restauracion');
    if (tipoRestauracion) {
        tipoRestauracion.addEventListener('change', function() {
            const otroContainer = document.getElementById('otro_tipo_container');
            if (this.value === 'otro') {
                otroContainer.style.display = 'block';
                document.getElementById('otro_tipo_restauracion').required = true;
            } else {
                otroContainer.style.display = 'none';
                document.getElementById('otro_tipo_restauracion').required = false;
                document.getElementById('otro_tipo_restauracion').value = '';
            }
        });
    }
    
    // Inicializar campo "otro" si hay valor en old()
    if (tipoRestauracion && tipoRestauracion.value === 'otro') {
        document.getElementById('otro_tipo_container').style.display = 'block';
    }
    
    // Limitar fecha máxima a hoy
    const fechaRestauracion = document.getElementById('fecha_restauracion');
    if (fechaRestauracion) {
        fechaRestauracion.max = new Date().toISOString().split('T')[0];
        
        // Establecer fecha por defecto si está vacío
        if (!fechaRestauracion.value) {
            fechaRestauracion.value = new Date().toISOString().split('T')[0];
        }
    }
});

function mostrarHectareasRestauracion(valor) {
    const card = document.getElementById('restauracion_card');
    const hectareasInput = document.getElementById('hectareas_restauracion');
    const fechaInput = document.getElementById('fecha_restauracion');
    const tipoSelect = document.getElementById('tipo_restauracion');
    const areaTotalFinca = parseFloat(document.getElementById('area_total_finca').value) || 0;
    
    if (valor === '1') {
        card.style.display = 'block';
        hectareasInput.required = true;
        fechaInput.required = true;
        tipoSelect.required = true;
        
        // Calcular porcentaje si ya hay valor
        if (hectareasInput.value) {
            calcularPorcentajeRestauracion();
        }
        
        // Mostrar alerta si no hay área total
        if (areaTotalFinca <= 0) {
            document.getElementById('alerta_sin_area').style.display = 'block';
        }
    } else {
        card.style.display = 'none';
        hectareasInput.required = false;
        fechaInput.required = false;
        tipoSelect.required = false;
        
        // Limpiar valores
        hectareasInput.value = '';
        fechaInput.value = '';
        tipoSelect.value = '';
        document.getElementById('ubicacion_restauracion').value = '';
        document.getElementById('otro_tipo_restauracion').value = '';
        document.getElementById('porcentaje_restauracion').value = '';
        document.getElementById('resultado_calculo').style.display = 'none';
    }
}

function calcularPorcentajeRestauracion() {
    const hectareas = parseFloat(document.getElementById('hectareas_restauracion').value) || 0;
    const areaTotalFinca = parseFloat(document.getElementById('area_total_finca').value) || 0;
    
    const resultadoDiv = document.getElementById('resultado_calculo');
    const hectareasDisplay = document.getElementById('hectareas_display');
    const areaTotalDisplay = document.getElementById('area_total_display');
    const porcentajeResultado = document.getElementById('porcentaje_resultado');
    const porcentajeHidden = document.getElementById('porcentaje_restauracion');
    
    if (areaTotalFinca > 0 && hectareas > 0) {
        // Validar que no sea mayor al área total
        if (hectareas > areaTotalFinca) {
            porcentajeResultado.textContent = '¡Error! Supera área total';
            porcentajeResultado.className = 'text-danger fw-bold';
            porcentajeHidden.value = '';
            return;
        }
        
        const porcentaje = (hectareas / areaTotalFinca) * 100;
        
        // Mostrar valores formateados
        hectareasDisplay.textContent = hectareas.toFixed(2);
        areaTotalDisplay.textContent = areaTotalFinca.toFixed(2);
        porcentajeResultado.textContent = porcentaje.toFixed(2) + '%';
        
        // Guardar en campo oculto
        porcentajeHidden.value = porcentaje.toFixed(2);
        
        // Cambiar color según porcentaje
        if (porcentaje >= 10) {
            resultadoDiv.className = 'alert alert-success';
        } else if (porcentaje >= 5) {
            resultadoDiv.className = 'alert alert-warning';
        } else {
            resultadoDiv.className = 'alert alert-danger';
        }
        
        resultadoDiv.style.display = 'block';
        document.getElementById('alerta_sin_area').style.display = 'none';
    } else if (areaTotalFinca <= 0) {
        resultadoDiv.style.display = 'none';
        document.getElementById('alerta_sin_area').style.display = 'block';
        porcentajeHidden.value = '';
    } else {
        resultadoDiv.style.display = 'none';
        porcentajeHidden.value = '';
    }
}

// Validación antes de enviar
document.getElementById('form-no-reemplazo').addEventListener('submit', function(e) {
    const restauracion = document.getElementById('restauracion_compensacion').value;
    const areaTotalFinca = parseFloat(document.getElementById('area_total_finca').value) || 0;
    
    if (restauracion === '1') {
        const hectareas = document.getElementById('hectareas_restauracion').value;
        const fecha = document.getElementById('fecha_restauracion').value;
        const tipo = document.getElementById('tipo_restauracion').value;
        
        // Validar hectáreas
        if (!hectareas.trim()) {
            e.preventDefault();
            alert('Por favor ingrese las hectáreas restauradas/compensadas');
            document.getElementById('hectareas_restauracion').focus();
            return false;
        }
        
        const hectareasNum = parseFloat(hectareas);
        
        // Validar que no sea mayor al área total
        if (areaTotalFinca > 0 && hectareasNum > areaTotalFinca) {
            e.preventDefault();
            alert(`Las hectáreas restauradas (${hectareasNum} Ha) no pueden ser mayores al área total de la finca (${areaTotalFinca} Ha)`);
            document.getElementById('hectareas_restauracion').focus();
            return false;
        }
        
        // Validar fecha
        if (!fecha) {
            e.preventDefault();
            alert('Por favor seleccione la fecha de restauración');
            document.getElementById('fecha_restauracion').focus();
            return false;
        }
        
        // Validar tipo
        if (!tipo) {
            e.preventDefault();
            alert('Por favor seleccione el tipo de restauración');
            document.getElementById('tipo_restauracion').focus();
            return false;
        }
        
        // Validar campo "otro" si aplica
        if (tipo === 'otro') {
            const otroTipo = document.getElementById('otro_tipo_restauracion').value;
            if (!otroTipo.trim()) {
                e.preventDefault();
                alert('Por favor describa el tipo de restauración');
                document.getElementById('otro_tipo_restauracion').focus();
                return false;
            }
        }
        
        // Advertencia si no hay área total
        if (areaTotalFinca <= 0) {
            if (!confirm('⚠️ No se encontró información del área total de la finca. El porcentaje no se calculará. ¿Desea continuar?')) {
                e.preventDefault();
                return false;
            }
        }
    }
});
</script>
@endsection
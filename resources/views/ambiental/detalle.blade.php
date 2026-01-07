@extends('layouts.app')

@section('content')
<style>
.container {
    background-color: #e8f5e9;
    padding: 20px;
    border-radius: 10px;
}
.info-header {
    text-align: center;
    font-family: Arial Black;
    font-weight: bold;
    font-size: 30px;
    color: #2e7d32;
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
}
.info-detail {
    text-align: center;
    color: #1b5e20;
    font-weight: bold;
}
.data-card {
    background: #f1f8e9;
    padding: 15px;
    border-radius: 8px;
    border-left: 5px solid #4caf50;
    margin-bottom: 15px;
}
.componente-header {
    background: linear-gradient(45deg, #2e7d32, #4caf50);
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    margin-bottom: 10px;
}
.badge-ambiental {
    font-size: 0.9em;
    padding: 5px 10px;
}
.firma-img {
    max-width: 250px;
    border: 1px solid #ddd;
    padding: 5px;
    background: white;
    border-radius: 5px;
}
.img-thumb {
    max-height: 200px;
    object-fit: cover;
    border: 2px solid #4caf50;
}
.accordion-button {
    font-weight: bold;
}
</style>

<div class="container" style="width: 80%; margin-left: 120px !important;">
    <h3 class="info-header" >🌍 Detalle Completo de Visita Ambiental - {{ $visita->fecha }}</h3>
    <h4 class="info-detail">
        Proveedor: <span style="color: #1b5e20">{{ $visita->proveedor?->proveedor_nombre ?? 'No asignado' }}</span><br>
        Plantación: <span style="color: #1b5e20">{{ $visita->plantacion?->nombre ?? 'Sin nombre de plantación' }}</span>
    </h4>
    
    <div class="accordion mt-4" id="acordeonDetalleAmbiental">

        {{-- 1. AGUA - CAPTACIÓN LEGAL --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAguaCaptacion">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAguaCaptacion">
                    💧 Agua - Captación Legal
                </button>
            </h2>
            <div id="collapseAguaCaptacion" class="accordion-collapse collapse show" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->aguaCaptacionLegal)
                        <div class="data-card">
                            <div class="componente-header">
                                💧 Información de Captación Legal de Agua
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Permiso/Concesión:</strong> 
                                        <span class="badge bg-{{ $visita->aguaCaptacionLegal->permiso_concesion ? 'success' : 'danger' }}">
                                            {{ $visita->aguaCaptacionLegal->permiso_concesion ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Permiso Ocupación Cauce:</strong> 
                                        <span class="badge bg-{{ $visita->aguaCaptacionLegal->permiso_ocupacion_cauce ? 'success' : 'danger' }}">
                                            {{ $visita->aguaCaptacionLegal->permiso_ocupacion_cauce ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Permisos Captación:</strong> 
                                        <span class="badge bg-{{ $visita->aguaCaptacionLegal->permisos_captacion ? 'success' : 'danger' }}">
                                            {{ $visita->aguaCaptacionLegal->permisos_captacion ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Registro de Agua:</strong> 
                                        <span class="badge bg-{{ $visita->aguaCaptacionLegal->registro_agua ? 'success' : 'danger' }}">
                                            {{ $visita->aguaCaptacionLegal->registro_agua ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Cumple Manejo/Construcción:</strong> 
                                        <span class="badge bg-{{ $visita->aguaCaptacionLegal->cumple_manejo_construccion ? 'success' : 'danger' }}">
                                            {{ $visita->aguaCaptacionLegal->cumple_manejo_construccion ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @if($visita->aguaCaptacionLegal->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->aguaCaptacionLegal->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de captación legal de agua.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 2. AGUA - USO EFICIENTE --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAguaUso">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAguaUso">
                    🚰 Agua - Uso Eficiente
                </button>
            </h2>
            <div id="collapseAguaUso" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->aguaUsoEficiente)
                        <div class="data-card">
                            <div class="componente-header">
                                🚰 Información de Uso Eficiente de Agua
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Plan de Ahorro y Uso Eficiente:</strong> 
                                        <span class="badge bg-{{ $visita->aguaUsoEficiente->plan_ahorro ? 'success' : 'danger' }}">
                                            {{ $visita->aguaUsoEficiente->plan_ahorro ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Mantenimiento Sistemas:</strong> 
                                        <span class="badge bg-{{ $visita->aguaUsoEficiente->mantenimiento_sistemas ? 'success' : 'danger' }}">
                                            {{ $visita->aguaUsoEficiente->mantenimiento_sistemas ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Uso Información Técnica:</strong> 
                                        <span class="badge bg-{{ $visita->aguaUsoEficiente->uso_informacion_balance ? 'success' : 'danger' }}">
                                            {{ $visita->aguaUsoEficiente->uso_informacion_balance ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Mecanismo de Medición:</strong> 
                                        <span class="badge bg-{{ $visita->aguaUsoEficiente->mecanismo_medicion ? 'success' : 'danger' }}">
                                            {{ $visita->aguaUsoEficiente->mecanismo_medicion ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    @if($visita->aguaUsoEficiente->consumo_agua)
                                        <p><strong>Consumo de Agua:</strong> 
                                            {{ number_format($visita->aguaUsoEficiente->consumo_agua, 2) }} m³/mes
                                        </p>
                                    @endif
                                    @if($visita->aguaUsoEficiente->metodo_medicion)
                                        <p><strong>Método de Medición:</strong> 
                                            @switch($visita->aguaUsoEficiente->metodo_medicion)
                                                @case('contador_agua') 📊 Contador de agua @break
                                                @case('medidor_volumen') ⚖️ Medidor de volumen @break
                                                @case('estimacion_manual') 📝 Estimación manual @break
                                                @case('sistema_automatico') 🤖 Sistema automático @break
                                                @case('lectura_mensual') 📅 Lectura mensual @break
                                                @default {{ $visita->aguaUsoEficiente->metodo_medicion }}
                                            @endswitch
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @if($visita->aguaUsoEficiente->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->aguaUsoEficiente->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de uso eficiente de agua.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 3. SUELO - CONSERVACIÓN --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSueloConservacion">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSueloConservacion">
                    🌱 Suelo - Conservación
                </button>
            </h2>
            <div id="collapseSueloConservacion" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->sueloConservacion)
                        <div class="data-card">
                            <div class="componente-header">
                                🌱 Información de Conservación de Suelo
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Uso de Fuego Preparación:</strong> 
                                        <span class="badge bg-{{ $visita->sueloConservacion->uso_fuego_preparacion ? 'danger' : 'success' }}">
                                            {{ $visita->sueloConservacion->uso_fuego_preparacion ? '❌ Sí' : '✅ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Control Coberturas Invasoras:</strong> 
                                        <span class="badge bg-{{ $visita->sueloConservacion->control_coberturas_invasoras ? 'success' : 'danger' }}">
                                            {{ $visita->sueloConservacion->control_coberturas_invasoras ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Siembra Coberturas:</strong> 
                                        <span class="badge bg-{{ $visita->sueloConservacion->siembra_coberturas ? 'success' : 'danger' }}">
                                            {{ $visita->sueloConservacion->siembra_coberturas ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    @if($visita->sueloConservacion->siembra_coberturas)
                                        @if($visita->sueloConservacion->area_cobertura)
                                            <p><strong>Área Cobertura:</strong> 
                                                {{ number_format($visita->sueloConservacion->area_cobertura, 2) }} Ha
                                            </p>
                                        @endif
                                        @if($visita->sueloConservacion->tipo_cobertura)
                                            <p><strong>Tipo de Cobertura:</strong> 
                                                @switch($visita->sueloConservacion->tipo_cobertura)
                                                    @case('leguminosas') Leguminosas (kudzu, canavalia, etc.) @break
                                                    @case('gramineas') Gramíneas (brachiaria, pennisetum, etc.) @break
                                                    @case('mixta') Mezcla de especies @break
                                                    @case('natural') Cobertura natural @break
                                                    @default {{ $visita->sueloConservacion->tipo_cobertura ?? 'N/A' }}
                                                @endswitch
                                            </p>
                                        @endif
                                        @if($visita->sueloConservacion->porcentaje_cobertura)
                                            <p><strong>Porcentaje Cobertura:</strong> 
                                                {{ number_format($visita->sueloConservacion->porcentaje_cobertura, 1) }}%
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @if($visita->sueloConservacion->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->sueloConservacion->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de conservación de suelo.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 4. ENERGÍA - MANEJO --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingEnergia">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEnergia">
                    ⚡ Energía - Manejo
                </button>
            </h2>
            <div id="collapseEnergia" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->energiaManejo)
                        <div class="data-card">
                            <div class="componente-header">
                                ⚡ Información de Manejo de Energía
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Registro Consumo Combustible:</strong> 
                                        <span class="badge bg-{{ $visita->energiaManejo->registro_consumo_combustible ? 'success' : 'danger' }}">
                                            {{ $visita->energiaManejo->registro_consumo_combustible ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Plan Uso Eficiente:</strong> 
                                        <span class="badge bg-{{ $visita->energiaManejo->plan_uso_eficiente ? 'success' : 'danger' }}">
                                            {{ $visita->energiaManejo->plan_uso_eficiente ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    @if($visita->energiaManejo->consumo_energia_kwh)
                                        <p><strong>Consumo Energía:</strong> 
                                            {{ number_format($visita->energiaManejo->consumo_energia_kwh, 2) }} kWh/mes
                                        </p>
                                    @endif
                                    <p><strong>Seguimiento Indicadores:</strong> 
                                        <span class="badge bg-{{ $visita->energiaManejo->seguimiento_indicadores ? 'success' : 'danger' }}">
                                            {{ $visita->energiaManejo->seguimiento_indicadores ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @if($visita->energiaManejo->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->energiaManejo->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de manejo de energía.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 5. GOBERNANZA HÍDRICA --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingGobernanza">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGobernanza">
                    🤝 Gobernanza Hídrica
                </button>
            </h2>
            <div id="collapseGobernanza" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->gobernanzaHidrica)
                        <div class="data-card">
                            <div class="componente-header">
                                🤝 Información de Gobernanza Hídrica
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Canales de Comunicación:</strong> 
                                        <span class="badge bg-{{ $visita->gobernanzaHidrica->canales_comunicacion ? 'success' : 'danger' }}">
                                            {{ $visita->gobernanzaHidrica->canales_comunicacion ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Identifica Actores Afectados:</strong> 
                                        <span class="badge bg-{{ $visita->gobernanzaHidrica->identifica_actores_afectados ? 'success' : 'danger' }}">
                                            {{ $visita->gobernanzaHidrica->identifica_actores_afectados ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Participa en Actividades Gestión:</strong> 
                                        <span class="badge bg-{{ $visita->gobernanzaHidrica->participa_actividades_gestion ? 'success' : 'danger' }}">
                                            {{ $visita->gobernanzaHidrica->participa_actividades_gestion ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @if($visita->gobernanzaHidrica->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->gobernanzaHidrica->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de gobernanza hídrica.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 6. EMISIONES GEI --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingEmisiones">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEmisiones">
                    🌍 Emisiones GEI
                </button>
            </h2>
            <div id="collapseEmisiones" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->emisionesGei)
                        <div class="data-card">
                            <div class="componente-header">
                                🌍 Información de Emisiones GEI
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Cuantifica Emisiones:</strong> 
                                        <span class="badge bg-{{ $visita->emisionesGei->cuantifica_emisiones ? 'success' : 'danger' }}">
                                            {{ $visita->emisionesGei->cuantifica_emisiones ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    @if($visita->emisionesGei->cuantifica_emisiones)
                                        @if($visita->emisionesGei->combustible)
                                            <p><strong>Combustible:</strong> {{ $visita->emisionesGei->combustible }}</p>
                                        @endif
                                        @if($visita->emisionesGei->distancia)
                                            <p><strong>Distancia:</strong> {{ number_format($visita->emisionesGei->distancia, 2) }} km</p>
                                        @endif
                                        @if($visita->emisionesGei->huella_carbono)
                                            <p><strong>Huella de Carbono:</strong> {{ number_format($visita->emisionesGei->huella_carbono, 2) }} tCO₂e</p>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Implementa Acciones Reducción:</strong> 
                                        <span class="badge bg-{{ $visita->emisionesGei->implementa_acciones_reduccion ? 'success' : 'danger' }}">
                                            {{ $visita->emisionesGei->implementa_acciones_reduccion ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    @if($visita->emisionesGei->implementa_acciones_reduccion && !empty($visita->emisionesGei->acciones_reduccion))
                                        <p><strong>Acciones de Reducción:</strong><br>
                                            {{ $visita->emisionesGei->acciones_reduccion }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @if($visita->emisionesGei->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->emisionesGei->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de emisiones GEI.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 7. RESIDUOS - MANEJO --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingResiduos">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseResiduos">
                    🗑️ Residuos - Manejo
                </button>
            </h2>
            <div id="collapseResiduos" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->residuosManejo)
                        <div class="data-card">
                            <div class="componente-header">
                                🗑️ Información de Manejo de Residuos
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Capacita Personal:</strong> 
                                        <span class="badge bg-{{ $visita->residuosManejo->capacita_personal ? 'success' : 'danger' }}">
                                            {{ $visita->residuosManejo->capacita_personal ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    @if($visita->residuosManejo->capacita_personal)
                                        @if($visita->residuosManejo->personas_manipulan)
                                            <p><strong>Personas que Manipulan:</strong> {{ $visita->residuosManejo->personas_manipulan }} personas</p>
                                        @endif
                                        @if($visita->residuosManejo->personas_capacitadas)
                                            <p><strong>Personas Capacitadas:</strong> {{ $visita->residuosManejo->personas_capacitadas }} personas</p>
                                        @endif
                                        @if($visita->residuosManejo->porcentaje_capacitadas)
                                            <p><strong>Porcentaje Capacitadas:</strong> {{ number_format($visita->residuosManejo->porcentaje_capacitadas, 1) }}%</p>
                                        @endif
                                    @endif
                                    <p><strong>Conoce Diferencias Residuos:</strong> 
                                        <span class="badge bg-{{ $visita->residuosManejo->conoce_diferencias ? 'success' : 'danger' }}">
                                            {{ $visita->residuosManejo->conoce_diferencias ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Puntos Ecológicos:</strong> 
                                        <span class="badge bg-{{ $visita->residuosManejo->puntos_ecologicos ? 'success' : 'danger' }}">
                                            {{ $visita->residuosManejo->puntos_ecologicos ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Pesa y Registra:</strong> 
                                        <span class="badge bg-{{ $visita->residuosManejo->pesa_y_registra ? 'success' : 'danger' }}">
                                            {{ $visita->residuosManejo->pesa_y_registra ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Acciones Minimizar Impacto:</strong> 
                                        <span class="badge bg-{{ $visita->residuosManejo->acciones_minimizar_impacto ? 'success' : 'danger' }}">
                                            {{ $visita->residuosManejo->acciones_minimizar_impacto ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @if($visita->residuosManejo->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->residuosManejo->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de manejo de residuos.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 8. SUSTANCIAS QUÍMICAS/BIOLÓGICAS --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSustancias">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSustancias">
                    ⚗️ Sustancias Químicas/Biológicas
                </button>
            </h2>
            <div id="collapseSustancias" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->sustanciasQuimicasBiologicas)
                        <div class="data-card">
                            <div class="componente-header">
                                ⚗️ Información de Sustancias Químicas/Biológicas
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Cuenta con POES:</strong> 
                                        <span class="badge bg-{{ $visita->sustanciasQuimicasBiologicas->cuenta_poes ? 'success' : 'danger' }}">
                                            {{ $visita->sustanciasQuimicasBiologicas->cuenta_poes ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Personal Capacitado:</strong> 
                                        <span class="badge bg-{{ $visita->sustanciasQuimicasBiologicas->personal_capacitado ? 'success' : 'danger' }}">
                                            {{ $visita->sustanciasQuimicasBiologicas->personal_capacitado ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Almacenamiento Adecuado:</strong> 
                                        <span class="badge bg-{{ $visita->sustanciasQuimicasBiologicas->almacenamiento_adecuado ? 'success' : 'danger' }}">
                                            {{ $visita->sustanciasQuimicasBiologicas->almacenamiento_adecuado ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    @if($visita->sustanciasQuimicasBiologicas->imagen_poes)
                                        <p><strong>Imagen POES:</strong><br>
                                            <a href="{{ Storage::url($visita->sustanciasQuimicasBiologicas->imagen_poes) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> Ver Imagen
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @if($visita->sustanciasQuimicasBiologicas->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->sustanciasQuimicasBiologicas->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de sustancias químicas/biológicas.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 9. VERTIMIENTO - MANEJO --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingVertimiento">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVertimiento">
                    💦 Vertimiento - Manejo
                </button>
            </h2>
            <div id="collapseVertimiento" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->vertimientoManejo)
                        <div class="data-card">
                            <div class="componente-header">
                                💦 Información de Manejo de Vertimientos
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Permiso de Vertimientos:</strong> 
                                        <span class="badge bg-{{ $visita->vertimientoManejo->permiso_vertimiento ? 'success' : 'danger' }}">
                                            {{ $visita->vertimientoManejo->permiso_vertimiento ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    @if($visita->vertimientoManejo->permiso_vertimiento)
                                        @if($visita->vertimientoManejo->numero_vertimientos_permitidos !== null)
                                            <p><strong>Vertimientos Permitidos:</strong> {{ $visita->vertimientoManejo->numero_vertimientos_permitidos }}</p>
                                        @endif
                                        @if($visita->vertimientoManejo->numero_vertimientos_totales !== null)
                                            <p><strong>Vertimientos Totales:</strong> {{ $visita->vertimientoManejo->numero_vertimientos_totales }}</p>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Cumple Obligación Permiso:</strong> 
                                        <span class="badge bg-{{ $visita->vertimientoManejo->cumple_obligacion_permiso ? 'success' : 'danger' }}">
                                            {{ $visita->vertimientoManejo->cumple_obligacion_permiso ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Realiza Triple Lavado:</strong> 
                                        <span class="badge bg-{{ $visita->vertimientoManejo->realiza_triplelavado ? 'success' : 'danger' }}">
                                            {{ $visita->vertimientoManejo->realiza_triplelavado ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @if($visita->vertimientoManejo->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->vertimientoManejo->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de manejo de vertimientos.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 10. PLANTACIÓN HMP --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingHMP">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHMP">
                    🌳 Plantación HMP
                </button>
            </h2>
            <div id="collapseHMP" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->plantacionHmp)
                        <div class="data-card">
                            <div class="componente-header">
                                🌳 Información de Plantación HMP
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Implementa HMP:</strong> 
                                        <span class="badge bg-{{ $visita->plantacionHmp->implementa_hmp ? 'success' : 'danger' }}">
                                            {{ $visita->plantacionHmp->implementa_hmp ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    @if($visita->plantacionHmp->implementa_hmp)
                                        @if($visita->plantacionHmp->hectareas_hmp)
                                            <p><strong>Hectáreas HMP:</strong> {{ number_format($visita->plantacionHmp->hectareas_hmp, 2) }} Ha</p>
                                        @endif
                                        @if($visita->plantacionHmp->porcentaje_hmp)
                                            <p><strong>Porcentaje HMP:</strong> {{ number_format($visita->plantacionHmp->porcentaje_hmp, 1) }}%</p>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($visita->plantacionHmp->implementa_hmp)
                                        <p><strong>Incluye HMP en Diseño:</strong> 
                                            <span class="badge bg-{{ $visita->plantacionHmp->incluye_hmp_disenio ? 'success' : 'danger' }}">
                                                {{ $visita->plantacionHmp->incluye_hmp_disenio ? '✅ Sí' : '❌ No' }}
                                            </span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @if($visita->plantacionHmp->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->plantacionHmp->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de plantación HMP.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 11. PLANTACIÓN AVC --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAVC">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAVC">
                    🦜 Plantación AVC
                </button>
            </h2>
            <div id="collapseAVC" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->plantacionAvc)
                        <div class="data-card">
                            <div class="componente-header">
                                🦜 Información de Plantación AVC
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Registros de Avistamientos:</strong> 
                                        <span class="badge bg-{{ $visita->plantacionAvc->registros_avistamientos ? 'success' : 'danger' }}">
                                            {{ $visita->plantacionAvc->registros_avistamientos ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Identifica AVC/ARC:</strong> 
                                        <span class="badge bg-{{ $visita->plantacionAvc->identifica_avc_arc ? 'success' : 'danger' }}">
                                            {{ $visita->plantacionAvc->identifica_avc_arc ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    @if($visita->plantacionAvc->identifica_avc_arc)
                                        @if(!empty($visita->plantacionAvc->especies_identificadas))
                                            <p><strong>Especies Identificadas:</strong> {{ $visita->plantacionAvc->especies_identificadas }}</p>
                                        @endif
                                        @if($visita->plantacionAvc->fecha_identificacion)
                                            <p><strong>Fecha Identificación:</strong> {{ $visita->plantacionAvc->fecha_identificacion->format('d/m/Y') }}</p>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @if($visita->plantacionAvc->identifica_avc_arc && $visita->plantacionAvc->tipo_identificacion)
                                        <p><strong>Tipo de Identificación:</strong><br>
                                            @php
                                                $tipos = is_array($visita->plantacionAvc->tipo_identificacion) 
                                                    ? $visita->plantacionAvc->tipo_identificacion 
                                                    : json_decode($visita->plantacionAvc->tipo_identificacion, true);
                                            @endphp
                                            @if(is_array($tipos) && count($tipos) > 0)
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($tipos as $tipo)
                                                        @switch($tipo)
                                                            @case('directa') <span class="badge bg-primary">Directa</span> @break
                                                            @case('indirecta') <span class="badge bg-info">Indirecta</span> @break
                                                            @case('entrevistas') <span class="badge bg-success">Entrevistas</span> @break
                                                            @case('trampas_camara') <span class="badge bg-warning">Trampas Cámara</span> @break
                                                            @case('huellas') <span class="badge bg-secondary">Huellas</span> @break
                                                            @case('registros_acusticos') <span class="badge bg-dark">Registros Acústicos</span> @break
                                                            @default <span class="badge bg-light text-dark">{{ $tipo }}</span>
                                                        @endswitch
                                                    @endforeach
                                                </div>
                                            @endif
                                        </p>
                                    @endif
                                    <p><strong>Implementa Medidas de Manejo:</strong> 
                                        <span class="badge bg-{{ $visita->plantacionAvc->implementa_medidas_manejo ? 'success' : 'danger' }}">
                                            {{ $visita->plantacionAvc->implementa_medidas_manejo ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @if($visita->plantacionAvc->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->plantacionAvc->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de plantación AVC.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 12. PLANTACIÓN ECOSISTEMAS --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingEcosistemas">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEcosistemas">
                    🌿 Plantación Ecosistemas
                </button>
            </h2>
            <div id="collapseEcosistemas" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->plantacionEcosistema)
                        <div class="data-card">
                            <div class="componente-header">
                                🌿 Información de Plantación Ecosistemas
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Planes de Manejo Diferenciados:</strong> 
                                        <span class="badge bg-{{ $visita->plantacionEcosistema->planes_manejo_diferenciados ? 'success' : 'danger' }}">
                                            {{ $visita->plantacionEcosistema->planes_manejo_diferenciados ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Acciones de Conservación Fragmentos:</strong> 
                                        <span class="badge bg-{{ $visita->plantacionEcosistema->acciones_conservacion_fragmentos ? 'success' : 'danger' }}">
                                            {{ $visita->plantacionEcosistema->acciones_conservacion_fragmentos ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Implementa Planes de Manejo Diferenciado:</strong> 
                                        <span class="badge bg-{{ $visita->plantacionEcosistema->implementa_planes_manejo_diferenciado ? 'success' : 'danger' }}">
                                            {{ $visita->plantacionEcosistema->implementa_planes_manejo_diferenciado ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                    <p><strong>Respeta Distancias Ronda Hídrica:</strong> 
                                        <span class="badge bg-{{ $visita->plantacionEcosistema->respeta_distancias_ronda_hidrica ? 'success' : 'danger' }}">
                                            {{ $visita->plantacionEcosistema->respeta_distancias_ronda_hidrica ? '✅ Sí' : '❌ No' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @if($visita->plantacionEcosistema->observaciones)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <strong>Observaciones:</strong><br>
                                    {{ $visita->plantacionEcosistema->observaciones }}
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No se ha registrado información de plantación ecosistemas.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 13. CIERRE DE VISITA AMBIENTAL --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCierreAmbiental">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCierreAmbiental">
                    🔏 Cierre de Visita Ambiental
                </button>
            </h2>
            <div id="collapseCierreAmbiental" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleAmbiental">
                <div class="accordion-body">
                    @if($visita->cierreVisitaAmbiental)
                        <div class="data-card">
                            <div class="componente-header">
                                🔏 Información de Cierre de Visita
                            </div>
                            <ul>
                                <li><strong>Fecha de Cierre:</strong> 
                                    {{ $visita->cierreVisitaAmbiental->fecha_cierre ? $visita->cierreVisitaAmbiental->fecha_cierre->format('d/m/Y') : 'N/A' }}
                                </li>
                                <li><strong>Estado de la Visita:</strong> 
                                    {{ ucfirst($visita->cierreVisitaAmbiental->estado_visita) ?? 'N/A' }}
                                </li>
                                <li><strong>Observaciones Finales:</strong> 
                                    {{ $visita->cierreVisitaAmbiental->observaciones_finales ?? 'No registradas' }}
                                </li>
                                <li><strong>Recomendaciones:</strong> 
                                    {{ $visita->cierreVisitaAmbiental->recomendaciones ?? 'No se especificaron' }}
                                </li>
                                <li><strong>Finalizada En:</strong> 
                                    {{ $visita->cierreVisitaAmbiental->finalizada_en ? $visita->cierreVisitaAmbiental->finalizada_en->format('d/m/Y H:i') : 'N/A' }}
                                </li>
                            </ul>

                            {{{-- Firmas --}}
@if($visita->cierreVisitaAmbiental->firma_responsable)
    <div class="mt-3">
        <strong>📄 Firma Responsable Ambiental:</strong><br>
        <img src="{{ asset($visita->cierreVisitaAmbiental->firma_responsable) }}" alt="Firma Responsable" class="firma-img">
    </div>
@endif
@if($visita->cierreVisitaAmbiental->firma_recibe)
    <div class="mt-3">
        <strong>📄 Firma de Proveedor:</strong><br>
        <img src="{{ asset($visita->cierreVisitaAmbiental->firma_recibe) }}" alt="Firma Recibe" class="firma-img">
    </div>
@endif
@if($visita->cierreVisitaAmbiental->firma_testigo)
    <div class="mt-3">
        <strong>📄 Firma del Testigo:</strong><br>
        <img src="{{ asset($visita->cierreVisitaAmbiental->firma_testigo) }}" alt="Firma Testigo" class="firma-img">
    </div>
@endif

{{-- Imágenes --}}
@php
    $imagenes = [];
    if ($visita->cierreVisitaAmbiental && $visita->cierreVisitaAmbiental->imagenes) {
        // Primero obtenemos el JSON/array
        $imagenes = is_array($visita->cierreVisitaAmbiental->imagenes) 
            ? $visita->cierreVisitaAmbiental->imagenes 
            : json_decode($visita->cierreVisitaAmbiental->imagenes, true) ?? [];
        
        // Limpiamos las barras invertidas de cada ruta
        $imagenes = array_map(function($img) {
            // Reemplazar \/ por /
            $cleaned = str_replace('\/', '/', $img);
            
            // Asegurarnos de que comience con "storage/"
            if (strpos($cleaned, 'storage/') !== 0) {
                $cleaned = 'storage/' . ltrim($cleaned, '/');
            }
            
            return $cleaned;
        }, $imagenes);
    }
@endphp

@if(count($imagenes) > 0)
    <div class="mt-4">
        <strong>🖼️ Evidencias Fotográficas:</strong><br>
        <div class="row">
            @foreach($imagenes as $img)
                <div class="col-md-4 col-6 mb-3">
                    <img src="{{ asset($img) }}" class="img-fluid rounded shadow img-thumb" 
                         alt="Evidencia visita ambiental">
                </div>
            @endforeach
        </div>
    </div>
@endif
                    @else
                        <p class="text-muted">No se ha registrado el cierre de visita ambiental.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <br><br>
    <!-- Botones de acción -->
    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
        <button onclick="descargarPDFAmbiental()" class="btn btn-danger">
            📥 Exportar PDF
        </button>
        <button onclick="descargarExcelAmbiental()" class="btn btn-success">
            📊 Exportar a Excel
        </button>
        <a href="{{ route('visitas_ambiental.homeAmbiental') }}" class="btn btn-secondary">⬅️ Volver a Listado</a>
    </div>

    <!-- Iframes ocultos para descarga -->
    <iframe id="descargaPDFAmbientalIframe" style="display:none;"></iframe>
    <iframe id="descargaExcelAmbientalIframe" style="display:none;"></iframe>

    <!-- SweetAlert y Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function descargarPDFAmbiental() {
            Swal.fire({
                title: 'Generando PDF Ambiental...',
                text: 'Esto puede tardar unos segundos',
                imageUrl: '{{ asset('images/loader.gif') }}',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });

            const url = "{{ route('visitas_ambiental.exportar.pdf', $visita->id) }}";
            window.open(url, '_blank');

            setTimeout(() => {
                Swal.close();
            }, 5000);
        }

        function descargarExcelAmbiental() {
            Swal.fire({
                title: 'Generando Excel Ambiental...',
                text: 'Esto puede tardar unos segundos',
                imageUrl: '{{ asset('images/loader.gif') }}',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });

            document.getElementById('descargaExcelAmbientalIframe').src = "{{ route('visitas_ambiental.exportar.excel', $visita->id) }}";

            setTimeout(() => {
                Swal.close();
            }, 4000);
        }
    </script>
</div>
@endsection
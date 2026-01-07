@extends('layouts.app')

@section('content')
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
        color: #fdffe5;
        text-shadow: -1px 0 #000, 0 1px #000;
        margin-bottom: 18px;
    }
    .info-visita h3 { color: wheat; }
    .info-visita small { color: #f8f9fa; }
    .selector-seccion .input-group { gap: 8px; }
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
    
    /* ESTILOS PARA EL ACORDEÓN */
    .accordion-ambiental .accordion-button {
        background-color: #2e7d32 !important;
        color: white !important;
        font-weight: bold;
    }
    .accordion-ambiental .accordion-body {
        background-color: #e8f5e9 !important;
    }
    .componente-card {
        border: 2px solid #4caf50;
        border-radius: 8px;
        margin-bottom: 15px;
        background: white;
    }
    .componente-header {
        background: #4caf50;
        color: white;
        padding: 10px 15px;
        border-radius: 6px 6px 0 0;
        font-weight: bold;
    }
    .componente-body {
        padding: 15px;
    }
    .info-item {
        padding: 5px 0;
        border-bottom: 1px solid #eee;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: bold;
        color: #2e7d32;
        min-width: 120px;
        display: inline-block;
    }
</style>

<div class="container offline-form-container" style="width:85%">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <h5 class="alert-heading">Errores de validación</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===================== ENCABEZADO ===================== --}}
    <h3 class="title">Agua — Uso Eficiente (Visita #{{ $visita->id }})</h3>

    <div class="info-visita mb-3">
        <h3>
            Fecha: <span>{{ \Carbon\Carbon::parse($visita->fecha_visita ?? $visita->fecha)->format('d/m/Y') }}</span><br>
            Proveedor: <span>{{ $visita->proveedor->proveedor_nombre ?? 'N/A' }}</span><br>
            Plantación: <span>{{ $visita->plantacion->nombre ?? 'N/A' }}</span>
        </h3>
    </div>

    {{-- ===================== ACORDEÓN PARA AGUA CAPTACIÓN LEGAL ===================== --}}
    @if($visita->aguaCaptacionLegal)
        <div class="accordion mb-4 accordion-ambiental" id="accordionCaptacionLegal">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCaptacionLegal">
                    <button class="accordion-button" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseCaptacionLegal" 
                            aria-expanded="true" aria-controls="collapseCaptacionLegal">
                        💧 Información de Agua - Captación Legal Registrada
                    </button>
                </h2>
                <div id="collapseCaptacionLegal" class="accordion-collapse collapse show" 
                     aria-labelledby="headingCaptacionLegal" data-bs-parent="#accordionCaptacionLegal">
                    <div class="accordion-body">
                        @php $captacion = $visita->aguaCaptacionLegal; @endphp
                        
                        <div class="componente-card">
                            <div class="componente-header d-flex justify-content-between align-items-center">
                                <span>💧 Agua - Captación Legal</span>
                                <small>Registrado el {{ $captacion->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            
                            <div class="componente-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label">Permiso/Concesión:</span>
                                            <span class="badge bg-{{ $captacion->permiso_concesion ? 'success' : 'danger' }}">
                                                {{ $captacion->permiso_concesion ? '✅ Sí' : '❌ No' }}
                                            </span>
                                        </div>
                                        
                                        <div class="info-item">
                                            <span class="info-label">Permiso Ocupación Cauce:</span>
                                            <span class="badge bg-{{ $captacion->permiso_ocupacion_cauce ? 'success' : 'danger' }}">
                                                {{ $captacion->permiso_ocupacion_cauce ? '✅ Sí' : '❌ No' }}
                                            </span>
                                        </div>
                                        
                                        <div class="info-item">
                                            <span class="info-label">Permisos Captación:</span>
                                            <span class="badge bg-{{ $captacion->permisos_captacion ? 'success' : 'danger' }}">
                                                {{ $captacion->permisos_captacion ? '✅ Sí' : '❌ No' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label">Registro de Agua:</span>
                                            <span class="badge bg-{{ $captacion->registro_agua ? 'success' : 'danger' }}">
                                                {{ $captacion->registro_agua ? '✅ Sí' : '❌ No' }}
                                            </span>
                                        </div>
                                        
                                        <div class="info-item">
                                            <span class="info-label">Cumple Manejo/Construcción:</span>
                                            <span class="badge bg-{{ $captacion->cumple_manejo_construccion ? 'success' : 'danger' }}">
                                                {{ $captacion->cumple_manejo_construccion ? '✅ Sí' : '❌ No' }}
                                            </span>
                                        </div>
                                        
                                        <div class="info-item">
                                            <span class="info-label">Gestionó Permiso Ocupación:</span>
                                            <span class="badge bg-{{ $captacion->gestion_permiso_ocupacion ? 'success' : 'danger' }}">
                                                {{ $captacion->gestion_permiso_ocupacion ? '✅ Sí' : '❌ No' }}
                                            </span>
                                        </div>
                                        
                                        <div class="info-item">
                                            <span class="info-label">Gestionó Permiso Captación:</span>
                                            <span class="badge bg-{{ $captacion->gestion_permiso_captacion ? 'success' : 'danger' }}">
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
                                            <div class="mt-1 p-2 bg-light rounded">
                                                {{ $captacion->observaciones }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="mt-3 d-flex justify-content-end">
                                    <a href="{{ route('aguaCaptacion.edit', $visita->id) }}" 
                                       class="btn btn-sm btn-warning me-2">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="{{ route('aguaCaptacion.create', $visita->id) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver completo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info mb-4">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Nota:</strong> No se ha registrado información para <strong>Agua - Captación Legal</strong>.
            <a href="{{ route('aguaCaptacion.create', $visita->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                Registrar ahora
            </a>
        </div>
    @endif

    {{-- ===================== SELECTOR DE SECCIONES ===================== --}}
    <div class="selector-seccion mb-3">
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
        </form>
    </div>

    {{-- ===================== SI YA EXISTE AGUA USO EFICIENTE ===================== --}}
    @if($visita->aguaUsoEficiente)
        @php $m = $visita->aguaUsoEficiente; @endphp

        <div class="card-component">
            <div class="card-header-green">Componente registrado</div>
            <div class="p-3">

                <p><strong>Plan de ahorro y uso eficiente:</strong> {{ $m->plan_ahorro ? 'Sí' : 'No' }}</p>
                <p><strong>Mantenimiento de sistemas de captación/distribución:</strong> {{ $m->mantenimiento_sistemas ? 'Sí' : 'No' }}</p>
                <p><strong>Uso de información técnica (balance hídrico, pluviómetro, etc.):</strong> {{ $m->uso_informacion_balance ? 'Sí' : 'No' }}</p>
                <p><strong>Mecanismos de medición del consumo:</strong> {{ $m->mecanismo_medicion ? 'Sí' : 'No' }}</p>

                @if(!empty($m->observaciones))
                    <p><strong>Observaciones:</strong> {{ $m->observaciones }}</p>
                @endif

                <div class="btn-group-top">
                    <a href="{{ route('aguaUso.edit', $visita->id) }}" class="btn btn-warning">✏️ Editar componente</a>
                    <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary">⬅️ Volver a la visita</a>
                </div>

            </div>
        </div>

    @else

    {{-- ===================== FORMULARIO DE CREACIÓN ===================== --}}
    <div class="card-component">
        <div class="card-header-green">Registrar — Agua: Uso Eficiente</div>

        <div class="p-3">
           <form action="{{ route('aguaUso.store', $visita->id) }}" method="POST">
                @csrf
                <input type="hidden" name="visita_ambiental_id" value="{{ $visita->id }}">

                <div class="mb-3">
                    <label for="plan_ahorro" class="form-label fw-bold">Cuenta con un plan de ahorro y uso eficiente del agua</label>
                    <select name="plan_ahorro" id="plan_ahorro" class="form-select">
                        <option value="">Seleccione...</option>
                        <option value="1" {{ old('plan_ahorro') == '1' ? 'selected' : '' }}>✅ Sí</option>
                        <option value="0" {{ old('plan_ahorro') == '0' ? 'selected' : '' }}>❌ No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="mantenimiento_sistemas" class="form-label fw-bold">Realiza mantenimiento a los sistemas de captación y distribución</label>
                    <select name="mantenimiento_sistemas" id="mantenimiento_sistemas" class="form-select">
                        <option value="">Seleccione...</option>
                        <option value="1" {{ old('mantenimiento_sistemas') == '1' ? 'selected' : '' }}>✅ Sí</option>
                        <option value="0" {{ old('mantenimiento_sistemas') == '0' ? 'selected' : '' }}>❌ No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="uso_informacion_balance" class="form-label fw-bold">Se basa en información como balance hídrico, pluviómetro, freatímetro</label>
                    <select name="uso_informacion_balance" id="uso_informacion_balance" class="form-select">
                        <option value="">Seleccione...</option>
                        <option value="1" {{ old('uso_informacion_balance') == '1' ? 'selected' : '' }}>✅ Sí</option>
                        <option value="0" {{ old('uso_informacion_balance') == '0' ? 'selected' : '' }}>❌ No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="mecanismo_medicion" class="form-label fw-bold">Tiene mecanismo de medición y registro del consumo de agua</label>
                    <select name="mecanismo_medicion" id="mecanismo_medicion" class="form-select" onchange="mostrarCamposMedicion(this.value)">
                        <option value="">Seleccione...</option>
                        <option value="1" {{ old('mecanismo_medicion') == '1' ? 'selected' : '' }}>✅ Sí</option>
                        <option value="0" {{ old('mecanismo_medicion') == '0' ? 'selected' : '' }}>❌ No</option>
                    </select>
                </div>

                <!-- Campos adicionales que se muestran solo cuando se selecciona "Sí" -->
                <div id="campos-medicion" style="display: {{ old('mecanismo_medicion') == '1' ? 'block' : 'none' }};">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="consumo_agua" class="form-label fw-bold">Consumo de agua (m³/mes)</label>
                            <div class="input-group">
                                <input type="number" name="consumo_agua" id="consumo_agua" 
                                       class="form-control" 
                                       value="{{ old('consumo_agua') }}"
                                       step="0.01" 
                                       min="0"
                                       placeholder="Ej: 120.5">
                                <span class="input-group-text">m³/mes</span>
                            </div>
                            <small class="text-muted">Ingrese el consumo mensual promedio de agua</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="metodo_medicion" class="form-label fw-bold">Método de medición</label>
                            <select name="metodo_medicion" id="metodo_medicion" class="form-select">
                                <option value="">Seleccione método...</option>
                                <option value="contador_agua">📊 Contador de agua</option>
                                <option value="medidor_volumen">⚖️ Medidor de volumen</option>
                                <option value="estimacion_manual">📝 Estimación manual</option>
                                <option value="sistema_automatico">🤖 Sistema automático</option>
                                <option value="lectura_mensual">📅 Lectura mensual</option>
                                <option value="otro" {{ old('metodo_medicion') == 'otro' ? 'selected' : '' }}>🔧 Otro método</option>
                            </select>
                        </div>
                    </div>

                    <!-- Campo adicional si selecciona "Otro método" -->
                    <div class="mb-3" id="otro-metodo-container" style="display: {{ old('metodo_medicion') == 'otro' ? 'block' : 'none' }};">
                        <label for="otro_metodo_medicion" class="form-label fw-bold">Especifique otro método de medición</label>
                        <input type="text" name="otro_metodo_medicion" id="otro_metodo_medicion" 
                               class="form-control" 
                               value="{{ old('otro_metodo_medicion') }}"
                               placeholder="Describa el método de medición utilizado">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                    <textarea name="observaciones" id="observaciones" rows="3" class="form-control">{{ old('observaciones') }}</textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary">⬅️ Volver</a>
                    <button type="submit" class="btn btn-success">Guardar componente</button>
                </div>
            </form>

            <script>
            function mostrarCamposMedicion(valor) {
                const camposMedicion = document.getElementById('campos-medicion');
                
                if (valor === '1') {
                    camposMedicion.style.display = 'block';
                    document.getElementById('consumo_agua').required = true;
                    document.getElementById('metodo_medicion').required = true;
                } else {
                    camposMedicion.style.display = 'none';
                    document.getElementById('consumo_agua').required = false;
                    document.getElementById('metodo_medicion').required = false;
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const metodoMedicionSelect = document.getElementById('metodo_medicion');
                const otroMetodoContainer = document.getElementById('otro-metodo-container');
                
                if (metodoMedicionSelect) {
                    metodoMedicionSelect.addEventListener('change', function() {
                        if (this.value === 'otro') {
                            otroMetodoContainer.style.display = 'block';
                            document.getElementById('otro_metodo_medicion').required = true;
                        } else {
                            otroMetodoContainer.style.display = 'none';
                            document.getElementById('otro_metodo_medicion').required = false;
                        }
                    });
                }
                
                const mecanismoMedicion = document.getElementById('mecanismo_medicion');
                if (mecanismoMedicion) {
                    mostrarCamposMedicion(mecanismoMedicion.value);
                }
            });
            </script>
        </div>
    </div>
    @endif
</div>
@endsection
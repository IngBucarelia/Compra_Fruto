@extends('layouts.app')

@section('content')
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
</style>

<div class="container offline-form-container">

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

    {{-- ===================== SI YA EXISTE EL COMPONENTE ===================== --}}
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

                <div class="mb-2 form-check">
                    <input type="checkbox" name="plan_ahorro" id="plan_ahorro" class="form-check-input" value="1">
                    <label for="plan_ahorro" class="form-check-label">Cuenta con un plan de ahorro y uso eficiente del agua</label>
                </div>

                <div class="mb-2 form-check">
                    <input type="checkbox" name="mantenimiento_sistemas" id="mantenimiento_sistemas" class="form-check-input" value="1">
                    <label for="mantenimiento_sistemas" class="form-check-label">Realiza mantenimiento a los sistemas de captación y distribución</label>
                </div>

                <div class="mb-2 form-check">
                    <input type="checkbox" name="uso_informacion_balance" id="uso_informacion_balance" class="form-check-input" value="1">
                    <label for="uso_informacion_balance" class="form-check-label">Se basa en información como balance hídrico, pluviómetro, freatímetro</label>
                </div>

                <div class="mb-2 form-check">
                    <input type="checkbox" name="mecanismo_medicion" id="mecanismo_medicion" class="form-check-input" value="1">
                    <label for="mecanismo_medicion" class="form-check-label">Tiene mecanismo de medición y registro del consumo de agua</label>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Observaciones</label>
                    <textarea name="observaciones" rows="3" class="form-control"></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary">⬅️ Volver</a>
                    <button type="submit" class="btn btn-success">Guardar componente</button>
                </div>

            </form>
        </div>

    </div>

    @endif
</div>
@endsection

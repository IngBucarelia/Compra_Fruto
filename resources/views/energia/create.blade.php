@extends('layouts.app')

@section('content')

<style>
    body{
        background-image: url('{{ asset('images/fondo_ambiental.png') }}');
    }

    .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929);
        padding: 22px;
        border-radius: 14px;
        margin-top: 20px;
        margin-bottom: 25px;
    }

    .title {
        text-align: center;
        font-family: Arial Black, sans-serif;
        font-weight: bold;
        font-size: 28px;
        color: #fdffe5;
        text-shadow: -1px 0 #000, 0 1px #000;
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

    .btn-group-bottom {
        display:flex;
        justify-content: space-between;
        margin-top:20px;
    }

</style>

<div class="container offline-form-container">

    <h3 class="title">Energía – Uso Eficiente (Crear Registro)</h3>
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

    <div class="card-component">
        <div class="card-header-green">
            Registro de Uso Eficiente de la Energía
        </div>

        <form action="{{ route('energia.store') }}" method="POST">
            @csrf

            <input type="hidden" name="visita_ambiental_id" value="{{ $visitaId }}">

            <div class="mb-3">
                <label class="fw-bold">¿Cuenta con registro de consumo de combustible e implementa acciones para optimizar su uso?</label>
                <select name="registro_consumo_combustible" class="form-control" required>
                    <option value="">Seleccione…</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-bold">¿Tiene implementado un plan para el uso eficiente de la energía?</label>
                <select name="plan_uso_eficiente" class="form-control" required>
                    <option value="">Seleccione…</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-bold">¿Realiza seguimiento a los indicadores energéticos?</label>
                <select name="seguimiento_indicadores" class="form-control" required>
                    <option value="">Seleccione…</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3"></textarea>
            </div>

            <div class="btn-group-bottom">
                <a href="{{ route('visitasAmbientales.show', $visitaId) }}" class="btn btn-secondary">
                    Volver a la visita
                </a>

                <button class="btn btn-success">
                    Guardar registro
                </button>
            </div>

        </form>
    </div>

</div>

@endsection

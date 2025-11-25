@extends('layouts.app')

@section('content')
<div class="container" style="background-color: whitesmoke; border-radius:30px">

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
</style>

<h2 class="title">Conservación, Prevención de la Erosión y Ecología del Suelo</h2>

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
    <div class="card-header-green">Formulario de Registro</div>

    <form action="{{ route('suelo_conservacion.store') }}" method="POST">
        @csrf

        <input type="hidden" name="visita_ambiental_id" value="{{ $visita->id }}">

        <div class="form-check mb-2">
            <input type="checkbox" name="usa_fuego_preparacion" class="form-check-input" id="fuego">
            <label class="form-check-label" for="fuego">
                ¿Ha usado fuego para preparar el terreno?
            </label>
        </div>

        <div class="form-check mb-2">
            <input type="checkbox" name="control_coberturas_invasoras" class="form-check-input" id="coberturas">
            <label class="form-check-label" for="coberturas">
                ¿Realiza control de coberturas invasoras?
            </label>
        </div>

        <div class="form-check mb-2">
            <input type="checkbox" name="siembra_coberturas" class="form-check-input" id="siembra">
            <label class="form-check-label" for="siembra">
                ¿Siembra coberturas para manejo del suelo?
            </label>
        </div>

        <div class="form-check mb-2">
            <input type="checkbox" name="sigue_recomendaciones_comerciales" class="form-check-input" id="recomendaciones">
            <label class="form-check-label" for="recomendaciones">
                ¿Sigue recomendaciones de casas comerciales?
            </label>
        </div>

        <button type="submit" class="btn btn-success mt-3">Guardar</button>
        <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary mt-3">
            Volver a la visita
        </a>
    </form>
</div>

</div>
@endsection

@extends('layouts.app')

@section('content')
<style>
    body{
        background-image: url('{{ asset('images/fondo_ambiental.png') }}');
    }
    .container.offline-form-container {
        background-color: rgba(129,165,114,0.929);
        padding: 20px;
        border-radius: 12px;
    }
    .title { text-align:center; font-weight:bold; color:#fdffe5; text-shadow:-1px 0 #000, 0 1px #000; }
    .card-component { background:#f1f6f1; border-radius:10px; padding:18px; border:1px solid #d5e6d5; }
    .card-header-green {
        background: linear-gradient(45deg,#28a745,#20c997);
        color:white; padding:12px; border-radius:8px 8px 0 0; margin:-18px -18px 12px -18px; font-weight:700;
    }
</style>

<div class="container offline-form-container">

    <h3 class="title">Gobernanza Hídrica — Visita #{{ $visita->id }}</h3>
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

    <div class="card-component">
        <div class="card-header-green">Registrar — Gobernanza del Recurso Hídrico</div>

        <form action="{{ route('gobernanza.store', $visita->id) }}" method="POST">
            @csrf

            <div class="mb-2 form-check">
                <input type="checkbox" name="canales_comunicacion" class="form-check-input" id="cc">
                <label class="form-check-label" for="cc">
                    ¿Establece canales de comunicación con los actores identificados en su comunidad?
                </label>
            </div>

            <div class="mb-2 form-check">
                <input type="checkbox" name="identifica_actores_afectados" class="form-check-input" id="iaa">
                <label class="form-check-label" for="iaa">
                    ¿Identifica actores de su comunidad que se pueden ver afectados por el uso del agua?
                </label>
            </div>

            <div class="mb-2 form-check">
                <input type="checkbox" name="participa_actividades_gestion" class="form-check-input" id="pag">
                <label class="form-check-label" for="pag">
                    ¿Participa en actividades para impulsar la gestión integral del recurso hídrico?
                </label>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary">Volver a la visita</a>
                <button type="submit" class="btn btn-success">Guardar componente</button>
            </div>
        </form>
    </div>

</div>
@endsection

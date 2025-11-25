@extends('layouts.app')

@section('content')
<div class="container offline-form-container" style="background-color: whitesmoke; border-radius:30px">

<style>
    body {
        background-image: url('{{ asset('images/fondo_ambiental.png') }}');
    }
    .offline-form-container {
        background-color: rgba(129,165,114,0.929);
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
        padding: 18px;
        border-radius:10px;
        margin-bottom: 18px;
    }
</style>

<h2 class="title">Manejo de Vertimientos</h2>
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
<form action="{{ route('vertimientos.store') }}" method="POST">
    @csrf

    <input type="hidden" name="visita_ambiental_id" value="{{ $visitaId }}">

    <div class="card-component">
        <label>¿Cuenta con permiso(s) de vertimientos?</label>
        <select name="permiso_vertimientos" class="form-control">
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
    </div>

    <div class="card-component">
        <label>¿Cuenta con sistemas de tratamiento de agua residual doméstica?</label>
        <select name="sistema_agua_domestica" class="form-control">
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
    </div>

    <div class="card-component">
        <label>¿Cuenta con sistemas de tratamiento de agua residual no doméstica?</label>
        <select name="sistema_agua_no_domestica" class="form-control">
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
    </div>

    <div class="card-component">
        <label>¿Cuenta con sistemas de tratamiento de aguas residuales para agroquímicos?</label>
        <select name="sistema_agroquimicos" class="form-control">
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
    </div>

    <div class="card-component">
        <label>¿Cumple con la obligación del permiso de vertimientos?</label>
        <select name="cumple_permiso" class="form-control">
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
    </div>

    <div class="card-component">
        <label>¿Ha gestionado la obtención del permiso?</label>
        <select name="gestion_permiso" class="form-control">
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
    </div>

    <div class="card-component">
        <label>¿Realiza actividades de triple lavado?</label>
        <select name="triple_lavado" class="form-control">
            <option value="">Seleccione…</option>
            <option value="1">Sí</option>
            <option value="0">No</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Observaciones</label>
        <textarea class="form-control" name="observaciones"></textarea>
    </div>

    <button class="btn btn-primary">Guardar</button>
</form>

<br><br>
<a href="{{ route('visitasAmbientales.show', $visitaId) }}" class="btn btn-secondary">Volver a la visita</a>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container offline-form-container" style="border-radius:20px; margin-top:15px;">
<style>
    body{
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
</style>

<h2 class="title">Planificación Ambiental – No Reemplazo y No Deforestación</h2>
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
<form action="{{ route('pno_reemplazo.store') }}" method="POST">
    @csrf

    <input type="hidden" name="visita_ambiental_id" value="{{ $visita->id }}">

    <div class="card-component">

        <label>¿Cuenta con estudios de AVC y ARC?</label>
        <select name="cuenta_estudios_avc_arc" class="form-control" required>
            <option value="">Seleccione…</option>
            <option value="1">Sí</option><option value="0">No</option>
        </select><br>

        <label>¿Cuenta con evidencias de no haber reemplazado bosques?</label>
        <select name="evidencias_no_reemplazo_bosques" class="form-control" required>
            <option value="">Seleccione…</option>
            <option value="1">Sí</option><option value="0">No</option>
        </select><br>

        <label>¿Cuenta con permiso de aprovechamiento forestal?</label>
        <select name="permiso_aprovechamiento_forestal" class="form-control" required>
            <option value="">Seleccione…</option>
            <option value="1">Sí</option><option value="0">No</option>
        </select><br>

        <label>¿Ha realizado restauración o compensación?</label>
        <select name="restauracion_compensacion" class="form-control" required>
            <option value="">Seleccione…</option>
            <option value="1">Sí</option><option value="0">No</option>
        </select><br>

        <label>¿La plantación está dentro de la frontera agrícola?</label>
        <select name="dentro_frontera_agricola" class="form-control" required>
            <option value="">Seleccione…</option>
            <option value="1">Sí</option><option value="0">No</option>
        </select><br>

        <label>Observaciones</label>
        <textarea name="observaciones" class="form-control"></textarea>
    </div>

    <br>
    <button class="btn btn-primary">Guardar</button>
</form>

<br>
<a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary">Volver a la visita</a>

</div>
@endsection

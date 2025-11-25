@extends('layouts.app')

@section('content')
<div class="container form-box">

    <h3 class="title">Diseño y Manejo de la Plantación para la Protección de AVC</h3>
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
    <form action="{{ route('plantacion_avc.store') }}" method="POST">
        @csrf

        <input type="hidden" name="visita_ambiental_id" value="{{ $visita->id }}">

        <table class="table table-bordered custom-table">
            <tr>
                <th>¿Cuentan con registros de avistamientos y monitoreo?</th>
                <td>
                    <select name="registros_avistamientos" class="form-control">
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>¿Ha identificado en su predio AVC y ARC?</th>
                <td>
                    <select name="identifica_avc_arc" class="form-control">
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>¿Implementa medidas de manejo para conservación de AVC y ARC?</th>
                <td>
                    <select name="implementa_medidas_manejo" class="form-control">
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Observaciones</th>
                <td>
                    <textarea name="observaciones" class="form-control"></textarea>
                </td>
            </tr>
        </table>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary">Volver</a>
    </form>

</div>

<style>
.form-box {
    background: whitesmoke;
    padding: 20px;
    border-radius: 18px;
}

.title {
    text-align: center;
    font-size: 26px;
    font-weight: bold;
    color: #2f6e3e;
    margin-bottom: 25px;
}

.custom-table th {
    background: #e3efe3;
    width: 55%;
}

.custom-table td {
    background: white;
}
</style>
@endsection

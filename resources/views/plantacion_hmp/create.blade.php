@extends('layouts.app')

@section('content')
<div class="container" style="background-color: whitesmoke; border-radius:30px; padding:20px;">

<style>
    body{
        background-image: url('{{ asset('images/fondo_ambiental.png') }}');
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
        font-size: 20px;
    }
    table.table-custom {
        background: #ffffff;
        border-radius: 10px;
    }
    table.table-custom th {
        background: #e4f3e2;
        color: #2f4f2f;
        font-weight: 600;
        width: 45%;
    }
    table.table-custom td {
        background: #f8fff8;
    }
</style>

<h2 class="title">Diseño y Manejo de la Plantación – HMP</h2>
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
    <div class="card-header-green">Formulario de Registro</div>

    <form action="{{ route('plantacion_hmp.store') }}" method="POST">
        @csrf

        <input type="hidden" name="visita_ambiental_id" value="{{ $visita->id }}">

        <table class="table table-bordered table-custom">

            <tr>
                <th>¿Implementa las HMP según el diseño realizado?</th>
                <td>
                    <select name="implementa_hmp" class="form-control">
                        <option value="">Seleccione…</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>¿Incluye HMP en el diseño o rediseño de la plantación según el predio?</th>
                <td>
                    <select name="incluye_hmp_disenio" class="form-control">
                        <option value="">Seleccione…</option>
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

        <button class="btn btn-success mt-3">Guardar</button>
        <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary mt-3">Volver</a>

    </form>
</div>

</div>
@endsection

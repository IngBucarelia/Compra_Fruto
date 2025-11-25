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
        overflow: hidden;
    }
    table.table-custom th {
        background: #e4f3e2;
        color: #2f4f2f;
        font-weight: 600;
        width: 45%;
        vertical-align: middle;
    }
    table.table-custom td {
        background: #f8fff8;
    }
</style>

<h2 class="title">Manejo de Residuos Ordinarios y Peligrosos</h2>

<div class="card-component">
    <div class="card-header-green">Formulario de Registro</div>

    <form action="{{ route('manejo_residuos.store') }}" method="POST">
        @csrf

        <table class="table table-bordered table-custom">
            <tr>
                <th>Visita Ambiental</th>
                <td>
                    <input type="number" name="visita_ambiental_id" value="{{ $visitaId ?? '' }}" class="form-control" required>
                </td>
            </tr>

            <tr>
                <th>Capacitación al personal</th>
                <td>
                    <select name="capacitacion_personal" class="form-control">
                        <option value="">Seleccione…</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Conoce clasificación residuos</th>
                <td>
                    <select name="conoce_clasificacion_residuos" class="form-control">
                        <option value="">Seleccione…</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Certificados RESPEL</th>
                <td>
                    <select name="certificados_respel" class="form-control">
                        <option value="">Seleccione…</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Manifiesto transporte RESPEL</th>
                <td>
                    <select name="manifiesto_transporte_respel" class="form-control">
                        <option value="">Seleccione…</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Puntos ecológicos</th>
                <td>
                    <select name="puntos_ecologicos" class="form-control">
                        <option value="">Seleccione…</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Entrega residuos a transportador autorizado</th>
                <td>
                    <select name="entrega_residuos_transportador_autorizado" class="form-control">
                        <option value="">Seleccione…</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Disposición final empresa autorizada</th>
                <td>
                    <select name="disposicion_final_empresa_autorizada" class="form-control">
                        <option value="">Seleccione…</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Acciones para minimizar impactos</th>
                <td>
                    <select name="acciones_minimizacion_impactos" class="form-control">
                        <option value="">Seleccione…</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Certificado disposición final</th>
                <td>
                    <select name="certificado_disposicion_final" class="form-control">
                        <option value="">Seleccione…</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Aprovechables gestionados</th>
                <td>
                    <select name="aprovechables_gestionados" class="form-control">
                        <option value="">Seleccione…</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Pesa y registra cantidades</th>
                <td>
                    <select name="pesa_registra_cantidades" class="form-control">
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
        <a href="{{ route('visitasAmbientales.show', $visitaId ?? 0) }}" class="btn btn-secondary mt-3">
            Volver a la visita
        </a>
    </form>
</div>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('redireccion_seccion_social', $visita->id) }}" method="GET" class="mt-4">
            <label for="seccion" class="form-label fw-bold text-success">📋 Ir a sección:</label>
            <div class="input-group">
                <select id="seccion" name="seccion" class="form-select" required>
                    <option value="">Seleccione una sección</option>
                    @if ($visita->estado === 'pendiente' || $visita->estado === 'en_ejecucion')
                        <option value="inicio"> Pagina de Inicio de Visita</option>
                        <option value="datos_personales">👤 Datos Personales</option>
                        <option value="miembros">👨‍👩‍👧‍👦 Miembros del Hogar</option>
                        <option value="predio">🏡 Datos del Predio</option>
                        <option value="fuerza_laboral">🧑‍🌾 Fuerza Laboral</option>
                        <option value="organizacion_social">👥 Organización Social</option>
                    @endif
                </select>
                <button type="submit" class="btn btn-success">Ir</button>
            </div>
        </form>
    <h3 class="text-info fw-bold mb-4">👁 Detalle Fuerza Laboral #{{ $fuerza->id }}</h3>

    <div class="card p-4 shadow-sm border-0 rounded-3 bg-light">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Forma de contratación:</strong> {{ implode(', ', $fuerza->forma_contratacion ?? []) }}</li>
            <li class="list-group-item"><strong>Trabajadores:</strong> {{ $fuerza->num_trabajadores }}</li>
            <li class="list-group-item"><strong>Hombres:</strong> {{ $fuerza->num_hombres }}</li>
            <li class="list-group-item"><strong>Mujeres:</strong> {{ $fuerza->num_mujeres }}</li>
            <li class="list-group-item"><strong>Contrato formal:</strong> {{ $fuerza->contrato_formal }}</li>
            <li class="list-group-item"><strong>Seguridad social:</strong> {{ $fuerza->seguridad_social }}</li>
            <li class="list-group-item"><strong>Tipo contrato:</strong> {{ $fuerza->tipo_contrato }}</li>
            <li class="list-group-item"><strong>Contrato firmado:</strong> {{ $fuerza->contrato_firmado }}</li>
            <li class="list-group-item"><strong>SG-SST:</strong> {{ $fuerza->sg_sst }}</li>
            <li class="list-group-item"><strong>Exámenes médicos:</strong> {{ $fuerza->examenes_medicos }}</li>
            <li class="list-group-item"><strong>Trabajadores migrantes:</strong> {{ $fuerza->trabajadores_migrantes }}</li>
            <li class="list-group-item"><strong>Comprobantes de pago:</strong> {{ $fuerza->comprobantes_pago }}</li>
            <li class="list-group-item"><strong>Dotación:</strong> {{ $fuerza->dotacion }}</li>
        </ul>
    </div>

    <a href="{{ route('fuerza_laboral.index', $visita->id) }}" class="btn btn-secondary mt-3">⬅ Volver</a>
</div>
<style>
    @media (max-width: 968px) {

         .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
    }
        .button-group-top {
            flex-direction: row;
            justify-content: flex-start;
        }

         .container.offline-form-container {
        padding: 15px;
            margin-top: 15px;
            border-radius: 0;
            box-shadow: none;
            width: 123%;
            max-width: none;
            margin-left: -60px !important;
    }

    .title{
    text-align: center;
    font-family: Arial Black;
    font-weight: bold;
    font-size: 30px;
    color: #fdffe5;
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    margin-bottom: 25px;
}

 .container {
        margin-left: -70px;
        width: 125%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }

        .card{
        width: 100%;
    }
    }
</style>
@endsection

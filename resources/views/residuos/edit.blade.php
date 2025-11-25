@extends('layouts.app')

@section('content')
<div class="container offline-form-container" style="background-color: whitesmoke; border-radius:30px">
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
</style>

<h2 class="title">Editar Manejo de Residuos</h2>

<form action="{{ route('residuos.update', $registro->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card-component">
        <div class="card-header-green">Evaluación de Manejo de Residuos</div>

        @php
            $preguntas = [
                'capacita_personal' => '¿Capacita al personal para el manejo integral de residuos según su naturaleza?',
                'conoce_diferencias' => '¿Conoce la diferencia entre un residuo peligroso/no peligroso?',
                'certificado_final_respel' => '¿Cuenta con certificados de disposición final RESPEL?',
                'manifiesto_transporte_respel' => '¿Cuenta con el manifiesto de transporte de RESPEL?',
                'puntos_ecologicos' => '¿Cuenta con puntos ecológicos para almacenamiento según su naturaleza?',
                'entrega_transportador_aut' => '¿Entrega residuos peligrosos a transportador autorizado?',
                'disposicion_empresa_aut' => '¿Gestiona disposición final con empresa autorizada?',
                'acciones_minimizar_impacto' => '¿Implementa acciones para minimizar impactos?',
                'certificado_relleno_sanitario' => '¿Cuenta con certificado de disposición final en relleno sanitario?',
                'residuos_aprovechables_gestion' => '¿Gestiona adecuadamente residuos aprovechables?',
                'pesa_y_registra' => '¿Pesa y registra las cantidades de residuos generados?',
            ];
        @endphp

        @foreach ($preguntas as $campo => $texto)
            <div class="mb-3">
                <label>{{ $texto }}</label>
                <select name="{{ $campo }}" class="form-control" required>
                    <option value="">Seleccione…</option>
                    <option value="1" {{ $registro->$campo == 1 ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ $registro->$campo == 0 ? 'selected' : '' }}>No</option>
                </select>
            </div>
        @endforeach

        <div class="mb-3">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control">{{ $registro->observaciones }}</textarea>
        </div>
    </div>

    <button class="btn btn-primary">Actualizar</button>
</form>

<br>

<a href="{{ route('visitasAmbientales.show', $registro->visita_ambiental_id) }}" class="btn btn-secondary">
    ⬅️ Volver a la visita
</a>

</div>
@endsection

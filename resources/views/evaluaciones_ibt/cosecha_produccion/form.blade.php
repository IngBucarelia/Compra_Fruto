@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-9">

        {{-- HEADER --}}
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between">
                <h4 class="mb-0">🧺 IBT – Cosecha y Producción</h4>
                <span class="badge bg-light text-dark fs-6">
                Evaluación #{{ $evaluacion->id }}
                </span>
            </div>
        </div>

        {{-- INFO --}}
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"><strong>Proveedor:</strong> {{ $evaluacion->proveedor->proveedor_nombre ?? 'N/A' }}</div>
                    <div class="col-md-4"><strong>Plantación:</strong> {{ $evaluacion->plantacion->nombre ?? 'N/A' }}</div>
            <div class="col-md-4"><strong>Evaluador:</strong> {{ $evaluacion->tecnico->name ?? 'N/A' }}</div>
        </div>
        </div>
        </div>

                <form method="POST"
                action="{{ isset($registro)
                    ? route('ibt.cosecha_produccion.update',$registro->id)
                    : route('ibt.cosecha_produccion.store') }}">

                @csrf
                @if(isset($registro)) @method('PUT') @endif
                <input type="hidden" name="evaluacion_ibt_id" value="{{ $evaluacion->id }}">

                <div class="card shadow mb-4">
                <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                <thead class="table-light">
                <tr class="text-center">
                <th>Componente</th>
                <th>Calificación</th>
                <th>Máx</th>
                </tr>
                </thead>
                <tbody>

                @php
                $campos = [
                'criterio_ciclo_cosecha' => 3,
                'recoleccion_fruto' => 3,
                'calidad_fruto_cosechado' => 3,
                'produccion' => 6,
                ];
                @endphp

                @foreach($campos as $campo => $max)
                <tr>
                <td>{{ ucwords(str_replace('_',' ',$campo)) }}</td>
                <td>
                <input type="number"
                name="{{ $campo }}"
                min="0" max="{{ $max }}"
                class="form-control text-center"
                value="{{ old($campo, $registro->$campo ?? '') }}"
                required>
                </td>
                <td class="text-center fw-bold">{{ $max }}</td>
                </tr>
                @endforeach

                <tr class="table-secondary">
                <th>Total</th>
                <th colspan="2" class="text-center">Máx 15 puntos</th>
                </tr>

                </tbody>
                </table>
                </div>
                </div>

                <div class="text-center">
                <button class="btn btn-success btn-lg">
                💾 {{ isset($registro) ? 'Actualizar' : 'Guardar' }}
                </button>

                <a href="{{ route('evaluaciones-ibt.show',$evaluacion->id) }}"
                class="btn btn-outline-secondary btn-lg">
                ⬅️ Volver
                </a>
                </div>

                </form>

        </div>
    </div>
</div>
@endsection

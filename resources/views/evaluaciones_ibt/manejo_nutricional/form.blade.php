@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-9">

            {{-- HEADER --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        🥗 IBT – Manejo Nutricional
                    </h4>
                    <span class="badge bg-light text-dark fs-6">
                        Evaluación #{{ $evaluacion->id }}
                    </span>
                </div>
            </div>

            {{-- INFO EVALUACIÓN --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 text-success">
                        Información de la Evaluación
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label class="fw-bold">Proveedor</label>
                            <div class="p-2 bg-light rounded">
                                {{ $evaluacion->proveedor->proveedor_nombre ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="fw-bold">Plantación</label>
                            <div class="p-2 bg-light rounded">
                                {{ $evaluacion->plantacion->nombre ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="fw-bold">Evaluador</label>
                            <div class="p-2 bg-light rounded">
                               {{ $evaluacion->tecnico->name ?? 'N/A' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- FORMULARIO --}}
            <form method="POST"
                action="{{ isset($registro)
                    ? route('ibt.manejo_nutricional.update', $registro->id)
                    : route('ibt.manejo_nutricional.store') }}">

                @csrf
                @if(isset($registro))
                    @method('PUT')
                @endif

                <input type="hidden" name="evaluacion_ibt_id" value="{{ $evaluacion->id }}">

                <div class="card shadow mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-success">
                            Criterios de Evaluación
                        </h5>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th style="width:60%">Componente</th>
                                    <th style="width:20%">Calificación</th>
                                    <th style="width:20%">Máx.</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                $campos = [
                                    'toma_muestra_foliares' => 3,
                                    'toma_muestras_suelos' => 3,
                                    'censo_produccion' => 3,
                                    'eficacia_fertilizacion' => 6,
                                    'fraccionamiento_fertilizacion' => 4,
                                    'epoca_fertilizacion' => 4,
                                    'medicion_crecimiento' => 2,
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
                                    <th>Total del componente</th>
                                    <th colspan="2" class="text-center">
                                        Máximo <strong>25 puntos</strong>
                                    </th>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="text-center">
                    <button class="btn btn-success btn-lg me-2">
                        💾 {{ isset($registro) ? 'Actualizar componente' : 'Guardar componente' }}
                    </button>

                    <a href="{{ route('evaluaciones-ibt.show', $evaluacion->id) }}"
                       class="btn btn-outline-secondary btn-lg">
                        ⬅️ Volver
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

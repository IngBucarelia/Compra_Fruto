@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-9">

            {{-- HEADER --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        🌱 IBT – Establecimiento de Cultivo
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
                        ? route('ibt.establecimiento_cultivo.update', $registro->id)
                        : route('ibt.establecimiento_cultivo.store') }}">

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
                                    <th style="width:60%">Fase / Componente agronómico</th>
                                    <th style="width:20%">Calificación</th>
                                    <th style="width:20%">Máx.</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>Estudios de caracterización de suelos y condiciones climatológicas</td>
                                    <td>
                                        <input type="number" name="estudios_caracterizacion_suelos"
                                               min="0" max="2"
                                               class="form-control text-center"
                                               value="{{ old('estudios_caracterizacion_suelos', $registro->estudios_caracterizacion_suelos ?? '') }}"
                                               required>
                                    </td>
                                    <td class="text-center fw-bold">2</td>
                                </tr>

                                <tr>
                                    <td>Estudios topográficos</td>
                                    <td>
                                        <input type="number" name="estudios_topograficos"
                                               min="0" max="2"
                                               class="form-control text-center"
                                               value="{{ old('estudios_topograficos', $registro->estudios_topograficos ?? '') }}"
                                               required>
                                    </td>
                                    <td class="text-center fw-bold">2</td>
                                </tr>

                                <tr>
                                    <td>Diseño de riegos y drenajes</td>
                                    <td>
                                        <input type="number" name="diseno_riegos_drenajes"
                                               min="0" max="6"
                                               class="form-control text-center"
                                               value="{{ old('diseno_riegos_drenajes', $registro->diseno_riegos_drenajes ?? '') }}"
                                               required>
                                    </td>
                                    <td class="text-center fw-bold">6</td>
                                </tr>

                                <tr>
                                    <td>Diseño de Unidades de Manejo Agronómico (UMA)</td>
                                    <td>
                                        <input type="number" name="diseno_uma"
                                               min="0" max="3"
                                               class="form-control text-center"
                                               value="{{ old('diseno_uma', $registro->diseno_uma ?? '') }}"
                                               required>
                                    </td>
                                    <td class="text-center fw-bold">3</td>
                                </tr>

                                <tr>
                                    <td>Preparación de suelos</td>
                                    <td>
                                        <input type="number" name="preparacion_suelos"
                                               min="0" max="4"
                                               class="form-control text-center"
                                               value="{{ old('preparacion_suelos', $registro->preparacion_suelos ?? '') }}"
                                               required>
                                    </td>
                                    <td class="text-center fw-bold">4</td>
                                </tr>

                                <tr>
                                    <td>Establecimiento de leguminosas de cobertura</td>
                                    <td>
                                        <input type="number" name="leguminosas_cobertura"
                                               min="0" max="3"
                                               class="form-control text-center"
                                               value="{{ old('leguminosas_cobertura', $registro->leguminosas_cobertura ?? '') }}"
                                               required>
                                    </td>
                                    <td class="text-center fw-bold">3</td>
                                </tr>

                                <tr class="table-secondary">
                                    <th>Total del componente</th>
                                    <th colspan="2" class="text-center">
                                        Máximo <strong>20 puntos</strong>
                                    </th>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="text-center">
                    <button class="btn btn-success btn-lg me-2">
                        💾 Guardar componente
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

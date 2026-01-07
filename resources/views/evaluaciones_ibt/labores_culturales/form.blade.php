@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-9">

            {{-- HEADER --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        🚜 IBT – Labores Culturales
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
                        ? route('ibt.labores_culturales.update', $registro->id)
                        : route('ibt.labores_culturales.store') }}">

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
                                    <th style="width:60%">Criterio</th>
                                    <th style="width:20%">Calificación</th>
                                    <th style="width:20%">Máx.</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>Limpieza de platos</td>
                                    <td>
                                        <input type="number" name="limpieza_platos"
                                               value="{{ old('limpieza_platos', $registro->limpieza_platos ?? '') }}"
                                               min="0" max="2"
                                               class="form-control text-center" required>
                                    </td>
                                    <td class="text-center fw-bold">2</td>
                                </tr>

                                <tr>
                                    <td>Limpieza de interlíneas</td>
                                    <td>
                                        <input type="number" name="limpieza_interlineas"
                                               value="{{ old('limpieza_interlineas', $registro->limpieza_interlineas ?? '') }}"
                                               min="0" max="2"
                                               class="form-control text-center" required>
                                    </td>
                                    <td class="text-center fw-bold">2</td>
                                </tr>

                                <tr>
                                    <td>Poda</td>
                                    <td>
                                        <input type="number" name="poda"
                                               value="{{ old('poda', $registro->poda ?? '') }}"
                                               min="0" max="2"
                                               class="form-control text-center" required>
                                    </td>
                                    <td class="text-center fw-bold">2</td>
                                </tr>

                                <tr>
                                    <td>Polinización</td>
                                    <td>
                                        <input type="number" name="polinizacion"
                                               value="{{ old('polinizacion', $registro->polinizacion ?? '') }}"
                                               min="0" max="8"
                                               class="form-control text-center" required>
                                    </td>
                                    <td class="text-center fw-bold">8</td>
                                </tr>

                                <tr>
                                    <td>Disposición de hojas podadas</td>
                                    <td>
                                        <input type="number" name="disposicion_hojas_podadas"
                                               value="{{ old('disposicion_hojas_podadas', $registro->disposicion_hojas_podadas ?? '') }}"
                                               min="0" max="4"
                                               class="form-control text-center" required>
                                    </td>
                                    <td class="text-center fw-bold">4</td>
                                </tr>

                                <tr>
                                    <td>Mantenimiento de infraestructura</td>
                                    <td>
                                        <input type="number" name="mantenimiento_infraestructura"
                                               value="{{ old('mantenimiento_infraestructura', $registro->mantenimiento_infraestructura ?? '') }}"
                                               min="0" max="2"
                                               class="form-control text-center" required>
                                    </td>
                                    <td class="text-center fw-bold">2</td>
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

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="col-md-6 mx-auto">

        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">✏️ Editar Calificación Final IBT</h5>
            </div>

            <form method="POST"
                  action="{{ route('evaluaciones-ibt.actualizar-calificacion', $evaluacion->id) }}">
                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="mb-3">
                        <label class="fw-bold">Puntaje final</label>
                        <input type="number"
                               name="puntaje_total"
                               class="form-control"
                               value="{{ $evaluacion->puntaje_total }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="en_proceso" {{ $evaluacion->estado === 'en_proceso' ? 'selected' : '' }}>
                                En proceso
                            </option>
                            <option value="terminado" {{ $evaluacion->estado === 'terminado' ? 'selected' : '' }}>
                                Terminado
                            </option>
                        </select>
                    </div>

                </div>

                <div class="card-footer text-center">
                    <button class="btn btn-success">
                        💾 Guardar cambios
                    </button>

                    <a href="{{ route('evaluaciones-ibt.show', $evaluacion->id) }}"
                       class="btn btn-outline-secondary">
                        Cancelar
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
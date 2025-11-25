@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Editar Registro de No Deforestación / PNO Reemplazo</h3>

    <form action="{{ route('pnoremplazo_nodeforestacion.update', $registro->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Código</label>
                    <div class="col-sm-9">
                        <input type="text" name="codigo" class="form-control"
                               value="{{ old('codigo', $registro->codigo) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Plantación</label>
                    <div class="col-sm-9">
                        <input type="text" name="plantacion" class="form-control"
                               value="{{ old('plantacion', $registro->plantacion) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Número de Árboles</label>
                    <div class="col-sm-9">
                        <input type="number" name="num_arboles" class="form-control"
                               value="{{ old('num_arboles', $registro->num_arboles) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Fecha Registro</label>
                    <div class="col-sm-9">
                        <input type="date" name="fecha_registro" class="form-control"
                               value="{{ old('fecha_registro', $registro->fecha_registro) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Responsable</label>
                    <div class="col-sm-9">
                        <input type="text" name="responsable" class="form-control"
                               value="{{ old('responsable', $registro->responsable) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Observaciones</label>
                    <div class="col-sm-9">
                        <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $registro->observaciones) }}</textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button class="btn btn-primary">Actualizar</button>
                    <a href="{{ route('pnoremplazo_nodeforestacion.index') }}" class="btn btn-secondary">Volver</a>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

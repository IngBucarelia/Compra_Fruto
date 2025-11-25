@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Agua - Uso Eficiente</h2>

    <form action="{{ route('agua_uso_eficiente.update', $aguaUsoEficiente->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h4>Preguntas</h4>

        <div class="mb-3">
            <label>¿Cuenta con un plan de ahorro?</label>
            <select name="plan_ahorro" class="form-control">
                <option value="1" {{ $aguaUsoEficiente->plan_ahorro ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$aguaUsoEficiente->plan_ahorro ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Mantenimiento de sistemas</label>
            <select name="mantenimiento_sistemas" class="form-control">
                <option value="1" {{ $aguaUsoEficiente->mantenimiento_sistemas ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$aguaUsoEficiente->mantenimiento_sistemas ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Uso de información balance hídrico</label>
            <select name="uso_informacion_balance" class="form-control">
                <option value="1" {{ $aguaUsoEficiente->uso_informacion_balance ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$aguaUsoEficiente->uso_informacion_balance ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Mecanismo de medición</label>
            <select name="mecanismo_medicion" class="form-control">
                <option value="1" {{ $aguaUsoEficiente->mecanismo_medicion ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$aguaUsoEficiente->mecanismo_medicion ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <button class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection

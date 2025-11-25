@extends('layouts.app')

@section('content')
<div class="container offline-form-container" style="background-color: whitesmoke; border-radius:30px">

<h2 class="title">Editar – Sustancias Químicas y Biológicas</h2>

<form action="{{ route('sustancias.update', $registro->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card-component">
        <label>¿Cuenta con POES?</label>
        <select name="cuenta_poes" class="form-control">
            <option value="1" {{ $registro->cuenta_poes ? 'selected':'' }}>Sí</option>
            <option value="0" {{ !$registro->cuenta_poes ? 'selected':'' }}>No</option>
        </select>
    </div>

    <div class="card-component">
        <label>¿Personal capacitado?</label>
        <select name="personal_capacitado" class="form-control">
            <option value="1" {{ $registro->personal_capacitado ? 'selected':'' }}>Sí</option>
            <option value="0" {{ !$registro->personal_capacitado ? 'selected':'' }}>No</option>
        </select>
    </div>

    <div class="card-component">
        <label>¿Almacenamiento adecuado?</label>
        <select name="almacenamiento_adecuado" class="form-control">
            <option value="1" {{ $registro->almacenamiento_adecuado ? 'selected':'' }}>Sí</option>
            <option value="0" {{ !$registro->almacenamiento_adecuado ? 'selected':'' }}>No</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Observaciones</label>
        <textarea name="observaciones" class="form-control">{{ $registro->observaciones }}</textarea>
    </div>

    <button class="btn btn-primary">Actualizar</button>
</form>

<br>
<a href="{{ route('visitasAmbientales.show', $visitaId) }}" class="btn btn-secondary">Volver</a>

</div>
@endsection

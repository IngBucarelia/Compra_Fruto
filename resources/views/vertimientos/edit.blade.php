@extends('layouts.app')

@section('content')
<div class="container offline-form-container" style="background-color: whitesmoke; border-radius:30px">

<h2 class="title">Editar Manejo de Vertimientos</h2>

<form action="{{ route('vertimientos.update', $registro->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card-component">
        <label>¿Cuenta con permiso(s)?</label>
        <select name="permiso_vertimientos" class="form-control">
            <option value="1" {{ $registro->permiso_vertimientos ? 'selected':'' }}>Sí</option>
            <option value="0" {{ !$registro->permiso_vertimientos ? 'selected':'' }}>No</option>
        </select>
    </div>

    <div class="card-component">
        <label>Sistema de agua residual doméstica</label>
        <select name="sistema_agua_domestica" class="form-control">
            <option value="1" {{ $registro->sistema_agua_domestica ? 'selected':'' }}>Sí</option>
            <option value="0" {{ !$registro->sistema_agua_domestica ? 'selected':'' }}>No</option>
        </select>
    </div>

    <div class="card-component">
        <label>Sistema de agua no doméstica</label>
        <select name="sistema_agua_no_domestica" class="form-control">
            <option value="1" {{ $registro->sistema_agua_no_domestica ? 'selected':'' }}>Sí</option>
            <option value="0" {{ !$registro->sistema_agua_no_domestica ? 'selected':'' }}>No</option>
        </select>
    </div>

    <div class="card-component">
        <label>Tratamiento para agroquímicos</label>
        <select name="sistema_agroquimicos" class="form-control">
            <option value="1" {{ $registro->sistema_agroquimicos ? 'selected':'' }}>Sí</option>
            <option value="0" {{ !$registro->sistema_agroquimicos ? 'selected':'' }}>No</option>
        </select>
    </div>

    <div class="card-component">
        <label>¿Cumple con el permiso?</label>
        <select name="cumple_permiso" class="form-control">
            <option value="1" {{ $registro->cumple_permiso ? 'selected':'' }}>Sí</option>
            <option value="0" {{ !$registro->cumple_permiso ? 'selected':'' }}>No</option>
        </select>
    </div>

    <div class="card-component">
        <label>¿Gestiona la obtención?</label>
        <select name="gestion_permiso" class="form-control">
            <option value="1" {{ $registro->gestion_permiso ? 'selected':'' }}>Sí</option>
            <option value="0" {{ !$registro->gestion_permiso ? 'selected':'' }}>No</option>
        </select>
    </div>

    <div class="card-component">
        <label>¿Realiza triple lavado?</label>
        <select name="triple_lavado" class="form-control">
            <option value="1" {{ $registro->triple_lavado ? 'selected':'' }}>Sí</option>
            <option value="0" {{ !$registro->triple_lavado ? 'selected':'' }}>No</option>
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

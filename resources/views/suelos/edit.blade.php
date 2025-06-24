@extends('layouts.app')

@section('content')
<div class="container">
    <h3>✏️ Editar Análisis de Suelo - {{ $visita->proveedor->proveedor_nombre }}</h3>

    <form method="POST" action="{{ route('suelos.update', $suelo->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>¿Análisis foliar?</label>
            <select name="analisis_foliar" class="form-control" required>
                <option value="si" {{ $suelo->analisis_foliar == 'si' ? 'selected' : '' }}>Sí</option>
                <option value="no" {{ $suelo->analisis_foliar == 'no' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>¿Análisis de suelo?</label>
            <select name="alanisis_suelo" class="form-control" required>
                <option value="si" {{ $suelo->alanisis_suelo == 'si' ? 'selected' : '' }}>Sí</option>
                <option value="no" {{ $suelo->alanisis_suelo == 'no' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Tipo de suelo</label>
            <select name="tipo_suelo" class="form-control" required>
                <option value="arenoso" {{ $suelo->tipo_suelo == 'arenoso' ? 'selected' : '' }}>Arenoso</option>
                <option value="arcilloso" {{ $suelo->tipo_suelo == 'arcilloso' ? 'selected' : '' }}>Arcilloso</option>
                <option value="franco" {{ $suelo->tipo_suelo == 'franco' ? 'selected' : '' }}>Franco</option>
                <option value="limoso" {{ $suelo->tipo_suelo == 'limoso' ? 'selected' : '' }}>Limoso</option>
                <option value="otro" {{ $suelo->tipo_suelo == 'otro' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">💾 Actualizar análisis</button>
    </form>

    <a href="{{ route('visitas.show', $visita->id) }}" class="btn btn-secondary mt-3">⬅️ Volver</a>
</div>
@endsection

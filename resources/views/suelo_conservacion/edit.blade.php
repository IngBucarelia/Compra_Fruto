@extends('layouts.app')

@section('content')
<div class="container" style="background-color: whitesmoke;border-radius:20px; padding:20px;">
    <h3>Editar - Energía</h3>

    <form action="{{ route('energia.update', $energia->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-check mt-2">
            <input type="checkbox" name="registro_consumo_optimizacion" class="form-check-input"
                {{ $energia->registro_consumo_optimizacion ? 'checked' : '' }}>
            <label class="form-check-label">
                ¿Cuenta con registro de consumo de combustible e implementa acciones para optimizar su uso?
            </label>
        </div>

        <div class="form-check mt-3">
            <input type="checkbox" name="plan_uso_eficiente_seguimiento" class="form-check-input"
                {{ $energia->plan_uso_eficiente_seguimiento ? 'checked' : '' }}>
            <label class="form-check-label">
                ¿Tiene implementado un plan para el uso eficiente de la energía y realiza seguimiento a los indicadores?
            </label>
        </div>

        <div class="mt-4">
            <label><strong>Observaciones</strong></label>
            <textarea name="observaciones" class="form-control" rows="4">{{ $energia->observaciones }}</textarea>
        </div>

        <button class="btn btn-primary mt-3">Actualizar</button>
    </form>
</div>
@endsection

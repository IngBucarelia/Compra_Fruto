@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Editar Polinización - {{ $visita->proveedor->proveedor_nombre }}</h3>

    <form method="POST" action="{{ route('polinizaciones.update', $polinizacion->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        <div class="mb-3">
            <label>Fecha de polinización:</label>
            <input type="date" name="fecha" class="form-control" value="{{ $polinizacion->fecha }}" required>
        </div>

        <div class="mb-3">
            <label>Número de pases:</label>
            <input type="number" name="n_pases" class="form-control" value="{{ $polinizacion->n_pases }}" required>
        </div>

        <div class="mb-3">
            <label>Ciclos por ronda:</label>
            <input type="number" name="ciclos_ronda" class="form-control" value="{{ $polinizacion->ciclos_ronda }}" required>
        </div>

        <div class="mb-3">
            <label>Cantidad de ANA aplicada:</label>
            <input type="number" step="0.01" name="ana" class="form-control" value="{{ $polinizacion->ana }}" required>
        </div>

        <div class="mb-3">
            <label>Tipo de ANA:</label>
            <select name="tipo_ana" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="solido" {{ $polinizacion->tipo_ana == 'solido' ? 'selected' : '' }}>Sólido</option>
                <option value="liquido" {{ $polinizacion->tipo_ana == 'liquido' ? 'selected' : '' }}>Líquido</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Cantidad de talco aplicado:</label>
            <input type="number" step="0.01" name="talco" class="form-control" value="{{ $polinizacion->talco }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar polinización</button>
        <a href="{{ route('visitas.show', $visita->id) }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>
@endsection

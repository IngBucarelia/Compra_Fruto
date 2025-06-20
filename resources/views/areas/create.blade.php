@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Área de la Visita - {{ $visita->proveedor->proveedor_nombre }}</h3>

    <form method="POST" action="{{ route('areas.store') }}">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        <div class="mb-3">
            <label>Material:</label>
            <select name="material" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="guinense">Guinense</option>
                <option value="hibrido">Híbrido</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Estado:</label>
            <select name="estado" class="form-control" required>
                <option value="desarrollo">Desarrollo</option>
                <option value="produccion">Producción</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Año de siembra:</label>
            <input type="date" name="anio_siembra" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Área (m²):</label>
            <input type="number" name="area" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Orden plantis número:</label>
            <input type="number" name="orden_plantis_numero" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Estado orden plantis:</label>
            <select name="estado_oren_plantis" class="form-control" required>
                <option value="desarrollo">Desarrollo</option>
                <option value="produccion">Producción</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar y continuar</button>
    </form>
</div>
@endsection

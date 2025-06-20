@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Fertilización para: {{ $visita->proveedor->proveedor_nombre }}</h3>

    <form method="POST" action="{{ route('fertilizaciones.store') }}">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        <div class="mb-3">
            <label>Fecha de fertilización:</label>
            <input type="date" name="fecha_fertilizacion" class="form-control" required>
        </div>

        <h5>Fertilizantes aplicados</h5>
        <div id="fertilizantes-container">
            <div class="fertilizante-group mb-3">
                <select name="fertilizantes[0][nombre]" class="form-control mb-2" required>
                    <option value="">Seleccione fertilizante</option>
                    <option value="urea">Urea</option>
                    <option value="compost">Compost</option>
                    <option value="npk">NPK</option>
                    <option value="otro">Otro</option>
                </select>
                <input type="number" name="fertilizantes[0][cantidad]" class="form-control" placeholder="Cantidad (kg)" required>
            </div>
        </div>

        <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="agregarFertilizante()">+ Añadir otro fertilizante</button>

        <button type="submit" class="btn btn-primary">Guardar fertilización</button>
    </form>
</div>
<hr>
<h4 class="mt-4">📋 Fertilizaciones registradas</h4>

@if ($visita->fertilizaciones->count())
    @foreach ($visita->fertilizaciones as $fertilizacion)
        <div class="card mb-3">
            <div class="card-header">
                <strong>🗓 Fecha:</strong> {{ $fertilizacion->fecha_fertilizacion }}
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach ($fertilizacion->fertilizantes as $fertilizante)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ ucfirst($fertilizante->fertilizante) }}
                            <span>{{ $fertilizante->cantidad }} kg</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
@else
    <p class="text-muted">No hay fertilizaciones registradas aún.</p>
@endif


<script>
let index = 1;
function agregarFertilizante() {
    const container = document.getElementById('fertilizantes-container');
    const grupo = document.createElement('div');
    grupo.classList.add('fertilizante-group', 'mb-3');
    grupo.innerHTML = `
        <select name="fertilizantes[${index}][nombre]" class="form-control mb-2" required>
            <option value="">Seleccione fertilizante</option>
            <option value="urea">Urea</option>
            <option value="compost">Compost</option>
            <option value="npk">NPK</option>
            <option value="otro">Otro</option>
        </select>
        <input type="number" name="fertilizantes[${index}][cantidad]" class="form-control" placeholder="Cantidad (kg)" required>
    `;
    container.appendChild(grupo);
    index++;
}
</script>
@endsection

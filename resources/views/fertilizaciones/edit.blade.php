@extends('layouts.app')

@section('content')
<div class="container">
     <h3>Editar Fetilizacion para: <br><br>Fecha Visita: <span class="text-primary">{{ $visita->fecha}}</span> <br> Proveedor:<span class="text-primary"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
        <span class="text-primary">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span></h3><br>

    <form method="POST" action="{{ route('fertilizaciones.update', $fertilizacion->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Fecha de fertilización:</label>
            <input type="date" name="fecha_fertilizacion" class="form-control" value="{{ $fertilizacion->fecha_fertilizacion }}" required>
        </div>

        <h5>Fertilizantes aplicados</h5>
        <div id="fertilizantes-container">
            @foreach ($fertilizacion->fertilizantes as $i => $fertilizante)
                <div class="fertilizante-group mb-3">
                    <select name="fertilizantes[{{ $i }}][nombre]" class="form-control mb-2" required>
                        <option value="">Seleccione fertilizante</option>
                        <option value="urea" {{ $fertilizante->fertilizante == 'urea' ? 'selected' : '' }}>Urea</option>
                        <option value="compost" {{ $fertilizante->fertilizante == 'compost' ? 'selected' : '' }}>Compost</option>
                        <option value="npk" {{ $fertilizante->fertilizante == 'npk' ? 'selected' : '' }}>NPK</option>
                        <option value="otro" {{ $fertilizante->fertilizante == 'otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    <input type="number" name="fertilizantes[{{ $i }}][cantidad]" class="form-control" placeholder="Cantidad (kg)" value="{{ $fertilizante->cantidad }}" required>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">💾 Actualizar fertilización</button>
        <a href="{{ route('fertilizaciones.create', ['visita_id' => $visita->id]) }}" class="btn btn-secondary">↩️ Cancelar</a>
    </form>
</div>
@endsection

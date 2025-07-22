@extends('layouts.app')

@section('content')
<style>

    
.container{
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
    }

    .title{
    text-align: center; 
    font-family: Arial Black; 
    font-weight: bold; 
    font-size: 30px; 
    color: #fdffe5; 
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    }
    @media (max-width: 768px) {

        .container {
        margin-left: -35px;
        width: 110%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }
    }
</style>
<div class="container" >
     <h3 class="title">Editar Fetilizacion </h3><h3> <br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span> <br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
        <span style="color: wheat">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span></h3><br>

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

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
    <h3 class="title">Editar Área </h3><h3> <br> <br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
        <span style="color: wheat">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span></h3><br>

    <form method="POST" action="{{ route('areas.update', $area->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Material:</label>
            <select name="material" class="form-control" required>
                <option value="guinense" {{ $area->material == 'guinense' ? 'selected' : '' }}>Guinense</option>
                <option value="hibrido" {{ $area->material == 'hibrido' ? 'selected' : '' }}>Híbrido</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Estado:</label>
            <select name="estado" class="form-control" required>
                <option value="desarrollo" {{ $area->estado == 'desarrollo' ? 'selected' : '' }}>Desarrollo</option>
                <option value="produccion" {{ $area->estado == 'produccion' ? 'selected' : '' }}>Producción</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Año de siembra:</label>
            <input type="date" name="anio_siembra" class="form-control" value="{{ $area->anio_siembra }}" required>
        </div>

        <div class="mb-3">
            <label>Área (m²):</label>
            <input type="number" name="area" class="form-control" value="{{ $area->area }}" required>
        </div>

        <div class="mb-3">
            <label>Orden plantis número:</label>
            <input type="number" name="orden_plantis_numero" class="form-control" value="{{ $area->orden_plantis_numero }}" required>
        </div>

        <div class="mb-3">
            <label>Estado orden plantis:</label>
            <select name="estado_oren_plantis" class="form-control" required>
                <option value="desarrollo" {{ $area->estado_oren_plantis == 'desarrollo' ? 'selected' : '' }}>Desarrollo</option>
                <option value="produccion" {{ $area->estado_oren_plantis == 'produccion' ? 'selected' : '' }}>Producción</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Área</button>
    </form><br>
     <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
</div>
@endsection

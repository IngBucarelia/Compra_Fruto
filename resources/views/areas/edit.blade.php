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


    @media (max-width: 968px) {

         .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
    }
        .button-group-top {
            flex-direction: row;
            justify-content: flex-start;
        }

         .container.offline-form-container {
        padding: 15px;
            margin-top: 15px;
            border-radius: 0;
            box-shadow: none;
            width: 123%;
            max-width: none;
            margin-left: -60px !important;
    }

    .title{
    text-align: center;
    font-family: Arial Black;
    font-weight: bold;
    font-size: 30px;
    color: #fdffe5;
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    margin-bottom: 25px;
}

 .container {
        margin-left: -70px;
        width: 125%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }

        .card{
        width: 100%;
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
            <label>Variedad:</label>
            <input type="text" name="variedad" class="form-control"
                value="{{ $area->variedad }}">
        </div>

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
            <input type="date" name="anio_siembra" class="form-control"
                value="{{ $area->anio_siembra }}">
        </div>

        <div class="mb-3">
            <label></label>
            <input type="number" step="0.01" name="area" class="form-control"
                value="{{ $area->area }}" required>
        </div>

        <div class="mb-3">
            <label>Área total finca (ha):</label>
            <input type="number" step="0.01" name="area_total_finca_hectareas"
                class="form-control" value="{{ $area->area_total_finca_hectareas }}">
        </div>

        <div class="mb-3">
            <label>Número palmas total finca:</label>
            <input type="number" name="numero_palmas_total_finca" class="form-control"
                value="{{ $area->numero_palmas_total_finca }}">
        </div>

        <div class="mb-3">
            <label>Área palmas desarrollo (ha):</label>
            <input type="number" step="0.01" name="area_palmas_desarrollo_hectareas"
                class="form-control" value="{{ $area->area_palmas_desarrollo_hectareas }}">
        </div>

        <div class="mb-3">
            <label>Número palmas desarrollo:</label>
            <input type="number" name="numero_palmas_desarrollo" class="form-control"
                value="{{ $area->numero_palmas_desarrollo }}">
        </div>

        <div class="mb-3">
            <label>Área palmas producción (ha):</label>
            <input type="number" step="0.01" name="area_palmas_produccion_hectareas"
                class="form-control" value="{{ $area->area_palmas_produccion_hectareas }}">
        </div>

        <div class="mb-3">
            <label>Número palmas producción:</label>
            <input type="number" name="numero_palmas_produccion" class="form-control"
                value="{{ $area->numero_palmas_produccion }}">
        </div>

        <div class="mb-3">
            <label>Ciclos de cosecha:</label>
            <input type="text" name="ciclos_cosecha" class="form-control"
                value="{{ $area->ciclos_cosecha }}">
        </div>

        <div class="mb-3">
            <label>Producción (ton/mes):</label>
            <input type="number" step="0.01" name="produccion_toneladas_por_mes"
                class="form-control" value="{{ $area->produccion_toneladas_por_mes }}">
        </div>

        <div class="mb-3">
            <label>¿Aplica orden Plantis?:</label>
            <select name="aplica_orden_plantis" class="form-control">
                <option value="1" {{ $area->aplica_orden_plantis ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ !$area->aplica_orden_plantis ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Número plantas orden Plantis:</label>
            <input type="number" name="numero_plantas_orden_plantis" class="form-control"
                value="{{ $area->numero_plantas_orden_plantis }}">
        </div>

        <div class="mb-3">
            <label>Orden Plantis número:</label>
            <input type="number" name="orden_plantis_numero" class="form-control"
                value="{{ $area->orden_plantis_numero }}">
        </div>

        <div class="mb-3">
            <label>Estado orden Plantis:</label>
            <select name="estado_oren_plantis" class="form-control">
                <option value="desarrollo" {{ $area->estado_oren_plantis == 'desarrollo' ? 'selected' : '' }}>Desarrollo</option>
                <option value="produccion" {{ $area->estado_oren_plantis == 'produccion' ? 'selected' : '' }}>Producción</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Área</button>
    </form>
<br>
     <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
</div>
@endsection

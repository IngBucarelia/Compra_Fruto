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
</style>
<div class="container"  >
    <h3 class="title">Editar Polinización </h3><h3> <br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span> <br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
        <span style="color: wheat">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span></h3><br>

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
     <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
    </form>
</div>
@endsection

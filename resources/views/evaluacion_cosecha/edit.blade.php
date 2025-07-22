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
    <h3 class="title">✏️ Editar Evaluación de Cosecha - {{ $evaluacion->visita->proveedor->proveedor_nombre }}</h3>

    <form method="POST" action="{{ route('evaluacion.update', $evaluacion->id) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="visita_id" value="{{ $evaluacion->visita_id }}">

        <div class="mb-3">
            <label>Variedad del fruto</label>
            <select name="variedad_fruto" class="form-control" required>
                <option value="guinense" {{ $evaluacion->variedad_fruto === 'guinense' ? 'selected' : '' }}>Guinense</option>
                <option value="hibrido" {{ $evaluacion->variedad_fruto === 'hibrido' ? 'selected' : '' }}>Híbrido</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Cantidad de racimos</label>
            <input type="number" name="cantidad_racimos" value="{{ $evaluacion->cantidad_racimos }}" class="form-control" required>
        </div>

        <div class="row">
            @foreach (['verde', 'maduro', 'sobremaduro', 'pedunculo'] as $campo)
                <div class="col-md-6 mb-3">
                    <label>{{ ucfirst($campo) }} (%)</label>
                    <input type="number" name="{{ $campo }}" value="{{ $evaluacion->$campo }}" class="form-control" required>
                </div>
            @endforeach
        </div>

        <div class="mb-3">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="3">{{ $evaluacion->observaciones }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">💾 Actualizar evaluación</button><br><br>
        <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
    </form>
</div>
@endsection

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
    <h3 class="title">✏️ Editar Análisis de Suelo </h3><h3> {{ $visita->proveedor->proveedor_nombre }}</h3>

    <form method="POST" action="{{ route('suelos.update', $suelo->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>¿Análisis foliar?</label>
            <select name="analisis_foliar" class="form-control" required>
                <option value="si" {{ $suelo->analisis_foliar == 'si' ? 'selected' : '' }}>Sí</option>
                <option value="no" {{ $suelo->analisis_foliar == 'no' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>¿Análisis de suelo?</label>
            <select name="alanisis_suelo" class="form-control" required>
                <option value="si" {{ $suelo->alanisis_suelo == 'si' ? 'selected' : '' }}>Sí</option>
                <option value="no" {{ $suelo->alanisis_suelo == 'no' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Tipo de suelo</label>
            <select name="tipo_suelo" class="form-control" required>
                <option value="arenoso" {{ $suelo->tipo_suelo == 'arenoso' ? 'selected' : '' }}>Arenoso</option>
                <option value="arcilloso" {{ $suelo->tipo_suelo == 'arcilloso' ? 'selected' : '' }}>Arcilloso</option>
                <option value="franco" {{ $suelo->tipo_suelo == 'franco' ? 'selected' : '' }}>Franco</option>
                <option value="limoso" {{ $suelo->tipo_suelo == 'limoso' ? 'selected' : '' }}>Limoso</option>
                <option value="otro" {{ $suelo->tipo_suelo == 'otro' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">💾 Actualizar análisis</button>
    </form>

    <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
</div>
@endsection

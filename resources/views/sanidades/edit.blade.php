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


<div class="container">
    <h3 class="title">✏️ Editar Sanidad</h3><h3> - {{ $visita->proveedor->proveedor_nombre }}</h3>

    <form method="POST" action="{{ route('sanidades.update', $sanidad->id) }}">
        @csrf
        @method('PUT')

        <div class="row">
            @php
                $enfermedades = [
                    'opsophanes' => 'Opsophanes',
                    'pudricion_cogollo' => 'Pudrición del cogollo',
                    'raspador' => 'Raspador',
                    'palmarum' => 'Palmarum',
                    'strategus' => 'Strategus',
                    'leptoparsha' => 'Leptoparsha',
                    'pestalotiopsis' => 'Pestalotiopsis',
                    'pudricion_basal' => 'Pudrición basal',
                    'pudricion_estipe' => 'Pudrición estipe',
                ];
            @endphp

            @foreach ($enfermedades as $campo => $nombre)
                <div class="col-md-6 mb-3">
                    <label>{{ $nombre }} (%)</label>
                    <input type="number" name="{{ $campo }}" class="form-control" min="0" max="100" value="{{ old($campo, $sanidad->$campo) }}">
                </div>
            @endforeach

            <div class="col-md-6 mb-3">
                <label>Otros (%)</label>
                <input type="text" name="otros" class="form-control" value="{{ old('otros', $sanidad->otros) }}">
            </div>

            <div class="col-12 mb-3">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $sanidad->observaciones) }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">💾 Actualizar</button>
        <a href="{{ route('sanidades.create', ['visita_id' => $visita->id]) }}" class="btn btn-secondary">↩️ Cancelar</a>
    </form>
</div>
@endsection

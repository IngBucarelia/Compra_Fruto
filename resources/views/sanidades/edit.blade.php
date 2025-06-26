@extends('layouts.app')

@section('content')
<div class="container">
    <h3>✏️ Editar Sanidad - {{ $visita->proveedor->proveedor_nombre }}</h3>

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

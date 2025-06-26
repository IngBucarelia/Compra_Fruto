@extends('layouts.app')

@section('content')
<div class="container">
    <h3>✏️ Editar Labores de Cultivo - {{ $visita->proveedor->proveedor_nombre }}</h3>

    <form method="POST" action="{{ route('labores-cultivo.update', $visita->laboresCultivo->id) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        <div class="row">
            @php
                $labores = [
                    'polinizacion' => 'Polinización',
                    'limpieza_calle' => 'Limpieza de calle',
                    'limpieza_plato' => 'Limpieza de plato',
                    'poda' => 'Poda',
                    'fertilizacion' => 'Fertilización',
                    'enmiendas' => 'Enmiendas',
                    'ubicacion_tusa_fibra' => 'Ubicación tusa/fibra',
                    'ubicacion_hoja' => 'Ubicación hoja',
                    'lugar_ubicacion_hoja' => 'Lugar ubicación hoja',
                    'plantas_nectariferas' => 'Plantas nectaríferas',
                    'cobertura' => 'Cobertura',
                    'labor_cosecha' => 'Labor cosecha',
                    'calidad_fruta' => 'Calidad fruta',
                    'recoleccion_fruta' => 'Recolección fruta',
                    'drenajes' => 'Drenajes',
                ];
            @endphp

            @foreach ($labores as $campo => $label)
                <div class="col-md-6 mb-3">
                    <label>{{ $label }} (%)</label>
                    <input type="number" name="{{ $campo }}" class="form-control"
                        value="{{ old($campo, $visita->laboresCultivo->$campo ?? '') }}" min="0" max="100">
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">💾 Actualizar</button>
        <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
    </form>
</div>
@endsection

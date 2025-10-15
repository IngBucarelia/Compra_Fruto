@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="text-warning fw-bold mb-4">✏ Editar Fuerza Laboral</h3>

    <form action="{{ route('fuerza_laboral.update', [$visita->id, $fuerza->id]) }}" method="POST" class="card p-4 shadow-sm border-0 rounded-3 bg-light">
        @csrf
        @method('PUT')

        <!-- Forma de contratación -->
        <div class="mb-3">
            <label class="form-label fw-bold">📋 Forma de contratación</label>
            <select name="forma_contratacion[]" class="form-select" multiple>
                @php
                    $opciones = [
                        "Dependiente", "Independiente", "Todas las anteriores",
                        "No identifica la forma contractual", "Contrata a traves de una SAS"
                    ];
                @endphp
                @foreach($opciones as $op)
                    <option value="{{ $op }}" @if(in_array($op, $fuerza->forma_contratacion ?? [])) selected @endif>{{ $op }}</option>
                @endforeach
            </select>
        </div>

        <!-- Número de trabajadores -->
        <div class="mb-3">
            <label class="form-label fw-bold">👥 Número de trabajadores</label>
            <input type="number" name="num_trabajadores" class="form-control" value="{{ $fuerza->num_trabajadores }}">
        </div>

        <!-- Hombres y Mujeres -->
        <div class="mb-3">
            <label class="form-label fw-bold">⚧ Distribución de género</label>
            <div class="d-flex gap-2">
                <input type="number" name="num_hombres" class="form-control" value="{{ $fuerza->num_hombres }}" placeholder="Hombres">
                <input type="number" name="num_mujeres" class="form-control" value="{{ $fuerza->num_mujeres }}" placeholder="Mujeres">
            </div>
        </div>

        <!-- Resto de selects -->
        @php
            $campos = [
                'contrato_formal' => ['SI', 'NO'],
                'seguridad_social' => ['SI', 'NO', 'En proceso'],
                'tipo_contrato' => ['Termino fijo', 'Termino indefinido', 'Por obra labor', 'Contrato tiempo parcial'],
                'contrato_firmado' => ['SI', 'NO'],
                'sg_sst' => ['SI', 'NO', 'En proceso'],
                'examenes_medicos' => ['SI', 'NO'],
                'trabajadores_migrantes' => ['SI', 'NO'],
                'comprobantes_pago' => ['SI', 'NO'],
                'dotacion' => ['SI', 'NO'],
            ];
        @endphp

        @foreach($campos as $name => $options)
            <div class="mb-3">
                <label class="form-label fw-bold text-capitalize">{{ str_replace('_',' ',$name) }}</label>
                <select name="{{ $name }}" class="form-select">
                    <option value="">Seleccione</option>
                    @foreach($options as $opt)
                        <option value="{{ $opt }}" @if($fuerza->$name === $opt) selected @endif>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <button type="submit" class="btn btn-warning mt-3">💾 Actualizar</button>
        <a href="{{ route('fuerza_laboral.index', $visita->id) }}" class="btn btn-secondary mt-3">⬅ Volver</a>
    </form>
</div>
@endsection

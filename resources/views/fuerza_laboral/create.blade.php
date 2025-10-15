@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('redireccion_seccion_social', $visita->id) }}" method="GET" class="mt-4">
            <label for="seccion" class="form-label fw-bold text-success">📋 Ir a sección:</label>
            <div class="input-group">
                <select id="seccion" name="seccion" class="form-select" required>
                    <option value="">Seleccione una sección</option>
                    @if ($visita->estado === 'pendiente' || $visita->estado === 'en_ejecucion')
                        <option value="inicio"> Pagina de Inicio de Visita</option>
                        <option value="datos_personales">👤 Datos Personales</option>
                        <option value="miembros">👨‍👩‍👧‍👦 Miembros del Hogar</option>
                        <option value="predio">🏡 Datos del Predio</option>
                        <option value="fuerza_laboral">🧑‍🌾 Fuerza Laboral</option>
                        <option value="organizacion_social">👥 Organización Social</option>
                    @endif
                </select>
                <button type="submit" class="btn btn-success">Ir</button>
            </div>
        </form>
    <h3 class="text-success fw-bold mb-4">➕ Registrar Fuerza Laboral</h3>

    <form action="{{ route('fuerza_laboral.store', $visita->id) }}" method="POST" class="card p-4 shadow-sm border-0 rounded-3 bg-light">
        @csrf

        <!-- Forma de contratación -->
        <div class="mb-3">
            <label class="form-label fw-bold">📋 Forma de contratación</label>
            <select name="forma_contratacion[]" class="form-select" multiple>
                <option value="Dependiente">Dependiente</option>
                <option value="Independiente">Independiente</option>
                <option value="Todas las anteriores">Todas las anteriores</option>
                <option value="No identifica la forma contractual">No identifica la forma contractual</option>
                <option value="Contrata a traves de una SAS">Contrata a través de una SAS</option>
            </select>
            <small class="text-muted">Mantén presionada CTRL para seleccionar varias</small>
        </div>

        <!-- Número de trabajadores -->
        <div class="mb-3">
            <label class="form-label fw-bold">👥 Número de trabajadores</label>
            <input type="number" name="num_trabajadores" class="form-control" placeholder="Ej: 15">
        </div>

        <!-- Hombres y Mujeres -->
        <div class="mb-3">
            <label class="form-label fw-bold">⚧ Distribución de género</label>
            <div class="d-flex gap-2">
                <input type="number" name="num_hombres" class="form-control" placeholder="Hombres">
                <input type="number" name="num_mujeres" class="form-control" placeholder="Mujeres">
            </div>
        </div>

        <!-- Reutilizamos los selects para las demás preguntas -->
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
                        <option value="{{ $opt }}">{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success mt-3">💾 Guardar</button>
        <a href="{{ route('fuerza_laboral.index', $visita->id) }}" class="btn btn-secondary mt-3">⬅ Volver</a>
    </form>
</div>
@endsection

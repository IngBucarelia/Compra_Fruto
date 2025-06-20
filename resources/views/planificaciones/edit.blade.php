@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Planificación</h2>

    <form method="POST" action="{{ route('planificaciones.update', $planificacion->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Fecha:</label>
            <input type="date" name="fecha" class="form-control" value="{{ $planificacion->fecha }}" required>
        </div>

        <div class="mb-3">
            <label>Técnico:</label>
            <select name="tecnico_campo" class="form-control" required>
                @foreach ($tecnicos as $tecnico)
                    <option value="{{ $tecnico->id }}" {{ $planificacion->tecnico_campo == $tecnico->id ? 'selected' : '' }}>
                        {{ $tecnico->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Proveedor:</label>
            <select id="proveedor" name="proveedor_id" class="form-control" required>
                @foreach ($proveedores as $p)
                    <option value="{{ $p->id }}" {{ $planificacion->proveedor_id == $p->id ? 'selected' : '' }}>
                        {{ $p->proveedor_nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Plantación:</label>
            <select id="plantacion" name="plantacion_id" class="form-control" required>
                @foreach ($plantaciones as $plantacion)
                    <option value="{{ $plantacion->id }}" {{ $planificacion->plantacion_id == $plantacion->id ? 'selected' : '' }}>
                        {{ $plantacion->nombre }} - {{ $plantacion->vereda }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tipo de visita:</label>
            <select name="tipo_visita" class="form-control" required>
                <option value="Inicial" {{ $planificacion->tipo_visita == 'Inicial' ? 'selected' : '' }}>Inicial</option>
                <option value="Seguimiento" {{ $planificacion->tipo_visita == 'Seguimiento' ? 'selected' : '' }}>Seguimiento</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Estado:</label>
            <select name="estado" class="form-control" required>
                <option value="pendiente" {{ $planificacion->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="realizada" {{ $planificacion->estado == 'realizada' ? 'selected' : '' }}>Realizada</option>
                <option value="cancelada" {{ $planificacion->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('planificaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
document.getElementById('proveedor').addEventListener('change', function () {
    const id = this.value;
    fetch(`/api/plantaciones/${id}`)
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('plantacion');
            select.innerHTML = '<option value="">Seleccione</option>';
            data.forEach(p => {
                const o = document.createElement('option');
                o.value = p.id;
                o.textContent = p.nombre + ' - ' + p.vereda;
                select.appendChild(o);
            });
        });
});
</script>
@endsection

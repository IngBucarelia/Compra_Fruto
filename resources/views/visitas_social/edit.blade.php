@extends('layouts.app')

@section('content')
<style>
    .container{
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
    }
</style>
<div class="container">
    <h2>Editar Visita - Componente Social</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('visitas_social.updateSocial', $visita->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Fecha:</label>
            <input type="date" name="fecha" class="form-control" 
                   value="{{ old('fecha', $visita->fecha) }}" required>
        </div>

        <div class="mb-3">
            <label>Proveedor:</label>
            <select id="proveedor-select" name="proveedor_id" class="form-control" required>
                <option value="">Seleccione proveedor</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}" 
                        {{ $visita->proveedor_id == $proveedor->id ? 'selected' : '' }}>
                        {{ $proveedor->proveedor_nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Plantación:</label>
            <select id="plantacion-select" name="plantacion_id" class="form-control" required>
                <option value="">Seleccione una plantación</option>
                @foreach($plantaciones as $plantacion)
                    <option value="{{ $plantacion->id }}" 
                        data-ubicacion="{{ $plantacion->vereda }}, {{ $plantacion->municipio }}, {{ $plantacion->departamento }}"
                        {{ $visita->plantacion_id == $plantacion->id ? 'selected' : '' }}>
                        {{ $plantacion->nombre }} - {{ $plantacion->vereda }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Ubicación:</label>
            <input type="text" id="ubicacion" name="ubicacion" class="form-control" 
                   value="{{ old('ubicacion', $visita->ubicacion) }}" required>
        </div>

        <div class="mb-3">
            <label>Técnico de campo:</label>
            <select name="tecnico_campo" class="form-control" required>
                <option value="">Seleccione técnico</option>
                @foreach($tecnicos as $tecnico)
                    <option value="{{ $tecnico->id }}" 
                        {{ $visita->tecnico_campo == $tecnico->id ? 'selected' : '' }}>
                        {{ $tecnico->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tipo de visita:</label>
            <select name="tipo_visita" class="form-control" required>
                <option value="">Seleccione tipo</option>
                <option value="Inicial" {{ $visita->tipo_visita == 'Inicial' ? 'selected' : '' }}>Inicial</option>
                <option value="Seguimiento" {{ $visita->tipo_visita == 'Seguimiento' ? 'selected' : '' }}>Seguimiento</option>
                <option value="Capacitacion" {{ $visita->tipo_visita == 'Capacitacion' ? 'selected' : '' }}>Capacitación</option>
                <option value="Poa" {{ $visita->tipo_visita == 'Poa' ? 'selected' : '' }}>Poa</option>
                <option value="Estudio Credito" {{ $visita->tipo_visita == 'Estudio Credito' ? 'selected' : '' }}>Estudio Credito</option>
                <option value="Inclusion a Pequeños" {{ $visita->tipo_visita == 'Inclusion a Pequeños' ? 'selected' : '' }}>Inclusion a Pequeños</option>
                <option value="Solidaridad" {{ $visita->tipo_visita == 'Solidaridad' ? 'selected' : '' }}>Solidaridad</option>
                <option value="Aps" {{ $visita->tipo_visita == 'Aps' ? 'selected' : '' }}>Aps</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Recibió la visita:</label>
            <input type="text" name="recibio_visita" class="form-control" 
                   value="{{ old('recibio_visita', $visita->recibio_visita) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar visita</button>
    </form><br>
    <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
</div>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#proveedor-select').select2({
            placeholder: "Seleccione proveedor",
            allowClear: true
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const proveedorSelect = document.getElementById('proveedor-select');
        const plantacionSelect = document.getElementById('plantacion-select');
        const ubicacionInput = document.getElementById('ubicacion');

        proveedorSelect.addEventListener('change', function () {
            const proveedorId = this.value;
            plantacionSelect.innerHTML = '<option value="">Cargando...</option>';
            ubicacionInput.value = '';

            if (proveedorId) {
                fetch(`/api/plantaciones/${proveedorId}`)
                    .then(res => res.json())
                    .then(data => {
                        plantacionSelect.innerHTML = '<option value="">Seleccione una plantación</option>';
                        data.forEach(p => {
                            const option = document.createElement('option');
                            option.value = p.id;
                            option.textContent = p.nombre + ' - ' + p.vereda;
                            option.setAttribute('data-ubicacion', p.vereda + ', ' + p.municipio + ', ' + p.departamento);
                            plantacionSelect.appendChild(option);
                        });
                    });
            }
        });

        plantacionSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const ubicacion = selected.getAttribute('data-ubicacion');
            if (ubicacion) ubicacionInput.value = ubicacion;
        });
    });
</script>

<style>
    @media (max-width: 768px) {
        .container {
            padding: 15px 10px;
            margin-left: -40px;
            width:110%
        }
        
        h2 {
            font-size: 1.5rem;
        }
        
        .search-box input {
            min-width: 100%;
        }
        
        .action-buttons .btn {
            min-width: 80px;
            font-size: 0.75rem;
        }
    }
</style>
@endsection

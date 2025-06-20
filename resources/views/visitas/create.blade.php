@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Registrar Visita</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('visitas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Fecha:</label>
            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
    <label>Proveedor:</label>
        <select id="proveedor-select" name="proveedor_id" class="form-control" required>
            <option value="">Seleccione proveedor</option>
            @foreach($proveedores as $proveedor)
                <option value="{{ $proveedor->id }}">{{ $proveedor->proveedor_nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Plantación:</label>
        <select id="plantacion-select" name="plantacion_id" class="form-control" required>
            <option value="">Seleccione una plantación</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Ubicación:</label>
        <input type="text" id="ubicacion" name="ubicacion" class="form-control" placeholder="Ej. Vereda La Cabaña" required>
    </div>


        <div class="mb-3">
            <label>Técnico de campo:</label>
            <select name="tecnico_campo" class="form-control" required>
                <option value="">Seleccione técnico</option>
                @foreach($tecnicos as $tecnico)
                    <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tipo de visita:</label>
            <select name="tipo_visita" class="form-control" required>
                <option value="">Seleccione tipo</option>
                <option value="Inicial">Inicial</option>
                <option value="Seguimiento">Seguimiento</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Recibió la visita:</label>
            <input type="text" name="recibio_visita" class="form-control" placeholder="Nombre de quien recibió" required>
        </div>
        <input type="hidden" name="es_planificada" value="0">


        <button type="submit" class="btn btn-primary">Guardar visita</button>
    </form>
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
</script>

<script>
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



@endsection

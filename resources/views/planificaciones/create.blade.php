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
    <h2 class="title">Crear Planificación</h2>

    <form method="POST" action="{{ route('planificaciones.store') }}">
        @csrf

        <div class="mb-3">
            <label>Fecha:</label>
            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label>Técnico:</label>
            <select name="tecnico_campo" class="form-control" required>
                @foreach ($tecnicos as $tecnico)
                    <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Proveedor:</label>
            <select id="proveedor" name="proveedor_id" class="form-control" required>
                <option value="">Seleccione proveedor</option>
                @foreach ($proveedores as $p)
                    <option value="{{ $p->id }}">{{ $p->proveedor_nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Plantación:</label>
            <select id="plantacion" name="plantacion_id" class="form-control" required></select>
        </div>

        <div class="mb-3">
            <label>Tipo de visita:</label>
            <select name="tipo_visita" class="form-control" required>
                <option value="Inicial">Inicial</option>
                <option value="Seguimiento">Seguimiento</option>
            </select>
        </div>

        <button class="btn btn-primary">Guardar</button>
    </form><br>
    <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
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

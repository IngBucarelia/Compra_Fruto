@extends('layouts.app')

@section('content')
<style>
      body{
        background-image: url('{{ asset('images/fondo_envios.png') }}'); 
    }
    @media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    .grid-3 { grid-template-columns: repeat(2,1fr);}

    .grid-3 { grid-template-columns: 1fr; }
    .btn-circle {
        width: 30px;
        height: 30px;
    }
    
    .table-responsive {
        font-size: 0.9em;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group .form-control {
        margin-bottom: 10px;
    }
     .container {
        margin-left: -70px;
        width: 125%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }

        .card{
        width: 100%;
    }
}
</style>
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-white">
            <h5 class="mb-0 text-success"><i class="fas fa-plus-circle me-2"></i>Planificar Envío</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('envios.store') }}" method="POST">
                @csrf
                   <div class="row">

                    
                    {{-- PROVEEDOR --}}
                    {{-- PROVEEDOR --}}
<div class="col-md-6 mb-3">
    <label class="form-label fw-bold">Proveedor</label>

    <div style="position: relative;">
        <!-- Campo de búsqueda -->
        <input type="text" id="buscarProveedor" class="form-control mb-1" placeholder="🔍 Buscar proveedor...">

        <!-- Lista de proveedores -->
        <select id="proveedor_id" name="proveedor_id" class="form-select" required size="6" style="height:auto;">
            <option value="">Seleccione proveedor</option>
            @foreach($proveedores as $p)
                <option value="{{ $p->id }}">{{ $p->proveedor_nombre }}</option>
            @endforeach
        </select>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const inputBusqueda = document.getElementById('buscarProveedor');
    const selectProveedor = document.getElementById('proveedor_id');

    inputBusqueda.addEventListener('keyup', () => {
        const filtro = inputBusqueda.value.toLowerCase();
        const opciones = selectProveedor.querySelectorAll('option');

        opciones.forEach(op => {
            const texto = op.textContent.toLowerCase();
            if (texto.includes(filtro) || op.value === '') {
                op.style.display = '';
            } else {
                op.style.display = 'none';
            }
        });

        // Siempre mantener visible la opción vacía "Seleccione proveedor"
        opciones[0].style.display = '';
    });
});
</script>


                    {{-- PLANTACIÓN (dependiente del proveedor) --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Plantación</label>
                        <select id="plantacion_id" name="plantacion_id" class="form-select" required>
                            <option value="">Seleccione primero un proveedor</option>
                        </select>
                    </div>

                    {{-- TÉCNICO --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Técnico Asignado</label>
                        <select name="tecnico_id" class="form-select">
                            <option value="">Sin asignar</option>
                            @foreach($tecnicos as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- FECHA --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Fecha de Envío</label>
                        <input type="date" name="fecha_envio" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>

                    {{-- DESCRIPCIÓN --}}
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Descripción del Envío</label>
                        <textarea name="descripcion_envio" class="form-control" rows="4" required placeholder="Qué se envía (herramientas, documentos, repuestos...)"></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('envios.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                    <button class="btn btn-success">Planificar Envío</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- AJAX --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const proveedorSelect = document.querySelector('select[name="proveedor_id"]');
    const plantacionSelect = document.querySelector('select[name="plantacion_id"]');

    proveedorSelect.addEventListener('change', async function() {
        const proveedorId = this.value;
        plantacionSelect.innerHTML = '<option value="">Cargando...</option>';

        if (!proveedorId) {
            plantacionSelect.innerHTML = '<option value="">Seleccione plantación</option>';
            return;
        }

        try {
            const response = await fetch(`/proveedor/${proveedorId}/plantaciones`);

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();

            plantacionSelect.innerHTML = '<option value="">Seleccione plantación</option>';
            data.forEach(pl => {
                plantacionSelect.innerHTML += `<option value="${pl.id}">${pl.nombre} — ${pl.municipio}</option>`;
            });
        } catch (error) {
            console.error('Error cargando plantaciones:', error);
            alert('⚠️ Error: No se pudieron cargar las plantaciones del proveedor seleccionado.');
            plantacionSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    });
});
</script>


@endsection

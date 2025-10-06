@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <!-- Card Principal -->
            <div class="card shadow">
                <div class="card-header bg-gradient-info py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-black">
                                <i class="fas fa-clipboard-list me-2"></i>
                                Registrar Visita - Componente Agronómico
                            </h4>
                            <p class="mb-0 text-black-50 small">Complete los datos para crear una nueva visita</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-green text-info fs-6 p-2">
                                <i class="fas fa-plus-circle me-1"></i>
                                Nueva Visita
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('visitas.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Fecha:</label>
                            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Proveedor:</label>
                            <select id="proveedor-select" name="proveedor_id" class="form-control" required>
                                <option value="">Seleccione proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->proveedor_nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Plantación:</label>
                            <select id="plantacion-select" name="plantacion_id" class="form-control" required>
                                <option value="">Seleccione una plantación</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Ubicación:</label>
                            <input type="text" id="ubicacion" name="ubicacion" class="form-control" placeholder="Ej. Vereda La Cabaña" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Técnico de campo:</label>
                            <select name="tecnico_campo" class="form-control" required>
                                <option value="">Seleccione técnico</option>
                                @foreach($tecnicos as $tecnico)
                                    <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Tipo de visita:</label>
                            <select name="tipo_visita" class="form-control" required>
                                <option value="">Seleccione tipo</option>
                                <option value="Inicial">Inicial</option>
                                <option value="Seguimiento">Seguimiento</option>
                                <option value="Capacitacion">Capacitación</option>
                                <option value="Poa">Poa</option>
                                <option value="Estudio Credito">Edtudio Credito</option>
                                <option value="Inclusion a Pequeños">Inclusion a Pequeños</option>
                                <option value="Solidaridad">Solidaridad</option>
                                <option value="Aps">Aps</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Recibió la visita:</label>
                            <input type="text" name="recibio_visita" class="form-control" placeholder="Nombre de quien recibió" required>
                        </div>
                        
                        <input type="hidden" name="es_planificada" value="0">

                        <div class="d-flex gap-3 flex-wrap">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Guardar visita
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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

<style>
.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.form-control {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #36b9cc;
    box-shadow: 0 0 0 0.2rem rgba(54, 185, 204, 0.25);
}

.btn-success {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    border-radius: 10px;
    padding: 10px 25px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn-outline-secondary {
    border-radius: 10px;
    padding: 10px 25px;
    font-weight: 600;
}

.alert {
    border-radius: 10px;
    border: none;
}

/* Select2 personalización */
.select2-container--default .select2-selection--single {
    border: 2px solid #e9ecef !important;
    border-radius: 10px !important;
    height: 38px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 38px !important;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 10px !important;
        width: 115%;
        margin-left: -40px;
    }
    
    .card-body {
        padding: 20px !important;
    }
    
    .d-flex.flex-wrap {
        flex-direction: column;
    }
    
    .d-flex.flex-wrap .btn {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endsection
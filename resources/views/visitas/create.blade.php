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

                    <form action="{{ route('visitas.store') }}" method="POST" id="visita-form">
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
                            <label class="form-label fw-bold text-success">Tipos de visita:</label>
                            <div class="tipos-visita-grid">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipos_visita[]" value="Inicial" id="tipo_inicial">
                                    <label class="form-check-label" for="tipo_inicial">
                                        <i class="fas fa-flag text-primary me-1"></i>Inicial
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipos_visita[]" value="Seguimiento" id="tipo_seguimiento">
                                    <label class="form-check-label" for="tipo_seguimiento">
                                        <i class="fas fa-sync-alt text-info me-1"></i>Seguimiento
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipos_visita[]" value="Capacitacion" id="tipo_capacitacion">
                                    <label class="form-check-label" for="tipo_capacitacion">
                                        <i class="fas fa-chalkboard-teacher text-warning me-1"></i>Capacitación
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipos_visita[]" value="Poa" id="tipo_poa">
                                    <label class="form-check-label" for="tipo_poa">
                                        <i class="fas fa-chart-line text-success me-1"></i>POA
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipos_visita[]" value="Estudio Credito" id="tipo_estudio">
                                    <label class="form-check-label" for="tipo_estudio">
                                        <i class="fas fa-file-invoice-dollar text-danger me-1"></i>Estudio Crédito
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipos_visita[]" value="Inclusion a Pequeños" id="tipo_inclusion">
                                    <label class="form-check-label" for="tipo_inclusion">
                                        <i class="fas fa-hands-helping text-info me-1"></i>Inclusión a Pequeños
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipos_visita[]" value="Solidaridad" id="tipo_solidaridad">
                                    <label class="form-check-label" for="tipo_solidaridad">
                                        <i class="fas fa-heart text-danger me-1"></i>Solidaridad
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tipos_visita[]" value="Aps" id="tipo_aps">
                                    <label class="form-check-label" for="tipo_aps">
                                        <i class="fas fa-clipboard-check text-success me-1"></i>APS
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">Puede seleccionar múltiples tipos de visita</small>
                            @error('tipos_visita')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Recibió la visita:</label>
                            <input type="text" name="recibio_visita" class="form-control" placeholder="Nombre de quien recibió" required>
                        </div>
                        
                        <input type="hidden" name="es_planificada" value="0">

                        <div class="d-flex gap-3 flex-wrap">
                            <button type="submit" class="btn btn-success" id="submit-btn">
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

<!-- jQuery primero, luego Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inicializar Select2 para proveedor
    $('#proveedor-select').select2({
        placeholder: "Seleccione proveedor",
        allowClear: true
    });

    // Inicializar Select2 para plantación
    $('#plantacion-select').select2({
        placeholder: "Seleccione plantación",
        allowClear: true
    });

    // Variables
    const ubicacionInput = document.getElementById('ubicacion');
    const form = document.getElementById('visita-form');
    const submitBtn = document.getElementById('submit-btn');

    // Evento change para proveedor (usando jQuery para Select2)
    $('#proveedor-select').on('change', function() {
        const proveedorId = $(this).val();
        
        // Limpiar plantación y ubicación
        $('#plantacion-select').empty().append('<option value="">Cargando...</option>').trigger('change');
        ubicacionInput.value = '';

        if (proveedorId) {
            fetch(`/api/plantaciones/${proveedorId}`)
                .then(res => {
                    if (!res.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return res.json();
                })
                .then(data => {
                    $('#plantacion-select').empty().append('<option value="">Seleccione una plantación</option>');
                    
                    if (data.length === 0) {
                        $('#plantacion-select').append('<option value="">No hay plantaciones para este proveedor</option>');
                        return;
                    }
                    
                    data.forEach(p => {
                        const option = new Option(
                            p.nombre + ' - ' + p.vereda,
                            p.id,
                            false,
                            false
                        );
                        // Agregar data attribute para la ubicación
                        $(option).data('ubicacion', p.vereda + ', ' + p.municipio + ', ' + p.departamento);
                        $('#plantacion-select').append(option);
                    });
                    
                    $('#plantacion-select').trigger('change');
                })
                .catch(error => {
                    console.error('Error cargando plantaciones:', error);
                    $('#plantacion-select').empty().append('<option value="">Error al cargar plantaciones</option>');
                });
        } else {
            $('#plantacion-select').empty().append('<option value="">Seleccione una plantación</option>');
        }
    });

    // Evento change para plantación (usando jQuery para Select2)
    $('#plantacion-select').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const ubicacion = selectedOption.data('ubicacion');
        
        if (ubicacion) {
            ubicacionInput.value = ubicacion;
        } else {
            ubicacionInput.value = '';
        }
    });

    // Validación antes de enviar
    form.addEventListener('submit', function(e) {
        const checkboxes = document.querySelectorAll('input[name="tipos_visita[]"]:checked');
        
        if (checkboxes.length === 0) {
            e.preventDefault();
            alert('Por favor seleccione al menos un tipo de visita');
            return false;
        }
        
        // Deshabilitar botón para evitar doble envío
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
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

.btn-success:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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

/* Estilos para los checkboxes */
.tipos-visita-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.form-check {
    background: #f8f9fa;
    padding: 12px 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-check:hover {
    background: #e9ecef;
    border-color: #36b9cc;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.form-check-label {
    font-weight: 500;
    cursor: pointer;
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

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px !important;
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
    
    .tipos-visita-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
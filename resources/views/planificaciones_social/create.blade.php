@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <!-- Card Container -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0" style="color: #054a30">
                            <i class="fas fa-calendar-plus me-2"></i>
                            Crear Nueva Planificación Social
                        </h4>
                        <div class="badge bg-white text-primary p-2">
                            <i class="fas fa-users me-1"></i> Visita Social
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('planificaciones_social.store') }}" id="planificacionForm">
                        @csrf

                        <div class="row">
                            <!-- Columna Izquierda -->
                            <div class="col-md-6">
                                <!-- Fecha -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-primary">
                                        <i class="fas fa-calendar-day me-2"></i>Fecha de Visita
                                    </label>
                                    <input type="date" name="fecha" class="form-control form-control-lg" required 
                                           value="{{ old('fecha', now()->format('Y-m-d')) }}"
                                           style="border-left: 4px solid #4e73df;">
                                </div>

                                <!-- Técnico de Campo -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-primary">
                                        <i class="fas fa-user-check me-2"></i>Técnico de Campo
                                    </label>
                                    <select name="tecnico_campo" class="form-select form-select-lg" required
                                            style="border-left: 4px solid #36b9cc;">
                                        <option value="">Seleccione un técnico</option>
                                        @foreach($tecnicos as $tecnico)
                                            <option value="{{ $tecnico->id }}" {{ old('tecnico_campo') == $tecnico->id ? 'selected' : '' }}>
                                                👨‍💼 {{ $tecnico->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Proveedor -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-primary">
                                        <i class="fas fa-building me-2"></i>Proveedor
                                    </label>
                                    <select name="proveedor_id" class="form-select form-select-lg" required id="proveedor_id"
                                            style="border-left: 4px solid #1cc88a;">
                                        <option value="">Seleccione un proveedor</option>
                                        @foreach($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                                🏢 {{ $proveedor->proveedor_nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Columna Derecha -->
                            <div class="col-md-6">
                                <!-- Plantación -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-primary">
                                        <i class="fas fa-seedling me-2"></i>Plantación
                                    </label>
                                    <select name="plantacion_id" class="form-select form-select-lg" required id="plantacion_id"
                                            style="border-left: 4px solid #f6c23e;">
                                        <option value="">Seleccione un proveedor primero</option>
                                    </select>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Las plantaciones se cargan automáticamente al seleccionar el proveedor
                                    </div>
                                </div>

                                <!-- Tipo de Visita -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-primary">
                                        <i class="fas fa-tasks me-2"></i>Tipo de Visita
                                    </label>
                                    <select name="tipo_visita" class="form-select form-select-lg" required
                                            style="border-left: 4px solid #e74a3b;">
                                        <option value="">Seleccione el tipo de visita</option>
                                        <option value="seguimiento" {{ old('tipo_visita') == 'seguimiento' ? 'selected' : '' }}>
                                            📋 Seguimiento
                                        </option>
                                        <option value="evaluacion" {{ old('tipo_visita') == 'evaluacion' ? 'selected' : '' }}>
                                            📊 Evaluación
                                        </option>
                                        <option value="capacitacion" {{ old('tipo_visita') == 'capacitacion' ? 'selected' : '' }}>
                                            🎓 Capacitación
                                        </option>
                                    </select>
                                </div>

                                <!-- Información Adicional -->
                                <div class="alert alert-info mt-4">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                                        <div>
                                            <h6 class="alert-heading mb-2">Información Importante</h6>
                                            <p class="mb-0 small">
                                                Al guardar la planificación, se creará automáticamente 
                                                una visita social asociada con estado "pendiente".
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{ route('planificaciones_social.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-save me-2"></i>Guardar Planificación
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.form-control, .form-select {
    border-radius: 10px;
    transition: all 0.3s ease;
    border: 2px solid #e3e6f0;
}

.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.3rem rgba(78, 115, 223, 0.15);
    transform: translateY(-2px);
}

.form-control-lg, .form-select-lg {
    padding: 12px 20px;
    font-size: 1.05rem;
}

.btn {
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-lg {
    padding: 12px 30px;
}

.btn-primary {
    background: linear-gradient(45deg, #4e73df, #224abe);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #224abe, #4e73df);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(78, 115, 223, 0.4);
}

.alert {
    border-radius: 12px;
    border: none;
}

.badge {
    border-radius: 20px;
    font-size: 0.85rem;
}

/* Animación de carga */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.loading {
    animation: pulse 1.5s infinite;
}
</style>

<script>
document.getElementById('proveedor_id').addEventListener('change', function() {
    const proveedorId = this.value;
    const plantacionSelect = document.getElementById('plantacion_id');
    
    if (proveedorId) {
        // Mostrar loading con animación
        plantacionSelect.innerHTML = '<option value="" class="loading">🔄 Cargando plantaciones...</option>';
        plantacionSelect.disabled = true;
        
        fetch(`/api/plantaciones-por-proveedor/${proveedorId}`)
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta');
                return response.json();
            })
            .then(data => {
                plantacionSelect.disabled = false;
                if (data.length > 0) {
                    plantacionSelect.innerHTML = '<option value="">🌱 Seleccione una plantación</option>';
                    data.forEach(plantacion => {
                        plantacionSelect.innerHTML += `<option value="${plantacion.id}">${plantacion.nombre}</option>`;
                    });
                } else {
                    plantacionSelect.innerHTML = '<option value="">❌ No hay plantaciones</option>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                plantacionSelect.innerHTML = '<option value="">⚠️ Error al cargar</option>';
                plantacionSelect.disabled = false;
            });
    } else {
        plantacionSelect.innerHTML = '<option value="">📋 Seleccione proveedor</option>';
        plantacionSelect.disabled = true;
    }
});

// Cargar plantaciones al editar
document.addEventListener('DOMContentLoaded', function() {
    const proveedorId = document.getElementById('proveedor_id').value;
    if (proveedorId) {
        document.getElementById('proveedor_id').dispatchEvent(new Event('change'));
    }
    
    // Validación del formulario
    document.getElementById('planificacionForm').addEventListener('submit', function(e) {
        const plantacionId = document.getElementById('plantacion_id').value;
        if (!plantacionId) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Plantación requerida',
                text: 'Por favor seleccione una plantación válida',
                confirmButtonColor: '#4e73df'
            });
        }
    });
});
</script>

<!-- Incluir SweetAlert2 para mejores alertas -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
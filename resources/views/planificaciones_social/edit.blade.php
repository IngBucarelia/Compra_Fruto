@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <!-- Card Container -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-warning text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-white">
                            <i class="fas fa-edit me-2"></i>
                            Editar Planificación Social
                        </h4>
                        <div class="badge bg-white text-warning p-2">
                            <i class="fas fa-users me-1"></i> ID: {{ $planificacion->id }}
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('planificaciones_social.update', $planificacion->id) }}" id="planificacionForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Columna Izquierda -->
                            <div class="col-md-6">
                                <!-- Fecha -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-primary">
                                        <i class="fas fa-calendar-day me-2"></i>Fecha de Visita
                                    </label>
                                    <input type="date" name="fecha" class="form-control form-control-lg" required 
                                           value="{{ old('fecha', $planificacion->fecha) }}" {{-- CORRECCIÓN AQUÍ --}}
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
                                            <option value="{{ $tecnico->id }}" 
                                                {{ old('tecnico_campo', $planificacion->tecnico_campo) == $tecnico->id ? 'selected' : '' }}>
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
                                            <option value="{{ $proveedor->id }}" 
                                                {{ old('proveedor_id', $planificacion->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
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
                                        <option value="">Cargando plantaciones...</option>
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
                                        <option value="seguimiento" {{ old('tipo_visita', $planificacion->tipo_visita) == 'seguimiento' ? 'selected' : '' }}>
                                            📋 Seguimiento
                                        </option>
                                        <option value="evaluacion" {{ old('tipo_visita', $planificacion->tipo_visita) == 'evaluacion' ? 'selected' : '' }}>
                                            📊 Evaluación
                                        </option>
                                        <option value="capacitacion" {{ old('tipo_visita', $planificacion->tipo_visita) == 'capacitacion' ? 'selected' : '' }}>
                                            🎓 Capacitación
                                        </option>
                                    </select>
                                </div>

                                <!-- Información del Estado -->
                                <div class="alert alert-warning mt-4">
                                    <div class="d-flex">
                                        <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
                                        <div>
                                            <h6 class="alert-heading mb-2">Información de Estado</h6>
                                            <p class="mb-1 small">
                                                <strong>Estado actual:</strong> 
                                                <span class="badge bg-{{ $planificacion->estado == 'pendiente' ? 'warning' : 'success' }}">
                                                    {{ strtoupper($planificacion->estado) }}
                                                </span>
                                            </p>
                                            <p class="mb-0 small">
                                                <strong>Visita asociada:</strong> 
                                                @if($planificacion->visita_social_id)
                                                    <span class="badge bg-success">✅ Vinculada</span>
                                                @else
                                                    <span class="badge bg-secondary">❌ Sin vincular</span>
                                                @endif
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
                                    <button type="submit" class="btn btn-warning btn-lg px-4">
                                        <i class="fas fa-save me-2"></i>Actualizar Planificación
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
    border-color: #f6c23e;
    box-shadow: 0 0 0 0.3rem rgba(246, 194, 62, 0.15);
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

.btn-warning {
    background: linear-gradient(45deg, #f6c23e, #dda20a);
    border: none;
    color: white;
}

.btn-warning:hover {
    background: linear-gradient(45deg, #dda20a, #f6c23e);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(246, 194, 62, 0.4);
    color: white;
}

.alert {
    border-radius: 12px;
    border: none;
}

.badge {
    border-radius: 20px;
    font-size: 0.85rem;
}

.loading {
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
</style>

<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const proveedorSelect = document.getElementById('proveedor_id');
    const plantacionSelect = document.getElementById('plantacion_id');
    const planificacionPlantacionId = {{ $planificacion->plantacion_id }};
    const planificacionProveedorId = {{ $planificacion->proveedor_id }};

    // Función para cargar plantaciones
    function cargarPlantaciones(proveedorId, plantacionSeleccionadaId = null) {
        if (proveedorId) {
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
                            const selected = plantacionSeleccionadaId && plantacion.id == plantacionSeleccionadaId ? 'selected' : '';
                            plantacionSelect.innerHTML += 
                                `<option value="${plantacion.id}" ${selected}>
                                    ${plantacion.nombre}
                                </option>`;
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
    }

    // Cargar plantaciones al cambiar proveedor
    proveedorSelect.addEventListener('change', function() {
        cargarPlantaciones(this.value);
    });

    // Cargar plantaciones al iniciar la página
    if (planificacionProveedorId) {
        cargarPlantaciones(planificacionProveedorId, planificacionPlantacionId);
    }

    // Sistema de Alertas y Validaciones
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#f6c23e',
            confirmButtonText: 'Aceptar',
            timer: 4000,
            timerProgressBar: true
        }).then((result) => {
            if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                window.location.href = "{{ route('planificaciones_social.index') }}";
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#e74a3b',
            confirmButtonText: 'Entendido'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'warning',
            title: 'Errores de validación',
            html: `{!! implode('<br>', $errors->all()) !!}`,
            confirmButtonColor: '#f6c23e',
            confirmButtonText: 'Corregir'
        });
    @endif

    // Validación del formulario antes de enviar
    document.getElementById('planificacionForm').addEventListener('submit', function(e) {
        const plantacionId = document.getElementById('plantacion_id').value;
        
        if (!plantacionId) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Plantación requerida',
                text: 'Por favor seleccione una plantación válida',
                confirmButtonColor: '#f6c23e'
            });
            return;
        }

        // Mostrar confirmación antes de actualizar
        e.preventDefault();
        
        Swal.fire({
            title: '¿Actualizar planificación?',
            text: "¿Estás seguro de que deseas actualizar esta planificación social?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f6c23e',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar el formulario
                this.submit();
            }
        });
    });
});
</script>
@endsection
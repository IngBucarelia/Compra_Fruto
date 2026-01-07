@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <!-- Card Container -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-black">
                            <i class="fas fa-calendar-plus me-2"></i>
                            Crear Nueva Planificación Agronómica
                        </h4>
                        <div class="badge bg-white text-success p-2">
                            <i class="fas fa-leaf me-1"></i> Visita Agronómica
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('planificaciones.store') }}" id="planificacionForm">
                        @csrf

                        <div class="row">
                            <!-- Columna Izquierda -->
                            <div class="col-md-6">
                                <!-- Fecha -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-success">
                                        <i class="fas fa-calendar-day me-2"></i>Fecha de Visita
                                    </label>
                                    <input type="date" name="fecha" class="form-control form-control-lg" required 
                                           value="{{ old('fecha', now()->format('Y-m-d')) }}"
                                           style="border-left: 4px solid #1cc88a;">
                                </div>

                                <!-- Técnico de Campo -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-success">
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
                                    <label class="form-label fw-bold text-success">Proveedor:</label>
                            <select id="proveedor-select" name="proveedor_id" class="form-control" required>
                                <option value="">Seleccione proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->proveedor_nombre }}</option>
                                @endforeach
                            </select>
                                </div>
                            </div>

                            <!-- Columna Derecha -->
                            <div class="col-md-6">
                                <!-- Plantación -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-success">
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
                                    <label class="form-label fw-semibold text-success">
                                        <i class="fas fa-tasks me-2"></i>Tipo de Visita
                                    </label>
                                    <select name="tipo_visita" class="form-select form-select-lg" required
                                            style="border-left: 4px solid #e74a3b;">
                                        <option value="">Seleccione el tipo de visita</option>
                                        <option value="Inicial" {{ old('tipo_visita') == 'Inicial' ? 'selected' : '' }}>
                                            🏁 Inicial
                                        </option>
                                        <option value="Seguimiento" {{ old('tipo_visita') == 'Seguimiento' ? 'selected' : '' }}>
                                            📋 Seguimiento
                                        </option>
                                        <option value="Evaluacion" {{ old('tipo_visita') == 'Evaluacion' ? 'selected' : '' }}>
                                            📊 Evaluación
                                        </option>
                                        <option value="Mantenimiento" {{ old('tipo_visita') == 'Mantenimiento' ? 'selected' : '' }}>
                                            🔧 Mantenimiento
                                        </option>
                                    </select>
                                </div>

                                <!-- Información Adicional -->
                                <div class="alert alert-success mt-4">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle fa-2x me-3 text-success"></i>
                                        <div>
                                            <h6 class="alert-heading mb-2">Información Importante</h6>
                                            <p class="mb-0 small">
                                                Al guardar la planificación, se creará automáticamente 
                                                una visita agronómica asociada con estado "pendiente".
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
                                    <a href="{{ route('planificaciones.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success btn-lg px-4">
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
    border-color: #1cc88a;
    box-shadow: 0 0 0 0.3rem rgba(28, 200, 138, 0.15);
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

.btn-success {
    background: linear-gradient(45deg, #1cc88a, #13855c);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(45deg, #13855c, #1cc88a);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(28, 200, 138, 0.4);
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

    // Cargar plantaciones al cambiar proveedor
    proveedorSelect.addEventListener('change', function() {
        const proveedorId = this.value;
        
        if (proveedorId) {
            plantacionSelect.innerHTML = '<option value="" class="loading">🔄 Cargando plantaciones...</option>';
            plantacionSelect.disabled = true;
            
            fetch(`/api/plantaciones/${proveedorId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta');
                    return response.json();
                })
                .then(data => {
                    plantacionSelect.disabled = false;
                    if (data.length > 0) {
                        plantacionSelect.innerHTML = '<option value="">🌱 Seleccione una plantación</option>';
                        data.forEach(plantacion => {
                            plantacionSelect.innerHTML += 
                                `<option value="${plantacion.id}">
                                    ${plantacion.nombre} - ${plantacion.vereda}
                                </option>`;
                        });
                    } else {
                        plantacionSelect.innerHTML = '<option value="">❌ No hay plantaciones disponibles</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    plantacionSelect.innerHTML = '<option value="">⚠️ Error al cargar plantaciones</option>';
                    plantacionSelect.disabled = false;
                });
        } else {
            plantacionSelect.innerHTML = '<option value="">📋 Seleccione un proveedor primero</option>';
            plantacionSelect.disabled = true;
        }
    });

    // Sistema de Alertas y Validaciones
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#1cc88a',
            confirmButtonText: 'Aceptar',
            timer: 4000,
            timerProgressBar: true
        }).then((result) => {
            if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                window.location.href = "{{ route('planificaciones.index') }}";
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
                confirmButtonColor: '#1cc88a'
            });
            return;
        }

        // Mostrar confirmación antes de enviar
        e.preventDefault();
        
        Swal.fire({
            title: '¿Crear planificación?',
            text: "¿Estás seguro de que deseas crear esta planificación agronómica?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, crear',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    setTimeout(() => {
                        resolve();
                    }, 1000);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar el formulario
                this.submit();
            }
        });
    });
});


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

    $(document).ready(function() {
    // Inicializar Select2 para proveedor
    $('#proveedor-select').select2({
        placeholder: "Seleccione proveedor",
        allowClear: true
    });

    )}
</script>
@endsection
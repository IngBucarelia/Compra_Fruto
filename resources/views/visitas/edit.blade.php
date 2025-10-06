@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <!-- Card Container -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-black">
                            <i class="fas fa-edit me-2"></i>
                            Editar Visita Agronómica
                        </h4>
                        <div class="badge bg-white text-primary p-2">
                            <i class="fas fa-leaf me-1"></i> ID: {{ $visita->id }}
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('visitas.update', $visita->id) }}" id="visitaForm">
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
                                           value="{{ old('fecha', $visita->fecha) }}"
                                           style="border-left: 4px solid #4e73df;">
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
                                                {{ old('proveedor_id', $visita->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
                                                🏢 {{ $proveedor->proveedor_nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

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

                                <!-- Ubicación -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-primary">
                                        <i class="fas fa-map-marker-alt me-2"></i>Ubicación
                                    </label>
                                    <input type="text" name="ubicacion" class="form-control form-control-lg" required 
                                           id="ubicacion" placeholder="Ej: Vereda La Cabaña, Municipio, Departamento"
                                           style="border-left: 4px solid #e74a3b;"
                                           value="{{ old('ubicacion', $visita->ubicacion) }}">
                                    <div class="form-text">
                                        <i class="fas fa-sync-alt me-1"></i>
                                        La ubicación se completa automáticamente al seleccionar la plantación
                                    </div>
                                </div>
                            </div>

                            <!-- Columna Derecha -->
                            <div class="col-md-6">
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
                                                {{ old('tecnico_campo', $visita->tecnico_campo) == $tecnico->id ? 'selected' : '' }}>
                                                👨‍💼 {{ $tecnico->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tipo de Visita -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-primary">
                                        <i class="fas fa-tasks me-2"></i>Tipo de Visita
                                    </label>
                                    <select name="tipo_visita" class="form-select form-select-lg" required
                                            style="border-left: 4px solid #6f42c1;">
                                        <option value="">Seleccione el tipo de visita</option>
                                        <option value="Inicial" {{ old('tipo_visita', $visita->tipo_visita) == 'Inicial' ? 'selected' : '' }}>🏁 Inicial</option>
                                        <option value="Seguimiento" {{ old('tipo_visita', $visita->tipo_visita) == 'Seguimiento' ? 'selected' : '' }}>📋 Seguimiento</option>
                                        <option value="Evaluacion" {{ old('tipo_visita', $visita->tipo_visita) == 'Evaluacion' ? 'selected' : '' }}>📊 Evaluación</option>
                                        <option value="Mantenimiento" {{ old('tipo_visita', $visita->tipo_visita) == 'Mantenimiento' ? 'selected' : '' }}>🔧 Mantenimiento</option>
                                    </select>
                                </div>

                                <!-- Recibió la Visita -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-primary">
                                        <i class="fas fa-user-tie me-2"></i>Recibió la Visita
                                    </label>
                                    <input type="text" name="recibio_visita" class="form-control form-control-lg" required 
                                           placeholder="Nombre completo de quien recibió la visita"
                                           style="border-left: 4px solid #fd7e14;"
                                           value="{{ old('recibio_visita', $visita->recibio_visita) }}">
                                </div>

                                <!-- Información del Estado -->
                                <div class="alert alert-primary mt-4">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle fa-2x me-3 text-primary"></i>
                                        <div>
                                            <h6 class="alert-heading mb-2">Información de Estado</h6>
                                            <p class="mb-1 small">
                                                <strong>Estado actual:</strong> 
                                                <span class="badge bg-{{ $visita->estado == 'pendiente' ? 'warning' : 'success' }}">
                                                    {{ strtoupper($visita->estado) }}
                                                </span>
                                            </p>
                                            <p class="mb-0 small">
                                                <strong>Tipo:</strong> 
                                                <span class="badge bg-info">🌱 Visita Agronómica</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mapa -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">
                                            <i class="fas fa-map me-2"></i>Ubicación en el Mapa
                                        </h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="map" style="height: 400px; border-radius: 0 0 10px 10px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-3 justify-content-end">
                                    <a href="{{ route('visitas.index') }}" class="btn btn-outline-secondary btn-lg">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-save me-2"></i>Actualizar Visita
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

.loading {
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

#map {
    border-radius: 0 0 10px 10px;
}
</style>

<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const proveedorSelect = document.getElementById('proveedor_id');
    const plantacionSelect = document.getElementById('plantacion_id');
    const ubicacionInput = document.getElementById('ubicacion');
    const visitaPlantacionId = {{ $visita->plantacion_id }};
    const visitaProveedorId = {{ $visita->proveedor_id }};

    // Mapa
    const map = L.map('map').setView([4.6097, -74.0817], 6);
    let marker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Función para actualizar el marcador
    function setMarker(geo) {
        if (!geo || !geo.includes(',')) {
            if (marker) {
                map.removeLayer(marker);
                marker = null;
            }
            return;
        }

        const [lat, lon] = geo.split(',').map(coord => parseFloat(coord.trim()));
        if (isNaN(lat) || isNaN(lon)) return;

        if (marker) {
            marker.setLatLng([lat, lon]);
        } else {
            marker = L.marker([lat, lon]).addTo(map);
        }

        map.setView([lat, lon], 15);
    }

    // Función para cargar plantaciones
    function cargarPlantaciones(proveedorId, plantacionSeleccionadaId = null) {
        if (proveedorId) {
            plantacionSelect.innerHTML = '<option value="" class="loading">🔄 Cargando plantaciones...</option>';
            plantacionSelect.disabled = true;
            ubicacionInput.value = '';
            
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
                            const selected = plantacionSeleccionadaId && plantacion.id == plantacionSeleccionadaId ? 'selected' : '';
                            const ubicacionCompleta = `${plantacion.vereda}, ${plantacion.municipio}, ${plantacion.departamento}`;
                            plantacionSelect.innerHTML += 
                                `<option value="${plantacion.id}" data-ubicacion="${ubicacionCompleta}" data-geo="${plantacion.geolocalizacion || ''}" ${selected}>
                                    ${plantacion.nombre} - ${plantacion.vereda}
                                </option>`;
                        });

                        // Si hay una plantación seleccionada, cargar su ubicación y mapa
                        if (plantacionSeleccionadaId) {
                            const selectedOption = plantacionSelect.querySelector(`option[value="${plantacionSeleccionadaId}"]`);
                            if (selectedOption) {
                                const ubicacion = selectedOption.getAttribute('data-ubicacion');
                                const geo = selectedOption.getAttribute('data-geo');
                                if (ubicacion) ubicacionInput.value = ubicacion;
                                if (geo) setMarker(geo);
                            }
                        }
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
            ubicacionInput.value = '';
        }
    }

    // Actualizar ubicación y mapa al seleccionar plantación
    plantacionSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const ubicacion = selectedOption.getAttribute('data-ubicacion');
        const geo = selectedOption.getAttribute('data-geo');
        
        if (ubicacion) {
            ubicacionInput.value = ubicacion;
        }
        if (geo) {
            setMarker(geo);
        }
    });

    // Cargar plantaciones al cambiar proveedor
    proveedorSelect.addEventListener('change', function() {
        cargarPlantaciones(this.value);
    });

    // Cargar plantaciones al iniciar la página
    if (visitaProveedorId) {
        cargarPlantaciones(visitaProveedorId, visitaPlantacionId);
    }

    // Sistema de Alertas y Validaciones
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#4e73df',
            confirmButtonText: 'Aceptar',
            timer: 4000,
            timerProgressBar: true
        }).then((result) => {
            if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                window.location.href = "{{ route('visitas.index') }}";
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
    document.getElementById('visitaForm').addEventListener('submit', function(e) {
        const plantacionId = document.getElementById('plantacion_id').value;
        const recibioVisita = document.querySelector('input[name="recibio_visita"]').value;
        
        if (!plantacionId) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Plantación requerida',
                text: 'Por favor seleccione una plantación válida',
                confirmButtonColor: '#4e73df'
            });
            return;
        }

        if (!recibioVisita.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Campo requerido',
                text: 'Por favor ingrese el nombre de quien recibió la visita',
                confirmButtonColor: '#4e73df'
            });
            return;
        }

        // Mostrar confirmación antes de actualizar
        e.preventDefault();
        
        Swal.fire({
            title: '¿Actualizar visita agronómica?',
            text: "¿Estás seguro de que deseas actualizar esta visita agronómica?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4e73df',
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
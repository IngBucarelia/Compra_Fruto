@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <!-- Card Container -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-black">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Registrar Visita Social - Manual
                        </h4>
                        <div class="badge bg-black text-success p-2">
                            <i class="fas fa-hand-paper me-1"></i> Manual
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('visitas_social.storeSocial') }}" id="visitaForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Fecha:</label>
                            <input type="date" name="fecha" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Proveedor:</label>
                            
                            <!-- Input de búsqueda -->
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="buscarProveedor" class="form-control" 
                                    placeholder="Buscar proveedor..." oninput="filtrarProveedores()">
                            </div>
                            
                            <!-- Select con altura fija y scroll -->
                            <select id="proveedor_id" name="proveedor_id" class="form-control" 
                                    size="8" style="height: auto; min-height: 200px;" required>
                                <option value="">Seleccione proveedor</option>
                                @foreach($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}">{{ $proveedor->proveedor_nombre }}</option>
                                @endforeach
                            </select>
                            
                            <small class="text-muted" id="contadorProveedores">
                                {{ count($proveedores) }} proveedores disponibles
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Plantación:</label>
                            <select id="plantacion_id" name="plantacion_id" class="form-control" required>
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
                                @php
                                    $tipos = [
                                        ['Inicial', 'flag', 'text-primary'],
                                        ['Seguimiento', 'sync-alt', 'text-info'],
                                        ['Capacitacion', 'chalkboard-teacher', 'text-warning'],
                                        ['POA', 'chart-line', 'text-success'],
                                        ['Estudio Credito', 'file-invoice-dollar', 'text-danger'],
                                        ['Inclusion a Pequeños', 'hands-helping', 'text-info'],
                                        ['Solidaridad', 'heart', 'text-danger'],
                                        ['APS', 'clipboard-check', 'text-success']
                                    ];
                                @endphp

                                @foreach($tipos as [$valor, $icono, $color])
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="tipo_visita[]" value="{{ $valor }}" id="tipo_{{ strtolower(str_replace(' ', '_', $valor)) }}">
                                        <label class="form-check-label" for="tipo_{{ strtolower(str_replace(' ', '_', $valor)) }}">
                                            <i class="fas fa-{{ $icono }} {{ $color }} me-1"></i>{{ $valor }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Puede seleccionar múltiples tipos de visita</small>
                            @error('tipo_visita')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Recibió la visita:</label>
                            <input type="text" name="recibio_visita" class="form-control" placeholder="Nombre de quien recibió" required>
                        </div>

                        <input type="hidden" name="es_planificada" value="0">

                        <div class="alert alert-success mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Esta visita se crea de forma manual y no está asociada a una planificación previa.
                            El estado inicial será <b>pendiente</b>.
                        </div>

                        <div class="d-flex gap-3 flex-wrap mt-4">
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
    const ubicacionInput = document.getElementById('ubicacion');

    // Cargar plantaciones al cambiar proveedor
    proveedorSelect.addEventListener('change', function() {
        const proveedorId = this.value;
        
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
                            const ubicacionCompleta = `${plantacion.vereda}, ${plantacion.municipio}, ${plantacion.departamento}`;
                            plantacionSelect.innerHTML += 
                                `<option value="${plantacion.id}" data-ubicacion="${ubicacionCompleta}">
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
            ubicacionInput.value = '';
        }
    });

    // Actualizar ubicación al seleccionar plantación
    plantacionSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const ubicacion = selectedOption.getAttribute('data-ubicacion');
        
        if (ubicacion) {
            ubicacionInput.value = ubicacion;
        }
    });

    // Cargar plantaciones al editar (si hay proveedor seleccionado)
    const proveedorIdInicial = proveedorSelect.value;
    if (proveedorIdInicial) {
        proveedorSelect.dispatchEvent(new Event('change'));
    }

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
                confirmButtonColor: '#1cc88a'
            });
            return;
        }

        if (!recibioVisita.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Campo requerido',
                text: 'Por favor ingrese el nombre de quien recibió la visita',
                confirmButtonColor: '#1cc88a'
            });
            return;
        }

        // Mostrar confirmación antes de enviar
        e.preventDefault();
        
        Swal.fire({
            title: '¿Crear visita manual?',
            text: "¿Estás seguro de que deseas registrar esta visita social?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1cc88a',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, registrar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    // Simular procesamiento
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

function filtrarProveedores() {
    const buscar = document.getElementById('buscarProveedor').value.toLowerCase();
    const select = document.getElementById('proveedor_id');
    const options = select.getElementsByTagName('option');
    let contador = 0;
    
    for (let i = 0; i < options.length; i++) {
        const texto = options[i].textContent.toLowerCase();
        if (texto.includes(buscar) || options[i].value === "") {
            options[i].style.display = '';
            contador++;
        } else {
            options[i].style.display = 'none';
        }
    }
    
    document.getElementById('contadorProveedores').textContent = 
        (contador - 1) + ' proveedores encontrados'; // Restamos la opción vacía
}
</script>
@endsection
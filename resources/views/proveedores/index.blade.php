@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header con Estadísticas -->
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-info py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-black">
                                <i class="fas fa-building me-2"></i>
                                Gestión de Proveedores
                            </h4>
                            <p class="mb-0 text-black-50 small">Administra los proveedores y sus plantaciones asociadas</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-white text-info fs-6 p-2">
                                <i class="fas fa-users me-1"></i>
                                Total: {{ $proveedores->total() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Principal -->
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        @if(Auth::check() && in_array(Auth::user()->rol, [1,4]))
                        <div class="col-md-6">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('proveedores.create') }}" class="btn btn-info btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>Nuevo Proveedor
                                </a>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <!-- Buscador -->
                            <form method="GET" action="{{ route('proveedores.index') }}" id="form-busqueda">
                                <div class="input-group">
                                    <input type="text" name="buscar" id="buscar" class="form-control" 
                                           placeholder="Buscar por nombre o NIT..." 
                                           value="{{ request('buscar') }}"
                                           style="border-radius: 10px 0 0 10px;">
                                    <button class="btn btn-outline-info" type="submit" style="border-radius: 0 10px 10px 0;">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Tabla Responsive -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0" id="proveedoresTable">
                            <thead class="table-info">
                                <tr>
                                    <th class="ps-4">
                                        <i class="fas fa-hashtag me-2"></i>ID
                                    </th>
                                    <th>
                                        <i class="fas fa-building me-2"></i>Nombre
                                    </th>
                                    <th>
                                        <i class="fas fa-id-card me-2"></i>NIT
                                    </th>
                                    <th class="text-center pe-4">
                                        <i class="fas fa-cogs me-2"></i>Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proveedores as $proveedor)
                                    <tr class="align-middle">
                                        <td class="ps-4">
                                            <div class="fw-bold text-info">
                                                #{{ $proveedor->id }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-info rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-building text-white fs-6"></i>
                                                </div>
                                                <span class="fw-semibold">🏢 {{ $proveedor->proveedor_nombre }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary fs-6">
                                                <i class="fas fa-id-card me-1"></i>
                                                {{ $proveedor->nit ?? 'Sin NIT' }}
                                            </span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Ver Plantaciones -->
                                                <a href="{{ route('proveedores.plantaciones.index', $proveedor->id) }}" 
                                                   class="btn btn-success btn-sm btn-circle"
                                                   data-bs-toggle="tooltip" title="Ver plantaciones">
                                                    <i class="fas fa-seedling"></i>
                                                </a>
                                                 @if(Auth::check() && in_array(Auth::user()->rol, [1,4]))
                                                <!-- Editar -->
                                                <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                                                   class="btn btn-warning btn-sm btn-circle"
                                                   data-bs-toggle="tooltip" title="Editar proveedor">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- Eliminar con confirmación -->
                                                <form action="{{ route('proveedores.destroy', $proveedor->id) }}" method="POST" class="d-inline" id="deleteForm-{{ $proveedor->id }}">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm btn-circle delete-btn"
                                                            data-bs-toggle="tooltip" 
                                                            title="Eliminar proveedor"
                                                            data-proveedor="{{ $proveedor->id }}"
                                                            data-nombre="{{ $proveedor->proveedor_nombre }}"
                                                            data-nit="{{ $proveedor->nit ?? 'Sin NIT' }}"
                                                            data-form-id="deleteForm-{{ $proveedor->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <h5>No hay proveedores registrados</h5>
                                                <p>Comienza creando un nuevo proveedor</p>
                                                <a href="{{ route('proveedores.create') }}" class="btn btn-info">
                                                    <i class="fas fa-plus me-2"></i>Crear Primer Proveedor
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($proveedores->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Mostrando {{ $proveedores->firstItem() }} - {{ $proveedores->lastItem() }} de {{ $proveedores->total() }} registros
                            </div>
                            <div>
                            {{ $proveedores->links('vendor.pagination.bootstrap-5') }}
                        </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Botón Cancelar -->
            <div class="text-end mt-3">
             @if(Auth::check() && in_array(Auth::user()->rol, [1]))    
        <a href="{{ route('proveedores.eliminados') }}" class="btn btn-danger">
            Ver eliminados
        </a>
        @endif
                <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </button>
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

.table th {
    border-top: none;
    font-weight: 600;
    color: #36b9cc;
}

.table-hover tbody tr:hover {
    background-color: rgba(54, 185, 204, 0.05) !important;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.btn-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-circle:hover {
    transform: scale(1.1);
}

.avatar-sm {
    width: 30px;
    height: 30px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.table-responsive {
    border-radius: 0 0 10px 10px;
}


/* CORREGIR FLECHAS GIGANTES EN PAGINACIÓN */
.pagination {
    font-size: 14px !important;
    margin-bottom: 0;
}

.page-link {
    padding: 0.375rem 0.75rem !important;
    font-size: 0.875rem !important;
    border-radius: 0.375rem !important;
    margin: 0 2px !important;
}

.page-item .page-link {
    border: 1px solid #dee2e6 !important;
    color: #36b9cc !important;
}

.page-item.active .page-link {
    background-color: #36b9cc !important;
    border-color: #36b9cc !important;
    color: white !important;
}

.page-item.disabled .page-link {
    color: #6c757d !important;
}

/* Específico para las flechas */
.page-link[rel="prev"],
.page-link[rel="next"] {
    font-weight: bold !important;
    font-size: 1rem !important;
}

/* Para móviles */
@media (max-width: 768px) {
    .pagination {
        font-size: 12px !important;
    }
    
    .page-link {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.75rem !important;
        margin: 0 1px !important;
    }
}


/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 10px !important;
    }
    
    .card-header .row {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-lg {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
    
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn-circle {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
}
</style>

<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Confirmación para eliminar
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const formId = this.getAttribute('data-form-id');
            const form = document.getElementById(formId);
            const proveedorId = this.getAttribute('data-proveedor');
            const nombre = this.getAttribute('data-nombre');
            const nit = this.getAttribute('data-nit');
            
            Swal.fire({
                title: '¿Eliminar proveedor?',
                html: `
                    <div class="text-start">
                        <p><strong>ID:</strong> ${proveedorId}</p>
                        <p><strong>Nombre:</strong> ${nombre}</p>
                        <p><strong>NIT:</strong> ${nit}</p>
                        <p class="text-danger mt-3">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            ¡Esta acción no se puede deshacer!
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Búsqueda en tiempo real
    const searchInput = document.getElementById('buscar');
    const formBusqueda = document.getElementById('form-busqueda');
    let timer;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                formBusqueda.submit();
            }, 500);
        });

        // También búsqueda en la tabla (filtro local)
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#proveedoresTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    // Sistema de Alertas
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#36b9cc',
            confirmButtonText: 'Aceptar',
            timer: 3000,
            timerProgressBar: true
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
});
</script>
@endsection
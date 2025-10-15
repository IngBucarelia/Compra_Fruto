@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header con Estadísticas -->
    <div class="row mb-4">
        <div class="col-12"> 
            <div class="card  shadow-lg border-0" style="background-color: #206227a5">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="text-white mb-1">
                                {{-- En la parte superior de tu vista index --}}
                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            @endif

                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            @endif
                                <i class="fas fa-calendar-alt me-2"></i>Planificaciones de Visitas Sociales
                            </h2>
                            <p class="text-white opacity-8 mb-0">
                                Gestiona y organiza todas las visitas sociales programadas
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="bg-white rounded-pill px-3 py-1 d-inline-block">
                                <span class="text-primary fw-bold fs-5">{{ $planificaciones->total() }}</span>
                                <span class="text-dark">Planificaciones</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Acción Rápida -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    @if(Auth::check() && in_array(Auth::user()->rol, [1,3]))
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Nueva Planificación
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('planificaciones_social.create') }}" class="btn btn-primary btn-circle">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Vista Calendario
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('planificaciones_social.calendario') }}" class="btn btn-info btn-circle">
                                <i class="fas fa-calendar"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pendientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $planificaciones->where('estado', 'pendiente')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Realizadas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $planificaciones->where('estado', 'realizada')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Planificaciones -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-2"></i>Lista de Planificaciones
                </h6>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Buscar...">
                    <button class="btn btn-sm btn-outline-primary" onclick="sortTable()">
                        <i class="fas fa-sort"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0" id="planificacionesTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Fecha</th>
                            <th>Técnico</th>
                            <th>Proveedor</th>
                            <th>Plantación</th>
                            <th>Tipo Visita</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($planificaciones as $planificacion)
                            <tr class="align-middle">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-day text-primary me-2"></i>
                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($planificacion->fecha)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($planificacion->tecnico->name ?? 'N/A') }}&background=4e73df&color=fff&size=32" 
                                             class="rounded-circle me-2" width="32" height="32">
                                        <span>{{ $planificacion->tecnico->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <i class="fas fa-building me-1"></i>
                                        {{ $planificacion->proveedor->proveedor_nombre ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-seedling me-1"></i>
                                        {{ $planificacion->plantacion->nombre ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $tipoIcon = [
                                            'seguimiento' => '📋',
                                            'evaluacion' => '📊', 
                                            'capacitacion' => '🎓'
                                        ][$planificacion->tipo_visita] ?? '📝';
                                    @endphp
                                    <span class="badge bg-secondary">
                                        {{ $tipoIcon }} {{ ucfirst($planificacion->tipo_visita) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $estadoConfig = [
                                            'pendiente' => ['class' => 'warning', 'icon' => '⏰'],
                                            'realizada' => ['class' => 'success', 'icon' => '✅'],
                                            'cancelada' => ['class' => 'danger', 'icon' => '❌']
                                        ][$planificacion->estado] ?? ['class' => 'secondary', 'icon' => '❓'];
                                    @endphp
                                    <span class="badge bg-{{ $estadoConfig['class'] }}">
                                        {{ $estadoConfig['icon'] }} {{ ucfirst($planificacion->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('visitas_social.showSocial', $planificacion->id) }}" 
                                           class="btn btn-sm btn-info btn-circle"
                                           data-bs-toggle="tooltip" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::check() && in_array(Auth::user()->rol, [1,3]))
                                        <a href="{{ route('planificaciones_social.edit', $planificacion->id) }}" 
                                           class="btn btn-sm btn-warning btn-circle"
                                           data-bs-toggle="tooltip" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        @if(Auth::check() && in_array(Auth::user()->rol, [1]))
                                        <form action="{{ route('planificaciones_social.destroy', $planificacion->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-circle delete-btn"
                                                    data-bs-toggle="tooltip" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay planificaciones registradas</h5>
                                        <p class="text-muted">Comienza creando tu primera planificación</p>
                                        <a href="{{ route('planificaciones_social.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus me-2"></i>Crear Planificación
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        @if($planificaciones->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Mostrando {{ $planificaciones->firstItem() }} - {{ $planificaciones->lastItem() }} de {{ $planificaciones->total() }} registros
                </div>
                <nav aria-label="Page navigation">
                    {{ $planificaciones->links('vendor.pagination.bootstrap-5') }}
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: 2px solid #e3e6f0;
}

.btn-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-circle:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.badge {
    font-size: 0.85em;
    padding: 0.5em 0.8em;
    border-radius: 10px;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #35a240;
    text-transform: uppercase;
    font-size: 0.85em;
}

.table td {
    vertical-align: middle;
    border-color: #e3e6f0;
}

.empty-state {
    padding: 3rem 1rem;
}

.border-left-primary { border-left: 4px solid #35a240 !important; }
.border-left-info { border-left: 4px solid #36b9cc !important; }
.border-left-warning { border-left: 4px solid #f6c23e !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }

.shadow-lg {
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #4e73df, #224abe) !important;
}

/* Hover effects */
.table-hover tbody tr:hover {
    background-color: rgba(78, 115, 223, 0.05);
    transform: scale(1.01);
    transition: all 0.2s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .btn-circle {
        width: 30px;
        height: 30px;
    }
    
    .table-responsive {
        font-size: 0.9em;
    }
}
</style>

<script>
// Tooltip initialization
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Delete confirmation
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4e73df',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#planificacionesTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
});

function sortTable() {
    const table = document.getElementById('planificacionesTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const dateA = new Date(a.cells[0].textContent);
        const dateB = new Date(b.cells[0].textContent);
        return dateB - dateA;
    });
    
    // Remove existing rows
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    
    // Add sorted rows
    rows.forEach(row => tbody.appendChild(row));
}
</script>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
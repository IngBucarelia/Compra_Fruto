@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header con Estadísticas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card  shadow-lg border-0" style="background-color: #206227a5">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="text-white mb-1">
                                <i class="fas fa-users me-2"></i>Visitas Sociales
                            </h2>
                            <p class="text-white opacity-8 mb-0">
                                Gestión y seguimiento de todas las visitas del componente social
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="bg-white rounded-pill px-3 py-1 d-inline-block">
                                <span class="text-success fw-bold fs-5">{{ $visitas->total() }}</span>
                                <span class="text-dark">Visitas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Nueva Visita
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('visitas_social.createSocial') }}" class="btn btn-primary btn-circle">
                                <i class="fas fa-plus"></i>
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
                                {{ $visitas->where('estado', 'pendiente')->count() }}
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
                                {{ $visitas->where('estado', 'realizada')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Planificadas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $visitas->where('planificacion_id', '!=', null)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Búsqueda y Filtros -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('visitas_social.indexSocial') }}" id="searchForm">
                        <div class="input-group">
                            <span class="input-group-text bg-gradient-success text-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="buscar" class="form-control form-control-lg" 
                                   placeholder="Buscar por proveedor, técnico, tipo de visita..."
                                   value="{{ request('buscar') }}"
                                   oninput="debounceSearch()">
                            <button class="btn btn-success" type="submit">
                                <i class="fas fa-filter me-2"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group">
                        <button class="btn btn-outline-success dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-download me-2"></i>Exportar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Visitas -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-list me-2"></i>Lista de Visitas Sociales
                </h6>
                <span class="badge bg-success">
                    {{ $visitas->count() }} de {{ $visitas->total() }} registros
                </span>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0" id="visitasTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Fecha</th>
                            <th>Proveedor</th>
                            <th>Plantación</th>
                            <th>Tipo Visita</th>
                            <th>Estado</th>
                            <th>Origen</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($visitas as $visita)
                            <tr class="align-middle">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar-day text-success me-2"></i>
                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($visita->fecha)->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $visita->proveedor->proveedor_nombre ?? '-' }}</div>
                                            <small class="text-muted">Proveedor</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-seedling me-1"></i>
                                        {{ $visita->plantacion->nombre ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $tipoIcon = [
                                            'seguimiento' => '📋',
                                            'evaluacion' => '📊', 
                                            'capacitacion' => '🎓',
                                            'social' => '👥'
                                        ][$visita->tipo_visita] ?? '📝';
                                    @endphp
                                    <span class="badge bg-secondary">
                                        {{ $tipoIcon }} {{ ucfirst($visita->tipo_visita) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $estadoConfig = [
                                            'pendiente' => ['class' => 'warning', 'icon' => '⏰'],
                                            'realizada' => ['class' => 'success', 'icon' => '✅'],
                                            'cancelada' => ['class' => 'danger', 'icon' => '❌']
                                        ][$visita->estado] ?? ['class' => 'secondary', 'icon' => '❓'];
                                    @endphp
                                    <span class="badge bg-{{ $estadoConfig['class'] }}">
                                        {{ $estadoConfig['icon'] }} {{ ucfirst($visita->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $visita->planificacion ? 'info' : 'secondary' }}">
                                        <i class="fas fa-{{ $visita->planificacion ? 'calendar-check' : 'user-plus' }} me-1"></i>
                                        {{ $visita->planificacion ? 'Planificada' : 'Manual' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('visitas_social.showSocial', $visita->id) }}" 
                                           class="btn btn-sm btn-info btn-circle"
                                           data-bs-toggle="tooltip" title="Ver detalles de la visita">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('visitas_social.editSocial', $visita->id) }}" 
                                           class="btn btn-sm btn-warning btn-circle"
                                           data-bs-toggle="tooltip" title="Editar visita">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('visitas_social.destroySocial', $visita->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-circle delete-btn"
                                                    data-bs-toggle="tooltip" title="Eliminar visita">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay visitas sociales registradas</h5>
                                        <p class="text-muted">Comienza creando tu primera visita social</p>
                                        <a href="{{ route('visitas_social.createSocial') }}" class="btn btn-success mt-2">
                                            <i class="fas fa-plus me-2"></i>Crear Visita Social
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
        @if($visitas->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Mostrando {{ $visitas->firstItem() }} - {{ $visitas->lastItem() }} de {{ $visitas->total() }} registros
                </div>
                <nav aria-label="Page navigation">
                    {{ $visitas->links('vendor.pagination.bootstrap-5') }}
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
    color: #1cc88a;
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

.border-left-primary { border-left: 4px solid #4e73df !important; }
.border-left-warning { border-left: 4px solid #f6c23e !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }
.border-left-info { border-left: 4px solid #36b9cc !important; }

.shadow-lg {
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
}

.bg-gradient-success {
    background: linear-gradient(45deg, #1cc88a, #13855c) !important;
}

.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 1rem;
}

/* Hover effects */
.table-hover tbody tr:hover {
    background-color: rgba(28, 200, 138, 0.05);
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
                text: "Esta acción eliminará la visita permanentemente",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#1cc88a',
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

    // Búsqueda con debounce
    let searchTimer;
    window.debounceSearch = function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 500);
    }
});

// Ordenamiento de tabla
function sortTable(columnIndex) {
    const table = document.getElementById('visitasTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const textA = a.cells[columnIndex].textContent.trim();
        const textB = b.cells[columnIndex].textContent.trim();
        
        if (columnIndex === 0) {
            // Ordenar por fecha
            return new Date(textA) - new Date(textB);
        }
        return textA.localeCompare(textB);
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
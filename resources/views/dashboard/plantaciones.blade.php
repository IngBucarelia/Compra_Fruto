@extends('layouts.app')

@section('content')
<div class="container mt-4" style="background-color: whitesmoke">
    <!-- Header con Estadísticas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0" style="background-color: #206227d4">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="text-white mb-1">
                                <i class="fas fa-tree me-2"></i>Dashboard de Plantaciones
                            </h2>
                            <p class="text-white opacity-8 mb-0">
                                Gestión y seguimiento de todas las plantaciones y sus visitas
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="bg-white rounded-pill px-3 py-1 d-inline-block">
                                <span class="text-primary fw-bold fs-5">{{ $plantaciones->total() }}</span>
                                <span class="text-dark">Plantaciones</span>
                            </div>
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
                    <form method="GET" action="{{ route('dashboard.plantaciones') }}" id="searchForm">
                        <div class="input-group">
                            <span class="input-group-text bg-gradient-primary text-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="buscar" class="form-control form-control-lg" 
                                   placeholder="Buscar por nombre de plantación o proveedor..."
                                   value="{{ request('buscar') }}"
                                   oninput="debounceSearch()">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-filter me-2"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-end">
                    <div class="btn-group">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-download me-2"></i>Exportar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                        </ul>
                    </div>
                    <a href="{{ route('plantaciones.create') }}" class="btn btn-info">
                                                    <i class="fas fa-plus me-2"></i>Crear Plantación
                                                </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicador de Loading -->
    <div id="loadingIndicator" class="d-none text-center py-3">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Buscando...</span>
        </div>
        <span class="ms-2 text-primary">Buscando plantaciones...</span>
    </div>

    <!-- Tabla de Plantaciones -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list me-2"></i>Lista de Plantaciones
                </h6>
                <span class="badge bg-primary">
                    {{ $plantaciones->count() }} de {{ $plantaciones->total() }} registros
                </span>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0" id="tabla-dashboard">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Plantación</th>
                            <th>Proveedor</th>
                            <th class="text-center">Visitas Agronómicas</th>
                            <th class="text-center">Visitas Sociales</th>
                            <th>Última Visita</th>
                            <th>Estado Proveedor</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($plantaciones as $p)
                            <tr class="align-middle">
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-tree"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-primary">{{ $p->nombre }}</div>
                                            <small class="text-muted">Plantación</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $p->proveedor->proveedor_nombre ?? 'N/A' }}</div>
                                            <small class="text-muted">Proveedor</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="fas fa-seedling me-1"></i>
                                        {{ $p->visitas->count() }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-white fs-6">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $p->visitasSociales->count() }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $ultimaAgro = optional($p->visitas->sortByDesc('fecha')->first())->fecha;
                                        $ultimaSocial = optional($p->visitasSociales->sortByDesc('fecha')->first())->fecha;
                                        $ultima = collect([$ultimaAgro, $ultimaSocial])->filter()->max();
                                    @endphp
                                    @if($ultima)
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar-day text-primary me-2"></i>
                                            <span class="fw-bold">{{ \Carbon\Carbon::parse($ultima)->format('d/m/Y') }}</span>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">Sin visitas</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $estado = $p->proveedor->estado ?? 'inactivo';
                                        $estadoConfig = [
                                            'activo' => ['class' => 'success', 'icon' => '✓'],
                                            'inactivo' => ['class' => 'danger', 'icon' => '✗'],
                                            'pendiente' => ['class' => 'warning', 'icon' => '⏰']
                                        ][$estado] ?? ['class' => 'secondary', 'icon' => '?'];
                                    @endphp
                                    <span class="badge bg-{{ $estadoConfig['class'] }}">
                                        {{ $estadoConfig['icon'] }} {{ ucfirst($estado) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('dashboard.plantaciones.visitas', $p->id) }}" 
                                           class="btn btn-sm btn-primary btn-circle"
                                           data-bs-toggle="tooltip" title="Ver visitas de esta plantación">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(Auth::check() && in_array(Auth::user()->rol, [1,2]))
                                        <a href="{{ route('plantaciones.show', $p->id) }}" 
                                           class="btn btn-sm btn-info btn-circle"
                                           data-bs-toggle="tooltip" title="Detalles de plantación">
                                            <i class="fas fa-info"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-tree fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No hay plantaciones registradas</h5>
                                        <p class="text-muted">No se encontraron plantaciones con los criterios de búsqueda</p>
                                        @if(request('buscar'))
                                            <a href="{{ route('dashboard.plantaciones') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-times me-2"></i>Limpiar búsqueda
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        @if($plantaciones->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Mostrando {{ $plantaciones->firstItem() }} - {{ $plantaciones->lastItem() }} de {{ $plantaciones->total() }} registros
                </div>
                <nav aria-label="Page navigation">
                    {{ $plantaciones->links('vendor.pagination.bootstrap-5') }}
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
    color: #2b7833;
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

.bg-gradient-primary {
    background: linear-gradient(45deg, #4edf51, #73be22) !important;
}

.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 1rem;
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
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group .form-control {
        margin-bottom: 10px;
    }
}
</style>

<script>
// Tooltip initialization
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

// Búsqueda con debounce y AJAX
let searchTimer;
const debounceSearch = function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        // Mostrar loading
        document.getElementById('loadingIndicator').classList.remove('d-none');
        
        // Obtener formulario y datos
        const form = document.getElementById('searchForm');
        const formData = new FormData(form);
        
        // Usar Fetch API para búsqueda AJAX
        fetch(`${form.action}?${new URLSearchParams(formData)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Actualizar solo la tabla y paginación
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const newTableContent = doc.querySelector('.table-responsive');
            const newPagination = doc.querySelector('.card-footer');
            
            if (newTableContent) {
                document.querySelector('.table-responsive').innerHTML = newTableContent.innerHTML;
            }
            
            if (newPagination) {
                document.querySelector('.card-footer').innerHTML = newPagination.innerHTML;
            } else {
                document.querySelector('.card-footer').innerHTML = '';
            }
            
            // Actualizar contador del header
            const newBadge = doc.querySelector('.card-header .badge');
            if (newBadge) {
                document.querySelector('.card-header .badge').innerHTML = newBadge.innerHTML;
            }
            
            document.getElementById('loadingIndicator').classList.add('d-none');
            
            // Re-inicializar tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loadingIndicator').classList.add('d-none');
            // Fallback: submit normal
            form.submit();
        });
    }, 300);
}

// Limpiar búsqueda
function clearSearch() {
    document.querySelector('input[name="buscar"]').value = '';
    debounceSearch();
}
</script>

<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

@endsection
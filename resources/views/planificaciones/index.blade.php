@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header con Estadísticas -->
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-success py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-black">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Planificaciones Agronómicas
                            </h4>
                            <p class="mb-0 text-black-50 small">Gestión de visitas planificadas para el área agronómica</p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-white text-success fs-6 p-2">
                                <i class="fas fa-leaf me-1"></i>
                                Total: {{ $planificaciones->total() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Principal -->
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="d-flex gap-2 flex-wrap">
                                 @if(Auth::check() && in_array(Auth::user()->rol, [1,2]))
                                <a href="{{ route('planificaciones.create') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>Nueva Planificación
                                </a>
                                @endif
                                <a href="{{ route('planificaciones.calendario') }}" class="btn btn-info btn-lg">
                                    <i class="fas fa-calendar me-2"></i>Ver Calendario
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Buscador (opcional) -->
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Buscar planificación..." id="searchInput">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Tabla Responsive -->
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0" id="planificacionesTable">
                            <thead class="table-success">
                                <tr>
                                    <th class="ps-4">
                                        <i class="fas fa-calendar-day me-2"></i>Fecha
                                    </th>
                                    <th>
                                        <i class="fas fa-user-check me-2"></i>Técnico
                                    </th>
                                    <th>
                                        <i class="fas fa-building me-2"></i>Proveedor
                                    </th>
                                    <th>
                                        <i class="fas fa-seedling me-2"></i>Plantación
                                    </th>
                                    <th>
                                        <i class="fas fa-tasks me-2"></i>Tipo Visita
                                    </th>
                                    <th>
                                        <i class="fas fa-info-circle me-2"></i>Estado
                                    </th>
                                    <th class="text-center pe-4">
                                        <i class="fas fa-cogs me-2"></i>Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($planificaciones as $p)
                                    <tr class="align-middle">
                                        <td class="ps-4">
                                            <div class="fw-bold text-primary">
                                                {{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') }}
                                            </div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($p->fecha)->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-user text-white fs-6"></i>
                                                </div>
                                                <span>{{ $p->tecnico->name ?? 'No asignado' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">🏢 {{ $p->proveedor->proveedor_nombre }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-semibold">🌱 {{ $p->plantacion->nombre }}</div>
                                                <small class="text-muted">{{ $p->plantacion->vereda }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $tipoBadge = [
                                                    'Inicial' => 'bg-primary',
                                                    'Seguimiento' => 'bg-info', 
                                                    'Evaluacion' => 'bg-warning',
                                                    'Mantenimiento' => 'bg-secondary'
                                                ][$p->tipo_visita] ?? 'bg-dark';
                                            @endphp
                                            <span class="badge {{ $tipoBadge }}">
                                                {{ $p->tipo_visita }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $estadoBadge = [
                                                    'pendiente' => 'bg-warning',
                                                    'completada' => 'bg-success',
                                                    'cancelada' => 'bg-danger',
                                                    'en_proceso' => 'bg-info'
                                                ][$p->estado] ?? 'bg-secondary';
                                            @endphp
                                            <span class="badge {{ $estadoBadge }}">
                                                <i class="fas fa-circle me-1 fs-6"></i>
                                                {{ ucfirst($p->estado) }}
                                            </span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <div class="d-flex justify-content-center gap-2">
                                                <!-- Ver -->
                                                <a href="{{ route('planificaciones.show', $p->id) }}" 
                                                   class="btn btn-info btn-sm btn-circle"
                                                   data-bs-toggle="tooltip" title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                 @if(Auth::check() && in_array(Auth::user()->rol, [1,2]))
                                                <!-- Editar -->
                                                <a href="{{ route('planificaciones.edit', $p->id) }}" 
                                                   class="btn btn-warning btn-sm btn-circle"
                                                   data-bs-toggle="tooltip" title="Editar planificación">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <!-- Eliminar con confirmación -->
<<<<<<< HEAD
=======
                                                 @if(Auth::check() && in_array(Auth::user()->rol, [1]))
>>>>>>> cd467f5 (todo terminado componente social y agronomico y anexado los permisos de usabilidad por roles de usuario)
                                               <form action="{{ route('planificaciones.destroy', $p->id) }}" method="POST" class="d-inline" id="deleteForm-{{ $p->id }}">
                                                @csrf 
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm btn-circle delete-btn"
                                                        data-bs-toggle="tooltip" 
                                                        title="Eliminar planificación"
                                                        data-planificacion="{{ $p->id }}"
                                                        data-tecnico="{{ $p->tecnico->name ?? 'N/A' }}"
                                                        data-fecha="{{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') }}"
                                                        data-form-id="deleteForm-{{ $p->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <h5>No hay planificaciones registradas</h5>
                                                <p>Comienza creando una nueva planificación agronómica</p>
                                                <a href="{{ route('planificaciones.create') }}" class="btn btn-success">
                                                    <i class="fas fa-plus me-2"></i>Crear Primera Planificación
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($planificaciones->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Mostrando {{ $planificaciones->firstItem() }} - {{ $planificaciones->lastItem() }} de {{ $planificaciones->total() }} registros
                            </div>
                            <div>
                                {{ $planificaciones->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Botón Cancelar -->
            <div class="text-end mt-3">
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
    color: #2e7d32;
}

.table-hover tbody tr:hover {
    background-color: rgba(28, 200, 138, 0.05) !important;
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

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 10px !important;
         width: 115%;
        margin-left: -40px;
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
    // Confirmación para eliminar
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const formId = this.getAttribute('data-form-id');
            const form = document.getElementById(formId);
            const planificacionId = this.getAttribute('data-planificacion');
            const tecnico = this.getAttribute('data-tecnico');
            const fecha = this.getAttribute('data-fecha');
            
            Swal.fire({
                title: '¿Eliminar planificación?',
                html: `
                    <div class="text-start">
                        <p><strong>ID:</strong> ${planificacionId}</p>
                        <p><strong>Técnico:</strong> ${tecnico}</p>
                        <p><strong>Fecha:</strong> ${fecha}</p>
                        <p class="text-danger mt-3">¡Esta acción no se puede deshacer!</p>
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
                    form.submit(); // Envía el formulario correctamente
                }
            });
        });
    });
    // Búsqueda en tiempo real (opcional)
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

    // Sistema de Alertas
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#1cc88a',
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
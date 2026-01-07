@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background-image: url('{{ asset('images/fondo_agronomico.png') }}');
    }
    
    .container-dashboard {
        width: 90%;
        padding: 20px;
        background-color: #5282289d;
        border-radius: 35px;
        margin: 20px auto;
    }
    
    .card-chart {
        border-radius: 12px;
        background: rgb(173, 173, 173);
        padding: 12px;
        color: #fff;
        margin-bottom: 18px;
        min-height: 260px;
    }
    
    .button-33 {
        background-color: #073c1a;
        border-radius: 100px;
        box-shadow: rgba(44, 187, 99, .2) 0 -25px 18px -14px inset,
                    rgba(44, 187, 99, .15) 0 1px 2px,
                    rgba(44, 187, 99, .15) 0 2px 4px,
                    rgba(44, 187, 99, .15) 0 4px 8px,
                    rgba(44, 187, 99, .15) 0 8px 16px,
                    rgba(44, 187, 99, .15) 0 16px 32px;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        padding: 8px 22px;
        text-align: center;
        text-decoration: none;
        transition: all 250ms;
        border: 0;
        font-size: 15px;
        user-select: none;
    }
    
    .button-33:hover {
        box-shadow: rgba(44,187,99,.35) 0 -25px 18px -14px inset,
                    rgba(44,187,99,.25) 0 1px 2px,
                    rgba(44,187,99,.25) 0 2px 4px,
                    rgba(44,187,99,.25) 0 4px 8px,
                    rgba(44,187,99,.25) 0 8px 16px,
                    rgba(44,187,99,.25) 0 16px 32px;
        transform: scale(1.05) rotate(-1deg);
    }
    
    .table-container {
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 20px;
        margin-top: 20px;
    }
    
    .card-header-custom {
        background: linear-gradient(135deg, #2e7d32, #1b5e20);
        color: white;
        border-radius: 10px 10px 0 0 !important;
        padding: 15px;
        font-weight: bold;
    }
    
    .card-body-custom {
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 0 0 10px 10px;
    }
    
    .table-custom {
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .table-custom thead {
        background-color: #2e7d32;
        color: white;
    }
    
    .table-custom tbody tr:hover {
        background-color: rgba(46, 125, 50, 0.1);
    }
    
    .badge-puntaje {
        font-size: 0.9em;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: bold;
    }
    
    .badge-excelente { background-color: #28a745; color: white; }
    .badge-muy-bueno { background-color: #17a2b8; color: white; }
    .badge-bueno { background-color: #007bff; color: white; }
    .badge-regular { background-color: #ffc107; color: #212529; }
    .badge-mejora { background-color: #dc3545; color: white; }
    
    .btn-action {
        padding: 5px 10px;
        border-radius: 5px;
        margin: 2px;
        transition: all 0.3s;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .btn-view { background-color: #17a2b8; color: white; }
    .btn-pdf { background-color: #dc3545; color: white; }
    .btn-edit { background-color: #ffc107; color: #212529; }
    .btn-delete { background-color: #6c757d; color: white; }
    
    .filtros-card {
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        border: 2px solid #2e7d32;
    }
    
    .pagination-custom .page-item.active .page-link {
        background-color: #2e7d32;
        border-color: #2e7d32;
        color: white;
    }
    
    .pagination-custom .page-link {
        color: #2e7d32;
    }
    
    .pagination-custom .page-link:hover {
        background-color: rgba(46, 125, 50, 0.1);
        color: #1b5e20;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .container-dashboard {
            width: 95%;
            padding: 10px;
        }
        
        .button-33 {
            padding: 6px 15px;
            font-size: 14px;
        }
        
        .table-responsive {
            font-size: 0.9em;
        }
        
        .btn-action {
            padding: 3px 6px;
            font-size: 0.8em;
        }
    }
</style>

<div class="container container-dashboard">
    <!-- Título -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 class="text-white">📋 Evaluaciones IBT (Índice de Buenas Prácticas)</h3>
            <p style="color: #e0e0e0">Gestión de evaluaciones de nivel tecnológico en cultivos de palma de aceite</p>
        </div>
        
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            {{-- 🔹 Botones principales a la izquierda --}}
            <div>
                <a href="{{ route('evaluaciones-ibt.create') }}" class="button-33 me-2">
                    <i class="fas fa-plus-circle"></i> Nueva Evaluación
                </a>
                <a href="{{ route('evaluaciones-ibt.dashboard') }}" class="button-33">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </a>
            </div>

            {{-- 🔹 Botón Reset a la derecha --}}
            <div>
                <a href="{{ route('evaluaciones-ibt.index') }}" class="btn btn-light btn-sm fw-bold shadow-sm">
                    🔁 Reset filtros
                </a>
            </div>
        </div>
    </div>

    <!-- FILTROS -->
    <div class="card mb-4 filtros-card">
        <div class="card-body p-3">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" 
                           value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" 
                           value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Proveedor</label>
                    <select name="proveedor" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        @foreach($proveedores as $p)
                            <option value="{{ $p->id }}" {{ request('proveedor') == $p->id ? 'selected' : '' }}>
                                {{ $p->proveedor_nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Plantación</label>
                    <select name="plantacion" class="form-control form-control-sm">
                        <option value="">Todas</option>
                        @foreach($plantaciones as $pl)
                            <option value="{{ $pl->id }}" {{ request('plantacion') == $pl->id ? 'selected' : '' }}>
                                {{ $pl->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" style="visibility: hidden;">.</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-sm flex-grow-1">
                            <i class="fas fa-filter"></i> Aplicar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLA DE EVALUACIONES -->
    <div class="table-container">
        @if($evaluaciones->count() > 0)
        <div class="table-responsive">
            <table class="table table-custom table-hover">
                <thead>
                    <tr class="text-center">
                        <th width="5%">ID</th>
                        <th width="12%">Fecha</th>
                        <th width="20%">Proveedor</th>
                        <th width="18%">Plantación</th>
                        <th width="12%">Puntaje</th>
                        <th width="15%">Calificación</th>
                        <th width="18%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluaciones as $evaluacion)
                    <tr>
                        <td class="text-center">
                            <strong class="text-primary">#{{ $evaluacion->id }}</strong>
                        </td>
                        <td class="text-center">
                            {{ date('d/m/Y', strtotime($evaluacion->fecha_evaluacion)) }}
                            <br>
                            <small class="text-muted">{{ $evaluacion->tecnico->name ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <strong>{{ $evaluacion->proveedor->proveedor_nombre ?? 'N/A' }}</strong>
                            @if($evaluacion->visita)
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-link"></i> Visita #{{ $evaluacion->visita->id }}
                            </small>
                            @endif
                        </td>
                        <td>{{ $evaluacion->plantacion->nombre ?? 'N/A' }}</td>
                        <td class="text-center">
                            <div class="d-flex flex-column align-items-center">
                                <span class="badge-puntaje 
                                    {{ $evaluacion->puntaje_total >= 90 ? 'badge-excelente' : 
                                       ($evaluacion->puntaje_total >= 80 ? 'badge-muy-bueno' : 
                                       ($evaluacion->puntaje_total >= 70 ? 'badge-bueno' : 
                                       ($evaluacion->puntaje_total >= 60 ? 'badge-regular' : 'badge-mejora'))) }}">
                                    {{ number_format($evaluacion->puntaje_total, 1) }}/100
                                </span>
                                <small class="text-muted mt-1">
                                    {{ number_format(($evaluacion->puntaje_total/100)*100, 0) }}%
                                </small>
                            </div>
                        </td>
                        <td class="text-center">
                            @php
                                $badgeClass = '';
                                $icon = '';
                                if($evaluacion->calificacion == 'Excelente') {
                                    $badgeClass = 'badge-excelente';
                                    $icon = 'fa-trophy';
                                } elseif($evaluacion->calificacion == 'Muy Bueno') {
                                    $badgeClass = 'badge-muy-bueno';
                                    $icon = 'fa-star';
                                } elseif($evaluacion->calificacion == 'Bueno') {
                                    $badgeClass = 'badge-bueno';
                                    $icon = 'fa-thumbs-up';
                                } elseif($evaluacion->calificacion == 'Regular') {
                                    $badgeClass = 'badge-regular';
                                    $icon = 'fa-check';
                                } else {
                                    $badgeClass = 'badge-mejora';
                                    $icon = 'fa-exclamation-triangle';
                                }
                            @endphp
                            <span class="badge-puntaje {{ $badgeClass }}">
                                <i class="fas {{ $icon }} me-1"></i>
                                {{ $evaluacion->calificacion }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('evaluaciones-ibt.show', $evaluacion->id) }}" 
                                   class="btn btn-action btn-view" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('evaluaciones-ibt.exportar-pdf', $evaluacion->id) }}" 
                                   class="btn btn-action btn-pdf" title="Exportar PDF" target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('evaluaciones-ibt.edit', $evaluacion->id) }}" 
                                   class="btn btn-action btn-edit" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('evaluaciones-ibt.destroy', $evaluacion->id) }}" 
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Está seguro de eliminar la evaluación #{{ $evaluacion->id }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-delete" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> 
                                    {{ date('H:i', strtotime($evaluacion->created_at)) }}
                                </small>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- PAGINACIÓN -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Mostrando {{ $evaluaciones->firstItem() }} - {{ $evaluaciones->lastItem() }} de {{ $evaluaciones->total() }} evaluaciones
                <br>
                <small>Promedio general: <strong>{{ number_format($evaluaciones->avg('puntaje_total') ?? 0, 1) }}</strong> puntos</small>
            </div>
            
            <nav aria-label="Page navigation" class="pagination-custom">
                {{ $evaluaciones->links() }}
            </nav>
        </div>
        @else
        <!-- SIN DATOS -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-clipboard-list fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted mb-3">No hay evaluaciones IBT registradas</h4>
            <p class="text-muted mb-4">Comienza creando tu primera evaluación de nivel tecnológico.</p>
            <a href="{{ route('evaluaciones-ibt.create') }}" class="button-33">
                <i class="fas fa-plus-circle"></i> Crear Primera Evaluación
            </a>
        </div>
        @endif
    </div>

    <!-- BOTONES FINALES -->
    <div class="d-flex flex-wrap align-items-center mt-4">
        <a href="{{ route('evaluaciones-ibt.create') }}" class="button-33 me-2 mb-2">
            <i class="fas fa-plus-circle"></i> Nueva Evaluación
        </a>
        <a href="{{ route('evaluaciones-ibt.dashboard') }}" class="button-33 me-2 mb-2">
            <i class="fas fa-chart-bar"></i> Ver Dashboard
        </a>
        <a href="{{ url()->previous() }}" class="button-33 mb-2">
            <i class="fas fa-arrow-left"></i> Volver atrás
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltips para botones
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Confirmación para eliminar
    const deleteForms = document.querySelectorAll('form[onsubmit*="confirm"]');
    deleteForms.forEach(form => {
        form.onsubmit = function(e) {
            const evaluacionId = this.action.split('/').pop();
            return confirm(`¿Está seguro de eliminar la evaluación #${evaluacionId}?\nEsta acción no se puede deshacer.`);
        };
    });
    
    // Auto-focus en el primer filtro
    const firstFilter = document.querySelector('input[type="date"], select');
    if (firstFilter) {
        firstFilter.focus();
    }
});
</script>
@endsection
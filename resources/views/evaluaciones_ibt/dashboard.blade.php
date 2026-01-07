<!-- resources/views/evaluaciones_ibt/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>📊 Dashboard de Evaluaciones IBT</h2>
    
    <!-- Filtros -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label>Proveedor</label>
                    <select name="proveedor" class="form-control">
                        <option value="">Todos</option>
                        @foreach($proveedores as $p)
                            <option value="{{ $p->id }}" {{ request('proveedor') == $p->id ? 'selected' : '' }}>{{ $p->proveedor_nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Plantación</label>
                    <select name="plantacion" class="form-control">
                        <option value="">Todas</option>
                        @foreach($plantaciones as $pl)
                            <option value="{{ $pl->id }}" {{ request('plantacion') == $pl->id ? 'selected' : '' }}>{{ $pl->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Desde</label>
                    <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-2">
                    <label>Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="{{ route('evaluaciones-ibt.dashboard') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center bg-primary text-white">
                <div class="card-body">
                    <h1 class="display-4">{{ $data['total_evaluaciones'] }}</h1>
                    <p class="card-text">Evaluaciones Realizadas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-success text-white">
                <div class="card-body">
                    <h1 class="display-4">{{ number_format($data['promedio_puntaje'], 1) }}</h1>
                    <p class="card-text">Puntaje Promedio</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-info text-white">
                <div class="card-body">
                    <h1 class="display-4">{{ $data['mejores_proveedores']->first()['promedio'] ?? 0 }}</h1>
                    <p class="card-text">Mejor Proveedor</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center bg-warning text-white">
                <div class="card-body">
                    <h1 class="display-4">{{ optional($data['distribucion_calificaciones']->get('Excelente', 0)) }}</h1>
                    <p class="card-text">Evaluaciones Excelentes</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Distribución por Calificación</div>
                <div class="card-body">
                    <canvas id="chartCalificaciones"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Evolución Temporal</div>
                <div class="card-body">
                    <canvas id="chartEvolucion"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Proveedores -->
    <div class="card mb-4">
        <div class="card-header">Top 5 Proveedores por Puntaje</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Proveedor</th>
                        <th>Puntaje Promedio</th>
                        <th>Evaluaciones</th>
                        <th>Calificación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['mejores_proveedores'] as $proveedor)
                    <tr>
                        <td>{{ $proveedor['nombre'] }}</td>
                        <td>{{ number_format($proveedor['promedio'], 1) }}</td>
                        <td>{{ $proveedor['total'] }}</td>
                        <td>
                            @if($proveedor['promedio'] >= 90)
                                <span class="badge bg-success">Excelente</span>
                            @elseif($proveedor['promedio'] >= 80)
                                <span class="badge bg-primary">Muy Bueno</span>
                            @elseif($proveedor['promedio'] >= 70)
                                <span class="badge bg-info">Bueno</span>
                            @elseif($proveedor['promedio'] >= 60)
                                <span class="badge bg-warning">Regular</span>
                            @else
                                <span class="badge bg-danger">Necesita Mejora</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de distribución por calificación
    const ctx1 = document.getElementById('chartCalificaciones').getContext('2d');
    const calificaciones = @json($data['distribucion_calificaciones']);
    
    new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: Object.keys(calificaciones),
            datasets: [{
                data: Object.values(calificaciones),
                backgroundColor: [
                    '#28a745', // Excelente
                    '#007bff', // Muy Bueno
                    '#17a2b8', // Bueno
                    '#ffc107', // Regular
                    '#dc3545'  // Necesita Mejora
                ]
            }]
        }
    });
    
    // Gráfico de evolución temporal
    const ctx2 = document.getElementById('chartEvolucion').getContext('2d');
    const evolucion = @json($data['evolucion_temporal']);
    
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: Object.values(evolucion).map(e => e.mes),
            datasets: [{
                label: 'Puntaje Promedio',
                data: Object.values(evolucion).map(e => e.promedio),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true
            }]
        }
    });
});
</script>
@endsection
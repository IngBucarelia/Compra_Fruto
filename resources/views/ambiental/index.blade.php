@extends('layouts.app')

@section('content')
<div class="container-fluid px-3">

    <!-- Header con Estadísticas -->
    <div class="row mb-4 justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-lg border-0" style="background-color: #206227a5;">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="text-white mb-1">
                                🌿 Visitas Ambientales
                            </h2>
                            <p class="text-white opacity-8 mb-0">
                                Gestión y seguimiento de todas las visitas del componente ambiental
                            </p>
                        </div>

                        <div class="col-md-4 text-end">
                            <div class="bg-white rounded-pill px-3 py-1 d-inline-block">
                                <a href="{{ url()->previous() }}" class="button-33">⬅️ Volver atrás</a>
                            </div>
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
    <div class="row mb-4 justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="row">

                <!-- Nueva visita -->
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
                                    <a href="{{ route('visitasAmbientales.create') }}" class="btn btn-primary btn-circle">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pendientes -->
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

                <!-- Realizadas -->
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

                <!-- Planificadas -->
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
        </div>
    </div>

    <!-- Búsqueda y Filtros -->
    <div class="row mb-4 justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <form method="GET" action="{{ route('visitasAmbientales.index') }}">
                        <div class="input-group">
                            <span class="input-group-text bg-gradient-success text-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="buscar" class="form-control form-control-lg"
                                   placeholder="Buscar por plantación, técnico, estado..."
                                   value="{{ request('buscar') }}">
                            <button class="btn btn-success" type="submit">
                                <i class="fas fa-filter me-2"></i>Filtrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-lg border-0">

                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-list me-2"></i>Lista de Visitas Ambientales
                        </h6>

                        <span class="badge bg-success">
                            {{ $visitas->count() }} de {{ $visitas->total() }} registros
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Plantación</th>
                                    <th>Técnico</th>
                                    <th>Estado</th>
                                    <th>Planificación</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($visitas as $v)
                                <tr class="align-middle">

                                    <td>
                                        <i class="fas fa-calendar-day text-success me-2"></i>
                                        <strong>{{ \Carbon\Carbon::parse($v->fecha_visita)->format('d/m/Y') }}</strong>
                                    </td>

                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-seedling me-1"></i>
                                            {{ $v->plantacion->nombre ?? '-' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $v->tecnico->name ?? '-' }}</strong>
                                                <br>
                                                <small class="text-muted">Técnico</small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        @php
                                            $estado = [
                                                'pendiente' => ['warning', '⏰'],
                                                'realizada' => ['success', '✅'],
                                                'cancelada' => ['danger', '❌'],
                                            ][$v->estado] ?? ['secondary','❓'];
                                        @endphp
                                        <span class="badge bg-{{ $estado[0] }}">
                                            {{ $estado[1] }} {{ ucfirst($v->estado) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-{{ $v->planificacion_id ? 'info' : 'secondary' }}">
                                            {{ $v->planificacion_id ? 'Planificada' : 'Manual' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('visitasAmbientales.show', $v->id) }}"
                                               class="btn btn-sm btn-info btn-circle">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="{{ route('visitasAmbientales.edit', $v->id) }}"
                                               class="btn btn-sm btn-warning btn-circle">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('visitasAmbientales.destroy', $v->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger btn-circle" onclick="return confirm('Seguro?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <h5 class="text-muted">No hay visitas ambientales registradas</h5>
                                        <a href="{{ route('visitasAmbientales.create') }}" class="btn btn-success mt-2">
                                            <i class="fas fa-plus me-2"></i>Nueva Visita
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

                @if($visitas->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <div class="text-muted">
                            Mostrando {{ $visitas->firstItem() }} - {{ $visitas->lastItem() }} de {{ $visitas->total() }} registros
                        </div>
                        {{ $visitas->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

</div>

<style>
    body{
        background-image: url('{{ asset('images/fondo_ambiental.png') }}');
    }

    .btn-circle{
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display:flex;
        justify-content:center;
        align-items:center;
    }

</style>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-4" style="background-color: #fdf4f4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-danger mb-0">
            <i class="fas fa-trash-alt me-2"></i> Visitas Sociales Eliminadas
        </h4>
        <a href="{{ route('visitas_social.indexSocial') }}" class="btn btn-success">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <form id="searchForm" method="GET" action="{{ route('visitas_social.eliminadas') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="buscar" id="buscar" class="form-control"
                placeholder="Buscar por proveedor, técnico, tipo o ubicación..."
                value="{{ $buscar }}">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    @if($visitas->count())
        <table class="table table-bordered table-hover align-middle" id="visitasTable">
            <thead class="table-danger text-center">
                <tr>
                    <th>ID</th>
                    <th>Proveedor</th>
                    <th>Técnico</th>
                    <th>Tipo Visita</th>
                    <th>Ubicación</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visitas as $v)
                    <tr>
                        <td>{{ $v->id }}</td>
                        <td>{{ $v->proveedor->proveedor_nombre ?? '—' }}</td>
                        <td>{{ $v->tecnico->name ?? '—' }}</td>
                        <td>{{ $v->tipo_visita }}</td>
                        <td>{{ $v->ubicacion }}</td>
                        <td><span class="badge bg-danger">{{ ucfirst($v->estado) }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($v->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $visitas->links() }}
        </div>
    @else
        <p class="text-center text-muted mt-4">No hay visitas eliminadas registradas.</p>
    @endif
</div>
@endsection

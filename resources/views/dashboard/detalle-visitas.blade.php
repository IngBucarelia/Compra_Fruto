@extends('layouts.app')

@section('content')
<div class="container mt-4" style="background-color: whitesmoke">
    <h3 class="text-center">🧾 Detalle de Visitas - {{ $plantacion->nombre }}</h3>
    <h5 class="text-center text-muted mb-4">
        Proveedor: {{ $plantacion->proveedor->proveedor_nombre ?? 'N/A' }}
    </h5>

    <!-- Visitas Agronómicas -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            🌿 Visitas Agronómicas ({{ $plantacion->visitas->count() }})
        </div>
        <div class="card-body">
            @if ($plantacion->visitas->isEmpty())
                <p class="text-center text-muted">No hay visitas agronómicas registradas.</p>
            @else
                <table class="table table-sm table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Técnico</th>
                            <th>Tipo</th>
                            <th>Área</th>
                            <th>Sanidad</th>
                            <th>Fertilizaciones</th>
                            <th>Polinizaciones</th>
                            <th>Cierre</th>
                            <th>Ver Detalles </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plantacion->visitas as $v)
                            <tr>
                                <td>{{ $v->fecha }}</td>
                                <td>{{ $v->tecnico->name ?? 'N/A' }}</td>
                                <td>{{ $v->tipo_visita ?? 'N/A' }}</td>
                                <td>{{ $v->areas->count() }}</td>
                                <td>{{ $v->sanidades->count() }}</td>
                                <td>{{ $v->fertilizaciones->count() }}</td>
                                <td>{{ $v->polinizaciones->count() }}</td>
                                <td>{{ $v->cierreVisita ? '✅ Cerrada' : '🕒 Abierta' }}</td>
                                <td> <a href="{{ route('visitas.show', $v->id) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Ver
                                    </a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Visitas Sociales -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            👥 Visitas Sociales ({{ $plantacion->visitasSociales->count() }})
        </div>
        <div class="card-body">
            @if ($plantacion->visitasSociales->isEmpty())
                <p class="text-center text-muted">No hay visitas sociales registradas.</p>
            @else
                <table class="table table-sm table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Técnico</th>
                            <th>Tipo</th>
                            <th>Miembros Hogar</th>
                            <th>Fuerza Laboral</th>
                            <th>Organización</th>
                            <th>Cierre</th>
                            <th>Ver Detalles </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plantacion->visitasSociales as $vs)
                            <tr>
                                <td>{{ $vs->fecha }}</td>
                                <td>{{ $vs->tecnico->name ?? 'N/A' }}</td>
                                <td>{{ $vs->tipo_visita ?? 'N/A' }}</td>
                                <td>{{ $vs->miembros->count() }}</td>
                                <td>{{ $vs->fuerzaLaboral->count() }}</td>
                                <td>{{ $vs->organizacionSocial ? 'Sí' : 'No' }}</td>
                                <td>{{ $vs->cierreVisitaSocial ? '✅ Cerrada' : '🕒 Abierta' }}</td>
                               <td> <a href="{{ route('visitas_social.showSocial', $vs->id) }}" 
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Ver
                                    </a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('dashboard.plantaciones') }}" class="btn btn-secondary">
            ← Volver al Dashboard
        </a>
    </div>
</div>
@endsection

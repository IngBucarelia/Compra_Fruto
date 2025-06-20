@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Planificaciones</h2>

    <a href="{{ route('planificaciones.create') }}" class="btn btn-success mb-3">Nueva planificación</a>
    <a href="{{ route('planificaciones.calendario') }}" class="btn btn-info mb-3">Ver calendario</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Técnico</th>
                <th>Proveedor</th>
                <th>Plantación</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($planificaciones as $p)
                <tr>
                    <td>{{ $p->fecha }}</td>
                    <td>{{ $p->tecnico->name ?? '-' }}</td>
                    <td>{{ $p->proveedor->proveedor_nombre }}</td>
                    <td>{{ $p->plantacion->nombre }}</td>
                    <td>{{ $p->tipo_visita }}</td>
                    <td>{{ ucfirst($p->estado) }}</td>
                    <td>
                        <a href="{{ route('planificaciones.show', $p) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('planificaciones.edit', $p) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('planificaciones.destroy', $p) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $planificaciones->links() }}
</div>
@endsection

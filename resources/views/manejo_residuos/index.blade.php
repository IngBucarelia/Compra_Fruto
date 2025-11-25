@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Manejo de Residuos</h3>

    <a href="{{ route('manejo_residuos.create') }}" class="btn btn-primary mb-3">
        Nuevo Registro
    </a>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Visita</th>
                <th>Capacitación</th>
                <th>Clasificación</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($registros as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->visita_ambiental_id }}</td>
                    <td>{{ $r->capacitacion_personal }}</td>
                    <td>{{ $r->conoce_clasificacion_residuos }}</td>
                    <td>
                        <a href="{{ route('manejo_residuos.show', $r->id) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('manejo_residuos.edit', $r->id) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('manejo_residuos.destroy', $r->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $registros->links() }}
</div>
@endsection

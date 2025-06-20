@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Plantaciones de: {{ $proveedor->proveedor_nombre }}</h2>

    <a href="{{ route('plantaciones.create') }}" class="btn btn-success mb-3">Agregar nueva plantación</a>
    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary mb-3">Volver a Proveedores</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Vereda</th>
                <th>Municipio</th>
                <th>Departamento</th>
                <th>Coordenas (x,Y)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plantaciones as $plantacion)
                <tr>
                    <td>{{ $plantacion->nombre }}</td>
                    <td>{{ $plantacion->vereda }}</td>
                    <td>{{ $plantacion->municipio }}</td>
                    <td>{{ $plantacion->departamento }}</td>
                     <td>{{ $plantacion->geolocalizacion }}</td>
                    <td>
                        <a href="{{ route('plantaciones.show', $plantacion->id) }}" class="btn btn-sm btn-primary"><span style="font-size: 1.5em;">👁️</span>Ver</a>
                        <a href="{{ route('plantaciones.edit', $plantacion->id) }}" class="btn btn-sm btn-warning"><span style="font-size: 1.5em;">✏️</span>Editar</a>
                        <form action="{{ route('plantaciones.destroy', $plantacion->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar esta plantación?')" class="btn btn-sm btn-danger"><span style="font-size: 1.5em;">🗑️</span>Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $plantaciones->links() }}
</div>
@endsection

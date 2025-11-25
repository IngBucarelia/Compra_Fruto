@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Agua - Uso Eficiente</h2>

    <a href="{{ route('agua_uso_eficiente.create') }}" class="btn btn-success mb-3">Nuevo registro</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Visita</th>
                <th>Plan Ahorro</th>
                <th>Mantenimiento</th>
                <th>Uso Información</th>
                <th>Medición</th>
                <th>Opciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->visita_ambiental_id }}</td>
                <td>{{ $item->plan_ahorro ? 'Sí' : 'No' }}</td>
                <td>{{ $item->mantenimiento_sistemas ? 'Sí' : 'No' }}</td>
                <td>{{ $item->uso_informacion_balance ? 'Sí' : 'No' }}</td>
                <td>{{ $item->mecanismo_medicion ? 'Sí' : 'No' }}</td>

                <td>
                    <a href="{{ route('agua_uso_eficiente.edit', $item->id) }}" class="btn btn-sm btn-primary">Editar</a>

                    <form action="{{ route('agua_uso_eficiente.destroy', $item->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection

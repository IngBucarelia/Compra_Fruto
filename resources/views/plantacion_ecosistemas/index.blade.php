@extends('layouts.app')

@section('content')
<div class="container form-box">

    <h3 class="title">Protección de Ecosistemas Estratégicos y Sensibles</h3>

    <a href="{{ route('plantacion_ecosistemas.create', $visita->id) }}" class="btn btn-success mb-3">Nuevo Registro</a>
    <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary mb-3">Volver</a>

    <table class="table table-bordered custom-table">

        <thead>
            <tr>
                <th>Planes diferenciados</th>
                <th>Acciones conservación</th>
                <th>Manejo diferenciado</th>
                <th>Ronda hídrica</th>
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($registros as $r)
            <tr>
                <td>{{ $r->planes_manejo_diferenciados }}</td>
                <td>{{ $r->acciones_conservacion_fragmentos }}</td>
                <td>{{ $r->implementa_planes_manejo_diferenciado }}</td>
                <td>{{ $r->respeta_distancias_ronda_hidrica }}</td>
                <td>{{ $r->observaciones }}</td>

                <td>
                    <a href="{{ route('plantacion_ecosistemas.edit', $r->id) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('plantacion_ecosistemas.destroy', $r->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>

<style>
.form-box {
    background: whitesmoke;
    padding: 20px;
    border-radius: 18px;
}

.title {
    text-align: center;
    font-size: 26px;
    font-weight: bold;
    color: #2f6e3e;
    margin-bottom: 25px;
}

.custom-table th {
    background: #e5efe5;
}

.custom-table td {
    background: white;
}
</style>

@endsection

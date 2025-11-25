@extends('layouts.app')

@section('content')
<div class="container form-box">

    <h3 class="title">Registros de Protección AVC</h3>

    <a href="{{ route('plantacion_avc.create', $visita->id) }}" class="btn btn-success mb-3">Nuevo Registro</a>
    <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary mb-3">Volver</a>

    <table class="table table-bordered custom-table">
        <thead>
            <tr>
                <th>Avistamientos</th>
                <th>Identifica AVC/ARC</th>
                <th>Medidas de manejo</th>
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($registros as $r)
            <tr>
                <td>{{ $r->registros_avistamientos }}</td>
                <td>{{ $r->identifica_avc_arc }}</td>
                <td>{{ $r->implementa_medidas_manejo }}</td>
                <td>{{ $r->observaciones }}</td>

                <td>
                    <a href="{{ route('plantacion_avc.edit', $r->id) }}" class="btn btn-warning btn-sm">Editar</a>

                    <form action="{{ route('plantacion_avc.destroy', $r->id) }}" method="POST" style="display:inline;">
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
@endsection

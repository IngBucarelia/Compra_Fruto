@extends('layouts.app')

@section('content')
<div class="container form-box">

    <h3 class="title">Editar Protección de Ecosistemas Estratégicos</h3>

    <form action="{{ route('plantacion_ecosistemas.update', $registro->id) }}" method="POST">
        @csrf
        @method('PUT')

        <table class="table table-bordered custom-table">

            <tr>
                <th>¿Cuenta con planes de manejo diferenciados?</th>
                <td>
                    <select name="planes_manejo_diferenciados" class="form-control">
                        <option value="si" {{ $registro->planes_manejo_diferenciados=='si'?'selected':'' }}>Sí</option>
                        <option value="no" {{ $registro->planes_manejo_diferenciados=='no'?'selected':'' }}>No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>¿Implementa acciones de conservación?</th>
                <td>
                    <select name="acciones_conservacion_fragmentos" class="form-control">
                        <option value="si" {{ $registro->acciones_conservacion_fragmentos=='si'?'selected':'' }}>Sí</option>
                        <option value="no" {{ $registro->acciones_conservacion_fragmentos=='no'?'selected':'' }}>No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>¿Implementa manejo diferenciado?</th>
                <td>
                    <select name="implementa_planes_manejo_diferenciado" class="form-control">
                        <option value="si" {{ $registro->implementa_planes_manejo_diferenciado=='si'?'selected':'' }}>Sí</option>
                        <option value="no" {{ $registro->implementa_planes_manejo_diferenciado=='no'?'selected':'' }}>No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>¿Respeta la ronda hídrica?</th>
                <td>
                    <select name="respeta_distancias_ronda_hidrica" class="form-control">
                        <option value="si" {{ $registro->respeta_distancias_ronda_hidrica=='si'?'selected':'' }}>Sí</option>
                        <option value="no" {{ $registro->respeta_distancias_ronda_hidrica=='no'?'selected':'' }}>No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Observaciones</th>
                <td>
                    <textarea name="observaciones" class="form-control">{{ $registro->observaciones }}</textarea>
                </td>
            </tr>

        </table>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('visitasAmbientales.show', $registro->visita_ambiental_id) }}" class="btn btn-secondary">Volver</a>

    </form>
</div>
@endsection

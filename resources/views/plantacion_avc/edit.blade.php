@extends('layouts.app')

@section('content')
<div class="container form-box">

    <h3 class="title">Editar Protección AVC</h3>

    <form action="{{ route('plantacion_avc.update', $registro->id) }}" method="POST">
        @csrf
        @method('PUT')

        <table class="table table-bordered custom-table">

            <tr>
                <th>¿Cuentan con registros de avistamientos?</th>
                <td>
                    <select name="registros_avistamientos" class="form-control">
                        <option value="si" {{ $registro->registros_avistamientos=='si'?'selected':'' }}>Sí</option>
                        <option value="no" {{ $registro->registros_avistamientos=='no'?'selected':'' }}>No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>¿Identifica AVC y ARC?</th>
                <td>
                    <select name="identifica_avc_arc" class="form-control">
                        <option value="si" {{ $registro->identifica_avc_arc=='si'?'selected':'' }}>Sí</option>
                        <option value="no" {{ $registro->identifica_avc_arc=='no'?'selected':'' }}>No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>¿Implementa medidas de manejo?</th>
                <td>
                    <select name="implementa_medidas_manejo" class="form-control">
                        <option value="si" {{ $registro->implementa_medidas_manejo=='si'?'selected':'' }}>Sí</option>
                        <option value="no" {{ $registro->implementa_medidas_manejo=='no'?'selected':'' }}>No</option>
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

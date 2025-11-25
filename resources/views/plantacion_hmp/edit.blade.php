@extends('layouts.app')

@section('content')
<div class="container" style="background-color: whitesmoke; border-radius:30px; padding:20px;">

<h2 class="title">Editar – Diseño y Manejo de la Plantación (HMP)</h2>

<div class="card-component">
    <div class="card-header-green">Editar Registro</div>

    <form action="{{ route('plantacion_hmp.update', $registro->id) }}" method="POST">
        @csrf
        @method('PUT')

        <table class="table table-bordered table-custom">
            <tr>
                <th>Implementa HMP</th>
                <td>
                    <select name="implementa_hmp" class="form-control">
                        <option value="si" {{ $registro->implementa_hmp == 'si' ? 'selected' : '' }}>Sí</option>
                        <option value="no" {{ $registro->implementa_hmp == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Incluye HMP en el diseño</th>
                <td>
                    <select name="incluye_hmp_disenio" class="form-control">
                        <option value="si" {{ $registro->incluye_hmp_disenio == 'si' ? 'selected' : '' }}>Sí</option>
                        <option value="no" {{ $registro->incluye_hmp_disenio == 'no' ? 'selected' : '' }}>No</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Observaciones</th>
                <td><textarea name="observaciones" class="form-control">{{ $registro->observaciones }}</textarea></td>
            </tr>

        </table>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('visitasAmbientales.show', $registro->visita_ambiental_id) }}" class="btn btn-secondary">Volver</a>
    </form>

</div>

</div>
@endsection

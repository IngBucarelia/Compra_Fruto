@extends('layouts.app')

@section('content')
<div class="container" style="background-color: whitesmoke; border-radius:20px; padding:20px;">

<h3>Detalle – HMP Plantación</h3>

<table class="table table-bordered">
    <tr>
        <th>Implementa HMP</th>
        <td>{{ $registro->implementa_hmp }}</td>
    </tr>

    <tr>
        <th>Incluye HMP en diseño</th>
        <td>{{ $registro->incluye_hmp_disenio }}</td>
    </tr>

    <tr>
        <th>Observaciones</th>
        <td>{{ $registro->observaciones }}</td>
    </tr>
</table>

<a href="{{ route('visitasAmbientales.show', $registro->visita_ambiental_id) }}" class="btn btn-secondary">Volver</a>

</div>
@endsection

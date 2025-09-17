@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg border-0 rounded-4 mx-auto p-4" style="max-width: 900px; background-color: #e8d5dce0;">
        <form action="{{ route('redireccion_seccion_social', $visita->id) }}" method="GET" class="mt-4">
            <label for="seccion" class="form-label fw-bold text-success">📋 Ir a sección:</label>
            <div class="input-group">
                <select id="seccion" name="seccion" class="form-select" required>
                    <option value="">Seleccione una sección</option>
                    @if ($visita->estado === 'pendiente' || $visita->estado === 'en_ejecucion')
                        <option value="inicio"> Pagina de Inicio de Visita</option>
                        <option value="datos_personales">👤 Datos Personales</option>
                        <option value="miembros">👨‍👩‍👧‍👦 Miembros del Hogar</option>
                        <option value="predio">🏡 Datos del Predio</option>
                        <option value="fuerza_laboral">🧑‍🌾 Fuerza Laboral</option>
                        <option value="organizacion_social">👥 Organización Social</option>
                    @endif
                </select>
                <button type="submit" class="btn btn-success">Ir</button>
            </div>
        </form>
        <h3 class="text-success fw-bold mb-4">👁 Detalle Organización Social</h3>

        <table class="table table-bordered">
            <tr>
                <th>Pertenece JAC</th>
                <td>{{ $organizacion->pertenece_jac }}</td>
            </tr>
            <tr>
                <th>Pertenece a asociación</th>
                <td>{{ $organizacion->pertenece_asociacion }}</td>
            </tr>
            <tr>
                <th>Nombre asociación</th>
                <td>{{ $organizacion->nombre_asociacion ?? 'N/A' }}</td>
            </tr>
        </table>

        <a href="{{ route('organizacion_social.index', $visita->id) }}" class="btn btn-secondary mt-3">⬅ Volver</a>
    </div>
</div>
@endsection

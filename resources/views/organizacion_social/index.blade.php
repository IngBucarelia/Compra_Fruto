@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 900px; background-color: #e8d5dce0; padding:10px;">
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
        <h3 class="text-success fw-bold mb-4">👥 Organización Social - Visita #{{ $visita->id }}</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('organizacion_social.create', $visita->id) }}" class="btn btn-success mb-3">➕ Registrar organización</a>

        <table class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Pertenece JAC</th>
                    <th>Pertenece a asociación</th>
                    <th>Nombre asociación</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($organizaciones as $org)
                    <tr>
                        <td>{{ $org->id }}</td>
                        <td>{{ $org->pertenece_jac }}</td>
                        <td>{{ $org->pertenece_asociacion }}</td>
                        <td>{{ $org->nombre_asociacion }}</td>
                        <td>
                            <a href="{{ route('organizacion_social.show', [$visita->id, $org->id]) }}" class="btn btn-info btn-sm">👁 Ver</a>
                            <a href="{{ route('organizacion_social.edit', [$visita->id, $org->id]) }}" class="btn btn-warning btn-sm">✏ Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">❌ No hay registros de organización social</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

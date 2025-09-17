@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg border-0 rounded-4 mx-auto p-4" style="max-width: 900px; background-color: #e8d5dce0;">
        <h3 class="text-success fw-bold mb-4">✏ Editar Organización Social</h3>

        <form action="{{ route('organizacion_social.update', [$visita->id, $organizacion->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Pertenece a la JAC?</label>
                <select name="pertenece_jac" class="form-select" required>
                    <option value="SI" {{ $organizacion->pertenece_jac == 'SI' ? 'selected' : '' }}>SI</option>
                    <option value="NO" {{ $organizacion->pertenece_jac == 'NO' ? 'selected' : '' }}>NO</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Pertenece a alguna asociación de palmicultores?</label>
                <select name="pertenece_asociacion" class="form-select" required>
                    <option value="SI" {{ $organizacion->pertenece_asociacion == 'SI' ? 'selected' : '' }}>SI</option>
                    <option value="NO" {{ $organizacion->pertenece_asociacion == 'NO' ? 'selected' : '' }}>NO</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Si pertenece, digite el nombre</label>
                <input type="text" name="nombre_asociacion" class="form-control" value="{{ $organizacion->nombre_asociacion }}">
            </div>

            <button type="submit" class="btn btn-warning">Actualizar</button>
            <a href="{{ route('organizacion_social.index', $visita->id) }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection

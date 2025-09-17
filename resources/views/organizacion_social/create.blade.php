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
        <h3 class="text-success fw-bold mb-4">➕ Registrar Organización Social</h3>

        <form action="{{ route('organizacion_social.store', $visita->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Pertenece a la JAC?</label>
                <select name="pertenece_jac" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Pertenece a alguna asociación de palmicultores?</label>
                <select name="pertenece_asociacion" class="form-select" required>
                    <option value="">Seleccione...</option>
                    <option value="SI">SI</option>
                    <option value="NO">NO</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Si pertenece, digite el nombre</label>
                <input type="text" name="nombre_asociacion" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('organizacion_social.index', $visita->id) }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection

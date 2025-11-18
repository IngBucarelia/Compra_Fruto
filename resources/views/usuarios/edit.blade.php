@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Editar Usuario</h3>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">← Volver a la lista</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Errores:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $usuario->email) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Número de documento <span class="text-danger">*</span></label>
                        <input type="text" name="num_documento" class="form-control" value="{{ old('num_documento', $usuario->num_documento) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Área pertenece</label>
                        <input type="text" name="area_pertenece" class="form-control" value="{{ old('area_pertenece', $usuario->area_pertenece) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ocupación</label>
                        <input type="text" name="ocupacion" class="form-control" value="{{ old('ocupacion', $usuario->ocupacion) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Rol</label>
                        <select name="rol" class="form-select">
                            <option value="">Seleccione un rol</option>
                            <option value="1" {{ old('rol', $usuario->rol) == 1 ? 'selected' : '' }}>1 - Administrador</option>
                            <option value="2" {{ old('rol', $usuario->rol) == 2 ? 'selected' : '' }}>2 - Técnico Campo Agronómico</option>
                            <option value="3" {{ old('rol', $usuario->rol) == 3 ? 'selected' : '' }}>3 - Técnico Campo Social</option>
                            <option value="4" {{ old('rol', $usuario->rol) == 4 ? 'selected' : '' }}>4 - Auxiliar Administrativo</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nueva contraseña (opcional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Dejar en blanco si no se cambia">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repetir contraseña">
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        💾 Guardar cambios
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

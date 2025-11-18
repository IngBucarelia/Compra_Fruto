@extends('layouts.app')

@section('content')
<div class="container mt-4" style="background-color: whitesmoke">
    <h3 class="mb-3 text-center">Lista de Usuarios</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="text-end mb-3">
        <a href="{{ route('register') }}" class="btn btn-primary">➕ Nuevo Usuario</a>
    </div>

    <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Documento</th>
                <th>Área</th>
                <th>Ocupación</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->id }}</td>
                <td>{{ $usuario->name }}</td>
                <td>{{ $usuario->email }}</td>
                <td>{{ $usuario->num_documento }}</td>
                <td>{{ $usuario->area_pertenece }}</td>
                <td>{{ $usuario->ocupacion }}</td>
                <td>{{ ucfirst($usuario->rol) }}</td>
                <td>
                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-warning">✏️</a>
                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar usuario?')">🗑️</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $usuarios->links() }}
</div>
@endsection

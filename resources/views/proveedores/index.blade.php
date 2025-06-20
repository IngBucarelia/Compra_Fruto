@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('proveedores.create') }}" class="btn btn-success mb-3">Crear nuevo proveedor</a>

    <h2 class="mb-4">Listado de Proveedores</h2>

    {{-- Formulario con envío automático --}}
    <form method="GET" action="{{ route('proveedores.index') }}" class="mb-3" id="form-busqueda">
        <div class="input-group">
            <input type="text" name="buscar" id="buscar" class="form-control" placeholder="Buscar por nombre o NIT..." value="{{ request('buscar') }}">
        </div>
    </form>

    {{-- Tabla de resultados --}}
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>NIT</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($proveedores as $proveedor)
            <tr>
                <td>{{ $proveedor->id }}</td>
                <td>{{ $proveedor->proveedor_nombre }}</td>
                <td>{{ $proveedor->nit }}</td>
                <td>
                    <a href="{{ route('proveedores.plantaciones.index', $proveedor->id) }}" class="btn btn-info btn-sm">  <span style="font-size: 1.5em;">🌴</span>Ver Plantaciones</a>
                    <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-warning btn-sm"><span style="font-size: 1.5em;">✏️</span>Editar</a>

                    <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"><span style="font-size: 1.5em;">🗑️</span>Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">No se encontraron proveedores.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Paginación --}}
    <div class="d-flex justify-content-center">
        {{ $proveedores->links() }}
    </div>
</div>

{{-- Script para enviar el formulario automáticamente al escribir --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('buscar');
        const form = document.getElementById('form-busqueda');
        let timer;

        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(() => {
                form.submit();
            }, 500); // espera 500ms tras dejar de escribir
        });
    });
</script>
@endsection

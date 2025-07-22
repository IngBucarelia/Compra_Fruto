@extends('layouts.app')

@section('content')  

<style>
    .container{
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
        margin-left: 0; /* Asegura que no hay margen izquierdo */
        width: 100%; /* Ocupa todo el ancho disponible */
    }

    .title{
        text-align: center; 
        font-family: Arial Black; 
        font-weight: bold; 
        font-size: 30px; 
        color: #fdffe5; 
        text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    }

    @media (max-width: 768px) {
        .container {
            margin-left: 0; /* Elimina el margen negativo */
            width: 100%; /* Usa el 100% del ancho */
            padding: 15px; /* Ajusta el padding para móviles */
        }
        
        /* Oculta el sidebar en móviles */
        .sidebar {
            display: none !important;
        }
        
        /* Asegura que el contenido principal ocupe todo el ancho */
        .main-content {
            margin-left: 0 !important;
            width: 100% !important;
        }
        
        /* Ajustes para la tabla en móviles */
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            font-size: 0.8rem;
        }
    }
</style>
<div class="container" >
    <h2 class="mb-4">Listado de Plantaciones</h2>

    {{-- Formulario con búsqueda automática al escribir --}}
    <form method="GET" action="{{ route('plantaciones.index') }}" id="form-busqueda" class="mb-4">
        <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" class="form-control" placeholder="Buscar por nombre o vereda...">
    </form>

    <a href="{{ route('plantaciones.create') }}" class="btn btn-success mb-3">Crear nueva plantación</a>
    <div class="table-responsive">
    <table class="table table-bordered" style="background-color: #fdffe5; border-color:#000">
        <thead>
            <tr>
                <th>Proveedor</th>
                <th>Nombre</th>
                <th>Vereda</th>
                <th>Municipio</th>
                <th>Departamento</th>
                <th>Geo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($plantaciones as $plantacion)
                <tr>
                    <td>{{ $plantacion->proveedor->proveedor_nombre }}</td>
                    <td>{{ $plantacion->nombre }}</td>
                    <td>{{ $plantacion->vereda }}</td>
                    <td>{{ $plantacion->municipio }}</td>
                    <td>{{ $plantacion->departamento }}</td>
                    <td>{{ $plantacion->geolocalizacion }}</td>
                    <td>
                        <a href="{{ route('plantaciones.show', $plantacion->id) }}" class="btn btn-info btn-sm" style="margin-bottom: 5px"><span style="font-size: 1.5em;">👁️</span>Ver</a><br>
                        <a href="{{ route('plantaciones.edit', $plantacion->id) }}" class="btn btn-warning btn-sm"  style="margin-bottom: 5px"><span style="font-size: 1.5em;">✏️</span>Editar</a>
                        <form action="{{ route('plantaciones.destroy', $plantacion->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta plantación?')"><span style="font-size: 1.5em;">🗑️</span>Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No se encontraron resultados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
     <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
    </div>

    <div class="d-flex justify-content-center">
        {{ $plantaciones->links() }}
    </div>
</div>

{{-- Script: búsqueda letra a letra sin presionar botón --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('buscar');
        const form = document.getElementById('form-busqueda');
        let timer;

        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(() => {
                form.submit();
            }, 500); // espera medio segundo después de dejar de escribir
        });
    });
</script>
@endsection

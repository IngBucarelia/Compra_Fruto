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

<!-- Contenedor principal que fuerza el ancho completo -->
<div class="container-fluid p-0">
    <div class="container">
        <h2 class="title">Planificaciones</h2>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('planificaciones.create') }}" class="btn btn-success">Nueva planificación</a>
            <a href="{{ route('planificaciones.calendario') }}" class="btn btn-info">Ver calendario</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" style="background-color: #fdffe5; border-color:#000">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Técnico</th>
                        <th>Proveedor</th>
                        <th>Plantación</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($planificaciones as $p)
                        <tr>
                            <td>{{ $p->fecha }}</td>
                            <td>{{ $p->tecnico->name ?? '-' }}</td>
                            <td>{{ $p->proveedor->proveedor_nombre }}</td>
                            <td>{{ $p->plantacion->nombre }}</td>
                            <td>{{ $p->tipo_visita }}</td>
                            <td>{{ ucfirst($p->estado) }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('planificaciones.show', $p) }}" class="btn btn-info btn-sm">Ver</a>
                                    <a href="{{ route('planificaciones.edit', $p) }}" class="btn btn-warning btn-sm">Editar</a>
                                    <form action="{{ route('planificaciones.destroy', $p) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>

        </div>

        {{ $planificaciones->links() }}
    </div>
</div>

<!-- Script para ocultar sidebar en móviles -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    function handleResponsive() {
        if (window.innerWidth <= 768) {
            document.querySelector('.sidebar').style.display = 'none';
            document.querySelector('.main-content').style.marginLeft = '0';
            document.querySelector('.main-content').style.width = '100%';
        } else {
            document.querySelector('.sidebar').style.display = 'block';
            // Restaurar valores originales para desktop si es necesario
        }
    }

    // Ejecutar al cargar y al cambiar tamaño
    handleResponsive();
    window.addEventListener('resize', handleResponsive);
});
</script>
@endsection
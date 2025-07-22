@extends('layouts.app')

@section('content')
<style>
    .visitas-container {
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
        overflow-x: auto; /* Para tablas en móviles */
        border-radius: 10px;

    }
    
    .search-box {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }
    
    .search-box .btn {
        flex: 1 1 auto;
        min-width: 200px;
    }
    
    .search-box input {
        flex: 3 1 300px;
    }
    
    .table-responsive-custom {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table {
        min-width: 600px; /* Ancho mínimo para la tabla */
        background-color: rgb(248, 234, 209);
        border-color: black
    }
    
    .badge {
        font-size: 0.85em;
        padding: 0.5em 0.75em;
        white-space: nowrap;
    }
    
    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    
    .action-buttons .btn {
        flex: 1 1 auto;
        min-width: 100px;
        padding: 0.25rem 0.5rem;
        font-size: 0.85rem;
    }
    
    @media (max-width: 768px) {
        .visitas-container {
            padding: 15px 10px;
            margin-left: -40px;
            width:110%
        }
        
        h2 {
            font-size: 1.5rem;
        }
        
        .search-box input {
            min-width: 100%;
        }
        
        .action-buttons .btn {
            min-width: 80px;
            font-size: 0.75rem;
        }
    }
</style>

<div class="container visitas-container">
    <h2 class="mb-4">Listado de Visitas</h2>

    <div class="search-box">
        <a href="{{ route('visitas.create') }}"  class="btn btn-success">Registrar nueva visita</a>
        <form method="GET" action="{{ route('visitas.index') }}" id="form-busqueda">
            <input type="text" name="buscar" id="buscar" class="form-control"
                placeholder="Buscar por proveedor, técnico o tipo de visita..."
                value="{{ request('buscar') }}">
        </form>
    </div>

    <script>
        const input = document.getElementById('buscar');
        let timer;
        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(() => document.getElementById('form-busqueda').submit(), 400);
        });
    </script>

    <div class="table-responsive-custom">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Origen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($visitas as $visita)
                    <tr>
                        <td data-label="Fecha">{{ $visita->fecha }}</td>
                        <td data-label="Proveedor">{{ $visita->proveedor->proveedor_nombre ?? '-' }}</td>
                        <td data-label="Tipo">{{ $visita->tipo_visita }}</td>
                        <td data-label="Estado">
                            <span class="badge bg-{{ 
                                $visita->estado === 'realizada' ? 'success' : 
                                ($visita->estado === 'pendiente' ? 'warning' : 'danger') 
                            }}">
                                {{ ucfirst($visita->estado) }}
                            </span>
                        </td>
                        <td data-label="Origen">
                            @if ($visita->planificacion)
                                <span class="badge bg-info">Planificada</span>
                            @else
                                <span class="badge bg-secondary">Manual</span>
                            @endif
                        </td>
                        <td data-label="Acciones">
                            <div class="action-buttons">
                                <a href="{{ route('visitas.show', $visita->id) }}" class="btn btn-sm btn-primary">Continuar Visita</a>
                                <a href="{{ route('visitas.detalle', $visita->id) }}" class="btn btn-sm btn-success">Ver Detalle</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
     <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
    </div>

    {{ $visitas->links() }}
</div>

<!-- Opcional: Para mejor visualización en móviles muy pequeños -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function adaptTableForMobile() {
            if (window.innerWidth < 576) {
                const actionButtons = document.querySelectorAll('.action-buttons .btn');
                actionButtons.forEach(btn => {
                    btn.innerHTML = btn.innerHTML.replace('Continuar Visita', 'Continuar')
                                                .replace('Ver Detalle', 'Detalle');
                });
            }
        }
        
        adaptTableForMobile();
        window.addEventListener('resize', adaptTableForMobile);
    });
</script>
@endsection
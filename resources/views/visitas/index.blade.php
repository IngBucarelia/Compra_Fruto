@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Listado de Visitas</h2>

    <a href="{{ route('visitas.create') }}" class="btn btn-success mb-3">Registrar nueva visita</a>
    <form method="GET" action="{{ route('visitas.index') }}" class="mb-3" id="form-busqueda">
        <input type="text" name="buscar" id="buscar" class="form-control"
            placeholder="Buscar por proveedor, técnico o tipo de visita..."
            value="{{ request('buscar') }}">
    </form>

    <script>
        const input = document.getElementById('buscar');
        let timer;
        input.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(() => document.getElementById('form-busqueda').submit(), 400);
        });
    </script>

    <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Origen</th> <!-- 👈 nueva columna -->
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($visitas as $visita)
                    <tr>
                        <td>{{ $visita->fecha }}</td>
                        <td>{{ $visita->proveedor->proveedor_nombre ?? '-' }}</td>
                        <td>{{ $visita->tipo_visita }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $visita->estado === 'realizada' ? 'success' : 
                                ($visita->estado === 'pendiente' ? 'warning' : 'danger') 
                            }}">
                                {{ ucfirst($visita->estado) }}
                            </span>
                        </td>
                        <td>
                            @if ($visita->planificacion)
                                <span class="badge bg-info">Planificada</span>
                            @else
                                <span class="badge bg-secondary">Manual</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('visitas.show', $visita->id) }}" class="btn btn-sm btn-primary">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    {{ $visitas->links() }}
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container py-4" style="background-color: azure;">
    <h4 class="text-success mb-4 d-flex justify-content-between align-items-center">
        <span><i class="fas fa-history me-2"></i> Auditoría del Sistema</span>
        <input type="text" id="search" class="form-control w-25" placeholder="🔍 Buscar...">
    </h4>

    <div class="table-responsive" id="tablaAuditoria">
        <table class="table table-bordered table-hover align-middle shadow-sm">
            <thead class="table-success text-center">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Módulo</th>
                    <th>ID Registro</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($auditorias as $a)
                    <tr>
                        <td>{{ $a->id }}</td>
                        <td>{{ $a->usuario->name ?? '—' }}</td>
                        <td>
                            @if($a->tipo_accion == 'create')
                                <span class="badge bg-success">Creación</span>
                            @elseif($a->tipo_accion == 'edit')
                                <span class="badge bg-warning text-dark">Edición</span>
                            @elseif($a->tipo_accion == 'delete')
                                <span class="badge bg-danger">Eliminación</span>
                            @else
                                {{ ucfirst($a->tipo_accion) }}
                            @endif
                        </td>
                        <td>{{ $a->tipo_modulo }}</td>
                        <td>{{ $a->registro_id }}</td>
                        <td class="text-start">{{ $a->descripcion }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-muted py-3">No se encontraron resultados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $auditorias->links() }}
        </div>
    </div>
</div>

{{-- Script búsqueda predictiva y paginación AJAX --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function cargarDatos(url = "{{ route('auditorias.index') }}", search = '') {
        $.ajax({
            url: url,
            method: 'GET',
            data: { search: search },
            success: function(data) {
                $('#tablaAuditoria').html($(data).find('#tablaAuditoria').html());
            }
        });
    }

    // Buscador en tiempo real
    $('#search').on('keyup', function() {
        let search = $(this).val();
        cargarDatos("{{ route('auditorias.index') }}", search);
    });

    // Paginación dinámica
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let search = $('#search').val();
        cargarDatos(url, search);
    });
});
</script>
@endsection

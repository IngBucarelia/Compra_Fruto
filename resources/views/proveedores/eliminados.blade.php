@extends('layouts.app')

@section('content')
<div class="container" style="background-color: azure;border-radius:25px;">
    <h3 class="mb-3 text-danger">Proveedores Eliminados</h3>

    <a href="{{ route('proveedores.index') }}" class="btn btn-success mb-3">
        ← Volver a proveedores activos
    </a>

    <form id="searchForm" method="GET" action="{{ route('proveedores.eliminados') }}" class="d-flex mb-3" style="width: 60%;">
        <input type="text" name="buscar" id="buscar" value="{{ $buscar }}" class="form-control"
               placeholder="Buscar proveedor eliminado" onkeyup="debounceSearch()">
    </form>

    <table class="table table-bordered table-striped align-middle" >
        <thead class="table-secondary">
            <tr>
                <th>Nombre del Proveedor</th>
                <th>NIT</th>
                <th>Descripción Auditoría</th>
                <th>Fecha Eliminación</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor->proveedor_nombre }}</td>
                    <td>{{ $proveedor->nit }}</td>
                    @php
                        $auditoria = $proveedor->auditorias->first();
                    @endphp
                    <td>{{ $auditoria->descripcion ?? 'Sin descripción' }}</td>
                    <td>{{ optional($auditoria)->created_at ? $auditoria->created_at->format('Y-m-d H:i') : '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No hay proveedores eliminados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $proveedores->links() }}
</div>

<script>
let searchTimer;
window.debounceSearch = function() {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        document.getElementById('searchForm').submit();
    }, 500);
}
</script>
@endsection

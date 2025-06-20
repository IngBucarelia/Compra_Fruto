@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar proveedor</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('proveedores.update', $proveedor) }}" method="POST">
        @csrf 
        @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="proveedor_nombre" class="form-control" value="{{ $proveedor->proveedor_nombre }}" required>
        </div>
        <div class="mb-3">
            <label>NIT</label>
            <input type="number" name="nit" class="form-control" value="{{ $proveedor->nit }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

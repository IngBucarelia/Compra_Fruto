@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Crear Nuevo Proveedor</h1>
    
    <form action="{{ route('proveedores.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="proveedor_nombre" class="form-label">Nombre del Proveedor*</label>
            <input type="text" class="form-control" id="proveedor_nombre" name="proveedor_nombre" required>
        </div>
        
        <div class="mb-3">
            <label for="nit" class="form-label">NIT*</label>
            <input type="number" class="form-control" id="nit" name="nit" required>
        </div>
        
        <div class="mb-3">
            <label for="dia_creado" class="form-label">Fecha de Creación</label>
            <input type="date" class="form-control" id="dia_creado" name="dia_creado" value="{{ now()->format('Y-m-d') }}">
        </div>
        
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Guardar Proveedor
        </button>
        
        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Cancelar
        </a>
    </form>
</div>
@endsection
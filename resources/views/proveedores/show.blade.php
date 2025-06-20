@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Detalle del Proveedor</h1>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $proveedor->proveedor_nombre }}</h5>
            <p class="card-text"><strong>NIT:</strong> {{ $proveedor->nit }}</p>
            <p class="card-text"><strong>Fecha Creación:</strong> {{ $proveedor->dia_creado->format('d/m/Y') }}</p>
            
            <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>
@endsection
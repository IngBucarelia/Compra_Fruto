@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        background: #f7f9fc;
        padding: 40px 20px;
    }

    .dashboard-card {
        background: white;
        border: none;
        border-radius: 10px;
        transition: 0.3s;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    }

    .dashboard-icon {
        font-size: 3em;
        margin-bottom: 10px;
    }

    .dashboard-title {
        font-size: 1.2em;
        font-weight: bold;
        color: #333;
    }

    .dashboard-link {
        text-decoration: none;
        color: inherit;
    }

    .dashboard-header {
        font-size: 1.8em;
        font-weight: bold;
        margin-bottom: 30px;
        text-align: center;
        color: #2c3e50;
    }
</style>

<div class="container dashboard-container">
    <div class="dashboard-header">
        👋 Bienvenido, {{ auth()->user()->name }}
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <a href="{{ route('proveedores.index') }}" class="dashboard-link">
                <div class="dashboard-card p-4">
                    <div class="dashboard-icon text-success">🚚</div>
                    <div class="dashboard-title">Gestión de Proveedores</div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('visitas.index') }}" class="dashboard-link">
                <div class="dashboard-card p-4">
                    <div class="dashboard-icon text-primary">👔📋</div>
                    <div class="dashboard-title">Listado de Visitas</div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('planificaciones.calendario') }}" class="dashboard-link">
                <div class="dashboard-card p-4">
                    <div class="dashboard-icon text-warning">📅</div>
                    <div class="dashboard-title">Calendario de Visitas</div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

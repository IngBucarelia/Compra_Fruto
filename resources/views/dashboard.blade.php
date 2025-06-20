@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h4 class="mb-0">Bienvenido, {{ auth()->user()->name }}</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('proveedores.index') }}" class="btn btn-lg btn-success w-100 py-3">
                            <span style="font-size: 2em;">🚚</span> Proveedores
                        </a>
                    </div>
                    <div class="col-md-6">
                       <a href="{{ route('visitas.index') }}" class="btn btn-lg btn-primary w-100 py-3">
                            <span style="font-size: 2em;">👔📋</span> Visitas
                        </a>

                    </div><br><br>
                    <div class="col-md-6">
                        <a href="{{ route('planificaciones.calendario') }}" class="btn btn-lg btn-primary w-100 py-3">
                            <span style="font-size: 2em;">📅</span> Calendario de Visitas
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
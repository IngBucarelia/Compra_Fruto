@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h2>Cargar Todos los Componentes de Visitas desde Excel</h2>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-header">Instrucciones</div>
                        <div class="card-body">
                            <p>El archivo Excel debe contener las siguientes hojas:</p>
                            <ul>
                                <li><strong>visitas</strong> - Información básica de las visitas</li>
                                <li><strong>areas</strong> - Datos de áreas</li>
                                <li><strong>fertilizaciones</strong> - Información de fertilizaciones</li>
                                <li><strong>fertilizantes_fertilizacion</strong> - Detalles de fertilizantes</li>
                                <li><strong>polinizaciones</strong> - Datos de polinización</li>
                                <li><strong>sanidades</strong> - Información de sanidad</li>
                                <li><strong>suelos</strong> - Análisis de suelos</li>
                                <li><strong>labores_cultivo</strong> - Labores de cultivo</li>
                                <li><strong>evaluacion_cosecha_campo</strong> - Evaluación de cosecha</li>
                                <li><strong>cierre_visita</strong> - Cierre de visita</li>
                            </ul>
                        </div>
                    </div>

                    <br>

                    <form action="{{ route('visitas.full-import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Seleccionar archivo Excel:</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-success">Importar Todos los Componentes</button>
                    </form>
                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endsection

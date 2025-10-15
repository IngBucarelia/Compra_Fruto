@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h2 class="text-center mt-3">Cargar Componentes de Visitas Sociales desde Excel</h2>

                @if(session('success'))
                    <div class="alert alert-success mt-3">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                @endif

                <div class="card mt-3">
                    <div class="card-header">📘 Instrucciones</div>
                    <div class="card-body">
                        <p>El archivo Excel debe contener las siguientes hojas:</p>
                        <ul>
                            <li><strong>datos_personales_socials</strong> - Información personal del hogar</li>
                            <li><strong>miembros_hogar</strong> - Datos de los miembros del hogar</li>
                            <li><strong>datos_predio_sociales</strong> - Información del predio</li>
                            <li><strong>fuerza_laborals</strong> - Datos sobre la fuerza laboral</li>
                            <li><strong>organizacion_social</strong> - Información organizacional</li>
                            <li><strong>cierre_visita_socials</strong> - Información de cierre de visita</li>
                        </ul>
                    </div>
                </div>

                <form action="{{ route('visitas_social.full-import') }}" method="POST" enctype="multipart/form-data" class="p-3">
                    @csrf
                    <div class="form-group">
                        <label for="file">Seleccionar archivo Excel:</label>
                        <input type="file" name="file" id="file" class="form-control" required>
                    </div>

                    <br>
                    <button type="submit" class="btn btn-success w-100">📤 Importar Todos los Componentes Sociales</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

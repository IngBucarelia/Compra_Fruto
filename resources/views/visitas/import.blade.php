@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Importar Visita Completa desde Excel</div>

                <div class="card-body">
                    @if(session('success'))
                    <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                        {{ session('error') }}
                    </div>
                @endif
                
                
                
                <!-- Loader -->
                <div id="loader" style="display: none; text-align: center; margin-top: 15px;">
                    <div style="border: 6px solid #f3f3f3; border-top: 6px solid green; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: auto;"></div>
                    <p>Cargando archivo...</p>
                </div>
                
                <style>
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                </style>
                
                <script>
                function mostrarLoader() {
                    document.getElementById('loader').style.display = 'block';
                }
                </script>

                    <form action="{{ route('visitas.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Seleccionar archivo Excel:</label>
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-success">Importar</button>
                    </form>
                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endsection

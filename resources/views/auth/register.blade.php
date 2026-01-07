@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-header bg-success text-white text-center">
            <h4>Registro de Usuario</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <table class="table table-borderless align-middle">
                    <tr>
                        <td><label for="name" class="fw-bold">Nombre Completo</label></td>
                        <td><input id="name" type="text" name="name" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td><label for="num_documento" class="fw-bold">Número de Documento</label></td>
                        <td><input id="num_documento" type="number" name="num_documento" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td><label for="email" class="fw-bold">Correo Institucional</label></td>
                        <td><input id="email" type="email" name="email" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td><label for="area_pertenece" class="fw-bold">Área</label></td>
                        <td><input id="area_pertenece" type="text" name="area_pertenece" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td><label for="ocupacion" class="fw-bold">Ocupación</label></td>
                        <td><input id="ocupacion" type="text" name="ocupacion" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td><label for="rol" class="fw-bold">Rol</label></td>
                        <td>
                            <select id="rol" name="rol" class="form-select" required>
                                <option value="" disabled selected>Seleccione un rol</option>
                                <option value="1">Administrador</option>
                                <option value="2">Técnico Campo Agronómico</option>
                                <option value="3">Técnico Campo Social</option>
                                <option value="4">Técnico Campo Ambiental </option>
                                 <option value="5">Auxiliar Administrativo</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="password" class="fw-bold">Contraseña</label></td>
                        <td><input id="password" type="password" name="password" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td><label for="password_confirmation" class="fw-bold">Confirmar Contraseña</label></td>
                        <td><input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required></td>
                    </tr>
                </table>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success px-4 me-2">Registrar</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary px-4">← Atrás</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

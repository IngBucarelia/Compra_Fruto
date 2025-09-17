@extends('layouts.app')

@section('content')
<div class="container offline-form-container">

    <div class="card shadow-lg border-0 rounded-4 mx-auto members-card">
        
        {{-- Selector de secciones --}}
        <form action="{{ route('redireccion_seccion_social', $visita->id) }}" method="GET" class="mt-4">
            <label for="seccion" class="form-label fw-bold text-success">📋 Ir a sección:</label>
            <div class="input-group">
                <select id="seccion" name="seccion" class="form-select" required>
                    <option value="">Seleccione una sección</option>
                    @if ($visita->estado === 'pendiente' || $visita->estado === 'en_ejecucion')
                        <option value="inicio"> Pagina de Inicio de Visita</option>
                        <option value="datos_personales">👤 Datos Personales</option>
                        <option value="miembros">👨‍👩‍👧‍👦 Miembros del Hogar</option>
                        <option value="predio">🏡 Datos del Predio</option>
                        <option value="fuerza_laboral">🧑‍🌾 Fuerza Laboral</option>
                        <option value="organizacion_social">👥 Organización Social</option>
                    @endif
                </select>
                <button type="submit" class="btn btn-success">Ir</button>
            </div>
        </form>

        {{-- Título --}}
        <h3 class="members-title">🧑‍🌾 Fuerza Laboral - Visita #{{ $visita->id }}</h3>

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        {{-- Botón de nuevo registro --}}
        <div class="form-actions mb-3">
            <a href="{{ route('fuerza_laboral.create', $visita->id) }}" class="btn btn-primary">
                ➕ Registrar fuerza laboral
            </a>
        </div>

        {{-- Tabla de registros --}}
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Forma de contratación</th>
                        <th>Trabajadores</th>
                        <th>Contrato Formal</th>
                        <th>Seguridad Social</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fuerzas as $fuerza)
                        <tr>
                            <td>{{ $fuerza->id }}</td>
                            <td>{{ implode(', ', $fuerza->forma_contratacion ?? []) }}</td>
                            <td>{{ $fuerza->num_trabajadores }}</td>
                            <td>{{ $fuerza->contrato_formal }}</td>
                            <td>{{ $fuerza->seguridad_social }}</td>
                            <td>
                                <a href="{{ route('fuerza_laboral.show', [$visita->id, $fuerza->id]) }}" class="btn btn-info btn-sm">👁 Ver</a>
                                <a href="{{ route('fuerza_laboral.edit', [$visita->id, $fuerza->id]) }}" class="btn btn-warning btn-sm">✏ Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">❌ No hay registros de fuerza laboral</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- Estilos responsivos compartidos --}}
<style>
.offline-form-container {
    max-width: 1000px;
    background: rgba(232, 213, 220, 0.9);
    border-radius: 22px;
    padding: 24px;
    margin: 0 auto;
}

.members-card {
    background: #fff;
    border-radius: 22px;
    padding: 24px;
    box-shadow: 0 8px 30px rgba(12, 50, 20, 0.08);
}

.members-title {
    font-size: 1.6rem;
    color: #19692b;
    font-weight: 700;
    margin-bottom: 20px;
}

.alert.success {
    background: #e9f7ee;
    color: #19692b;
    padding: 10px 14px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-weight: 600;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

.custom-table th {
    background: #f0fdf4;
    color: #14532d;
    text-align: left;
    padding: 10px 14px;
    font-weight: 600;
    border-bottom: 1px solid #e5e7eb;
}

.custom-table td {
    padding: 10px 14px;
    border-bottom: 1px solid #e5e7eb;
    color: #374151;
}

.custom-table tr:hover td {
    background: #f9fafb;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
}

.btn {
    display: inline-block;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    cursor: pointer;
    text-align: center;
}

.btn-primary {
    background: #198754;
    color: #fff;
    border: none;
}
.btn-primary:hover { background: #14673f; }

.btn-warning {
    background: #f59e0b;
    color: #fff;
}
.btn-warning:hover { background: #d97706; }

.btn-info {
    background: #0dcaf0;
    color: #fff;
}
.btn-info:hover { background: #0bb1d1; }

@media (max-width: 968px) {

         .container.offline-form-container {
    }
        .button-group-top {
            flex-direction: row;
            justify-content: flex-start;
        }

         .container.offline-form-container {
        padding: 15px;
            margin-top: 15px;
            border-radius: 0;
            box-shadow: none;
            width: 123%;
            max-width: none;
            margin-left: -60px !important;
    }

    .title{
    text-align: center;
    font-family: Arial Black;
    font-weight: bold;
    font-size: 30px;
    color: #fdffe5;
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    margin-bottom: 25px;
}

 .container {
        margin-left: -70px;
        width: 125%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }

        .card{
        width: 100%;
    }
    }
</style>
@endsection

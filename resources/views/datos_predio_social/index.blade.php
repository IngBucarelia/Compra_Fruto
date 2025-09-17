@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 900px; background-color: #e8d5dce0;">
        <div class="card-body">
            {{-- Botón dinámico según estado --}}
            @if ($visita->estado !== 'finalizada')
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
            @endif

            <h3 class="text-center mb-3 text-success fw-bold">🏡 Datos del Predio</h3>
            <p class="text-muted text-center mb-4">
                Visita <span class="fw-semibold">#{{ $visita->id }}</span> – Proveedor <b>{{ $visita->proveedor->proveedor_nombre }}</b>
            </p>

            @foreach($plantaciones as $plantacion)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white fw-bold">
                        🌱 Plantación: {{ $plantacion->nombre }}
                    </div>
                    <div class="card-body">
                        @php
                            $dato = $datos->where('plantacion_id', $plantacion->id)->first();
                        @endphp

                        @if($dato)
                            <p><b>📍 Nombre finca:</b> {{ $dato->nombre_finca }}</p>
                             <p><b>📍 Foma Tenencia:</b> {{ $dato->forma_tenencia }}</p>
                              <p><b>📍 Municipio:</b> {{ $dato->municipio }}</p>
                               <p><b>📍Vereda:</b> {{ $dato->vereda }}</p>
                               <p><b>📍 Registo ICA:</b> {{ $dato->registrado_ica }}</p>
                               <p><b>📍 Vive en el predio:</b> {{ $dato->vive_predio }}</p>
                                <p><b>📍 Infraestructura de predio:</b> {{ $dato->infraestructura_predio }}</p>

                            <div class="d-flex gap-2">
                                <a href="{{ route('datos_predio_social.edit', [$visita->id, $dato->id]) }}" 
                                   class="btn btn-warning btn-sm">✏️ Editar</a>
                                <form action="{{ route('datos_predio_social.destroy', [$visita->id, $dato->id]) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('datos_predio_social.create', [$visita->id, $plantacion->id]) }}" 
                               class="btn btn-success">➕ Registrar datos del predio</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<style>
.members-wrap {
    max-width: 135%;
    border-radius: 5px;
    margin-left: -60px;
}
.members-card {
     max-width: 125%;
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
    width: 40%;
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
    justify-content: space-between;
    align-items: center;
    gap: 10px;
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
.btn-primary:hover {
    background: #14673f;
}
.btn-ghost {
    background: #f3f4f6;
    color: #374151;
}
.btn-ghost:hover {
    background: #e5e7eb;
}
.btn-warning {
    background: #f59e0b;
    color: #fff;
}
.btn-warning:hover {
    background: #d97706;
}
   @media (max-width: 968px) {

         .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
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

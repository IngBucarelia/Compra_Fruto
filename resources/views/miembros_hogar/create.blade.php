@extends('layouts.app')

@section('content')
<div class="members-wrap">
    <div class="members-card" style="background-color: #e8d5dce0; max-width: 900px;">
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
        <!-- Mensaje de éxito -->
        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Título -->
        <h2 class="members-title">
            👨‍👩‍👧‍👦 Registrar Miembro del Hogar
        </h2>

        <!-- Formulario -->
        <form action="{{ route('miembros_hogar.store', $visita->id) }}" method="POST" class="form">
            @csrf

            <!-- Nombre -->
            <div class="form-group">
                <label>Nombres y Apellidos:</label>
                <input type="text" name="nombre" required>
            </div>

            <!-- Documento -->
            <div class="form-group">
                <label>Número de Documento:</label>
                <input type="text" name="documento" required>
            </div>

            <!-- Sexo -->
            <div class="form-group">
                <label>Sexo:</label>
                <select name="sexo" required>
                    <option value="">Seleccione...</option>
                    <option value="Mujer">Mujer</option>
                    <option value="Hombre">Hombre</option>
                    <option value="No se identifica">No se identifica con ninguno</option>
                </select>
            </div>

            <!-- Parentezco -->
            <div class="form-group">
                <label>Parentezco con el productor/a:</label>
                <select name="parentezco" required>
                    <option value="">Seleccione...</option>
                    <option value="Hijo/a">Hijo/a</option>
                    <option value="Hijastro/a">Hijastro/a</option>
                    <option value="Mamá">Mamá</option>
                    <option value="Papá">Papá</option>
                    <option value="Hermano/a">Hermana/o</option>
                </select>
            </div>

            <!-- Reside en predio -->
            <div class="form-group">
                <label>¿Reside en el predio?</label>
                <select name="reside_predio" required>
                    <option value="">Seleccione...</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <!-- Sabe leer -->
            <div class="form-group">
                <label>¿Sabe leer y/o escribir?</label>
                <select name="sabe_leer" required>
                    <option value="">Seleccione...</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <!-- Nivel estudio -->
            <div class="form-group">
                <label>Nivel de estudio:</label>
                <select name="nivel_estudio" required>
                    <option value="">Seleccione...</option>
                    <option value="Ninguno">Ninguno</option>
                    <option value="Técnico">Técnico</option>
                    <option value="Tecnólogo">Tecnólogo</option>
                    <option value="Profesional">Profesional</option>
                    <option value="Maestría">Maestría</option>
                </select>
            </div>

            <!-- Participa labores -->
            <div class="form-group">
                <label>¿Participa en las labores del cultivo?</label>
                <select name="participa_labores" required>
                    <option value="">Seleccione...</option>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="form-actions">
                <a href="{{ route('miembros_hogar.index', $visita->id) }}" class="btn btn-ghost">
                    ⬅ Volver
                </a>
                <button type="submit" class="btn btn-primary">
                    💾 Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Estilos locales -->
<style>
.members-wrap {
    max-width: 135%;
    border-radius: 5px;
    margin-left: 60px;
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

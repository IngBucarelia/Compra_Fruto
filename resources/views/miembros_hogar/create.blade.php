@extends('layouts.app')

@section('content')
<div class="members-wrap">
    <div class="members-card">
        <!-- 🔹 Selector de sección -->
        <form action="{{ route('redireccion_seccion_social', $visita->id) }}" method="GET" class="section-form">
            <label for="seccion" class="form-label fw-bold text-success mb-2">📋 Ir a otra sección</label>
            <div class="input-group">
                <select id="seccion" name="seccion" class="form-select" required>
                    <option value="">Seleccione una sección</option>
                    @if ($visita->estado === 'pendiente' || $visita->estado === 'en_ejecucion')
                        <option value="inicio">🏁 Inicio de Visita</option>
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

        <!-- ✅ Mensaje de éxito -->
        @if(session('success'))
            <div class="alert success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <!-- 🧾 Título principal -->
        <h2 class="members-title text-center mt-4">👨‍👩‍👧‍👦 Registrar Miembro del Hogar</h2>
        <hr class="divider">

        <!-- 🧍 Formulario de registro -->
        <form action="{{ route('miembros_hogar.store', $visita->id) }}" method="POST" class="form">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label>Nombres y Apellidos:</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Número de Documento:</label>
                    <input type="text" name="documento" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label>Sexo:</label>
                    <select name="sexo" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option value="Mujer">Mujer</option>
                        <option value="Hombre">Hombre</option>
                        <option value="No se identifica">No se identifica</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Parentezco con el Productor/a:</label>
                    <select name="parentezco" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option value="Hijo/a">Hijo/a</option>
                        <option value="Hijastro/a">Hijastro/a</option>
                        <option value="Mamá">Mamá</option>
                        <option value="Papá">Papá</option>
                        <option value="Hermano/a">Hermana/o</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>¿Reside en el Predio?</label>
                    <select name="reside_predio" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>¿Sabe leer y/o escribir?</label>
                    <select name="sabe_leer" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>Nivel de Estudio:</label>
                    <select name="nivel_estudio" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option value="Ninguno">Ninguno</option>
                        <option value="Técnico">Técnico</option>
                        <option value="Tecnólogo">Tecnólogo</option>
                        <option value="Profesional">Profesional</option>
                        <option value="Maestría">Maestría</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label>¿Participa en las labores del cultivo?</label>
                    <select name="participa_labores" class="form-select" required>
                        <option value="">Seleccione...</option>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
            </div>

            <!-- 🔘 Botones -->
            <div class="form-actions mt-4">
                <a href="{{ route('miembros_hogar.index', $visita->id) }}" class="btn btn-secondary">
                    ⬅ Volver
                </a>
                <button type="submit" class="btn btn-success">
                    💾 Guardar Registro
                </button>
            </div>
        </form>
    </div>
</div>

<!-- 🎨 Estilos -->
<style>
.members-wrap {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}
.members-card {
    background: #ffffff;
    border-radius: 20px;
    padding: 30px;
    width: 95%;
    max-width: 900px;
    box-shadow: 0 4px 20px rgba(22, 101, 52, 0.15);
    border-top: 6px solid #198754;
}
.members-title {
    font-size: 1.8rem;
    color: #14532d;
    font-weight: 800;
}
.divider {
    border: none;
    height: 2px;
    background: linear-gradient(to right, #198754, #71c17d);
    margin-bottom: 25px;
}
label {
    font-weight: 600;
    color: #14532d;
    margin-bottom: 5px;
}
.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    transition: border-color 0.3s;
}
.form-control:focus, .form-select:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.15rem rgba(25, 135, 84, 0.25);
}
.alert.success {
    background: #d1fae5;
    color: #065f46;
    padding: 12px 18px;
    border-radius: 10px;
    border: 1px solid #34d399;
}
.form-actions {
    display: flex;
    justify-content: space-between;
}
.btn {
    font-weight: 600;
    border-radius: 10px;
    padding: 10px 20px;
    border: none;
}
.btn-success {
    background: #198754;
    color: white;
}
.btn-success:hover {
    background: #146c43;
}
.btn-secondary {
    background: #e5e7eb;
    color: #374151;
}
.btn-secondary:hover {
    background: #d1d5db;
}
@media (max-width: 768px) {
    .members-card {
        width: 98%;
        padding: 20px;
    }
    .form-actions {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
@endsection

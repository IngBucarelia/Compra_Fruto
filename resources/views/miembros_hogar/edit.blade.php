@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-lg border-0 rounded-4" style="background-color: #e8d5dce0; max-width: 900px;">
        <div class="card-header bg-success text-white fw-bold">
            ✏️ Editar Miembro del Hogar
        </div>
        <div class="card-body">

            <form action="{{ route('miembros_hogar.update', [$visita->id, $miembro->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">👤 Nombres y Apellidos:</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $miembro->nombre) }}" 
                           class="form-control border-success" required>
                </div>

                <!-- Documento -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">🪪 Número de Documento:</label>
                    <input type="text" name="documento" value="{{ old('documento', $miembro->documento) }}" 
                           class="form-control border-success" required>
                </div>

                <!-- Sexo -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">⚧ Sexo:</label>
                    <select name="sexo" class="form-select border-success" required>
                        <option value="Mujer" {{ $miembro->sexo == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                        <option value="Hombre" {{ $miembro->sexo == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                        <option value="No se identifica" {{ $miembro->sexo == 'No se identifica' ? 'selected' : '' }}>No se identifica</option>
                    </select>
                </div>

                <!-- Parentezco -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">👪 Parentezco con el productor/a:</label>
                    <select name="parentezco" class="form-select border-success" required>
                        <option value="Hijo/a" {{ $miembro->parentezco == 'Hijo/a' ? 'selected' : '' }}>Hijo/a</option>
                        <option value="Hijastro/a" {{ $miembro->parentezco == 'Hijastro/a' ? 'selected' : '' }}>Hijastro/a</option>
                        <option value="Mamá" {{ $miembro->parentezco == 'Mamá' ? 'selected' : '' }}>Mamá</option>
                        <option value="Papá" {{ $miembro->parentezco == 'Papá' ? 'selected' : '' }}>Papá</option>
                        <option value="Hermano/a" {{ $miembro->parentezco == 'Hermano/a' ? 'selected' : '' }}>Hermano/a</option>
                    </select>
                </div>

                <!-- Reside en predio -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">🏡 ¿Reside en el predio?</label>
                    <select name="reside_predio" class="form-select border-success" required>
                        <option value="1" {{ $miembro->reside_predio ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ !$miembro->reside_predio ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Sabe leer -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">📖 ¿Sabe leer y/o escribir?</label>
                    <select name="sabe_leer" class="form-select border-success" required>
                        <option value="1" {{ $miembro->sabe_leer ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ !$miembro->sabe_leer ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Nivel estudio -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">🎓 Nivel de estudio:</label>
                    <select name="nivel_estudio" class="form-select border-success" required>
                        <option value="Ninguno" {{ $miembro->nivel_estudio == 'Ninguno' ? 'selected' : '' }}>Ninguno</option>
                        <option value="Técnico" {{ $miembro->nivel_estudio == 'Técnico' ? 'selected' : '' }}>Técnico</option>
                        <option value="Tecnólogo" {{ $miembro->nivel_estudio == 'Tecnólogo' ? 'selected' : '' }}>Tecnólogo</option>
                        <option value="Profesional" {{ $miembro->nivel_estudio == 'Profesional' ? 'selected' : '' }}>Profesional</option>
                        <option value="Maestría" {{ $miembro->nivel_estudio == 'Maestría' ? 'selected' : '' }}>Maestría</option>
                    </select>
                </div>

                <!-- Participa labores -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">🌱 ¿Participa en las labores del cultivo?</label>
                    <select name="participa_labores" class="form-select border-success" required>
                        <option value="1" {{ $miembro->participa_labores ? 'selected' : '' }}>Sí</option>
                        <option value="0" {{ !$miembro->participa_labores ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('miembros_hogar.index', $visita->id) }}" 
                       class="btn btn-outline-success px-4">
                        ⬅ Volver
                    </a>
                    <button type="submit" class="btn btn-success fw-bold px-4">
                        💾 Actualizar
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
<style>
.members-wrap {
    max-width: 125%;
    border-radius: 5px;
}
.container.offline-form-container {
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
    text-align: center;
    padding: 10px 14px;
    font-weight: 600;
    border-bottom: 1px solid #e5e7eb;
}
.custom-table td {
    padding: 10px 14px;
    border-bottom: 1px solid #e5e7eb;
    color: #374151;
    text-align: center;
}
.custom-table tr:hover td {
    background: #f9fafb;
}
.empty {
    text-align: center;
    color: #6b7280;
}
.actions-col .action {
    display: inline-block;
    margin: 0 4px;
    padding: 6px 8px;
    border-radius: 6px;
    font-size: 0.95rem;
}
.actions-col .edit {
    background: #fef7e6;
    color: #b76b00;
}
.actions-col .edit:hover { background: #fbeec9; }
.actions-col .delete {
    background: #fff0f0;
    color: #a71d2a;
    border: none;
}
.actions-col .delete:hover { background: #ffdede; }
.inline-form { display: inline-block; margin: 0; padding: 0; }
.pagination-wrap { display: flex; justify-content: flex-end; }

/* Responsive tabla */
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
        border-radius: 25px;
    }
    }
</style>
@endsection

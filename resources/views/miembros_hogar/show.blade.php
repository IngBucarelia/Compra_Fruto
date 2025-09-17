@extends('layouts.app')

@section('content')
<div class="members-wrap">
    <div class="container offline-form-container" style="background-color: #e8d5dce0;">
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

        <!-- Header -->
        <h2 class="members-title text-center">
            👨‍👩‍👧‍👦 Miembros del Hogar
        </h2>

        <!-- Acciones -->
        <div class="members-actions mb-4 d-flex justify-content-center gap-2 flex-wrap">
            <a href="{{ route('miembros_hogar.create', $visita->id) }}" class="btn btn-primary">
                ➕ Agregar Miembro
            </a>
            <a href="{{ route('datos_personales_sociales.create', $visita->id) }}" class="btn btn-ghost">
                ← Ver Datos Personales
            </a>
            <a href="{{ route('datos_predio_social.index', $visita->id) }}" class="btn btn-warning">
                🏡 Ir a Información Predio
            </a>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Sexo</th>
                        <th>Parentezco</th>
                        <th>Reside en Predio</th>
                        <th>Sabe Leer</th>
                        <th>Nivel Estudio</th>
                        <th>Participa Labores</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($miembros as $miembro)
                        <tr>
                            <td data-label="Nombre">{{ $miembro->nombre }}</td>
                            <td data-label="Documento">{{ $miembro->documento }}</td>
                            <td data-label="Sexo">{{ $miembro->sexo }}</td>
                            <td data-label="Parentezco">{{ $miembro->parentezco }}</td>
                            <td data-label="Reside en Predio">{{ $miembro->reside_predio ? 'Sí' : 'No' }}</td>
                            <td data-label="Sabe Leer">{{ $miembro->sabe_leer ? 'Sí' : 'No' }}</td>
                            <td data-label="Nivel Estudio">{{ $miembro->nivel_estudio }}</td>
                            <td data-label="Participa Labores">{{ $miembro->participa_labores ? 'Sí' : 'No' }}</td>
                            <td data-label="Acciones" class="actions-col">
                                <a href="{{ route('miembros_hogar.edit', [$visita->id, $miembro->id]) }}" class="action edit">
                                    ✏️
                                </a>
                                <form action="{{ route('miembros_hogar.destroy', [$visita->id, $miembro->id]) }}"
                                      method="POST" class="inline-form delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action delete delete-member" title="Eliminar">
                                        🗑️
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty">No hay miembros registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if(method_exists($miembros, 'links'))
            <div class="pagination-wrap mt-3">
                {{ $miembros->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Estilos locales -->
<style>
.members-wrap {
    max-width: 125%;
    border-radius: 55px;
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
            border-radius: 45%;
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

<!-- Confirmación de eliminación -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-member').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de eliminar este miembro del hogar?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endsection

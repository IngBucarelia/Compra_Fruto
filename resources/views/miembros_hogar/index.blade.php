@extends('layouts.app')

@section('content')
<div class="members-wrap">
    <div class="members-card" style="background-color: #e8d5dce0; max-width: 900px;">
        
        <div class="members-header" style="background-color: #e8d5dce0; max-width: 900px;">
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
            <h2 class="members-title">👨‍👩‍👧‍👦 Miembros dele Hogar</h2>

            <div class="members-actions">
                <a href="{{ route('miembros_hogar.create', $visita->id) }}" class="btn btn-primary">
                    ➕ Agregar Miembro
                </a>
                <a href="{{ route('datos_personales_sociales.create', $visita->id) }}" class="btn btn-ghost">
                    ← Ver Datos Personales
                </a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="members-table" role="table" aria-label="Miembros del hogar">
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

        {{-- Paginación si la tienes --}}
        @if(method_exists($miembros, 'links'))
            <div class="pagination-wrap">
                {{ $miembros->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Estilos locales (inserta aquí, no toques appbar/slider) -->
<style>
/* Contenedor principal centrado */
.members-wrap {
    max-width: 1100px;
    margin: 24px auto;
    padding: 0 16px;
}

/* Card blanco con sombra */
.members-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 8px 30px rgba(12, 50, 20, 0.08);
    border: 1px solid rgba(0,0,0,0.04);
}

/* Header: título + acciones */
.members-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.members-title {
    font-size: 1.5rem;
    color: #19692b; /* verde oscuro */
    font-weight: 700;
    margin: 0;
}

/* Botones */
.members-actions { display: flex; gap: 8px; align-items:center; }

.btn {
    display: inline-block;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: none;
    border: none;
    cursor: pointer;
}
.btn-primary {
    background: #198754;
    color: #fff;
}
.btn-primary:hover { background: #14673f; }
.btn-ghost {
    background: #f2f4f3;
    color: #2d2d2d;
}
.btn-ghost:hover { background: #e6e9e7; }

/* Tabla */
.table-wrapper {
    width: 100%;
    overflow-x: auto;
    border-radius: 8px;
    border: 1px solid #eef3ee;
    padding: 8px;
}

.members-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 920px; /* fuerza scroll en pantallas pequeñas */
    font-size: 0.95rem;
}

.members-table thead th {
    text-align: left;
    padding: 10px 12px;
    background: #e9f7ee;
    color: #19692b;
    font-weight: 700;
    border-bottom: 1px solid #e6f1ea;
}

.members-table tbody td {
    padding: 10px 12px;
    border-bottom: 1px solid #f3f6f3;
    vertical-align: middle;
    text-align: center;
}

.members-table tbody tr:hover {
    background: #fbfffb;
}

/* Empty row */
.empty {
    color: #6b6b6b;
    padding: 18px;
    text-align: center;
}

/* Actions column buttons */
.actions-col .action {
    display: inline-block;
    margin: 0 4px;
    padding: 6px 8px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.98rem;
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

/* Inline form */
.inline-form { display: inline-block; margin: 0; padding: 0; }

/* Pagination container (if present) */
.pagination-wrap {
    margin-top: 14px;
    display: flex;
    justify-content: flex-end;
}

/* Responsive: en móviles mostramos etiquetas de data-label para filas */
@media (max-width: 880px) {
    .members-header { flex-direction: column; align-items: flex-start; gap: 10px; }
    .members-title { font-size: 1.25rem; }

    .members-table {
        min-width: 0;
        font-size: 0.92rem;
    }

    /* hacer tabla más legible en muy pequeño: mostrar cada celda en bloque */
    .members-table thead { display: none; }
    .members-table tbody td {
        display: block;
        text-align: left;
        padding: 10px 12px;
        border-bottom: 1px solid #f1f4f1;
    }
    .members-table tbody tr { margin-bottom: 12px; display: block; border-radius: 8px; background: #ffffff; box-shadow: 0 1px 0 rgba(0,0,0,0.02); padding: 8px; }
    .members-table tbody td:before {
        content: attr(data-label) ": ";
        font-weight: 700;
        color: #3b6b3b;
        display: inline-block;
        width: 48%;
    }
    .actions-col { text-align: right; }
}
</style>

<!-- Confirmación simple al eliminar -->
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

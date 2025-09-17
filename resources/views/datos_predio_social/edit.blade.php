@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 900px; background-color: #f8fdf8;">
        <div class="card-body">
            <h3 class="text-center mb-4 text-success fw-bold">✏️ Editar Datos del Predio</h3>
            <p class="text-muted text-center mb-4">
                Plantación: <b>{{ $plantacion->nombre }}</b> – Proveedor: <b>{{ $plantacion->proveedor_nombre }}</b>
            </p>

            <form action="{{ route('datos_predio_social.update', [$visita->id, $dato->id]) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Nombre finca -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">🏡 Nombre de la finca:</label>
                    <input type="text" name="nombre_finca" class="form-control" value="{{ old('nombre_finca', $dato->nombre_finca) }}" required>
                </div>

                <!-- Forma de tenencia -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">📜 Forma de tenencia:</label>
                    <select name="forma_tenencia[]" class="form-select" multiple required>
                        <option value="Propietario con escritura" {{ in_array('Propietario con escritura', $dato->forma_tenencia ?? []) ? 'selected' : '' }}>Propietario con escritura</option>
                        <option value="Arrendamiento" {{ in_array('Arrendamiento', $dato->forma_tenencia ?? []) ? 'selected' : '' }}>Arrendamiento</option>
                        <option value="Carta venta" {{ in_array('Carta venta', $dato->forma_tenencia ?? []) ? 'selected' : '' }}>Carta venta</option>
                        <option value="Tradición y libertad" {{ in_array('Tradición y libertad', $dato->forma_tenencia ?? []) ? 'selected' : '' }}>Tradición y libertad</option>
                    </select>
                </div>

                <!-- Municipio -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">🌍 Municipio:</label>
                    <input type="text" name="municipio" class="form-control" value="{{ old('municipio', $dato->municipio) }}" required>
                </div>

                <!-- Vereda -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">🏘️ Vereda:</label>
                    <input type="text" name="vereda" class="form-control" value="{{ old('vereda', $dato->vereda) }}" required>
                </div>

                <!-- ICA -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">📋 Registro ICA:</label>
                    <select name="registro_ica" class="form-select" required>
                        <option value="SI" {{ $dato->registro_ica == 'SI' ? 'selected' : '' }}>Sí</option>
                        <option value="NO" {{ $dato->registro_ica == 'NO' ? 'selected' : '' }}>No</option>
                        <option value="En gestión" {{ $dato->registro_ica == 'En gestión' ? 'selected' : '' }}>En gestión</option>
                    </select>
                </div>

                <!-- Vive en predio -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">👤 Vive en el predio:</label>
                    <select name="vive_predio" class="form-select" required>
                        <option value="SI" {{ $dato->vive_predio == 'SI' ? 'selected' : '' }}>Sí</option>
                        <option value="NO" {{ $dato->vive_predio == 'NO' ? 'selected' : '' }}>No</option>
                        <option value="NO (va por temporadas)" {{ $dato->vive_predio == 'NO (va por temporadas)' ? 'selected' : '' }}>No (va por temporadas)</option>
                    </select>
                </div>

                <!-- Infraestructura vial -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">🛣️ Infraestructura vial:</label>
                    <select name="infraestructura_vial[]" class="form-select" multiple required>
                        <option value="Vía destapada carreteable" {{ in_array('Vía destapada carreteable', $dato->infraestructura_vial ?? []) ? 'selected' : '' }}>Vía destapada carreteable</option>
                        <option value="Vía pavimentada" {{ in_array('Vía pavimentada', $dato->infraestructura_vial ?? []) ? 'selected' : '' }}>Vía pavimentada</option>
                        <option value="Camino" {{ in_array('Camino', $dato->infraestructura_vial ?? []) ? 'selected' : '' }}>Camino</option>
                        <option value="Fluvial" {{ in_array('Fluvial', $dato->infraestructura_vial ?? []) ? 'selected' : '' }}>Fluvial</option>
                    </select>
                </div>

                <!-- Infraestructura vivienda -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">🏠 ¿Cuenta con vivienda o infraestructura?:</label>
                    <select name="infraestructura_vivienda" class="form-select" required>
                        <option value="SI" {{ $dato->infraestructura_vivienda == 'SI' ? 'selected' : '' }}>Sí</option>
                        <option value="NO" {{ $dato->infraestructura_vivienda == 'NO' ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('datos_predio_social.index', $visita->id) }}" class="btn btn-secondary">⬅ Volver</a>
                    <button type="submit" class="btn btn-success">💾 Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

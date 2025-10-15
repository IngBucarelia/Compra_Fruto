@extends('layouts.app')

@section('content')
<div class="container form-container p-4 rounded shadow"style="background-color: #e8d5dce0; max-width: 900px;">
    <h3 class="mb-4 text-center text-success">✏️ Editar Datos Personales del Productor</h3>

    <form action="{{ route('datos_personales_sociales.update', $visita->id) }}" method="POST">
        @csrf
        @method('PUT')
    <input type="hidden" name="id" value="{{ $datos->id }}">

        {{-- Teléfono --}}
        <div class="mb-3">
            <label class="form-label fw-bold">📞 Teléfono del productor/a</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $datos->telefono) }}">
        </div>

        {{-- Sexo --}}
        <div class="mb-3">
            <label class="form-label fw-bold">⚧ Sexo</label>
            <select name="sexo" class="form-select">
                <option value="">Seleccione</option>
                <option {{ old('sexo', $datos->sexo) == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                <option {{ old('sexo', $datos->sexo) == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                <option {{ old('sexo', $datos->sexo) == 'No se identifica' ? 'selected' : '' }}>No se identifica</option>
            </select>
        </div>

        {{-- RNP --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🆔 Cuenta con RNP?</label>
            <select name="rnp" class="form-select">
                <option {{ old('rnp', $datos->rnp) == 'SI' ? 'selected' : '' }}>SI</option>
                <option {{ old('rnp', $datos->rnp) == 'NO' ? 'selected' : '' }}>NO</option>
            </select>
        </div>

        {{-- Fedepalma --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🌴 Afiliado/a a Fedepalma?</label>
            <select name="fedepalma" class="form-select">
                <option {{ old('fedepalma', $datos->fedepalma) == 'SI' ? 'selected' : '' }}>SI</option>
                <option {{ old('fedepalma', $datos->fedepalma) == 'NO' ? 'selected' : '' }}>NO</option>
            </select>
        </div>

        {{-- Alfabetizado --}}
        <div class="mb-3">
            <label class="form-label fw-bold">📖 Sabe leer y/o escribir?</label>
            <select name="alfabetizado" class="form-select">
                <option {{ old('alfabetizado', $datos->alfabetizado) == 'SI' ? 'selected' : '' }}>SI</option>
                <option {{ old('alfabetizado', $datos->alfabetizado) == 'NO' ? 'selected' : '' }}>NO</option>
            </select>
        </div>

        {{-- Nivel de estudio --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🎓 Nivel de estudio</label>
            <select name="nivel_estudio" class="form-select">
                <option value="">Seleccione</option>
                <option {{ old('nivel_estudio', $datos->nivel_estudio) == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                <option {{ old('nivel_estudio', $datos->nivel_estudio) == 'Bachillerato' ? 'selected' : '' }}>Bachillerato</option>
                <option {{ old('nivel_estudio', $datos->nivel_estudio) == 'Ninguno' ? 'selected' : '' }}>Ninguno</option>
                <option {{ old('nivel_estudio', $datos->nivel_estudio) == 'Tecnico' ? 'selected' : '' }}>Tecnico</option>
                <option {{ old('nivel_estudio', $datos->nivel_estudio) == 'Tecnologo' ? 'selected' : '' }}>Tecnologo</option>
                <option {{ old('nivel_estudio', $datos->nivel_estudio) == 'Profesional' ? 'selected' : '' }}>Profesional</option>
                <option {{ old('nivel_estudio', $datos->nivel_estudio) == 'Maestria' ? 'selected' : '' }}>Maestria</option>
            </select>
        </div>

        {{-- Otras líneas --}}
        <div class="mb-3">
            <label class="form-label fw-bold">💼 Otras líneas de negocio?</label>
            <select name="otras_lineas" class="form-select">
                <option {{ old('otras_lineas', $datos->otras_lineas) == 'SI' ? 'selected' : '' }}>SI</option>
                <option {{ old('otras_lineas', $datos->otras_lineas) == 'NO' ? 'selected' : '' }}>NO</option>
            </select>
        </div>

        {{-- Fecha nacimiento --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🎂 Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento', $datos->fecha_nacimiento) }}">
        </div>

        {{-- Grupo poblacional --}}
        <div class="mb-3">
            <label class="form-label fw-bold">👥 Grupo poblacional</label>
            <select name="grupo_poblacional" class="form-select">
                <option value="">Seleccione</option>
                <option {{ old('grupo_poblacional', $datos->grupo_poblacional) == 'Indigena' ? 'selected' : '' }}>Indigena</option>
                <option {{ old('grupo_poblacional', $datos->grupo_poblacional) == 'Negro, mulato afrodescendiente' ? 'selected' : '' }}>Negro, mulato afrodescendiente</option>
                <option {{ old('grupo_poblacional', $datos->grupo_poblacional) == 'Campesino' ? 'selected' : '' }}>Campesino</option>
                <option {{ old('grupo_poblacional', $datos->grupo_poblacional) == 'Ninguno de los anteriores' ? 'selected' : '' }}>Ninguno de los anteriores</option>
            </select>
        </div>

        {{-- Reside en el predio --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🏠 Reside en el predio?</label>
            <select name="reside_predio" class="form-select">
                <option {{ old('reside_predio', $datos->reside_predio) == 'SI' ? 'selected' : '' }}>SI</option>
                <option {{ old('reside_predio', $datos->reside_predio) == 'NO' ? 'selected' : '' }}>NO</option>
            </select>
        </div>

        {{-- Administra cultivo --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🌱 Administra el cultivo?</label>
            <select name="administra_cultivo" class="form-select">
                <option {{ old('administra_cultivo', $datos->administra_cultivo) == 'SI' ? 'selected' : '' }}>SI</option>
                <option {{ old('administra_cultivo', $datos->administra_cultivo) == 'NO' ? 'selected' : '' }}>NO</option>
                <option {{ old('administra_cultivo', $datos->administra_cultivo) == 'Lo realiza un tercero' ? 'selected' : '' }}>Lo realiza un tercero</option>
            </select>
        </div>

        {{-- Supervisa cultivo --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🔎 Supervisa el cultivo?</label>
            <select name="supervisa_cultivo" class="form-select">
                <option {{ old('supervisa_cultivo', $datos->supervisa_cultivo) == 'SI' ? 'selected' : '' }}>SI</option>
                <option {{ old('supervisa_cultivo', $datos->supervisa_cultivo) == 'NO' ? 'selected' : '' }}>NO</option>
                <option {{ old('supervisa_cultivo', $datos->supervisa_cultivo) == 'Lo realiza un tercero' ? 'selected' : '' }}>Lo realiza un tercero</option>
            </select>
        </div>

        {{-- Realiza cultivo --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🛠️ Realiza labores del cultivo?</label>
            <select name="realiza_cultivo" class="form-select">
                <option {{ old('realiza_cultivo', $datos->realiza_cultivo) == 'SI' ? 'selected' : '' }}>SI</option>
                <option {{ old('realiza_cultivo', $datos->realiza_cultivo) == 'NO' ? 'selected' : '' }}>NO</option>
                <option {{ old('realiza_cultivo', $datos->realiza_cultivo) == 'Las realiza un tercero' ? 'selected' : '' }}>Las realiza un tercero</option>
            </select>
        </div>

        {{-- Años en palmicultura --}}
        <div class="mb-3">
            <label class="form-label fw-bold">⏳ Años en la palmicultura</label>
            <input type="number" name="anios_palmicultura" class="form-control" value="{{ old('anios_palmicultura', $datos->anios_palmicultura) }}">
        </div>

        {{-- Internet --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🌐 Acceso a internet?</label>
            <select name="internet" class="form-select">
                <option {{ old('internet', $datos->internet) == 'SI' ? 'selected' : '' }}>SI</option>
                <option {{ old('internet', $datos->internet) == 'NO' ? 'selected' : '' }}>NO</option>
            </select>
        </div>

        {{-- Tipo persona --}}
        <div class="mb-3">
            <label class="form-label fw-bold">👤 Tipo de persona comercial</label>
            <select name="tipo_persona" class="form-select">
                <option {{ old('tipo_persona', $datos->tipo_persona) == 'Natural' ? 'selected' : '' }}>Natural</option>
                <option {{ old('tipo_persona', $datos->tipo_persona) == 'Juridica' ? 'selected' : '' }}>Juridica</option>
            </select>
        </div>

        {{-- Red social --}}
        <div class="mb-3">
            <label class="form-label fw-bold">📱 Red social que más usa</label>
            <select name="red_social" class="form-select">
                <option {{ old('red_social', $datos->red_social) == 'Whatsapp' ? 'selected' : '' }}>Whatsapp</option>
                <option {{ old('red_social', $datos->red_social) == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                <option {{ old('red_social', $datos->red_social) == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                <option {{ old('red_social', $datos->red_social) == 'TikTok' ? 'selected' : '' }}>TikTok</option>
                <option {{ old('red_social', $datos->red_social) == 'Todas las anteriores' ? 'selected' : '' }}>Todas las anteriores</option>
                <option {{ old('red_social', $datos->red_social) == 'Ninguna de las anteriores' ? 'selected' : '' }}>Ninguna de las anteriores</option>
            </select>
        </div>

        {{-- Régimen de salud --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🏥 Régimen de salud</label>
            <select name="regimen_salud" class="form-select">
                <option value="Contributivo" {{ old('regimen_salud', $datos->regimen_salud) == 'Contributivo' ? 'selected' : '' }}>Contributivo</option>
                <option value="Subsidiado" {{ old('regimen_salud', $datos->regimen_salud) == 'Subsidiado' ? 'selected' : '' }}>Subsidiado</option>
                <option value="Especial" {{ old('regimen_salud', $datos->regimen_salud) == 'Regimen especial' ? 'selected' : '' }}>Regimen especial</option>
                <option value="Ninguno" {{ old('regimen_salud', $datos->regimen_salud) == 'Ninguno' ? 'selected' : '' }}>Ninguno</option>
            </select>

        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('visitas_social.showSocial', $visita->id) }}" class="btn btn-secondary px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4">💾 Actualizar</button>
        </div>
    </form>
</div>
@endsection

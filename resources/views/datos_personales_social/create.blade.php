@extends('layouts.app')

@section('content')
<div class="container form-container p-4 rounded shadow" style="background-color: #e8d5dce0; max-width: 900px;">
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
    <h3 class="mb-4 text-center text-success">🧑‍🌾 Datos Personales de Proveedor - Visita Social </h3>
     <div class="mb-4">
        <a href="{{ route('miembros_hogar.index', $visita->id) }}" 
           class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700">
           👨‍👩‍👧‍👦 Ir a Miembros del Hogar
        </a>
    </div>
    <form action="{{ route('datos_personales_sociales.store', $visita->id) }}" method="POST">
        @csrf

        {{-- Teléfono --}}
        <div class="mb-3">
            <label class="form-label fw-bold">📞 Teléfono del productor/a</label>
            <input type="text" name="telefono" class="form-control" placeholder="Digite el teléfono">
        </div>

        {{-- Sexo --}}
        <div class="mb-3">
            <label class="form-label fw-bold">⚧ Sexo</label>
            <select name="sexo" class="form-select">
                <option value="">Seleccione</option>
                <option>Hombre</option>
                <option>Mujer</option>
                <option>No se identifica</option>
            </select>
        </div>

        {{-- RNP --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🆔 Cuenta con RNP (Registro Nacional Palmero)?</label>
            <select name="rnp" class="form-select">
                <option>SI</option>
                <option>NO</option>
            </select>
        </div>

        {{-- Fedepalma --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🌴 Se encuentra afiliado/a a Fedepalma?</label>
            <select name="fedepalma" class="form-select">
                <option>SI</option>
                <option>NO</option>
            </select>
        </div>

        {{-- Alfabetizado --}}
        <div class="mb-3">
            <label class="form-label fw-bold">📖 Sabe leer y/o escribir?</label>
            <select name="alfabetizado" class="form-select">
                <option>SI</option>
                <option>NO</option>
            </select>
        </div>
        {{-- Nivel de estudio --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🎓 Nivel de estudio</label>
            <select name="nivel_estudio" class="form-select">
                <option value="">Seleccione</option>
                <option value="Primaria">Primaria</option>
                <option value="Bachillerato">Bachillerato</option>
                <option value="Tecnico">Tecnico</option>
                <option value="Tecnologo">Tecnologo</option>
                <option value="Profesional">Profesional</option>
                <option value="Maestria">Maestria</option>
                <option value="Ninguno">Ninguno</option>
            </select>
        </div>

        {{-- Otras líneas --}}
        <div class="mb-3">
            <label class="form-label fw-bold">💼 Cuenta con otras líneas de negocio dentro de la finca?</label>
            <select name="otras_lineas" class="form-select">
                <option>SI</option>
                <option>NO</option>
            </select>
        </div>

        {{-- Fecha nacimiento --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🎂 Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control">
        </div>
        {{-- Grupo poblacional --}}
        <div class="mb-3">
        <label class="form-label fw-bold">👥 Grupo poblacional</label>
        <select name="grupo_poblacional" class="form-select">
            <option value="">Seleccione</option>
            <option value="Indigena">Indigena</option>
            <option value="Afrodescendiente">Afrodescendiente</option>
            <option value="Campesino">Campesino</option>
            <option value="Ninguno">Ninguno de los anteriores</option>
        </select>
    </div>


        {{-- Reside en el predio --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🏠 Reside en el predio donde se realiza la producción palmera?</label>
            <select name="reside_predio" class="form-select">
                <option>SI</option>
                <option>NO</option>
            </select>
        </div>

        {{-- Administra cultivo --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🌱 Administra las labores del cultivo?</label>
            <select name="administra_cultivo" class="form-select">
                <option>SI</option>
                <option>NO</option>
                <option>Lo realiza un tercero</option>
            </select>
        </div>

        {{-- Supervisa cultivo --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🔎 Supervisa las labores del cultivo?</label>
            <select name="supervisa_cultivo" class="form-select">
                <option>SI</option>
                <option>NO</option>
                <option>Lo realiza un tercero</option>
            </select>
        </div>

        {{-- Realiza cultivo --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🛠️ Realiza las labores del cultivo?</label>
            <select name="realiza_cultivo" class="form-select">
                <option>SI</option>
                <option>NO</option>
                <option>Las realiza un tercero</option>
            </select>
        </div>

        {{-- Años en palmicultura --}}
        <div class="mb-3">
            <label class="form-label fw-bold">⏳ ¿Cuántos años lleva en la palmicultura?</label>
            <input type="number" name="anios_palmicultura" class="form-control" placeholder="Ej: 10">
        </div>

        {{-- Internet --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🌐 Cuenta con acceso a internet?</label>
            <select name="internet" class="form-select">
                <option>SI</option>
                <option>NO</option>
            </select>
        </div>

        {{-- Tipo persona --}}
        <div class="mb-3">
            <label class="form-label fw-bold">👤 Tipo de persona comercial</label>
            <select name="tipo_persona" class="form-select">
                <option>Natural</option>
                <option>Juridica</option>
            </select>
        </div>

        {{-- Red social --}}
        <div class="mb-3">
            <label class="form-label fw-bold">📱 Red social que más usa</label>
            <select name="red_social" class="form-select">
                <option>Whatsapp</option>
                <option>Facebook</option>
                <option>Instagram</option>
                <option>TikTok</option>
                <option>Todas las anteriores</option>
                <option>Ninguna de las anteriores</option>
            </select>
        </div>

        {{-- Régimen de salud --}}
        <div class="mb-3">
            <label class="form-label fw-bold">🏥 Régimen de salud</label>
            <select name="regimen_salud" class="form-select">
                <option>Contributivo</option>
                <option>Subsidiado</option>
                <option>Regimen especial</option>
                <option>Ninguno</option>
            </select>
        </div>

        {{-- Botones --}}
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('visitas_social.showSocial', $visita->id) }}" class="btn btn-secondary px-4">Cancelar</a>
            <button type="submit" class="btn btn-success px-4">💾 Guardar</button>
        </div>
    </form>
</div>

<style>
    /* Puedes pegar el contenido del bloque 'Estilos CSS para Formularios Responsivos (Reutilizable)' aquí
       si no lo tienes en un archivo CSS global enlazado en layouts/app.blade.php. */

    /* Estilos específicos para este formulario si los necesitas */
    .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
    }
    .offline-form-container h2.title {
        text-align: center;
        font-family: Arial Black;
        font-weight: bold;
        font-size: 30px;
        color: #fdffe5;
        text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    }
    .info-visita span {
        color: wheat;
    }
    .button-group-top {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 30px;
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

    .title{
    text-align: center;
    font-family: Arial Black;
    font-weight: bold;
    font-size: 30px;
    color: #fdffe5;
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    margin-bottom: 25px;
}

    /* Estilos para los formularios de área dinámicos */
    .area-form-card {
        background-color: #a5b8a5; /* Un color claro para las tarjetas de formulario */
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        position: relative; /* Para el botón de eliminar */
    }
    .area-form-card .remove-area-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        font-size: 1.2em;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
    .area-form-card .remove-area-btn:hover {
        background-color: #c82333;
    }
    .area-form-card h4 {
        margin-bottom: 20px;
        color: #28a745;
        border-bottom: 1px dashed #729079;
        padding-bottom: 10px;
    }
</style>
@endsection

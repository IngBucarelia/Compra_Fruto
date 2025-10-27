@extends('layouts.app')

@section('content')
<div class="container ">
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 900px; background-color: #f8fdf8;">
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
        <div class="card-body">
            <h3 class="text-center mb-4 text-success fw-bold">➕ Registrar Datos del Predio</h3>
            <p class="text-muted text-center mb-4">
                Plantación: <b>{{ $plantacion->nombre }}</b> – Proveedor: <b>{{ $plantacion->proveedor->proveedor_nombre }}</b>
            </p>

            <form action="{{ route('datos_predio_social.store', [$visita->id, $plantacion->id]) }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nombre finca -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">🏡 Nombre de la finca:</label>
                    <input readonly type="text" value="{{ $plantacion->nombre }}" name="nombre_finca" class="form-control" required>
                </div>

                <!-- Forma de tenencia -->
                <div class="mb-3">
                    <label class="form-label fw-semibold d-block">📜 Forma de tenencia:</label>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="forma_tenencia[]" value="Propietario con escritura" id="tenencia1">
                        <label class="form-check-label" for="tenencia1">Propietario con escritura</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="forma_tenencia[]" value="Arrendamiento" id="tenencia2">
                        <label class="form-check-label" for="tenencia2">Arrendamiento</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="forma_tenencia[]" value="Carta venta" id="tenencia3">
                        <label class="form-check-label" for="tenencia3">Carta venta</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="forma_tenencia[]" value="Tradición y libertad" id="tenencia4">
                        <label class="form-check-label" for="tenencia4">Tradición y libertad</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="forma_tenencia[]" value="En Sucesión" id="tenencia5">
                        <label class="form-check-label" for="tenencia4">En Sucesión</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="forma_tenencia[]" value="Problemas Jurídicos" id="tenencia6">
                        <label class="form-check-label" for="tenencia4">Predio con Problemas Jurídicos</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="forma_tenencia[]" value="Sana Posesión" id="tenencia7">
                        <label class="form-check-label" for="tenencia4">Sana Posesión</label>
                    </div>

                    <small class="text-muted">Puedes seleccionar una o varias opciones</small>
                </div>


                                <!-- Municipio -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">🌍 Municipio:</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        value="{{ $plantacion->municipio }}" 
                        readonly
                    >
                    <input type="hidden" name="municipio" value="{{ $plantacion->municipio }}">
                </div>

                <!-- 🏘️ Vereda -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">🏘️ Vereda:</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        value="{{ $plantacion->vereda }}" 
                        readonly
                    >
                    <input type="hidden" name="vereda" value="{{ $plantacion->vereda }}">
                </div>


                <!-- ICA -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">📋 Registro ICA:</label>
                    <select name="registrado_ica" class="form-select" required>
                        <option value="SI">Sí</option>
                        <option value="NO">No</option>
                        <option value="En gestión">En gestión</option>
                    </select>
                </div>

                <!-- Vive en predio -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">👤 Vive en el predio:</label>
                    <select name="vive_predio" class="form-select" required>
                        <option value="SI">Sí</option>
                        <option value="NO">No</option>
                        <option value="NO (va por temporadas)">No (va por temporadas)</option>
                    </select>
                </div>

                <!-- Infraestructura vial -->
                <div class="mb-3">
                    <label class="form-label fw-semibold d-block">🛣️ Infraestructura vial:</label>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="infraestructura_vial[]" value="Vía destapada carreteable" id="vial1">
                        <label class="form-check-label" for="vial1">Vía destapada carreteable</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="infraestructura_vial[]" value="Vía pavimentada" id="vial2">
                        <label class="form-check-label" for="vial2">Vía pavimentada</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="infraestructura_vial[]" value="Camino" id="vial3">
                        <label class="form-check-label" for="vial3">Camino</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="infraestructura_vial[]" value="Fluvial" id="vial4">
                        <label class="form-check-label" for="vial4">Fluvial</label>
                    </div>

                    <small class="text-muted">Puedes seleccionar una o varias opciones</small>
                </div>


                <!-- Infraestructura vivienda -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">🏠 ¿Cuenta con vivienda o infraestructura?:</label>
                    <select name="infraestructura_predio" class="form-select" required>
                        <option value="SI">Sí</option>
                        <option value="NO">No</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('datos_predio_social.index', $visita->id) }}" class="btn btn-secondary">⬅ Volver</a>
                    <button type="submit" class="btn btn-success">💾 Guardar</button>
                </div>
            </form>
        </div>
    </div>
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

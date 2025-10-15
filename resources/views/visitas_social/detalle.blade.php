@extends('layouts.app')

@section('content')

<style>
    .container{
        background-color: #e8d5dce0;
        padding: 20px;
        width: 120%;
        border-radius: 8px; /* Añadido para consistencia */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Añadido para consistencia */
        max-width: 900px !important; /* Ajusta el ancho para mejor visualización */
        
        margin-top: 25px; /* Margen superior para separación */
    }

    .info-header {
        text-align: center;
        font-family: Arial Black;
        font-weight: bold;
        font-size: 24px;
        color: #fdffe5;
        text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
        margin-bottom: 20px;
    }

    .info-detail span {
        color: rgb(36, 56, 39);
    }
    

    .accordion-item .accordion-button {
        background-color: darkseagreen !important;
        color: aliceblue !important;
        font-weight: bold;
    }
    .accordion-item .accordion-body {
        background-color: rgb(209, 241, 209) !important;
        color: rgb(31, 32, 34);
    }

    .data-card {
        background-color: #f0fff0;
        border: 1px solid #d4edda;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .data-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .data-card li {
        padding: 5px 0;
        border-bottom: 1px dashed #e2e6ea;
    }
    .data-card li:last-child {
        border-bottom: none;
    }

    .firma-img, .img-thumb {
        max-height: 150px; /* Ajustado para mejor visualización */
        width: auto;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .img-thumb {
        max-width: 100%; /* Asegura que no se desborde en columnas pequeñas */
        height: auto;
    }
    .firma-img {
        max-width: 200px;   /* Ancho máximo */
        max-height: 120px;  /* Alto máximo */
        margin: 10px auto;  /* Espaciado alrededor */
        display: block;     /* Centrar dentro del div */
        border: 1px solid #ccc; /* Borde suave */
        padding: 5px;
        background: #f9f9f9; /* Fondo claro */
        border-radius: 6px;
    }

    .firma-container {
        display: flex;
        justify-content: space-around;
        align-items: center;
        flex-wrap: wrap; /* Por si se reduce el ancho en móvil */
    }

    .firma-container .col-md-4 {
        margin-bottom: 20px;
    }

    /* Media Queries para Responsividad */
    @media (max-width: 767.98px) {
        .container {
            margin-left: -35px !important;
            width: 100%;
            padding: 15px;
            border-radius: 0;
            box-shadow: none;
        }
        .info-header {
            font-size: 20px;
        }
        .accordion-button {
            font-size: 0.9em;
        }
        .data-card {
            padding: 10px;
        } 
        .firma-img, .img-thumb {
            max-height: 100px;
        }
    }
</style>

<div class="container">
    <h2>Detalle de la Visita Social #{{ $visita->id }}</h2>

    <div class="accordion" id="accordionVisita">

        <!-- Datos Personales -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingDatosPersonales">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDatosPersonales">
                    👤 Datos Personales
                </button>
            </h2>
            <div id="collapseDatosPersonales" class="accordion-collapse collapse show" data-bs-parent="#accordionVisita">
                <div class="accordion-body">
                    @if($visita->datosPersonales)
                        <p><strong>Proveedor:</strong> {{ $visita->datosPersonales->proveedor ? $visita->datosPersonales->proveedor->proveedor_nombre : 'N/A' }}</p>
                        <p><strong>Teléfono:</strong> {{ $visita->datosPersonales->telefono ?? 'N/A' }}</p>
                        <p><strong>Sexo:</strong> {{ $visita->datosPersonales->sexo ?? 'N/A' }}</p>
                        <p><strong>RNP:</strong> {{ $visita->datosPersonales->rnp ?? 'N/A' }}</p>
                        <p><strong>Fedepalma:</strong> {{ $visita->datosPersonales->fedepalma ?? 'N/A' }}</p>
                        <p><strong>Alfabetizado:</strong> {{ $visita->datosPersonales->alfabetizado ?? 'N/A' }}</p>
                        <p><strong>Nivel de estudio:</strong> {{ $visita->datosPersonales->nivel_estudio ?? 'N/A' }}</p>
                        <p><strong>Otras líneas:</strong> {{ $visita->datosPersonales->otras_lineas ?? 'N/A' }}</p>
                        <p><strong>Fecha de nacimiento:</strong> {{ $visita->datosPersonales->fecha_nacimiento ?? 'N/A' }}</p>
                        <p><strong>Grupo poblacional:</strong> {{ $visita->datosPersonales->grupo_poblacional ?? 'N/A' }}</p>
                        <p><strong>Reside en el predio:</strong> {{ $visita->datosPersonales->reside_predio ?? 'N/A' }}</p>
                        <p><strong>Administra cultivo:</strong> {{ $visita->datosPersonales->administra_cultivo ?? 'N/A' }}</p>
                        <p><strong>Supervisa cultivo:</strong> {{ $visita->datosPersonales->supervisa_cultivo ?? 'N/A' }}</p>
                        <p><strong>Realiza cultivo:</strong> {{ $visita->datosPersonales->realiza_cultivo ?? 'N/A' }}</p>
                        <p><strong>Años en palmicultura:</strong> {{ $visita->datosPersonales->anios_palmicultura ?? 'N/A' }}</p>
                        <p><strong>Internet:</strong> {{ $visita->datosPersonales->internet ?? 'N/A' }}</p>
                        <p><strong>Tipo de persona:</strong> {{ $visita->datosPersonales->tipo_persona ?? 'N/A' }}</p>
                        <p><strong>Red social:</strong> {{ $visita->datosPersonales->red_social ?? 'N/A' }}</p>
                        <p><strong>Régimen de salud:</strong> {{ $visita->datosPersonales->regimen_salud ?? 'N/A' }}</p>
                    @else
                        <p>No hay datos registrados.</p>
                    @endif
                </div>

            </div>
        </div>

        <!-- Miembros del Hogar -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingMiembros">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMiembros">
                    👨‍👩‍👧‍👦 Miembros del Hogar
                </button>
            </h2>
            <div id="collapseMiembros" class="accordion-collapse collapse" data-bs-parent="#accordionVisita">
                <div class="accordion-body">
                    @if($visita->miembros->count())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-success">
                                    <tr>
                                        <th>👤 Nombre</th>
                                        <th>🪪 Documento</th>
                                        <th>⚧ Sexo</th>
                                        <th>👨‍👩‍👧 Parentesco</th>
                                        <th>🏡 Reside en el Predio</th>
                                        <th>📖 Sabe Leer</th>
                                        <th>🎓 Nivel de Estudio</th>
                                        <th>🌱 Participa en Labores</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visita->miembros as $miembro)
                                        <tr>
                                            <td>{{ $miembro->nombre }}</td>
                                            <td>{{ $miembro->documento }}</td>
                                            <td>{{ $miembro->sexo }}</td>
                                            <td>{{ $miembro->parentezco }}</td>
                                            <td>{{ $miembro->reside_predio == 'si' ? '✅ Sí' : '❌ No' }}</td>
                                            <td>{{ $miembro->sabe_leer == 'si' ? '✅ Sí' : '❌ No' }}</td>
                                            <td>{{ $miembro->nivel_estudio }}</td>
                                            <td>{{ $miembro->participa_labores == 'si' ? '✅ Sí' : '❌ No' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No hay miembros registrados.</p>
                    @endif
                </div>
            </div>

        </div>

        <!-- Predio -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPredio">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePredio">
                    🏡 Datos del Predio
                </button>
            </h2>
           <div id="collapsePredio" class="accordion-collapse collapse" data-bs-parent="#accordionVisita">
    <div class="accordion-body">
        @if($visita->predio)
            <div class="row">
                <div class="col-md-6 mb-2">
                    <p><strong>🏡 Nombre de la Finca:</strong> {{ $visita->predio->nombre_finca }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <p><strong>📍 Municipio:</strong> {{ $visita->predio->municipio }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <p><strong>🏘️ Vereda:</strong> {{ $visita->predio->vereda }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <p><strong>📑 Forma de Tenencia:</strong> {{ $visita->predio->forma_tenencia }}</p>
                </div>
                <div class="col-md-6 mb-2">
                    <p><strong>🧾 Registrado en ICA:</strong> 
                        {{ $visita->predio->registrado_ica == 'si' ? '✅ Sí' : '❌ No' }}
                    </p>
                </div>
                <div class="col-md-6 mb-2">
                    <p><strong>🏠 Vive en el Predio:</strong> 
                        {{ $visita->predio->vive_predio == 'si' ? '✅ Sí' : '❌ No' }}
                    </p>
                </div>
                <div class="col-md-12 mb-2">
                    <p><strong>🛣️ Infraestructura Vial:</strong> 
                        @if(is_array($visita->predio->infraestructura_vial))
                            {{ implode(', ', $visita->predio->infraestructura_vial) }}
                        @else
                            {{ $visita->predio->infraestructura_vial }}
                        @endif
                    </p>
                </div>
                <div class="col-md-12 mb-2">
                    <p><strong>🏗️ Infraestructura del Predio:</strong> {{ $visita->predio->infraestructura_predio }}</p>
                </div>
            </div>
        @else
            <p>No hay datos registrados.</p>
        @endif
    </div>
</div>

        </div>

        <!-- Fuerza Laboral -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFuerzaLaboral">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFuerzaLaboral">
                    🧑‍🌾 Fuerza Laboral
                </button>
            </h2>
            <div id="collapseFuerzaLaboral" class="accordion-collapse collapse" data-bs-parent="#accordionVisita">
                <div class="accordion-body">
                    @if($visita->fuerzaLaboral && $visita->fuerzaLaboral->count())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-success">
                                    <tr>
                                        <th>#</th>
                                        <th>Forma de Contratación</th>
                                        <th>Trabajadores</th>
                                        <th>Hombres</th>
                                        <th>Mujeres</th>
                                        <th>Contrato Formal</th>
                                        <th>Seguridad Social</th>
                                        <th>Tipo de Contrato</th>
                                        <th>Contrato Firmado</th>
                                        <th>SG-SST</th>
                                        <th>Exámenes Médicos</th>
                                        <th>Migrantes</th>
                                        <th>Comprobantes Pago</th>
                                        <th>Dotación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($visita->fuerzaLaboral as $index => $fuerza)
                                        <tr>
                                            <td>{{ (int)$index + 1 }}</td>

                                            <td>
                                                @if(!empty($fuerza->forma_contratacion))
                                                    @if(is_array($fuerza->forma_contratacion))
                                                        {{ implode(', ', $fuerza->forma_contratacion) }}
                                                    @else
                                                        {{ $fuerza->forma_contratacion }}
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $fuerza->num_trabajadores ?? '-' }}</td>
                                            <td>{{ $fuerza->num_hombres ?? '-' }}</td>
                                            <td>{{ $fuerza->num_mujeres ?? '-' }}</td>
                                            <td>{{ $fuerza->contrato_formal === 'si' ? '✅ Sí' : '❌ No' }}</td>
                                            <td>{{ $fuerza->seguridad_social === 'si' ? '✅ Sí' : '❌ No' }}</td>
                                            <td>{{ $fuerza->tipo_contrato ?? '-' }}</td>
                                            <td>{{ $fuerza->contrato_firmado === 'si' ? '✅ Sí' : '❌ No' }}</td>
                                            <td>{{ $fuerza->sg_sst === 'si' ? '✅ Sí' : '❌ No' }}</td>
                                            <td>{{ $fuerza->examenes_medicos === 'si' ? '✅ Sí' : '❌ No' }}</td>
                                            <td>{{ $fuerza->trabajadores_migrantes === 'si' ? '✅ Sí' : '❌ No' }}</td>
                                            <td>{{ $fuerza->comprobantes_pago === 'si' ? '✅ Sí' : '❌ No' }}</td>
                                            <td>{{ $fuerza->dotacion === 'si' ? '✅ Sí' : '❌ No' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No hay registros de fuerza laboral.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Organización Social -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOrganizacionSocial">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrganizacionSocial">
                    👥 Organización Social
                </button>
            </h2>
            <div id="collapseOrganizacionSocial" class="accordion-collapse collapse" data-bs-parent="#accordionVisita">
    <div class="accordion-body">
        @if($visita->organizacionSocial)
            <p><strong>Pertenece a JAC:</strong> {{ $visita->organizacionSocial->pertenece_jac ? 'Sí' : 'No' }}</p>
            <p><strong>Pertenece a Asociación:</strong> {{ $visita->organizacionSocial->pertenece_asociacion ? 'Sí' : 'No' }}</p>
            <p><strong>Nombre de Asociación:</strong> {{ $visita->organizacionSocial->nombre_asociacion ?? 'N/A' }}</p>
        @else
            <p>No hay datos de organización social.</p>
        @endif
    </div>
</div>

        </div>
         <!-- Cierre de Visita Social -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCierre">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCierre">
                    ✅ Cierre de Visita Social
                </button>
            </h2>
            <div id="collapseCierre" class="accordion-collapse collapse" data-bs-parent="#accordionVisita">
                <div class="accordion-body">
                    @if($visita->cierreVisitaSocial)
                        <p><strong>📅 Fecha de Cierre:</strong> {{ $visita->cierreVisitaSocial->fecha_cierre }}</p>
                        <p><strong>📌 Estado:</strong> 
                            @if($visita->cierreVisitaSocial->estado_visita == 'completado')
                                ✅ Completado
                            @elseif($visita->cierreVisitaSocial->estado_visita == 'pendiente')
                                ⏳ Pendiente
                            @else
                                ❌ Cancelado
                            @endif
                        </p>
                        <p><strong>📝 Observaciones Finales:</strong> {{ $visita->cierreVisitaSocial->observaciones_finales ?? 'N/A' }}</p>
                        <p><strong>💡 Recomendaciones:</strong> {{ $visita->cierreVisitaSocial->recomendaciones ?? 'N/A' }}</p>

                        <div class="row mt-3">
                            {{-- Firmas --}}
                            @if ($visita->cierreVisitaSocial->firma_responsable)
                                <div class="mt-3">
                                    <strong>📄 Firma Responsable de Visita:</strong><br>
                                    <img src="{{ $visita->cierreVisitaSocial->firma_responsable }}" alt="Firma Responsable" class="firma-img">
                                </div>
                            @endif
                            @if ($visita->cierreVisitaSocial->firma_recibe)
                                <div class="mt-3">
                                    <strong>📄 Firma de quien recibió la visita:</strong><br>
                                    <img src="{{ $visita->cierreVisitaSocial->firma_recibe }}" alt="Firma Recibe" class="firma-img">
                                </div>
                            @endif
                            @if ($visita->cierreVisitaSocial->firma_testigo)
                                <div class="mt-3">
                                    <strong>📄 Firma del testigo:</strong><br>
                                    <img src="{{ $visita->cierreVisitaSocial->firma_testigo }}" alt="Firma Testigo" class="firma-img">
                                </div>
                            @endif

   
</div>


                   {{-- Imágenes finales --}}
                                @php
                                    // Manejo seguro del campo 'imagenes'
                                    $imagenes = [];
                                    if ($visita->cierreVisitaSocial && $visita->cierreVisitaSocial->imagenes) {
                                        $imagenes = is_array($visita->cierreVisitaSocial->imagenes) 
                                            ? $visita->cierreVisitaSocial->imagenes 
                                            : json_decode($visita->cierreVisitaSocial->imagenes, true) ?? [];
                                    }
                                @endphp
                                
                                {{-- Verificamos si hay imágenes --}}
                                @if (count($imagenes) > 0)
                                    <div class="mt-4">
                                        <strong>🖼️ Tomas destacadas durante la visita:</strong><br>
                                        <div class="row">
                                            @foreach ($imagenes as $img)
                                                <div class="col-md-4 col-6 mb-3">
                                                    <img src="{{ $img }}" class="img-fluid rounded shadow img-thumb">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif


                    @else
                        <p>No se ha registrado el cierre de esta visita.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
      <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
        <button onclick="descargarPDFSocial()" class="btn btn-danger">
            📥 Exportar PDF
        </button>
        <button onclick="descargarExcelConSweet()" class="btn btn-success">
            📊 Exportar a Excel
        </button>
        <a href="{{ route('visitas_social.indexSocial') }}" class="btn btn-secondary">⬅️ Volver</a>
    </div>
</div>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
       

    async function descargarPDFSocial() {
         Swal.fire({
            title: 'Generando PDF...',
            text: 'Esto puede tardar unos segundos',
            imageUrl: '{{ asset('images/loader.gif') }}',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        const url = "{{ route('visitas_social.exportar.pdf', $visita->id) }}";

        window.open(url, '_blank');

        setTimeout(() => {
            Swal.close();
        }, 5000);
    }
</script>
@endsection

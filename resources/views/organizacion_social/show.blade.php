@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg border-0 rounded-4 mx-auto p-4" style="max-width: 900px; background-color: #e8d5dce0;">
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

        {{-- ✅ Acordeón de Datos Personales --}}
        @if($datosPersonales)
        <div class="accordion-container mb-4">
            <div class="accordion-card">
                <div class="accordion-header" onclick="toggleAccordion(this, 'personal')">
                    <h3 class="accordion-title">
                        👤 Datos Personales del Productor
                        <span class="accordion-icon" id="icon-personal">▼</span>
                    </h3>
                </div>
                <div class="accordion-content" id="content-personal">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>📞 Teléfono:</strong> {{ $datosPersonales->telefono ?? 'No especificado' }}</p>
                            <p><strong>🚻 Sexo:</strong> {{ $datosPersonales->sexo ?? 'No especificado' }}</p>
                            <p><strong>📚 Nivel de Estudio:</strong> {{ $datosPersonales->nivel_estudio ?? 'No especificado' }}</p>
                            <p><strong>🎂 Fecha Nacimiento:</strong> {{ $datosPersonales->fecha_nacimiento ? \Carbon\Carbon::parse($datosPersonales->fecha_nacimiento)->format('d/m/Y') : 'No especificado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>🌴 Años en Palmicultura:</strong> {{ $datosPersonales->anios_palmicultura ?? '0' }} años</p>
                            <p><strong>🏥 Régimen Salud:</strong> {{ $datosPersonales->regimen_salud ?? 'No especificado' }}</p>
                            <p><strong>🏠 Reside en Predio:</strong> {{ $datosPersonales->reside_predio ? 'Sí' : 'No' }}</p>
                            <p><strong>💻 Internet:</strong> {{ $datosPersonales->internet ?? 'No especificado' }}</p>
                        </div>
                    </div>
                    @if($datosPersonales->rnp || $datosPersonales->fedepalma)
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>📄 Registros:</strong></p>
                            <ul>
                                @if($datosPersonales->rnp)
                                    <li>RNP: {{ $datosPersonales->rnp }}</li>
                                @endif
                                @if($datosPersonales->fedepalma)
                                    <li>Fedepalma: {{ $datosPersonales->fedepalma }}</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- ✅ Acordeón de Miembros del Hogar --}}
        @if($miembros && $miembros->count() > 0)
        <div class="accordion-container mb-4">
            <div class="accordion-card">
                <div class="accordion-header" onclick="toggleAccordion(this, 'miembros')">
                    <h3 class="accordion-title">
                        👨‍👩‍👧‍👦 Miembros del Hogar ({{ $miembros->count() }})
                        <span class="accordion-icon" id="icon-miembros">▼</span>
                    </h3>
                </div>
                <div class="accordion-content" id="content-miembros">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Documento</th>
                                    <th>Sexo</th>
                                    <th>Parentezco</th>
                                    <th>Reside</th>
                                    <th>Estudio</th>
                                    <th>Participa Labores</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($miembros as $miembro)
                                <tr>
                                    <td>{{ $miembro->nombre }}</td>
                                    <td>{{ $miembro->documento ?? 'N/A' }}</td>
                                    <td>{{ $miembro->sexo ?? 'N/A' }}</td>
                                    <td>{{ $miembro->parentezco ?? 'N/A' }}</td>
                                    <td>{{ $miembro->reside_predio ? 'Sí' : 'No' }}</td>
                                    <td>{{ $miembro->nivel_estudio ?? 'N/A' }}</td>
                                    <td>{{ $miembro->participa_labores ? 'Sí' : 'No' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ✅ Acordeón de Datos del Predio --}}
        @if($datosPredio && $datosPredio->count() > 0)
        <div class="accordion-container mb-4">
            <div class="accordion-card">
                <div class="accordion-header" onclick="toggleAccordion(this, 'predio')">
                    <h3 class="accordion-title">
                        🏡 Datos del Predio ({{ $datosPredio->count() }} plantaciones)
                        <span class="accordion-icon" id="icon-predio">▼</span>
                    </h3>
                </div>
                <div class="accordion-content" id="content-predio">
                    @foreach($datosPredio as $dato)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>🌱 Plantación:</strong> {{ $dato->plantacion->nombre ?? 'N/A' }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>🏡 Nombre finca:</strong> {{ $dato->nombre_finca }}</p>
                                    <p><strong>📜 Forma Tenencia:</strong> {{ $dato->forma_tenencia }}</p>
                                    <p><strong>🌍 Municipio:</strong> {{ $dato->municipio }}</p>
                                    <p><strong>🏘️ Vereda:</strong> {{ $dato->vereda }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>📋 Registro ICA:</strong> {{ $dato->registrado_ica ? 'Sí' : 'No' }}</p>
                                    <p><strong>👤 Vive en predio:</strong> {{ $dato->vive_predio ? 'Sí' : 'No' }}</p>
                                    <p><strong>🏠 Infraestructura:</strong> {{ $dato->infraestructura_predio }}</p>
                                </div>
                            </div>
                            
                            {{-- Infraestructura vial y servicios --}}
                            @php
                                $infraestructuraVial = [];
                                if (!empty($dato->infraestructura_vial)) {
                                    $decodedVial = json_decode($dato->infraestructura_vial, true);
                                    $infraestructuraVial = is_array($decodedVial) ? $decodedVial : [$dato->infraestructura_vial];
                                }

                                $serviciosPublicos = [];
                                if (!empty($dato->servicios_publicos)) {
                                    $decodedServicios = json_decode($dato->servicios_publicos, true);
                                    $serviciosPublicos = is_array($decodedServicios) ? $decodedServicios : [$dato->servicios_publicos];
                                }
                            @endphp

                            @if(!empty($infraestructuraVial) || !empty($serviciosPublicos))
                            <div class="row mt-3">
                                @if(!empty($infraestructuraVial))
                                <div class="col-md-6">
                                    <p><strong>🛣️ Infraestructura Vial:</strong></p>
                                    <ul>
                                        @foreach($infraestructuraVial as $vial)
                                            @if(!empty($vial))
                                                <li>{{ $vial }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if(!empty($serviciosPublicos))
                                <div class="col-md-6">
                                    <p><strong>⚡ Servicios Públicos:</strong></p>
                                    <ul>
                                        @foreach($serviciosPublicos as $servicio)
                                            @if(!empty($servicio))
                                                <li>{{ $servicio }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ✅ Acordeón de Fuerza Laboral --}}
        @if($fuerzaLaboral && $fuerzaLaboral->count() > 0)
        <div class="accordion-container mb-4">
            <div class="accordion-card">
                <div class="accordion-header" onclick="toggleAccordion(this, 'fuerza')">
                    <h3 class="accordion-title">
                        🧑‍🌾 Fuerza Laboral ({{ $fuerzaLaboral->count() }} registros)
                        <span class="accordion-icon" id="icon-fuerza">▼</span>
                    </h3>
                </div>
                <div class="accordion-content" id="content-fuerza">
                    @foreach($fuerzaLaboral as $fuerza)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Registro #{{ $fuerza->id }}</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>👥 Total Trabajadores:</strong> {{ $fuerza->num_trabajadores }}</p>
                                    <p><strong>👨 Hombres:</strong> {{ $fuerza->num_hombres }}</p>
                                    <p><strong>👩 Mujeres:</strong> {{ $fuerza->num_mujeres }}</p>
                                    <p><strong>📝 Contrato formal:</strong> {{ $fuerza->contrato_formal ? 'Sí' : 'No' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>🏥 Seguridad social:</strong> {{ $fuerza->seguridad_social ? 'Sí' : 'No' }}</p>
                                    <p><strong>🛡️ SG-SST:</strong> {{ $fuerza->sg_sst ? 'Sí' : 'No' }}</p>
                                    <p><strong>👕 Dotación:</strong> {{ $fuerza->dotacion ? 'Sí' : 'No' }}</p>
                                </div>
                            </div>
                            
                            {{-- Forma de contratación --}}
                            @php
                                $formaContratacion = [];
                                if (!empty($fuerza->forma_contratacion)) {
                                    $decodedContratacion = json_decode($fuerza->forma_contratacion, true);
                                    $formaContratacion = is_array($decodedContratacion) ? $decodedContratacion : [$fuerza->forma_contratacion];
                                }
                            @endphp

                            @if(!empty($formaContratacion))
                            <div class="row mt-2">
                                <div class="col-12">
                                    <p class="fw-bold mb-1">📋 Forma de contratación:</p>
                                    <ul class="list-unstyled">
                                        @foreach($formaContratacion as $contratacion)
                                            @if(!empty($contratacion))
                                                <li>• {{ $contratacion }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <h3 class="text-success fw-bold mb-4">👁 Detalle Organización Social</h3>

        <div class="card p-4 shadow-sm border-0 rounded-3 bg-light">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>🏘️ Pertenece a JAC:</strong> 
                            {{ $organizacion->pertenece_jac ? 'Sí' : 'No' }}
                        </li>
                        <li class="list-group-item">
                            <strong>🤝 Pertenece a asociación:</strong> 
                            {{ $organizacion->pertenece_asociacion ? 'Sí' : 'No' }}
                        </li>
                        @if($organizacion->pertenece_asociacion && $organizacion->nombre_asociacion)
                        <li class="list-group-item">
                            <strong>🏢 Nombre asociación:</strong> 
                            {{ $organizacion->nombre_asociacion }}
                        </li>
                        @endif
                        <li class="list-group-item">
                            <strong>👥 Participa en otras organizaciones:</strong> 
                            {{ $organizacion->participa_otras_organizaciones ? 'Sí' : 'No' }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group list-group-flush">
                        @if($organizacion->cargos_directivos)
                        <li class="list-group-item">
                            <strong>📋 Cargos directivos:</strong> 
                            {{ $organizacion->cargos_directivos }}
                        </li>
                        @endif
                        @if($organizacion->frecuencia_participacion)
                        <li class="list-group-item">
                            <strong>📅 Frecuencia participación:</strong> 
                            {{ $organizacion->frecuencia_participacion }}
                        </li>
                        @endif
                        @if($organizacion->descripcion_cargos)
                        <li class="list-group-item">
                            <strong>📝 Descripción de cargos:</strong> 
                            {{ $organizacion->descripcion_cargos }}
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Beneficios de participación --}}
            @php
                $beneficios = [];
                if (!empty($organizacion->beneficios_participacion)) {
                    $decodedBeneficios = json_decode($organizacion->beneficios_participacion, true);
                    $beneficios = is_array($decodedBeneficios) ? $decodedBeneficios : [$organizacion->beneficios_participacion];
                }
            @endphp

            @if(!empty($beneficios))
            <div class="row mt-3">
                <div class="col-12">
                    <p class="fw-bold">🎁 Beneficios de participación:</p>
                    <ul class="list-unstyled">
                        @foreach($beneficios as $beneficio)
                            @if(!empty($beneficio))
                                <li>• {{ $beneficio }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if($organizacion->observaciones)
            <div class="row mt-3">
                <div class="col-12">
                    <p class="fw-bold">📝 Observaciones:</p>
                    <p>{{ $organizacion->observaciones }}</p>
                </div>
            </div>
            @endif
        </div>

        <a href="{{ route('organizacion_social.index', $visita->id) }}" class="btn btn-secondary mt-3">⬅ Volver</a>
    </div>
</div>

<style>
    /* ✅ Estilos para los acordeones */
    .accordion-container {
        margin-bottom: 15px;
    }

    .accordion-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        background: #f8f9fa;
    }

    .accordion-header {
        background: #198754;
        color: white;
        padding: 12px 16px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .accordion-header:hover {
        background: #146c43;
    }

    .accordion-title {
        margin: 0;
        font-size: 1.1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .accordion-icon {
        transition: transform 0.3s ease;
    }

    .accordion-content {
        padding: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        background: white;
    }

    .accordion-content.active {
        padding: 16px;
        max-height: 500px;
    }

    .accordion-icon.rotated {
        transform: rotate(180deg);
    }

    /* Tabla responsive */
    .table-responsive {
        max-height: 300px;
        overflow-y: auto;
    }

    .table-sm th, .table-sm td {
        padding: 8px 12px;
        font-size: 0.9rem;
    }

    @media (max-width: 968px) {
        .container {
            margin-left: -70px;
            width: 125%;
        }
        .accordion-content.active {
            max-height: 600px;
        }
        .table-responsive {
            max-height: 250px;
        }
    }
</style>

<script>
// ✅ Función para el acordeón - INICIAN CERRADOS
function toggleAccordion(header, type) {
    const content = document.getElementById(`content-${type}`);
    const icon = document.getElementById(`icon-${type}`);
    
    content.classList.toggle('active');
    icon.classList.toggle('rotated');
}

// ✅ NO hay código que abra automáticamente - permanecen CERRADOS
</script>
@endsection
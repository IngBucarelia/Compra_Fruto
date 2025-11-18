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
                        <option value="cierre_visita">✅ Cierre de Visita</option>
                    @endif
                </select>
                <button type="submit" class="btn btn-success">Ir</button>
            </div>
        </form><br><br>

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
                            <p><strong>🏠 Reside en Predio:</strong> {{ $datosPersonales->reside_predio ? 'Sí' : 'No' }}</p>
                            <p><strong>💻 Internet:</strong> {{ $datosPersonales->internet ?? 'No especificado' }}</p>
                            <p><strong>💊 Régimen de Salud:</strong> {{ $datosPersonales->regimen_salud ?? 'No especificado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>🌴 Años en Palmicultura:</strong> {{ $datosPersonales->anios_palmicultura ?? 'No especificado' }}</p>
                            <p><strong>🧾 Grupo Poblacional:</strong> {{ $datosPersonales->grupo_poblacional ?? 'No especificado' }}</p>
                            <p><strong>🧍 Tipo de Persona:</strong> {{ $datosPersonales->tipo_persona ?? 'No especificado' }}</p>
                            <p><strong>📡 Red Social:</strong> {{ $datosPersonales->red_social ?? 'No especificado' }}</p>
                            <p><strong>🧾 Oferta Mercantil Firmada:</strong> {{ $datosPersonales->oferta_mercantil ?? 'No especificado' }}</p>
                            @if(($datosPersonales->oferta_mercantil ?? '') === 'SI')
                                <p><strong>📅 Hace cuánto:</strong> {{ $datosPersonales->hace_cuanto ?? 'No especificado' }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Registros adicionales --}}
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>📄 Registros:</strong></p>
                            <ul>
                                <li>RNP: {{ $datosPersonales->rnp ?? 'No especificado' }}</li>
                                <li>Número RNP: {{ $datosPersonales->numero_rnp ?? 'No especificado' }}</li>
                                <li>Fedepalma: {{ $datosPersonales->fedepalma ?? 'No especificado' }}</li>
                            </ul>
                        </div>
                    </div>
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
                            <thead class="table-success">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Documento</th>
                                    <th>Sexo</th>
                                    <th>Parentezco</th>
                                    <th>Reside en Predio</th>
                                    <th>Sabe Leer</th>
                                    <th>Nivel Estudio</th>
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
                                    <td>{{ $miembro->sabe_leer ? 'Sí' : 'No' }}</td>
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
                        🏡 Datos del Predio ({{ $datosPredio->count() }})
                        <span class="accordion-icon" id="icon-predio">▼</span>
                    </h3>
                </div>
                <div class="accordion-content" id="content-predio">
                    @foreach($datosPredio as $dato)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>🌱 Plantación:</strong> {{ optional($dato->plantacion)->nombre ?? 'N/A' }}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>🏠 Nombre Finca:</strong> {{ $dato->nombre_finca ?? 'No especificado' }}</p>

                                    {{-- Forma de tenencia (array / json / string) --}}
                                    @php
                                        $formaTenencia = $dato->forma_tenencia;
                                        $formaTenenciaArr = [];
                                        if(is_array($formaTenencia)) {
                                            $formaTenenciaArr = $formaTenencia;
                                        } elseif(is_string($formaTenencia) && strlen($formaTenencia)) {
                                            $decoded = json_decode($formaTenencia, true);
                                            if(is_array($decoded)) {
                                                $formaTenenciaArr = $decoded;
                                            } else {
                                                // fallback: split comma-separated string
                                                $formaTenenciaArr = array_filter(array_map('trim', explode(',', $formaTenencia)));
                                            }
                                        }
                                    @endphp

                                    <p><strong>📜 Forma de Tenencia:</strong>
                                        @if(!empty($formaTenenciaArr))
                                            {{ implode(', ', $formaTenenciaArr) }}
                                        @else
                                            {{ $dato->forma_tenencia ?? 'No especificado' }}
                                        @endif
                                    </p>

                                    <p><strong>🌍 Municipio:</strong> {{ $dato->municipio ?? 'No especificado' }}</p>
                                    <p><strong>🏘️ Vereda:</strong> {{ $dato->vereda ?? 'No especificado' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>📋 Registro ICA:</strong> {{ $dato->registrado_ica ? 'Sí' : 'No' }}</p>
                                    <p><strong>👤 Vive en Predio:</strong> {{ $dato->vive_predio ? 'Sí' : 'No' }}</p>
                                    <p><strong>🏗️ Infraestructura del Predio:</strong> {{ $dato->infraestructura_predio ?? 'No especificado' }}</p>
                                </div>
                            </div>

                            {{-- Infraestructura Vial --}}
                            @php
                                $infraestructura = $dato->infraestructura_vial;
                                $infraestructuraArr = [];
                                if(is_array($infraestructura)) {
                                    $infraestructuraArr = $infraestructura;
                                } elseif(is_string($infraestructura) && strlen($infraestructura)) {
                                    $decoded = json_decode($infraestructura, true);
                                    if(is_array($decoded)) {
                                        $infraestructuraArr = $decoded;
                                    } else {
                                        $infraestructuraArr = array_filter(array_map('trim', explode(',', $infraestructura)));
                                    }
                                }
                            @endphp

                            @if(!empty($infraestructuraArr))
                                <div class="mt-3">
                                    <p class="mb-1"><strong>🛣️ Infraestructura Vial:</strong></p>
                                    @foreach($infraestructuraArr as $vial)
                                        @if(!empty($vial))
                                            <span class="badge bg-success me-1 mb-1">{{ $vial }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            {{-- Servicios Públicos --}}
                            @php
                                $servicios = $dato->servicios_publicos ?? null;
                                $serviciosArr = [];
                                if(is_array($servicios)) {
                                    $serviciosArr = $servicios;
                                } elseif(is_string($servicios) && strlen($servicios)) {
                                    $decodedS = json_decode($servicios, true);
                                    if(is_array($decodedS)) {
                                        $serviciosArr = $decodedS;
                                    } else {
                                        $serviciosArr = array_filter(array_map('trim', explode(',', $servicios)));
                                    }
                                }
                            @endphp

                            @if(!empty($serviciosArr))
                                <div class="mt-2">
                                    <p class="mb-1"><strong>⚡ Servicios Públicos:</strong></p>
                                    <ul>
                                        @foreach($serviciosArr as $serv)
                                            @if(!empty($serv))
                                                <li>{{ $serv }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
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
                                    <p><strong>👥 Total Trabajadores:</strong> {{ $fuerza->num_trabajadores ?? 0 }}</p>
                                    <p><strong>👨 Hombres:</strong> {{ $fuerza->num_hombres ?? 0 }}</p>
                                    <p><strong>👩 Mujeres:</strong> {{ $fuerza->num_mujeres ?? 0 }}</p>
                                    <p><strong>📝 Contrato formal:</strong> {{ $fuerza->contrato_formal ?? 'No especificado' }}</p>
                                    <p><strong>🧾 Tipo Contrato:</strong> {{ $fuerza->tipo_contrato ?? 'No especificado' }}</p>
                                    <p><strong>📄 Contrato Firmado:</strong> {{ $fuerza->contrato_firmado ?? 'No especificado' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>🏥 Seguridad Social:</strong> {{ $fuerza->seguridad_social ?? 'No especificado' }}</p>
                                    <p><strong>🛡️ SG-SST:</strong> {{ $fuerza->sg_sst ?? 'No especificado' }}</p>
                                    <p><strong>⚕️ Exámenes Médicos:</strong> {{ $fuerza->examenes_medicos ?? 'No especificado' }}</p>
                                    <p><strong>🧍 Trabajadores Migrantes:</strong> {{ $fuerza->trabajadores_migrantes ?? 'No especificado' }}</p>
                                    <p><strong>📑 Comprobantes Pago:</strong> {{ $fuerza->comprobantes_pago ?? 'No especificado' }}</p>
                                    <p><strong>👕 Dotación:</strong> {{ $fuerza->dotacion ?? 'No especificado' }}</p>
                                </div>
                            </div>

                            {{-- Forma de contratación (normalize) --}}
                            @php
                                $forma = $fuerza->forma_contratacion ?? null;
                                $formaArr = [];
                                if(is_array($forma)) {
                                    $formaArr = $forma;
                                } elseif(is_string($forma) && strlen($forma)) {
                                    $decodedF = json_decode($forma, true);
                                    if(is_array($decodedF)) {
                                        $formaArr = $decodedF;
                                    } else {
                                        $formaArr = array_filter(array_map('trim', explode(',', $forma)));
                                    }
                                }
                            @endphp

                            @if(!empty($formaArr))
                                <div class="mt-3">
                                    <p class="mb-1 fw-bold">📋 Forma de Contratación:</p>
                                    <ul>
                                        @foreach($formaArr as $item)
                                            @if(!empty($item))
                                                <li>{{ $item }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
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

        {{-- ✅ VERIFICAR SI $organizacion EXISTE --}}
        @if(!$organizaciones)
            <div class="alert alert-warning">
                <h4>⚠️ Registro no encontrado</h4>
                <p>No se encontró el registro de organización social para esta visita.</p>
                <a href="{{ route('organizacion_social.create', $visita->id) }}" class="btn btn-success">
                    ➕ Crear Registro de Organización Social
                </a>
                <a href="{{ route('organizacion_social.index', $visita->id) }}" class="btn btn-secondary">
                    ← Volver a la lista
                </a>
            </div>
        @else
            <div class="card p-4 shadow-sm border-0 rounded-3 bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>🏘️ Pertenece a JAC:</strong> 
                                {{ $organizaciones->pertenece_jac ? 'Sí' : 'No' }}
                            </li>
                            <li class="list-group-item">
                                <strong>🤝 Pertenece a asociación:</strong> 
                                {{ $organizaciones->pertenece_asociacion ? 'Sí' : 'No' }}
                            </li>
                            @if($organizaciones->pertenece_asociacion && $organizaciones->nombre_asociacion)
                            <li class="list-group-item">
                                <strong>🏢 Nombre asociación:</strong> 
                                {{ $organizaciones->nombre_asociacion }}
                            </li>
                            @endif
                            <li class="list-group-item">
                                <strong>👥 Participa en otras organizaciones:</strong> 
                                {{ $organizaciones->participa_otras_organizaciones ? 'Sí' : 'No' }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            @if($organizaciones->cargos_directivos)
                            <li class="list-group-item">
                                <strong>📋 Cargos directivos:</strong> 
                                {{ $organizaciones->cargos_directivos }}
                            </li>
                            @endif
                            @if($organizaciones->frecuencia_participacion)
                            <li class="list-group-item">
                                <strong>📅 Frecuencia participación:</strong> 
                                {{ $organizaciones->frecuencia_participacion }}
                            </li>
                            @endif
                            @if($organizaciones->descripcion_cargos)
                            <li class="list-group-item">
                                <strong>📝 Descripción de cargos:</strong> 
                                {{ $organizaciones->descripcion_cargos }}
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

                {{-- Beneficios de participación --}}
                @php
                    $beneficios = [];
                    if (!empty($organizaciones->beneficios_participacion)) {
                        // Si ya es array, usarlo directamente
                        if (is_array($organizaciones->beneficios_participacion)) {
                            $beneficios = $organizaciones->beneficios_participacion;
                        } 
                        // Si es string, intentar decodificar JSON
                        elseif (is_string($organizaciones->beneficios_participacion)) {
                            $decodedBeneficios = json_decode($organizaciones->beneficios_participacion, true);
                            $beneficios = is_array($decodedBeneficios) ? $decodedBeneficios : [$organizaciones->beneficios_participacion];
                        }
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

                @if($organizaciones->observaciones)
                <div class="row mt-3">
                    <div class="col-12">
                        <p class="fw-bold">📝 Observaciones:</p>
                        <p>{{ $organizaciones->observaciones }}</p>
                    </div>
                </div>
                @endif
            </div>
        @endif

        <div class="mt-3">
            @if($organizaciones)
                <a href="{{ route('organizacion_social.index', $visita->id) }}" class="btn btn-secondary">⬅ Volver</a>
                <a href="{{ route('organizacion_social.edit', [$visita->id, $organizaciones->id]) }}" class="btn btn-warning">✏️ Editar</a>
            @else
                <a href="{{ route('organizacion_social.index', $visita->id) }}" class="btn btn-secondary">⬅ Volver a la lista</a>
            @endif
        </div><br><br>
        <div class="mb-4" style="margin-left: 50px">
            <a href="{{ route('cierre-visitas-social.create', $visita->id) }}" class="btn btn-primary">
                ✅ Ir A Cierre de Visita
            </a>
           
        </div>
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
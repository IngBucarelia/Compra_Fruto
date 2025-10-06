@extends('layouts.app')

@section('content')
<div class="container">
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

    {{-- ✅ TÍTULO Y BOTÓN PARA AGREGAR --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-info fw-bold mb-0">🧑‍🌾 Fuerza Laboral</h3>
        <a href="{{ route('fuerza_laboral.create', $visita->id) }}" class="btn btn-success">
            ➕ Agregar Fuerza Laboral
        </a>
    </div>

    {{-- ✅ TABLA DE FUERZA LABORAL --}}
    @if($fuerzas->count() > 0)
    <div class="card p-4 shadow-sm border-0 rounded-3 bg-light">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Total Trabajadores</th>
                        <th>Hombres</th>
                        <th>Mujeres</th>
                        <th>Contrato Formal</th>
                        <th>Seguridad Social</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fuerzas as $fuerza)
                    <tr>
                        <td>{{ $fuerza->id }}</td>
                        <td>{{ $fuerza->num_trabajadores }}</td>
                        <td>{{ $fuerza->num_hombres }}</td>
                        <td>{{ $fuerza->num_mujeres }}</td>
                        <td>{{ $fuerza->contrato_formal ? 'Sí' : 'No' }}</td>
                        <td>{{ $fuerza->seguridad_social ? 'Sí' : 'No' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('fuerza_laboral.show', [$visita->id, $fuerza->id]) }}" 
                                   class="btn btn-info btn-sm">👁️ Ver</a>
                                <a href="{{ route('fuerza_laboral.edit', [$visita->id, $fuerza->id]) }}" 
                                   class="btn btn-warning btn-sm">✏️ Editar</a>
                                <form action="{{ route('fuerza_laboral.destroy', [$visita->id, $fuerza->id]) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('¿Estás seguro de eliminar este registro?')">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="card p-4 text-center">
        <h4 class="text-muted">📭 No hay registros de fuerza laboral</h4>
        <p class="text-muted">Agrega el primer registro de fuerza laboral para esta visita.</p>
        <a href="{{ route('fuerza_laboral.create', $visita->id) }}" class="btn btn-success">
            ➕ Agregar Fuerza Laboral
        </a>
    </div>
    @endif
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
        max-height: 400px;
        overflow-y: auto;
    }

    .table-sm th, .table-sm td {
        padding: 8px 12px;
        font-size: 0.9rem;
    }

    /* Estilos existentes */
    @media (max-width: 968px) {
        .container.offline-form-container {
            background-color: rgba(129, 165, 114, 0.929);
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

        .title {
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

        .card {
            width: 100%;
        }

        /* Responsive para acordeones en móvil */
        .accordion-content.active {
            max-height: 600px;
        }
        
        .table-responsive {
            max-height: 300px;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .btn-group .btn {
            margin: 2px 0;
        }
    }
</style>

<script>
// ✅ Función para el acordeón
function toggleAccordion(header, type) {
    const content = document.getElementById(`content-${type}`);
    const icon = document.getElementById(`icon-${type}`);
    
    content.classList.toggle('active');
    icon.classList.toggle('rotated');
}

document.addEventListener('DOMContentLoaded', function () {
    // ✅ Abrir acordeones automáticamente si hay datos
    const accordions = ['personal', 'miembros', 'predio'];
    
    accordions.forEach(type => {
        const content = document.getElementById(`content-${type}`);
        const icon = document.getElementById(`icon-${type}`);
        
        if (content) {
            setTimeout(() => {
                content.classList.add('active');
                if (icon) icon.classList.add('rotated');
            }, 300);
        }
    });
});
</script>
@endsection
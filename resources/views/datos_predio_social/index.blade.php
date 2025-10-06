@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 900px; background-color: #e8d5dce0;">
        <div class="card-body">
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

            {{-- ✅ NUEVO: Acordeón de Datos Personales --}}
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

            {{-- ✅ NUEVO: Acordeón de Miembros del Hogar --}}
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
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <h3 class="text-center mb-3 text-success fw-bold">🏡 Datos del Predio</h3>
            <p class="text-muted text-center mb-4">
                Visita <span class="fw-semibold">#{{ $visita->id }}</span> – Proveedor <b>{{ $visita->proveedor->proveedor_nombre }}</b>
            </p>

            @foreach($plantaciones as $plantacion)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white fw-bold">
                        🌱 Plantación: {{ $plantacion->nombre }}
                    </div>
                    <div class="card-body">
                        @php
                            $dato = $datos->where('plantacion_id', $plantacion->id)->first();
                        @endphp

                        @if($dato)
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b>📍 Nombre finca:</b> {{ $dato->nombre_finca }}</p>
                                    <p><b>📍 Forma Tenencia:</b> {{ $dato->forma_tenencia }}</p>
                                    <p><b>📍 Municipio:</b> {{ $dato->municipio }}</p>
                                    <p><b>📍 Vereda:</b> {{ $dato->vereda }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><b>📍 Registro ICA:</b> {{ $dato->registrado_ica ? 'Sí' : 'No' }}</p>
                                    <p><b>📍 Vive en el predio:</b> {{ $dato->vive_predio ? 'Sí' : 'No' }}</p>
                                    <p><b>📍 Infraestructura de predio:</b> {{ $dato->infraestructura_predio }}</p>
                                </div>
                            </div>

                            @if($dato->infraestructura_vial || $dato->servicios_publicos)
                            <div class="row mt-3">
                                @if($dato->infraestructura_vial)
                                <div class="col-md-6">
                                    <p><b>🛣️ Infraestructura Vial:</b></p>
                                    <ul>
                                        @foreach(json_decode($dato->infraestructura_vial) as $vial)
                                            <li>{{ $vial }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if($dato->servicios_publicos)
                                <div class="col-md-6">
                                    <p><b>⚡ Servicios Públicos:</b></p>
                                    <ul>
                                        @foreach(json_decode($dato->servicios_publicos) as $servicio)
                                            <li>{{ $servicio }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                            @endif

                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('datos_predio_social.edit', [$visita->id, $dato->id]) }}" 
                                   class="btn btn-warning btn-sm">✏️ Editar</a>
                                <form action="{{ route('datos_predio_social.destroy', [$visita->id, $dato->id]) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este registro?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('datos_predio_social.create', [$visita->id, $plantacion->id]) }}" 
                               class="btn btn-success">➕ Registrar datos del predio</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
.members-wrap {
    max-width: 135%;
    border-radius: 5px;
    margin-left: -60px;
}
.members-card {
     max-width: 125%;
    background: #fff;
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

/* ✅ NUEVO: Estilos para los acordeones */
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

.alert.success {
    background: #e9f7ee;
    color: #19692b;
    padding: 10px 14px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-weight: 600;
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
    text-align: left;
    padding: 10px 14px;
    width: 40%;
    font-weight: 600;
    border-bottom: 1px solid #e5e7eb;
}
.custom-table td {
    padding: 10px 14px;
    border-bottom: 1px solid #e5e7eb;
    color: #374151;
}
.custom-table tr:hover td {
    background: #f9fafb;
}
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}
.btn {
    display: inline-block;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    cursor: pointer;
    text-align: center;
}
.btn-primary {
    background: #198754;
    color: #fff;
    border: none;
}
.btn-primary:hover {
    background: #14673f;
}
.btn-ghost {
    background: #f3f4f6;
    color: #374151;
}
.btn-ghost:hover {
    background: #e5e7eb;
}
.btn-warning {
    background: #f59e0b;
    color: #fff;
}
.btn-warning:hover {
    background: #d97706;
}

/* Tabla responsive para miembros */
.table-responsive {
    max-height: 300px;
    overflow-y: auto;
}

.table-sm th, .table-sm td {
    padding: 8px 12px;
    font-size: 0.9rem;
}

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

    /* Responsive para acordeones en móvil */
    .accordion-content.active {
        max-height: 600px;
    }
    
    .table-responsive {
        max-height: 250px;
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
    const accordions = ['personal', 'miembros'];
    
    accordions.forEach(type => {
        const content = document.getElementById(`content-${type}`);
        const icon = document.getElementById(`icon-${type}`);
        
        
    });
});
</script>
@endsection
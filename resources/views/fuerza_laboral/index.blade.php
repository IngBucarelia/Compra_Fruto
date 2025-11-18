@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5 p-4 bg-white rounded shadow-sm">

    {{-- ✅ Selector de secciones --}}
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('redireccion_seccion_social', $visita->id) }}" method="GET">
                <label for="seccion" class="form-label fw-bold text-success">📋 Ir a sección:</label>
                <div class="input-group">
                    <select id="seccion" name="seccion" class="form-select" required>
                        <option value="">Seleccione una sección</option>
                        @if ($visita->estado === 'pendiente' || $visita->estado === 'en_ejecucion')
                            <option value="inicio">🏠 Página de Inicio de Visita</option>
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
        </div>
    </div>

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
                        <div class="row g-3">
                            <div class="col-md-6">
                                <p><strong>📞 Teléfono:</strong> {{ $datosPersonales->telefono ?? 'No especificado' }}</p>
                                <p><strong>🚻 Sexo:</strong> {{ $datosPersonales->sexo ?? 'No especificado' }}</p>
                                <p><strong>📚 Nivel de Estudio:</strong> {{ $datosPersonales->nivel_estudio ?? 'No especificado' }}</p>
                                <p><strong>🎂 Fecha Nacimiento:</strong>
                                    {{ $datosPersonales->fecha_nacimiento ? \Carbon\Carbon::parse($datosPersonales->fecha_nacimiento)->format('d/m/Y') : 'No especificado' }}
                                </p>
                                <p><strong>🏠 Reside en Predio:</strong> {{ $datosPersonales->reside_predio === 'SI' ? 'Sí' : 'No' }}</p>
                                <p><strong>👨‍🌾 Administra Cultivo:</strong> {{ $datosPersonales->administra_cultivo ?? 'No especificado' }}</p>
                            </div>

                            <div class="col-md-6">
                                <p><strong>🌴 Años en Palmicultura:</strong> {{ $datosPersonales->anios_palmicultura ?? '0' }} años</p>
                                <p><strong>🏥 Régimen de Salud:</strong> {{ $datosPersonales->regimen_salud ?? 'No especificado' }}</p>
                                <p><strong>💻 Internet:</strong> {{ $datosPersonales->internet ?? 'No especificado' }}</p>
                                <p><strong>👥 Grupo Poblacional:</strong> {{ $datosPersonales->grupo_poblacional ?? 'No especificado' }}</p>
                                <p><strong>💬 Red Social:</strong> {{ $datosPersonales->red_social ?? 'No especificado' }}</p>
                                <p><strong>💼 Tipo Persona:</strong> {{ $datosPersonales->tipo_persona ?? 'No especificado' }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>📝 RNP:</strong> {{ $datosPersonales->rnp ?? 'No especificado' }}</p>
                                <p><strong>🔢 Número RNP:</strong> {{ $datosPersonales->numero_rnp ?? 'No especificado' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>🏛️ Fedepalma:</strong> {{ $datosPersonales->fedepalma ?? 'No especificado' }}</p>
                                <p><strong>📘 Alfabetizado:</strong> {{ $datosPersonales->alfabetizado ?? 'No especificado' }}</p>
                            </div>
                             <div class="row">
                <div class="col-md-6">
                    <p><strong>🕓 Oferta Mercantil:</strong> {{ $datosPersonales->oferta_mercantil ?? 'No especificado' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>⏳ Hace cuánto:</strong> {{ $datosPersonales->hace_cuanto ?? 'No aplica' }}</p>
                </div>
            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
            @endif

    {{-- ✅ Miembros del Hogar --}}
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
                    <table class="table table-sm table-hover align-middle">
                        <thead class="table-success">
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

    {{-- ✅ Datos del Predio --}}
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
                <div class="card mb-3 border-success">
                    <div class="card-header bg-success text-white fw-bold">
                        🌱 Plantación: {{ $dato->plantacion->nombre ?? 'N/A' }}
                    </div>
                    <div class="card-body bg-light">
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
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ✅ Título y botón agregar --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-success fw-bold">🧑‍🌾 Fuerza Laboral</h3>
        <a href="{{ route('fuerza_laboral.create', $visita->id) }}" class="btn btn-success">➕ Agregar Fuerza Laboral</a>
    </div>

    {{-- ✅ Tabla Fuerza Laboral --}}
    @if($fuerzas->count() > 0)
    <div class="card p-4 shadow-sm border-0 bg-light">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
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
                            <div class="btn-group">
                                <a href="{{ route('fuerza_laboral.show', [$visita->id, $fuerza->id]) }}" class="btn btn-info btn-sm">👁️ Ver</a>
                                <a href="{{ route('fuerza_laboral.edit', [$visita->id, $fuerza->id]) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                                <form action="{{ route('fuerza_laboral.destroy', [$visita->id, $fuerza->id]) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este registro?')">🗑️ Eliminar</button>
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
    <div class="card p-4 text-center bg-light shadow-sm">
        <h4 class="text-muted">📭 No hay registros de fuerza laboral</h4>
        <a href="{{ route('fuerza_laboral.create', $visita->id) }}" class="btn btn-success mt-2">➕ Agregar Fuerza Laboral</a>
    </div><br><br>
    @endif
<div class="mb-4" style="margin-left: 50px">
            <a href="{{ route('organizacion_social.index', $visita->id) }}" class="btn btn-primary">
                👥 Ir a Organización Social
            </a>
            <a href="{{ url()->previous() }}" class="btn btn-primary">
                 Regresar  
            </a>
        </div>
</div>

<style>
.container {
    max-width: 1100px;
}

.accordion-container {
    margin-bottom: 15px;
}

.accordion-card {
    border: 1px solid #dcdcdc;
    border-radius: 10px;
    background: #f8f9fa;
    overflow: hidden;
}

.accordion-header {
    background: #198754;
    color: white;
    padding: 12px 16px;
    cursor: pointer;
}

.accordion-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.accordion-content {
    padding: 0;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

.accordion-content.active {
    padding: 16px;
    max-height: 600px;
}

.accordion-icon.rotated {
    transform: rotate(180deg);
}

.table-responsive {
    max-height: 400px;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .container {
        width: 100%;
        padding: 15px;
    }
}
</style>

<script>
function toggleAccordion(header, type) {
    const content = document.getElementById(`content-${type}`);
    const icon = document.getElementById(`icon-${type}`);
    content.classList.toggle('active');
    icon.classList.toggle('rotated');
}
</script>
@endsection

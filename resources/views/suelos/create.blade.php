@extends('layouts.app')

@section('content')

<style>

    

.container{
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
    }

    .title{
    text-align: center; 
    font-family: Arial Black; 
    font-weight: bold; 
    font-size: 30px; 
    color: #fdffe5; 
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    }


    @media (max-width: 768px) {

        .container {
        margin-left: -35px;
        width: 110%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }
    }
</style>
    <div class="container" >
    <h3 class="title">🧪Información previa de plantación - Analisis de Suelo 🧬</h3><h3> <br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span><br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
    <span style="color: wheat">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span>
</h3>
    <form id="formRedireccion" class="mt-4">
    <p> <strong>Seleccione la Zona a Dirigirse</strong></p>
                <div class="input-group">
                    <select id="seccion" class="form-select" required>
                        <option value="">Seleccione una sección</option>

                        @if ($visita->estado === 'pendiente' || $visita->estado === 'en_ejecucion')
                            <option value="{{ route('areas.create', ['visita_id' => $visita->id]) }}">📍 Área</option>
                            <option value="{{ route('fertilizaciones.create', ['visita_id' => $visita->id]) }}">💧 Fertilización</option>
                            <option value="{{ route('polinizaciones.create', ['visita_id' => $visita->id]) }}">🌸 Polinización</option>
                            <option value="{{ route('sanidades.create', ['visita_id' => $visita->id]) }}">🦠 Sanidad</option>
                            <option value="{{ route('suelos.create', ['visita_id' => $visita->id]) }}">🧪 Análisis de Suelo</option>
                            <option value="{{ route('labores_cultivo.create', ['visita_id' => $visita->id]) }}">🚜 Labores de Cultivo</option>
                            <option value="{{ route('evaluacion.create', ['visita_id' => $visita->id]) }}">🌴 Evaluación de Cosecha</option>
                        @endif
                    </select>

                    <button type="submit" class="btn btn-primary">Ir</button>
                </div>
                </form>

                <script>
                document.getElementById('formRedireccion').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const url = document.getElementById('seccion').value;
                    if (url) window.location.href = url;
                });
            </script><br><br>

    {{-- Acordeones con formularios anteriores --}}
    <div class="accordion mb-4" id="acordeonSuelo">

        {{-- Área --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-expanded="true">
                    📍 Área registrada
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" data-bs-parent="#acordeonSuelo">
                <div class="accordion-body">
                    @if ($visita->area)
                        <ul>
                            <li><strong>Material:</strong> {{ $visita->area->material }}</li>
                            <li><strong>Estado:</strong> {{ $visita->area->estado }}</li>
                            <li><strong>Año siembra:</strong> {{ $visita->area->anio_siembra }}</li>
                            <li><strong>Área (m²):</strong> {{ $visita->area->area }}</li>
                            <li><strong>Orden Plantis:</strong> {{ $visita->area->orden_plantis_numero }}</li>
                            <li><strong>Estado Orden Plantis:</strong> {{ $visita->area->estado_oren_plantis }}</li>
                        </ul>
                    @else
                        <p class="text-muted">No se ha registrado información de área.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Fertilizaciones --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFert">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFert">
                    💧 Fertilizaciones
                </button>
            </h2>
            <div id="collapseFert" class="accordion-collapse collapse" data-bs-parent="#acordeonSuelo">
                <div class="accordion-body">
                    @if ($visita->fertilizaciones->count())
                        @foreach ($visita->fertilizaciones as $fertilizacion)
                            <div class="mb-3">
                                <strong>Fecha:</strong> {{ $fertilizacion->fecha_fertilizacion }}
                                <ul>
                                    @foreach ($fertilizacion->fertilizantes as $f)
                                        <li>{{ ucfirst($f->nombre) }} - {{ $f->cantidad }} kg</li>
                                    @endforeach
                                </ul>
                                <hr>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay fertilizaciones registradas.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Polinizaciones --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPol">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePol">
                    🌸 Polinizaciones
                </button>
            </h2>
            <div id="collapsePol" class="accordion-collapse collapse" data-bs-parent="#acordeonSuelo">
                <div class="accordion-body">
                    @if ($visita->polinizaciones->count())
                        <ul class="list-group">
                            @foreach ($visita->polinizaciones as $poli)
                                <li class="list-group-item">
                                    📅 {{ $poli->fecha }} |
                                    Pases: <strong>{{ $poli->n_pases }}</strong>,
                                    Ronda: <strong>{{ $poli->ciclos_ronda }}</strong>,
                                    ANA: <strong>{{ $poli->ana }}</strong> ({{ $poli->tipo_ana }}),
                                    Talco: <strong>{{ $poli->talco }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No hay polinizaciones registradas.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sanidad --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSanidad">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSanidad">
                    🧪 Sanidad registrada
                </button>
            </h2>
            <div id="collapseSanidad" class="accordion-collapse collapse" data-bs-parent="#acordeonSuelo">
                <div class="accordion-body">
                    @if ($visita->sanidad)
                        <ul>
                            <li><strong>Opsophanes:</strong> {{ $visita->sanidad->opsophanes }}%</li>
                            <li><strong>Pudrición cogollo:</strong> {{ $visita->sanidad->pudricion_cogollo }}%</li>
                            <li><strong>Raspador:</strong> {{ $visita->sanidad->raspador }}%</li>
                            <li><strong>Palmarum:</strong> {{ $visita->sanidad->palmarum }}%</li>
                            <li><strong>Strategus:</strong> {{ $visita->sanidad->strategus }}%</li>
                            <li><strong>Leptoparsha:</strong> {{ $visita->sanidad->leptoparsha }}%</li>
                            <li><strong>Pestalotiopsis:</strong> {{ $visita->sanidad->pestalotiopsis }}%</li>
                            <li><strong>Pudrición basal:</strong> {{ $visita->sanidad->pudricion_basal }}%</li>
                            <li><strong>Pudrición estipe:</strong> {{ $visita->sanidad->pudricion_estipe }}%</li>
                            <li><strong>Otros:</strong> {{ $visita->sanidad->otros }}</li>
                            <li><strong>Observaciones:</strong> {{ $visita->sanidad->observaciones }}</li>
                        </ul>
                    @else
                        <p class="text-muted">No hay datos de sanidad registrados.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
    <h3 class="title">🧬Formulario de Análisis de Suelo </h3>

            @if ($suelo)
            {{-- Mostrar detalles del suelo con botón para editar --}}
            <div class="card mb-4">
                <div class="card-header">🧾 Análisis de Suelo Registrado</div>
                <div class="card-body">
                    <p><strong>Análisis foliar:</strong> {{ ucfirst($suelo->analisis_foliar) }}</p>
                    <p><strong>Análisis suelo:</strong> {{ ucfirst($suelo->alanisis_suelo) }}</p>
                    <p><strong>Tipo de suelo:</strong> {{ ucfirst($suelo->tipo_suelo) }}</p>
                    <a href="{{ route('suelos.edit', $suelo->id) }}" class="btn btn-warning">✏️ Editar análisis</a>
                    <form method="POST" action="{{ route('suelos.destroy', $suelo->id) }}" class="d-inline" onsubmit="return confirm('¿Deseas eliminar este análisis de suelo?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">🗑️ Eliminar análisis</button>
                    </form>
                </div>
            </div>
        @else
            {{-- Mostrar formulario si no hay registro --}}
            <form method="POST" action="{{ route('suelos.store') }}">
                @csrf
                <input type="hidden" name="visita_id" value="{{ $visita->id }}">

                <div class="mb-3">
                    <label>¿Análisis foliar?</label>
                    <select name="analisis_foliar" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>¿Análisis de suelo?</label>
                    <select name="alanisis_suelo" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Tipo de suelo</label>
                    <select name="tipo_suelo" class="form-control" required>
                        <option value="">Seleccione</option>
                        <option value="arenoso">Arenoso</option>
                        <option value="arcilloso">Arcilloso</option>
                        <option value="franco">Franco</option>
                        <option value="limoso">Limoso</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">💾 Guardar análisis de suelo</button>
            </form>
        @endif
           <button> <a href="{{ route('labores_cultivo.create', ['visita_id' => $visita->id]) }}" >
                ➡️ Continuar con Labores de Cultivo
            </a>  </button>                 
</div>
@endsection

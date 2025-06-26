@extends('layouts.app')

@section('content')
<div class="container">
    <h3>🌴🌴 Información de plantación - Evaluación de Cosecha en Campo 🌴🌴 <br><br>Fecha Visita: <span class="text-primary">{{ $visita->fecha}}</span><br> Proveedor:<span class="text-primary"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
        <span class="text-primary">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span>
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


    {{-- Acordeón con formularios anteriores --}}
    <div class="accordion mb-4" id="acordeonEvaluacion">

        {{-- Área --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-expanded="true">
                    📍 Área registrada
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" data-bs-parent="#acordeonEvaluacion">
                <div class="accordion-body">
                    @if ($visita->area)
                        <ul>
                            <li><strong>Material:</strong> {{ $visita->area->material }}</li>
                            <li><strong>Estado:</strong> {{ $visita->area->estado }}</li>
                            <li><strong>Año siembra:</strong> {{ $visita->area->anio_siembra }}</li>
                            <li><strong>Área:</strong> {{ $visita->area->area }} m²</li>
                            <li><strong>Orden Plantis:</strong> {{ $visita->area->orden_plantis_numero }}</li>
                            <li><strong>Estado Orden:</strong> {{ $visita->area->estado_oren_plantis }}</li>
                        </ul>
                    @else
                        <p class="text-muted">No se ha registrado área.</p>
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
            <div id="collapseFert" class="accordion-collapse collapse" data-bs-parent="#acordeonEvaluacion">
                <div class="accordion-body">
                    @if ($visita->fertilizaciones->count())
                        @foreach ($visita->fertilizaciones as $fertilizacion)
                            <div class="mb-2">
                                <strong>Fecha:</strong> {{ $fertilizacion->fecha_fertilizacion }}
                                <ul>
                                    @foreach ($fertilizacion->fertilizantes as $f)
                                        <li>{{ ucfirst($f->nombre) }} - {{ $f->cantidad }} kg</li>
                                    @endforeach
                                </ul>
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
            <div id="collapsePol" class="accordion-collapse collapse" data-bs-parent="#acordeonEvaluacion">
                <div class="accordion-body">
                    @if ($visita->polinizaciones->count())
                        <ul>
                            @foreach ($visita->polinizaciones as $p)
                                <li>
                                    📅 {{ $p->fecha }} | Pases: {{ $p->n_pases }}, Ronda: {{ $p->ciclos_ronda }}, ANA: {{ $p->ana }} ({{ $p->tipo_ana }}), Talco: {{ $p->talco }}
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
                    🧪 Sanidad
                </button>
            </h2>
            <div id="collapseSanidad" class="accordion-collapse collapse" data-bs-parent="#acordeonEvaluacion">
                <div class="accordion-body">
                    @if ($visita->sanidad)
                        <ul>
                            <li>Opsophanes: {{ $visita->sanidad->opsophanes }}%</li>
                            <li>Pudrición Cogollo: {{ $visita->sanidad->pudricion_cogollo }}%</li>
                            <li>Raspador: {{ $visita->sanidad->raspador }}%</li>
                            <li>Palmarum: {{ $visita->sanidad->palmarum }}%</li>
                            <li>Strategus: {{ $visita->sanidad->strategus }}%</li>
                            <li>Leptoparsa: {{ $visita->sanidad->leptoparsa }}%</li>
                            <li>Pestalotiopsis: {{ $visita->sanidad->pestalotiopsis }}%</li>
                            <li>Pudrición Basal: {{ $visita->sanidad->pudricion_basal }}%</li>
                            <li>Pudrición Estipe: {{ $visita->sanidad->pudricion_estipe }}%</li>
                            <li>Otros: {{ $visita->sanidad->otros }}</li>
                            <li>Observaciones: {{ $visita->sanidad->observaciones }}</li>
                        </ul>
                    @else
                        <p class="text-muted">No hay registro de sanidad.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Suelo --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSuelo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSuelo">
                    🧬 Análisis de Suelo
                </button>
            </h2>
            <div id="collapseSuelo" class="accordion-collapse collapse" data-bs-parent="#acordeonEvaluacion">
                <div class="accordion-body">
                    @if ($visita->suelo)
                        <ul>
                            <li>Análisis foliar: {{ ucfirst($visita->suelo->analisis_foliar) }}</li>
                            <li>Análisis suelo: {{ ucfirst($visita->suelo->alanisis_suelo) }}</li>
                            <li>Tipo suelo: {{ ucfirst($visita->suelo->tipo_suelo) }}</li>
                        </ul>
                    @else
                        <p class="text-muted">No hay análisis de suelo registrado.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Labores de Cultivo --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingLabores">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLabores">
                    🛠️ Labores de Cultivo
                </button>
            </h2>
            <div id="collapseLabores" class="accordion-collapse collapse" data-bs-parent="#acordeonEvaluacion">
                <div class="accordion-body">
                    @if ($visita->laboresCultivo)
                        <ul>
                            @foreach ($visita->laboresCultivo->toArray() as $campo => $valor)
                                @continue(in_array($campo, ['id', 'visita_id', 'created_at', 'updated_at']))
                                <li>{{ ucwords(str_replace('_', ' ', $campo)) }}: {{ $valor }}%</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No se han registrado labores de cultivo.</p>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
    <h3>🌴 Formulario Evaluación de Cosecha en Campo </h3>

    {{-- Evaluación de Cosecha --}}
    @if ($visita->evaluacionCosechaCampo)
        <div class="alert alert-info">
            ✅ Ya existe una evaluación de cosecha registrada.
        </div>

        <ul class="list-group mb-3">
            <li class="list-group-item">
                <strong>Variedad del fruto:</strong> {{ ucfirst($visita->evaluacionCosechaCampo->variedad_fruto) }}
            </li>
            <li class="list-group-item">
                <strong>Cantidad de racimos:</strong> {{ $visita->evaluacionCosechaCampo->cantidad_racimos }}
            </li>
            <li class="list-group-item">
                <strong>Verde:</strong> {{ $visita->evaluacionCosechaCampo->verde }}%
            </li>
            <li class="list-group-item">
                <strong>Maduro:</strong> {{ $visita->evaluacionCosechaCampo->maduro }}%
            </li>
            <li class="list-group-item">
                <strong>Sobre maduro:</strong> {{ $visita->evaluacionCosechaCampo->sobremaduro }}%
            </li>
            <li class="list-group-item">
                <strong>Pedúnculo:</strong> {{ $visita->evaluacionCosechaCampo->pedunculo }}%
            </li>
            <li class="list-group-item">
                <strong>Observaciones:</strong> {{ $visita->evaluacionCosechaCampo->observaciones ?? 'Sin observaciones' }}
            </li>
        </ul>

        <a href="{{ route('evaluacion.edit', $visita->evaluacionCosechaCampo->id) }}" class="btn btn-warning">
            ✏️ Editar Evaluación 
        </a>
        <form method="POST" action="{{ route('evaluacion.destroy', $visita->evaluacionCosechaCampo->id) }}" class="d-inline" onsubmit="return confirm('¿Deseas eliminar esta evaluación?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">🗑️ Eliminar Evaluacion</button>
        </form>
    @else
        {{-- FORMULARIO DE CREACIÓN --}}
        <form method="POST" action="{{ route('evaluacion.store') }}">
            @csrf
            <input type="hidden" name="visita_id" value="{{ $visita->id }}">

            <div class="mb-3">
                <label>Variedad del fruto</label>
                <select name="variedad_fruto" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="guinense">Guinense</option>
                    <option value="hibrido">Híbrido</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Cantidad de racimos</label>
                <input type="number" name="cantidad_racimos" class="form-control" required>
            </div>

            <div class="row">
                @foreach (['verde', 'maduro', 'sobremaduro', 'pedunculo'] as $campo)
                    <div class="col-md-6 mb-3">
                        <label>{{ ucfirst($campo) }} (%)</label>
                        <input type="number" name="{{ $campo }}" class="form-control" required>
                    </div>
                @endforeach
            </div>

            <div class="mb-3">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">💾 Guardar evaluación</button>
        </form>
    @endif
        <a href="{{ route('cierre-visitas.create', ['visita_id' => $visita->id]) }}" class="btn btn-outline-primary mt-3">
            📌 Finalizar Visita
        </a>

</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">

    <h3>🌴🌴 Información de plantación - Labores de Cultivo🌴🌴 <br> Proveedor:<span class="text-primary"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
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


    <div class="accordion mb-4" id="acordeonLabores">

        {{-- Área --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-expanded="true">
                    📍 Área registrada
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" data-bs-parent="#acordeonLabores">
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
            <div id="collapseFert" class="accordion-collapse collapse" data-bs-parent="#acordeonLabores">
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
            <div id="collapsePol" class="accordion-collapse collapse" data-bs-parent="#acordeonLabores">
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
                    🧪 Sanidad
                </button>
            </h2>
            <div id="collapseSanidad" class="accordion-collapse collapse" data-bs-parent="#acordeonLabores">
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

        {{-- Suelo --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSuelo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSuelo">
                    🧬 Análisis de Suelo
                </button>
            </h2>
            <div id="collapseSuelo" class="accordion-collapse collapse" data-bs-parent="#acordeonLabores">
                <div class="accordion-body">
                    @if ($visita->suelo)
                        <ul>
                            <li><strong>Análisis foliar:</strong> {{ ucfirst($visita->suelo->analisis_foliar) }}</li>
                            <li><strong>Análisis suelo:</strong> {{ ucfirst($visita->suelo->alanisis_suelo) }}</li>
                            <li><strong>Tipo de suelo:</strong> {{ ucfirst($visita->suelo->tipo_suelo) }}</li>
                        </ul>
                    @else
                        <p class="text-muted">No se ha registrado análisis de suelo.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
        <h3>🚜 Formulario Labores de Cultivo </h3>

    {{-- Formulario Labores de Cultivo --}}
    <form method="POST" action="{{ route('labores_cultivo.store') }}">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        <div class="row">
            @php
                $labores = [
                    'polinizacion' => 'Polinización',
                    'limpieza_calle' => 'Limpieza de calle',
                    'limpieza_plato' => 'Limpieza de plato',
                    'poda' => 'Poda',
                    'fertilizacion' => 'Fertilización',
                    'enmiendas' => 'Enmiendas',
                    'ubicacion_tusa_fibra' => 'Ubicación tusa/fibra',
                    'ubicacion_hoja' => 'Ubicación hoja',
                    'lugar_ubicacion_hoja' => 'Lugar ubicación hoja',
                    'plantas_nectariferas' => 'Plantas nectaríferas',
                    'cobertura' => 'Cobertura',
                    'labor_cosecha' => 'Labor de cosecha',
                    'calidad_fruta' => 'Calidad de fruta',
                    'recoleccion_fruta' => 'Recolección de fruta',
                    'drenajes' => 'Drenajes',
                ];
            @endphp

            @foreach ($labores as $campo => $label)
                <div class="col-md-6 mb-3">
                    <label>{{ $label }} (%)</label>
                    <input type="number" name="{{ $campo }}" class="form-control" min="0" max="100">
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">💾 Guardar labores</button>
    </form>
</div>
@endsection

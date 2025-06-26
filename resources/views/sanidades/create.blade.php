@extends('layouts.app')

@section('content')
<div class="container">
    <h3>🌴🌴 Información de plantación - Sanidad 🌴🌴<br><br>Fecha Visita: <span class="text-primary">{{ $visita->fecha}}</span> <br> Proveedor:<span class="text-primary"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
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
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Acordeón con información anterior --}}
    <div class="accordion mb-4" id="acordeonSanidad">

        {{-- Área --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-expanded="true">
                    📍 Área registrada
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" data-bs-parent="#acordeonSanidad">
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
                    💧 Fertilizaciones registradas
                </button>
            </h2>
            <div id="collapseFert" class="accordion-collapse collapse" data-bs-parent="#acordeonSanidad">
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
                    🌸 Polinizaciones registradas
                </button>
            </h2>
            <div id="collapsePol" class="accordion-collapse collapse" data-bs-parent="#acordeonSanidad">
                <div class="accordion-body">
                    @if ($visita->polinizaciones->count())
                        <ul class="list-group">
                            @foreach ($visita->polinizaciones as $poli)
                                <li class="list-group-item">
                                    📅 {{ $poli->fecha }} | Pases: <strong>{{ $poli->n_pases }}</strong>, 
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

    </div>
    <h3>🧪 Registro de Sanidad - {{ $visita->proveedor->proveedor_nombre }}</h3>

    {{-- Formulario SANIDAD --}}
    <form method="POST" action="{{ route('sanidades.store') }}">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        <div class="row">
            @php
                $enfermedades = [
                    'opsophanes' => 'Opsophanes',
                    'pudricion_cogollo' => 'Pudrición del cogollo',
                    'raspador' => 'Raspador',
                    'palmarum' => 'Palmarum',
                    'strategus' => 'Strategus',
                    'leptoparsha' => 'Leptoparsha',
                    'pestalotiopsis' => 'Pestalotiopsis',
                    'pudricion_basal' => 'Pudrición basal',
                    'pudricion_estipe' => 'Pudrición estipe',
                ];
            @endphp

            @foreach ($enfermedades as $campo => $nombre)
                <div class="col-md-6 mb-3">
                    <label>{{ $nombre }} (%)</label>
                    <input type="number" name="{{ $campo }}" class="form-control" min="0" max="100" >
                </div>
            @endforeach

            <div class="col-md-6 mb-3">
                <label>Otros (%)</label>
                <input type="text" name="otros" class="form-control">
            </div>

            <div class="col-12 mb-3">
                <label>Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">💾 Guardar sanidad</button>
    </form>
    <a href="{{ route('suelos.create', ['visita_id' => $visita->id]) }}" class="btn btn-outline-success mt-3">
        ➡️ Continuar con Análisis de Suelo
    </a>

</div>

<hr>
<h4 class="mt-4">🦠 Sanidades registradas</h4>

@if ($visita->sanidades->count())
    <ul class="list-group">
        @foreach ($visita->sanidades as $sanidad)
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div>
                    <strong>Opsophanes:</strong> {{ $sanidad->opsophanes }}% |
                    <strong>P. Cogollo:</strong> {{ $sanidad->pudricion_cogollo }}% |
                    <strong>Raspador:</strong> {{ $sanidad->raspador }}% |
                    <strong>Palmarum:</strong> {{ $sanidad->palmarum }}% |
                    <strong>Strategus:</strong> {{ $sanidad->strategus }}% |
                    <strong>Leptoparsha:</strong> {{ $sanidad->leptoparsha }}% |
                    <strong>Pestalotiopsis:</strong> {{ $sanidad->pestalotiopsis }}% |
                    <strong>P. Basal:</strong> {{ $sanidad->pudricion_basal }}% |
                    <strong>P. Estipe:</strong> {{ $sanidad->pudricion_estipe }}% |
                    <strong>Otros:</strong> {{ $sanidad->otros ?? '-' }}<br>
                    <strong>Observaciones:</strong> {{ $sanidad->observaciones ?? 'Sin observaciones' }}
                </div>
                    <a href="{{ route('sanidades.edit', $sanidad->id) }}" class="btn btn-sm btn-warning">✏️ Editar Registro</a>

                <form method="POST" action="{{ route('sanidades.destroy', $sanidad->id) }}" onsubmit="return confirm('¿Deseas eliminar esta sanidad?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger ms-3">🗑️ Eliminar</button>
                </form>
            </li>
        @endforeach
    </ul>
@else
    <p class="text-muted">No se han registrado sanidades aún.</p>
@endif

@endsection

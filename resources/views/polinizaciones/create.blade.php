@extends('layouts.app')

@section('content')
<div class="container">
<h3>🌴🌴 Información de plantación - Polinización 🌴🌴<br><br>Fecha Visita: <span class="text-primary">{{ $visita->fecha}}</span> <br> Proveedor:<span class="text-primary"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
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
    {{-- Acordeón: Área --}}
    <div class="accordion mb-4" id="accordionPolinizacion" style="background-color: darkseagreen !important">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button style="background-color: darkseagreen !important; color:aliceblue" class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" >
                    📍 Área registrada
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" data-bs-parent="#accordionPolinizacion">
                <div class="accordion-body" style="background-color: rgb(209, 241, 209) !important; color:rgb(31, 32, 34)">
                    @if ($visita->area)
                        <ul>
                            <li><strong>Material:</strong> {{ $visita->area->material }}</li>
                            <li><strong>Estado:</strong> {{ $visita->area->estado }}</li>
                            <li><strong>Año siembra:</strong> {{ $visita->area->anio_siembra }}</li>
                            <li><strong>Área (m²):</strong> {{ $visita->area->area }}</li>
                            <li><strong>Orden Plantis N°:</strong> {{ $visita->area->orden_plantis_numero }}</li>
                            <li><strong>Estado orden Plantis:</strong> {{ $visita->area->estado_orden_plantis }}</li>
                        </ul>
                    @else
                        <p class="text-muted">No hay información de área registrada.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Acordeón: Fertilizaciones --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFertilizaciones">
                <button style="background-color: darkseagreen !important; color:aliceblue" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFertilizaciones" aria-expanded="false">
                    💧 Fertilizaciones registradas
                </button>
            </h2>
            <div id="collapseFertilizaciones" class="accordion-collapse collapse" data-bs-parent="#accordionPolinizacion">
                <div class="accordion-body" style="background-color: rgb(209, 241, 209) !important; color:rgb(31, 32, 34)">
                    @if ($visita->fertilizaciones->count())
                        @foreach ($visita->fertilizaciones as $fertilizacion)
                            <div class="mb-3">
                                <h6>📅 {{ $fertilizacion->fecha_fertilizacion }}</h6>
                                <ul class="list-group">
                                    @foreach ($fertilizacion->fertilizantes as $fertilizante)
                                        <li class="list-group-item d-flex justify-content-between">
                                            {{ ucfirst($fertilizante->fertilizante) }}
                                            <span>{{ $fertilizante->cantidad }} kg</span>
                                        </li>
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
    </div>
    <h3>🌾Formulario de Registro de Polinización - {{ $visita->proveedor->proveedor_nombre }}</h3>

    {{-- Formulario de polinización --}}
    <form method="POST" action="{{ route('polinizaciones.store') }}">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        <div class="mb-3">
            <label>📅 Fecha de polinización:</label>
            <input type="date" name="fecha" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>🔁 Número de pases:</label>
            <input type="number" name="n_pases" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>🔄 Ciclos por ronda:</label>
            <input type="number" name="ciclos_ronda" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>💊 Cantidad de ANA aplicada:</label>
            <input type="number" step="0.01" name="ana" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>💧 Tipo de ANA:</label>
            <select name="tipo_ana" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="solido">Sólido</option>
                <option value="liquido">Líquido</option>
            </select>
        </div>

        <div class="mb-3">
            <label>🌫️ Cantidad de talco aplicado:</label>
            <input type="number" step="0.01" name="talco" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">💾 Guardar polinización</button>
    </form>

    <a href="{{ route('visitas.show', $visita->id) }}" class="btn btn-secondary mt-4">
        ⬅️ Volver al detalle de la visita
    </a>
     <a href="{{ route('sanidades.create', $visita->id) }}" class="btn btn-secondary mt-4">
        ⬅️ Pasar a Sanidad Directamente
    </a>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
</div>


    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    @if ($visita->polinizaciones->count())
        <div class="mb-4 mt-5">
            <h5>🌸 Polinizaciones registradas</h5>
            <ul class="list-group">
                @foreach ($visita->polinizaciones as $poli)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            📅 <strong>{{ $poli->fecha }}</strong><br>
                            N° Pases: {{ $poli->n_pases }} | 
                            Ciclos: {{ $poli->ciclos_ronda }} | 
                            ANA: {{ $poli->ana }} ({{ ucfirst($poli->tipo_ana) }}) | 
                            Talco: {{ $poli->talco }}
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('polinizaciones.edit', $poli->id) }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                            <form action="{{ route('polinizaciones.destroy', $poli->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta polinización?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif



</div>
@endsection

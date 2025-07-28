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
<h3 class="title">🌸 Información de previa plantación - Polinización 🌸</h3><h3><br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span> <br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
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
    {{-- Acordeón: Área --}}
    <div class="accordion mb-4" id="accordionPolinizacion" style="background-color: darkseagreen !important">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button style="background-color: darkseagreen !important; color:aliceblue" class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-controls="collapseArea">
                    📍 Información del Área(s)
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" aria-labelledby="headingArea" data-bs-parent="#accordionArea">
                <div class="accordion-body" style="background-color: rgb(209, 241, 209) !important; color:rgb(31, 32, 34)">
                    @if ($visita->areas->count() > 0)
                        @foreach ($visita->areas as $area)
                            <div class="area-info-card mb-3">
                                <h5>Área - Material: {{ $area->material }}</h5>
                                <ul>
                                    <li><strong>Estado:</strong> {{ $area->estado }}</li>
                                    <li><strong>Año siembra:</strong> {{ $area->anio_siembra }}</li>
                                    <li><strong>Área (m²):</strong> {{ $area->area }}</li>
                                    <li><strong>Área Total Finca (Ha):</strong> {{ $area->area_total_finca_hectareas ?? 'N/A' }}</li>
                                    <li><strong>Palmas Total Finca:</strong> {{ $area->numero_palmas_total_finca ?? 'N/A' }}</li>
                                    <li><strong>Área Palmas Desarrollo (Ha):</strong> {{ $area->area_palmas_desarrollo_hectareas ?? 'N/A' }}</li>
                                    <li><strong>Palmas Desarrollo:</strong> {{ $area->numero_palmas_desarrollo ?? 'N/A' }}</li>
                                    <li><strong>Área Palmas Producción (Ha):</strong> {{ $area->area_palmas_produccion_hectareas ?? 'N/A' }}</li>
                                    <li><strong>Palmas Producción:</strong> {{ $area->numero_palmas_produccion ?? 'N/A' }}</li>
                                    <li><strong>Ciclos de Cosecha:</strong> {{ $area->ciclos_cosecha ?? 'N/A' }}</li>
                                    <li><strong>Producción Toneladas/Mes:</strong> {{ $area->produccion_toneladas_por_mes ?? 'N/A' }}</li>
                                    <li><strong>Aplica Orden Plantis:</strong> {{ $area->aplica_orden_plantis ? 'Sí' : 'No' }}</li>
                                    @if ($area->aplica_orden_plantis)
                                        <li><strong>Orden Plantis N°:</strong> {{ $area->orden_plantis_numero ?? 'N/A' }}</li>
                                        <li><strong>Número de Plantas (Orden Plantis):</strong> {{ $area->numero_plantas_orden_plantis ?? 'N/A' }}</li>
                                        <li><strong>Estado Orden Plantis:</strong> {{ $area->estado_oren_plantis ?? 'N/A' }}</li>
                                    @endif
                                </ul>
                                <div class="d-flex justify-content-end mt-2">
                                    <a href="{{ route('areas.edit', $area->id) }}" class="btn btn-warning btn-sm">✏️ Editar esta área</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No se ha registrado área para esta visita.</p>
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
                                <h6>📅 Fecha Registro <br> {{ $fertilizacion->fecha_fertilizacion }}</h6>
                                <ul class="list-group">
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Fertilizante</th>
                                                    <th>Cantidad (kg)</th>
                                                    <th>Fecha de Aplicación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($fertilizacion->fertilizantes as $fertilizante)
                                                    <tr>
                                                        <td>{{ ucfirst($fertilizante->fertilizante) }}</td>
                                                        <td class="text-right">{{ number_format($fertilizante->cantidad, 2) }}</td>
                                                        <td>{{ $fertilizante->fecha_aplicacion ? \Carbon\Carbon::parse($fertilizante->fecha_aplicacion)->format('d/m/Y') : 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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
    <!--<a href="{{ route('sanidades.create', $visita->id) }}" class="btn btn-secondary mt-4">
        ⬅️ Pasar a Sanidad Directamente 
    </a> -->

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

        <div class="container" style="background-color: whitesmoke; padding: 20px;">
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



</div>
@endsection

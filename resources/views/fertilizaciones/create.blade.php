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
<h3 class="title">🌴🌴 Información Previa de plantación - Fertilización 🌴🌴</h3><h3><br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span> <br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
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
            </script>
                    <br><br>
    <div class="accordion mb-4" id="accordionArea">
        <div class="accordion-item">
            
            <h2 class="accordion-header" id="headingArea">
                  

            <button style="background-color: darkseagreen !important; color:aliceblue" class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea"  aria-controls="collapseArea">
                📍 información del Área
            </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" aria-labelledby="headingArea" data-bs-parent="#accordionArea">
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
                <p class="text-muted">No se ha registrado área para esta visita.</p>
                @endif
            </div>
            </div>
        </div>
        </div>

    <h3 class="title">Formulario de Fertilización para: {{ $visita->proveedor->proveedor_nombre }}</h3>

    <form method="POST" action="{{ route('fertilizaciones.store') }}">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        <div class="mb-3">
            <label>Fecha de fertilización:</label>
            <input type="date" name="fecha_fertilizacion" class="form-control" required>
        </div>

        <h5>Fertilizantes aplicados</h5>
        <div id="fertilizantes-container">
            <div class="fertilizante-group mb-3">
                <select name="fertilizantes[0][nombre]" class="form-control mb-2" required>
                    <option value="">Seleccione fertilizante</option>
                    <option value="urea">Urea</option>
                    <option value="compost">Compost</option>
                    <option value="npk">NPK</option>
                    <option value="otro">Otro</option>
                </select>
                <input type="number" name="fertilizantes[0][cantidad]" class="form-control" placeholder="Cantidad (kg)" required>
            </div>
        </div>

        <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="agregarFertilizante()">+ Añadir otro fertilizante</button>

        <button type="submit" class="btn btn-primary">Guardar fertilización</button>
    </form><button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
</div>
<hr>
<div class="container" style="background-color: whitesmoke; padding: 20px;">
    <h4 class="mt-4">📋 Fertilizaciones registradas</h4>

@if ($visita->fertilizaciones->count())
    @foreach ($visita->fertilizaciones as $fertilizacion)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <strong>🗓 Fecha:</strong> {{ $fertilizacion->fecha_fertilizacion }}
                </div>
                <div>
                    <a href="{{ route('fertilizaciones.edit', $fertilizacion->id) }}" class="btn btn-sm btn-outline-warning">✏️ Editar</a>
                    <form action="{{ route('fertilizaciones.destroy', $fertilizacion->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta fertilización?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">🗑️ Eliminar</button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @foreach ($fertilizacion->fertilizantes as $fertilizante)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ ucfirst($fertilizante->fertilizante) }}
                            <span>{{ $fertilizante->cantidad }} kg</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
@else
    <p class="text-muted">No hay fertilizaciones registradas aún.</p>
@endif
</div>



<script>
let index = 1;
function agregarFertilizante() {
    const container = document.getElementById('fertilizantes-container');
    const grupo = document.createElement('div');
    grupo.classList.add('fertilizante-group', 'mb-3');
    grupo.innerHTML = `
        <select name="fertilizantes[${index}][nombre]" class="form-control mb-2" required>
            <option value="">Seleccione fertilizante</option>
            <option value="urea">Urea</option>
            <option value="compost">Compost</option>
            <option value="npk">NPK</option>
            <option value="otro">Otro</option>
        </select>
        <input type="number" name="fertilizantes[${index}][cantidad]" class="form-control" placeholder="Cantidad (kg)" required>
    `;
    container.appendChild(grupo);
    index++;
}
</script>
@endsection

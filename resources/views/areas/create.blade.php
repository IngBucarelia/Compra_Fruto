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
<h3 class="title">🌴🌴 Información de plantación - Área 🌴🌴</h3><h3><br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span> <br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
    <span style="color: wheat">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span>
</h3>     <form id="formRedireccion" class="mt-4">
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

    {{-- Mostrar el área registrada si existe --}}
    @if ($visita->area)
        <div class="alert alert-success">
            <strong>Área ya registrada:</strong>
            <ul class="mb-0">
                <li><strong>Material:</strong> {{ $visita->area->material }}</li>
                <li><strong>Estado:</strong> {{ $visita->area->estado }}</li>
                <li><strong>Año de siembra:</strong> {{ $visita->area->anio_siembra }}</li>
                <li><strong>Área (m²):</strong> {{ $visita->area->area }}</li>
                <li><strong>Orden Plantis N°:</strong> {{ $visita->area->orden_plantis_numero }}</li>
                <li><strong>Estado orden Plantis:</strong> {{ $visita->area->estado_oren_plantis }}</li>
            </ul>
        </div>
        <a href="{{ route('areas.edit', $visita->area->id) }}" class="btn btn-warning mt-2">
            ✏️ Editar área
        </a>

        <a href="{{ route('fertilizaciones.create', ['visita_id' => $visita->id]) }}" class="btn btn-primary mt-3">
            ➡️ Continuar con fertilización
        </a>
        <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
    @else
        {{-- Formulario si NO hay área registrada --}}
        <form method="POST" action="{{ route('areas.store') }}">
            @csrf
            <input type="hidden" name="visita_id" value="{{ $visita->id }}">

            <div class="mb-3">
                <label>Material:</label>
                <select name="material" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="guinense">Guinense</option>
                    <option value="hibrido">Híbrido</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Estado:</label>
                <select name="estado" class="form-control" required> 
                    <option value="desarrollo">Desarrollo</option>
                    <option value="produccion">Producción</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Año de siembra:</label>
                <input type="date" name="anio_siembra" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Área (m²):</label>
                <input type="number" name="area" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Orden plantis número:</label>
                <input type="number" name="orden_plantis_numero" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Estado orden plantis:</label>
                <select name="estado_oren_plantis" class="form-control" required>
                    <option value="desarrollo">Desarrollo</option>
                    <option value="produccion">Producción</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar y continuar</button>
        </form>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
   <h3>🌴🌴 Información de plantación - Cierre de Visita 🌴🌴<br><br>Fecha Visita: <span class="text-primary">{{ $visita->fecha}}</span> <br> Proveedor:<span class="text-primary"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
    <span class="text-primary">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span>
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

                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


    {{-- Formulario --}}
    <form method="POST" action="{{ route('cierre-visitas.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        <div class="mb-3">
            <label>Firma del responsable</label>
            <input type="file" name="firma_responsable" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label>Recomendaciones técnicas</label>
            <textarea name="recomendaciones" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Observaciones finales</label>
            <textarea name="observaciones_finales" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Subir imágenes de cierre (máx. 8)</label>
            <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">✅ Finalizar visita</button>
    </form>

    <a href="{{ route('visitas.show', $visita->id) }}" class="btn btn-secondary mt-3">⬅️ Cancelar</a>
</div>
@endsection

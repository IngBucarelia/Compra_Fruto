@extends('layouts.app')

@section('content')
<style>
     body{
        background-image: url('{{ asset('images/fondo_ambiental.png') }}');
    }
    .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
        border-radius: 12px;
    }
    .title {
        text-align: center;
        font-family: Arial Black, sans-serif;
        font-weight: bold;
        font-size: 28px;
        color: #fdffe5;
        text-shadow: -1px 0 #000, 0 1px #000;
        margin-bottom: 18px;
    }
    .info-visita h3 { color: wheat; }
    .info-visita small { color: #f8f9fa; }
    .selector-seccion .input-group { gap: 8px; }
    .card-component {
        background: #f1f6f1;
        border: 1px solid #d5e6d5;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 18px;
    }
    .card-header-green {
        background: linear-gradient(45deg,#28a745,#20c997);
        color: white;
        padding: 12px 16px;
        border-radius: 8px 8px 0 0;
        margin: -18px -18px 12px -18px;
        font-weight: 700;
    }
    .btn-group-top {
        display:flex;
        gap:10px;
        margin-top:12px;
    }
    @media (max-width: 768px) {
        .button-group-top { flex-direction: column; }
    }
</style>

<div class="container offline-form-container">

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <h5 class="alert-heading">Errores de validación</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Encabezado --}}
    <h3 class="title">Agua — Captación Legal (Visita #{{ $visita->id }})</h3>

    <div class="info-visita mb-3">
        <h3>
            Fecha: <span>{{ \Carbon\Carbon::parse($visita->fecha_visita ?? $visita->fecha)->format('d/m/Y') }}</span><br>
            Proveedor: <span>{{ $visita->proveedor->proveedor_nombre ?? 'N/A' }}</span><br>
            Plantación: <span>{{ $visita->plantacion->nombre ?? 'N/A' }}</span>
        </h3>
    </div>

    {{-- Selector de secciones (igual que en show) --}}
    <div class="selector-seccion mb-3">
         <form action="{{ route('redireccion_componente_ambiental', $visita->id) }}" method="GET">
                                <div class="mb-3">
                                    <label for="componente" class="form-label fw-bold text-success">
                                        <i class="fas fa-map-signs me-2"></i>Seleccione un componente:
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <select id="componente" name="seccion" class="form-select" required
                                                style="border-radius: 10px 0 0 10px; border: 2px solid #e9ecef;">
                                            <option value="">Seleccione un componente</option>
                                            <option value="agua_captacion_legal">💧 Agua - Captación Legal</option>
                                            <option value="agua_uso_eficiente">🚰 Agua - Uso Eficiente</option>
                                            <option value="suelo_conservacion">🌱 Suelo - Conservación</option>
                                            <option value="energia">⚡ Energía</option>
                                            <option value="gobernanza_hidrica">🤝 Gobernanza Hídrica</option>
                                            <option value="emisiones_gei">🏭 Emisiones GEI</option>
                                            <option value="residuos_manejo">🗑️ Residuos - Manejo</option>
                                            <option value="sustancias_manejo">🧪 Sustancias - Manejo</option>
                                            <option value="vertimientos_manejo">💦 Vertimientos - Manejo</option>
                                            <option value="hmp_manejo">☣️ HMP - Manejo</option>
                                            <option value="avc_control">🛡️ AVC - Control</option>
                                            <option value="ecosistema_proteccion">🌳 Ecosistema - Protección</option>
                                            <option value="avc_no_reemplazo">🚫 AVC - No Reemplazo 🌲 No Deforestación</option>
                                           
                                        </select>
                                        <button type="submit" class="btn btn-success" 
                                                style="border-radius: 0 10px 10px 0;">
                                            <i class="fas fa-arrow-right me-2"></i>Ir
                                        </button>
                                    </div>
                                </div>
                            </form>
    </div>

    <script>
        document.getElementById('formRedireccion').addEventListener('submit', function (e) {
            e.preventDefault();
            const url = document.getElementById('seccion').value;
            if (url) window.location.href = url;
        });
    </script>

    {{-- Si el componente ya existe, mostrar resumen y botón editar --}}
    @if($visita->aguaCaptacionLegal)
        @php $m = $visita->aguaCaptacionLegal; @endphp
        <div class="card-component">
            <div class="card-header-green">Componente registrado</div>
            <div class="p-3">
                <p><strong>Permiso concesión:</strong> {{ $m->permiso_concesion ? 'Sí' : 'No' }}</p>
                <p><strong>Permiso ocupación cauce:</strong> {{ $m->permiso_ocupacion_cauce ? 'Sí' : 'No' }}</p>
                <p><strong>Permisos captación:</strong> {{ $m->permisos_captacion ? 'Sí' : 'No' }}</p>
                <p><strong>Registro agua:</strong> {{ $m->registro_agua ? 'Sí' : 'No' }}</p>
                <p><strong>Cumple manejo/construcción:</strong> {{ $m->cumple_manejo_construccion ? 'Sí' : 'No' }}</p>
                <p><strong>Gestión permiso ocupación:</strong> {{ $m->gestion_permiso_ocupacion ? 'Sí' : 'No' }}</p>
                <p><strong>Gestión permiso captación:</strong> {{ $m->gestion_permiso_captacion ? 'Sí' : 'No' }}</p>
                @if(!empty($m->observaciones))
                    <p><strong>Observaciones:</strong> {{ $m->observaciones }}</p>
                @endif

                <div class="btn-group-top">
                    <a href="{{ route('aguaCaptacion.edit', $visita->id) }}" class="btn btn-warning">✏️ Editar componente</a>
                    <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary">⬅️ Volver a la visita</a>
                </div>
            </div>
        </div>
    @else
        {{-- Formulario de creación --}}
        <div class="card-component">
            <div class="card-header-green">Registrar — Agua: Captación Legal</div>
            <div class="p-3">
                <form action="{{ route('aguaCaptacion.store', $visita->id) }}" method="POST">
                    @csrf

                    <div class="mb-2 form-check">
                        <input type="checkbox" name="permiso_concesion" id="permiso_concesion" class="form-check-input" value="1" {{ old('permiso_concesion') ? 'checked' : '' }}>
                        <label for="permiso_concesion" class="form-check-label">Cuenta con el permiso de concesión actualizado de acuerdo al volumen requerido</label>
                    </div>

                    <div class="mb-2 form-check">
                        <input type="checkbox" name="permiso_ocupacion_cauce" id="permiso_ocupacion_cauce" class="form-check-input" value="1" {{ old('permiso_ocupacion_cauce') ? 'checked' : '' }}>
                        <label for="permiso_ocupacion_cauce" class="form-check-label">Cuenta con el permiso de ocupación de cauce</label>
                    </div>

                    <div class="mb-2 form-check">
                        <input type="checkbox" name="permisos_captacion" id="permisos_captacion" class="form-check-input" value="1" {{ old('permisos_captacion') ? 'checked' : '' }}>
                        <label for="permisos_captacion" class="form-check-label">Cuenta con los permisos que autoricen la(s) captación(es)</label>
                    </div>

                    <div class="mb-2 form-check">
                        <input type="checkbox" name="registro_agua" id="registro_agua" class="form-check-input" value="1" {{ old('registro_agua') ? 'checked' : '' }}>
                        <label for="registro_agua" class="form-check-label">Cuenta con un registro de agua que evidencie el cumplimiento del volumen concesionado</label>
                    </div>

                    <div class="mb-2 form-check">
                        <input type="checkbox" name="cumple_manejo_construccion" id="cumple_manejo_construccion" class="form-check-input" value="1" {{ old('cumple_manejo_construccion') ? 'checked' : '' }}>
                        <label for="cumple_manejo_construccion" class="form-check-label">Cumple con la obligación de manejo, construcción y distribución requerida en el permiso</label>
                    </div>

                    <div class="mb-2 form-check">
                        <input type="checkbox" name="gestion_permiso_ocupacion" id="gestion_permiso_ocupacion" class="form-check-input" value="1" {{ old('gestion_permiso_ocupacion') ? 'checked' : '' }}>
                        <label for="gestion_permiso_ocupacion" class="form-check-label">Ha realizado la gestión para la obtención de permisos de ocupación de cauce</label>
                    </div>

                    <div class="mb-2 form-check">
                        <input type="checkbox" name="gestion_permiso_captacion" id="gestion_permiso_captacion" class="form-check-input" value="1" {{ old('gestion_permiso_captacion') ? 'checked' : '' }}>
                        <label for="gestion_permiso_captacion" class="form-check-label">Ha realizado la gestión para la obtención de permisos que autoricen la(s) captación(es)</label>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="3" class="form-control">{{ old('observaciones') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary">⬅️ Volver</a>
                        <button type="submit" class="btn btn-success">Guardar componente</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
@endsection

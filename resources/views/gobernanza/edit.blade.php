@extends('layouts.app')

@section('content')
<style>
    .container.offline-form-container {
        background-color: rgba(129,165,114,0.929);
        padding: 20px;
        border-radius: 12px;
    }
    .title { text-align:center; font-weight:bold; color:#fdffe5; text-shadow:-1px 0 #000, 0 1px #000; }
    .card-component { background:#f1f6f1; border-radius:10px; padding:18px; border:1px solid #d5e6d5; }
    .card-header-green {
        background: linear-gradient(45deg,#28a745,#20c997);
        color:white; padding:12px; border-radius:8px 8px 0 0; margin:-18px -18px 12px -18px; font-weight:700;
    }
</style>

<div class="container offline-form-container">

    <h3 class="title">Editar — Gobernanza Hídrica (Visita #{{ $visita->id }})</h3>

    <div class="card-component">
        <div class="card-header-green">Actualizar información</div>

        <form action="{{ route('gobernanza.update', $visita->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-2 form-check">
                <input type="checkbox" name="canales_comunicacion" class="form-check-input" id="cc"
                       {{ $g->canales_comunicacion ? 'checked' : '' }}>
                <label class="form-check-label" for="cc">Canales de comunicación establecidos</label>
            </div>

            <div class="mb-2 form-check">
                <input type="checkbox" name="identifica_actores_afectados" class="form-check-input" id="iaa"
                       {{ $g->identifica_actores_afectados ? 'checked' : '' }}>
                <label class="form-check-label" for="iaa">Actores potencialmente afectados identificados</label>
            </div>

            <div class="mb-2 form-check">
                <input type="checkbox" name="participa_actividades_gestion" class="form-check-input" id="pag"
                       {{ $g->participa_actividades_gestion ? 'checked' : '' }}>
                <label class="form-check-label" for="pag">Participa en actividades de gestión hídrica</label>
            </div>

            <div class="mb-3">
                <label class="fw-bold">Observaciones</label>
                <textarea name="observaciones" class="form-control" rows="3">{{ $g->observaciones }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('visitasAmbientales.show', $visita->id) }}" class="btn btn-secondary">Volver a la visita</a>
                <button type="submit" class="btn btn-success">Actualizar</button>
            </div>
        </form>
    </div>

</div>
@endsection

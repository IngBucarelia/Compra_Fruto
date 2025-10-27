@extends('layouts.app')

@section('content')

<style>
.container {
    background-color: rgba(129, 165, 114, 0.929);
    padding: 20px;
    border-radius: 10px;
}
.title {
    text-align: center;
    font-family: Arial Black;
    font-weight: bold;
    font-size: 30px;
    color: #fdffe5;
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
}
.firma-canvas {
    background-color: #fff;
    width: 100%;
    max-width: 300px;
    height: 150px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
#imagenesPreview img {
    width: 100%;
    height: auto;
    border-radius: 0.25rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.btn-success, .btn-primary, .btn-warning {
    border-radius: 10px;
    font-weight: bold;
}
</style>

<div class="container">
    <h3 class="title">🚚 Detalles del Envío #{{ $envio->id }}</h3>

    <div class="text-end mb-3">
        @if($envio->estado === 'pendiente')
            <form action="{{ route('envios.ejecutar', $envio->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning">🔄 Marcar como En Proceso</button>
            </form>
        @elseif($envio->estado === 'en_proceso')
            <form action="{{ route('envios.completar', $envio->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">✅ Marcar como Completado</button>
            </form>
        @endif
    </div>

    <h4>
        <br>Plantación: <span style="color: wheat">{{ $envio->plantacion->nombre ?? 'Sin nombre' }}</span>
        <br>Proveedor: <span style="color: wheat">{{ $envio->proveedor->proveedor_nombre ?? 'Sin proveedor' }}</span>
        <br>Técnico: <span style="color: wheat">{{ $envio->tecnico->name ?? 'Sin asignar' }}</span>
        <br>Estado: <span style="color: wheat">{{ ucfirst($envio->estado) }}</span>
        <br>Fecha envío: <span style="color: wheat">{{ optional($envio->fecha_envio)->format('d/m/Y') }}</span>
    </h4>

    <hr>

    {{-- Descripción --}}
    <div class="mb-3">
        <h5>📦 Descripción del envío</h5>
        <p style="color:#fff;">{{ $envio->descripcion_envio ?? 'Sin descripción' }}</p>
    </div>

    {{-- Galería de evidencias --}}
    <div class="mb-3">
        <h5>📸 Evidencias del envío</h5>
        <div id="imagenesPreview" class="row">
            @forelse($envio->evidencias as $e)
                <div class="col-md-4 mb-2">
                    <a href="{{ asset($e->archivo) }}" target="_blank">
                        <img src="{{ asset($e->archivo) }}" class="img-thumbnail">
                    </a>
                </div>
            @empty
                <p class="text-light">No hay evidencias registradas.</p>
            @endforelse
        </div>

        {{-- Input oculto que detecta nuevas fotos --}}
        <div class="d-flex gap-2 mt-2">
            <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('inputCamara').click()">📷 Tomar foto</button>
            <button type="button" class="btn btn-sm btn-secondary" onclick="document.getElementById('inputGaleria').click()">🖼️ Subir desde galería</button>
        </div>
        <input type="file" id="inputCamara" class="d-none" accept="image/*" capture="environment" multiple>
        <input type="file" id="inputGaleria" class="d-none" accept="image/*" multiple>
    </div>

    {{-- Firma final y comentarios --}}
    @if($envio->estado !== 'completado')
    <form id="finalizarEnvioForm" class="mt-4">
        @csrf
        <input type="hidden" name="envio_id" value="{{ $envio->id }}">

        <div class="mb-3">
            <label class="form-label text-light">📝 Comentarios finales</label>
            <textarea id="comentarios_finales" class="form-control" name="comentarios_finales" rows="3"></textarea>
        </div>

        <div class="mb-4">
            <h5>✍️ Firma del responsable del envío *</h5>
            <canvas id="firmaEnvioCanvas" class="firma-canvas border"></canvas>
            <div class="mt-2">
                <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature()">🧹 Limpiar</button>
            </div>
        </div>

        <button type="submit" class="btn btn-success">✅ Completar Envío</button>
    </form>
    @else
        <h5 class="mt-4 text-light">📝 Comentarios finales</h5>
        <p style="color:#fff;">{{ $envio->comentarios_finales ?? 'Sin comentarios.' }}</p>
        @if($envio->firma_envio)
            <h5 class="text-light">✍️ Firma registrada:</h5>
            <img src="{{ asset($envio->firma_envio) }}" style="max-width:300px; border:1px solid #ccc; padding:5px;">
        @endif
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const handleImageUpload = async (event) => {
        const files = event.target.files;
        const preview = document.getElementById('imagenesPreview');
        const envioId = {{ $envio->id }};
        const formData = new FormData();

        for (let file of files) {
            formData.append('archivos[]', file);
        }
        formData.append('_token', '{{ csrf_token() }}');

        try {
            const response = await fetch(`/envios/${envioId}/evidencias`, {
                method: 'POST',
                body: formData
            });

            // Obtener texto primero para evitar "Unexpected token <"
            const text = await response.text();
            let result;
            try {
                result = JSON.parse(text);
            } catch (e) {
                console.error('Respuesta no JSON:', text);
                Swal.fire('Error', 'Respuesta inesperada del servidor.', 'error');
                return;
            }

            if (result.success) {
                Swal.fire('✅ Éxito', result.message, 'success');

                // Mostrar las imágenes nuevas al instante
                result.urls.forEach(url => {
                    const img = document.createElement('img');
                    img.src = url;
                    img.className = 'img-thumbnail';
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-2';
                    col.appendChild(img);
                    preview.prepend(col);
                });
            } else {
                Swal.fire('⚠️ Atención', result.message, 'warning');
            }
        } catch (error) {
            console.error(error);
            Swal.fire('❌ Error', 'No se pudieron subir las imágenes.', 'error');
        }
    };

    document.getElementById('inputCamara').addEventListener('change', handleImageUpload);
    document.getElementById('inputGaleria').addEventListener('change', handleImageUpload);
});
</script>

@endsection

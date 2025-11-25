@extends('layouts.app')

@section('content')
<style>
      body{
        background-image: url('{{ asset('images/fondo_envios.png') }}'); 
    }
.firma-canvas { background:#fff; width:100%; max-width:400px; height:160px; border:1px solid #ccc; }
.img-thumb { border-radius:8px; box-shadow:0 0 6px rgba(0,0,0,0.12); width:100%; height:auto; }
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    .grid-3 { grid-template-columns: repeat(2,1fr);}

    .grid-3 { grid-template-columns: 1fr; }
    .btn-circle {
        width: 30px;
        height: 30px;
    }
    
    .table-responsive {
        font-size: 0.9em;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group .form-control {
        margin-bottom: 10px;
    }
     .container {
        margin-left: -70px;
        width: 120% !important;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }

        .card{
        width: 100%;
    }
}

</style>

<div class="container " style="background-color: #ccc; border-radius:25px">
    <h3>🚚 Detalles del Envío #{{ $envio->id }}</h3>

    <p><strong>Plantación:</strong> {{ $envio->plantacion->nombre ?? 'N/A' }}</p>
    <p><strong>Proveedor:</strong> {{ $envio->proveedor->proveedor_nombre ?? 'N/A' }}</p>
    <p><strong>Estado:</strong> {{ ucfirst($envio->estado) }}</p>

    <hr>

    <h5>📸 Evidencias</h5>
    <div id="imagenesPreview" class="row mb-2">
        @forelse($envio->evidencias as $e)
            <div class="col-md-4 mb-2">
                <a href="{{ asset(trim($e->archivo, '/')) }}" target="_blank">
                    <img src="{{ asset(trim($e->archivo, '/')) }}" class="img-thumb" alt="Evidencia">
                </a>
            </div>
        @empty
            <p class="text-muted">No hay evidencias registradas.</p>
        @endforelse
    </div>

    <div class="d-flex gap-2 mb-3">
        <button class="btn btn-sm btn-primary" onclick="document.getElementById('inputCamara').click()">📷 Tomar foto</button>
        <button class="btn btn-sm btn-secondary" onclick="document.getElementById('inputGaleria').click()">🖼️ Subir desde galería</button>
    </div>
    <input type="file" id="inputCamara" class="d-none" accept="image/*" capture="environment" multiple>
    <input type="file" id="inputGaleria" class="d-none" accept="image/*" multiple>

    <hr>

    @if($envio->estado !== 'completado')
    <div class="row">
        <div class="col-md-6">
            <h5>✍️ Firma - Entrega (quien entrega)</h5>
            <canvas id="firmaEntregaCanvas" class="firma-canvas"></canvas>
            <div class="mt-2 mb-3">
                <button type="button" class="btn btn-sm btn-secondary" onclick="clearEntrega()">Limpiar</button>
            </div>
        </div>

        <div class="col-md-6">
            <h5>✍️ Firma - Recibe (quien recibe)</h5>
            <canvas id="firmaRecibeCanvas" class="firma-canvas"></canvas>
            <div class="mt-2 mb-3">
                <button type="button" class="btn btn-sm btn-secondary" onclick="clearRecibe()">Limpiar</button>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <label>📝 Comentarios finales</label>
        <textarea id="comentarios_finales" class="form-control" rows="3"></textarea>
    </div>

    <div class="mt-3">
        <button id="btnCompletar" class="btn btn-success">✅ Completar Envío</button>
    </div>
    @else
        <h5>Firmas registradas</h5>
        <div class="row">
            <div class="col-md-6">
                <p>Firma Entrega:</p>
                @if($envio->firma_entrega)
                    <img src="{{ asset($envio->firma_entrega) }}" class="img-thumb">
                @else
                    <p>No registrada</p>
                @endif
            </div>
            <div class="col-md-6">
                <p>Firma Recibe:</p>
                @if($envio->firma_recibe)
                    <img src="{{ asset($envio->firma_recibe) }}" class="img-thumb">
                @else
                    <p>No registrada</p>
                @endif
            </div>
        </div>

        <h5 class="mt-3">Comentarios</h5>
        <p>{{ $envio->comentarios_finales ?? 'Sin comentarios' }}</p>
    @endif
    <a href="{{ route('envios.pdf', $envio->id) }}" target="_blank" class="btn btn-outline-success">
    <i class="fas fa-file-pdf"></i> Imprimir PDF
</a>

    <a href="{{ route('envios.index') }}" class="btn btn-outline-success me-3">
                    <i class="fas fa-arrow-left"></i> Terminar envio 
                </a>
</div> 


<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Canvas firmas
    const canvasEntrega = document.getElementById('firmaEntregaCanvas');
    const canvasRecibe = document.getElementById('firmaRecibeCanvas');
    const sigEntrega = new SignaturePad(canvasEntrega);
    const sigRecibe = new SignaturePad(canvasRecibe);

    function downloadDataUrlToFile(dataUrl, filename) {
        // no usado, helper si quieres descargar
    }

    window.clearEntrega = () => sigEntrega.clear();
    window.clearRecibe = () => sigRecibe.clear();

    // Manejo de subida de evidencias (AJAX)
    async function handleImageUpload(event) {
        const files = event.target.files;
        const envioId = {{ $envio->id }};
        const formData = new FormData();
        for (let f of files) formData.append('archivos[]', f);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            const res = await fetch(`/envios/${envioId}/evidencias`, { method: 'POST', body: formData });
            const json = await res.json();
            if (json.success) {
                Swal.fire('✅', json.message, 'success');
                const preview = document.getElementById('imagenesPreview');
                json.urls.forEach(url => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-2';
                    const a = document.createElement('a');
                    a.href = url; a.target = '_blank';
                    const img = document.createElement('img');
                    img.src = url; img.className = 'img-thumb';
                    a.appendChild(img);
                    col.appendChild(a);
                    preview.prepend(col);
                });
            } else {
                Swal.fire('⚠️', json.message, 'warning');
            }
        } catch (err) {
            console.error(err);
            Swal.fire('❌', 'Error subiendo imágenes', 'error');
        }
    }

    document.getElementById('inputCamara').addEventListener('change', handleImageUpload);
    document.getElementById('inputGaleria').addEventListener('change', handleImageUpload);

    // Completar: enviar ambas firmas + comentarios
    document.getElementById('btnCompletar').addEventListener('click', async function() {
        // si quieres al menos una firma obligatoria, valida aquí:
        if (sigEntrega.isEmpty() && sigRecibe.isEmpty()) {
            if (!confirm('No hay firmas. ¿Deseas continuar sin firmas?')) return;
        }

        const payload = {
            _token: '{{ csrf_token() }}',
            comentarios_finales: document.getElementById('comentarios_finales').value,
            firma_entrega: sigEntrega.isEmpty() ? null : sigEntrega.toDataURL(),
            firma_recibe: sigRecibe.isEmpty() ? null : sigRecibe.toDataURL()
        };

        try {
            const res = await fetch(`/envios/{{ $envio->id }}/completar`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            if (json.success) {
                Swal.fire('✅', json.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('❌', json.message || 'Error', 'error');
            }
        } catch (err) {
            console.error(err);
            Swal.fire('❌', 'Error al completar envío', 'error');
        }
    });
});
</script>

<style>
    /* Puedes pegar el contenido del bloque 'Estilos CSS para Formularios Responsivos (Reutilizable)' aquí
       si no lo tienes en un archivo CSS global enlazado en layouts/app.blade.php. */

    /* Estilos específicos para este formulario si los necesitas */
    .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
    }
    .offline-form-container h2.title {
        text-align: center;
        font-family: Arial Black;
        font-weight: bold;
        font-size: 30px;
        color: #fdffe5;
        text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    }
    .info-visita span {
        color: wheat;
    }
    .button-group-top {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 30px;
    }
    @media (max-width: 968px) {

         .container.offline-form-container {
        background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
    }
        .button-group-top {
            flex-direction: row;
            justify-content: flex-start;
        }

         .container.offline-form-container {
        padding: 15px;
            margin-top: 15px;
            border-radius: 0;
            box-shadow: none;
            width: 123%;
            max-width: none;
            margin-left: -60px !important;
    }

    .title{
    text-align: center;
    font-family: Arial Black;
    font-weight: bold;
    font-size: 30px;
    color: #fdffe5;
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    margin-bottom: 25px;
}

 .container {
        margin-left: -70px;
        width: 110%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }

        .card{
        width: 100%;
    }
    }

    .title{
    text-align: center;
    font-family: Arial Black;
    font-weight: bold;
    font-size: 30px;
    color: #fdffe5;
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    margin-bottom: 25px;
}

    /* Estilos para los formularios de área dinámicos */
    .area-form-card {
        background-color: #a5b8a5; /* Un color claro para las tarjetas de formulario */
        border: 1px solid #c3e6cb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        position: relative; /* Para el botón de eliminar */
    }
    .area-form-card .remove-area-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        font-size: 1.2em;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
    .area-form-card .remove-area-btn:hover {
        background-color: #c82333;
    }
    .area-form-card h4 {
        margin-bottom: 20px;
        color: #28a745;
        border-bottom: 1px dashed #729079;
        padding-bottom: 10px;
    }
</style>
@endsection

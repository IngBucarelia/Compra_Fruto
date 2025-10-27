@extends('layouts.app')

@section('content')

<style>
.container{
    background-color: #e8d5dce0;
    padding: 20px;
    border-radius: 10px;
}
.title{
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
</style>

<div class="container">
     <form action="{{ route('redireccion_seccion_social', $visita->id) }}" method="GET" class="mt-4">
            <label for="seccion" class="form-label fw-bold text-success">📋 Ir a sección:</label>
            <div class="input-group">
                <select id="seccion" name="seccion" class="form-select" required>
                    <option value="">Seleccione una sección</option>
                    @if ($visita->estado === 'pendiente' || $visita->estado === 'en_ejecucion')
                        <option value="inicio"> Pagina de Inicio de Visita</option>
                        <option value="datos_personales">👤 Datos Personales</option>
                        <option value="miembros">👨‍👩‍👧‍👦 Miembros del Hogar</option>
                        <option value="predio">🏡 Datos del Predio</option>
                        <option value="fuerza_laboral">🧑‍🌾 Fuerza Laboral</option>
                        <option value="organizacion_social">👥 Organización Social</option>
                        <option value="cierre_visita">✅ Cierre de Visita</option>
                    @endif
                </select>
                <button type="submit" class="btn btn-success">Ir</button>
            </div>
        </form><br><br>
    <h3 class="title">✍️ Cierre de Visita - Social ✍️</h3>
    <h5>
        📅 Fecha Visita: <span style="color: rgb(38, 81, 17)">{{ $visita->fecha }}</span><br>
        👤 Proveedor: <span style="color: rgb(38, 81, 17)">{{ $visita->proveedor->proveedor_nombre }}</span><br>
        🌴 Plantación: <span style="color: rgb(38, 81, 17)">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span>
    </h5>
   

    {{-- Formulario --}}
    <form id="cierreVisitaForm" class="mt-4">
        @csrf
        <input type="hidden" name="visita_social_id" value="{{ $visita->id }}">

        <div class="mb-3">
            <label for="fechaCierre" class="form-label">📅 Fecha de Cierre *</label>
            <input 
                type="date" 
                id="fechaCierre" 
                class="form-control" 
                name="fecha_cierre" 
                value="{{ date('Y-m-d') }}" 
                readonly
            />
            </div>
        <div class="mb-3">
            <label for="estadoVisita" class="form-label">📊 Estado de la Visita *</label>
            <select id="estadoVisita" class="form-control" name="estado_visita" required>
                <option value="">Seleccione</option>
                <option value="completado">Completado</option>
                <option value="pendiente">Pendiente</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="observacionesFinales" class="form-label">📝 Observaciones Finales</label>
            <textarea id="observacionesFinales" class="form-control" rows="3" name="observaciones_finales"></textarea>
        </div>

        <div class="mb-3">
            <label for="recomendaciones" class="form-label">💡 Recomendaciones</label>
            <textarea id="recomendaciones" class="form-control" rows="3" name="recomendaciones"></textarea>
        </div>

        {{-- FIRMAS --}}
        <div class="mb-4">
            <h5>✍️ Firma Responsable *</h5>
            <canvas id="firmaResponsableCanvas" class="firma-canvas"></canvas>
            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature('firmaResponsableCanvas')">🧹 Limpiar</button>
        </div>

        <div class="mb-4">
            <h5>✍️ Firma Recibe *</h5>
            <canvas id="firmaRecibeCanvas" class="firma-canvas"></canvas>
            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature('firmaRecibeCanvas')">🧹 Limpiar</button>
        </div>

        <div class="mb-4">
            <h5>✍️ Firma Testigo (opcional)</h5>
            <canvas id="firmaTestigoCanvas" class="firma-canvas"></canvas>
            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature('firmaTestigoCanvas')">🧹 Limpiar</button>
        </div>

        {{-- IMÁGENES --}}
        <div class="mb-3">
            <h5>📸 Fotos de la visita (opcional)</h5>
            <div class="d-flex gap-2 mb-2">
                <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('imagenesInput').click()">
                    📷 Tomar foto
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="document.getElementById('galeriaInput').click()">
                    🖼️ Seleccionar de galería
                </button>
            </div>
            <input type="file" id="imagenesInput" class="d-none" accept="image/*" capture="environment" multiple />
            <input type="file" id="galeriaInput" class="d-none" accept="image/*" multiple />
            <div id="imagenesPreview" class="row mt-3"></div>
        </div>

        <button type="submit" class="btn btn-success">✅ Finalizar visita</button>
    </form>

    <button type="button" class="btn btn-secondary mt-3" onclick="history.back()">Cancelar</button>
</div>

{{-- Librerías --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let firmaPadResponsable, firmaPadRecibe, firmaPadTestigo;
    let uploadedImagesBase64 = []; // Aquí guardamos imágenes en Base64

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar firmas
        firmaPadResponsable = new SignaturePad(document.getElementById('firmaResponsableCanvas'));
        firmaPadRecibe = new SignaturePad(document.getElementById('firmaRecibeCanvas'));
        firmaPadTestigo = new SignaturePad(document.getElementById('firmaTestigoCanvas'));

        // Manejo de imágenes
        const handleImageChange = function(event) {
            uploadedImagesBase64 = [];
            const files = event.target.files;
            const previewContainer = document.getElementById('imagenesPreview');
            previewContainer.innerHTML = '';

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    uploadedImagesBase64.push(e.target.result);
                    const colDiv = document.createElement('div');
                    colDiv.className = 'col-4 mb-3';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-thumbnail';
                    colDiv.appendChild(img);
                    previewContainer.appendChild(colDiv);
                };
                reader.readAsDataURL(file);
            }
        };

        document.getElementById('imagenesInput').addEventListener('change', handleImageChange);
        document.getElementById('galeriaInput').addEventListener('change', handleImageChange);

        // Envío formulario
        document.getElementById('cierreVisitaForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validaciones básicas
            if (firmaPadResponsable.isEmpty() || firmaPadRecibe.isEmpty()) {
                Swal.fire('Error', 'Las firmas del responsable y de quien recibe son obligatorias.', 'error');
                return;
            }
            if (!document.getElementById('fechaCierre').value || !document.getElementById('estadoVisita').value) {
                Swal.fire('Error', 'La Fecha de Cierre y el Estado de la Visita son obligatorios.', 'error');
                return;
            }

            Swal.fire({
                title: 'Guardando Cierre de Visita...',
                text: 'Por favor, espere.',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            const formData = new FormData(this); // Recoger los campos del form
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }

            // Añadir firmas
            data.firma_responsable = firmaPadResponsable.toDataURL();
            data.firma_recibe = firmaPadRecibe.toDataURL();
            data.firma_testigo = firmaPadTestigo.isEmpty() ? null : firmaPadTestigo.toDataURL();

            // Añadir imágenes
            data.imagenes = uploadedImagesBase64;

            // Añadir fecha finalizada
            data.finalizada_en = new Date().toISOString().slice(0, 10);

            try {
                const response = await fetch("{{ route('cierre-visitas-social.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire('Éxito', result.message || 'Visita social finalizada con éxito.', 'success').then(() => {
                        window.location.href = "{{ route('visitas_social.showSocial', $visita->id) }}";
                    });
                } else {
                    let errorMessage = 'Error al finalizar la visita.';
                    if (result.errors) {
                        errorMessage += '<br>' + Object.values(result.errors).flat().join('<br>');
                    } else if (result.message) {
                        errorMessage = result.message;
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }
            } catch (error) {
                console.error('Error al enviar el formulario:', error);
                Swal.fire('Error', 'Hubo un problema de conexión o servidor al finalizar la visita.', 'error');
            }
        });
    });

    function clearSignature(canvasId) {
        const canvas = document.getElementById(canvasId);
        if (canvas) {
            const signaturePadInstance = new SignaturePad(canvas);
            signaturePadInstance.clear();
        }
    }
</script>


@endsection

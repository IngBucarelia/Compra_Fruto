@extends('layouts.app')

@section('content')

<style>
.container{
    background-color: rgba(129, 165, 114, 0.929);
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
        width: 125%;
    

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


<div class="container">
    <h3 class="title">✍️ Información de plantación - Cierre de Visita ✍️</h3><h3><br><br>Fecha Visita: <span style="color: wheat">{{ $visita->fecha}}</span> <br> Proveedor:<span style="color: wheat"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario de Cierre de Visita --}}
    {{-- ✅ IMPORTANTE: Este formulario ahora se enviará vía JavaScript (fetch API) --}}
    <form id="cierreVisitaForm" class="mt-4">
        @csrf {{-- Laravel CSRF token --}}
        <input type="hidden" name="visita_id" value="{{ $visita->id }}">

        {{-- Nuevos campos de Cierre de Visita --}}
        <div class="mb-3">
            <label for="fechaCierre" class="form-label">📅 Fecha de Cierre *</label>
            <input type="date" id="fechaCierre" class="form-control" name="fecha_cierre" required />
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
            <label for="observacionesFinales" class="form-label">📝 Observaciones Finales (Opcional)</label>
            <textarea id="observacionesFinales" class="form-control" rows="3" name="observaciones_finales"></textarea>
        </div>

        <div class="mb-3">
            <label for="recomendaciones" class="form-label">💡 Recomendaciones (Opcional)</label>
            <textarea id="recomendaciones" class="form-control" rows="3" name="recomendaciones"></textarea>
        </div>

        {{-- Firma: Responsable de la visita (firma_responsable) --}}
        <div class="mb-4">
            <h5>✍️ Firma del responsable *</h5>
            <canvas id="firmaResponsableCanvas" class="firma-canvas border" width="300" height="150"></canvas>
            <div class="mt-2">
                <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature('firmaResponsableCanvas')">🧹 Limpiar</button>
            </div>
        </div>

        {{-- Firma: Quien recibe la visita (firma_recibe) --}}
        <div class="mb-4">
            <h5>✍️ Firma de quien recibe la visita *</h5>
            <canvas id="firmaRecibeCanvas" class="firma-canvas border" width="300" height="150"></canvas>
            <div class="mt-2">
                <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature('firmaRecibeCanvas')">🧹 Limpiar</button>
            </div>
        </div>

        {{-- Firma: Testigo (opcional) (firma_testigo) --}}
        <div class="mb-4">
            <h5>✍️ Firma del testigo (opcional)</h5>
            <canvas id="firmaTestigoCanvas" class="firma-canvas border" width="300" height="150"></canvas>
            <div class="mt-2">
                <button type="button" class="btn btn-sm btn-secondary" onclick="clearSignature('firmaTestigoCanvas')">🧹 Limpiar</button>
            </div>
        </div>

        {{-- Galería de imágenes --}}
        <div class="mb-3">
        <h5>📸 Fotos de la visita (opcional)</h5>
        <div class="d-flex gap-2 mb-2">
            <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('imagenesInput').click()">
                <i class="fas fa-camera"></i> Tomar foto
            </button>
            <button type="button" class="btn btn-sm btn-secondary" onclick="document.getElementById('galeriaInput').click()">
                <i class="fas fa-images"></i> Seleccionar de galería
            </button>
        </div>
        <!-- Input para cámara -->
        <input type="file" id="imagenesInput" class="d-none" accept="image/*" capture="environment" multiple />
        <!-- Input para galería -->
        <input type="file" id="galeriaInput" class="d-none" accept="image/*" multiple />
        <div id="imagenesPreview" class="row mt-3"></div>
    </div>

        <button type="submit" class="btn btn-success">✅ Finalizar visita</button>
    </form>

    <button type="button" class="btn btn-secondary mt-3" onclick="history.back()">Cancelar</button>
</div>

{{-- Script para SignaturePad y manejo de imágenes/envío --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let firmaPadResponsable, firmaPadRecibe, firmaPadTestigo;
    let uploadedImagesBase64 = []; // Almacenará las imágenes Base64

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar SignaturePads
        firmaPadResponsable = new SignaturePad(document.getElementById('firmaResponsableCanvas'));
        firmaPadRecibe = new SignaturePad(document.getElementById('firmaRecibeCanvas'));
        firmaPadTestigo = new SignaturePad(document.getElementById('firmaTestigoCanvas'));

        // Manejar la carga de imágenes
        const handleImageChange = function(event) {
            uploadedImagesBase64 = []; // Limpiar array al seleccionar nuevas imágenes
            const files = event.target.files;
            const previewContainer = document.getElementById('imagenesPreview');
            previewContainer.innerHTML = ''; // Limpiar previsualizaciones antiguas

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    uploadedImagesBase64.push(e.target.result); // Guardar Base64
                    // Previsualizar imagen
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

        // --- CORRECCIÓN APLICADA AQUÍ ---
        // Ahora ambos inputs, 'imagenesInput' (cámara) y 'galeriaInput' (galería),
        // tienen su propio listener para la misma función de manejo de imágenes.
        document.getElementById('imagenesInput').addEventListener('change', handleImageChange);
        document.getElementById('galeriaInput').addEventListener('change', handleImageChange);

        // Manejar el envío del formulario
        document.getElementById('cierreVisitaForm').addEventListener('submit', async function(e) {
            e.preventDefault(); // Prevenir el envío tradicional del formulario

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
                imageUrl: '{{ asset('images/loader.gif') }}', // Asegúrate de que esta ruta sea correcta
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false
            });

            const formData = new FormData(this); // Obtener datos de los campos de texto/select
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }

            // Añadir firmas Base64
            data.firma_responsable = firmaPadResponsable.toDataURL();
            data.firma_recibe = firmaPadRecibe.toDataURL();
            data.firma_testigo = firmaPadTestigo.isEmpty() ? null : firmaPadTestigo.toDataURL();

            // Añadir imágenes Base64
            data.imagenes = uploadedImagesBase64;

            // Añadir finalizada_en
            data.finalizada_en = new Date().toISOString().slice(0, 10); // Formato YYYY-MM-DD

            try {
                const response = await fetch('{{ route('cierre-visitas.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value // Incluir CSRF token
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire('Éxito', result.message || 'Visita finalizada con éxito.', 'success').then(() => {
                        window.location.href = '{{ route('visitas.show', $visita->id) }}'; // Redirigir
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

    // Función global para limpiar firmas
    function clearSignature(canvasId) {
        const canvas = document.getElementById(canvasId);
        if (canvas) {
            const signaturePadInstance = new SignaturePad(canvas); // Re-instanciar si no se guarda globalmente
            signaturePadInstance.clear();
        }
    }
</script>
@endsection

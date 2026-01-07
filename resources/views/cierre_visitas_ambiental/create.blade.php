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
    {{-- Selector de Secciones (similar al social) --}}
    <form action="{{ route('redireccion_componente_ambiental', $visita->id) }}" method="GET" class="mt-4">
        <label for="seccion" class="form-label fw-bold text-success">📋 Ir a sección:</label>
        <div class="input-group">
            <select id="seccion" name="seccion" class="form-select" required>
                <option value="">Seleccione una sección</option>
                @if ($visita->estado === 'en_proceso' || $visita->estado === 'en_ejecucion')
                    <option value="inicio">🏠 Inicio de Visita</option>
                    <option value="agua_captacion_legal">💧 Agua - Captación Legal</option>
                    <option value="agua_uso_eficiente">🚰 Agua - Uso Eficiente</option>
                    <option value="suelo_conservacion">🌱 Suelo - Conservación</option>
                    <option value="energia_manejo">⚡ Energía - Manejo</option>
                    <option value="gobernanza_hidrica">🤝 Gobernanza Hídrica</option>
                    <option value="emisiones_gei">🌍 Emisiones GEI</option>
                    <option value="residuos_manejo">🗑️ Residuos - Manejo</option>
                    <option value="sustancias_quimicas_biologicas">⚗️ Sustancias Químicas/Biológicas</option>
                    <option value="vertimiento_manejo">💦 Vertimiento - Manejo</option>
                    <option value="plantacion_hmp">🌳 Plantación HMP</option>
                    <option value="plantacion_avc">🦜 Plantación AVC</option>
                    <option value="plantacion_ecosistemas">🌿 Plantación Ecosistemas</option>
                    <option value="cierre_visita">✅ Cierre de Visita</option>
                @endif
            </select>
            <button type="submit" class="btn btn-success">Ir</button>
        </div>
    </form><br><br>
    
    <h3 class="title">🌍 Cierre de Visita - Ambiental 🌍</h3>
    <h5>
    📅 Fecha Visita: <span style="color: rgb(38, 81, 17)">{{ $visita->fecha }}</span><br>
    
    {{-- Verificar si proveedor existe antes de acceder a sus propiedades --}}
    @if($visita->proveedor)
        👤 Proveedor: <span style="color: rgb(38, 81, 17)">{{ $visita->proveedor->proveedor_nombre }}</span><br>
    @else
        👤 Proveedor: <span style="color: #dc3545;">No asignado</span><br>
    @endif
    
    {{-- Verificar si plantación existe --}}
    @if($visita->plantacion)
        🌴 Plantación: <span style="color: rgb(38, 81, 17)">{{ $visita->plantacion->nombre }}</span>
    @else
        🌴 Plantación: <span style="color: #dc3545;">No asignada</span>
    @endif
</h5>
    
    {{-- Resumen de Componentes Registrados --}}
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            📊 Componentes Ambientales Registrados
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li>{{ $visita->aguaCaptacionLegal ? '✅' : '❌' }} 💧 Agua - Captación Legal</li>
                        <li>{{ $visita->aguaUsoEficiente ? '✅' : '❌' }} 🚰 Agua - Uso Eficiente</li>
                        <li>{{ $visita->sueloConservacion ? '✅' : '❌' }} 🌱 Suelo - Conservación</li>
                        <li>{{ $visita->energiaManejo ? '✅' : '❌' }} ⚡ Energía - Manejo</li>
                        <li>{{ $visita->gobernanzaHidrica ? '✅' : '❌' }} 🤝 Gobernanza Hídrica</li>
                        <li>{{ $visita->emisionesGei ? '✅' : '❌' }} 🌍 Emisiones GEI</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li>{{ $visita->residuosManejo ? '✅' : '❌' }} 🗑️ Residuos - Manejo</li>
                        <li>{{ $visita->sustanciasQuimicasBiologicas ? '✅' : '❌' }} ⚗️ Sustancias Químicas/Biológicas</li>
                        <li>{{ $visita->vertimientoManejo ? '✅' : '❌' }} 💦 Vertimiento - Manejo</li>
                        <li>{{ $visita->plantacionHmp ? '✅' : '❌' }} 🌳 Plantación HMP</li>
                        <li>{{ $visita->plantacionAvc ? '✅' : '❌' }} 🦜 Plantación AVC</li>
                        <li>{{ $visita->plantacionEcosistema ? '✅' : '❌' }} 🌿 Plantación Ecosistemas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario de Cierre --}}
    <form id="cierreVisitaAmbientalForm" class="mt-4">
        @csrf
        <input type="hidden" name="visita_ambiental_id" value="{{ $visita->id }}">

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
                <option value="completada">Completado</option>
                <option value="pendiente">Pendiente</option>
                <option value="cancelado">Cancelado</option>
                <option value="en_proceso">Parcialmente Completado</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="observacionesFinales" class="form-label">📝 Observaciones Finales</label>
            <textarea id="observacionesFinales" class="form-control" rows="3" name="observaciones_finales"></textarea>
        </div>

        <div class="mb-3">
            <label for="recomendaciones" class="form-label">💡 Recomendaciones Ambientales</label>
            <textarea id="recomendaciones" class="form-control" rows="3" name="recomendaciones"></textarea>
        </div>

        {{-- FIRMAS --}}
        <div class="mb-4">
            <h5>✍️ Firma Responsable Ambiental *</h5>
            <canvas id="firmaResponsableCanvas" class="firma-canvas"></canvas>
            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature('firmaResponsableCanvas')">🧹 Limpiar</button>
        </div>

        <div class="mb-4">
            <h5>✍️ Firma Recibe (Proveedor) *</h5>
            <canvas id="firmaRecibeCanvas" class="firma-canvas"></canvas>
            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature('firmaRecibeCanvas')">🧹 Limpiar</button>
        </div>

        <div class="mb-4">
            <h5>✍️ Firma Testigo (opcional)</h5>
            <canvas id="firmaTestigoCanvas" class="firma-canvas"></canvas>
            <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature('firmaTestigoCanvas')">🧹 Limpiar</button>
        </div>

        {{-- IMÁGENES AMBIENTALES --}}
        <div class="mb-3">
            <h5>📸 Evidencias Fotográficas Ambientales (opcional)</h5>
            <small class="text-muted">Puede tomar fotos de infraestructuras, sistemas, prácticas ambientales, etc.</small>
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

        <div class="alert alert-warning">
            <strong>⚠️ Importante:</strong> Al finalizar la visita, el estado cambiará a "finalizada" y no se podrán realizar más modificaciones.
        </div>

        <button type="submit" class="btn btn-success">✅ Finalizar Visita Ambiental</button>
    </form>

    <button type="button" class="btn btn-secondary mt-3" onclick="history.back()">Cancelar</button>
</div>

{{-- Librerías --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let firmaPadResponsable, firmaPadRecibe, firmaPadTestigo;
    let uploadedImagesBase64 = [];

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
        document.getElementById('cierreVisitaAmbientalForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validaciones básicas
            if (firmaPadResponsable.isEmpty() || firmaPadRecibe.isEmpty()) {
                Swal.fire('Error', 'Las firmas del responsable ambiental y del proveedor son obligatorias.', 'error');
                return;
            }
            if (!document.getElementById('fechaCierre').value || !document.getElementById('estadoVisita').value) {
                Swal.fire('Error', 'La Fecha de Cierre y el Estado de la Visita son obligatorios.', 'error');
                return;
            }

            // Confirmación antes de finalizar
            Swal.fire({
                title: '¿Finalizar Visita Ambiental?',
                text: 'Esta acción marcará la visita como finalizada. ¿Desea continuar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, finalizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    enviarFormulario();
                }
            });
        });

        async function enviarFormulario() {
            Swal.fire({
                title: 'Finalizando Visita Ambiental...',
                text: 'Por favor, espere mientras se guarda la información.',
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            const formData = new FormData(document.getElementById('cierreVisitaAmbientalForm'));
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
                const response = await fetch("{{ route('cierre-visitas-ambiental.store') }}", {
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
                    Swal.fire({
                        title: '✅ Visita Finalizada',
                        text: result.message || 'Visita ambiental finalizada con éxito.',
                        icon: 'success',
                        confirmButtonText: 'Ver Visita'
                    }).then(() => {
                        window.location.href = "{{ route('visitasAmbientales.show', $visita->id) }}";
                    });
                } else {
                    let errorMessage = 'Error al finalizar la visita ambiental.';
                    if (result.errors) {
                        errorMessage += '<br>' + Object.values(result.errors).flat().join('<br>');
                    } else if (result.message) {
                        errorMessage = result.message;
                    }
                    Swal.fire('Error', errorMessage, 'error');
                }
            } catch (error) {
                console.error('Error al enviar el formulario:', error);
                Swal.fire('Error', 'Hubo un problema de conexión o servidor al finalizar la visita ambiental.', 'error');
            }
        }
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
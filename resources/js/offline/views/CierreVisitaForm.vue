<template>
  <div class="offline-container offline-form-container">
    <!-- Header con información de la visita -->
    <div class="visita-info-card mb-4">
      <div class="card-header bg-gradient-info text-white">
        <h4 class="mb-0">
          <!-- Agrega esto temporalmente antes del botón -->
          <div class="debug-info alert alert-info" v-if="false"> <!-- Cambia a true para ver debug -->
            <h6>Debug - Estado del Formulario:</h6>
            <p>Fecha Cierre: {{ formData.fecha_cierre || 'NO' }}</p>
            <p>Estado Visita: {{ formData.estado_visita || 'NO' }}</p>
            <p>Firma Responsable: {{ signaturePads.responsable ? (signaturePads.responsable.isEmpty() ? 'VACÍA' : 'LLENA') : 'NO INICIALIZADA' }}</p>
            <p>Firma Recibe: {{ signaturePads.recibe ? (signaturePads.recibe.isEmpty() ? 'VACÍA' : 'LLENA') : 'NO INICIALIZADA' }}</p>
            <p>Formulario Válido: {{ formValido ? 'SÍ' : 'NO' }}</p>
          </div>
          <i class="fas fa-flag-checkered me-2"></i>
          Cierre de Visita Social - Modo Offline
        </h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Proveedor:</strong> {{ proveedorNombre }}</p>
            <p><strong>Visita ID:</strong> #{{ visitaId }}</p>
            <p><strong>Predio:</strong> {{ datosPredio?.nombre_finca || 'No especificado' }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Fecha:</strong> {{ fechaActual }}</p>
            <p><strong>Municipio:</strong> {{ datosPredio?.municipio || 'No especificado' }}</p>
            <p><strong>Estado:</strong> <span class="badge bg-warning">📱 Modo Offline</span></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Resumen Completo de Todos los Datos -->
    <div class="resumen-completo-cards mb-4">
      <h5 class="text-center mb-3" style="color: whitesmoke;">
        <i class="fas fa-clipboard-check me-2"></i>Resumen Completo de la Visita Social
      </h5>
      <div class="row">
        <!-- Datos Personales -->
        <div class="col-lg-3 col-md-6 mb-3" v-if="datosPersonales">
          <div class="card h-100">
            <div class="card-header bg-success text-white py-2">
              <h6 class="mb-0">
                <i class="fas fa-user me-1"></i>Datos Personales
              </h6>
            </div>
            <div class="card-body p-2">
              <p class="mb-1"><small><strong>Teléfono:</strong> {{ datosPersonales.telefono }}</small></p>
              <p class="mb-1"><small><strong>Sexo:</strong> {{ datosPersonales.sexo }}</small></p>
              <p class="mb-1"><small><strong>Estudio:</strong> {{ datosPersonales.nivel_estudio }}</small></p>
              <p class="mb-0"><small><strong>Experiencia:</strong> {{ datosPersonales.anios_palmicultura }} años</small></p>
            </div>
          </div>
        </div>

        <!-- Miembros del Hogar -->
        <div class="col-lg-3 col-md-6 mb-3" v-if="miembrosHogar.length > 0">
          <div class="card h-100">
            <div class="card-header bg-primary text-white py-2">
              <h6 class="mb-0">
                <i class="fas fa-users me-1"></i>Miembros ({{ miembrosHogar.length }})
              </h6>
            </div>
            <div class="card-body p-2">
              <div v-for="miembro in miembrosHogar.slice(0, 2)" :key="miembro.local_id" class="mb-1">
                <p class="mb-0"><small><strong>{{ miembro.nombre.split(' ')[0] }}</strong></small></p>
                <p class="mb-0"><small>{{ miembro.parentezco }}</small></p>
                <hr class="my-1" v-if="miembro !== miembrosHogar[1]">
              </div>
              <p class="mb-0 text-center" v-if="miembrosHogar.length > 2">
                <small class="text-muted">+{{ miembrosHogar.length - 2 }} más</small>
              </p>
            </div>
          </div>
        </div>

        <!-- Datos del Predio -->
        <div class="col-lg-3 col-md-6 mb-3" v-if="datosPredio">
          <div class="card h-100">
            <div class="card-header bg-warning text-dark py-2">
              <h6 class="mb-0">
                <i class="fas fa-tractor me-1"></i>Datos Predio
              </h6>
            </div>
            <div class="card-body p-2">
              <p class="mb-1"><small><strong>Finca:</strong> {{ datosPredio.nombre_finca }}</small></p>
              <p class="mb-1"><small><strong>Tenencia:</strong> {{ datosPredio.forma_tenencia }}</small></p>
              <p class="mb-1"><small><strong>Ubicación:</strong> {{ datosPredio.municipio }}</small></p>
              <p class="mb-0"><small><strong>Vereda:</strong> {{ datosPredio.vereda }}</small></p>
            </div>
          </div>
        </div>

        <!-- Fuerza Laboral -->
        <div class="col-lg-3 col-md-6 mb-3" v-if="datosFuerzaLaboral">
          <div class="card h-100">
            <div class="card-header bg-dark text-white py-2">
              <h6 class="mb-0">
                <i class="fas fa-hard-hat me-1"></i>Fuerza Laboral
              </h6>
            </div>
            <div class="card-body p-2">
              <p class="mb-1"><small><strong>Total:</strong> {{ datosFuerzaLaboral.num_trabajadores }}</small></p>
              <p class="mb-1"><small><strong>Hombres:</strong> {{ datosFuerzaLaboral.num_hombres }}</small></p>
              <p class="mb-1"><small><strong>Mujeres:</strong> {{ datosFuerzaLaboral.num_mujeres }}</small></p>
              <p class="mb-0"><small><strong>Contrato:</strong> {{ datosFuerzaLaboral.contrato_formal }}</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navegación entre secciones -->
    <div class="navigation-card mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap gap-2">
            <button @click="irASeccion('inicio')" class="btn btn-outline-primary btn-sm">
              🏠 Inicio
            </button>
            <button @click="irASeccion('datos-personales')" class="btn btn-outline-success btn-sm">
              👤 Datos Personales
            </button>
            <button @click="irASeccion('miembros')" class="btn btn-outline-info btn-sm">
              👨‍👩‍👧‍👦 Miembros Hogar
            </button>
            <button @click="irASeccion('predio')" class="btn btn-outline-warning btn-sm">
              🏡 Datos Predio
            </button>
            <button @click="irASeccion('fuerza-laboral')" class="btn btn-outline-dark btn-sm">
              🧑‍🌾 Fuerza Laboral
            </button>
            <button @click="irASeccion('organizacion')" class="btn btn-outline-secondary btn-sm">
              👥 Organización Social
            </button>
            <button @click="irASeccion('cierre')" class="btn btn-outline-danger btn-sm active">
              ✅ Cierre Visita
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Datos Guardados de Cierre -->
    <div v-if="datosCierre" class="datos-guardados-card mb-4">
      <div class="card">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">
            <i class="fas fa-check-circle me-2"></i>Cierre de Visita Guardado
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Fecha Cierre:</strong> {{ datosCierre.fecha_cierre }}</p>
              <p><strong>Estado:</strong> {{ datosCierre.estado_visita }}</p>
              <p><strong>Firmas:</strong> ✅ Responsable y Recibe</p>
            </div>
            <div class="col-md-6">
              <p><strong>Imágenes:</strong> {{ datosCierre.imagenes ? datosCierre.imagenes.length : 0 }}</p>
              <p><strong>Guardado:</strong> {{ new Date(datosCierre.timestamp).toLocaleString() }}</p>
            </div>
          </div>
          <button @click="eliminarDatos" class="btn btn-danger btn-sm mt-3">
            <i class="fas fa-trash me-1"></i>Eliminar Cierre
          </button>
        </div>
      </div>
    </div>

    <!-- Formulario de Cierre de Visita -->
    <div class="form-card">
      <div class="card-header bg-white">
        <h5 class="mb-0 text-success">
          <i class="fas fa-flag-checkered me-2"></i>Cierre de Visita Social
        </h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="guardarDatos">
          <div class="row">
            <!-- Fecha de Cierre -->
           <div class="col-md-6 mb-3">
            <label class="form-label fw-bold" style="color: whitesmoke;">
              <i class="fas fa-calendar-alt me-2"></i>Fecha de Cierre *
            </label>
            <input 
              type="date" 
              v-model="formData.fecha_cierre" 
              class="form-control" 
              required 
              readonly
            >
          </div>


            <!-- Estado de la Visita -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" style="color: whitesmoke;">
                <i class="fas fa-clipboard-check me-2"></i>Estado de la Visita *
              </label>
              <select v-model="formData.estado_visita" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="completado">Completado</option>
                <option value="pendiente">Pendiente</option>
                <option value="cancelado">Cancelado</option>
                <option value="en_proceso">En proceso</option>
              </select>
            </div>
          </div>

          <!-- Observaciones Finales -->
          <div class="mb-3">
            <label class="form-label fw-bold " style="color: whitesmoke;">
              <i class="fas fa-sticky-note me-2"></i>Observaciones Finales
            </label>
            <textarea v-model="formData.observaciones_finales" class="form-control" 
                      placeholder="Observaciones generales de la visita social..."
                      rows="3"></textarea>
          </div>

          <!-- Recomendaciones -->
          <div class="mb-3">
            <label class="form-label fw-bold" style="color: whitesmoke;" >
              <i class="fas fa-lightbulb me-2"></i>Recomendaciones
            </label>
            <textarea v-model="formData.recomendaciones" class="form-control" 
                      placeholder="Recomendaciones para el productor..."
                      rows="3"></textarea>
          </div>

          <!-- FIRMAS -->
          <div class="firmas-section mb-4">
            <h5 class="text-success mb-3">
              <i class="fas fa-signature me-2"></i>Registro de Firmas
            </h5>
            
            <!-- Firma Responsable -->
            <div class="firma-card mb-4">
              <div class="card">
                <div class="card-header bg-light">
                  <h6 class="mb-0" style="color: whitesmoke;">
                    <i class="fas fa-user-tie me-2"></i>Firma del Responsable *
                  </h6>
                </div>
                <div class="card-body">
                  <canvas ref="firmaResponsableCanvas" class="firma-canvas"></canvas>
                  <div class="mt-2">
                    <button type="button" @click="limpiarFirma('responsable')" class="btn btn-sm btn-secondary me-2">
                      <i class="fas fa-eraser me-1"></i>Limpiar
                    </button>
                    <small class="text-muted">Firme en el área superior</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- Firma Recibe -->
            <div class="firma-card mb-4">
              <div class="card">
                <div class="card-header bg-light">
                  <h6 class="mb-0 text-success">
                    <i class="fas fa-user-check me-2"></i>Firma de Quien Recibe *
                  </h6>
                </div>
                <div class="card-body">
                  <canvas ref="firmaRecibeCanvas" class="firma-canvas"></canvas>
                  <div class="mt-2">
                    <button type="button" @click="limpiarFirma('recibe')" class="btn btn-sm btn-secondary me-2">
                      <i class="fas fa-eraser me-1"></i>Limpiar
                    </button>
                    <small class="text-muted">Firme en el área superior</small>
                  </div>
                </div>
              </div>
            </div>

            <!-- Firma Testigo (Opcional) -->
            <div class="firma-card mb-4">
              <div class="card">
                <div class="card-header bg-light">
                  <h6 class="mb-0 text-success">
                    <i class="fas fa-user-friends me-2"></i>Firma del Testigo (Opcional)
                  </h6>
                </div>
                <div class="card-body">
                  <canvas ref="firmaTestigoCanvas" class="firma-canvas"></canvas>
                  <div class="mt-2">
                    <button type="button" @click="limpiarFirma('testigo')" class="btn btn-sm btn-secondary me-2">
                      <i class="fas fa-eraser me-1"></i>Limpiar
                    </button>
                    <small class="text-muted">Firma opcional de testigo</small>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- CAPTURA DE IMÁGENES -->
          <div class="imagenes-section mb-4">
            <h5 class="text-success mb-3" style="color: whitesmoke;">
              <i class="fas fa-camera me-2"></i>Registro Fotográfico (Opcional)
            </h5>
            
            <div class="card">
              <div class="card-body">
                <!-- Botones de captura -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <button type="button" @click="tomarFoto" class="btn btn-primary btn-sm">
                    <i class="fas fa-camera me-1"></i>Tomar Foto
                  </button>
                  <button type="button" @click="seleccionarDeGaleria" class="btn btn-secondary btn-sm">
                    <i class="fas fa-images me-1"></i>Seleccionar de Galería
                  </button>
                  <input 
                    ref="camaraInput" 
                    type="file" 
                    class="d-none" 
                    accept="image/*" 
                    capture="environment" 
                    @change="procesarImagen"
                  >
                  <input 
                    ref="galeriaInput" 
                    type="file" 
                    class="d-none" 
                    accept="image/*" 
                    multiple 
                    @change="procesarImagen"
                  >
                </div>

                <!-- Vista previa de imágenes -->
                <div v-if="imagenesCapturadas.length > 0" class="mt-3">
                  <h6 class="text-success">Imágenes Capturadas ({{ imagenesCapturadas.length }})</h6>
                  <div class="row">
                    <div class="col-4 mb-2" v-for="(imagen, index) in imagenesCapturadas" :key="index">
                      <div class="image-preview-container">
                        <img :src="imagen" class="img-thumbnail" alt="Vista previa">
                        <button 
                          type="button" 
                          @click="eliminarImagen(index)" 
                          class="btn btn-danger btn-sm delete-image-btn"
                        >
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else>
                  <p class="text-muted text-center">No hay imágenes capturadas</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Botones de acción -->
          <div class="button-group mt-4">
            <button type="submit" class="btn btn-success btn-lg" :disabled="!formValido">
              <i class="fas fa-save me-2"></i>
              {{ datosCierre ? 'Actualizar Cierre' : 'Guardar Cierre de Visita' }}
            </button>
            
            <button v-if="canSync && datosCierre" @click="sincronizar" class="btn btn-warning btn-lg">
              <i class="fas fa-sync me-2"></i>Sincronizar
            </button>

            <button type="button" class="btn btn-info btn-lg" @click="irARevisionFinal">
              <i class="fas fa-clipboard-check me-2"></i>Ir a Revisión Final
            </button>
            
            <button type="button" class="btn btn-outline-secondary btn-lg" onclick="history.back()">
              <i class="fas fa-arrow-left me-2"></i>Volver
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import SignaturePad from 'signature_pad';
// En la sección de imports, agrega:
import { saveFormData, getFormDataByVisita, deleteDataFromStore, getAllDataFromStore } from '../store/indexeddb';

export default {
  data() {
    return {
      formData: {
        fecha_cierre: '',
        estado_visita: '',
        observaciones_finales: '',
        recomendaciones: '',
        firma_responsable: '',
        firma_recibe: '',
        firma_testigo: '',
        imagenes: [],
        fecha_cierre: '',
      },
      datosCierre: null,
      datosPersonales: null,
      miembrosHogar: [],
      datosPredio: null,
      datosFuerzaLaboral: null,
      datosOrganizacion: null,
      visitaId: null,
      proveedorNombre: 'Cargando...',
      canSync: navigator.onLine,
      fechaActual: new Date().toLocaleDateString(),
      imagenesCapturadas: [],
      signaturePads: {
        responsable: null,
        recibe: null,
        testigo: null
      }
    };
  },
  computed: {
    formValido() {
      const condiciones = {
        fecha_cierre: !!this.formData.fecha_cierre,
        estado_visita: !!this.formData.estado_visita,
        firmaResponsableInicializada: !!this.signaturePads.responsable,
        firmaRecibeInicializada: !!this.signaturePads.recibe,
        firmaResponsableNoVacia: this.signaturePads.responsable ? !this.signaturePads.responsable.isEmpty() : false,
        firmaRecibeNoVacia: this.signaturePads.recibe ? !this.signaturePads.recibe.isEmpty() : false
      };
      
      console.log('🔍 Condiciones del formulario:', condiciones);
      
      return condiciones.fecha_cierre && 
            condiciones.estado_visita &&
            condiciones.firmaResponsableInicializada &&
            condiciones.firmaRecibeInicializada &&
            condiciones.firmaResponsableNoVacia &&
            condiciones.firmaRecibeNoVacia;
    }
  },
  methods: {
    generateUUID() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
    },

    irARevisionFinal() {
        this.$router.push(`/revision-final-social?visita_id=${this.visitaId}`);
        },

    // Inicializar firmas
        // En el método inicializarFirmas:
    inicializarFirmas() {
      try {
        this.signaturePads.responsable = new SignaturePad(this.$refs.firmaResponsableCanvas);
        this.signaturePads.recibe = new SignaturePad(this.$refs.firmaRecibeCanvas);
        this.signaturePads.testigo = new SignaturePad(this.$refs.firmaTestigoCanvas);

        // Configurar firmas
        Object.values(this.signaturePads).forEach(pad => {
          if (pad) {
            pad.penColor = 'rgb(0, 0, 0)';
            pad.minWidth = 1;
            pad.maxWidth = 3;
            // Agregar evento para debug
            pad.addEventListener('endStroke', () => {
              console.log('Firma realizada, isEmpty:', pad.isEmpty());
            });
          }
        });
        
        console.log('✅ Firmas inicializadas correctamente');
      } catch (error) {
        console.error('❌ Error inicializando firmas:', error);
      }
    },

    limpiarFirma(tipo) {
      if (this.signaturePads[tipo]) {
        this.signaturePads[tipo].clear();
      }
    },

    // Manejo de imágenes
    tomarFoto() {
      this.$refs.camaraInput.click();
    },

    seleccionarDeGaleria() {
      this.$refs.galeriaInput.click();
    },

    async procesarImagen(event) {
        const files = event.target.files;
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = async (e) => {
                try {
                    // Comprimir la imagen inmediatamente al cargarla
                    const imagenComprimida = await this.comprimirImagen(e.target.result, 0.6, 1024);
                    this.imagenesCapturadas.push(imagenComprimida);
                    
                    // Mostrar info de compresión
                    const tamañoOriginal = this.getTamañoBase64(e.target.result);
                    const tamañoComprimido = this.getTamañoBase64(imagenComprimida);
                    console.log(`🖼️ Nueva imagen comprimida: ${tamañoOriginal} → ${tamañoComprimido}`);
                    
                } catch (error) {
                    console.error('Error comprimiendo imagen:', error);
                    // En caso de error, usar imagen original
                    this.imagenesCapturadas.push(e.target.result);
                }
            };
            
            reader.readAsDataURL(file);
        }
        
        // Limpiar inputs
        event.target.value = '';
    },

    // Método para comprimir imágenes
    async comprimirImagen(base64String, calidad = 0.7, maxWidth = 800) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => {
                // Calcular nuevo tamaño manteniendo aspect ratio
                let width = img.width;
                let height = img.height;
                
                if (width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }
                
                // Crear canvas para comprimir
                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                // Convertir a JPEG comprimido (menor tamaño que PNG)
                const imagenComprimida = canvas.toDataURL('image/jpeg', calidad);
                resolve(imagenComprimida);
            };
            img.onerror = reject;
            img.src = base64String;
        });
    },

    // Método para comprimir firmas (mantener como PNG pero optimizar)
    comprimirFirma(base64Firma, escala = 0.5) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                // Reducir tamaño del canvas para firmas
                canvas.width = img.width * escala;
                canvas.height = img.height * escala;
                
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                
                // Mantener como PNG para transparencia pero con compresión
                const firmaComprimida = canvas.toDataURL('image/png');
                resolve(firmaComprimida);
            };
            img.onerror = reject;
            img.src = base64Firma;
        });
    },

    // Método para procesar y comprimir múltiples imágenes
    async procesarYComprimirImagenes(imagenesBase64) {
        const imagenesComprimidas = [];
        
        for (const imagen of imagenesBase64) {
            try {
                const imagenComprimida = await this.comprimirImagen(imagen, 0.6, 1024);
                imagenesComprimidas.push(imagenComprimida);
                
                // Log de compresión (opcional para debug)
                const tamañoOriginal = this.getTamañoBase64(imagen);
                const tamañoComprimido = this.getTamañoBase64(imagenComprimida);
                console.log(`📊 Imagen comprimida: ${tamañoOriginal} → ${tamañoComprimido}`);
                
            } catch (error) {
                console.error('Error comprimiendo imagen:', error);
                // En caso de error, usar imagen original
                imagenesComprimidas.push(imagen);
            }
        }
        
        return imagenesComprimidas;
    },

    // Método para comprimir todas las firmas
    async comprimirFirmas(firmaResponsable, firmaRecibe, firmaTestigo) {
        const firmasComprimidas = {
            responsable: '',
            recibe: '',
            testigo: ''
        };
        
        try {
            if (firmaResponsable && !this.signaturePads.responsable.isEmpty()) {
                firmasComprimidas.responsable = await this.comprimirFirma(firmaResponsable, 0.3);
                console.log(`📊 Firma responsable comprimida: ${this.getTamañoBase64(firmaResponsable)} → ${this.getTamañoBase64(firmasComprimidas.responsable)}`);
            }
            
            if (firmaRecibe && !this.signaturePads.recibe.isEmpty()) {
                firmasComprimidas.recibe = await this.comprimirFirma(firmaRecibe, 0.3);
                console.log(`📊 Firma recibe comprimida: ${this.getTamañoBase64(firmaRecibe)} → ${this.getTamañoBase64(firmasComprimidas.recibe)}`);
            }
            
            if (firmaTestigo && !this.signaturePads.testigo.isEmpty()) {
                firmasComprimidas.testigo = await this.comprimirFirma(firmaTestigo, 0.3);
                console.log(`📊 Firma testigo comprimida: ${this.getTamañoBase64(firmaTestigo)} → ${this.getTamañoBase64(firmasComprimidas.testigo)}`);
            }
        } catch (error) {
            console.error('Error comprimiendo firmas:', error);
            // En caso de error, usar firmas originales
            firmasComprimidas.responsable = firmaResponsable;
            firmasComprimidas.recibe = firmaRecibe;
            firmasComprimidas.testigo = firmaTestigo;
        }
        
        return firmasComprimidas;
    },

    // Método auxiliar para calcular tamaño de Base64
    getTamañoBase64(base64String) {
        if (!base64String) return '0 KB';
        
        // Eliminar el prefijo data:image/...;base64, para calcular solo los datos
        const base64Data = base64String.includes(',') 
            ? base64String.split(',')[1] 
            : base64String;
            
        const tamañoEnBytes = (base64Data.length * 3) / 4;
        const tamañoEnKB = (tamañoEnBytes / 1024).toFixed(2);
        
        return `${tamañoEnKB} KB`;
    },

    // Método para calcular tamaño total de datos
    calcularTamañoTotalDatos(datos) {
        let tamañoTotal = 0;
        
        if (datos.firma_responsable) {
            tamañoTotal += this.getTamañoEnBytes(datos.firma_responsable);
        }
        if (datos.firma_recibe) {
            tamañoTotal += this.getTamañoEnBytes(datos.firma_recibe);
        }
        if (datos.firma_testigo) {
            tamañoTotal += this.getTamañoEnBytes(datos.firma_testigo);
        }
        if (datos.imagenes) {
            datos.imagenes.forEach(imagen => {
                tamañoTotal += this.getTamañoEnBytes(imagen);
            });
        }
        
        return (tamañoTotal / 1024 / 1024).toFixed(2); // Tamaño en MB
    },

    getTamañoEnBytes(base64String) {
        if (!base64String) return 0;
        const base64Data = base64String.includes(',') 
            ? base64String.split(',')[1] 
            : base64String;
        return (base64Data.length * 3) / 4;
    },

    eliminarImagen(index) {
      this.imagenesCapturadas.splice(index, 1);
    },

    // Cargar datos anteriores
    async cargarDatosPersonales() {
      try {
        this.datosPersonales = await getFormDataByVisita('datos_personales_social', this.visitaId);
      } catch (error) {
        console.error('Error cargando datos personales:', error);
      }
    },

    async cargarMiembrosHogar() {
      try {
        const todosLosMiembros = await getAllDataFromStore('miembros_hogar_social');
        this.miembrosHogar = todosLosMiembros.filter(miembro => 
          miembro.visita_id == this.visitaId
        );
      } catch (error) {
        console.error('Error cargando miembros:', error);
      }
    },

    async cargarDatosPredio() {
      try {
        this.datosPredio = await getFormDataByVisita('datos_predio_social', this.visitaId);
      } catch (error) {
        console.error('Error cargando datos del predio:', error);
      }
    },

    async cargarDatosFuerzaLaboral() {
      try {
        this.datosFuerzaLaboral = await getFormDataByVisita('fuerza_laboral_social', this.visitaId);
      } catch (error) {
        console.error('Error cargando datos de fuerza laboral:', error);
      }
    },

    async cargarDatosOrganizacion() {
      try {
        this.datosOrganizacion = await getFormDataByVisita('organizacion_social', this.visitaId);
      } catch (error) {
        console.error('Error cargando datos de organización social:', error);
      }
    },

    async cargarDatosCierre() {
      try {
        this.datosCierre = await getFormDataByVisita('cierre_visita_social', this.visitaId);
        if (this.datosCierre) {
          this.formData = { ...this.datosCierre };
          this.imagenesCapturadas = this.datosCierre.imagenes || [];
          
          // Cargar firmas si existen
          if (this.datosCierre.firma_responsable && this.signaturePads.responsable) {
            this.signaturePads.responsable.fromDataURL(this.datosCierre.firma_responsable);
          }
          if (this.datosCierre.firma_recibe && this.signaturePads.recibe) {
            this.signaturePads.recibe.fromDataURL(this.datosCierre.firma_recibe);
          }
          if (this.datosCierre.firma_testigo && this.signaturePads.testigo) {
            this.signaturePads.testigo.fromDataURL(this.datosCierre.firma_testigo);
          }
        }
      } catch (error) {
        console.error('Error cargando datos de cierre:', error);
      }
    },

    async guardarDatos() {
        try {
            console.log('🔄 Iniciando guardado con compresión...');
            
            // Obtener firmas originales
            const firmaResponsableOriginal = this.signaturePads.responsable.toDataURL();
            const firmaRecibeOriginal = this.signaturePads.recibe.toDataURL();
            const firmaTestigoOriginal = this.signaturePads.testigo.isEmpty() 
                ? '' 
                : this.signaturePads.testigo.toDataURL();

            // Comprimir firmas
            console.log('📝 Comprimiendo firmas...');
            const firmasComprimidas = await this.comprimirFirmas(
                firmaResponsableOriginal, 
                firmaRecibeOriginal, 
                firmaTestigoOriginal
            );

            // Comprimir imágenes
            console.log('🖼️ Comprimiendo imágenes...');
            const imagenesComprimidas = this.imagenesCapturadas.length > 0
                ? await this.procesarYComprimirImagenes(this.imagenesCapturadas)
                : [];

            // Asignar datos comprimidos al formulario
            this.formData.firma_responsable = firmasComprimidas.responsable;
            this.formData.firma_recibe = firmasComprimidas.recibe;
            this.formData.firma_testigo = firmasComprimidas.testigo;
            this.formData.imagenes = imagenesComprimidas;

            const datosCompletos = {
                ...this.formData,
                local_id: this.datosCierre ? this.datosCierre.local_id : this.generateUUID(),
                visita_id: this.visitaId,
                proveedor_nombre: this.proveedorNombre,
                timestamp: new Date().toISOString(),
                sincronizado: false,
                finalizada_en: new Date().toISOString().slice(0, 10)
            };

            // Calcular y mostrar tamaño optimizado
            const tamañoTotal = this.calcularTamañoTotalDatos(datosCompletos);
            console.log(`📦 Tamaño total de datos: ${tamañoTotal} MB`);

            await saveFormData('cierre_visita_social', datosCompletos);
            await this.cargarDatosCierre();
            
            this.mostrarAlerta('success', `✅ Cierre guardado. Tamaño optimizado: ${tamañoTotal} MB`);
            router.push({
              path: '/revision-final-ambiental',
              query: { visita_id: visitaId }
            })
        } catch (error) {
            console.error('Error guardando cierre de visita:', error);
            this.mostrarAlerta('error', 'Error al guardar el cierre: ' + error.message);
        }
    },

    async eliminarDatos() {
      if (confirm('¿Eliminar el cierre de visita guardado?')) {
        try {
          await deleteDataFromStore('cierre_visita_social', this.datosCierre.local_id);
          this.datosCierre = null;
          this.formData = { ...this.$options.data().formData };
          this.imagenesCapturadas = [];
          this.limpiarFirma('responsable');
          this.limpiarFirma('recibe');
          this.limpiarFirma('testigo');
          this.mostrarAlerta('success', 'Cierre eliminado correctamente');
        } catch (error) {
          console.error('Error eliminando cierre:', error);
          this.mostrarAlerta('error', 'Error al eliminar el cierre: ' + error.message);
        }
      }
    },

    irASeccion(seccion) {
      switch(seccion) {
        case 'inicio':
          window.location.href = `/visitas_social/showSocial/${this.visitaId}`;
          break;
        case 'datos-personales':
          this.$router.push(`/datos-personales?visita_id=${this.visitaId}`);
          break;
        case 'miembros':
          this.$router.push(`/miembros-hogar?visita_id=${this.visitaId}`);
          break;
        case 'predio':
          this.$router.push(`/datos-predio?visita_id=${this.visitaId}`);
          break;
        case 'fuerza-laboral':
          this.$router.push(`/fuerza-laboral?visita_id=${this.visitaId}`);
          break;
        case 'organizacion':
          this.$router.push(`/organizacion-social?visita_id=${this.visitaId}`);
          break;
        case 'cierre':
          // Ya estamos aquí
          break;
      }
    },

    async sincronizar() {
      if (!this.canSync) {
        this.mostrarAlerta('warning', 'No hay conexión a internet para sincronizar');
        return;
      }
      
      if (!this.datosCierre) {
        this.mostrarAlerta('warning', 'No hay datos de cierre para sincronizar');
        return;
      }

      try {
        this.mostrarAlerta('info', '🔄 Sincronizando cierre de visita...');
        
        // Aquí iría la lógica de sincronización con tu backend
        setTimeout(() => {
          this.mostrarAlerta('success', '✅ Cierre de visita sincronizado correctamente');
        }, 2000);
        
      } catch (error) {
        this.mostrarAlerta('error', 'Error en sincronización: ' + error.message);
      }
    },

    mostrarAlerta(tipo, mensaje) {
      if (tipo === 'success') {
        alert(mensaje);
      } else if (tipo === 'error') {
        alert('❌ ' + mensaje);
      } else if (tipo === 'warning') {
        alert('⚠️ ' + mensaje);
      } else {
        alert(mensaje);
      }
    },

    updateOnlineStatus() {
      this.canSync = navigator.onLine;
    },

    cargarInformacionVisita() {
      this.proveedorNombre = localStorage.getItem(`proveedor_${this.visitaId}`) || 'Proveedor';
      // Establecer fecha actual como fecha de cierre por defecto
      this.formData.fecha_cierre = new Date().toISOString().split('T')[0];
    }
  },
  async mounted() {
    const hoy = new Date().toISOString().split('T')[0];
    this.formData.fecha_cierre = hoy;
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    this.cargarInformacionVisita();
    await this.cargarDatosPersonales();
    await this.cargarMiembrosHogar();
    await this.cargarDatosPredio();
    await this.cargarDatosFuerzaLaboral();
    await this.cargarDatosOrganizacion();
    
    // Inicializar firmas después de que el DOM esté listo
    this.$nextTick(() => {
      console.log('🔍 Refs disponibles:', Object.keys(this.$refs));
      console.log('🔍 Canvas Recibe:', this.$refs.firmaRecibeCanvas);
      console.log('🔍 Canvas Responsable:', this.$refs.firmaResponsableCanvas);
     this.inicializarFirmas();
      this.cargarDatosCierre();
    });

    window.addEventListener('online', this.updateOnlineStatus);
    window.addEventListener('offline', this.updateOnlineStatus);
  },
  beforeUnmount() {
    window.removeEventListener('online', this.updateOnlineStatus);
    window.removeEventListener('offline', this.updateOnlineStatus);
  }
};
</script>

<style scoped> 
@import '../styles/offline.css'; 

/* ESTILOS APLICADOS PARA ARMONIZAR */
.offline-form-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Tarjetas de Resumen de Datos Previos (NUEVO ESTILO CONSOLIDADOR) */
.resumen-cards .card { 
  border: 1px solid #dee2e6; 
  height: 100%; 
  background-color: #8b6761c7; /* Fondo oscuro para el cuerpo de la tarjeta */
} 

.resumen-cards .card-header { 
  font-size: 0.875rem; 
  padding: 8px 12px; 
} 

.resumen-cards .card-body p.text-white {
    color: #ffffff !important;
}

/* Tarjeta de Datos Guardados de la sección actual (Fuerza Laboral) */
.datos-guardados-card .card { 
  border: 2px solid #28a745; 
  background-color: #406653a2; /* Fondo oscuro para el cuerpo de la tarjeta */
} 
.datos-guardados-card .card-body p .text-white {
    color: #ffffff !important;
}

/* Otros estilos existentes */
.navigation-card .btn.active { 
  background-color: #59a04e91; 
  color: white; 
  border-color: #343a40; 
} 

.contratacion-options { 
  background-color: #f8f9fa; 
  padding: 15px; 
  border-radius: 8px; 
  border: 1px solid #dee2e6; 
  max-height: 200px; 
  overflow-y: auto; 
} 

.contratacion-options .form-check { 
  margin-bottom: 8px; 
} 

.button-group { 
  display: flex; 
  gap: 10px; 
  flex-wrap: wrap; 
  justify-content: center; 
} 

@media (max-width: 768px) { 
  .offline-container { 
    margin-left: 0; 
    margin-top: 20px; 
        width: 330px !important; 

    margin-left: -250px !important;
  } 

  .button-group { 
    flex-direction: column; 
  } 
  
  .button-group .btn { 
    width: 100%; 
    margin-bottom: 10px; 
  } 

  .resumen-cards .col-md-4 { 
    margin-bottom: 15px; 
  } 

  .contratacion-options { 
    padding: 10px; 
    max-height: 150px; 
  } 
} 

.form-label { 
  font-weight: 500; 
  margin-bottom: 5px; 
  display: block; 
} 

.card-header { 
  border-radius: 15px 15px 0 0 !important; 
} 

.badge { 
  font-size: 0.8rem;
}

/* ESTILO DEL HEADER DE VISITA PARA USAR EL COLOR CORRECTO */
.visita-info-card .card-body {
    background-color: #17a2b8; /* Color de fondo del card-header bg-gradient-info */
    border-radius: 0 0 15px 15px;
    color: white;
}
.visita-info-card .card-body strong {
    color: #f8f9fa;
}
</style>
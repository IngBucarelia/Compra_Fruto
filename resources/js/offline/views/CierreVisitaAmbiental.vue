<template>
    <div class="offline-container offline-form-container" style="background-color: aliceblue;margin-top: 30px;border-radius: 25px;padding: 15px;">
  <div class="form-card">
    <div class="card-header bg-white">
      <h5 class="mb-0 text-success">
        <i class="fas fa-leaf me-2"></i>Cierre de Visita Ambiental
      </h5>
    </div>

    <div class="card-body">
      <form @submit.prevent="guardarDatos">

        <div class="row">
          <!-- Fecha -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">
              <i class="fas fa-calendar-alt me-2"></i>Fecha de Cierre *
            </label>
            <input
              type="date"
              v-model="formData.fecha_cierre"
              class="form-control"
              readonly
              required
            />
          </div>

          <!-- Estado -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold">
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

        <!-- Observaciones -->
        <div class="mb-3">
          <label class="form-label fw-bold">
            <i class="fas fa-sticky-note me-2"></i>Observaciones Ambientales
          </label>
          <textarea
            v-model="formData.observaciones_finales"
            class="form-control"
            rows="3"
            placeholder="Conclusiones generales de la evaluación ambiental..."
          ></textarea>
        </div>

        <!-- Recomendaciones -->
        <div class="mb-4">
          <label class="form-label fw-bold">
            <i class="fas fa-lightbulb me-2"></i>Recomendaciones Ambientales
          </label>
          <textarea
            v-model="formData.recomendaciones"
            class="form-control"
            rows="3"
            placeholder="Recomendaciones ambientales para el productor..."
          ></textarea>
        </div>

        <!-- FIRMAS -->
        <div class="firmas-section mb-4">
          <h5 class="text-success mb-3">
            <i class="fas fa-signature me-2"></i>Firmas de Cierre
          </h5>

          <!-- Responsable -->
          <div class="firma-card mb-4">
            <div class="card">
              <div class="card-header bg-light">
                <h6 class="mb-0">
                  <i class="fas fa-user-tie me-2"></i>Firma Responsable Ambiental *
                </h6>
              </div>
              <div class="card-body">
                <canvas ref="firmaResponsableCanvas" class="firma-canvas"></canvas>
                <button type="button" class="btn btn-sm btn-secondary mt-2"
                        @click="limpiarFirma('responsable')">
                  Limpiar
                </button>
              </div>
            </div>
          </div>

          <!-- Recibe -->
          <div class="firma-card mb-4">
            <div class="card">
              <div class="card-header bg-light">
                <h6 class="mb-0">
                  <i class="fas fa-user-check me-2"></i>Firma Productor *
                </h6>
              </div>
              <div class="card-body">
                <canvas ref="firmaRecibeCanvas" class="firma-canvas"></canvas>
                <button type="button" class="btn btn-sm btn-secondary mt-2"
                        @click="limpiarFirma('recibe')">
                  Limpiar
                </button>
              </div>
            </div>
          </div>

          <!-- Testigo -->
          <div class="firma-card mb-4">
            <div class="card">
              <div class="card-header bg-light">
                <h6 class="mb-0">
                  <i class="fas fa-user-friends me-2"></i>Firma Testigo (Opcional)
                </h6>
              </div>
              <div class="card-body">
                <canvas ref="firmaTestigoCanvas" class="firma-canvas"></canvas>
                <button type="button" class="btn btn-sm btn-secondary mt-2"
                        @click="limpiarFirma('testigo')">
                  Limpiar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- IMÁGENES -->
        <div class="imagenes-section mb-4">
          <h5 class="text-success mb-3">
            <i class="fas fa-camera me-2"></i>Registro Fotográfico Ambiental
          </h5>

          <button type="button" class="btn btn-primary btn-sm me-2" @click="tomarFoto">
            Tomar Foto
          </button>
          <button type="button" class="btn btn-secondary btn-sm" @click="seleccionarDeGaleria">
            Galería
          </button>

          <input ref="camaraInput" type="file" class="d-none" accept="image/*" capture @change="procesarImagen">
          <input ref="galeriaInput" type="file" class="d-none" accept="image/*" multiple @change="procesarImagen">

          <div class="row mt-3" v-if="imagenesCapturadas.length">
            <div class="col-4 mb-2" v-for="(img, i) in imagenesCapturadas" :key="i">
              <img :src="img" class="img-thumbnail">
            </div>
          </div>
        </div>

        <!-- ACCIONES -->
        <div class="button-group mt-4">
          <button class="btn btn-success btn-lg" :disabled="!formValido">
            <i class="fas fa-save me-2"></i>Guardar Cierre Ambiental
          </button>

          <button v-if="canSync && datosCierre"
                  type="button"
                  class="btn btn-warning btn-lg"
                  @click="sincronizar">
            <i class="fas fa-sync me-2"></i>Sincronizar
          </button>

          <button type="button"
                  class="btn btn-outline-secondary btn-lg"
                  @click="history.back()">
            Volver
          </button>
        </div>

      </form>
    </div>
  </div>
  </div>
</template>
<script>
    import SignaturePad from 'signature_pad';
import {
  saveFormData,
  getFormDataByVisita
} from '../store/indexeddb';

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
        imagenes: []
      },
      visitaId: null,
      datosCierre: null,
      imagenesCapturadas: [],
      canSync: navigator.onLine,
      signaturePads: {
        responsable: null,
        recibe: null,
        testigo: null
      }
    };
  },

  computed: {
    formValido() {
      return (
        this.formData.fecha_cierre &&
        this.formData.estado_visita &&
        this.signaturePads.responsable &&
        this.signaturePads.recibe &&
        !this.signaturePads.responsable.isEmpty() &&
        !this.signaturePads.recibe.isEmpty()
      );
    }
  },

  methods: {
   async guardarDatos() {
        try {
            const datos = {
            ...this.formData,
            local_id: crypto.randomUUID(),
            visita_id: this.visitaId,
            firma_responsable: this.signaturePads.responsable.toDataURL(),
            firma_recibe: this.signaturePads.recibe.toDataURL(),
            firma_testigo: this.signaturePads.testigo.isEmpty()
                ? ''
                : this.signaturePads.testigo.toDataURL(),
            imagenes: this.imagenesCapturadas,
            sincronizado: false,
            timestamp: new Date().toISOString()
            };

            await saveFormData('cierre_visita_ambiental', datos);

            alert('✅ Cierre ambiental guardado offline');

            // ✅ REDIRECCIÓN CORRECTA OFFLINE
            this.$router.push({
            path: '/revision-final-ambiental',
            query: { visita_id: this.visitaId }
            });

        } catch (e) {
            console.error(e);
            alert('❌ Error guardando el cierre ambiental');
        }
        },



    sincronizar() {
      alert('🔄 Sincronización ambiental pendiente de backend');
    },

    limpiarFirma(tipo) {
      this.signaturePads[tipo]?.clear();
    },

    inicializarFirmas() {
      this.signaturePads.responsable = new SignaturePad(this.$refs.firmaResponsableCanvas);
      this.signaturePads.recibe = new SignaturePad(this.$refs.firmaRecibeCanvas);
      this.signaturePads.testigo = new SignaturePad(this.$refs.firmaTestigoCanvas);
    },

    tomarFoto() {
      this.$refs.camaraInput.click();
    },

    seleccionarDeGaleria() {
      this.$refs.galeriaInput.click();
    },

    procesarImagen(e) {
      [...e.target.files].forEach(file => {
        const reader = new FileReader();
        reader.onload = ev => this.imagenesCapturadas.push(ev.target.result);
        reader.readAsDataURL(file);
      });
      e.target.value = '';
    }
  },

  mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id');
    this.formData.fecha_cierre = new Date().toISOString().split('T')[0];

    this.$nextTick(() => {
      this.inicializarFirmas();
    });
  }
};

</script>

<style scoped>
.container { background: rgba(129,165,114,.93); padding:20px; border-radius:12px; }
.title { color:#fff; text-align:center; margin-bottom:20px; }
.card-component { background:#f1f6f1; padding:18px; border-radius:10px; }
.card-header-green { background:#28a745; color:#fff; padding:10px; font-weight:bold; }
.acciones { display:flex; justify-content:space-between; margin-top:12px; }

/* RESPONSIVE */

@media (max-width: 768px) {
  .offline-container{
    margin-left: -240px;
    margin-top: 65PX;
  }

  .button-group {
    flex-direction: column;
  }
  
  .button-group .btn {
    width: 100%;
    margin-bottom: 5px;
  }
}
</style>
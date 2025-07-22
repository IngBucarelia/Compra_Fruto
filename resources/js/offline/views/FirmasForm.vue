<template>
  <div class="offline-container" >
    <h2>🖊️ Registro de Firmas y Cierre de Visita (Modo Offline)</h2>

    <!-- Campos para Cierre de Visita -->
    <div class="form-group">
      <label for="fechaCierre" class="form-label">📅 Fecha de Cierre *</label>
      <input type="date" id="fechaCierre" class="form-control" v-model="cierreVisita.fecha_cierre" required />
    </div>

    <div class="form-group">
      <label for="estadoVisita" class="form-label">📊 Estado de la Visita *</label>
      <select id="estadoVisita" class="form-control" v-model="cierreVisita.estado_visita" required>
        <option value="">Seleccione</option>
        <option value="completado">Completado</option>
        <option value="pendiente">Pendiente</option>
        <option value="cancelado">Cancelado</option>
      </select>
    </div>

    <div class="form-group">
      <label for="observacionesFinales" class="form-label">📝 Observaciones Finales (Opcional)</label>
      <textarea id="observacionesFinales" class="form-control" rows="3" v-model="cierreVisita.observaciones"></textarea>
    </div>

    <!-- ✅ Campo Recomendaciones (NUEVO - si lo tienes en DB y lo quieres enviar) -->
    <div class="form-group">
      <label for="recomendaciones" class="form-label">💡 Recomendaciones (Opcional)</label>
      <textarea id="recomendaciones" class="form-control" rows="3" v-model="cierreVisita.recomendaciones"></textarea>
    </div>

    <!-- Firma: Realiza la visita -->
    <div class="form-group">
      <h5>✍️ Firma de quien realiza la visita *</h5>
      <canvas ref="firmaRealiza" class="firma-canvas border" width="300" height="150"></canvas>
      <div class="mt-2">
        <button class="btn btn-sm btn-secondary" @click="limpiarFirma('realiza')">🧹 Limpiar</button>
      </div>
    </div>

    <!-- Firma: Recibe la visita -->
    <div class="form-group">
      <h5>✍️ Firma de quien recibe la visita *</h5>
      <canvas ref="firmaRecibe" class="firma-canvas border" width="300" height="150"></canvas>
      <div class="mt-2">
        <button class="btn btn-sm btn-secondary" @click="limpiarFirma('recibe')">🧹 Limpiar</button>
      CierreVisita::updateOrCreate(
                ['visita_id' => $data['visita_id']],
                $data
            );
      </div>
    </div>

    <!-- Firma: Testigo (opcional) -->
    <div class="form-group">
      <h5>✍️ Firma del testigo (opcional)</h5>
      <canvas ref="firmaTestigo" class="firma-canvas border" width="300" height="150"></canvas>
      <div class="mt-2">
        <button class="btn btn-sm btn-secondary" @click="limpiarFirma('testigo')">🧹 Limpiar</button>
      </div>
    </div>

    <!-- Galería de imágenes -->
    <div class="mb-3">
      <h5>📸 Fotos de la visita (opcional)</h5>
      <input type="file" accept="image/*" capture="environment" multiple @change="cargarImagenes" />
      <div class="row mt-3">
        <div class="col-4" v-for="(img, i) in imagenes" :key="i">
          <img :src="img" class="img-thumbnail mb-2" />
        </div>
      </div>
    </div>

    <!-- Acciones -->
    <button @click="guardar" class="btn btn-primary">💾 Guardar Cierre de Visita</button>
    <button type="button" class="btn btn-success" @click="verRevisionFinal">
      ➡️ Ver Revisión Final
    </button>
  </div>
</template>

<script>
import SignaturePad from 'signature_pad'
import { saveFormData, getFormDataByVisita } from '../store/indexeddb' // Importar getFormDataByVisita

export default {
  data() {
    return {
      visitaId: null,
      firmaPadRealiza: null,
      firmaPadRecibe: null,
      firmaPadTestigo: null,
      imagenes: [],
      cierreVisita: {
        fecha_cierre: '',
        estado_visita: '',
        observaciones: '',
        recomendaciones: '' // ✅ Añadido el campo recomendaciones
      }
    }
  },
  async mounted() { // Usar async mounted para await
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id')
    localStorage.setItem('visita_id', this.visitaId)

    // Inicializar SignaturePads
    this.firmaPadRealiza = new SignaturePad(this.$refs.firmaRealiza)
    this.firmaPadRecibe = new SignaturePad(this.$refs.firmaRecibe)
    this.firmaPadTestigo = new SignaturePad(this.$refs.firmaTestigo)

    // Cargar datos existentes de cierre de visita si los hay
    await this.loadExistingCierreVisitaData();
  },
  methods: {
    async loadExistingCierreVisitaData() {
      const existingData = await getFormDataByVisita('cierre_visitas', this.visitaId);
      if (existingData) {
        this.cierreVisita.fecha_cierre = existingData.fecha_cierre || '';
        this.cierreVisita.estado_visita = existingData.estado_visita || '';
        this.cierreVisita.observaciones = existingData.observaciones || '';
        this.cierreVisita.recomendaciones = existingData.recomendaciones || ''; // ✅ Cargar recomendaciones
        this.imagenes = Array.isArray(existingData.imagenes) ? existingData.imagenes : [];

        // Restaurar las firmas si existen
        if (existingData.firma_responsable && !this.firmaPadRealiza.isEmpty()) { // ✅ CAMBIO: usar firma_responsable
          this.firmaPadRealiza.fromDataURL(existingData.firma_responsable);
        }
        if (existingData.firma_recibe && !this.firmaPadRecibe.isEmpty()) {
          this.firmaPadRecibe.fromDataURL(existingData.firma_recibe);
        }
        if (existingData.firma_testigo && !this.firmaPadTestigo.isEmpty()) {
          this.firmaPadTestigo.fromDataURL(existingData.firma_testigo);
        }
      }
    },
    limpiarFirma(tipo) {
      if (tipo === 'realiza') this.firmaPadRealiza.clear()
      if (tipo === 'recibe') this.firmaPadRecibe.clear()
      if (tipo === 'testigo') this.firmaPadTestigo.clear()
    },
    cargarImagenes(event) {
      const files = event.target.files
      for (let i = 0; i < files.length; i++) {
        const reader = new FileReader()
        reader.onload = e => {
          this.imagenes.push(e.target.result)
        }
        reader.readAsDataURL(files[i])
      }
    },
    async guardar() {
      if (this.firmaPadRealiza.isEmpty() || this.firmaPadRecibe.isEmpty()) {
        alert('⚠️ Las dos firmas obligatorias deben estar diligenciadas')
        return
      }
      if (!this.cierreVisita.fecha_cierre || !this.cierreVisita.estado_visita) {
        alert('⚠️ La Fecha de Cierre y el Estado de la Visita son obligatorios.')
        return
      }

      const dataToSave = {
        visita_id: this.visitaId,
        fecha_cierre: this.cierreVisita.fecha_cierre,
        estado_visita: this.cierreVisita.estado_visita,
        observaciones_finales: this.cierreVisita.observaciones, // ✅ CAMBIO DE NOMBRE
        recomendaciones: this.cierreVisita.recomendaciones, // ✅ NUEVO CAMPO

        firma_responsable: this.firmaPadRealiza.toDataURL(), // ✅ CAMBIO DE NOMBRE
        firma_recibe: this.firmaPadRecibe.toDataURL(),
        firma_testigo: this.firmaPadTestigo.isEmpty() ? null : this.firmaPadTestigo.toDataURL(),
        
        imagenes: this.imagenes,
        finalizada_en: new Date().toISOString().slice(0, 10) // ✅ NUEVO CAMPO: Fecha actual para 'finalizada_en'
      }

      await saveFormData('cierre_visitas', dataToSave)
      alert('✅ Cierre de Visita, firmas e imágenes guardadas localmente')
    },
    verRevisionFinal() {
      this.$router.push(`/revisionfinal?visita_id=${this.visitaId}`);
    },
  }
}
</script>

<style scoped>
@import '../styles/offline.css';
.firma-canvas {
  background-color: #fff;
  width: 100%;
  max-width: 300px;
  height: 150p
  }
</style>


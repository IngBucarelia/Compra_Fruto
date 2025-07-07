<template>
  <div class="container">
    <h2>🖊️ Registro de Firmas (Modo Offline)</h2>

    <!-- Firma: Realiza la visita -->
    <div class="mb-4">
      <h5>✍️ Firma de quien realiza la visita *</h5>
      <canvas ref="firmaRealiza" class="firma-canvas border" width="300" height="150"></canvas>
      <div class="mt-2">
        <button class="btn btn-sm btn-secondary" @click="limpiarFirma('realiza')">🧹 Limpiar</button>
      </div>
    </div>

    <!-- Firma: Recibe la visita -->
    <div class="mb-4">
      <h5>✍️ Firma de quien recibe la visita *</h5>
      <canvas ref="firmaRecibe" class="firma-canvas border" width="300" height="150"></canvas>
      <div class="mt-2">
        <button class="btn btn-sm btn-secondary" @click="limpiarFirma('recibe')">🧹 Limpiar</button>
      </div>
    </div>

    <!-- Firma: Testigo (opcional) -->
    <div class="mb-4">
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
    <button @click="guardar" class="btn btn-primary">💾 Guardar</button>
   <button type="button" class="btn btn-success" @click="verRevisionFinal">
        ➡️ Ver Revision Final
        </button>
  </div>
</template>

<script>
import SignaturePad from 'signature_pad'
import { saveFormData } from '../store/indexeddb'

export default {
  data() {
    return {
      visitaId: null,
      firmaPadRealiza: null,
      firmaPadRecibe: null,
      firmaPadTestigo: null,
      imagenes: []
    }
  },
  mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id')
    localStorage.setItem('visita_id', this.visitaId)

    // Inicializar SignaturePads
    this.firmaPadRealiza = new SignaturePad(this.$refs.firmaRealiza)
    this.firmaPadRecibe = new SignaturePad(this.$refs.firmaRecibe)
    this.firmaPadTestigo = new SignaturePad(this.$refs.firmaTestigo)
  },
  methods: {
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

      const data = {
        visita_id: this.visitaId,
        firma_realiza: this.firmaPadRealiza.toDataURL(),
        firma_recibe: this.firmaPadRecibe.toDataURL(),
        firma_testigo: this.firmaPadTestigo.isEmpty() ? null : this.firmaPadTestigo.toDataURL(),
        imagenes: this.imagenes
      }

      await saveFormData('firmas', data)
      alert('✅ Firmas e imágenes guardadas localmente')
    },
    verRevisionFinal() {
      this.$router.push(`/revisionfinal?visita_id=${this.visitaId}`);
    },
  }
}
</script>

<style scoped>
.firma-canvas {
  background-color: #fff;
  width: 100%;
  max-width: 300px;
  height: 150px;
}
</style>

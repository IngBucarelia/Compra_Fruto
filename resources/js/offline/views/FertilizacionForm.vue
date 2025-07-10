<template>
  <div class="container">
    <h2>🌱 Fertilizacion - Registros Previos </h2>

    <div v-if="areaInfo" class="mb-4">
      <h5>📍 Información del Área guardada</h5>
      <ul class="list-group">
        <li class="list-group-item"><strong>Material:</strong> {{ areaInfo.material }}</li>
        <li class="list-group-item"><strong>Estado:</strong> {{ areaInfo.estado }}</li>
        <li class="list-group-item"><strong>Año siembra:</strong> {{ areaInfo.anio_siembra }}</li>
        <li class="list-group-item"><strong>Área:</strong> {{ areaInfo.area }}</li>
      </ul>
    </div>
 <h2>🌱 Registro de Fertilización (Modo Offline)</h2>
    <form @submit.prevent="guardar">
      <div class="mb-3">
        <label>Fecha de fertilización</label>
        <input type="date" v-model="fertilizacion.fecha_fertilizacion" class="form-control" required>
      </div>

      <h5>Fertilizantes</h5>
      <div v-for="(item, index) in fertilizacion.fertilizantes" :key="index" class="mb-3 border p-3">
        <select v-model="item.nombre" class="form-select mb-2" required>
          <option value="">Seleccione fertilizante</option>
          <option value="urea">Urea</option>
          <option value="compost">Compost</option>
          <option value="npk">NPK</option>
          <option value="otro">Otro</option>
        </select>
        <input type="number" v-model="item.cantidad" class="form-control" placeholder="Cantidad (kg)" required>
      </div>

      <button type="button" @click="agregarFertilizante" class="btn btn-secondary mb-3">➕ Añadir otro</button>
      <br>
      <button type="submit" class="btn btn-primary">💾 Guardar Local</button>
    </form>
    <button type="button" class="btn btn-success" @click="irAPolinizacion">
      ➡️ Ir a Polinizacion
    </button>
    <button v-if="canSync" @click="sincronizar" class="btn btn-success mt-3">🔄 Sincronizar</button>
    <button class="btn btn-dark mt-3 ms-2" @click="volver">⬅️ Volver</button>
  </div>
</template>

<script>
import { getFormDataByVisita, saveFormData } from '../store/indexeddb'

export default {
  data() {
    return {
      visitaId: null,
      areaInfo: null,
      canSync: navigator.onLine,
      fertilizacion: {
        fecha_fertilizacion: '',
        fertilizantes: [
          { nombre: '', cantidad: '' }
        ]
      }
    }
  },
  async mounted() {
  this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
  localStorage.setItem('visita_id', this.visitaId);    
  this.areaInfo = await getFormDataByVisita('area', this.visitaId)

    window.addEventListener('online', () => this.canSync = true)
    window.addEventListener('offline', () => this.canSync = false)
  },
  methods: {
    agregarFertilizante() {
      this.fertilizacion.fertilizantes.push({ nombre: '', cantidad: '' })
    },
    irAPolinizacion() {
      this.$router.push(`/polinizacion?visita_id=${this.visitaId}`);
    },
    async guardar() {
      const data = {
        ...this.fertilizacion,
        visita_id: this.visitaId
      }
      await saveFormData('fertilizacion', data)
      alert('✅ Fertilización guardada localmente')
    },
    async sincronizar() {
      
    },
    volver() {
      this.$router.push('/area')
    }
  }
}
</script>

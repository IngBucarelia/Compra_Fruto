<template>
  
  <div class="offline-container">
    <h2 class="offline-title">📍 Registro de Áreas (Modo Offline)</h2>
    <form class="offline-form" @submit.prevent="guardar">
      <div class="form-group">
        <label class="form-label">Material</label>
        <select v-model="area.material" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="guinense">Guinense</option>
          <option value="hibrido">Híbrido</option>
        </select>
      </div>
      <div class="form-group">
        <label>Estado</label>
        <select v-model="area.estado" class="form-control" required>
          <option value="desarrollo">Desarrollo</option>
          <option value="produccion">Producción</option>
        </select>
      </div>
      <div class="form-group">
        <label>Año siembra</label>
        <input type="date" v-model="area.anio_siembra" class="form-control" required />
      </div>
      <div class="form-group">
        <label>Área (m²)</label>
        <input type="number" v-model="area.area" class="form-control" required />
      </div>
      <div class="form-group">
        <label>Orden plantis número</label>
        <input type="number" v-model="area.orden_plantis_numero" class="form-control" required />
      </div>
      <div class="form-group">
        <label>Estado orden Plantis</label>
        <select v-model="area.estado_oren_plantis" class="form-control" required>
          <option value="desarrollo">Desarrollo</option>
          <option value="produccion">Producción</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">💾 Guardar Local</button>
    </form>

    <button v-if="canSync" @click="sincronizar" class="btn btn-success mt-3">
      🔄 Sincronizar
    </button>

    <div class="mt-3">
  <button type="button" class="btn btn-success" @click="irAFertilizacion">
    ➡️ Ir a Fertilización
  </button><br>

  <button 
      @click="redirectToOnlineDashboard" 
      class="btn btn-primary fixed bottom-4 right-4"
      v-if="isOnline"
    >
      Ir al Dashboard Online
    </button>
            <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>

</div> 

  </div>
</template>

<script>
import { saveFormData } from '../store/indexeddb';

export default {
  data() {
    return {
      isOnline: navigator.onLine,
      area: {
        material: '',
        estado: '',
        anio_siembra: '',
        area: '',
        orden_plantis_numero: '',
        estado_oren_plantis: '',
      },
      visitaId: null,
      canSync: navigator.onLine,
    };
  },
  mounted() {
    const el = document.getElementById('offline-app')
    this.visitaId = el?.dataset?.visitaId

    // respaldo desde localStorage
    if (!this.visitaId) {
      this.visitaId = localStorage.getItem('visita_id')
    } else {
      localStorage.setItem('visita_id', this.visitaId)
    }

    console.log('Visita ID capturado:', this.visitaId)
  },

  methods: {
    redirectToOnlineDashboard() {
      // Redirige a la ruta online de Laravel
      window.location.href = '/dashboard';
    },
    async guardar() {
      const data = { ...this.area, visita_id: this.visitaId };
      
      // Guarda en localStorage
      localStorage.setItem(`area_${this.visitaId}`, JSON.stringify(data));
      
      // Guarda también en IndexedDB
      await saveFormData('area', data);

      alert('✅ Área guardada localmente');
    },

    
    irAFertilizacion() {
      this.$router.push(`/fertilizacion?visita_id=${this.visitaId}`);
    },
    irAInicio() {
      this.$router.push(`/dashboard`);
    },

    async sincronizar() {
     
    }
  },
  
};
</script>

<style scoped>
@import '../styles/offline.css';

/* Estilos adicionales específicos para este componente si los necesitas */
</style>

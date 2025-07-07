<template>
  <div class="container">
    <h2>📍 Registro de Áreas (Modo Offline)</h2>
    <form @submit.prevent="guardar">
      <div class="mb-3">
        <label>Material</label>
        <select v-model="area.material" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="guinense">Guinense</option>
          <option value="hibrido">Híbrido</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Estado</label>
        <select v-model="area.estado" class="form-control" required>
          <option value="desarrollo">Desarrollo</option>
          <option value="produccion">Producción</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Año siembra</label>
        <input type="date" v-model="area.anio_siembra" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>Área (m²)</label>
        <input type="number" v-model="area.area" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>Orden plantis número</label>
        <input type="number" v-model="area.orden_plantis_numero" class="form-control" required />
      </div>
      <div class="mb-3">
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
  </button>
</div> 

  </div>
</template>

<script>
import { saveFormData } from '../store/indexeddb';

export default {
  data() {
    return {
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

    async sincronizar() {
      const data = JSON.parse(localStorage.getItem(`area_${this.visitaId}`));
      if (!data) return alert('⚠️ No hay datos locales');

      const res = await fetch('/api/offline/sync', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          submissions: [{ formName: 'area', formData: data }]
        })
      });

      const result = await res.json();
      if (result.results?.[0]?.success) {
        localStorage.removeItem(`area_${this.visitaId}`);
        alert('✅ Datos sincronizados');
      } else {
        alert('❌ Error al sincronizar');
      }
    }
  },
  
};
</script>

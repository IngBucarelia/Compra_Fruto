<template>
  <div class="container">
    <h2>🌱Polinización - Registros Previos </h2>

    <div class="row mb-4">
  <!-- Tarjeta: Área -->
  <div class="col-md-6">
    <div class="card border-success mb-3">
      <div class="card-header bg-success text-white">
        📍 Información del Área
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush" v-if="areaInfo">
          <li class="list-group-item"><strong>Material:</strong> {{ areaInfo.material }}</li>
          <li class="list-group-item"><strong>Estado:</strong> {{ areaInfo.estado }}</li>
          <li class="list-group-item"><strong>Año siembra:</strong> {{ areaInfo.anio_siembra }}</li>
          <li class="list-group-item"><strong>Área (m²):</strong> {{ areaInfo.area }}</li>
          <li class="list-group-item"><strong>Orden Plantis N°:</strong> {{ areaInfo.orden_plantis_numero }}</li>
          <li class="list-group-item"><strong>Estado orden Plantis:</strong> {{ areaInfo.estado_oren_plantis }}</li>
        </ul>
        <p v-else class="text-muted">Sin datos de área</p>
      </div>
    </div>
  </div>

  <!-- Tarjeta: Fertilización -->
  <div class="col-md-6">
    <div class="card border-primary mb-3">
      <div class="card-header bg-primary text-white">
        💧 Fertilizaciones guardadas
      </div>
      <div class="card-body" v-if="fertilizacion.length">
        <div v-for="(fert, index) in fertilizacion" :key="index" class="mb-3">
          <h6>📅 {{ fert.fecha_fertilizacion }}</h6>
          <ul class="list-group">
            <li v-for="(item, i) in fert.fertilizantes" :key="i" class="list-group-item">
              {{ item.nombre }} - {{ item.cantidad }} kg
            </li>
          </ul>
        </div>
      </div>
      <p v-else class="text-muted">Sin fertilizaciones guardadas.</p>
    </div>
  </div>
</div>
    <h2>🌱 Registro de Polinización (Modo Offline)</h2>


    <!-- Formulario polinización -->
    <form @submit.prevent="guardar">
      <div class="mb-3">
        <label>🗓️ Fecha de polinización:</label>
        <input type="date" v-model="form.fecha" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>🔀 Nº de pases:</label>
        <input type="number" v-model="form.n_pases" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>🔁 Ciclos por ronda:</label>
        <input type="number" v-model="form.ciclos_ronda" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>💊 Cantidad de ANA aplicada:</label>
        <input type="number" step="0.01" v-model="form.ana" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>💧 Tipo de ANA:</label>
        <select v-model="form.tipo_ana" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="solido">Sólido</option>
          <option value="liquido">Líquido</option>
        </select>
      </div>
      <div class="mb-3">
        <label>🌬️ Talco aplicado (kg):</label>
        <input type="number" step="0.01" v-model="form.talco" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">💾 Guardar Polinización</button>
    </form>
    <button type="button" class="btn btn-success" @click="irASanidad">
      ➡️ Ir a Sanidad
    </button>
    <button v-if="canSync" @click="sincronizar" class="btn btn-success mt-3">🔄 Sincronizar</button>
    <button class="btn btn-dark mt-3 ms-2" @click="volver">⬅️ Volver</button>
  </div>
</template>

<script>
import { getFormDataByVisita, saveFormData } from '../store/indexeddb';

export default {
  data() {
    return {
      visitaId: null,
      area: null,
      fertilizacion: [],
      canSync: navigator.onLine,
      form: {
        fecha: '',
        n_pases: '',
        ciclos_ronda: '',
        ana: '',
        tipo_ana: '',
        talco: ''
      }
    };
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);
    this.areaInfo = await getFormDataByVisita('area', this.visitaId)
    const fert = await getFormDataByVisita('fertilizacion', this.visitaId);
    this.fertilizacion = Array.isArray(fert) ? fert : fert ? [fert] : [];
  },
  methods: {
    async guardar() {
      const data = { ...this.form, visita_id: this.visitaId };
      await saveFormData('polinizacion', data);
      alert('✅ Polinización guardada localmente');
    },
    irASanidad() {
      this.$router.push(`/sanidad?visita_id=${this.visitaId}`);
    },
  }
};
</script>

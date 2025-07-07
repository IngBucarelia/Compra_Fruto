<template>
  <div class="container">
    <h2>🌱 Sanidad - Registros Previos </h2>

    <div class="row mb-4">
      <!-- Tarjeta: Área -->
      <div class="col-md-4">
        <div class="card border-success">
          <div class="card-header bg-success text-white">
            📍 Información del Área
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush" v-if="areaInfo">
              <li class="list-group-item"><strong>Material:</strong> {{ areaInfo.material }}</li>
              <li class="list-group-item"><strong>Estado:</strong> {{ areaInfo.estado }}</li>
              <li class="list-group-item"><strong>Año siembra:</strong> {{ areaInfo.anio_siembra }}</li>
              <li class="list-group-item"><strong>Área:</strong> {{ areaInfo.area }}</li>
            </ul>
            <p v-else class="text-muted">Sin datos de área</p>
          </div>
        </div>
      </div>

      <!-- Tarjeta: Fertilizaciones -->
      <div class="col-md-4">
        <div class="card border-primary">
          <div class="card-header bg-primary text-white">
            💧 Fertilizaciones
          </div>
          <div class="card-body" v-if="fertilizaciones.length">
            <div v-for="(fert, index) in fertilizaciones" :key="index">
              <h6>📅 {{ fert.fecha_fertilizacion }}</h6>
              <ul class="list-group">
                <li v-for="(item, i) in fert.fertilizantes" :key="i" class="list-group-item">
                  {{ item.nombre }} - {{ item.cantidad }} kg
                </li>
              </ul>
            </div>
          </div>
          <p v-else class="text-muted">Sin fertilizaciones</p>
        </div>
      </div>

      <!-- Tarjeta: Polinizaciones -->
      <div class="col-md-4">
        <div class="card border-warning">
          <div class="card-header bg-warning text-dark">
            🌸 Polinizaciones
          </div>
          <div class="card-body" v-if="polinizaciones.length">
            <div v-for="(poli, index) in polinizaciones" :key="index">
              <h6>📅 {{ poli.fecha }}</h6>
              <ul class="list-group">
                <li class="list-group-item">Pases: {{ poli.n_pases }}</li>
                <li class="list-group-item">Ciclos: {{ poli.ciclos_ronda }}</li>
                <li class="list-group-item">ANA: {{ poli.ana }} ({{ poli.tipo_ana }})</li>
                <li class="list-group-item">Talco: {{ poli.talco }} kg</li>
              </ul>
            </div>
          </div>
          <p v-else class="text-muted">Sin polinizaciones</p>
        </div>
      </div>
    </div>
    <h2>🪩 Registro de Sanidad (Modo Offline)</h2>

    <!-- Formulario Sanidad -->
    <form @submit.prevent="guardar">
      <div class="row">
        <div class="col-md-4" v-for="campo in campos" :key="campo.key">
          <label>{{ campo.label }}</label>
          <input :type="campo.type" v-model="form[campo.key]" class="form-control mb-3" :required="campo.required">
        </div>
      </div>

      <div class="mb-3">
        <label>Observaciones</label>
        <textarea v-model="form.observaciones" class="form-control"></textarea>
      </div>

      <button type="submit" class="btn btn-primary">📁 Guardar Local</button>
    </form>
    <button type="button" class="btn btn-success" @click="irASuelo">
      ➡️ Ir a Estudio de Suelo
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
      areaInfo: null,
      fertilizaciones: [],
      polinizaciones: [],
      form: {
        opsophanes: '',
        pudricion_cogollo: '',
        raspador: '',
        palmarum: '',
        strategus: '',
        leptophrasa: '',
        pestalotiopsis: '',
        pudricion_basal: '',
        pudricion_estipe: '',
        otros: '',
        observaciones: ''
      },
      campos: [
        { key: 'opsophanes', label: 'Opsophanes (%)', type: 'number', required: true },
        { key: 'pudricion_cogollo', label: 'Pudrición Cogollo (%)', type: 'number', required: true },
        { key: 'raspador', label: 'Raspador (%)', type: 'number', required: true },
        { key: 'palmarum', label: 'Palmarum (%)', type: 'number', required: true },
        { key: 'strategus', label: 'Strategus (%)', type: 'number', required: true },
        { key: 'leptophrasa', label: 'Leptophrasa (%)', type: 'number', required: true },
        { key: 'pestalotiopsis', label: 'Pestalotiopsis (%)', type: 'number', required: true },
        { key: 'pudricion_basal', label: 'Pudrición Basal (%)', type: 'number', required: true },
        { key: 'pudricion_estipe', label: 'Pudrición Estipe (%)', type: 'number', required: true },
        { key: 'otros', label: 'Otros', type: 'text', required: false },
      ]
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);
    this.areaInfo = await getFormDataByVisita('area', this.visitaId);
    const fert = await getFormDataByVisita('fertilizacion', this.visitaId);
    this.fertilizaciones = Array.isArray(fert) ? fert : fert ? [fert] : [];
    const poli = await getFormDataByVisita('polinizacion', this.visitaId);
    this.polinizaciones = Array.isArray(poli) ? poli : poli ? [poli] : [];
  },
  methods: {
    async guardar() {
      const data = { ...this.form, visita_id: this.visitaId };
      await saveFormData('sanidad', data);
      alert('✅ Sanidad guardada localmente');
    },
    irASuelo() {
      this.$router.push(`/suelo?visita_id=${this.visitaId}`);
    },
  }
};
</script>

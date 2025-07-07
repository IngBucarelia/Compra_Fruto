<template>
  <div class="container">
    <h2>🌱 Suelo - Registros Previos </h2>

    <div class="row mb-4">
      <!-- Área -->
      <div class="col-md-6">
        <div class="card border-success mb-3">
          <div class="card-header bg-success text-white">📍 Información del Área</div>
          <div class="card-body" v-if="area">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>Material:</strong> {{ area.material }}</li>
              <li class="list-group-item"><strong>Estado:</strong> {{ area.estado }}</li>
              <li class="list-group-item"><strong>Año siembra:</strong> {{ area.anio_siembra }}</li>
              <li class="list-group-item"><strong>Área (m²):</strong> {{ area.area }}</li>
              <li class="list-group-item"><strong>Orden Plantis N°:</strong> {{ area.orden_plantis_numero }}</li>
              <li class="list-group-item"><strong>Estado orden Plantis:</strong> {{ area.estado_oren_plantis }}</li>
            </ul>
          </div>
          <p v-else class="text-muted">Sin datos de área.</p>
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

      <!-- Sanidad -->
      <div class="col-md-6">
        <div class="card border-danger mb-3">
          <div class="card-header bg-danger text-white">🦠 Sanidad</div>
          <div class="card-body" v-if="sanidad">
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Opsophanes: {{ sanidad.opsophanes }}%</li>
              <li class="list-group-item">Pudrición Cogollo: {{ sanidad.pudricion_cogollo }}%</li>
              <li class="list-group-item">Raspador: {{ sanidad.raspador }}%</li>
              <li class="list-group-item">Palmarum: {{ sanidad.palmarum }}%</li>
              <li class="list-group-item">Strategus: {{ sanidad.strategus }}%</li>
              <li class="list-group-item">Leptoparsha: {{ sanidad.leptoparsa }}%</li>
              <li class="list-group-item">Pestalotiopsis: {{ sanidad.pestalotiopsis }}%</li>
              <li class="list-group-item">Pudrición Basal: {{ sanidad.pudricion_basal }}%</li>
              <li class="list-group-item">Pudrición Estípite: {{ sanidad.pudricion_estipe }}%</li>
              <li class="list-group-item">Otros: {{ sanidad.otros }}</li>
              <li class="list-group-item">Observaciones: {{ sanidad.observaciones }}</li>
            </ul>
          </div>
          <p v-else class="text-muted">Sin sanidad registrada.</p>
        </div>
      </div>
    </div>
    <h2>🧪 Registro de Análisis de Suelo (Modo Offline)</h2>

    <!-- Formulario suelo -->
    <form @submit.prevent="guardar">
      <h4>📋 Formulario de Suelo</h4>
      <div class="mb-3">
        <label>Análisis foliar realizado:</label>
        <select v-model="form.analisis_foliar" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="si">Sí</option>
          <option value="no">No</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Análisis de suelo realizado:</label>
        <select v-model="form.alanalisis_suelo" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="si">Sí</option>
          <option value="no">No</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Tipo de suelo:</label>
        <select v-model="form.tipo_suelo" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="arenoso">Arenoso</option>
          <option value="arcilloso">Arcilloso</option>
          <option value="franco">Franco</option>
          <option value="franco arcilloso">Franco arcilloso</option>
          <option value="otro">Otro</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">💾 Guardar Suelo</button>
    </form>
     <button type="button" class="btn btn-success" @click="irALaboresCultivo">
      ➡️ Ir a Labores de Cultivo
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
      area: null,
      fertilizaciones: [],
      polinizaciones: [],
      sanidad: null,
      form: {
        analisis_foliar: '',
        alanalisis_suelo: '',
        tipo_suelo: ''
      }
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id')
    localStorage.setItem('visita_id', this.visitaId)

    this.area = await getFormDataByVisita('area', this.visitaId)
     const fert = await getFormDataByVisita('fertilizacion', this.visitaId);
    this.fertilizaciones = Array.isArray(fert) ? fert : fert ? [fert] : [];
    const poli = await getFormDataByVisita('polinizacion', this.visitaId);
    this.polinizaciones = Array.isArray(poli) ? poli : poli ? [poli] : [];
    this.sanidad = await getFormDataByVisita('sanidad', this.visitaId)
  },
  methods: {
    async guardar() {
      const data = { ...this.form, visita_id: this.visitaId }
      await saveFormData('suelo', data)
      alert('✅ Suelo guardado localmente')
    },
    irALaboresCultivo() {
      this.$router.push(`/labores?visita_id=${this.visitaId}`);
    },
  }
}
</script>

<template>
  <div class="offline-container" >
    <h2 class="offline-title">🌱 Labores Cultivo - Registros Previos </h2>

    <!-- 🧾 Módulos previos -->
    <div class="row mb-4">
      <InfoCard v-if="area" title="📍 Área" :items="[
        { label: 'Material', value: area.material },
        { label: 'Estado', value: area.estado },
        { label: 'Año siembra', value: area.anio_siembra },
        { label: 'Área (m²)', value: area.area },
        { label: 'Orden Plantis', value: area.orden_plantis_numero },
        { label: 'Estado orden Plantis', value: area.estado_oren_plantis }
      ]" />

      <InfoCard v-if="fertilizaciones.length" title="💧 Fertilización">
        <div v-for="(f, index) in fertilizaciones" :key="index" class="mb-2">
          <strong>📅 {{ f.fecha_fertilizacion }}</strong>
          <ul class="list-group">
            <li v-for="(item, i) in f.fertilizantes" :key="i" class="list-group-item">
              {{ item.nombre }} - {{ item.cantidad }} kg
            </li>
          </ul>
        </div>
      </InfoCard>

      <InfoCard v-if="polinizaciones.length" title="🌸 Polinización">
        <div v-for="(p, index) in polinizaciones" :key="index" class="mb-2">
          <strong>📅 {{ p.fecha }}</strong>
          <ul class="list-group">
            <li>N° Pases: {{ p.n_pases }}</li>
            <li>Ciclos: {{ p.ciclos_ronda }}</li>
            <li>ANA: {{ p.ana }} ({{ p.tipo_ana }})</li>
            <li>Talco: {{ p.talco }}</li>
          </ul>
        </div>
      </InfoCard>

      <InfoCard v-if="sanidad" title="🦠 Sanidad" :items="[
        { label: 'Opsophanes', value: sanidad.opsophanes + '%' },
        { label: 'Pudrición Cogollo', value: sanidad.pudricion_cogollo + '%' },
        { label: 'Raspador', value: sanidad.raspador + '%' },
        { label: 'Palmarum', value: sanidad.palmarum + '%' },
        { label: 'Strategus', value: sanidad.strategus + '%' },
        { label: 'Leptoparsha', value: sanidad.leptoparsha + '%' },
        { label: 'Pestalotiopsis', value: sanidad.pestalotiopsis + '%' },
        { label: 'Pudrición Basal', value: sanidad.pudricion_basal + '%' },
        { label: 'Pudrición Estípite', value: sanidad.pudricion_estipe + '%' },
        { label: 'Otros', value: sanidad.otros },
        { label: 'Observaciones', value: sanidad.observaciones }
      ]" />

      <InfoCard v-if="suelo" title="🧪 Suelo" :items="[
        { label: 'Análisis Foliar', value: suelo.analisis_foliar },
        { label: 'Análisis Suelo', value: suelo.alanalisis_suelo },
        { label: 'Tipo Suelo', value: suelo.tipo_suelo }
      ]" />
    </div>
    <h2>🚜 Registro de Labores de Cultivo (Modo Offline)</h2>

    <!-- 🚜 Formulario Labores -->
    <form @submit.prevent="guardar">
      <div class="row">
        <div class="col-md-6" v-for="(label, key) in campos" :key="key">
          <div class="mb-3">
            <label>{{ label }}</label>
            <input type="number" v-model="form[key]" class="form-control" required min="0" max="100">
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary mt-3">💾 Guardar Labores</button>
    </form>
        <button type="button" class="btn btn-success" @click="irAEvaluacionCosecha">
        ➡️ Ir a Evaluacion de Cosecha
        </button>
        <button v-if="canSync" @click="sincronizar" class="btn btn-success mt-3">🔄 Sincronizar</button>
        <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
  </div>
</template>

<script>
import InfoCard from '../../components/InfoCard.vue'
import { getFormDataByVisita, saveFormData } from '../store/indexeddb'

export default {
  components: { InfoCard },
  data() {
    return {
      visitaId: null,
      area: null,
      fertilizaciones: [],
      polinizaciones: [],
      sanidad: null,
      suelo: null,
      campos: {
        polinizacion: '🌸 Polinización',
        limpieza_calle: '🧹 Limpieza Calle',
        limpieza_plato: '🧹 Limpieza Plato',
        poda: '✂️ Poda',
        fertilizacion: '💧 Fertilización',
        enmiendas: '🧪 Enmiendas',
        ubicacion_tusa_fibra: '📦 Ubicación Tusa/Fibra',
        ubicacion_hoja: '📦 Ubicación Hoja',
        lugar_ubicacion_hoja: '📍 Lugar Ubicación Hoja',
        plantas_nectariferas: '🌻 Plantas Nectaríferas',
        cobertura: '🌿 Cobertura',
        labor_cosecha: '🚜 Labor Cosecha',
        calidad_fruta: '🍍 Calidad Fruta',
        recoleccion_fruta: '🧺 Recolección Fruta',
        drenajes: '🚰 Drenajes'
      },
      form: {}
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id')
    localStorage.setItem('visita_id', this.visitaId)

    this.area = await getFormDataByVisita('area', this.visitaId)

    const fert = await getFormDataByVisita('fertilizacion', this.visitaId)
    this.fertilizaciones = Array.isArray(fert) ? fert : fert ? [fert] : []

    const poli = await getFormDataByVisita('polinizacion', this.visitaId)
    this.polinizaciones = Array.isArray(poli) ? poli : poli ? [poli] : []

    this.sanidad = await getFormDataByVisita('sanidad', this.visitaId)
    this.suelo = await getFormDataByVisita('suelo', this.visitaId)

    // inicializar campos
    for (const key in this.campos) {
      this.form[key] = ''
    }
  },
  methods: {
    async guardar() {
      const data = { ...this.form, visita_id: this.visitaId }
      await saveFormData('labores_cultivo', data)
      alert('✅ Labores de Cultivo guardadas localmente')
    },
    irAEvaluacionCosecha() {
      this.$router.push(`/evaluacion-cosecha?visita_id=${this.visitaId}`);
    },
  }
}
</script>
<style scoped>
@import '../styles/offline.css';

/* Estilos adicionales específicos para este componente si los necesitas */
</style>
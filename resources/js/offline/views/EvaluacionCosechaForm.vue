<template>
  <div class="offline-container" >
    <h2  class="offline-title">🌱 Evaluacion Cosecha - Registros Previos </h2>

    <!-- 🧻 Módulos previos -->
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
          <strong>🗕️ {{ f.fecha_fertilizacion }}</strong>
          <ul class="list-group">
            <li v-for="(item, i) in f.fertilizantes" :key="i" class="list-group-item">
              {{ item.nombre }} - {{ item.cantidad }} kg
            </li>
          </ul>
        </div>
      </InfoCard>

      <InfoCard v-if="polinizaciones.length" title="🌸 Polinización">
        <div v-for="(p, index) in polinizaciones" :key="index" class="mb-2">
          <strong>🗕️ {{ p.fecha }}</strong>
          <ul class="list-group">
            <li>N° Pases: {{ p.n_pases }}</li>
            <li>Ciclos: {{ p.ciclos_ronda }}</li>
            <li>ANA: {{ p.ana }} ({{ p.tipo_ana }})</li>
            <li>Talco: {{ p.talco }}</li>
          </ul>
        </div>
      </InfoCard>

      <InfoCard v-if="sanidad" title="🩠 Sanidad" :items="[
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

      <InfoCard v-if="suelo" title="🧢 Suelo" :items="[
        { label: 'Análisis Foliar', value: suelo.analisis_foliar },
        { label: 'Análisis Suelo', value: suelo.alanalisis_suelo },
        { label: 'Tipo Suelo', value: suelo.tipo_suelo }
      ]" />

      <InfoCard v-if="labores" title="🚜 Labores de Cultivo" :items="Object.keys(labores).map(k => ({
        label: campos[k] || k,
        value: labores[k] + '%'
      }))" />
    </div>
        <h2>🌴 Evaluación de Cosecha (Modo Offline)</h2>

    <!-- 🌾 Formulario Evaluación Cosecha -->
    <form @submit.prevent="guardar">
      <div class="mb-3">
        <label>🌱 Variedad de Fruto:</label>
        <select v-model="form.variedad_fruto" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="guinense">Guinense</option>
          <option value="hibrido">Híbrido</option>
        </select>
      </div>
      <div class="mb-3">
        <label>📦 Cantidad de Racimos:</label>
        <input type="number" v-model="form.cantidad_racimos" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>🟢 Verde (%)</label>
        <input type="number" v-model="form.verde" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>🟡 Maduro (%)</label>
        <input type="number" v-model="form.maduro" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>🟠 Sobremaduro (%)</label>
        <input type="number" v-model="form.sobremaduro" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>✂️ Pedúnculo (%)</label>
        <input type="number" v-model="form.pedunculo" class="form-control" required />
      </div>
      <div class="mb-3">
        <label>📝 Observaciones:</label>
        <textarea v-model="form.observaciones" class="form-control" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">📏 Guardar Evaluación</button>
    </form>
    <button type="button" class="btn btn-success mt-3" @click="irAFirmas">
      ➡️ Continuar con Firmas e Imagenes
    </button>
    <button v-if="canSync" @click="sincronizar" class="btn btn-success mt-3">🔄 Sincronizar</button>
        <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
  </div>
</template>

<script>
import InfoCard from '../../components/InfoCard.vue';
import { getFormDataByVisita, saveFormData } from '../store/indexeddb';

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
      labores: null,
      campos: {
        polinizacion: '🌸 Polinización',
        limpieza_calle: '🧹 Limpieza Calle',
        limpieza_plato: '🧹 Limpieza Plato',
        poda: '✂️ Poda',
        fertilizacion: '💧 Fertilización',
        enmiendas: '🧪 Enmiendas',
        ubicacion_tusa_fibra: '📦 Tusa/Fibra',
        ubicacion_hoja: '📦 Hoja',
        lugar_ubicacion_hoja: '📍 Lugar Hoja',
        plantas_nectariferas: '🌻 Nectaríferas',
        cobertura: '🌿 Cobertura',
        labor_cosecha: '🚜 Cosecha',
        calidad_fruta: '🍍 Calidad Fruta',
        recoleccion_fruta: '🧺 Recolección',
        drenajes: '🚰 Drenajes'
      },
      form: {
        variedad_fruto: '',
        cantidad_racimos: '',
        verde: '',
        maduro: '',
        sobremaduro: '',
        pedunculo: '',
        observaciones: ''
      }
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    this.area = await getFormDataByVisita('area', this.visitaId);

    const fert = await getFormDataByVisita('fertilizacion', this.visitaId);
    this.fertilizaciones = Array.isArray(fert) ? fert : fert ? [fert] : [];

    const poli = await getFormDataByVisita('polinizacion', this.visitaId);
    this.polinizaciones = Array.isArray(poli) ? poli : poli ? [poli] : [];

    this.sanidad = await getFormDataByVisita('sanidad', this.visitaId);
    this.suelo = await getFormDataByVisita('suelo', this.visitaId);
    this.labores = await getFormDataByVisita('labores_cultivo', this.visitaId);
  },
  methods: {
    async guardar() {
      const data = { ...this.form, visita_id: this.visitaId };
      await saveFormData('evaluacion_cosecha', data);
      alert('✅ Evaluación guardada localmente');
    },
    
    irAFirmas() {
      this.$router.push(`/firmas?visita_id=${this.visitaId}`);
    }
  }
}
</script>

<style scoped>
@import '../styles/offline.css';

/* Estilos adicionales específicos para este componente si los necesitas */
</style>

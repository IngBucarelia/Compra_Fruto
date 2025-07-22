<template>
  <div class="container" style="background-color: whitesmoke !important; padding: 20px;margin-top: 25px;">
    <h2 class="offline-title" >📋 Revisión Final de Formulario (Modo Offline)</h2>

    <!-- Módulo: Área -->
    <InfoCard v-if="area" title="📍 Área" :items="[
      { label: 'Material', value: area.material },
      { label: 'Estado', value: area.estado },
      { label: 'Año siembra', value: area.anio_siembra },
      { label: 'Área (m²)', value: area.area },
      { label: 'Orden Plantis', value: area.orden_plantis_numero },
      { label: 'Estado orden Plantis', value: area.estado_oren_plantis }
    ]" />

    <!-- Fertilizaciones -->
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

    <!-- Polinizaciones -->
    <InfoCard v-if="polinizaciones.length" title="🌸 Polinización">
      <div v-for="(p, index) in polinizaciones" :key="index" class="mb-2">
        <strong>📅 {{ p.fecha }}</strong>
        <ul class="list-group">
          <li>N° Pases: {{ p.n_pases }}</li>
          <li>Ciclos: {{ p.ciclos_ronda }}</li>
          <li>ANA: {{ p.ana }} ({{ p.tipo_ana }})</li>
          <li>Talco: {{ p.talco }} kg</li>
        </ul>
      </div>
    </InfoCard>

    <!-- Sanidad -->
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

    <!-- Suelo -->
    <InfoCard v-if="suelo" title="🧪 Suelo" :items="[
      { label: 'Análisis Foliar', value: suelo.analisis_foliar },
      { label: 'Análisis Suelo', value: suelo.alanalisis_suelo },
      { label: 'Tipo Suelo', value: suelo.tipo_suelo }
    ]" />

    <!-- Labores de Cultivo -->
    <InfoCard v-if="labores" title="🚜 Labores de Cultivo" :items="Object.entries(labores).map(([key, value]) => ({ label: camposLabores[key] || key, value: value + '%' }))" />

    <!-- Evaluación de Cosecha -->
    <InfoCard v-if="evaluacion" title="🌾 Evaluación de Cosecha" :items="[
      { label: 'Variedad de Fruto', value: evaluacion.variedad_fruto },
      { label: 'Cantidad de Racimos', value: evaluacion.cantidad_racimos },
      { label: 'Verde', value: evaluacion.verde + '%' },
      { label: 'Maduro', value: evaluacion.maduro + '%' },
      { label: 'Sobremaduro', value: evaluacion.sobremaduro + '%' },
      { label: 'Pedúnculo', value: evaluacion.pedunculo + '%' },
      { label: 'Observaciones', value: evaluacion.observaciones }
    ]" />

    <!-- ✅ Módulo: Cierre de Visita (NUEVO) -->
    <InfoCard v-if="cierreVisita" title="✅ Cierre de Visita" :items="[
      { label: 'Fecha de Cierre', value: cierreVisita.fecha_cierre },
      { label: 'Estado de la Visita', value: cierreVisita.estado_visita },
      { label: 'Observaciones Finales', value: cierreVisita.observaciones_finales }, // ✅ CAMBIO AQUÍ
      { label: 'Recomendaciones', value: cierreVisita.recomendaciones } // ✅ NUEVO CAMPO
    ]" />

    <!-- 🖋️ Firmas (AHORA REFERENCIAN A cierreVisita) -->
    <div class="row mt-4">
      <div class="col-md-4" v-if="cierreVisita.firma_responsable">
        <h5>Firma de quien realizó la visita</h5>
        <img :src="cierreVisita.firma_responsable" class="img-fluid border" />
      </div>
      <div class="col-md-4" v-if="cierreVisita.firma_recibe">
        <h5>Firma de quien recibió la visita</h5>
        <img :src="cierreVisita.firma_recibe" class="img-fluid border" />
      </div>
      <div class="col-md-4" v-if="cierreVisita.firma_testigo">
        <h5>Firma del testigo (opcional)</h5>
        <img :src="cierreVisita.firma_testigo" class="img-fluid border" />
      </div>
    </div>

    <!-- 🖼️ Imágenes (AHORA REFERENCIAN A cierreVisita) -->
    <div v-if="cierreVisita.imagenes && cierreVisita.imagenes.length" class="mt-4">
      <h5>📸 Imágenes de la visita</h5>
      <div class="row">
        <div class="col-md-3 mb-3" v-for="(img, idx) in cierreVisita.imagenes" :key="idx">
          <img :src="img" class="img-fluid border" />
        </div>
      </div>
    </div>

    <!-- PDF -->
    <button class="btn btn-primary mt-4" @click="generarPDF">🖨️ Generar PDF</button>
    <button class="btn btn-outline-success mt-2" @click="descargarExcel">
      📥 Exportar a Excel
    </button>
    <button class="btn btn-success" @click="sincronizarTodo">🔄 Sincronizar Datos</button>

  </div>
  <div class="mt-4">
    <button class="btn btn-success" @click="irAFirmas">
      ✍️ Continuar a Firma del Responsable
    </button>
  </div>
</template>

<script>
import InfoCard from '../../components/InfoCard.vue'
import { getFormDataByVisita } from '../store/indexeddb'
import { generarResumenPDF } from '../utils/pdfGenerator'
import { exportarResumenExcel } from '../utils/excelExporter'
import { sincronizarDatosOffline } from '../utils/sincronizador'

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
      evaluacion: null,
      cierreVisita: null, // Para los datos de cierre de visita
      camposLabores: {
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
      }
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
    this.labores = await getFormDataByVisita('labores_cultivo', this.visitaId)
    this.evaluacion = await getFormDataByVisita('evaluacion_cosecha', this.visitaId)

    // Cargar los datos de cierre de visita desde el store 'cierre_visitas'
    this.cierreVisita = await getFormDataByVisita('cierre_visitas', this.visitaId) || {};
  },
  methods: {
    irAFirmas() {
      // this.$router.push(`/firmas?visita_id=${this.visitaId}`)
    },
    async generarPDF() {
      await generarResumenPDF({
        area: this.area,
        fertilizaciones: this.fertilizaciones,
        polinizaciones: this.polinizaciones,
        sanidad: this.sanidad,
        suelo: this.suelo,
        labores: this.labores,
        evaluacion: this.evaluacion,
        cierreVisita: this.cierreVisita,
      });
    },
    descargarExcel() {
      exportarResumenExcel({
        area: this.area,
        fertilizaciones: this.fertilizaciones,
        polinizaciones: this.polinizaciones,
        sanidad: this.sanidad,
        suelo: this.suelo,
        labores: this.labores,
        evaluacion: this.evaluacion,
        cierreVisita: this.cierreVisita,
      })
    },
    async sincronizarTodo() {
      await sincronizarDatosOffline()
    }
  }
}
</script>

<style scoped>

@import '../styles/offline.css';

.firma-canvas {
  background-color: #fff;
  width: 100%;
  max-width: 300px;
  height: 150px;
}
</style>



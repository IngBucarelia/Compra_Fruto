<template>
  <div class="offline-container offline-form-container" style="background-color: aliceblue;margin-top: 60px;">

    <h3 class="title" style="color: darkolivegreen;">
      💦 Vertimientos — Manejo — Visita #{{ visitaId }}
    </h3>

    <!-- ================= SELECTOR ================= -->
    <div class="selector-seccion mb-4">
      <div class="input-group input-group-lg">
        <select v-model="componenteSeleccionado" class="form-select">
          <option value="datos-personales">🏠 Inicio de Visita</option>
          <option value="agua-captacion-legal">💧 Agua - Captación Legal</option>
          <option value="agua-uso-eficiente">🚰 Agua - Uso Eficiente</option>
          <option value="SueloConservacion">🌱 Suelo - Conservación</option>
          <option value="energia-uso-eficiente">⚡ Energía</option>
          <option value="gobernanza-hidrica">🤝 Gobernanza Hídrica</option>
          <option value="emisiones-gei">🏭 Emisiones GEI</option>
          <option value="residuos-manejo">🗑️ Residuos - Manejo</option>
          <option value="sustancias-manejo">🧪 Sustancias - Manejo</option>
          <option value="vertiminetos-manejo">💦 Vertimientos - Manejo</option>
          <option value="hmp-manejo">☣️ HMP - Manejo</option>
          <option value="avc-control">🛡️ AVC - Control</option>
          <option value="ecosistema-proteccion">🌳 Ecosistema - Protección</option>
          <option value="noremplazo-nodeforestacion">🚫 No Deforestación</option>
          <option value="cierre-visita">✅ Cierre de Visita</option>
        </select>
        <button class="btn btn-success" @click="irAComponente">Ir</button>
      </div>
    </div>

    <!-- ================= ACORDEÓN INFORMACIÓN PREVIA ================= -->
    <<!-- ================= ACORDEÓN INFORMACIÓN PREVIA ================= -->
<div
  v-if="
    aguaCaptacion ||
    aguaUso ||
    suelo ||
    energia ||
    gobernanza ||
    emisiones ||
    residuos ||
    sustancias
  "
  class="accordion mb-4"
>

  <!-- 💧 Agua - Captación Legal -->
  <div v-if="aguaCaptacion" class="accordion-item shadow-sm mb-3">
    <h2 class="accordion-header">
      <button class="accordion-button fw-bold">
        💧 Agua - Captación Legal
      </button>
    </h2>
    <div class="accordion-body">
      <p><strong>Permiso concesión:</strong> {{ siNo(aguaCaptacion.permiso_concesion) }}</p>
      <p><strong>Permiso ocupación cauce:</strong> {{ siNo(aguaCaptacion.permiso_ocupacion_cauce) }}</p>
      <p><strong>Permisos captación:</strong> {{ siNo(aguaCaptacion.permisos_captacion) }}</p>
      <p><strong>Registro agua:</strong> {{ siNo(aguaCaptacion.registro_agua) }}</p>
    </div>
  </div>

  <!-- 🚰 Agua - Uso Eficiente -->
  <div v-if="aguaUso" class="accordion-item shadow-sm mb-3">
    <h2 class="accordion-header">
      <button class="accordion-button fw-bold">
        🚰 Agua - Uso Eficiente
      </button>
    </h2>
    <div class="accordion-body">
      <p><strong>Plan ahorro:</strong> {{ siNo(aguaUso.plan_ahorro) }}</p>
      <p><strong>Mantenimiento:</strong> {{ siNo(aguaUso.mantenimiento_sistemas) }}</p>
      <p><strong>Consumo:</strong> {{ aguaUso.consumo_agua ?? 'N/A' }}</p>
    </div>
  </div>

  <!-- 🌱 Suelo – Conservación -->
  <div v-if="suelo" class="accordion-item shadow-sm mb-3">
    <h2 class="accordion-header">
      <button class="accordion-button fw-bold">
        🌱 Suelo – Conservación
      </button>
    </h2>
    <div class="accordion-body">
      <p><strong>Prácticas de conservación:</strong> {{ siNo(suelo.practicas_conservacion) }}</p>
      <p><strong>Control de erosión:</strong> {{ siNo(suelo.control_erosion) }}</p>
      <p><strong>Cobertura vegetal:</strong> {{ siNo(suelo.cobertura_vegetal) }}</p>
      <p><strong>Área intervenida:</strong> {{ suelo.area_intervenida }} ha</p>

      <p v-if="suelo.area_total_usada">
        <strong>Área total del predio:</strong> {{ suelo.area_total_usada }} ha
      </p>

      <p v-if="suelo.porcentaje">
        <strong>Porcentaje intervenido:</strong> {{ suelo.porcentaje }} %
      </p>

      <div v-if="suelo.observaciones" class="obs-box">
        {{ suelo.observaciones }}
      </div>
    </div>
  </div>

  <!-- ⚡ Energía - Uso Eficiente -->
  <div v-if="energia" class="accordion-item shadow-sm mb-3">
    <h2 class="accordion-header">
      <button class="accordion-button fw-bold">
        ⚡ Energía - Uso Eficiente
      </button>
    </h2>
    <div class="accordion-body">
      <p><strong>Registro combustible:</strong> {{ siNo(energia.registro_consumo_combustible) }}</p>
      <p><strong>Plan uso eficiente:</strong> {{ siNo(energia.plan_uso_eficiente) }}</p>
      <p><strong>Consumo kWh:</strong> {{ energia.consumo_energia_kwh }}</p>
      <p><strong>Seguimiento:</strong> {{ siNo(energia.seguimiento_indicadores) }}</p>

      <div v-if="energia.observaciones" class="obs-box">
        {{ energia.observaciones }}
      </div>
    </div>
  </div>

  <!-- 🤝 Gobernanza Hídrica -->
  <div v-if="gobernanza" class="accordion-item shadow-sm mb-3">
    <h2 class="accordion-header">
      <button class="accordion-button fw-bold">
        🤝 Gobernanza Hídrica
      </button>
    </h2>
    <div class="accordion-body">
      <p><strong>Canales comunicación:</strong> {{ siNo(gobernanza.canales_comunicacion) }}</p>
      <p><strong>Identifica actores:</strong> {{ siNo(gobernanza.identifica_actores_afectados) }}</p>
      <p><strong>Participa en gestión:</strong> {{ siNo(gobernanza.participa_actividades_gestion) }}</p>
    </div>
  </div>

  <!-- 🏭 Emisiones GEI -->
  <div v-if="emisiones" class="accordion-item shadow-sm mb-3">
    <h2 class="accordion-header">
      <button class="accordion-button fw-bold">
        🏭 Emisiones GEI
      </button>
    </h2>
    <div class="accordion-body">
      <p><strong>Cuantifica emisiones:</strong> {{ siNo(emisiones.cuantifica_emisiones) }}</p>

      <div v-if="emisiones.cuantifica_emisiones == 1" class="border rounded p-2 bg-light mb-2">
        <p><strong>Combustible:</strong> {{ emisiones.combustible }}</p>
        <p><strong>Distancia:</strong> {{ emisiones.distancia }}</p>
        <p><strong>Huella carbono:</strong> {{ emisiones.huella_carbono }}</p>
      </div>

      <p><strong>Implementa reducción:</strong> {{ siNo(emisiones.implementa_acciones_reduccion) }}</p>

      <p v-if="emisiones.acciones_reduccion">
        <strong>Acciones:</strong> {{ emisiones.acciones_reduccion }}
      </p>

      <div v-if="emisiones.observaciones" class="obs-box">
        {{ emisiones.observaciones }}
      </div>
    </div>
  </div>

  <!-- 🗑️ Residuos – Manejo -->
  <div v-if="residuos" class="accordion-item shadow-sm mb-3">
    <h2 class="accordion-header">
      <button class="accordion-button fw-bold">
        🗑️ Residuos – Manejo
      </button>
    </h2>
    <div class="accordion-body">
      <p><strong>Personas que manipulan:</strong> {{ residuos.personas_manipulan }}</p>
      <p><strong>Capacita personal:</strong> {{ siNo(residuos.capacita_personal) }}</p>

      <div v-if="residuos.capacita_personal == 1" class="border rounded p-2 bg-light mb-2">
        <p><strong>Personas capacitadas:</strong> {{ residuos.personas_capacitadas }}</p>
        <p><strong>% Capacitado:</strong> {{ residuos.porcentaje_capacitadas }} %</p>
      </div>

      <div v-if="residuos.observaciones" class="obs-box">
        {{ residuos.observaciones }}
      </div>
    </div>
  </div>

  <!-- 🧪 Sustancias – Manejo -->
  <div v-if="sustancias" class="accordion-item shadow-sm mb-3">
    <h2 class="accordion-header">
      <button class="accordion-button fw-bold">
        🧪 Sustancias – Manejo
      </button>
    </h2>
    <div class="accordion-body">
      <p><strong>Cuenta con POES:</strong> {{ siNo(sustancias.cuenta_poes) }}</p>

      <div v-if="sustancias.cuenta_poes == 1 && sustancias.poes_preview" class="mb-3">
        <strong>POES cargado:</strong>
        <img
          :src="sustancias.poes_preview"
          class="img-fluid rounded mt-2"
          style="width: 20% !important;"
        />
      </div>

      <p><strong>Personal capacitado:</strong> {{ siNo(sustancias.personal_capacitado) }}</p>
      <p><strong>Almacenamiento adecuado:</strong> {{ siNo(sustancias.almacenamiento_adecuado) }}</p>

      <div v-if="sustancias.observaciones" class="obs-box">
        {{ sustancias.observaciones }}
      </div>
    </div>
  </div>

</div>


    <!-- ================= FORMULARIO ================= -->
    <div class="card-component">
      <div class="card-header-green">Evaluación Manejo de Vertimientos</div>

      <label>¿Cuenta con permiso(s) de vertimientos?</label>
      <select v-model="form.permiso_vertimientos" class="form-control">
        <option value="">Seleccione</option>
        <option :value="1">Sí</option>
        <option :value="0">No</option>
      </select>

      <div v-if="form.permiso_vertimientos == 1">
        <label>Número de vertimientos con permiso</label>
        <input type="number" v-model="form.numero_vertimientos_permitidos" class="form-control">

        <label>Número total de vertimientos</label>
        <input type="number" v-model="form.numero_vertimientos_totales" class="form-control">
      </div>

      <label>¿Sistema tratamiento agua doméstica?</label>
      <select v-model="form.sistema_agua_domestica" class="form-control">
        <option value="">Seleccione</option>
        <option :value="1">Sí</option>
        <option :value="0">No</option>
      </select>

      <label>¿Sistema tratamiento agroquímicos?</label>
      <select v-model="form.sistema_agroquimicos" class="form-control">
        <option value="">Seleccione</option>
        <option :value="1">Sí</option>
        <option :value="0">No</option>
      </select>

      <label>¿Cumple permiso?</label>
      <select v-model="form.cumple_permiso" class="form-control">
        <option value="">Seleccione</option>
        <option :value="1">Sí</option>
        <option :value="0">No</option>
      </select>

      <label>¿Ha gestionado permiso?</label>
      <select v-model="form.gestion_permiso" class="form-control">
        <option value="">Seleccione</option>
        <option :value="1">Sí</option>
        <option :value="0">No</option>
      </select>

      <label>¿Realiza triple lavado?</label>
      <select v-model="form.triple_lavado" class="form-control">
        <option value="">Seleccione</option>
        <option :value="1">Sí</option>
        <option :value="0">No</option>
      </select>

      <label>Observaciones</label>
      <textarea v-model="form.observaciones" class="form-control"></textarea>

      <div class="acciones">
        <button class="btn btn-secondary" @click="volver">Volver</button>
        <button class="btn btn-success" @click="guardar">Guardar Offline</button>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getFormDataByVisita, saveFormData } from '../store/indexeddb'

const route = useRoute()
const router = useRouter()
const visitaId = Number(route.query.visita_id)

const componenteSeleccionado = ref('vertimientos-manejo')

const aguaCaptacion = ref(null)
const aguaUso = ref(null)
const suelo = ref(null)
const energia = ref(null)
const gobernanza = ref(null)
const emisiones = ref(null)
const residuos = ref(null)
const sustancias = ref(null)

const form = ref({
  visita_id: visitaId,
  permiso_vertimientos: '',
  numero_vertimientos_permitidos: '',
  numero_vertimientos_totales: '',
  sistema_agua_domestica: '',
  sistema_agroquimicos: '',
  cumple_permiso: '',
  gestion_permiso: '',
  triple_lavado: '',
  observaciones: ''
})

onMounted(async () => {
  aguaCaptacion.value = await getFormDataByVisita('agua_captacion_legal', visitaId)
  aguaUso.value = await getFormDataByVisita('agua_uso_eficiente', visitaId)
  suelo.value = await getFormDataByVisita('suelo_conservacion', visitaId)
  energia.value = await getFormDataByVisita('energia_uso_eficiente', visitaId)
  gobernanza.value = await getFormDataByVisita('gobernanza_hidrica', visitaId)
  emisiones.value = await getFormDataByVisita('emisiones_gei', visitaId)
  residuos.value = await getFormDataByVisita('residuos_manejo', visitaId)
  sustancias.value = await getFormDataByVisita('sustancias_manejo', visitaId)

  const saved = await getFormDataByVisita('vertimientos_manejo', visitaId)
  if (saved) Object.assign(form.value, saved)
})

watch(() => form.value.permiso_vertimientos, val => {
  if (val != 1) {
    form.value.numero_vertimientos_permitidos = ''
    form.value.numero_vertimientos_totales = ''
  }
})

async function guardar() {
  const requeridos = [
    'permiso_vertimientos',
    'sistema_agua_domestica',
    'sistema_agroquimicos',
    'cumple_permiso',
    'gestion_permiso',
    'triple_lavado'
  ]

  for (const campo of requeridos) {
    if (!form.value[campo]) {
      alert('Complete todos los campos obligatorios')
      return
    }
  }

  if (form.value.permiso_vertimientos == 1 &&
      (!form.value.numero_vertimientos_permitidos || !form.value.numero_vertimientos_totales)) {
    alert('Debe completar los campos de vertimientos permitidos')
    return
  }

  await saveFormData('vertimientos_manejo', form.value)
  alert('Vertimientos guardado offline')
  router.push({
      path: '/hmp-manejo',
      query: { visita_id: visitaId }
    })

    
}

function irAComponente() {
  router.push({ path: `/${componenteSeleccionado.value}`, query: { visita_id: visitaId } })
}

function volver() {
  router.push({ path: '/sustancias-manejo', query: { visita_id: visitaId } })
}

function siNo(valor) {
  return valor === 1 || valor === '1' ? 'Sí' : 'No'
}

</script>

<style scoped>
.container { background: rgba(129,165,114,.93); padding:20px; border-radius:12px; }
.title { color:#fff; text-align:center; margin-bottom:20px; }
.card-component { background:#f1f6f1; padding:18px; border-radius:10px; margin-bottom:12px; }
.card-header-green { background:#28a745; color:#fff; padding:10px; font-weight:bold; }
.acciones { display:flex; justify-content:space-between; margin-top:12px; }

/* RESPONSIVE */

@media (max-width: 768px) {
  .offline-container{
    margin-left: -240px;
    margin-top: 65PX;
  }

  .button-group {
    flex-direction: column;
  }
  
  .button-group .btn {
    width: 100%;
    margin-bottom: 5px;
  }
}

</style>

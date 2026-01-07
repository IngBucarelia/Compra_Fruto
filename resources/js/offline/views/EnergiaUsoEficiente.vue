<template>
  <div class="offline-container offline-form-container">

    <!-- ================= TÍTULO ================= -->
    <h2 class="offline-title">
      ⚡ Energía — Uso Eficiente (Modo Offline)
    </h2>

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
          <option value="vertimientos-manejo">💦 Vertimientos - Manejo</option>
          <option value="hmp-manejo">☣️ HMP - Manejo</option>
          <option value="avc-control">🛡️ AVC - Control</option>
          <option value="ecosistema-proteccion">🌳 Ecosistema - Protección</option>
          <option value="noremplazo-nodeforestacion">🚫 No Deforestación</option>
          <option value="cierre-visita">✅ Cierre de Visita</option>
        </select>
        <button class="btn btn-success" @click="irAComponente">
          Ir
        </button>
      </div>
    </div>

    <!-- ================= COMPONENTES ANTERIORES ================= -->

    <!-- Agua Captación Legal -->
    <div v-if="captacionLegal" class="accordion mb-3">
      <div class="accordion-item shadow-sm">
        <h2 class="accordion-header">
          <button class="accordion-button fw-bold">
            💧 Agua - Captación Legal
          </button>
        </h2>
        <div class="accordion-body">
          <p><strong>Permiso concesión:</strong> {{ siNo(captacionLegal.permiso_concesion) }}</p>
          <p><strong>Permiso ocupación cauce:</strong> {{ siNo(captacionLegal.permiso_ocupacion_cauce) }}</p>
          <p><strong>Permisos captación:</strong> {{ siNo(captacionLegal.permisos_captacion) }}</p>
          <p><strong>Registro agua:</strong> {{ siNo(captacionLegal.registro_agua) }}</p>
        </div>
      </div>
    </div>

    <!-- Agua Uso Eficiente -->
    <div v-if="usoEficiente" class="accordion mb-3">
      <div class="accordion-item shadow-sm">
        <h2 class="accordion-header">
          <button class="accordion-button fw-bold">
            🚰 Agua - Uso Eficiente
          </button>
        </h2>
        <div class="accordion-body">
          <p><strong>Plan ahorro:</strong> {{ siNo(usoEficiente.plan_ahorro) }}</p>
          <p><strong>Mantenimiento:</strong> {{ siNo(usoEficiente.mantenimiento_sistemas) }}</p>
          <p><strong>Consumo:</strong> {{ usoEficiente.consumo_agua ?? 'N/A' }}</p>
        </div>
      </div>
    </div>

    <!-- Suelo Conservación -->
    <div v-if="sueloConservacion" class="accordion mb-4">
      <div class="accordion-item shadow-sm">
        <h2 class="accordion-header">
          <button class="accordion-button fw-bold">
            🌱 Suelo – Conservación
          </button>
        </h2>

        <div class="accordion-body">
          <p>
            <strong>Prácticas de conservación:</strong>
            {{ siNo(sueloConservacion.practicas_conservacion) }}
          </p>

          <p>
            <strong>Control de erosión:</strong>
            {{ siNo(sueloConservacion.control_erosion) }}
          </p>

          <p>
            <strong>Cobertura vegetal:</strong>
            {{ siNo(sueloConservacion.cobertura_vegetal) }}
          </p>

          <p>
            <strong>Área intervenida:</strong>
            {{ sueloConservacion.area_intervenida }} ha
          </p>

          <p v-if="sueloConservacion.area_total_usada">
            <strong>Área total del predio:</strong>
            {{ sueloConservacion.area_total_usada }} ha
          </p>

          <p v-if="sueloConservacion.porcentaje">
            <strong>Porcentaje intervenido:</strong>
            {{ sueloConservacion.porcentaje }} %
          </p>

          <p v-if="sueloConservacion.observaciones">
            <strong>Observaciones:</strong>
            {{ sueloConservacion.observaciones }}
          </p>
        </div>
      </div>


    </div>

    <!-- ================= FORMULARIO ENERGÍA ================= -->
    <div class="card card-component">
      <div class="card-header-green">
        Registro de Uso Eficiente de la Energía
      </div>

      <div class="p-3">
        <form @submit.prevent="guardar">

          <div class="mb-3">
            <label class="fw-bold">
              ¿Cuenta con registro de consumo de combustible?
            </label>
            <select v-model="form.registro_consumo_combustible" class="form-select" required>
              <option value="">Seleccione…</option>
              <option :value="1">Sí</option>
              <option :value="0">No</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="fw-bold">
              ¿Tiene plan de uso eficiente de la energía?
            </label>
            <select v-model="form.plan_uso_eficiente" class="form-select" required>
              <option value="">Seleccione…</option>
              <option :value="1">Sí</option>
              <option :value="0">No</option>
            </select>
          </div>

          <div v-if="form.plan_uso_eficiente == 1" class="mb-3">
            <label class="fw-bold">Consumo de energía (kWh)</label>
            <input
              type="number"
              class="form-control"
              v-model.number="form.consumo_energia_kwh"
              required
            />
          </div>

          <div class="mb-3">
            <label class="fw-bold">¿Seguimiento a indicadores?</label>
            <select v-model="form.seguimiento_indicadores" class="form-select" required>
              <option value="">Seleccione…</option>
              <option :value="1">Sí</option>
              <option :value="0">No</option>
            </select>
          </div>

          <textarea
            class="form-control mb-3"
            v-model="form.observaciones"
            placeholder="Observaciones"
          ></textarea>

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" @click="volver">
              Volver
            </button>
            <button class="btn btn-success">
              Guardar offline
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getFormDataByVisita, saveFormData } from '../store/indexeddb'

const route = useRoute()
const router = useRouter()
const visitaId = Number(route.query.visita_id)

const componenteSeleccionado = ref('energia-uso-eficiente')

const captacionLegal = ref(null)
const usoEficiente = ref(null)
const sueloConservacion = ref(null)

const form = ref({
  visita_id: visitaId,
  registro_consumo_combustible: '',
  plan_uso_eficiente: '',
  consumo_energia_kwh: '',
  seguimiento_indicadores: '',
  observaciones: ''
})

onMounted(async () => {
  captacionLegal.value = await getFormDataByVisita('agua_captacion_legal', visitaId)
  usoEficiente.value = await getFormDataByVisita('agua_uso_eficiente', visitaId)
  sueloConservacion.value = await getFormDataByVisita('suelo_conservacion', visitaId)

  const saved = await getFormDataByVisita('energia_uso_eficiente', visitaId)
  if (saved) Object.assign(form.value, saved)
})

async function guardar() {
  await saveFormData('energia_uso_eficiente', form.value)
  alert('Registro de energía guardado offline')
    router.push({
      path: '/gobernanza-hidrica',
      query: { visita_id: visitaId }
    })
 
}

function irAComponente() {
  router.push({ path: `/${componenteSeleccionado.value}`, query: { visita_id: visitaId } })
}

function volver() {
  router.push({ path: '/sueloConservacion', query: { visita_id: visitaId } })
}

function siNo(v) {
  return v == 1 ? 'Sí' : 'No'
}
</script>

<style scoped>
@import '../styles/offline.css';
</style>

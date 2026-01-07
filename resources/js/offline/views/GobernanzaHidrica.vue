<template>
  <div class="container offline-form-container">

    <!-- ================= TÍTULO ================= -->
    <h3 class="title">
      🤝 Gobernanza Hídrica — Visita #{{ visitaId }}
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

        <button class="btn btn-success" @click="irAComponente">
          Ir
        </button>
      </div>
    </div>

    <!-- ================= ACORDEÓN INFO PREVIA ================= -->
    <div
      v-if="captacionLegal || usoEficiente || sueloConservacion || energiaUso"
      class="accordion mb-4"
    >

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

      <!-- Energía -->
      <div v-if="sueloConservacion" class="accordion mb-4">
      <div class="accordion-item shadow-sm">
        <h2 class="accordion-header">
          <button class="accordion-button fw-bold">
            ⚡ Energía - Uso Eficiente
          </button>
        </h2>
        <div class="accordion-body">
          <p><strong>Registro combustible:</strong> {{ siNo(energiaUso.registro_consumo_combustible) }}</p>
          <p><strong>Plan uso eficiente:</strong> {{ siNo(energiaUso.plan_uso_eficiente) }}</p>
          <p><strong>Consumo kWh:</strong> {{ energiaUso.consumo_energia_kwh }}</p>
          <p><strong>Seguimiento:</strong> {{ siNo(energiaUso.seguimiento_indicadores) }}</p>

          <div v-if="energiaUso.observaciones" class="obs-box">
            {{ energiaUso.observaciones }}
          </div>
        </div>
      </div>

    </div>
    </div>

    <!-- ================= FORMULARIO ================= -->
    <div class="card-component shadow-sm">
  <div class="card-header-green d-flex align-items-center gap-2">
    <i class="fas fa-handshake"></i>
    <span>Gobernanza del Recurso Hídrico</span>
  </div>

  <div class="p-3">
    <form @submit.prevent="guardar">

      <!-- Canales de comunicación -->
      <div class="mb-3">
        <label class="form-label fw-bold">
          ¿Cuenta con canales de comunicación con actores del territorio?
        </label>
        <select v-model="form.canales_comunicacion" class="form-select" required>
          <option value="">Seleccione…</option>
          <option :value="1">Sí</option>
          <option :value="0">No</option>
        </select>
      </div>

      <!-- Identificación de actores -->
      <div class="mb-3">
        <label class="form-label fw-bold">
          ¿Identifica actores potencialmente afectados?
        </label>
        <select v-model="form.identifica_actores_afectados" class="form-select" required>
          <option value="">Seleccione…</option>
          <option :value="1">Sí</option>
          <option :value="0">No</option>
        </select>
      </div>

      <!-- Participación -->
      <div class="mb-3">
        <label class="form-label fw-bold">
          ¿Participa en actividades de gestión del recurso hídrico?
        </label>
        <select v-model="form.participa_actividades_gestion" class="form-select" required>
          <option value="">Seleccione…</option>
          <option :value="1">Sí</option>
          <option :value="0">No</option>
        </select>
      </div>

      <!-- Observaciones -->
      <div class="mb-4">
        <label class="form-label fw-bold">Observaciones</label>
        <textarea
          v-model="form.observaciones"
          class="form-control"
          rows="3"
          placeholder="Observaciones adicionales (opcional)"
        ></textarea>
      </div>

      <!-- Acciones -->
      <div class="d-flex justify-content-between align-items-center">
        <button
          type="button"
          class="btn btn-outline-secondary"
          @click="volver"
        >
          <i class="fas fa-arrow-left me-1"></i> Volver
        </button>

        <button class="btn btn-success px-4">
          <i class="fas fa-save me-1"></i> Guardar offline
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

const componenteSeleccionado = ref('gobernanza-hidrica')

const captacionLegal = ref(null)
const usoEficiente = ref(null)
const sueloConservacion = ref(null)
const energiaUso = ref(null)

const form = ref({
  visita_id: visitaId,
  canales_comunicacion: '',
  identifica_actores_afectados: '',
  participa_actividades_gestion: '',
  observaciones: ''
})

onMounted(async () => {
  captacionLegal.value = await getFormDataByVisita('agua_captacion_legal', visitaId)
  usoEficiente.value = await getFormDataByVisita('agua_uso_eficiente', visitaId)
  sueloConservacion.value = await getFormDataByVisita('suelo_conservacion', visitaId)
  energiaUso.value = await getFormDataByVisita('energia_uso_eficiente', visitaId)

  const saved = await getFormDataByVisita('gobernanza_hidrica', visitaId)
  if (saved) Object.assign(form.value, saved)
})

async function guardar() {
  await saveFormData('gobernanza_hidrica', form.value)
  alert('Gobernanza hídrica guardada offline')
   router.push({
      path: '/emisiones-gei',
      query: { visita_id: visitaId }
    })
   
}

function irAComponente() {
  router.push({ path: `/${componenteSeleccionado.value}`, query: { visita_id: visitaId } })
}

function volver() {
  router.push({ path: '/energia-uso-eficiente', query: { visita_id: visitaId } })
}

function siNo(v) {
  return v == 1 ? 'Sí' : 'No'
}
</script>

<style scoped>
.container {
  background: rgba(129,165,114,.93);
  padding: 20px;
  border-radius: 12px;
}

.title {
  text-align: center;
  color: #fff;
  margin-bottom: 20px;
}

.card-component {
  background: #f1f6f1;
  padding: 18px;
  border-radius: 10px;
}

.card-header-green {
  background: #28a745;
  color: #fff;
  padding: 10px;
  font-weight: bold;
}

.obs-box {
  background: #eef3ee;
  padding: 8px;
  border-radius: 6px;
  margin-top: 8px;
}

select,
textarea {
  width: 100%;
  margin-bottom: 12px;
}

.acciones {
  display: flex;
  justify-content: space-between;
}

/*-------------------- nuevos estilos --------------- */

.card-component {
  border-radius: 12px;
  background: #f4f8f4;
}

.card-header-green {
  font-size: 1.1rem;
  font-weight: 700;
}

.card-header-green i {
  font-size: 1.2rem;
}

.form-label {
  color: #2f5d50;
}

.form-select,
.form-control {
  border-radius: 8px;
}

.form-select:focus,
.form-control:focus {
  border-color: #28a745;
  box-shadow: 0 0 0 0.15rem rgba(40, 167, 69, 0.25);
}

</style>

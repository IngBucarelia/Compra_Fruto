<template>
  <div class="offline-container offline-form-container" style="background-color: aliceblue;">

    <!-- ================= TÍTULO ================= -->
    <h3 class="title" style="color: darkolivegreen;">
      🏭 Emisiones GEI — Visita #{{ visitaId }}
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

    <!-- ================= ACORDEÓN INFO PREVIA ================= -->
    <div
      v-if="captacionLegal || usoEficiente || sueloConservacion || energiaUso || gobernanza"
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

      <!-- Gobernanza -->
      <div v-if="sueloConservacion" class="accordion mb-4">
      <div class="accordion-item shadow-sm">
        <h2 class="accordion-header">
          <button class="accordion-button fw-bold">🤝 Gobernanza Hídrica</button>
        </h2>
        <div class="accordion-body">
          <p><strong>Canales comunicación:</strong> {{ siNo(gobernanza.canales_comunicacion) }}</p>
          <p><strong>Identifica actores:</strong> {{ siNo(gobernanza.identifica_actores_afectados) }}</p>
          <p><strong>Participa en gestión:</strong> {{ siNo(gobernanza.participa_actividades_gestion) }}</p>
        </div>
      </div>

    </div>
    </div>

    <!-- ================= FORMULARIO GEI ================= -->
    <div class="card-component shadow-sm">
  <div class="card-header-green d-flex align-items-center gap-2">
    <i class="fas fa-cloud"></i>
    <span>Evaluación de Emisiones GEI</span>
  </div>

  <div class="p-3">
    <form @submit.prevent="guardar">

      <!-- Cuantificación GEI -->
      <div class="mb-3">
        <label class="form-label fw-bold">
          ¿Cuantifica emisiones de Gases de Efecto Invernadero (GEI)?
        </label>
        <select v-model="form.cuantifica_emisiones" class="form-select" required>
          <option value="">Seleccione…</option>
          <option :value="1">Sí</option>
          <option :value="0">No</option>
        </select>
      </div>

      <!-- Datos de cálculo -->
      <div v-if="form.cuantifica_emisiones == 1" class="border rounded p-3 mb-3 bg-light">

        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label fw-bold">Consumo de combustible</label>
            <input
              type="number"
              step="0.01"
              class="form-control"
              v-model.number="form.combustible"
              placeholder="Ej. litros"
            />
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-bold">Distancia recorrida</label>
            <input
              type="number"
              step="0.01"
              class="form-control"
              v-model.number="form.distancia"
              placeholder="Ej. km"
            />
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-bold">Huella de carbono</label>
            <input
              type="number"
              step="0.0001"
              class="form-control bg-white"
              :value="huellaCarbono"
              readonly
            />
          </div>
        </div>

        <div class="alert alert-info mb-0">
          <i class="fas fa-info-circle me-1"></i>
          La huella de carbono se calcula automáticamente.
        </div>
      </div>

      <!-- Acciones de reducción -->
      <div class="mb-3">
        <label class="form-label fw-bold">
          ¿Implementa acciones de reducción de emisiones?
        </label>
        <select
          v-model="form.implementa_acciones_reduccion"
          class="form-select"
          required
        >
          <option value="">Seleccione…</option>
          <option :value="1">Sí</option>
          <option :value="0">No</option>
        </select>
      </div>

      <div v-if="form.implementa_acciones_reduccion == 1" class="mb-3">
        <label class="form-label fw-bold">Acciones implementadas</label>
        <textarea
          v-model="form.acciones_reduccion"
          class="form-control"
          rows="3"
          placeholder="Describa las acciones de reducción"
        ></textarea>
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
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getFormDataByVisita, saveFormData } from '../store/indexeddb'

const route = useRoute()
const router = useRouter()
const visitaId = Number(route.query.visita_id)

const componenteSeleccionado = ref('emisiones-gei')

const captacionLegal = ref(null)
const usoEficiente = ref(null)
const sueloConservacion = ref(null)
const energiaUso = ref(null)
const gobernanza = ref(null)

const form = ref({
  visita_id: visitaId,
  cuantifica_emisiones: '',
  combustible: '',
  distancia: '',
  implementa_acciones_reduccion: '',
  acciones_reduccion: '',
  observaciones: ''
})

const huellaCarbono = computed(() => {
  if (form.value.combustible > 0 && form.value.distancia > 0) {
    return (form.value.combustible / form.value.distancia).toFixed(4)
  }
  return ''
})

watch(() => form.value.cuantifica_emisiones, v => {
  if (v != 1) {
    form.value.combustible = ''
    form.value.distancia = ''
  }
})

watch(() => form.value.implementa_acciones_reduccion, v => {
  if (v != 1) form.value.acciones_reduccion = ''
})

onMounted(async () => {
  captacionLegal.value = await getFormDataByVisita('agua_captacion_legal', visitaId)
  usoEficiente.value = await getFormDataByVisita('agua_uso_eficiente', visitaId)
  sueloConservacion.value = await getFormDataByVisita('suelo_conservacion', visitaId)
  energiaUso.value = await getFormDataByVisita('energia_uso_eficiente', visitaId)
  gobernanza.value = await getFormDataByVisita('gobernanza_hidrica', visitaId)

  const saved = await getFormDataByVisita('emisiones_gei', visitaId)
  if (saved) Object.assign(form.value, saved)
})

async function guardar() {
  await saveFormData('emisiones_gei', { ...form.value, huella_carbono: huellaCarbono.value })
  alert('Emisiones GEI guardadas offline')
  router.push({
      path: '/residuos-manejo',
      query: { visita_id: visitaId }
    })

}

function irAComponente() {
  router.push({ path: `/${componenteSeleccionado.value}`, query: { visita_id: visitaId } })
}

function volver() {
  router.push({ path: '/gobernanza-hidrica', query: { visita_id: visitaId } })
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

/* -------------------------------------------------------------    */

.card-component {
  border-radius: 12px;
  background: #f4f8f4;
}

.card-header-green {
  font-size: 1.1rem;
  font-weight: 700;
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
.offline-form-container {
  max-width: 1100px;        /* desktop cómodo */
  margin: 0 auto;
  padding: 1.5rem;
  margin-left: -60px;
}

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
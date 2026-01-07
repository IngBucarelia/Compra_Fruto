<template>
  <div class="offline-container offline-form-container">

    <!-- ================= TÍTULO ================= -->
    <h2 class="offline-title">
      🌱 Suelo — Conservación (Modo Offline)
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

    <!-- ================= AGUA - CAPTACIÓN LEGAL ================= -->
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

          <div v-if="captacionLegal.observaciones" class="bg-light p-2 rounded">
            <strong>Observaciones:</strong><br>
            {{ captacionLegal.observaciones }}
          </div>
        </div>
      </div>
    </div>

    <!-- ================= AGUA - USO EFICIENTE ================= -->
    <div v-if="usoEficiente" class="accordion mb-4">
      <div class="accordion-item shadow-sm">
        <h2 class="accordion-header">
          <button class="accordion-button fw-bold">
            🚰 Agua - Uso Eficiente
          </button>
        </h2>

        <div class="accordion-body">
          <p><strong>Plan ahorro:</strong> {{ siNo(usoEficiente.plan_ahorro) }}</p>
          <p><strong>Mantenimiento:</strong> {{ siNo(usoEficiente.mantenimiento_sistemas) }}</p>
          <p><strong>Uso balance:</strong> {{ siNo(usoEficiente.uso_informacion_balance) }}</p>
          <p><strong>Mecanismo medición:</strong> {{ siNo(usoEficiente.mecanismo_medicion) }}</p>

          <p v-if="usoEficiente.consumo_agua">
            <strong>Consumo (m³):</strong> {{ usoEficiente.consumo_agua }}
          </p>

          <div v-if="usoEficiente.observaciones" class="bg-light p-2 rounded">
            <strong>Observaciones:</strong><br>
            {{ usoEficiente.observaciones }}
          </div>
        </div>
      </div>
    </div>


    <!-- ================= INFO PREDIO ================= -->
    <div v-if="datosPredio" class="card card-component mb-4">
      <div class="card-header-green">
        🏡 Información del Predio
      </div>

      <div class="p-3">
        <p><strong>Nombre:</strong> {{ datosPredio.nombre_predio }}</p>
        <p><strong>Área total (ha):</strong> {{ datosPredio.area_total }}</p>
        <p><strong>Municipio:</strong> {{ datosPredio.municipio }}</p>
      </div>
    </div>

    <!-- ================= ALERTA ÁREA ================= -->
    <div v-if="usarAreaManual" class="alert alert-warning">
      No se encontró el área total del predio. Debe ingresarla manualmente.
    </div>

    <!-- ================= VISTA LECTURA ================= -->
    <div v-if="registro && !editando" class="card card-component mb-4">
      <div class="card-header-green">
        🌱 Conservación del suelo (registrado)
      </div>

      <div class="p-3">
        <p><strong>Prácticas:</strong> {{ siNo(registro.practicas_conservacion) }}</p>
        <p><strong>Control erosión:</strong> {{ siNo(registro.control_erosion) }}</p>
        <p><strong>Cobertura vegetal:</strong> {{ siNo(registro.cobertura_vegetal) }}</p>
        <p><strong>Área intervenida (ha):</strong> {{ registro.area_intervenida }}</p>
        <p><strong>Porcentaje:</strong> {{ porcentajeCalculado }} %</p>

        <div class="d-flex gap-2 mt-3">
          <button class="btn btn-warning" @click="editando = true">Editar</button>
          <button class="btn btn-secondary" @click="volver">Volver</button>
        </div>
      </div>
    </div>

    <!-- ================= FORMULARIO ================= -->
    <div v-else class="card card-component">
      <div class="card-header-green">
        🌱 Registrar / Editar Conservación
      </div>

      <div class="p-3">
        <form @submit.prevent="guardar">

          <div v-if="usarAreaManual" class="mb-3">
            <label class="form-label">Área total del predio (ha)</label>
            <input
              type="number"
              class="form-control"
              v-model.number="areaManual"
              required
            />
          </div>

          <select v-model="form.practicas_conservacion" class="form-select mb-3">
            <option disabled value="">¿Prácticas de conservación?</option>
            <option :value="1">Sí</option>
            <option :value="0">No</option>
          </select>

          <select v-model="form.control_erosion" class="form-select mb-3">
            <option disabled value="">¿Control de erosión?</option>
            <option :value="1">Sí</option>
            <option :value="0">No</option>
          </select>

          <select v-model="form.cobertura_vegetal" class="form-select mb-3">
            <option disabled value="">¿Cobertura vegetal?</option>
            <option :value="1">Sí</option>
            <option :value="0">No</option>
          </select>

          <input
            type="number"
            class="form-control mb-3"
            placeholder="Área intervenida (ha)"
            v-model.number="form.area_intervenida"
          />

        

          <p><strong>Porcentaje:</strong> {{ porcentajeCalculado }} %</p>

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
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getFormDataByVisita, saveFormData } from '../store/indexeddb'



const componenteSeleccionado = ref('SueloConservacion')

const captacionLegal = ref(null)   // ✅ FALTABA
const usoEficiente = ref(null)     // ✅ FALTABA

const registro = ref(null)


const route = useRoute()
const router = useRouter()

const visitaId = Number(route.query.visita_id)


const datosPredio = ref(null)
const editando = ref(false)

const areaManual = ref(null)
const usarAreaManual = ref(false)

const form = ref({
  visita_id: visitaId,
  practicas_conservacion: '',
  control_erosion: '',
  cobertura_vegetal: '',
  area_intervenida: '',
  porcentajeCalculado: '',
  observaciones: ''
})

const areaParaCalculo = computed(() => {
  return datosPredio.value?.area_total || areaManual.value
})

const porcentajeCalculado = computed(() => {
  if (!areaParaCalculo.value || !form.value.area_intervenida) return 0
  return ((form.value.area_intervenida / areaParaCalculo.value) * 100).toFixed(2)
})

onMounted(async () => {
  datosPredio.value = await getFormDataByVisita('datos_predio', visitaId)

  if (!datosPredio.value?.area_total) {
    usarAreaManual.value = true
    editando.value = true
  }

  // 🔹 AQUI FALTABA ESTO 🔹
  captacionLegal.value = await getFormDataByVisita(
    'agua_captacion_legal',
    visitaId
  )

  usoEficiente.value = await getFormDataByVisita(
    'agua_uso_eficiente', // 👈 EXACTAMENTE como se guardó
      visitaId
    )

  registro.value = await getFormDataByVisita(
    'suelo_conservacion',
    visitaId
  )

  if (registro.value) {
    Object.assign(form.value, registro.value)
  }
})


async function guardar() {
  const porcentaje = porcentajeCalculado.value

  await saveFormData('suelo_conservacion', {
    ...form.value,
    visita_id: visitaId,
    area_total_usada: areaParaCalculo.value,
    porcentaje: porcentaje 
  })

  registro.value = {
    ...form.value,
    area_total_usada: areaParaCalculo.value,
    porcentaje: porcentaje
  }

  editando.value = false

  router.push({
  path: '/energia-uso-eficiente',
  query: { visita_id: visitaId }
})
}


function irAComponente() {
  router.push({ path: `/${componenteSeleccionado.value}`, query: { visita_id: visitaId } })
}

function volver() {
  router.push({ path: '/agua-uso-eficiente', query: { visita_id: visitaId } })
}

function siNo(v) {
  return v == 1 ? 'Sí' : 'No'
}
</script>

<style scoped>
@import '../styles/offline.css';
</style>

<template>
  <div class="offline-container offline-form-container">

    <!-- ================= TÍTULO ================= -->
    <h2 class="offline-title">
      🚰 Agua — Uso Eficiente (Modo Offline)
    </h2>

    <!-- ================= SELECTOR DE COMPONENTE ================= -->
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
          <i class="fas fa-arrow-right me-2"></i>Ir
        </button>
      </div>
    </div>

    <!-- ================= CAPTACIÓN LEGAL (SOLO LECTURA) ================= -->
    <div v-if="captacionLegal" class="accordion mb-4">
      <div class="accordion-item shadow-sm">
        <h2 class="accordion-header">
          <button class="accordion-button fw-bold">
            💧 Agua - Captación Legal (Registrada)
          </button>
        </h2>

        <div class="accordion-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Permiso concesión:</strong> {{ siNo(captacionLegal.permiso_concesion) }}</p>
              <p><strong>Permiso ocupación cauce:</strong> {{ siNo(captacionLegal.permiso_ocupacion_cauce) }}</p>
              <p><strong>Permisos captación:</strong> {{ siNo(captacionLegal.permisos_captacion) }}</p>
            </div>

            <div class="col-md-6">
              <p><strong>Registro agua:</strong> {{ siNo(captacionLegal.registro_agua) }}</p>
              <p><strong>Cumple manejo:</strong> {{ siNo(captacionLegal.cumple_manejo_construccion) }}</p>
              <p><strong>Gestión permiso:</strong> {{ siNo(captacionLegal.gestion_permiso_captacion) }}</p>
            </div>

            <div v-if="captacionLegal.observaciones" class="col-12 mt-3">
              <strong>Observaciones:</strong>
              <div class="bg-light p-3 rounded border">
                {{ captacionLegal.observaciones }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================= USO EFICIENTE ================= -->

    <!-- VISTA SOLO LECTURA -->
    <div v-if="usoEficiente && !editando" class="card card-component mb-4">
      <div class="card-header-green">
        🚰 Uso eficiente del agua (registrado)
      </div>

      <div class="p-3">
        <p><strong>Plan ahorro:</strong> {{ siNo(usoEficiente.plan_ahorro) }}</p>
        <p><strong>Mantenimiento sistemas:</strong> {{ siNo(usoEficiente.mantenimiento_sistemas) }}</p>
        <p><strong>Uso balance hídrico:</strong> {{ siNo(usoEficiente.uso_informacion_balance) }}</p>
        <p><strong>Mecanismo medición:</strong> {{ siNo(usoEficiente.mecanismo_medicion) }}</p>

        <p v-if="usoEficiente.consumo_agua">
          <strong>Consumo agua (m³):</strong> {{ usoEficiente.consumo_agua }}
        </p>

        <p v-if="usoEficiente.observaciones">
          <strong>Observaciones:</strong> {{ usoEficiente.observaciones }}
        </p>

        <div class="d-flex gap-2 mt-3">
          <button class="btn btn-warning" @click="editando = true">
            ✏️ Editar
          </button>

          <button class="btn btn-secondary" @click="volver">
            ⬅️ Volver
          </button>
        </div>
      </div>
    </div>

    <!-- FORMULARIO -->
    <div v-else class="card card-component">
      <div class="card-header-green">
        🚰 Registrar / Editar — Uso eficiente del agua
      </div>

      <div class="p-3">
        <form @submit.prevent="guardar">

          <select v-model="form.plan_ahorro" class="form-select mb-3">
            <option disabled value="">Plan de ahorro</option>
            <option :value="1">Sí</option>
            <option :value="0">No</option>
          </select>

          <select v-model="form.mantenimiento_sistemas" class="form-select mb-3">
            <option disabled value="">Mantenimiento sistemas</option>
            <option :value="1">Sí</option>
            <option :value="0">No</option>
          </select>

          <select v-model="form.uso_informacion_balance" class="form-select mb-3">
            <option disabled value="">Uso balance hídrico</option>
            <option :value="1">Sí</option>
            <option :value="0">No</option>
          </select>

          <select v-model="form.mecanismo_medicion" class="form-select mb-3">
            <option disabled value="">Mecanismo medición</option>
            <option :value="1">Sí</option>
            <option :value="0">No</option>
          </select>

          <div v-if="form.mecanismo_medicion == 1" class="mb-3">
            <input
              type="number"
              class="form-control"
              v-model="form.consumo_agua"
              placeholder="Consumo de agua (m³)"
            />
          </div>

          <textarea
            class="form-control mb-3"
            v-model="form.observaciones"
            placeholder="Observaciones"
          ></textarea>

          <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-secondary" @click="volver">
              ⬅️ Volver
            </button>

            <button class="btn btn-success">
              💾 Guardar offline
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getFormDataByVisita, saveFormData } from '../store/indexeddb'

const route = useRoute()
const router = useRouter()

const visitaId = computed(() => route.query.visita_id)

const componenteSeleccionado = ref('agua-uso-eficiente')

const captacionLegal = ref(null)
const usoEficiente = ref(null)
const editando = ref(false)

const form = ref({
  visita_id: null,
  plan_ahorro: '',
  mantenimiento_sistemas: '',
  uso_informacion_balance: '',
  mecanismo_medicion: '',
  consumo_agua: '',
  observaciones: ''
})

onMounted(async () => {
  if (!visitaId.value) return

  form.value.visita_id = visitaId.value

  captacionLegal.value = await getFormDataByVisita(
    'agua_captacion_legal',
    visitaId.value
  )

  usoEficiente.value = await getFormDataByVisita(
    'agua_uso_eficiente',
    visitaId.value
  )

  if (usoEficiente.value) {
    Object.assign(form.value, usoEficiente.value)
  }
})

async function guardar() {

  await saveFormData('agua_uso_eficiente', {
    ...form.value,
    visita_id: visitaId.value
  })

  usoEficiente.value = { ...form.value }
  editando.value = false

  // 👉 REDIRECCIÓN
  router.push('/SueloConservacion')
}



saveFormData

function irAComponente() {
  router.push({
    path: `/${componenteSeleccionado.value}`,
    query: { visita_id: visitaId.value }
  })
}

function volver() {
  router.push({
    path: '/agua-captacion-legal',
    query: { visita_id: visitaId.value }
  })
}

function siNo(valor) {
  return valor == 1 ? 'Sí' : 'No'
}
</script>

<style scoped>
@import '../styles/offline.css';
</style>

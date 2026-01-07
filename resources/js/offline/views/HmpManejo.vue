<template>
  <div class="offline-container offline-form-container" style="background-color: aliceblue;margin-top: 70px;">

    <h3 class="title" style="color: darkolivegreen;">
      ☣️ HMP — Manejo — Visita #{{ visitaId }}
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
          <option value="vertimientos-manejo">💦 Vertimientos - Manejo</option>
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
      v-if="agua || uso || suelo || energia || gobernanza || emisiones || residuos || sustancias || vertimientos"
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




      <div v-if="vertimientos" class="accordion-item shadow-sm mb-3">
        <h2 class="accordion-header">
        <button class="accordion-button fw-bold">💦 Vertimientos</button>
        </h2>
        <div class="accordion-body">
        <p><strong>Permiso vertimientos:</strong> {{ vertimientos.permiso_vertimientos }}</p>
        <p><strong>Numero vertimientos permitidos:</strong> {{ vertimientos.numero_vertimientos_permitidos }}</p>
        <p><strong>Numero vertimientos totales:</strong> {{ vertimientos.numero_vertimientos_totales }}</p>
        <p><strong>Sistema agua domestica:</strong> {{ vertimientos.sistema_agua_domestica }}</p>
        <p><strong>Sistema agroquimicos:</strong> {{ vertimientos.sistema_agroquimicos }}</p>
        <p><strong>Cumple permiso:</strong> {{ vertimientos.cumple_permiso }}</p>
        <p><strong>Gestion permiso:</strong> {{ vertimientos.gestion_permiso }}</p>
        <p><strong>Triple lavado:</strong> {{ vertimientos.triple_lavado }}</p>
        <p><strong>Observaciones:</strong> {{ vertimientos.observaciones }}</p>
        </div>
      </div>
    </div>

    <!-- ================= FORMULARIO HMP ================= -->
    <div class="card-component">
      <div class="card-header-green">Formulario HMP</div>

      <!-- 🔹 ÁREA MANUAL (MISMO PATRÓN DE SUELO) -->
      <div v-if="usarAreaManual" class="mb-3">
        <label class="form-label">Área total del predio (ha)</label>
        <input
          type="number"
          class="form-control"
          v-model.number="areaManual"
          required
        />
      </div>

      <label>¿Implementa HMP según el diseño?</label>
      <select v-model="form.implementa_hmp" class="form-control">
        <option value="">Seleccione</option>
        <option value="si">Sí</option>
        <option value="no">No</option>
      </select>

      <!-- SOLO SI ES SI -->
      <div v-if="form.implementa_hmp === 'si'" class="mt-3">

        <label>
          🌿 Hectáreas con HMP implementadas
          <small class="text-muted">
            (Área total: {{ areaParaCalculo }} Ha)
          </small>
        </label>

        <div class="input-group">
          <input
            type="number"
            step="0.01"
            class="form-control"
            v-model.number="form.hectareas_hmp"
          />
          <span class="input-group-text">Ha</span>
        </div>

        <div class="alert alert-info mt-2">
          <strong>Porcentaje:</strong>
          <span :class="porcentajeClass">{{ porcentaje }}%</span>
        </div>

        <small class="text-muted">
          ({{ form.hectareas_hmp || 0 }} ÷ {{ areaParaCalculo }}) × 100
        </small>
      </div>

      <label class="mt-3">¿Incluye HMP en diseño/rediseño?</label>
      <select v-model="form.incluye_hmp_disenio" class="form-control">
        <option value="">Seleccione</option>
        <option value="si">Sí</option>
        <option value="no">No</option>
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
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getFormDataByVisita, saveFormData } from '../store/indexeddb'

const route = useRoute()
const router = useRouter()
const visitaId = Number(route.query.visita_id)

const componenteSeleccionado = ref('hmp-manejo')

const aguaCaptacion = ref(null)
const aguaUso = ref(null)
const suelo = ref(null)
const energia = ref(null)
const gobernanza = ref(null)
const emisiones = ref(null)
const residuos = ref(null)
const sustancias = ref(null)
const vertimientos = ref(null)

const areaTotal = ref(null)
const areaManual = ref(null)
const usarAreaManual = ref(false)

const form = ref({
  visita_id: visitaId,
  implementa_hmp: '',
  hectareas_hmp: '',
  porcentaje_hmp: '',
  incluye_hmp_disenio: '',
  observaciones: ''
})

const areaParaCalculo = computed(() => {
  return areaTotal.value > 0 ? areaTotal.value : areaManual.value
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
  vertimientos.value = await getFormDataByVisita('vertimientos_manejo', visitaId)

  if (suelo.value?.area_total_usada) {
    areaTotal.value = Number(suelo.value.area_total_usada)
  }

  if (!areaTotal.value || areaTotal.value <= 0) {
    usarAreaManual.value = true
  }

  const saved = await getFormDataByVisita('hmp_manejo', visitaId)
  if (saved) Object.assign(form.value, saved)
})

const porcentaje = computed(() => {
  if (!areaParaCalculo.value || !form.value.hectareas_hmp) return 0
  return ((form.value.hectareas_hmp / areaParaCalculo.value) * 100).toFixed(2)
})

const porcentajeClass = computed(() => {
  if (porcentaje.value >= 80) return 'text-success fw-bold'
  if (porcentaje.value >= 50) return 'text-warning fw-bold'
  return 'text-danger fw-bold'
})

watch(porcentaje, val => {
  form.value.porcentaje_hmp = val
})

async function guardar() {
  if (!form.value.implementa_hmp) {
    alert('Seleccione si implementa HMP')
    return
  }

  if (form.value.implementa_hmp === 'si') {
    if (!areaParaCalculo.value) {
      alert('Debe ingresar el área total del predio')
      return
    }

    if (!form.value.hectareas_hmp) {
      alert('Ingrese hectáreas con HMP')
      return
    }

    if (form.value.hectareas_hmp > areaParaCalculo.value) {
      alert('Las hectáreas con HMP superan el área total')
      return
    }
  }

  await saveFormData('hmp_manejo', {
    ...form.value,
    visita_id: visitaId,
    area_total_usada: areaParaCalculo.value
  })

  alert('HMP guardado offline')
  router.push({
      path: '/avc-control',
      query: { visita_id: visitaId }
    })

}

function irAComponente() {
  router.push({ path: `/${componenteSeleccionado.value}`, query: { visita_id: visitaId } })
}

function volver() {
  router.push({ path: '/vertimientos-manejo', query: { visita_id: visitaId } })
}

function siNo(valor) {
  return valor === 1 || valor === '1' ? 'Sí' : 'No'
}
</script>

<style scoped>
.container { background: rgba(129,165,114,.93); padding:20px; border-radius:12px; }
.title { color:#fff; text-align:center; margin-bottom:20px; }
.card-component { background:#f1f6f1; padding:18px; border-radius:10px; }
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

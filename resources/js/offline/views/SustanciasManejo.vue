<template>
  <div class="offline-container offline-form-container" style="background-color: aliceblue;margin-top: 60px;border-radius: 35px;">
    <h3 class="title" style="color: darkolivegreen; margin-top: 80px;">
      🧪 Sustancias — Manejo — Visita #{{ visitaId }}
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

    <!-- ================= ACORDEÓN INFORMACIÓN PREVIA ================= -->
    <div
      v-if="aguaCaptacion || aguaUso || suelo || energia || gobernanza || emisiones || residuos"
      class="accordion mb-4"
    >

       <!-- Agua Captación Legal -->
    <div v-if="aguaCaptacion" class="accordion mb-3">
      <div class="accordion-item shadow-sm">
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
    </div>

    <!-- Agua Uso Eficiente -->
    <div v-if="aguaUso" class="accordion mb-3">
      <div class="accordion-item shadow-sm">
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
    </div>

    <!-- Suelo Conservación -->
    <div v-if="suelo" class="accordion mb-4">
      <div class="accordion-item shadow-sm">
        <h2 class="accordion-header">
          <button class="accordion-button fw-bold">
            🌱 Suelo – Conservación
          </button>
        </h2>

        <div class="accordion-body">
          <p>
            <strong>Prácticas de conservación:</strong>
            {{ siNo(suelo.practicas_conservacion) }}
          </p>

          <p>
            <strong>Control de erosión:</strong>
            {{ siNo(suelo.control_erosion) }}
          </p>

          <p>
            <strong>Cobertura vegetal:</strong>
            {{ siNo(suelo.cobertura_vegetal) }}
          </p>

          <p>
            <strong>Área intervenida:</strong>
            {{ suelo.area_intervenida }} ha
          </p>

          <p v-if="suelo.area_total_usada">
            <strong>Área total del predio:</strong>
            {{ suelo.area_total_usada }} ha
          </p>

          <p v-if="suelo.porcentaje">
            <strong>Porcentaje intervenido:</strong>
            {{ suelo.porcentaje }} %
          </p>

          <p v-if="suelo.observaciones">
            <strong>Observaciones:</strong>
            {{ suelo.observaciones }}
          </p>
        </div>
      </div>
    </div>

      <!-- Energía -->
      <div v-if="energia" class="accordion mb-4">
      <div class="accordion-item shadow-sm">
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

    </div>

      <!-- Gobernanza -->
      <div v-if="gobernanza" class="accordion mb-4">
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


   <!-- Emisiones GEI -->
    <div v-if="emisiones" class="accordion mb-4">
      <div class="accordion-item shadow-sm">
        <h2 class="accordion-header">
          <button class="accordion-button fw-bold">
            🏭 Emisiones GEI
          </button>
        </h2>

        <div class="accordion-body">
          <p>
            <strong>Cuantifica emisiones:</strong>
            {{ siNo(emisiones.cuantifica_emisiones) }}
          </p>

          <div
            v-if="emisiones.cuantifica_emisiones == 1"
            class="border rounded p-2 mb-3 bg-light"
          >
            <p><strong>Combustible:</strong> {{ emisiones.combustible }}</p>
            <p><strong>Distancia:</strong> {{ emisiones.distancia }}</p>
            <p>
              <strong>Huella de carbono:</strong>
              {{ emisiones.huella_carbono }}
            </p>
          </div>

          <p>
            <strong>Implementa reducción:</strong>
            {{ siNo(emisiones.implementa_acciones_reduccion) }}
          </p>

          <p v-if="emisiones.acciones_reduccion">
            <strong>Acciones implementadas:</strong>
            {{ emisiones.acciones_reduccion }}
          </p>

          <div v-if="emisiones.observaciones" class="obs-box">
            {{ emisiones.observaciones }}
          </div>
        </div>
      </div>
    </div>

    </div>

      <!-- Residuos -->
      <div v-if="residuos" class="accordion mb-4">
        <div class="accordion-item shadow-sm">
          <h2 class="accordion-header">
            <button class="accordion-button fw-bold">
              🗑️ Residuos – Manejo
            </button>
          </h2>

          <div class="accordion-body">
            <p>
              <strong>Personas que manipulan residuos:</strong>
              {{ residuos.personas_manipulan }}
            </p>

            <p>
              <strong>Capacita al personal:</strong>
              {{ siNo(residuos.capacita_personal) }}
            </p>

            <div
              v-if="residuos.capacita_personal == 1"
              class="border rounded p-2 mb-3 bg-light"
            >
              <p>
                <strong>Personas capacitadas:</strong>
                {{ residuos.personas_capacitadas }}
              </p>
              <p>
                <strong>% Capacitado:</strong>
                {{ residuos.porcentaje_capacitadas }} %
              </p>
            </div>

            <div v-if="residuos.observaciones" class="obs-box">
              {{ residuos.observaciones }}
            </div>
          </div>
        </div>
      </div>



    <!-- ================= FORMULARIO SUSTANCIAS ================= -->
    <div class="card-component">
      <div class="card-header-green">Evaluación Manejo de Sustancias</div>

      <label>¿Cuenta con POES?</label>
        <select v-model="form.cuenta_poes" class="form-control">
        <option value="">Seleccione</option>
        <option :value="1">Sí</option>
        <option :value="0">No</option>
        </select>

        <!-- SOLO SI ES SÍ -->
        <div v-if="form.cuenta_poes == 1" class="mt-2">
        <label>Cargar POES (imagen)</label>
        <input
            type="file"
            accept="image/*"
            style="width: 20%;"
            class="form-control"
            @change="onFileChange"
        />

        <div v-if="form.poes_preview" class="mt-2">
            <img
            :src="form.poes_preview"
            style="max-width: 100%; border-radius: 8px;"
            />
        </div>
        </div>

      <label>¿Personal capacitado?</label>
      <select v-model="form.personal_capacitado" class="form-control">
        <option value="">Seleccione</option>
        <option :value="1">Sí</option>
        <option :value="0">No</option>
      </select>

      <label>¿Almacenamiento adecuado?</label>
      <select v-model="form.almacenamiento_adecuado" class="form-control">
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
import { ref, onMounted, watch  } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { getFormDataByVisita, saveFormData } from '../store/indexeddb'

const route = useRoute()
const router = useRouter()
const visitaId = Number(route.query.visita_id)

const componenteSeleccionado = ref('sustancias-manejo')

const aguaCaptacion = ref(null)
const aguaUso = ref(null)
const suelo = ref(null)
const energia = ref(null)
const gobernanza = ref(null)
const emisiones = ref(null)
const residuos = ref(null)

const form = ref({
  visita_id: visitaId,
  cuenta_poes: '',
  personal_capacitado: '',
  almacenamiento_adecuado: '',
  observaciones: '',
  poes_file: null,       
  poes_preview: null,    
})

onMounted(async () => {
  aguaCaptacion.value = await getFormDataByVisita('agua_captacion_legal', visitaId)
  aguaUso.value = await getFormDataByVisita('agua_uso_eficiente', visitaId)
  suelo.value = await getFormDataByVisita('suelo_conservacion', visitaId)
  energia.value = await getFormDataByVisita('energia_uso_eficiente', visitaId)
  gobernanza.value = await getFormDataByVisita('gobernanza_hidrica', visitaId)
  emisiones.value = await getFormDataByVisita('emisiones_gei', visitaId)
  residuos.value = await getFormDataByVisita('residuos_manejo', visitaId)

  const saved = await getFormDataByVisita('sustancias_manejo', visitaId)
  if (saved) Object.assign(form.value, saved)
})

async function guardar() {
  await saveFormData('sustancias_manejo', {
    ...form.value,
    poes_file: form.value.poes_file,
    poes_preview: form.value.poes_preview
  })

  alert('Sustancias guardado offline')
  router.push({
      path: '/vertimientos-manejo',
      query: { visita_id: visitaId }
    })

}


function irAComponente() {
  router.push({ path: `/${componenteSeleccionado.value}`, query: { visita_id: visitaId } })
}

function volver() {
  router.push({ path: '/residuos-manejo', query: { visita_id: visitaId } })
}

function siNo(v) {
  return v == 1 ? 'Sí' : 'No'
}

function onFileChange(e) {
  const file = e.target.files[0]
  if (!file) return

  form.value.poes_file = file

  const reader = new FileReader()
  reader.onload = () => {
    form.value.poes_preview = reader.result
  }
  reader.readAsDataURL(file)
}
watch(
  () => form.value.cuenta_poes,
  (val) => {
    if (val != 1) {
      form.value.poes_file = null
      form.value.poes_preview = null
    }
  }
)

</script>
<style>
    .container {
  background: rgba(129,165,114,.93);
  padding: 20px;
  border-radius: 12px;
}
.title {
  color:#fff;
  text-align:center;
  margin-bottom:20px;
}
.card-component {
  background:#f1f6f1;
  padding:18px;
  border-radius:10px;
}
.card-header-green {
  background:#28a745;
  color:#fff;
  padding:10px;
  font-weight:bold;
}
.obs-box {
  background:#eef3ee;
  padding:8px;
  border-radius:6px;
  margin-top:6px;
}
.acciones {
  display:flex;
  justify-content:space-between;
  margin-top:12px;
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
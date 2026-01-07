<template>
  <div class="offline-container offline-form-container" style="background-color: aliceblue;margin-top: 70px;">

    <h3 class="title" style="color: darkolivegreen;">
      🛡️ AVC — Control — Visita #{{ visitaId }}
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
      v-if="agua || uso || suelo || energia || gobernanza || emisiones || residuos || sustancias || vertimientos || hmp"
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

      <div v-if="hmp" class="accordion-item shadow-sm mb-3">
        <h2 class="accordion-header">
        <button class="accordion-button fw-bold">☣️ HMP</button>
        </h2>
        <div class="accordion-body">
          Implementa HMP: {{ hmp.implementa_hmp }} <br />
          Hectáreas: {{ hmp.hectareas_hmp }}
          Porcentaje: {{ hmp.porcentaje_hmp }}
          Hmp diseño: {{ hmp.incluye_hmp_disenio }}
          Observaciones: {{ hmp.observaciones }}  
        </div>
      </div>
    </div>

    <!-- ================= FORMULARIO AVC ================= -->
    <div class="card-component">
      <div class="card-header-green">Formulario AVC – Control</div>

      <label>¿Cuentan con registros de avistamientos y monitoreo?</label>
      <select v-model="form.registros_avistamientos" class="form-control">
        <option value="">Seleccione</option>
        <option value="si">Sí</option>
        <option value="no">No</option>
      </select>

      <label class="mt-3">¿Ha identificado AVC y ARC en su predio?</label>
      <select v-model="form.identifica_avc_arc" class="form-control">
        <option value="">Seleccione</option>
        <option value="si">Sí</option>
        <option value="no">No</option>
      </select>

      <!-- SOLO SI ES SI -->
      <div v-if="form.identifica_avc_arc === 'si'" class="mt-3">

        <label>🦜 Especies AVC / ARC identificadas</label>
        <textarea
          class="form-control"
          rows="4"
          v-model="form.especies_identificadas"
          placeholder="Una especie por línea"
        ></textarea>

        <div class="row mt-2">
          <div class="col-md-6">
            <label>Fecha de identificación</label>
            <input
              type="date"
              class="form-control"
              v-model="form.fecha_identificacion"
              :max="fechaHoy"
            />
          </div>

          <div class="col-md-6">
            <label>Ubicación aproximada</label>
            <input
              type="text"
              class="form-control"
              v-model="form.ubicacion_identificacion"
            />
          </div>
        </div>

        <label class="mt-2">Tipo de identificación</label>
        <div class="form-check">
          <input type="checkbox" value="avistamiento_directo" v-model="form.tipo_identificacion" />
          Avistamiento directo
        </div>
        <div class="form-check">
          <input type="checkbox" value="rastros_huellas" v-model="form.tipo_identificacion" />
          Rastros / Huellas
        </div>
        <div class="form-check">
          <input type="checkbox" value="monitoreo_camaras" v-model="form.tipo_identificacion" />
          Monitoreo con cámaras
        </div>
      </div>

      <label class="mt-3">¿Implementa medidas de manejo para conservación?</label>
      <select v-model="form.implementa_medidas_manejo" class="form-control">
        <option value="">Seleccione</option>
        <option value="si">Sí</option>
        <option value="no">No</option>
      </select>

      <label class="mt-3">Observaciones</label>
      <textarea v-model="form.observaciones" class="form-control"></textarea>

      <div class="acciones">
        <button class="btn btn-secondary" @click="volver">Volver</button>
        <button class="btn btn-success" @click="guardar">Guardar Offline</button>
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

const componenteSeleccionado = ref('avc-control')

const aguaCaptacion = ref(null)
const aguaUso = ref(null)
const suelo = ref(null)
const energia = ref(null)
const gobernanza = ref(null)
const emisiones = ref(null)
const residuos = ref(null)
const sustancias = ref(null)
const vertimientos = ref(null)
const hmp = ref(null)

const fechaHoy = new Date().toISOString().split('T')[0]

const form = ref({
  visita_id: visitaId,
  registros_avistamientos: '',
  identifica_avc_arc: '',
  especies_identificadas: '',
  fecha_identificacion: '',
  ubicacion_identificacion: '',
  tipo_identificacion: [],
  implementa_medidas_manejo: '',
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
  vertimientos.value = await getFormDataByVisita('vertimientos_manejo', visitaId)
  hmp.value = await getFormDataByVisita('hmp_manejo', visitaId)

  const saved = await getFormDataByVisita('avc_control', visitaId)
  if (saved) Object.assign(form.value, saved)
})

async function guardar() {
  if (!form.value.registros_avistamientos || !form.value.identifica_avc_arc) {
    alert('Complete los campos obligatorios')
    return
  }

  if (form.value.identifica_avc_arc === 'si') {
    if (!form.value.especies_identificadas || !form.value.fecha_identificacion) {
      alert('Debe completar la identificación de especies')
      return
    }

    if (form.value.tipo_identificacion.length === 0) {
      alert('Seleccione al menos un tipo de identificación')
      return
    }
  }

  await saveFormData('avc_control', form.value)
  alert('AVC guardado offline')
  router.push({
      path: '/ecosistema-proteccion',
      query: { visita_id: visitaId }
    })
 
}

function irAComponente() {
  router.push({ path: `/${componenteSeleccionado.value}`, query: { visita_id: visitaId } })
}

function volver() {
  router.push({ path: '/hmp-manejo', query: { visita_id: visitaId } })
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

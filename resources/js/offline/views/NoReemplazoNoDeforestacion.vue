<template>
  <div class="offline-container offline-form-container" style="background-color: aliceblue;margin-top: 30px;border-radius: 25px;">

    <h3 class="title" style="color: darkolivegreen;">
      🚫 No Deforestación — Visita #{{ visitaId }}
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
          <option value="noremplazo-nodeforestacion">🚫 No Deforestación</option>
          <option value="cierre-visita">Cierre de Visita</option>
        </select>
        <button class="btn btn-success" @click="irAComponente">Ir</button>
      </div>
    </div>

    <!-- ================= ACORDEÓN ================= -->
    <div
      v-if="agua || uso || suelo || energia || gobernanza || emisiones || residuos || sustancias || vertimientos || hmp || avc || ecosistema"
      class="accordion mb-4"
    >
      !-- 💧 Agua - Captación Legal -->
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
    
      <div v-if="avc" class="accordion-item shadow-sm mb-3">
        <h2 class="accordion-header">
        <button class="accordion-button fw-bold">🛡️ AVC - Control</button>
        </h2>
        <div class="accordion-body">
            Registros de avistamientos: {{ avc.registros_avistamientos }} <br />
            Identifica AVC / ARC: {{ avc.identifica_avc_arc }} <br />

            <div v-if="avc.identifica_avc_arc === 'si'">
            <strong>Especies:</strong>
            <pre style="white-space: pre-wrap">{{ avc.especies_identificadas }}</pre>
            <strong>Fecha:</strong> {{ avc.fecha_identificacion }} <br />
            <strong>Ubicación:</strong> {{ avc.ubicacion_identificacion }} <br />
            <strong>Tipo identificación:</strong>
            {{ avc.tipo_identificacion?.join(', ') }}
            </div>
        </div>
        </div>

      <div v-if="ecosistema" class="accordion-item shadow-sm mb-3">
        <h2 class="accordion-header">
        <button class="accordion-button fw-bold">
            🌳 Ecosistema – Protección
        </button>
        </h2>
        <div class="accordion-body">
            Planes manejo diferenciados:
            {{ ecosistema.planes_manejo_diferenciados }} <br />

            Conserva fragmentos:
            {{ ecosistema.acciones_conservacion_fragmentos }} <br />

            Manejo diferenciado:
            {{ ecosistema.implementa_planes_manejo_diferenciado }} <br />

            Respeta ronda hídrica:
            {{ ecosistema.respeta_distancias_ronda_hidrica }} <br />

            <span v-if="ecosistema.observaciones">
            Observaciones: {{ ecosistema.observaciones }}
            </span>
        </div>
        </div>

    </div>

    <!-- ================= FORMULARIO ================= -->
    <div class="card-component">
      <div class="card-header-green">Formulario No Deforestación</div>

      <!-- ÁREA -->
      <div v-if="usarAreaManual" class="alert alert-warning mb-3">
        <label>Área total de la finca (Ha)</label>
        <input type="number" class="form-control" v-model.number="areaManual" />
      </div>

      <div v-else class="alert alert-success mb-3">
        Área total de la finca: {{ areaTotal }} Ha
      </div>

      <label>¿Cuenta con estudios de AVC y ARC?</label>
      <select v-model="form.cuenta_estudios_avc_arc" class="form-control">
        <option value="">Seleccione</option>
        <option value="1">Sí</option>
        <option value="0">No</option>
      </select>

      <label class="mt-2">¿Cuenta con evidencias de no reemplazo?</label>
      <select v-model="form.evidencias_no_reemplazo_bosques" class="form-control">
        <option value="">Seleccione</option>
        <option value="1">Sí</option>
        <option value="0">No</option>
      </select>

      <label class="mt-2">¿Permiso forestal?</label>
      <select v-model="form.permiso_aprovechamiento_forestal" class="form-control">
        <option value="">Seleccione</option>
        <option value="1">Sí</option>
        <option value="0">No</option>
      </select>

      <label class="mt-2">¿Realizó restauración/compensación?</label>
      <select v-model="form.restauracion_compensacion" class="form-control">
        <option value="">Seleccione</option>
        <option value="1">Sí</option>
        <option value="0">No</option>
      </select>

      <!-- RESTAURACIÓN -->
      <div v-if="form.restauracion_compensacion === '1'" class="mt-3">
        <label>Hectáreas restauradas</label>
        <input type="number" class="form-control" v-model.number="form.hectareas_restauracion" />

        <label class="mt-2">Fecha restauración</label>
        <input type="date" class="form-control" v-model="form.fecha_restauracion" :max="fechaHoy" />

        <label class="mt-2">Tipo restauración</label>
        <select v-model="form.tipo_restauracion" class="form-control">
          <option value="">Seleccione</option>
          <option value="restauracion_ecologica">Restauración ecológica</option>
          <option value="compensacion_ambiental">Compensación ambiental</option>
          <option value="reforestacion">Reforestación</option>
          <option value="regeneracion_natural">Regeneración natural</option>
          <option value="otro">Otro</option>
        </select>

        <div v-if="form.tipo_restauracion === 'otro'" class="mt-2">
          <input class="form-control" v-model="form.otro_tipo_restauracion" placeholder="Especifique" />
        </div>

        <div class="alert alert-info mt-2">
          Porcentaje restauración: {{ porcentaje }} %
        </div>
      </div>

      <label class="mt-2">¿Dentro de frontera agrícola?</label>
      <select v-model="form.dentro_frontera_agricola" class="form-control">
        <option value="">Seleccione</option>
        <option value="1">Sí</option>
        <option value="0">No</option>
      </select>

      <label class="mt-2">Observaciones</label>
      <textarea class="form-control" v-model="form.observaciones"></textarea>

      <div class="acciones">
        <button class="btn btn-secondary" @click="volver">Volver</button>
        <button class="btn btn-success" @click="guardar">Guardar Offline</button>
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
const visitaId = Number(route.query.visita_id)

const componenteSeleccionado = ref('noremplazo-nodeforestacion')

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
const avc = ref(null)
const ecosistema = ref(null)


const areaManual = ref(null)
const usarAreaManual = ref(false)

const areaTotal = computed(() => suelo.value?.area_total_usada || areaManual.value || 0)

const fechaHoy = new Date().toISOString().split('T')[0]

const form = ref({
  visita_id: visitaId,
  cuenta_estudios_avc_arc: '',
  evidencias_no_reemplazo_bosques: '',
  permiso_aprovechamiento_forestal: '',
  restauracion_compensacion: '',
  hectareas_restauracion: '',
  fecha_restauracion: '',
  tipo_restauracion: '',
  otro_tipo_restauracion: '',
  dentro_frontera_agricola: '',
  observaciones: '',
  
})

const porcentaje = computed(() => {
  if (areaTotal.value > 0 && form.value.hectareas_restauracion > 0) {
    return ((form.value.hectareas_restauracion / areaTotal.value) * 100).toFixed(2)
  }
  return 0
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
  avc.value = await getFormDataByVisita('avc_control', visitaId)
  ecosistema.value = await getFormDataByVisita('ecosistema_proteccion', visitaId)


  if (!suelo.value?.area_total_usada) usarAreaManual.value = true

  const saved = await getFormDataByVisita('avc_no_reemplazo', visitaId)
  if (saved) Object.assign(form.value, saved)
})

async function guardar() {
  await saveFormData('avc_no_reemplazo', {
    ...form.value,
    porcentaje_restauracion: porcentaje.value,
    area_total_usada: areaTotal.value
  })
  alert('No deforestación guardado offline')
  router.push({
      path: '/cierre-visita',
      query: { visita_id: visitaId }
    })

}

function irAComponente() {
  router.push({ path: `/${componenteSeleccionado.value}`, query: { visita_id: visitaId } })
}

function volver() {
  router.push({ path: '/avc-control', query: { visita_id: visitaId } })
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

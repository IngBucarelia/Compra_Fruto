<template>
  <div class="container py-3">

    <h4 class="text-success mb-4">
      🌿 Revisión Final – Componente Ambiental
    </h4>

    <div class="accordion">

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
          Hectáreas: {{ hmp.hectareas_hmp }} <br>
          Porcentaje: {{ hmp.porcentaje_hmp }} <br>
          Hmp diseño: {{ hmp.incluye_hmp_disenio }} <br>
          Observaciones: {{ hmp.observaciones }}  <br>
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

        <div v-if="noDeforestacion" class="accordion-item shadow-sm mb-3">
            <h2 class="accordion-header">
                <button class="accordion-button fw-bold">
                🌳 No Deforestación
                </button>
            </h2>

            <div class="accordion-body">

                <p>
                <strong>Área total de la finca:</strong>
                {{ noDeforestacion.area_total ?? 'N/A' }} Ha
                </p>

                <p>
                <strong>Cuenta con estudios AVC / ARC:</strong>
                {{ siNo(noDeforestacion.cuenta_estudios_avc_arc) }}
                </p>

                <p>
                <strong>Evidencias de no reemplazo:</strong>
                {{ siNo(noDeforestacion.evidencias_no_reemplazo_bosques) }}
                </p>

                <p>
                <strong>Permiso forestal:</strong>
                {{ siNo(noDeforestacion.permiso_aprovechamiento_forestal) }}
                </p>

                <p>
                <strong>Dentro de frontera agrícola:</strong>
                {{ siNo(noDeforestacion.dentro_frontera_agricola) }}
                </p>

                <!-- RESTAURACIÓN -->
                <p>
                <strong>Realizó restauración / compensación:</strong>
                {{ siNo(noDeforestacion.restauracion_compensacion) }}
                </p>

                <div
                v-if="noDeforestacion.restauracion_compensacion == 1"
                class="border rounded p-3 bg-light mt-2"
                >
                <p>
                    <strong>Hectáreas restauradas:</strong>
                    {{ noDeforestacion.hectareas_restauracion }} Ha
                </p>

                <p>
                    <strong>Fecha restauración:</strong>
                    {{ noDeforestacion.fecha_restauracion }}
                </p>

                <p>
                    <strong>Tipo restauración:</strong>
                    {{ noDeforestacion.tipo_restauracion }}
                </p>

                <p v-if="noDeforestacion.otro_tipo_restauracion">
                    <strong>Otro tipo:</strong>
                    {{ noDeforestacion.otro_tipo_restauracion }}
                </p>

                <p v-if="noDeforestacion.porcentaje">
                    <strong>Porcentaje restaurado:</strong>
                    {{ noDeforestacion.porcentaje }} %
                </p>
                </div>

                <div
                v-if="noDeforestacion.observaciones"
                class="obs-box mt-3"
                >
                {{ noDeforestacion.observaciones }}
                </div>

            </div>
            </div>




      <!-- 🏁 Cierre -->
      <div v-if="cierreAmbiental" class="accordion-item shadow-sm mb-3">
        <h2 class="accordion-header">
          <button class="accordion-button fw-bold">🏁 Cierre de Visita Ambiental</button>
        </h2>
        <div class="accordion-body">
          <p><strong>Fecha cierre:</strong> {{ cierreAmbiental.fecha_cierre }}</p>
          <p><strong>Estado:</strong> {{ cierreAmbiental.estado_visita }}</p>

          <p v-if="cierreAmbiental.observaciones_finales">
            <strong>Observaciones:</strong> {{ cierreAmbiental.observaciones_finales }}
          </p>

          <p v-if="cierreAmbiental.recomendaciones">
            <strong>Recomendaciones:</strong> {{ cierreAmbiental.recomendaciones }}
          </p>
        </div>
        <!-- FIRMAS -->
        <div class="row mt-3">
        <div class="col-md-4 text-center" v-if="cierreAmbiental.firma_responsable">
            <p class="fw-bold">Firma Responsable</p>
            <img
            :src="cierreAmbiental.firma_responsable"
            class="img-fluid border rounded"
            style="max-height: 150px"
            />
        </div>

        <div class="col-md-4 text-center" v-if="cierreAmbiental.firma_recibe">
            <p class="fw-bold">Firma Recibe</p>
            <img
            :src="cierreAmbiental.firma_recibe"
            class="img-fluid border rounded"
            style="max-height: 150px"
            />
        </div>

        <div class="col-md-4 text-center" v-if="cierreAmbiental.firma_testigo">
            <p class="fw-bold">Firma Testigo</p>
            <img
            :src="cierreAmbiental.firma_testigo"
            class="img-fluid border rounded"
            style="max-height: 150px"
            />
        </div>
        </div>

        <!-- IMÁGENES DE EVIDENCIA -->
        <div v-if="cierreAmbiental.imagenes?.length" class="mt-4">
        <h6 class="fw-bold mb-2">📸 Evidencias Fotográficas</h6>

        <div class="row">
            <div
            v-for="(img, index) in cierreAmbiental.imagenes"
            :key="index"
            class="col-md-3 col-6 mb-3"
            >
            <img
                :src="img"
                class="img-fluid rounded shadow-sm"
                style="cursor: pointer"
            />
            </div>
        </div>
        </div>


      </div>
        <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
      <button class="btn btn-primary" @click="generarPDFAmbiental">
        🖨️ Generar PDF
      </button>
     
      <button 
      class="btn btn-success" 
      @click="sincronizarTodo" 
      :disabled="!isOnline"
      :title="isOnline ? 'Sincronizar datos con el servidor' : 'No hay conexión a internet para sincronizar'"
    >
    
      <span v-if="isOnline">🔄 Sincronizar Datos</span>
      <span v-else>🔴 Sin Conexión</span>
    </button>
    <button type="button" class="btn btn-info" @click="irAInicioLaravel">
        🏠 Ir al Inicio
      </button>

    <!-- Puedes agregar un indicador visual de estado de conexión -->
    <p class="mt-2 text-sm" :class="isOnline ? 'text-green-600' : 'text-red-600'">
      Estado: {{ isOnline ? 'Online' : 'Offline' }}
    </p>[]
    </div>
    </div>
    <!-- Modal -->
  <div v-if="mostrar" class="modal-overlay">
    <div class="modal-contenido">
      <h3 v-if="!completo">⏳ Sincronizando...</h3>
      <h3 v-else>✅ ¡Sincronización Completa!</h3>

      <p v-if="!completo">Por favor espera mientras se sincronizan los datos.</p>
      <p v-else>Los datos se sincronizaron correctamente.</p>

      <button v-if="completo" @click="mostrar = false" class="btn btn-success">
        Cerrar
      </button>
    </div>
  </div>
  </div>
</template>
<script>
import { generarResumenAmbientalPDF } from '../utils/pdfAmbientalGenerator'
import { getFormDataByVisita, getAllDataFromStore } from '../store/indexeddb'
import { sincronizadorAmbiental } from '../utils/sincronizadorAmbiental'



export default {
  data() {
    return {
      visitaId: null,
      aguaCaptacion: null,
      aguaUso: null,
      suelo: null,
      energia: null,
      gobernanza: null,
      emisiones: null,
      residuos: null,
      sustancias: null,
      vertimientos: null,
      hmp: null,
      avc: null,
      ecosistema : null,
      noDeforestacion: null,
      isOnline: navigator.onLine,
      cierreAmbiental: null
    };
  },

  methods: {
    siNo(valor) {
      if (valor === 1 || valor === '1' || valor === true) return 'Sí';
      if (valor === 0 || valor === '0' || valor === false) return 'No';
      return 'N/A';
    },

    async loadAllDataAmbiental() {
        try {
            const visitaId = Number(this.visitaId) || this.visitaId
            console.log('🔍 Buscando información de visita ambiental ID:', visitaId)

            const lsKey = 'visita_offline_' + visitaId
            const lsData = localStorage.getItem(lsKey)

            // 1️⃣ Intentar desde localStorage
            if (lsData) {
            this.visitaInfo = JSON.parse(lsData)
            console.log('✅ Visita ambiental desde localStorage:', this.visitaInfo)
            } else {
            // 2️⃣ Buscar en IndexedDB (IGUAL que agronómico)
            const allVisitas = await getAllDataFromStore('visita') || []
            console.log('📦 Total visitas en IndexedDB:', allVisitas.length)

            let visitaEncontrada = null

            for (const v of allVisitas) {
                const formData = v.formData || v

                const posiblesIds = [
                formData.id,
                formData.visita_id,
                formData.local_id,
                v.id,
                v.visita_id,
                v.local_id
                ]

                const coincide = posiblesIds.some(id => {
                if (!id) return false
                return Number(id) == Number(visitaId)
                })

                if (coincide) {
                visitaEncontrada = formData
                console.log('🎯 Visita ambiental encontrada:', formData)
                break
                }
            }

            this.visitaInfo = visitaEncontrada

            // 3️⃣ Fallback (SOLO si no existe nada)
            if (!this.visitaInfo) {
                this.visitaInfo = {
                fecha_visita:
                    localStorage.getItem('fecha_visita') ||
                    new Date().toISOString(),

                proveedor_nombre:
                    localStorage.getItem('proveedor_nombre') ||
                    'No especificado',

                nombre: // nombre de plantación
                    localStorage.getItem('nombre') ||
                    localStorage.getItem('plantacion_nombre') ||
                    'No especificado'
                }
            }

            localStorage.setItem(lsKey, JSON.stringify(this.visitaInfo))
            }

            console.log('🧩 RAW VISITA AMBIENTAL:', this.visitaInfo)

            // 4️⃣ NORMALIZACIÓN FINAL (MISMA IDEA QUE AGRONÓMICO)
            const raw = this.visitaInfo || {}

            this.visitaInfo = {
            proveedor: {
              nombre:
                raw.proveedor?.proveedor_nombre ||
                raw.proveedor_nombre ||
                'No especificado'
            },

            plantacion: {
                nombre:
                raw.nombre ||                 // 👈 CLAVE
                raw.plantacion_nombre ||
                raw.plantacion?.nombre ||
                'No especificado'
            },

            fecha:
                raw.fecha_visita ||             // 👈 FECHA REAL
                raw.fecha ||
                new Date().toISOString(),

            proveedor_id: raw.proveedor_id ?? null,
            plantacion_id: raw.plantacion_id ?? null,
            tecnico_id: raw.tecnico_id ?? null
            }

            console.log('📋 VISITA AMBIENTAL NORMALIZADA:', this.visitaInfo)

        } catch (error) {
            console.error('❌ Error cargando visita ambiental:', error)
            alert('Error cargando información de la visita ambiental')
        }
        },


    async cargarDatos() {

      this.aguaCaptacion = await getFormDataByVisita('agua_captacion_legal', this.visitaId);
      this.aguaUso = await getFormDataByVisita('agua_uso_eficiente', this.visitaId);
      this.suelo = await getFormDataByVisita('suelo_conservacion', this.visitaId);
      this.energia = await getFormDataByVisita('energia_uso_eficiente', this.visitaId);
      this.gobernanza = await getFormDataByVisita('gobernanza_hidrica', this.visitaId)
      this.emisiones = await getFormDataByVisita('emisiones_gei', this.visitaId);
      this.residuos = await getFormDataByVisita('residuos_manejo', this.visitaId);
      this.sustancias = await getFormDataByVisita('sustancias_manejo', this.visitaId);
      this.vertimientos = await getFormDataByVisita('vertimientos_manejo', this.visitaId);
      this.hmp = await getFormDataByVisita('hmp_manejo', this.visitaId);
      this.avc = await getFormDataByVisita('avc_control', this.visitaId);
      this.ecosistema = await getFormDataByVisita('ecosistema_proteccion', this.visitaId);
      this.noDeforestacion = await getFormDataByVisita('no_deforestacion_ambiental',this.visitaId);
      this.cierreAmbiental = await getFormDataByVisita('cierre_visita_ambiental', this.visitaId);
    },

   async generarPDFAmbiental() {
        if (!this.visitaInfo) {
            await this.loadAllDataAmbiental()
        }

        console.log('📤 Enviando al PDF ambiental:', this.visitaInfo)

        await generarResumenAmbientalPDF({
            visitaInfo: this.visitaInfo,

            aguaCaptacion: this.aguaCaptacion,
            aguaUso: this.aguaUso,
            suelo: this.suelo,
            energia: this.energia,
            gobernanza: this.gobernanza,
            emisiones: this.emisiones,
            residuos: this.residuos,
            sustancias: this.sustancias,
            vertimientos: this.vertimientos,
            hmp: this.hmp,
            avc: this.avc,
            ecosistema: this.ecosistema,
            noDeforestacion: this.noDeforestacion,
            cierreAmbiental: this.cierreAmbiental,

            headerImagePath: '/images/header.png',
            footerImagePath: '/images/footer.png'
        })
        },



        async sincronizarTodo() {
          if (!this.isOnline) {
            alert('No hay conexión a internet. Por favor, conéctate para sincronizar los datos.');
            return;
          }

          this.mostrar = true;
          this.completo = false;


          try {
            // 🔹 Primero sincronizas todo
            await sincronizadorAmbiental();

            // 🔹 Luego actualizas el estado de la visita a "finalizado"
            const visitaId = this.visita_id; // <-- asegúrate de tener este valor en tu componente
            await fetch(`/visitas/${visitaId}/update-status`, {
              method: 'PUT',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              }
            });
            this.completo = true;


            alert("✅ Sincronización completa y estado de la visita actualizado.");
          } catch (error) {
            console.error('Error general al iniciar la sincronización:', error);
            this.mostrar = false;
          } finally {
            if (modalInstance) modalInstance.close();

          }
        },

    
    /**
     * Actualiza el estado 'isOnline' cuando el navegador se conecta.
     */
    handleOnline() {
      this.isOnline = true;
      console.log('Conexión reestablecida.');
    },

  },

  async mounted() {
    this.visitaId =
      new URLSearchParams(window.location.search).get('visita_id') ||
      localStorage.getItem('visita_id');

    localStorage.setItem('visita_id', this.visitaId);
    await this.loadAllDataAmbiental();
    await this.cargarDatos();
  }
};
</script>

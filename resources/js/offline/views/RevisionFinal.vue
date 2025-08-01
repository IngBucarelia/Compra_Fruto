<template>
  <div class="offline-container offline-form-container">
    <h2 class="offline-title">📋 Revisión Final de Formulario (Modo Offline)</h2>

    <!-- Contenedor principal para las secciones, para que se apilen correctamente -->
    <div class="all-sections-container">
      <!-- Áreas -->
      <div class="card border-success mb-4">
        <div class="card-header bg-success text-white">
          📍 Áreas Registradas
        </div>
        <div class="card-body">
          <div v-if="areas && areas.length > 0">
            <div v-for="(area, index) in areas" :key="area.local_id || index" class="mb-3 area-card">
              <h5>Área #{{ index + 1 }}</h5>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Material:</strong> {{ area.material || 'N/A' }}</li>
                <li class="list-group-item"><strong>Estado:</strong> {{ area.estado || 'N/A' }}</li>
                <li class="list-group-item"><strong>Año siembra:</strong> {{ formatDate(area.anio_siembra) }}</li>
                <li class="list-group-item"><strong>Área (m²):</strong> {{ area.area || 'N/A' }}</li>
                
                <li class="list-group-item"><strong>Área Total Finca (Ha):</strong> {{ area.area_total_finca_hectareas || 'N/A' }}</li>
                <li class="list-group-item"><strong>N° Palmas Total Finca:</strong> {{ area.numero_palmas_total_finca || 'N/A' }}</li>
                
                <li class="list-group-item"><strong>Área Palmas Desarrollo (Ha):</strong> {{ area.area_palmas_desarrollo_hectareas || 'N/A' }}</li>
                <li class="list-group-item"><strong>N° Palmas Desarrollo:</strong> {{ area.numero_palmas_desarrollo || 'N/A' }}</li>
                
                <li class="list-group-item"><strong>Área Palmas Producción (Ha):</strong> {{ area.area_palmas_produccion_hectareas || 'N/A' }}</li>
                <li class="list-group-item"><strong>N° Palmas Producción:</strong> {{ area.numero_palmas_produccion || 'N/A' }}</li>
                
                <li class="list-group-item"><strong>Ciclos de Cosecha:</strong> {{ area.ciclos_cosecha || 'N/A' }}</li>
                <li class="list-group-item"><strong>Producción (Toneladas/Mes):</strong> {{ area.produccion_toneladas_por_mes || 'N/A' }}</li>
                
                <li class="list-group-item"><strong>Aplica Orden Plantis:</strong> {{ area.aplica_orden_plantis ? 'Sí' : 'No' }}</li>
                
                <template v-if="area.aplica_orden_plantis">
                  <li class="list-group-item"><strong>Orden Plantis N°:</strong> {{ area.orden_plantis_numero || 'N/A' }}</li>
                  <li class="list-group-item"><strong>Estado Orden Plantis:</strong> {{ area.estado_oren_plantis || 'N/A' }}</li>
                  <li class="list-group-item"><strong>N° Plantas Orden Plantis:</strong> {{ area.numero_plantas_orden_plantis || 'N/A' }}</li>
                </template>
              </ul>
            </div>
          </div>
          <p v-else class="text-muted">No hay áreas registradas</p>
        </div>
      </div>

      <!-- Fertilizaciones -->
      <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white">
          💧 Fertilizaciones Registradas
        </div>
        <div class="card-body">
          <div v-if="fertilizaciones && fertilizaciones.length > 0">
            <div v-for="(fert, index) in fertilizaciones" :key="fert.local_id || index" class="mb-3">
              <h5>📅 {{ formatDate(fert.fecha_fertilizacion) }}</h5>
              <ul class="list-group list-group-flush">
                <li v-for="(item, i) in fert.fertilizantes" :key="item.local_id || i" class="list-group-item">
                  <strong>{{ item.nombre || 'N/A' }}</strong> - 
                  {{ item.cantidad || 'N/A' }} {{ item.unidad_medida || 'N/A' }} 
                  <span v-if="item.fecha_aplicacion">(Aplicado: {{ formatDate(item.fecha_aplicacion) }})</span>
                </li>
              </ul>
            </div>
          </div>
          <p v-else class="text-muted">No hay fertilizaciones registradas</p>
        </div>
      </div>

      <!-- Polinizaciones -->
      <div class="card border-info mb-4">
        <div class="card-header bg-info text-white">
          🌸 Polinizaciones Registradas
        </div>
        <div class="card-body">
          <div v-if="polinizaciones && polinizaciones.length > 0">
            <div v-for="(poli, index) in polinizaciones" :key="poli.local_id || index" class="mb-3">
              <h5>Polinización #{{ index + 1 }}</h5>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Fecha:</strong> {{ formatDate(poli.fecha) }}</li>
                <li class="list-group-item"><strong>N° Pases:</strong> {{ poli.n_pases || 'N/A' }}</li>
                <li class="list-group-item"><strong>Ciclos:</strong> {{ poli.ciclos_ronda || 'N/A' }}</li>
                <li class="list-group-item"><strong>ANA:</strong> {{ poli.ana || 'N/A' }} ({{ poli.tipo_ana || 'N/A' }})</li>
                <li class="list-group-item"><strong>Talco:</strong> {{ poli.talco || 'N/A' }} kg</li>
              </ul>
            </div>
          </div>
          <p v-else class="text-muted">No hay polinizaciones registradas.</p>
        </div>
      </div>

      <!-- Sanidad -->
      <div class="card border-danger mb-4" v-if="sanidad">
        <div class="card-header bg-danger text-white">🦠 Sanidad</div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
              <li class="list-group-item">Opsophanes: {{ formatPercentage(sanidad.opsophanes) }}</li>
              <li class="list-group-item">Pudrición Cogollo: {{ formatPercentage(sanidad.pudricion_cogollo) }}</li>
              <li class="list-group-item">Raspador: {{ formatPercentage(sanidad.raspador) }}</li>
              <li class="list-group-item">Palmarum: {{ formatPercentage(sanidad.palmarum) }}</li>
              <li class="list-group-item">Strategus: {{ formatPercentage(sanidad.strategus) }}</li>
              <li class="list-group-item">Leptopharsa: {{ formatPercentage(sanidad.leptopharsa) }}</li>
              <li class="list-group-item">Pestalotiopsis: {{ formatPercentage(sanidad.pestalotiopsis) }}</li>
              <li class="list-group-item">Pudrición Basal: {{ formatPercentage(sanidad.pudricion_basal) }}</li>
              <li class="list-group-item">Pudrición Estípite: {{ formatPercentage(sanidad.pudricion_estipe) }}</li>
              <li class="list-group-item">Otros: {{ sanidad.otros || 'N/A' }}</li>
              <li class="list-group-item">Observaciones: {{ sanidad.observaciones || 'N/A' }}</li>
            </ul>
        </div>
      </div>
      <p v-else class="text-muted text-center mb-4">No hay datos de sanidad registrados.</p>

      <!-- Suelo -->
      <div class="card border-warning mb-4" v-if="suelo">
        <div class="card-header bg-warning text-white">
          🧪 Suelo
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Análisis Foliar:</strong> {{ formatSiNo(suelo.analisis_foliar) }}</li>
            <li class="list-group-item"><strong>Análisis Suelo:</strong> {{ formatSiNo(suelo.alanalisis_suelo) }}</li>
            <li class="list-group-item"><strong>Tipo Suelo:</strong> {{ suelo.tipo_suelo || 'N/A' }}</li>
          </ul>
        </div>
      </div>
      <p v-else class="text-muted text-center mb-4">No hay datos de suelo registrados.</p>

      <!-- Labores de Cultivo -->
      <div class="card border-warning mb-4">
        <div class="card-header bg-warning text-white">
          🚜 Labores de Cultivo
        </div>
        <div class="card-body p-2">
          <div v-if="laboresCultivo && laboresCultivo.length > 0">
            <div v-for="(labor, index) in laboresCultivo" :key="labor.local_id || index" class="mb-3 labor-card">
              <div class="d-flex justify-content-between align-items-center mb-2 bg-light p-2 rounded">
                <h6 class="mb-0">Labor #{{ index + 1 }}</h6>
                <small class="text-muted" v-if="labor.created_at">{{ formatDate(labor.created_at) }}</small>
              </div>
              
              <div class="mb-2" v-if="labor.tipo_planta">
                <span class="badge bg-info text-dark w-100">
                  <strong>Tipo Planta:</strong> {{ ucfirst(labor.tipo_planta) || 'N/A' }}
                </span>
              </div>
              
              <div class="list-group list-group-flush">
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Polinización:</span>
                  <strong>{{ formatPercentage(labor.polinizacion) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Limpieza Calle:</span>
                  <strong>{{ formatPercentage(labor.limpieza_calle) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Limpieza Plato:</span>
                  <strong>{{ formatPercentage(labor.limpieza_plato) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Poda:</span>
                  <strong>{{ formatPercentage(labor.poda) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Fertilización:</span>
                  <strong>{{ formatPercentage(labor.fertilizacion) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Enmiendas:</span>
                  <strong>{{ formatPercentage(labor.enmiendas) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Ubicación Tusa/Fibra:</span>
                  <strong>{{ formatPercentage(labor.ubicacion_tusa_fibra) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Hoja en Barrera:</span>
                  <strong>{{ formatPercentage(labor.ubicacion_hoja) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Hoja en Plato:</span>
                  <strong>{{ formatPercentage(labor.lugar_ubicacion_hoja) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Plantas Nectaríferas:</span>
                  <strong>{{ formatPercentage(labor.plantas_nectariferas) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Cobertura:</span>
                  <strong>{{ formatPercentage(labor.cobertura) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Labor Cosecha:</span>
                  <strong>{{ formatPercentage(labor.labor_cosecha) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Calidad Fruta:</span>
                  <strong>{{ formatPercentage(labor.calidad_fruta) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Recolección Fruta:</span>
                  <strong>{{ formatPercentage(labor.recoleccion_fruta) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Drenajes:</span>
                  <strong>{{ formatPercentage(labor.drenajes) }}</strong>
                </div>
              </div>
              
              <div class="mt-2" v-if="labor.observaciones">
                <div class="alert alert-info p-2 mb-0">
                  <strong>Observaciones:</strong> {{ labor.observaciones }}
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-3">
            <p class="text-muted">No hay labores de cultivo registradas</p>
          </div>
        </div>
      </div>

      <!-- Evaluación de Cosecha -->
      <div class="card border-info mb-4">
        <div class="card-header bg-info text-white">
          🌴 Evaluación de Cosecha
        </div>
        <div class="card-body">
          <div v-if="evaluacionesCosecha && evaluacionesCosecha.length > 0">
            <div v-for="(evaluacion, index) in evaluacionesCosecha" :key="evaluacion.local_id || index" class="mb-3">
              <h5>Evaluación #{{ index + 1 }}</h5>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Variedad:</strong> {{ ucfirst(evaluacion.variedad_fruto) || 'N/A' }}</li>
                <li class="list-group-item"><strong>Racimos:</strong> {{ evaluacion.cantidad_racimos || 'N/A' }}</li>
                <li class="list-group-item"><strong>Verde:</strong> {{ formatPercentage(evaluacion.verde) }}</li>
                <li class="list-group-item"><strong>Maduro:</strong> {{ formatPercentage(evaluacion.maduro) }}</li>
                <li class="list-group-item"><strong>Sobremaduro:</strong> {{ formatPercentage(evaluacion.sobremaduro) }}</li>
                <li class="list-group-item"><strong>Pedúnculo:</strong> {{ formatPercentage(evaluacion.pedunculo) }}</li>
                <li v-if="evaluacion.conformacion" class="list-group-item">
                  <strong>Conformación:</strong> {{ evaluacion.conformacion }}
                </li>
                <li class="list-group-item"><strong>Observaciones:</strong> {{ evaluacion.observaciones || 'N/A' }}</li>
              </ul>
            </div>
          </div>
          <p v-else class="text-muted">No hay evaluaciones de cosecha</p>
        </div>
      </div>
    </div> <!-- Fin all-sections-container -->

    <!-- Cierre de Visita -->
    <div class="card border-secondary mb-4" v-if="cierreVisita && cierreVisita.fecha_cierre">
      <div class="card-header bg-secondary text-white">
        ✅ Cierre de Visita
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <strong>Fecha de Cierre:</strong> {{ formatDate(cierreVisita.fecha_cierre) }}
            </div>
            <div class="mb-3">
              <strong>Estado:</strong> {{ cierreVisita.estado_visita || 'N/A' }}
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <strong>Observaciones:</strong> {{ cierreVisita.observaciones || 'Ninguna' }}
            </div>
            <div class="mb-3">
              <strong>Recomendaciones:</strong> {{ cierreVisita.recomendaciones || 'Ninguna' }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <p v-else class="text-muted text-center mb-4">No hay datos de cierre de visita registrados.</p>

    <!-- Firmas -->
    <div class="card border-dark mb-4" v-if="cierreVisita && (cierreVisita.firma_responsable || cierreVisita.firma_recibe || cierreVisita.firma_testigo)">
      <div class="card-header bg-dark text-white">
        ✍️ Firmas
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Firma Responsable -->
          <div class="col-md-4 mb-3" v-if="cierreVisita.firma_responsable">
            <h5>Realizó la visita</h5>
            <img :src="cierreVisita.firma_responsable" class="img-fluid border" style="max-height: 150px;" alt="Firma Responsable" />
          </div>
          
          <!-- Firma Recibe -->
          <div class="col-md-4 mb-3" v-if="cierreVisita.firma_recibe">
            <h5>Recibió la visita</h5>
            <img :src="cierreVisita.firma_recibe" class="img-fluid border" style="max-height: 150px;" alt="Firma Recibe" />
          </div>
          
          <!-- Firma Testigo -->
          <div class="col-md-4 mb-3" v-if="cierreVisita.firma_testigo">
            <h5>Testigo</h5>
            <img :src="cierreVisita.firma_testigo" class="img-fluid border" style="max-height: 150px;" alt="Firma Testigo" />
          </div>
        </div>
      </div>
    </div>

    <!-- Imágenes -->
    <div class="card border-info mb-4" v-if="cierreVisita && cierreVisita.imagenes && cierreVisita.imagenes.length > 0">
      <div class="card-header bg-info text-white">
        📸 Fotos de la Visita
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6 col-md-3 mb-3" v-for="(img, idx) in cierreVisita.imagenes" :key="idx">
            <img :src="img" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;" alt="Imagen de la visita" />
          </div>
        </div>
      </div>
    </div>

    <!-- Acciones -->
    <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
      <button class="btn btn-primary" @click="generarPDF">
        🖨️ Generar PDF
      </button>
      <button class="btn btn-outline-success" @click="descargarExcel">
        📥 Exportar a Excel
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
    </p>
    </div>
  </div>
</template>

<script>
import { getFormDataByVisita, getAllDataFromStore } from '../store/indexeddb'
import { generarResumenPDF } from '../utils/pdfGenerator'
import { exportarResumenExcel } from '../utils/excelExporter'
import { sincronizarDatosOffline } from '../utils/sincronizador'

export default {
  data() {
    return {
      visitaId: null,
      areas: [],
      fertilizaciones: [],
      polinizaciones: [],
      sanidad: null,
      suelo: null,
      laboresCultivo: [],
      evaluacionesCosecha: [],
      isOnline: navigator.onLine,
      cierreVisita: {
        fecha_cierre: '',
        estado_visita: '',
        observaciones: '',
        recomendaciones: '',
        firma_responsable: null,
        firma_recibe: null,
        firma_testigo: null,
        imagenes: []
      }
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id')
    localStorage.setItem('visita_id', this.visitaId)

    await this.loadAllData()
    },
    methods: {
      // Método para capitalizar la primera letra de una cadena
      ucfirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
      },
      // Método para formatear fechas
      formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
          const date = new Date(dateString);
          // Opciones de formato, puedes ajustarlas según tu preferencia
          const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
          return date.toLocaleDateString('es-ES', options);
        } catch (e) {
          console.error("Error al formatear fecha:", dateString, e);
          return dateString; // Devuelve la cadena original si hay error
        }
      },
      // Método para formatear porcentajes
      formatPercentage(value) {
        return (value !== null && value !== undefined) ? `${value}%` : 'N/A';
      },
      // Método para formatear "si"/"no" a "Sí"/"No"
      formatSiNo(value) {
        if (value === 'si') return 'Sí';
        if (value === 'no') return 'No';
        return 'N/A';
      },
      irAInicioLaravel() {
          if (!confirm('¿Salir y borrar todos los datos?')) return;
          
          // Limpieza instantánea
          this.cierreVisita = { fecha_cierre: '', estado_visita: '', observaciones: '', recomendaciones: '' };
          this.imagenes = [];
          localStorage.clear();
          
          // Limpieza en segundo plano (no bloqueante)
          try {
            indexedDB.deleteDatabase('NombreDeTuBaseDeDatos');
          } catch (e) {
            console.warn('Error borrando DB:', e);
          }
          
          // Redirección inmediata
          window.location.href = '/dashboard';
        },

    async loadAllData() {
      try {
        const visitaId = Number(this.visitaId) || this.visitaId
        
        // Cargar todas las áreas
        const allAreas = await getAllDataFromStore('area') || []
        this.areas = allAreas.filter(a => {
          const areaVisitaId = Number(a.visita_id) || a.visita_id
          return areaVisitaId == visitaId
        })
        
        // Cargar evaluaciones de cosecha
        const allEvaluaciones = await getAllDataFromStore('evaluacion_cosecha') || []
        this.evaluacionesCosecha = allEvaluaciones.filter(e => {
          const evalVisitaId = Number(e.visita_id) || e.visita_id
          return evalVisitaId == visitaId
        })
        
        // Cargar fertilizaciones
        const allFertilizaciones = await getAllDataFromStore('fertilizacion') || []
        this.fertilizaciones = allFertilizaciones.filter(f => {
          const fertVisitaId = Number(f.visita_id) || f.visita_id
          return fertVisitaId == visitaId
        })
        
        // Cargar polinizaciones
        const allPolinizaciones = await getAllDataFromStore('polinizacion') || []
        this.polinizaciones = allPolinizaciones.filter(p => {
          const poliVisitaId = Number(p.visita_id) || p.visita_id
          return poliVisitaId == visitaId
        })
        
        // Cargar sanidad
        const allSanidad = await getAllDataFromStore('sanidad') || []
        this.sanidad = allSanidad.find(s => {
          const sanidadVisitaId = Number(s.visita_id) || s.visita_id
          return sanidadVisitaId == visitaId
        }) || null
        
        // Cargar suelo
        const allSuelos = await getAllDataFromStore('suelo') || []
        this.suelo = allSuelos.find(s => {
          const sueloVisitaId = Number(s.visita_id) || s.visita_id
          return sueloVisitaId == visitaId
        }) || null
        
        // Cargar labores de cultivo
        const allLabores = await getAllDataFromStore('labores_cultivo') || []
        this.laboresCultivo = allLabores.filter(l => {
          const laborVisitaId = Number(l.visita_id) || l.visita_id
          return laborVisitaId == visitaId
        })
        
        // Cargar cierre de visita
        const allCierres = await getAllDataFromStore('cierre_visitas') || []
        const cierre = allCierres.find(c => {
          const cierreVisitaId = Number(c.visita_id) || c.visita_id
          return cierreVisitaId == visitaId
        })
        
        if (cierre) {
          this.cierreVisita = {
            ...this.cierreVisita,
            ...cierre
          }
        }
        
      } catch (error) {
        console.error('Error cargando datos:', error)
        alert('Error al cargar los datos: ' + error.message)
      }
    },
    async generarPDF() {
      await generarResumenPDF({
        areas: this.areas,
        fertilizaciones: this.fertilizaciones,
        polinizaciones: this.polinizaciones,
        sanidad: this.sanidad,
        suelo: this.suelo,
        laboresCultivo: this.laboresCultivo,
        evaluacionesCosecha: this.evaluacionesCosecha,
        cierreVisita: this.cierreVisita,
      });
    },
    descargarExcel() {
      exportarResumenExcel({
        areas: this.areas,
        fertilizaciones: this.fertilizaciones,
        polinizaciones: this.polinizaciones,
        sanidad: this.sanidad,
        suelo: this.suelo,
        laboresCultivo: this.laboresCultivo,
        evaluacionesCosecha: this.evaluacionesCosecha,
        cierreVisita: this.cierreVisita,
      })
    },
    async sincronizarTodo() {
      if (!this.isOnline) {
        alert('No hay conexión a internet. Por favor, conéctate para sincronizar los datos.');
        return;
      }
      // Muestra un mensaje de carga o spinner si lo deseas
      // this.isLoading = true; 
      try {
        await sincronizarDatosOffline();
      } catch (error) {
        console.error('Error general al iniciar la sincronización:', error);
        // Manejar el error a nivel de UI si es necesario
      } finally {
        // this.isLoading = false;
      }
    },
    
    /**
     * Actualiza el estado 'isOnline' cuando el navegador se conecta.
     */
    handleOnline() {
      this.isOnline = true;
      console.log('Conexión reestablecida.');
    },

    /**
     * Actualiza el estado 'isOnline' cuando el navegador se desconecta.
     */
    handleOffline() {
      this.isOnline = false;
      console.warn('Conexión perdida. Operando en modo offline.');
    }
  }
}
</script>

<style scoped>
/* Importa tus estilos base de offline.css */
@import '../styles/offline.css';

/* Estilos Base para Contenedores de Formularios */
.offline-form-container {
    background-color: rgba(90, 104, 84, 0.968);
    padding: 20px;
    margin-top: 25px; /* Margen superior para desktop */
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-width: 900px; /* Ancho máximo para pantallas grandes */
   
    box-sizing: border-box; /* Asegura que padding y border se incluyan en el ancho total */
}

/* Contenedor para todas las secciones de datos previos */
.all-sections-container {
  display: flex;
  flex-direction: column; /* Apila las secciones por defecto (móvil) */
  gap: 20px; /* Espacio entre las tarjetas de sección */
}

/* Estilos para Títulos de Formularios */
.offline-title {
    text-align: center;
    color: #2F4F4F; /* Un color oscuro para el título */
    margin-bottom: 25px;
    font-size: 1.8em; /* Tamaño de fuente para el título */
}

/* Estilos para las tarjetas de información */
.card {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.card-header {
    font-weight: bold;
    padding: 10px 15px;
    border-bottom: 1px solid rgba(0,0,0,.125);
    border-top-left-radius: calc(0.25rem - 1px);
    border-top-right-radius: calc(0.25rem - 1px);
}

.card-body {
    padding: 15px;
}

.list-group-item {
    padding: 8px 15px;
    border-color: #f0f0f0;
}

.list-group-item:last-child {
    border-bottom: none;
}

/* Colores específicos para las tarjetas */
.card.border-success { border-color: #28a745 !important; }
.card-header.bg-success { background-color: #28a745 !important; }

.card.border-primary { border-color: #007bff !important; }
.card-header.bg-primary { background-color: #007bff !important; }

.card.border-info { border-color: #17a2b8 !important; }
.card-header.bg-info { background-color: #17a2b8 !important; }

.card.border-danger { border-color: #dc3545 !important; }
.card-header.bg-danger { background-color: #dc3545 !important; }

.card.border-warning { border-color: #ffc107 !important; }
.card-header.bg-warning { background-color: #ffc107 !important; }

.card.border-secondary { border-color: #6c757d !important; }
.card-header.bg-secondary { background-color: #6c757d !important; }

.card.border-dark { border-color: #343a40 !important; }
.card-header.bg-dark { background-color: #343a40 !important; }


/* Estilos para los botones de acción */
.btn {
  padding: 10px 20px;
  border-radius: 0.25rem;
  font-size: 1em;
  cursor: pointer;
  transition: background-color 0.2s ease, transform 0.2s ease;
  border: none;
}

.btn-primary { background-color: #007bff; color: white; }
.btn-primary:hover { background-color: #0056b3; transform: translateY(-1px); }

.btn-success { background-color: #28a745; color: white; }
.btn-success:hover { background-color: #1e7e34; transform: translateY(-1px); }

.btn-outline-success { border-color: #28a745; color: #28a745; background-color: transparent;}
.btn-outline-success:hover { background-color: #28a745; color: white; }


/* Estilos para imágenes */
.img-thumbnail {
  max-height: 150px;
  object-fit: contain;
}

/* Responsividad */
@media (max-width: 967.98px) { /* Para pantallas más pequeñas que md (768px) */
 
    .offline-form-container {
        padding: 15px;
        border-radius: 0;
        box-shadow: none;
        width: 300px !important; 
        margin-left: -210px !important;
        padding-top: 90px !important; 
        box-sizing: border-box; 
    }

    .offline-title {
        font-size: 1.5em;
        margin-bottom: 20px;
    }

    .card {
      margin-bottom: 15px; /* Menos espacio entre tarjetas en móvil */
    }

    .d-flex.flex-wrap.gap-2 {
      flex-direction: column; /* Apila los botones de acción en móvil */
      gap: 10px;
    }
    .d-flex.flex-wrap.gap-2 .btn {
      width: 100%; /* Botones de acción ocupan todo el ancho */
    }

    /* Ajustes para las imágenes de firmas */
    .card-body .row .col-md-4,
    .card-body .row .col-6 {
        flex: 0 0 100%; /* Cada imagen ocupa su propia fila en móvil */
        max-width: 100%;
    }
    .card-body img {
        max-height: 120px !important; /* Ajusta la altura máxima de las imágenes en móvil */
        object-fit: contain; /* Asegura que la imagen completa sea visible */
    }
}


</style>

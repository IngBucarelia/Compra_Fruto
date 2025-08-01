<template>
  <div class="offline-container" >
    <h2 class="offline-title">📋 Resumen de Visita - Firmas</h2>
    
    <!-- Sección de Datos Previos -->
    <div class="row mb-4">
      <!-- Áreas -->
      <div class="col-md-6">
        <div class="card border-success">
          <div class="card-header bg-success text-white">
            📍 Áreas Registradas
          </div>
          <div class="card-body">
            <div v-if="areas && areas.length > 0">
              <div v-for="(area, index) in areas" :key="area.id || index" class="mb-3 area-card">
                <h5>Área #{{ index + 1 }}</h5>
                <ul class="list-group">
                  <li class="list-group-item"><strong>Material:</strong> {{ area.material }}</li>
                  <li class="list-group-item"><strong>Estado:</strong> {{ area.estado }}</li>
                  <li class="list-group-item"><strong>Año siembra:</strong> {{ formatDate(area.anio_siembra) }}</li>
                  <li class="list-group-item"><strong>Área (m²):</strong> {{ area.area }}</li>
                  
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
      </div>

      <!-- Tarjeta: Fertilizaciones -->
      <div class="col-md-6">
        <div class="card border-primary">
          <div class="card-header bg-primary text-white">
            💧 Fertilizaciones Registradas
          </div>
          <div class="card-body">
            <div v-if="fertilizaciones && fertilizaciones.length > 0">
              <div v-for="(fert, index) in fertilizaciones" :key="index" class="mb-3">
                <h5>📅 {{ formatDate(fert.fecha_fertilizacion) }}</h5>
                <ul class="list-group">
                  <li v-for="(item, i) in fert.fertilizantes" :key="i" class="list-group-item">
                    <strong>{{ item.nombre }}</strong> - 
                    {{ item.cantidad }} {{ item.unidad_medida }} 
                    <span v-if="item.fecha_aplicacion">(Aplicado: {{ formatDate(item.fecha_aplicacion) }})</span>
                  </li>
                </ul>
              </div>
            </div>
            <p v-else class="text-muted">No hay fertilizaciones registradas</p>
          </div>
        </div>
      </div>

      <div v-if="polinizaciones && polinizaciones.length > 0">
        <div class="card-header bg-success text-white">
            🌸 Polinizaciones Registradas
          </div>
        
        <div v-for="(poli, index) in polinizaciones" :key="poli.local_id" class="card border-info form-group mb-3">
          <div class="card-header bg-info text-white">
            🌸 Polinización #{{ index + 1 }}
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>Fecha:</strong> {{ poli.fecha }}</li>
              <li class="list-group-item"><strong>N° Pases:</strong> {{ poli.n_pases }}</li>
              <li class="list-group-item"><strong>Ciclos:</strong> {{ poli.ciclos_ronda }}</li>
              <li class="list-group-item"><strong>ANA:</strong> {{ poli.ana }} ({{ poli.tipo_ana }})</li>
              <li class="list-group-item"><strong>Talco:</strong> {{ poli.talco }} kg</li>
            </ul>
          </div>
        </div>
      </div>
      <p v-else class="text-muted">No hay polinizaciones registradas.</p>   

      <!-- Sanidad -->
      <div class="col-md-6">
        <div class="card border-danger mb-3">
          <div class="card-header bg-danger text-white">🦠 Sanidad</div>
          <div class="card-body" v-if="sanidad">
            <ul class="list-group list-group-flush">
              <li class="list-group-item">Opsophanes: {{ sanidad.opsophanes }}%</li>
              <li class="list-group-item">Pudrición Cogollo: {{ sanidad.pudricion_cogollo }}%</li>
              <li class="list-group-item">Raspador: {{ sanidad.raspador }}%</li>
              <li class="list-group-item">Palmarum: {{ sanidad.palmarum }}%</li>
              <li class="list-group-item">Strategus: {{ sanidad.strategus }}%</li>
              <li class="list-group-item">Leptoparsha: {{ sanidad.leptoparsa }}%</li>
              <li class="list-group-item">Pestalotiopsis: {{ sanidad.pestalotiopsis }}%</li>
              <li class="list-group-item">Pudrición Basal: {{ sanidad.pudricion_basal }}%</li>
              <li class="list-group-item">Pudrición Estípite: {{ sanidad.pudricion_estipe }}%</li>
              <li class="list-group-item">Otros: {{ sanidad.otros }}</li>
              <li class="list-group-item">Observaciones: {{ sanidad.observaciones }}</li>
            </ul>
          </div>
          <p v-else class="text-muted">Sin sanidad registrada.</p>
        </div>
      <!-- Tarjeta: Suelo -->
      <div class="col-md-6" v-if="suelo">
        <div class="card border-warning">
          <div class="card-header bg-warning text-white">
            🧪 Suelo
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>Análisis Foliar:</strong> {{ suelo.analisis_foliar || 'N/A' }}</li>
              <li class="list-group-item"><strong>Análisis Suelo:</strong> {{ suelo.alanalisis_suelo || 'N/A' }}</li>
              <li class="list-group-item"><strong>Tipo Suelo:</strong> {{ suelo.tipo_suelo || 'N/A' }}</li>
              <!-- Agrega más campos de suelo según sea necesario -->
            </ul>
          </div>
        </div>
      </div>
      <!-- Labores de Cultivo -->
      <div class="col-12">
        <div class="card border-warning mb-3">
          <div class="card-header bg-warning text-white">
            🚜 Labores de Cultivo
          </div>
          <div class="card-body p-2">
            <div v-if="laboresCultivo && laboresCultivo.length > 0">
              <div v-for="(labor, index) in laboresCultivo" :key="labor.id || index" class="mb-3 labor-card">
                <!-- Encabezado -->
                <div class="d-flex justify-content-between align-items-center mb-2 bg-light p-2 rounded">
                  <h6 class="mb-0">Labor #{{ index + 1 }}</h6>
                  <small class="text-muted">{{ formatDate(labor.created_at) }}</small>
                </div>
                
                <!-- Tipo de Planta -->
                <div class="mb-2">
                  <span class="badge bg-info text-dark w-100">
                    <strong>Tipo Planta:</strong> {{ ucfirst(labor.tipo_planta) || 'N/A' }}
                  </span>
                </div>
                
                <!-- Lista de labores -->
                <div class="list-group">
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Polinización:</span>
                    <strong>{{ labor.polinizacion || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Limpieza Calle:</span>
                    <strong>{{ labor.limpieza_calle || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Limpieza Plato:</span>
                    <strong>{{ labor.limpieza_plato || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Poda:</span>
                    <strong>{{ labor.poda || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Fertilización:</span>
                    <strong>{{ labor.fertilizacion || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Enmiendas:</span>
                    <strong>{{ labor.enmiendas || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Ubicación Tusa/Fibra:</span>
                    <strong>{{ labor.ubicacion_tusa_fibra || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Hoja en Barrera:</span>
                    <strong>{{ labor.ubicacion_hoja || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Hoja en Plato:</span>
                    <strong>{{ labor.lugar_ubicacion_hoja || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Plantas Nectaríferas:</span>
                    <strong>{{ labor.plantas_nectariferas || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Cobertura:</span>
                    <strong>{{ labor.cobertura || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Labor Cosecha:</span>
                    <strong>{{ labor.labor_cosecha || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Calidad Fruta:</span>
                    <strong>{{ labor.calidad_fruta || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Recolección Fruta:</span>
                    <strong>{{ labor.recoleccion_fruta || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Drenajes:</span>
                    <strong>{{ labor.drenajes || 0 }}%</strong>
                  </div>
                </div>
                
                <!-- Observaciones -->
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
      </div>
      </div>

      <!-- Evaluación de Cosecha -->
      <div class="col-md-6">
        <div class="card border-info">
          <div class="card-header bg-info text-white">
            🌴 Evaluación de Cosecha
          </div>
          <div class="card-body">
            <div v-if="evaluacionesCosecha && evaluacionesCosecha.length > 0">
              <div v-for="(evaluacion, index) in evaluacionesCosecha" :key="index" class="mb-3">
                <h5>Evaluación #{{ index + 1 }}</h5>
                <ul class="list-group">
                  <li class="list-group-item"><strong>Variedad:</strong> {{ ucfirst(evaluacion.variedad_fruto) }}</li>
                  <li class="list-group-item"><strong>Racimos:</strong> {{ evaluacion.cantidad_racimos }}</li>
                  <li class="list-group-item"><strong>Verde:</strong> {{ evaluacion.verde }}%</li>
                  <li class="list-group-item"><strong>Maduro:</strong> {{ evaluacion.maduro }}%</li>
                  <li class="list-group-item"><strong>Sobremaduro:</strong> {{ evaluacion.sobremaduro }}%</li>
                  <li class="list-group-item"><strong>Pedúnculo:</strong> {{ evaluacion.pedunculo }}%</li>
                  <li v-if="evaluacion.conformacion" class="list-group-item">
                    <strong>Conformación:</strong> {{ evaluacion.conformacion }}
                  </li>
                </ul>
              </div>
            </div>
            <p v-else class="text-muted">No hay evaluaciones de cosecha</p>
          </div>
        </div>
      </div>

      <!-- Resto de formularios (fertilización, polinización, etc.) -->
      <!-- ... -->
    </div>

    <h2>🖊️ Registro de Firmas y Cierre de Visita (Modo Offline)</h2>

    <!-- Campos para Cierre de Visita -->
    <div class="form-group">
      <label for="fechaCierre" class="form-label">📅 Fecha de Cierre *</label>
      <input type="date" id="fechaCierre" class="form-control" v-model="cierreVisita.fecha_cierre" required />
    </div>

    <div class="form-group">
      <label for="estadoVisita" class="form-label">📊 Estado de la Visita *</label>
      <select id="estadoVisita" class="form-control" v-model="cierreVisita.estado_visita" required>
        <option value="">Seleccione</option>
        <option value="completado">Completado</option>
        <option value="pendiente">Pendiente</option>
        <option value="cancelado">Cancelado</option>
      </select>
    </div>

    <div class="form-group">
      <label for="observacionesFinales" class="form-label">📝 Observaciones Finales (Opcional)</label>
      <textarea id="observacionesFinales" class="form-control" rows="3" v-model="cierreVisita.observaciones"></textarea>
    </div>

    <!-- ✅ Campo Recomendaciones (NUEVO - si lo tienes en DB y lo quieres enviar) -->
    <div class="form-group">
      <label for="recomendaciones" class="form-label">💡 Recomendaciones (Opcional)</label>
      <textarea id="recomendaciones" class="form-control" rows="3" v-model="cierreVisita.recomendaciones"></textarea>
    </div>

    <!-- Firma: Realiza la visita -->
    <div class="form-group">
      <h5>✍️ Firma de quien realiza la visita *</h5>
      <canvas ref="firmaRealiza" class="firma-canvas border" width="300" height="150"></canvas>
      <div class="mt-2">
        <button class="btn btn-sm btn-secondary" @click="limpiarFirma('realiza')">🧹 Limpiar</button>
      </div>
    </div>

    <!-- Firma: Recibe la visita -->
    <div class="form-group">
      <h5>✍️ Firma de quien recibe la visita *</h5>
      <canvas ref="firmaRecibe" class="firma-canvas border" width="300" height="150"></canvas>
      <div class="mt-2">
        <button class="btn btn-sm btn-secondary" @click="limpiarFirma('recibe')">🧹 Limpiar</button>
      </div>
    </div>

    <!-- Firma: Testigo (opcional) -->
    <div class="form-group">
      <h5>✍️ Firma del testigo (opcional)</h5>
      <canvas ref="firmaTestigo" class="firma-canvas border" width="300" height="150"></canvas>
      <div class="mt-2">
        <button class="btn btn-sm btn-secondary" @click="limpiarFirma('testigo')">🧹 Limpiar</button>
      </div>
    </div>

    <!-- Galería de imágenes -->
    <div class="mb-3">
      <h5>📸 Fotos de la visita (opcional)</h5>
      <!-- ✅ CAMBIO AQUÍ: Eliminado capture="environment" -->
      <input type="file" accept="image/*" multiple @change="cargarImagenes" />
      <div class="row mt-3">
        <div class="col-4" v-for="(img, i) in imagenes" :key="i">
          <img :src="img" class="img-thumbnail mb-2" />
        </div>
      </div>
    </div>

    <!-- Acciones -->
    <button @click="guardar" class="btn btn-primary">💾 Guardar Cierre de Visita</button>
    <button type="button" class="btn btn-success" @click="verRevisionFinal">
      ➡️ Ver Revisión Final
    </button>
  </div>
</template>

<script>
import SignaturePad from 'signature_pad'
import { saveFormData, getFormDataByVisita, getAllDataFromStore } from '../store/indexeddb' // Asegúrate de importar getAllDataFromStore

export default {
  data() {
    return {
      visitaId: null,
      areas: [],
      evaluacionesCosecha: [],
      fertilizaciones: [],
      polinizaciones: [],
      sanidad: null,
      suelo: null,
      laboresCultivo: [],
      firmaPadRealiza: null,
      firmaPadRecibe: null,
      firmaPadTestigo: null,
      imagenes: [],
      cierreVisita: {
        fecha_cierre: '',
        estado_visita: '',
        observaciones: '',
        recomendaciones: '' // ✅ Añadido el campo recomendaciones
      }
    }
  },
  async mounted() { // Usar async mounted para await
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id')
    localStorage.setItem('visita_id', this.visitaId)

    // Inicializar SignaturePads
    this.firmaPadRealiza = new SignaturePad(this.$refs.firmaRealiza)
    this.firmaPadRecibe = new SignaturePad(this.$refs.firmaRecibe)
    this.firmaPadTestigo = new SignaturePad(this.$refs.firmaTestigo)

    // Cargar datos existentes de cierre de visita si los hay
    await this.loadAllData()
  },
  methods: {
    async loadAllData() {
      try {
        // Convertir visitaId a número si es necesario
        const visitaId = Number(this.visitaId) || this.visitaId
        
        // Cargar todas las áreas
        const allAreas = await getAllDataFromStore('area') || []
        this.areas = allAreas.filter(a => {
          // Comparación flexible de visita_id
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
        
        // Cargar datos existentes de cierre de visita
        await this.loadExistingCierreVisitaData()
        
      } catch (error) {
        console.error('Error cargando datos:', error)
        
      }
    },
    limpiarFirma(tipo) {
      if (tipo === 'realiza') this.firmaPadRealiza.clear()
      if (tipo === 'recibe') this.firmaPadRecibe.clear()
      if (tipo === 'testigo') this.firmaPadTestigo.clear()
    },
    cargarImagenes(event) {
      const files = event.target.files
      for (let i = 0; i < files.length; i++) {
        const reader = new FileReader()
        reader.onload = e => {
          this.imagenes.push(e.target.result)
        }
        reader.readAsDataURL(files[i])
      }
    },
    async guardar() {
      try {
        // Validaciones previas (firmas, campos obligatorios)
        if (this.firmaPadRealiza.isEmpty() || this.firmaPadRecibe.isEmpty()) {
          alert('⚠️ Las dos firmas obligatorias deben estar diligenciadas');
          return;
        }
        if (!this.cierreVisita.fecha_cierre || !this.cierreVisita.estado_visita) {
          alert('⚠️ La Fecha de Cierre y el Estado de la Visita son obligatorios.');
          return;
        }

        // Preparar datos para guardar
        const dataToSave = {
          visita_id: this.visitaId,
          fecha_cierre: this.cierreVisita.fecha_cierre,
          estado_visita: this.cierreVisita.estado_visita,
          observaciones_finales: this.cierreVisita.observaciones,
          recomendaciones: this.cierreVisita.recomendaciones,
          firma_responsable: this.firmaPadRealiza.toDataURL('image/png'), // Especificar formato PNG
          firma_recibe: this.firmaPadRecibe.toDataURL('image/png'),
          firma_testigo: this.firmaPadTestigo.isEmpty() ? null : this.firmaPadTestigo.toDataURL('image/png'),
          imagenes: this.imagenes.map(img => img.startsWith('data:') ? img : `data:image/jpeg;base64,${img}`),
          finalizada_en: new Date().toISOString()
        };

        // Guardar en IndexedDB
        await saveFormData('cierre_visitas', dataToSave);
        
        // Limpiar el formulario
        this.resetForm();
        
        alert('✅ Cierre de Visita guardado localmente. Se sincronizará cuando haya conexión.');
      } catch (error) {
        console.error('Error al guardar:', error);
        alert('❌ Error al guardar: ' + error.message);
      }
    },

    resetForm() {
      this.cierreVisita = {
        fecha_cierre: '',
        estado_visita: '',
        observaciones: '',
        recomendaciones: ''
      };
      this.imagenes = [];
      this.firmaPadRealiza.clear();
      this.firmaPadRecibe.clear();
      this.firmaPadTestigo.clear();
    },
    verRevisionFinal() {
      this.$router.push(`/revisionfinal?visita_id=${this.visitaId}`);
    },
    async loadExistingCierreVisitaData() { // Renombrado para mayor claridad
      try {
        const existingCierre = await getFormDataByVisita('cierre_visitas', this.visitaId);
        if (existingCierre) {
          this.cierreVisita = {
            fecha_cierre: existingCierre.fecha_cierre || '',
            estado_visita: existingCierre.estado_visita || '',
            observaciones: existingCierre.observaciones_finales || existingCierre.observaciones || '', // Compatibilidad
            recomendaciones: existingCierre.recomendaciones || '',
          };
          this.imagenes = existingCierre.imagenes || [];

          // Cargar firmas existentes en los SignaturePads si no están vacías
          if (existingCierre.firma_responsable && this.firmaPadRealiza) {
            this.firmaPadRealiza.fromDataURL(existingCierre.firma_responsable);
          }
          if (existingCierre.firma_recibe && this.firmaPadRecibe) {
            this.firmaPadRecibe.fromDataURL(existingCierre.firma_recibe);
          }
          if (existingCierre.firma_testigo && this.firmaPadTestigo) {
            this.firmaPadTestigo.fromDataURL(existingCierre.firma_testigo);
          }
        }
      } catch (error) {
        console.error('Error cargando datos de cierre de visita existentes:', error);
      }
    },
    ucfirst(str) {
      return str ? str.charAt(0).toUpperCase() + str.slice(1) : ''
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A'
      try {
        const date = new Date(dateString)
        return isNaN(date.getTime()) ? 'Fecha inválida' : 
          date.toLocaleDateString('es-ES')
      } catch {
        return 'N/A'
      }
    },
  }
}
</script>

<style scoped>
@import '../styles/offline.css';
.firma-canvas {
  background-color: #fff;
  width: 100%;
  max-width: 300px;
  height: 150px; /* Corregido el error tipográfico '150p' a '150px' */
  border: 1px solid #ccc; /* Añadido borde para mejor visualización */
}
</style>

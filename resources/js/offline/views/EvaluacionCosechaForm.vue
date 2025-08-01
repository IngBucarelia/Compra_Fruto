<template>
  <div class="offline-container">
    <h2 class="offline-title">🌱 Evaluación Cosecha - Registros Previos</h2>

    <!-- 🧾 Módulos previos -->
     <div class="col-md-6">
        <div class="card border-success">
          <div class="card-header bg-success text-white">
            📍 Áreas Registradas
          </div>
          <div class="card-body">
          <div v-if="areas && areas.length > 0">
            <!-- Añade el v-for aquí -->
            <div v-for="(area, index) in areas" :key="area.id" class="mb-3 area-card">
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
        <div class="card-header bg-primary text-white">Polinizaciones Registradas</div>
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
      <InfoCard v-if="laboresCultivo && laboresCultivo.length > 0"  title="🚜 Labores de Cultivo">
        <div v-for="(labor, index) in laboresCultivo" :key="index" class="mb-3">
          <strong>📅 {{ formatDate(labor.created_at) }}</strong>
          <div class="labor-details">
            <div><strong>Tipo Planta:</strong> {{ ucfirst(labor.tipo_planta) }}</div>
            <div v-for="(label, key) in camposLabores" :key="key">
              <span>{{ label }}:</span>
              <strong>{{ labor[key] || 0 }}%</strong>
            </div>
          </div>
          <p v-if="labor.observaciones"><strong>Observaciones:</strong> {{ labor.observaciones }}</p>
        </div>
      </InfoCard>
    </div>

    <h2 class="offline-title">🌴 Evaluación de Cosecha (Modo Offline)</h2>

    <!-- Contenedor para formularios de evaluación -->
    <div class="evaluacion-container">
      <div 
        v-for="(evaluacion, index) in evaluacionesForms" 
        :key="evaluacion.local_id" 
        class="evaluacion-form-card"
      >
        <h4>Evaluación #{{ index + 1 }}</h4>
        <button 
          v-if="evaluacionesForms.length > 1"
          type="button" 
          class="remove-evaluacion-btn"
          @click="removeEvaluacionForm(index)"
        >
          ✖️
        </button>

        <div class="form-group">
          <label class="form-label">Variedad de Fruto:</label>
          <select 
            v-model="evaluacion.variedad_fruto" 
            class="form-control" 
            required
            @change="toggleConformacion(evaluacion)"
          >
            <option value="">Seleccione</option>
            <option value="guinense">Guinense</option>
            <option value="hibrido">Híbrido</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Cantidad de Racimos:</label>
          <input 
            type="number" 
            v-model="evaluacion.cantidad_racimos" 
            class="form-control" 
            required 
            min="0"
          >
        </div>

        <div class="evaluacion-grid">
          <div class="form-group">
            <label class="form-label">Verde (%):</label>
            <input 
              type="number" 
              v-model="evaluacion.verde" 
              class="form-control" 
              required 
              min="0" 
              max="100"
            >
          </div>

          <div class="form-group">
            <label class="form-label">Maduro (%):</label>
            <input 
              type="number" 
              v-model="evaluacion.maduro" 
              class="form-control" 
              required 
              min="0" 
              max="100"
            >
          </div>

          <div class="form-group">
            <label class="form-label">Sobremaduro (%):</label>
            <input 
              type="number" 
              v-model="evaluacion.sobremaduro" 
              class="form-control" 
              required 
              min="0" 
              max="100"
            >
          </div>

          <div class="form-group">
            <label class="form-label">Pedúnculo (%):</label>
            <input 
              type="number" 
              v-model="evaluacion.pedunculo" 
              class="form-control" 
              required 
              min="0" 
              max="100"
            >
          </div>
        </div>

        <div class="form-group" v-if="evaluacion.variedad_fruto === 'hibrido'">
          <label class="form-label">Conformación:</label>
          <input 
            type="text" 
            v-model="evaluacion.conformacion" 
            class="form-control"
          >
        </div>

        <div class="form-group">
          <label class="form-label">Observaciones:</label>
          <textarea 
            v-model="evaluacion.observaciones" 
            class="form-control" 
            rows="3"
          ></textarea>
        </div>
      </div>
    </div>

    <button 
      type="button" 
      class="btn btn-info my-3" 
      @click="addEvaluacionForm"
    >
      ➕ Añadir Otra Evaluación
    </button>

    <div class="button-group">
      <button type="button" class="btn btn-primary" @click="guardarEvaluaciones">
        💾 Guardar Local
      </button>
      <button type="button" class="btn btn-success" @click="irAFirmas">
        ➡️ Continuar con Firmas e Imágenes
      </button>
      <button 
        v-if="canSync" 
        @click="sincronizar" 
        class="btn btn-success"
      >
        🔄 Sincronizar
      </button>
      <button type="button" class="btn btn-secondary" @click="$router.go(-1)">
        Cancelar
      </button>
    </div>

    <!-- Mostrar evaluaciones guardadas localmente -->
    <div v-if="evaluacionesGuardadas.length > 0" class="mt-4">
      <h4 class="offline-subtitle">📋 Evaluaciones guardadas localmente</h4>
      <div 
        v-for="(evaluacion, index) in evaluacionesGuardadas" 
        :key="index" 
        class="saved-evaluacion-card"
      >
        <div class="saved-evaluacion-header">
          <h5>Evaluación {{ index + 1 }} - {{ ucfirst(evaluacion.variedad_fruto) }}</h5>
          <small>{{ formatDate(evaluacion.created_at) }}</small>
        </div>
        <div class="saved-evaluacion-details">
          <div>
            <span>Cantidad de racimos:</span>
            <strong>{{ evaluacion.cantidad_racimos || 0 }}</strong>
          </div>
          <div>
            <span>Verde:</span>
            <strong>{{ evaluacion.verde || 0 }}%</strong>
          </div>
          <div>
            <span>Maduro:</span>
            <strong>{{ evaluacion.maduro || 0 }}%</strong>
          </div>
          <div>
            <span>Sobremaduro:</span>
            <strong>{{ evaluacion.sobremaduro || 0 }}%</strong>
          </div>
          <div>
            <span>Pedúnculo:</span>
            <strong>{{ evaluacion.pedunculo || 0 }}%</strong>
          </div>
          <div v-if="evaluacion.variedad_fruto === 'hibrido'">
            <span>Conformación:</span>
            <strong>{{ evaluacion.conformacion || 'No especificada' }}</strong>
          </div>
        </div>
        <p v-if="evaluacion.observaciones">
          <strong>Observaciones:</strong> {{ evaluacion.observaciones }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import InfoCard from '../../components/InfoCard.vue';
import { getFormDataByVisita, saveFormData, getAllDataFromStore } from '../store/indexeddb';

export default {
  components: { InfoCard },
  data() {
    return {
      visitaId: null,
      areas: [],  // Asegúrate de inicializar como array
      fertilizaciones: [], 
      polinizaciones: [],
      sanidad: null,
      suelo: null,
      laboresCultivo: [],  // Asegúrate de inicializar como array
      evaluacionesForms: [],
      evaluacionesGuardadas: [],
      canSync: navigator.onLine,
      camposLabores: {
        polinizacion: 'Polinización',
        limpieza_calle: 'Limpieza Calle',
        limpieza_plato: 'Limpieza Plato',
        poda: 'Poda',
        fertilizacion: 'Fertilización',
        enmiendas: 'Enmiendas',
        ubicacion_tusa_fibra: 'Ubicación Tusa/Fibra',
        ubicacion_hoja: 'Hoja en Barrera',
        lugar_ubicacion_hoja: 'Hoja en Plato',
        plantas_nectariferas: 'Plantas Nectaríferas',
        cobertura: 'Cobertura',
        labor_cosecha: 'Labor Cosecha',
        calidad_fruta: 'Calidad Fruta',
        recoleccion_fruta: 'Recolección Fruta',
        drenajes: 'Drenajes'
      }
    };
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || 
                    localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    // Cargar datos previos
    await this.loadDatosPrevios();
    // Cargar evaluaciones existentes
    await this.loadEvaluacionesExistentes();
    
    // Si no hay evaluaciones, añadir una por defecto
    if (this.evaluacionesForms.length === 0) {
      this.addEvaluacionForm();
    }

    window.addEventListener('online', this.updateOnlineStatus);
    window.addEventListener('offline', this.updateOnlineStatus);
  },
  beforeUnmount() {
    window.removeEventListener('online', this.updateOnlineStatus);
    window.removeEventListener('offline', this.updateOnlineStatus);
  },
  methods: {
    updateOnlineStatus() {
      this.canSync = navigator.onLine;
    },
    async loadDatosPrevios() {
      try {
        // Cargar todas las áreas
        const allAreas = await getAllDataFromStore('area') || [];
        this.areas = allAreas.filter(area => area.visita_id == this.visitaId);
        
        // Cargar fertilizaciones
        const allFertilizaciones = await getAllDataFromStore('fertilizacion') || [];
        this.fertilizaciones = allFertilizaciones.filter(f => f.visita_id == this.visitaId);
        
        // Cargar polinizaciones
        const allPolinizaciones = await getAllDataFromStore('polinizacion') || [];
        this.polinizaciones = allPolinizaciones.filter(p => p.visita_id == this.visitaId);
        
        // Cargar sanidad
        const allSanidad = await getAllDataFromStore('sanidad') || [];
        this.sanidad = allSanidad.find(s => s.visita_id == this.visitaId) || null;
        
        // Cargar suelo
        const allSuelos = await getAllDataFromStore('suelo') || [];
        this.suelo = allSuelos.find(s => s.visita_id == this.visitaId) || null;
        
        // Cargar labores de cultivo
        const allLabores = await getAllDataFromStore('labores_cultivo') || [];
        this.laboresCultivo = allLabores.filter(l => l.visita_id == this.visitaId);
        
      } catch (error) {
        console.error('Error cargando datos previos:', error);
        // Inicializar arrays vacíos en caso de error
        this.areas = [];
        this.fertilizaciones = [];
        this.polinizaciones = [];
        this.laboresCultivo = [];
      }
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      try {
        const date = new Date(dateString);
        return isNaN(date.getTime()) ? 'Fecha inválida' : 
          date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
          });
      } catch {
        return 'N/A';
      }
    },
    async loadEvaluacionesExistentes() {
      try {
        const evaluaciones = await getAllDataFromStore('evaluacion_cosecha');
        if (evaluaciones && evaluaciones.length > 0) {
          this.evaluacionesGuardadas = evaluaciones
            .filter(e => e.visita_id == this.visitaId)
            .sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0));
          
          // Si no hay evaluaciones en el formulario, cargamos las existentes
          if (this.evaluacionesForms.length === 0 && this.evaluacionesGuardadas.length > 0) {
            this.evaluacionesForms = JSON.parse(JSON.stringify(this.evaluacionesGuardadas));
          }
        }
      } catch (error) {
        console.error('Error cargando evaluaciones existentes:', error);
      }
    },
    addEvaluacionForm() {
      const newEvaluacion = {
        local_id: Date.now().toString(),
        visita_id: this.visitaId,
        variedad_fruto: '',
        cantidad_racimos: '',
        verde: '',
        maduro: '',
        sobremaduro: '',
        pedunculo: '',
        conformacion: '',
        observaciones: '',
        created_at: new Date().toISOString()
      };

      this.evaluacionesForms.push(newEvaluacion);
    },
    removeEvaluacionForm(index) {
      if (confirm('¿Eliminar esta evaluación?')) {
        this.evaluacionesForms.splice(index, 1);
      }
    },
    toggleConformacion(evaluacion) {
      if (evaluacion.variedad_fruto !== 'hibrido') {
        evaluacion.conformacion = '';
      }
    },
    ucfirst(str) {
      return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
    },
    formatDate(dateString) {
      if (!dateString) return 'Fecha no disponible';
      try {
        const date = new Date(dateString);
        return isNaN(date.getTime()) ? 'Fecha inválida' : 
          date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
          });
      } catch {
        return 'Fecha no disponible';
      }
    },
    async guardarEvaluaciones() {
      try {
        // Validar al menos una variedad de fruto seleccionada
        const hasValidForm = this.evaluacionesForms.some(e => e.variedad_fruto);
        if (!hasValidForm) {
          alert('Seleccione al menos una variedad de fruto');
          return;
        }

        for (const evaluacion of this.evaluacionesForms) {
          if (evaluacion.variedad_fruto) {
            // Preparar datos para guardar
            const evaluacionToSave = {
              ...evaluacion,
              // Convertir campos numéricos
              cantidad_racimos: parseInt(evaluacion.cantidad_racimos) || 0,
              verde: parseInt(evaluacion.verde) || null,
              maduro: parseInt(evaluacion.maduro) || null,
              sobremaduro: parseInt(evaluacion.sobremaduro) || null,
              pedunculo: parseInt(evaluacion.pedunculo) || null,
              // Manejar conformación según variedad
              conformacion: evaluacion.variedad_fruto === 'hibrido' ? evaluacion.conformacion : null,
              created_at: evaluacion.created_at || new Date().toISOString(),
              updated_at: new Date().toISOString()
            };
            
            await saveFormData('evaluacion_cosecha', evaluacionToSave);
          }
        }
        
        alert('Evaluaciones guardadas correctamente');
        await this.loadEvaluacionesExistentes();
      } catch (error) {
        console.error('Error al guardar evaluaciones:', error);
        alert('Error al guardar: ' + error.message);
      }
    },
    irAFirmas() {
      this.$router.push(`/firmas?visita_id=${this.visitaId}`);
    },
    async sincronizar() {
      if (!this.canSync) {
        alert('No hay conexión a internet');
        return;
      }
      alert('Sincronización iniciada...');
      // Aquí iría tu lógica de sincronización
    }
  }
};
</script>

<style scoped>
@import '../styles/offline.css';

/* Estilos específicos para este componente */
.evaluacion-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.evaluacion-form-card {
  position: relative;
  padding: 1.5rem;
  border: 1px solid #d4edda;
  border-radius: 0.5rem;
  background-color: #f8fff8;
  box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.remove-evaluacion-btn {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  background-color: #dc3545;
  color: white;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.evaluacion-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
  margin: 1rem 0;
}

@media (min-width: 576px) {
  .evaluacion-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

.button-group {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  margin: 1.5rem 0;
}

.button-group .btn {
  flex: 1 1 auto;
  min-width: 150px;
}

.saved-evaluacion-card {
  padding: 1rem;
  margin-bottom: 1rem;
  border: 1px solid #dee2e6;
  border-radius: 0.25rem;
  background-color: #f8f9fa;
}

.saved-evaluacion-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #eee;
}

.saved-evaluacion-header h5 {
  margin: 0;
  color: #2c3e50;
}

.saved-evaluacion-details {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 0.75rem;
  margin: 0.75rem 0;
}

.saved-evaluacion-details div {
  display: flex;
  justify-content: space-between;
}

.labor-details {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 0.5rem;
  margin: 0.5rem 0;
}

.labor-details div {
  display: flex;
  justify-content: space-between;
}

.offline-subtitle {
  font-size: 1.25rem;
  color: #2c3e50;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #81a572;
}
</style>
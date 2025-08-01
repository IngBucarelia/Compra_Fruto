<template>
  <div class="offline-container">
    <h2 class="offline-title">🌱 Labores Cultivo - Registros Previos</h2>

    <!-- Tarjeta: Áreas -->
      <div class="col-md-6">
        <div class="card border-success">
          <div class="card-header bg-success text-white">
            📍 Áreas Registradas
          </div>
          <div class="card-body">
            <div v-if="areas.length > 0">
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
            <div v-if="fertilizaciones.length > 0">
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

      <div v-if="polinizaciones.length > 0">
        <h4 class="mb-3">Polinizaciones Registradas</h4>
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
    </div>

    <h2 class="offline-title">🚜 Registro de Labores de Cultivo (Modo Offline)</h2>

    <!-- Contenedor para formularios de labores -->
    <div class="labores-container">
      <div 
        v-for="(labor, index) in laboresForms" 
        :key="labor.local_id" 
        class="labor-form-card"
      >
        <h4>Labor #{{ index + 1 }}</h4>
        <button 
          v-if="laboresForms.length > 1"
          type="button" 
          class="remove-labor-btn"
          @click="removeLaborForm(index)"
        >
          ✖️
        </button>

        <div class="form-group">
          <label class="form-label">Tipo de Planta:</label>
          <select v-model="labor.tipo_planta" class="form-control" required>
            <option value="">Seleccione</option>
            <option value="guinense">Guinense</option>
            <option value="hibrido">Híbrido</option>
          </select>
        </div>

        <!-- Campos de labores -->
        <div class="labor-fields-grid">
          <div 
            v-for="(label, key) in camposLabores" 
            :key="key" 
            class="form-group"
          >
            <label>{{ label }} (%):</label>
            <input 
              type="number" 
              v-model="labor[key]" 
              class="form-control" 
              min="0" 
              max="100"
            >
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Observaciones:</label>
          <textarea 
            v-model="labor.observaciones" 
            class="form-control" 
            rows="2"
          ></textarea>
        </div>
      </div>
    </div>

    <button 
      type="button" 
      class="btn btn-info my-3" 
      @click="addLaborForm"
    >
      ➕ Añadir Otra Labor
    </button>

    <div class="button-group">
      <button type="button" class="btn btn-primary" @click="guardarLabores">
        💾 Guardar Local
      </button>
      <button type="button" class="btn btn-success" @click="irAEvaluacionCosecha">
        ➡️ Ir a Evaluación de Cosecha
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

    <!-- Mostrar labores guardadas localmente -->
    <!-- Mostrar labores guardadas localmente -->
<div v-if="laboresGuardadas.length > 0" class="mt-4">
  <h4 class="offline-subtitle">📋 Labores guardadas localmente ({{ laboresGuardadas.length }})</h4>
  
  <div class="saved-labores-container">
    <div 
      v-for="(labor, index) in laboresGuardadas" 
      :key="labor.local_id || index" 
      class="saved-labor-card"
    >
      <div class="saved-labor-header">
        <h5>Labor #{{ index + 1 }} - {{ ucfirst(labor.tipo_planta) }}</h5>
        <small v-if="labor.created_at">
          {{ formatDate(labor.created_at) }}
        </small>
      </div>
      
      <div class="saved-labor-details">
        <div 
          v-for="(label, key) in camposLabores" 
          :key="key"
          v-if="labor[key] !== null && labor[key] !== ''"
        >
          <span>{{ label }}:</span>
          <strong>{{ labor[key] }}%</strong>
        </div>
      </div>
      
      <p v-if="labor.observaciones" class="saved-labor-observaciones">
        <strong>Observaciones:</strong> {{ labor.observaciones }}
      </p>
    </div>
  </div>
</div>
  </div>
</template>

<script>
import InfoCard from '../../components/InfoCard.vue';
import { getFormDataByVisita, saveFormData,getAllDataFromStore } from '../store/indexeddb';

export default {
  components: { InfoCard },
  data() {
    return {
     visitaId: null,
      areas: [], // Cambiar de area a areas (array)
      fertilizaciones: [],
      polinizaciones: [],
      sanidad: null,
      suelo: null,
      laboresForms: [],
      laboresGuardadas: [],
      canSync: navigator.onLine,
      camposLabores: {
        polinizacion: '🌸 Polinización',
        limpieza_calle: '🧹 Limpieza Calle',
        limpieza_plato: '🧹 Limpieza Plato',
        poda: '✂️ Poda',
        fertilizacion: '💧 Fertilización',
        enmiendas: '🧪 Enmiendas',
        ubicacion_tusa_fibra: '📦 Ubicación Tusa/Fibra',
        ubicacion_hoja: '📦  Hoja en Barrera',
        lugar_ubicacion_hoja: '📦 Hoja en Plato',
        plantas_nectariferas: '🌻 Plantas Nectaríferas',
        cobertura: '🌿 Cobertura',
        labor_cosecha: '🚜 Labor Cosecha',
        calidad_fruta: '🍍 Calidad Fruta',
        recoleccion_fruta: '🧺 Recolección Fruta',
        drenajes: '🚰 Drenajes'
      }
    };
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || 
                    localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    // Cargar datos previos
    await this.loadDatosPrevios();
    // Cargar labores existentes
    await this.loadLaboresExistentes();
    
    // Si no hay labores, añadir una por defecto
    if (this.laboresForms.length === 0) {
      this.addLaborForm();
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
        const allAreas = await getAllDataFromStore('area');
        this.areas = allAreas.filter(area => area.visita_id == this.visitaId);
        
        // Cargar datos de suelo
        const suelos = await getAllDataFromStore('suelo');
        this.suelo = suelos.find(s => s.visita_id == this.visitaId) || null;
        
        // Cargar otros datos (fertilización, polinización, sanidad)
        const fert = await getFormDataByVisita('fertilizacion', this.visitaId);
        this.fertilizaciones = Array.isArray(fert) ? fert : fert ? [fert] : [];
        
        const poli = await getFormDataByVisita('polinizacion', this.visitaId);
        this.polinizaciones = Array.isArray(poli) ? poli : poli ? [poli] : [];
        
        this.sanidad = await getFormDataByVisita('sanidad', this.visitaId);
      } catch (error) {
        console.error('Error cargando datos previos:', error);
        alert('Error al cargar datos previos');
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
    async loadLaboresExistentes() {
      try {
        // Obtener todos los datos de labores_cultivo
        const allLabores = await getAllDataFromStore('labores_cultivo');
        
        if (allLabores && allLabores.length > 0) {
          // Filtrar por visita_id y ordenar por fecha (más reciente primero)
          this.laboresGuardadas = allLabores
            .filter(l => l.visita_id == this.visitaId)
            .sort((a, b) => {
              const dateA = new Date(a.created_at || 0);
              const dateB = new Date(b.created_at || 0);
              return dateB - dateA;
            });
          
          // Si no hay labores en el formulario, cargamos las existentes
          if (this.laboresForms.length === 0 && this.laboresGuardadas.length > 0) {
            this.laboresForms = JSON.parse(JSON.stringify(this.laboresGuardadas));
          }
        }
      } catch (error) {
        console.error('Error cargando labores existentes:', error);
        // Opcional: Mostrar un mensaje al usuario
        alert('Error al cargar labores existentes');
      }
},
    addLaborForm() {
      const newLabor = {
        local_id: Date.now().toString(),
        visita_id: this.visitaId,
        tipo_planta: '',
        observaciones: '',
      };
      
      // Inicializar todos los campos de labor
      Object.keys(this.camposLabores).forEach(key => {
        newLabor[key] = '';
      });

      this.laboresForms.push(newLabor);
    },
    removeLaborForm(index) {
      if (confirm('¿Eliminar esta labor?')) {
        this.laboresForms.splice(index, 1);
      }
    },
    ucfirst(str) {
      return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
    },
    async guardarLabores() {
        // Validar al menos un tipo de planta seleccionado
        const hasValidForm = this.laboresForms.some(l => l.tipo_planta);
        if (!hasValidForm) {
          alert('Seleccione al menos un tipo de planta');
          return;
        }

        try {
          for (const labor of this.laboresForms) {
            if (labor.tipo_planta) {
              // Asegurarnos de incluir la fecha de creación
              const laborToSave = {
                ...labor,
                created_at: new Date().toISOString(),
                updated_at: new Date().toISOString()
              };
              await saveFormData('labores_cultivo', laborToSave);
            }
          }
          
          alert('Labores guardadas localmente');
          await this.loadLaboresExistentes(); // Recargar todas las labores
          
          // Opcional: Limpiar el formulario después de guardar
          // this.laboresForms = [];
          // this.addLaborForm();
          
        } catch (error) {
          console.error('Error al guardar:', error);
          alert('Error al guardar las labores: ' + error.message);
        }
      },

    irAEvaluacionCosecha() {
      this.$router.push(`/evaluacion-cosecha?visita_id=${this.visitaId}`);
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
.labores-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.labor-form-card {
  position: relative;
  padding: 1.5rem;
  border: 1px solid #d4edda;
  border-radius: 0.5rem;
  background-color: #f8fff8;
  box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
}

.remove-labor-btn {
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

.labor-fields-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
  margin: 1rem 0;
}

@media (min-width: 768px) {
  .labor-fields-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 992px) {
  .labor-fields-grid {
    grid-template-columns: repeat(3, 1fr);
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

.saved-labor-card {
  padding: 1rem;
  margin-bottom: 1rem;
  border: 1px solid #dee2e6;
  border-radius: 0.25rem;
  background-color: #f8f9fa;
}

.saved-labor-details {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 0.75rem;
  margin: 0.75rem 0;
}

.offline-subtitle {
  font-size: 1.25rem;
  color: #2c3e50;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #81a572;
}
</style>
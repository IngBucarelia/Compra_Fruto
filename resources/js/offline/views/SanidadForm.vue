<template>
<div class="container">
  <div class="offline-container offline-form-container">
    <h2 class="offline-title">🪩 Sanidad - Registros Previos</h2>

    <!-- Sección de información previa -->
    <div class="row mb-4">
      <!-- Tarjeta: Áreas -->
      <div class="col-md-6">
        <div class="card border-success">
          <div class="card-header bg-success text-white">
            📍 Áreas Registradas
          </div>
          <div class="card-body">
            <div v-if="areasInfo.length > 0">
              <div v-for="(area, index) in areasInfo" :key="area.id" class="mb-3 area-card">
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
    </div>



    <h2>🪩 Registro de Sanidad (Modo Offline)</h2>

    <!-- Formulario Sanidad -->
    <form @submit.prevent="guardar">
      <div id="enfermedades-container">
    <div class="enfermedad-group mb-3" v-for="(entry, index) in dynamicDiseases" :key="entry.id">
      <button type="button" class="remove-enfermedad-btn" @click="removeEnfermedad(index)">✖️</button>
      <div class="form-group mb-3">
        <label>Enfermedad:</label>
        <select 
          v-model="entry.name" 
          class="form-control" 
          required
          @change="handleDiseaseChange(entry.id, $event.target.value)"
        >
          <option value="">Seleccione enfermedad</option>
          <option v-for="option in diseaseOptions" :value="option.value" :key="option.value">
            {{ option.text }}
          </option>
        </select>
      </div>
      <div class="form-group mb-3">
        <label>Porcentaje de afectación (%):</label>
        <input
          type="number"
          class="form-control"
          v-model="entry.percentage"
          min="0"
          max="100"
          required
          @input="handlePercentageChange(entry.id, $event.target.value)"
        />
      </div>
    </div>
  </div>

  <button type="button" class="btn btn-info mb-3" @click="addEnfermedad()">+ Añadir enfermedad</button>

      <div class="form-group mb-3">
        <label>Otros (descripción):</label>
        <input type="text" v-model="form.otros" class="form-control" />
      </div>

      <div class="form-group mb-3">
        <label>Observaciones:</label>
        <textarea v-model="form.observaciones" class="form-control" rows="3"></textarea>
      </div>

      <div class="button-group mt-4">
        <button type="submit" class="btn btn-primary">💾 Guardar Sanidad</button>
        <button type="button" class="btn btn-success" @click="irASuelo">
          ➡️ Ir a Estudio de Suelo
        </button>
        <button v-if="canSync" @click="sincronizar" class="btn btn-success">🔄 Sincronizar</button>
        <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
      </div>
    </form>

    <!-- Sección para mostrar sanidades guardadas localmente -->
    <div class="mt-4 p-3 bg-light rounded">
      <h4 class="mb-3">Sanidades Guardadas Localmente</h4>
      <div v-if="localSanidades.length > 0">
        <div v-for="sanidad in localSanidades" :key="sanidad.local_id" class="card mb-3">
         
          
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <template v-for="(value, key) in diseaseFieldMap">
                <li v-if="sanidad[key]" class="list-group-item">
                  <strong>{{ value }}:</strong> {{ sanidad[key] }}%
                </li>
              </template>
              <li v-if="sanidad.otros" class="list-group-item"><strong>Otros:</strong> {{ sanidad.otros }}</li>
              <li v-if="sanidad.observaciones" class="list-group-item"><strong>Observaciones:</strong> {{ sanidad.observaciones }}</li>
            </ul>
          </div>
        </div>
      </div>
      <p v-else class="text-muted">No hay sanidades guardadas localmente.</p>
    </div>
    </div>
  </div>
</template>

<script>
import { getFormDataByVisita, saveFormData, getAllDataFromStore } from '../store/indexeddb';
import { v4 as uuidv4 } from 'uuid';

export default {
  data() {
    return {
      visitaId: null,
      areasInfo: [],
      fertilizaciones: [],
      polinizaciones: [],
      form: {
        opsophanes: null,
        pudricion_cogollo: null,
        raspador: null,
        palmarum: null,
        strategus: null,
        leptopharsa: null,
        pestalotiopsis: null,
        pudricion_basal: null,
        pudricion_estipe: null,
        otros: '',
        observaciones: ''
      },
      dynamicDiseases: [],
      currentEnfermedadIndex: 0,
      localSanidades: [],
      canSync: navigator.onLine,
      diseaseFieldMap: {
        'Opsophanes': 'opsophanes',
        'Pudrición del cogollo': 'pudricion_cogollo',
        'Raspador': 'raspador',
        'Palmarum': 'palmarum',
        'Strategus': 'strategus',
        'Leptopharsa': 'leptopharsa',
        'Pestalotiopsis': 'pestalotiopsis',
        'Pudrición basal': 'pudricion_basal',
        'Pudrición estipe': 'pudricion_estipe'
      }
    };
  },
  computed: {
    diseaseOptions() {
      return Object.keys(this.diseaseFieldMap).map(name => ({
        value: name,
        text: name
      }));
    }
  },
  async mounted() {
    try {
      this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
      if (!this.visitaId) {
        console.error('No se encontró visita_id');
        return;
      }
      
      localStorage.setItem('visita_id', this.visitaId);
      await this.loadInitialData();
      
      if (this.dynamicDiseases.length === 0) {
        this.addEnfermedad();
      }

      window.addEventListener('online', this.updateSyncStatus);
      window.addEventListener('offline', this.updateSyncStatus);
    } catch (error) {
      console.error('Error en mounted:', error);
    }
  },
  beforeUnmount() {
    window.removeEventListener('online', this.updateSyncStatus);
    window.removeEventListener('offline', this.updateSyncStatus);
  },
  methods: {
    updateSyncStatus() {
      this.canSync = navigator.onLine;
    },
    irASuelo() {
      this.$router.push(`/suelo?visita_id=${this.visitaId}`);
    },

    async guardar() {
    try {
      // Verificar que tenemos visita_id
      if (!this.visitaId) {
        throw new Error('No se encontró el ID de visita');
      }

      // Preparar datos para guardar
      const formData = {
        ...this.form,  // Copia todos los campos del formulario
        visita_id: this.visitaId,
        id: uuidv4(),  // Generar un ID único
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      };

      // Procesar enfermedades dinámicas
      this.dynamicDiseases.forEach(entry => {
        if (entry.name && entry.percentage !== undefined) {
          const fieldName = this.diseaseFieldMap[entry.name];
          if (fieldName) {
            formData[fieldName] = entry.percentage;
          }
        }
      });

      // Guardar usando tu IndexedDB existente
      await saveFormData('sanidad', formData);
      
      // Actualizar la lista local
      await this.loadLocalSanidades();
      
      alert('Datos de sanidad guardados correctamente en modo offline');
      
    } catch (error) {
      console.error('Error en guardar:', error);
      alert('Error al guardar: ' + error.message);
    }
  },
  
    async loadInitialData() {
      try {
        const allAreas = await getAllDataFromStore('area');
        this.areasInfo = Array.isArray(allAreas) ? 
          allAreas.filter(item => item.visita_id == this.visitaId) : [];

        const allFertilizaciones = await getAllDataFromStore('fertilizacion');
        this.fertilizaciones = Array.isArray(allFertilizaciones) ?
          allFertilizaciones.filter(item => item.visita_id == this.visitaId) : [];

        const allPolinizaciones = await getAllDataFromStore('polinizacion');
        this.polinizaciones = Array.isArray(allPolinizaciones) ?
          allPolinizaciones.filter(item => item.visita_id == this.visitaId) : [];

        await this.loadLocalSanidades();
        await this.loadExistingSanidadData();
      } catch (error) {
        console.error('Error cargando datos iniciales:', error);
      }
    },
    async loadLocalSanidades() {
      try {
        const allSanidades = await getAllDataFromStore('sanidad');
        this.localSanidades = Array.isArray(allSanidades) ?
          allSanidades.filter(item => item.visita_id == this.visitaId) : [];
      } catch (error) {
        console.error('Error cargando sanidades locales:', error);
      }
    },
    async loadExistingSanidadData() {
      try {
        // Cargar datos existentes de sanidad
        const existingSanidad = this.localSanidades[0]; // Tomamos la primera si existe
        
        if (existingSanidad) {
          // Actualizar el formulario principal
          Object.keys(this.form).forEach(key => {
            if (existingSanidad[key] !== undefined) {
              this.form[key] = existingSanidad[key];
            }
          });

          // Cargar enfermedades dinámicas
          this.dynamicDiseases = [];
          Object.keys(this.diseaseFieldMap).forEach(name => {
            const fieldName = this.diseaseFieldMap[name];
            const percentage = existingSanidad[fieldName];
            if (percentage !== null && percentage !== '') {
              this.dynamicDiseases.push({
                id: this.currentEnfermedadIndex++,
                name,
                percentage
              });
            }
          });

          // Renderizar enfermedades dinámicas
          this.renderDynamicDiseases();
        }
      } catch (error) {
        console.error('Error cargando datos existentes:', error);
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
    resetHiddenDiseaseInputs() {
      Object.keys(this.diseaseFieldMap).forEach(key => {
        const fieldName = this.diseaseFieldMap[key];
        const hiddenInput = document.getElementById(`${fieldName}_hidden`);
        if (hiddenInput) {
          hiddenInput.value = '';
        }
      });
    },
    updateHiddenDiseaseInputs() {
      this.resetHiddenDiseaseInputs();
      this.dynamicDiseases.forEach(entry => {
        const fieldName = this.diseaseFieldMap[entry.name];
        if (fieldName) {
          const hiddenInput = document.getElementById(`${fieldName}_hidden`);
          if (hiddenInput) {
            hiddenInput.value = entry.percentage;
          }
        }
      });
    },
    addEnfermedad(defaultName = '', defaultPercentage = '') {
      this.dynamicDiseases.push({
        id: this.currentEnfermedadIndex++,
        name: defaultName,
        percentage: defaultPercentage
      });
      this.updateHiddenDiseaseInputs();
    },
    handleDiseaseChange(index, newName) {
      const entry = this.dynamicDiseases.find(d => d.id === index);
      if (entry) {
        entry.name = newName;
        this.updateHiddenDiseaseInputs();
      }
    },
    handlePercentageChange(index, newPercentage) {
      const entry = this.dynamicDiseases.find(d => d.id === index);
      if (entry) {
        entry.percentage = newPercentage;
        this.updateHiddenDiseaseInputs();
      }
    },
    removeEnfermedad(index) {
      this.dynamicDiseases = this.dynamicDiseases.filter(entry => entry.id !== index);
      this.updateHiddenDiseaseInputs();
      
      if (this.dynamicDiseases.length === 0) {
        this.addEnfermedad();
      }
    },
    renderDynamicDiseases() {
      // Este método sería llamado desde el template para renderizar las enfermedades
      // La implementación exacta depende de cómo quieras manejarlo en el template
    },
    async submitForm() {
      try {
        // Preparar datos para guardar
        const formData = {
          ...this.form,
          visita_id: this.visitaId,
          id: uuidv4(),
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString()
        };

        // Añadir datos de enfermedades dinámicas al formulario
        this.dynamicDiseases.forEach(entry => {
          const fieldName = this.diseaseFieldMap[entry.name];
          if (fieldName) {
            formData[fieldName] = entry.percentage;
          }
        });

        // Guardar en IndexedDB
        await saveFormData('sanidad', formData);
        
        // Manejar sincronización si está online
        if (this.canSync) {
          await this.syncData();
        }

        // Redireccionar o mostrar mensaje de éxito
        alert('Datos guardados correctamente');
      } catch (error) {
        console.error('Error al guardar:', error);
        alert('Error al guardar los datos');
      }
    },
    async syncData() {
      // Implementar lógica de sincronización con el servidor
    }
  }
};
</script>
<style scoped>

@import '../styles/offline.css';


  .container.offline-form-container {
          background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
          margin-left: -40px !important;
          margin-top: 50px !important;
      }


  .offline-form-container h2.title {
      text-align: center;
      font-family: Arial Black;
      font-weight: bold;
      font-size: 30px;
      color: #fdffe5;
      text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
  }

.offline-form-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
} 

.polinizacion-form-group {
  border: 1px solid #dee2e6;
  border-radius: 8px;
  padding: 20px;
  background-color: #f8f9fa;
  margin-bottom: 25px;
}

.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
  padding-bottom: 10px;
  border-bottom: 1px solid #dee2e6;
}

.button-group {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-top: 25px;
}

@media (max-width: 768px) {

    .container{
      margin-left: -70px !important;
      margin-top: 70px !important;
    }

    .container.offline-form-container {
          background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
          
      }


  .offline-form-container h2.title {
      text-align: center;
      font-family: Arial Black;
      font-weight: bold;
      font-size: 30px;
      color: #fdffe5;
      text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
  }


  .offline-container {
    margin-left: -180px;
  }

  .button-group {
    flex-direction: column;
  }
  
  .button-group .btn {
    width: 100%;
    margin-bottom: 5px;
  }
}

.card {
  margin-bottom: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.list-group-item {
  padding: 10px 15px;
  border-left: 0;
  border-right: 0;
}

.form-group {
  margin-bottom: 15px;
}
</style>
<template>
  <div class="offline-container offline-form-container">
    <h2 class="offline-title">🪩 Sanidad - Registros Previos</h2>

    <!-- Contenedor para las tarjetas de información previa -->
    <div class="previous-info-cards-container mb-4">
      <!-- Tarjetas de Área -->
      <div v-if="areasInfo.length > 0">
        <h4 class="mb-3">Áreas Registradas</h4>
        <div v-for="(area, index) in areasInfo" :key="area.local_id" class="card border-success form-group mb-3">
          <div class="card-header bg-success text-white">
            📍 Área #{{ index + 1 }} - {{ area.material }}
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>Estado:</strong> {{ area.estado }}</li>
              <li class="list-group-item"><strong>Año siembra:</strong> {{ area.anio_siembra }}</li>
              <li class="list-group-item"><strong>Área (m²):</strong> {{ area.area }}</li>
              <li class="list-group-item"><strong>Área Total Finca (Ha):</strong> {{ area.area_total_finca_hectareas || 'N/A' }}</li>
              <li class="list-group-item"><strong>Palmas Total Finca:</strong> {{ area.numero_palmas_total_finca || 'N/A' }}</li>
            </ul>
          </div>
        </div>
      </div>
      <p v-else class="text-muted">No se ha registrado información de área.</p>

      <!-- Tarjetas de Polinización -->
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
        <div class="enfermedad-group mb-3" v-for="entry in dynamicEnfermedades" :key="entry.id">
          <button type="button" class="remove-enfermedad-btn" @click="removeEnfermedad(entry.id)">✖️</button>
          <div class="form-group mb-3">
            <label>Enfermedad:</label>
            <select v-model="entry.nombre" class="form-control" required>
              <option value="">Seleccione enfermedad</option>
              <option value="Opsophanes">Opsophanes</option>
              <option value="Pudrición del cogollo">Pudrición del cogollo</option>
              <option value="Raspador">Raspador</option>
              <option value="Palmarum">Palmarum</option>
              <option value="Strategus">Strategus</option>
              <option value="Leptopharsa">Leptopharsa</option>
              <option value="Pestalotiopsis">Pestalotiopsis</option>
              <option value="Pudrición basal">Pudrición basal</option>
              <option value="Pudrición estipe">Pudrición estipe</option>
            </select>
          </div>
          <div class="form-group mb-3">
            <label>Porcentaje de afectación (%):</label>
            <input
              type="number"
              class="form-control"
              v-model="entry.porcentaje"
              min="0"
              max="100"
              required
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
          <div class="card-header">
            <strong>ID:</strong> {{ sanidad.local_id.substring(0, 8) }}...
          </div>
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
</template>

<script>
import { getFormDataByVisita, saveFormData, getAllDataFromStore } from '../store/indexeddb';
import { v4 as uuidv4 } from 'uuid';

export default {
  data() {
    return {
      visitaId: null,
      areasInfo: [], // Array para múltiples áreas
      polinizaciones: [], // Array para múltiples polinizaciones
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
      dynamicEnfermedades: [],
      nextEnfermedadId: 0,
      localSanidades: [],
      canSync: navigator.onLine,
    };
  },
  computed: {
    diseaseFieldMap() {
      return {
        opsophanes: 'Opsophanes',
        pudricion_cogollo: 'Pudrición del cogollo',
        raspador: 'Raspador',
        palmarum: 'Palmarum',
        strategus: 'Strategus',
        leptopharsa: 'Leptopharsa',
        pestalotiopsis: 'Pestalotiopsis',
        pudricion_basal: 'Pudrición basal',
        pudricion_estipe: 'Pudrición estipe'
      };
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    // Cargar múltiples áreas
    const allAreas = await getAllDataFromStore('area');
    this.areasInfo = allAreas.filter(item => item.visita_id == this.visitaId);

    // Cargar múltiples polinizaciones
    const allPolinizaciones = await getAllDataFromStore('polinizacion');
    this.polinizaciones = allPolinizaciones.filter(item => item.visita_id == this.visitaId);

    // Cargar sanidades locales
    await this.loadLocalSanidades();
    
    // Añadir primera enfermedad si no hay datos
    if (this.dynamicEnfermedades.length === 0) {
      this.addEnfermedad();
    }

    window.addEventListener('online', this.updateSyncStatus);
    window.addEventListener('offline', this.updateSyncStatus);
  },
  beforeUnmount() {
    window.removeEventListener('online', this.updateSyncStatus);
    window.removeEventListener('offline', this.updateSyncStatus);
  },
  methods: {
    updateSyncStatus() {
      this.canSync = navigator.onLine;
    },
    resetFormDiseaseFields() {
      Object.keys(this.diseaseFieldMap).forEach(key => {
        this.form[key] = null;
      });
    },
    updateFormFromDynamicDiseases() {
      this.resetFormDiseaseFields();
      this.dynamicEnfermedades.forEach(entry => {
        const fieldKey = Object.entries(this.diseaseFieldMap).find(
          ([key, value]) => value === entry.nombre
        )?.[0];
        if (fieldKey) {
          this.form[fieldKey] = entry.porcentaje === '' ? null : parseFloat(entry.porcentaje);
        }
      });
    },
    addEnfermedad() {
      this.dynamicEnfermedades.push({
        id: this.nextEnfermedadId++,
        nombre: '',
        porcentaje: ''
      });
    },
    removeEnfermedad(id) {
      if (confirm('¿Eliminar esta enfermedad?')) {
        this.dynamicEnfermedades = this.dynamicEnfermedades.filter(e => e.id !== id);
        if (this.dynamicEnfermedades.length === 0) {
          this.addEnfermedad();
        }
      }
    },
    async loadLocalSanidades() {
      const allSanidades = await getAllDataFromStore('sanidad');
      this.localSanidades = allSanidades.filter(item => item.visita_id == this.visitaId);
    },
    async guardar() {
      // Validaciones
      if (this.dynamicEnfermedades.some(e => !e.nombre || e.porcentaje === '')) {
        alert('Complete todos los campos de enfermedad');
        return;
      }

      this.updateFormFromDynamicDiseases();

      const data = { 
        ...this.form, 
        visita_id: this.visitaId,
        local_id: uuidv4()
      };

      await saveFormData('sanidad', data);
      alert('✅ Sanidad guardada localmente');
      
      // Resetear formulario
      this.resetFormDiseaseFields();
      this.form.otros = '';
      this.form.observaciones = '';
      this.dynamicEnfermedades = [];
      this.addEnfermedad();
      
      // Recargar lista
      await this.loadLocalSanidades();
    },
    async sincronizar() {
      if (!this.canSync) {
        alert('No hay conexión para sincronizar');
        return;
      }
      alert('Sincronización iniciada...');
      // Aquí iría la lógica de sincronización real
    },
    irASuelo() {
      this.$router.push(`/suelo?visita_id=${this.visitaId}`);
    }
  }
};
</script>

<style scoped>
/* Tus estilos existentes se mantienen igual */
.previous-info-cards-container {
  max-height: 400px;
  overflow-y: auto;
  padding-right: 10px;
}

.card {
  margin-bottom: 20px;
}

.card-header {
  font-weight: bold;
}

.list-group-item {
  padding: 8px 15px;
}

.enfermedad-group {
  border: 1px solid #d4edda;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 15px;
  background-color: #f8f9fa;
  position: relative;
}

.remove-enfermedad-btn {
  position: absolute;
  top: 5px;
  right: 5px;
  background: #dc3545;
  color: white;
  border: none;
  border-radius: 50%;
  width: 25px;
  height: 25px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
}

.button-group {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

@media (max-width: 767.98px) {
  .button-group {
    flex-direction: column;
  }
  
  .button-group .btn {
    width: 100%;
  }
  
  .offline-form-container {
    padding: 15px;
    width: 100%;
    margin-left: 0;
  }
}
</style>
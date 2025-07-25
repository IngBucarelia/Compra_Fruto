<template>
  <div class="offline-container">
    <h2 class="offline-title">🌱 Evaluación Cosecha - Registros Previos</h2>

    <!-- Módulos previos (se mantiene igual) -->
    <div class="row mb-4">
      <!-- ... tus InfoCards existentes ... -->
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
        <h5>Evaluación {{ index + 1 }} - {{ ucfirst(evaluacion.variedad_fruto) }}</h5>
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
import { getFormDataByVisita, saveFormData } from '../store/indexeddb';

export default {
  components: { InfoCard },
  data() {
    return {
      visitaId: null,
      area: null,
      fertilizaciones: [],
      polinizaciones: [],
      sanidad: null,
      suelo: null,
      laboresCultivo: [],
      evaluacionesForms: [],
      evaluacionesGuardadas: [],
      canSync: navigator.onLine
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
      this.area = await getFormDataByVisita('area', this.visitaId);
      const fert = await getFormDataByVisita('fertilizacion', this.visitaId);
      this.fertilizaciones = Array.isArray(fert) ? fert : fert ? [fert] : [];
      const poli = await getFormDataByVisita('polinizacion', this.visitaId);
      this.polinizaciones = Array.isArray(poli) ? poli : poli ? [poli] : [];
      this.sanidad = await getFormDataByVisita('sanidad', this.visitaId);
      this.suelo = await getFormDataByVisita('suelo', this.visitaId);
      
      // Cargar labores de cultivo
      const labores = await getFormDataByVisita('labores_cultivo', this.visitaId);
      this.laboresCultivo = Array.isArray(labores) ? labores : labores ? [labores] : [];
    },
    async loadEvaluacionesExistentes() {
      const evaluaciones = await getFormDataByVisita('evaluacion_cosecha', this.visitaId);
      if (evaluaciones) {
        this.evaluacionesForms = Array.isArray(evaluaciones) ? evaluaciones : [evaluaciones];
        this.evaluacionesGuardadas = [...this.evaluacionesForms];
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
        observaciones: ''
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
    async guardarEvaluaciones() {
      // Validar al menos una variedad de fruto seleccionada
      const hasValidForm = this.evaluacionesForms.some(e => e.variedad_fruto);
      if (!hasValidForm) {
        alert('Seleccione al menos una variedad de fruto');
        return;
      }

      try {
        for (const evaluacion of this.evaluacionesForms) {
          if (evaluacion.variedad_fruto) {
            await saveFormData('evaluacion_cosecha', evaluacion);
          }
        }
        
        alert('Evaluaciones guardadas localmente');
        await this.loadEvaluacionesExistentes();
      } catch (error) {
        console.error('Error al guardar:', error);
        alert('Error al guardar las evaluaciones');
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

.saved-evaluacion-details {
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
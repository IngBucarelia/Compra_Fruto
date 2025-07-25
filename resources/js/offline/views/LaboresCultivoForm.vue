<template>
  <div class="offline-container">
    <h2 class="offline-title">🌱 Labores Cultivo - Registros Previos</h2>

    <!-- 🧾 Módulos previos -->
    <div class="row mb-4">
      <InfoCard v-if="area" title="📍 Área" :items="[
        { label: 'Material', value: area.material },
        { label: 'Estado', value: area.estado },
        { label: 'Año siembra', value: area.anio_siembra },
        { label: 'Área (m²)', value: area.area },
        { label: 'Orden Plantis', value: area.orden_plantis_numero },
        { label: 'Estado orden Plantis', value: area.estado_oren_plantis }
      ]" />

      <InfoCard v-if="fertilizaciones.length" title="💧 Fertilización">
        <div v-for="(f, index) in fertilizaciones" :key="index" class="mb-2">
          <strong>📅 {{ f.fecha_fertilizacion }}</strong>
          <ul class="list-group">
            <li v-for="(item, i) in f.fertilizantes" :key="i" class="list-group-item">
              {{ item.nombre }} - {{ item.cantidad }} kg
            </li>
          </ul>
        </div>
      </InfoCard>

      <InfoCard v-if="polinizaciones.length" title="🌸 Polinización">
        <div v-for="(p, index) in polinizaciones" :key="index" class="mb-2">
          <strong>📅 {{ p.fecha }}</strong>
          <ul class="list-group">
            <li>N° Pases: {{ p.n_pases }}</li>
            <li>Ciclos: {{ p.ciclos_ronda }}</li>
            <li>ANA: {{ p.ana }} ({{ p.tipo_ana }})</li>
            <li>Talco: {{ p.talco }}</li>
          </ul>
        </div>
      </InfoCard>

      <InfoCard v-if="sanidad" title="🦠 Sanidad" :items="[
        { label: 'Opsophanes', value: sanidad.opsophanes + '%' },
        { label: 'Pudrición Cogollo', value: sanidad.pudricion_cogollo + '%' },
        { label: 'Raspador', value: sanidad.raspador + '%' },
        { label: 'Palmarum', value: sanidad.palmarum + '%' },
        { label: 'Strategus', value: sanidad.strategus + '%' },
        { label: 'Leptoparsha', value: sanidad.leptoparsha + '%' },
        { label: 'Pestalotiopsis', value: sanidad.pestalotiopsis + '%' },
        { label: 'Pudrición Basal', value: sanidad.pudricion_basal + '%' },
        { label: 'Pudrición Estípite', value: sanidad.pudricion_estipe + '%' },
        { label: 'Otros', value: sanidad.otros },
        { label: 'Observaciones', value: sanidad.observaciones }
      ]" />

      <InfoCard v-if="suelo" title="🧪 Suelo" :items="[
        { label: 'Análisis Foliar', value: suelo.analisis_foliar },
        { label: 'Análisis Suelo', value: suelo.alanalisis_suelo },
        { label: 'Tipo Suelo', value: suelo.tipo_suelo }
      ]" />
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
    <div v-if="laboresGuardadas.length > 0" class="mt-4">
      <h4 class="offline-subtitle">📋 Labores guardadas localmente</h4>
      <div 
        v-for="(labor, index) in laboresGuardadas" 
        :key="index" 
        class="saved-labor-card"
      >
        <h5>Labor {{ index + 1 }} - {{ ucfirst(labor.tipo_planta) }}</h5>
        <div class="saved-labor-details">
          <div v-for="(label, key) in camposLabores" :key="key">
            <span>{{ label }}:</span>
            <strong>{{ labor[key] || 0 }}%</strong>
          </div>
        </div>
        <p v-if="labor.observaciones">
          <strong>Observaciones:</strong> {{ labor.observaciones }}
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
      this.area = await getFormDataByVisita('area', this.visitaId);
      const fert = await getFormDataByVisita('fertilizacion', this.visitaId);
      this.fertilizaciones = Array.isArray(fert) ? fert : fert ? [fert] : [];
      const poli = await getFormDataByVisita('polinizacion', this.visitaId);
      this.polinizaciones = Array.isArray(poli) ? poli : poli ? [poli] : [];
      this.sanidad = await getFormDataByVisita('sanidad', this.visitaId);
      this.suelo = await getFormDataByVisita('suelo', this.visitaId);
    },
    async loadLaboresExistentes() {
      const labores = await getFormDataByVisita('labores_cultivo', this.visitaId);
      if (labores) {
        this.laboresForms = Array.isArray(labores) ? labores : [labores];
        this.laboresGuardadas = [...this.laboresForms];
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
            await saveFormData('labores_cultivo', labor);
          }
        }
        
        alert('Labores guardadas localmente');
        await this.loadLaboresExistentes();
      } catch (error) {
        console.error('Error al guardar:', error);
        alert('Error al guardar las labores');
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
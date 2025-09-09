<template>
  <div class="offline-container">
    <h2 class="offline-title">🌱 Suelo - Registros Previos </h2>

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
                  <li class="list-group-item"><strong>Variedad:</strong> {{ area.variedad }}</li>
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
              <!-- Enfermedades -->
              <li v-if="sanidad.enfermedades && sanidad.enfermedades.length > 0" class="list-group-item">
                <h6>Enfermedades:</h6>
                <ul class="list-group list-group-flush">
                  <li v-for="(enf, eIndex) in sanidad.enfermedades" :key="eIndex" class="list-group-item">
                    <strong>Nombre:</strong> {{ enf.nombre || '-' }},
                    <strong>Estado (%):</strong> {{ enf.estado || '-' }}
                  </li>
                </ul>
              </li>
              <li v-else class="list-group-item text-muted">No hay enfermedades registradas.</li>

              <!-- Plagas -->
              <li v-if="sanidad.plagas && sanidad.plagas.length > 0" class="list-group-item">
                <h6>Plagas:</h6>
                <ul class="list-group list-group-flush">
                  <li v-for="(pla, pIndex) in sanidad.plagas" :key="pIndex" class="list-group-item">
                    <strong>Nombre:</strong> {{ pla.nombre || '-' }},
                    <strong>Estado:</strong> {{ pla.estado || '-' }}
                  </li>
                </ul>
              </li>
              <li v-else class="list-group-item text-muted">No hay plagas registradas.</li>

              <!-- Censo y ciclos -->
              <li v-if="sanidad.censo_enfermedades !== undefined" class="list-group-item">
                <strong>Censo de enfermedades:</strong> {{ sanidad.censo_enfermedades ? 'Sí' : 'No' }}
              </li>
              <li v-if="sanidad.ciclos_lectura_enfermedades" class="list-group-item">
                <strong>Ciclos lectura enfermedades:</strong> {{ sanidad.ciclos_lectura_enfermedades }}
              </li>
              <li v-if="sanidad.ciclos_lectura_plagas" class="list-group-item">
                <strong>Ciclos lectura plagas:</strong> {{ sanidad.ciclos_lectura_plagas }}
              </li>

              <!-- Otros y observaciones -->
              <li v-if="sanidad.otros" class="list-group-item">
                <strong>Otros:</strong> {{ sanidad.otros }}
              </li>
              <li v-if="sanidad.observaciones" class="list-group-item">
                <strong>Observaciones:</strong> {{ sanidad.observaciones }}
              </li>

              <!-- Trampas -->
              <li v-if="sanidad.trampas && sanidad.trampas.length > 0" class="list-group-item">
                <h6 class="mt-2">Trampas de Palmarum:</h6>
                <ul class="list-group list-group-flush">
                  <li v-for="(trampa, index) in sanidad.trampas" :key="index" class="list-group-item">
                    Ciclos: {{ trampa.ciclos || '-' }}, Machos: {{ trampa.machos }}, Hembras: {{ trampa.hembras }}
                  </li>
                </ul>
              </li>
              <li v-else class="list-group-item text-muted">
                No hay trampas registradas.
              </li>
            </ul>
          </div>
          <p v-else class="text-muted card-body">Sin sanidad registrada.</p>
        </div>
      </div>
</div>
    <h2>🧪 Registro de Análisis de Suelo (Modo Offline)</h2>

    <!-- Formulario suelo -->
    <form @submit.prevent="guardar">
      <h4>📋 Formulario de Suelo</h4>
      <div class="mb-3">
        <label>Análisis foliar realizado:</label>
        <select v-model="form.analisis_foliar" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="si">Sí</option>
          <option value="no">No</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Análisis de suelo realizado:</label>
        <select v-model="form.alanalisis_suelo" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="si">Sí</option>
          <option value="no">No</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Tipo de suelo:</label>
        <select v-model="form.tipo_suelo" class="form-control" required>
          <option value="">Seleccione</option>
          <option value="arenoso">Arenoso</option>
          <option value="arcilloso">Arcilloso</option>
          <option value="franco">Franco</option>
          <option value="franco arcilloso">Franco arcilloso</option>
          <option value="franco arenoso">Franco arenoso</option>
          <option value="otro">Otro</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">💾 Guardar Suelo</button>
    </form>
    <button type="button" class="btn btn-success" @click="irALaboresCultivo">
      ➡️ Ir a Labores de Cultivo
    </button>
    <button v-if="canSync" @click="sincronizar" class="btn btn-success mt-3">🔄 Sincronizar</button>
        <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
  </div>
</template>

<script>
import { getFormDataByVisita, saveFormData, getAllDataFromStore } from '../store/indexeddb';

export default {
  data() {
    return {
      visitaId: null,
      areasInfo: [],
      fertilizaciones: [],
      polinizaciones: [],
      sanidad: null,
      form: {
        analisis_foliar: '',
        alanalisis_suelo: '',
        tipo_suelo: ''
      },
      canSync: navigator.onLine
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    // Cargar todas las áreas
    await this.loadAreas();
    
    // Cargar otros datos
    const fert = await getFormDataByVisita('fertilizacion', this.visitaId);
    this.fertilizaciones = Array.isArray(fert) ? fert : fert ? [fert] : [];
    
    const poli = await getFormDataByVisita('polinizacion', this.visitaId);
    this.polinizaciones = Array.isArray(poli) ? poli : poli ? [poli] : [];
    
    // 👉 Cargar sanidad (ya con los nuevos campos)
    this.sanidad = await getFormDataByVisita('sanidad', this.visitaId);

    // Eventos para detectar cambios en la conexión
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
    async loadAreas() {
      try {
        const allAreas = await getAllDataFromStore('area');
        this.areasInfo = Array.isArray(allAreas) ? 
          allAreas.filter(item => item.visita_id == this.visitaId) : [];
      } catch (error) {
        console.error('Error cargando áreas:', error);
      }
    },
    async guardar() {
      try {
        const data = { 
          ...this.form, 
          visita_id: this.visitaId,
          created_at: new Date().toISOString()
        };
        await saveFormData('suelo', data);
        alert('✅ Datos de suelo guardados correctamente');
      } catch (error) {
        console.error('Error al guardar:', error);
        alert('Error al guardar los datos de suelo');
      }
    },
    irALaboresCultivo() {
      this.$router.push(`/labores?visita_id=${this.visitaId}`);
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
    async sincronizar() {
      if (!this.canSync) {
        alert('No hay conexión a internet');
        return;
      }
      alert('Sincronización iniciada...');
      // Aquí iría tu lógica de sincronización
    }
  }
}
</script>

<style scoped>
@import '../styles/offline.css';

/* Estilos adicionales específicos para este componente si los necesitas */
</style>
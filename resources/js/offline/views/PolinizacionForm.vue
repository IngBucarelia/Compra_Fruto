<template>
  <div class="container">
    <div v-if="tieneAreasHibridas">
      <div class="offline-container offline-form-container">
        <h2 class="offline-title">🌱 Polinización - Registros Previos</h2>

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
        </div>

        <h2>🌱 Registro de Polinización (Modo Offline)</h2>

        <!-- Formularios de polinización -->
        <div class="mb-4">
          <button @click="agregarFormulario" class="btn btn-info mb-3">
            ➕ Añadir otra polinización
          </button>

          <div v-for="(formPol, index) in formulariosPolinizacion" :key="index" class="polinizacion-form-group mb-4">
            <div class="form-header">
              <h4>Polinización #{{ index + 1 }}</h4>
              <button 
                v-if="formulariosPolinizacion.length > 1"
                @click="eliminarFormulario(index)" 
                class="btn btn-sm btn-danger"
              >
                ✖ Eliminar
              </button>
            </div>

            <div class="form-body">
              <div class="form-group mb-3">
                <label>🗓️ Fecha de polinización:</label>
                <input type="date" v-model="formPol.fecha" class="form-control" required>
              </div>
              <div class="form-group mb-3">
                <label>🔀 Nº de pases:</label>
                <input type="number" v-model="formPol.n_pases" class="form-control" required min="0">
              </div>
              <div class="form-group mb-3">
                <label>🔁 Ciclos por ronda:</label>
                <input type="number" v-model="formPol.ciclos_ronda" class="form-control" required min="0">
              </div>
              <div class="form-group mb-3">
                <label>💊 Cantidad de ANA aplicada:</label>
                <input type="number" step="0.01" v-model="formPol.ana" class="form-control" required min="0">
              </div>
              <div class="form-group mb-3">
                <label>💧 Tipo de ANA:</label>
                <select v-model="formPol.tipo_ana" class="form-control" required>
                  <option value="">Seleccione</option>
                  <option value="solido">Sólido</option>
                  <option value="liquido">Líquido</option>
                </select>
              </div>
              <div class="form-group mb-3">
                <label>🌬️ Talco aplicado (kg):</label>
                <input type="number" step="0.01" v-model="formPol.talco" class="form-control" required min="0">
              </div>
            </div>
          </div>
        </div>

        <!-- Polinizaciones guardadas -->
        <div v-if="polinizacionesGuardadas.length > 0" class="mb-4 p-3 bg-light rounded">
          <h3>Polinizaciones Guardadas</h3>
          <div v-for="(pol, index) in polinizacionesGuardadas" :key="pol.id" class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
              <span>Polinización #{{ index + 1 }}</span>
              <button @click="eliminarPolinizacion(pol.id)" class="btn btn-sm btn-danger">Eliminar</button>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Fecha:</strong> {{ formatDate(pol.fecha) }}</li>
                <li class="list-group-item"><strong>N° Pases:</strong> {{ pol.n_pases }}</li>
                <li class="list-group-item"><strong>Ciclos:</strong> {{ pol.ciclos_ronda }}</li>
                <li class="list-group-item"><strong>ANA:</strong> {{ pol.ana }} ({{ pol.tipo_ana }})</li>
                <li class="list-group-item"><strong>Talco:</strong> {{ pol.talco }} kg</li>
              </ul>
            </div>
          </div>
        </div>
        <p v-else class="text-muted mb-4">No hay polinizaciones guardadas aún.</p>

        <div class="button-group mt-4">
          <button @click="guardarPolinizaciones" class="btn btn-primary" :disabled="!hayFormulariosValidos">
            💾 Guardar Polinizaciones
          </button>
          <button type="button" class="btn btn-success" @click="irASanidad">
            ➡️ Ir a Sanidad
          </button>
          <button v-if="canSync" @click="sincronizar" class="btn btn-success">🔄 Sincronizar</button>
          <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
        </div>
      </div>
    </div>
    <div v-else class="alert alert-info mt-4">
      <h4>⚠️ No aplica polinización</h4>
      <p>Ninguna de las áreas registradas tiene material "Híbrido".</p>
      
      <button 
        @click="irASanidad" 
        class="btn btn-primary mt-2">
        ➡️ Continuar a Sanidad
      </button>
    </div>
  </div>
</template>

<script>
import { saveFormData, getAllDataFromStore, clearStore  } from '../store/indexeddb';

export default {
  data() {
    return {
      visitaId: null,
      areasInfo: [],
      fertilizaciones: [],
      formulariosPolinizacion: [this.nuevoFormularioPolinizacion()],
      polinizacionesGuardadas: [],
      canSync: navigator.onLine,
      tieneAreasHibridas: false,
    };
  },
  computed: {
    hayFormulariosValidos() {
      return this.formulariosPolinizacion.some(form => 
        form.fecha && form.n_pases && form.ciclos_ronda && form.ana && form.tipo_ana && form.talco
      );
    }
  },
  methods: {
    nuevoFormularioPolinizacion() {
      return {
        fecha: '',
        n_pases: '',
        ciclos_ronda: '',
        ana: '',
        tipo_ana: '',
        talco: ''
      };
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString();
    },
    agregarFormulario() {
      this.formulariosPolinizacion.push(this.nuevoFormularioPolinizacion());
    },
    eliminarFormulario(index) {
      if (confirm('¿Eliminar este formulario de polinización?')) {
        this.formulariosPolinizacion.splice(index, 1);
      }
    },
    async cargarDatosPrevios() {
      try {
        // Cargar todas las áreas
        const allAreas = await getAllDataFromStore('area');
        this.areasInfo = allAreas.filter(area => area.visita_id == this.visitaId);
        
        // Verificar si hay áreas híbridas
        this.tieneAreasHibridas = this.areasInfo.some(area => 
          area.material && area.material.toLowerCase().includes('híbrido')
        );
        
        // Cargar todas las fertilizaciones
        const allFertilizaciones = await getAllDataFromStore('fertilizacion');
        this.fertilizaciones = allFertilizaciones.filter(fert => fert.visita_id == this.visitaId);
        
        // Cargar polinizaciones existentes (ordenadas por fecha descendente)
        const allPolinizaciones = await getAllDataFromStore('polinizacion');
        this.polinizacionesGuardadas = allPolinizaciones
          .filter(pol => pol.visita_id == this.visitaId)
          .sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
      } catch (error) {
        console.error('Error cargando datos:', error);
      }
    },
    async guardarPolinizaciones() {
      try {
        const polinizacionesValidas = this.formulariosPolinizacion.filter(form => 
          form.fecha && form.n_pases && form.ciclos_ronda && form.ana && form.tipo_ana && form.talco
        );

        if (polinizacionesValidas.length === 0) {
          alert('Complete al menos un formulario válido');
          return;
        }

        // Generar un ID único para cada polinización
        const polinizacionesParaGuardar = polinizacionesValidas.map(form => ({
          ...form,
          id: Date.now().toString(36) + Math.random().toString(36).substr(2), // ID único
          visita_id: this.visitaId,
          created_at: new Date().toISOString(),
          n_pases: parseInt(form.n_pases),
          ciclos_ronda: parseInt(form.ciclos_ronda),
          ana: parseFloat(form.ana),
          talco: parseFloat(form.talco)
        }));

        // Guardar todas las polinizaciones
        for (const polinizacion of polinizacionesParaGuardar) {
          await saveFormData('polinizacion', polinizacion);
        }

        // Actualizar la lista de polinizaciones guardadas
        await this.cargarDatosPrevios();
        
        // Limpiar formularios y dejar uno vacío
        this.formulariosPolinizacion = [this.nuevoFormularioPolinizacion()];
        
        alert(`✅ ${polinizacionesParaGuardar.length} polinizaciones guardadas correctamente`);
      } catch (error) {
        console.error('Error guardando polinizaciones:', error);
        alert('Error al guardar las polinizaciones: ' + error.message);
      }
    },
    async eliminarPolinizacion(id) {
      if (confirm('¿Eliminar esta polinización permanentemente?')) {
        try {
          // Obtener todas las polinizaciones
          const todasPolinizaciones = await getAllDataFromStore('polinizacion');
          
          // Filtrar para quitar la que queremos eliminar
          const polinizacionesActualizadas = todasPolinizaciones.filter(pol => pol.id !== id);
          
          // Limpiar el store completo
          await clearStore('polinizacion');
          
          // Volver a guardar todas excepto la eliminada
          for (const pol of polinizacionesActualizadas) {
            await saveFormData('polinizacion', pol);
          }
          
          // Actualizar la vista
          await this.cargarDatosPrevios();
          alert('Polinización eliminada correctamente');
        } catch (error) {
          console.error('Error eliminando polinización:', error);
          alert('Error al eliminar la polinización');
        }
      }
    },
    irASanidad() {
      this.$router.push(`/sanidad?visita_id=${this.visitaId}`);
    },
    async sincronizar() {
      if (!this.canSync) {
        alert('No hay conexión a internet para sincronizar');
        return;
      }
      alert('Sincronización iniciada...');
      // Lógica de sincronización aquí
    },
    updateOnlineStatus() {
      this.canSync = navigator.onLine;
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    await this.cargarDatosPrevios();
    window.addEventListener('online', this.updateOnlineStatus);
    window.addEventListener('offline', this.updateOnlineStatus);
  },
  beforeUnmount() {
    window.removeEventListener('online', this.updateOnlineStatus);
    window.removeEventListener('offline', this.updateOnlineStatus);
  }
};
</script>

<style scoped>
@import '../styles/offline.css';

.container.offline-form-container {
  background-color: rgba(129, 165, 114, 0.929);
  margin-left: -80px !important;
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
  .container {
    margin-left: -70px !important;
    margin-top: 70px !important;
  }

  .container.offline-form-container {
    background-color: rgba(129, 165, 114, 0.929);
    margin-left: -40px;
    margin-top: 50px;
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
<template>
  <div class="offline-container offline-form-container">
    <h2 class="offline-title">📍 Registro de Áreas (Modo Offline)</h2>

    <!-- Formulario para agregar múltiples áreas -->
    <div class="mb-4">
      <h3>Nuevas Áreas</h3>
      <button @click="agregarFormulario" class="btn btn-info mb-3">
        ➕ Añadir otra área
      </button>

      <div v-for="(formArea, index) in formulariosAreas" :key="index" class="area-form-group mb-4">
        <div class="form-header">
          <h4>Área #{{ index + 1 }}</h4>
          <button 
            v-if="formulariosAreas.length > 1"
            @click="eliminarFormulario(index)" 
            class="btn btn-sm btn-danger"
          >
            ✖ Eliminar
          </button>
        </div>

        <div class="form-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label class="form-label">Material</label>
                <select v-model="formArea.material" class="form-control" required>
                  <option value="">Seleccione</option>
                  <option value="guinense">Guinense</option>
                  <option value="hibrido">Híbrido</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Estado</label>
                <select v-model="formArea.estado" class="form-control" required>
                  <option value="desarrollo">Desarrollo</option>
                  <option value="produccion">Producción</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Año siembra</label>
                <input type="date" v-model="formArea.anio_siembra" class="form-control" required />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Área (m²)</label>
                <input type="number" step="0.01" v-model="formArea.area" class="form-control" required />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Área Total Finca (Ha)</label>
                <input type="number" step="0.01" v-model="formArea.area_total_finca_hectareas" class="form-control" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>N° Palmas Total Finca</label>
                <input type="number" v-model="formArea.numero_palmas_total_finca" class="form-control" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Área Palmas Desarrollo (Ha)</label>
                <input type="number" step="0.01" v-model="formArea.area_palmas_desarrollo_hectareas" class="form-control" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>N° Palmas Desarrollo</label>
                <input type="number" v-model="formArea.numero_palmas_desarrollo" class="form-control" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Área Palmas Producción (Ha)</label>
                <input type="number" step="0.01" v-model="formArea.area_palmas_produccion_hectareas" class="form-control" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>N° Palmas Producción</label>
                <input type="number" v-model="formArea.numero_palmas_produccion" class="form-control" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Ciclos de Cosecha</label>
                <input type="number" v-model="formArea.ciclos_cosecha" class="form-control" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Producción (Toneladas/Mes)</label>
                <input type="number" step="0.01" v-model="formArea.produccion_toneladas_por_mes" class="form-control" />
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <label>Aplica Orden Plantis?</label>
            <select v-model="formArea.aplica_orden_plantis" class="form-control" @change="formArea.orden_plantis_numero = ''">
              <option :value="true">Sí</option>
              <option :value="false">No</option>
            </select>
          </div>

          <template v-if="formArea.aplica_orden_plantis">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label>Orden Plantis Número</label>
                  <input type="number" v-model="formArea.orden_plantis_numero" class="form-control" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-3">
                  <label>Estado Orden Plantis</label>
                  <select v-model="formArea.estado_oren_plantis" class="form-control">
                    <option value="desarrollo">Desarrollo</option>
                    <option value="produccion">Producción</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-3">
              <label>Número de Plantas (Orden Plantis)</label>
              <input type="number" v-model="formArea.numero_plantas_orden_plantis" class="form-control" />
            </div>
          </template>
        </div>
      </div>
    </div>

    <!-- Áreas ya guardadas -->
    <div v-if="areasGuardadas.length > 0" class="mb-4 p-3 bg-light rounded">
      <h3>Áreas Guardadas</h3>
      <div v-for="(area, index) in areasGuardadas" :key="area.local_id" class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Área #{{ index + 1 }}</span>
          <button @click="eliminarArea(area.local_id)" class="btn btn-sm btn-danger">Eliminar</button>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Material:</strong> {{ area.material }}</li>
            <li class="list-group-item"><strong>Estado:</strong> {{ area.estado }}</li>
            <li class="list-group-item"><strong>Año siembra:</strong> {{ formatDate(area.anio_siembra) }}</li>
            <li class="list-group-item"><strong>Área (m²):</strong> {{ area.area }}</li>
            <li class="list-group-item"><strong>Área Total Finca (Ha):</strong> {{ area.area_total_finca_hectareas }}</li>
            <li class="list-group-item"><strong>N° Palmas Total:</strong> {{ area.numero_palmas_total_finca }}</li>
            <li class="list-group-item"><strong>Área Palmas Desarrollo (Ha):</strong> {{ area.area_palmas_desarrollo_hectareas }}</li>
            <li class="list-group-item"><strong>N° Palmas Desarrollo:</strong> {{ area.numero_palmas_desarrollo }}</li>
            <li class="list-group-item"><strong>Área Palmas Producción (Ha):</strong> {{ area.area_palmas_produccion_hectareas }}</li>
            <li class="list-group-item"><strong>N° Palmas Producción:</strong> {{ area.numero_palmas_produccion }}</li>
            <li class="list-group-item"><strong>Ciclos de Cosecha:</strong> {{ area.ciclos_cosecha }}</li>
            <li class="list-group-item"><strong>Producción (Toneladas/Mes):</strong> {{ area.produccion_toneladas_por_mes }}</li>
            <li class="list-group-item"><strong>Aplica Orden Plantis:</strong> {{ area.aplica_orden_plantis ? 'Sí' : 'No' }}</li>
            <template v-if="area.aplica_orden_plantis">
              <li class="list-group-item"><strong>Orden Plantis N°:</strong> {{ area.orden_plantis_numero }}</li>
              <li class="list-group-item"><strong>Estado Orden Plantis:</strong> {{ area.estado_oren_plantis }}</li>
              <li class="list-group-item"><strong>N° Plantas Orden Plantis:</strong> {{ area.numero_plantas_orden_plantis }}</li>
            </template>
          </ul>
        </div>
      </div>
    </div>
    <p v-else class="text-muted mb-4">No hay áreas guardadas aún.</p>

    <div class="button-group mt-4">
      <button @click="guardarAreas" class="btn btn-primary" :disabled="!hayFormulariosValidos">
        💾 Guardar Áreas
      </button>
      <button 
        type="button" 
        class="btn btn-success" 
        @click="irAFertilizacion"
        :disabled="areasGuardadas.length === 0"
      >
        ➡️ Ir a Fertilización
      </button>
      <button v-if="canSync" @click="sincronizar" class="btn btn-success">🔄 Sincronizar</button>
      <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
    </div>
  </div>
</template>

<script>
import { saveFormData, getAllDataFromStore, deleteDataFromStore } from '../store/indexeddb';

export default {
  data() {
    return {
      formulariosAreas: [this.nuevoFormularioArea()],
      areasGuardadas: [],
      visitaId: null,
      canSync: navigator.onLine,
    };
  },
  computed: {
    hayFormulariosValidos() {
      return this.formulariosAreas.some(form => 
        form.material && form.anio_siembra && form.area
      );
    }
  },
  methods: {
    nuevoFormularioArea() {
      return {
        material: '',
        estado: 'desarrollo',
        anio_siembra: '',
        area: '',
        orden_plantis_numero: '',
        estado_oren_plantis: 'desarrollo',
        area_total_finca_hectareas: null,
        numero_palmas_total_finca: null,
        area_palmas_desarrollo_hectareas: null,
        numero_palmas_desarrollo: null,
        area_palmas_produccion_hectareas: null,
        numero_palmas_produccion: null,
        ciclos_cosecha: null,
        produccion_toneladas_por_mes: null,
        aplica_orden_plantis: false,
        numero_plantas_orden_plantis: null
      };
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString();
    },
    agregarFormulario() {
      this.formulariosAreas.push(this.nuevoFormularioArea());
    },
    eliminarFormulario(index) {
      if (confirm('¿Eliminar este formulario de área?')) {
        this.formulariosAreas.splice(index, 1);
      }
    },
    async cargarAreasGuardadas() {
        try {
          const todasAreas = await getAllDataFromStore('area');
          // Filtra por visita_id y mapea para incluir el ID de IndexedDB
          this.areasGuardadas = todasAreas
            .filter(area => area.visita_id == this.visitaId)
            .map(area => ({
              ...area,
              id: area.id // Asegúrate de incluir el ID generado por IndexedDB
            }));
        } catch (error) {
          console.error('Error cargando áreas:', error);
          alert('Error cargando áreas: ' + error.message);
        }
      },


   async guardarAreas() {
      try {
        const areasValidas = this.formulariosAreas.filter(form => 
          form.material && form.anio_siembra && form.area
        );

        if (areasValidas.length === 0) {
          alert('Complete al menos un formulario válido');
          return;
        }

        for (const form of areasValidas) {
          const areaData = {
            ...form,
            visita_id: this.visitaId,
            // No incluyas local_id aquí, ya que tu IndexedDB usa autoIncrement
            aplica_orden_plantis: Boolean(form.aplica_orden_plantis),
            // Asegúrate que los campos numéricos sean números
            area_total_finca_hectareas: parseFloat(form.area_total_finca_hectareas) || null,
            numero_palmas_total_finca: parseInt(form.numero_palmas_total_finca) || null,
            // Repite para otros campos numéricos
          };
          
          // Guardar en IndexedDB
          await saveFormData('area', areaData);
        }

        await this.cargarAreasGuardadas();
        this.formulariosAreas = [this.nuevoFormularioArea()];
        alert(`✅ ${areasValidas.length} áreas guardadas correctamente`);
      } catch (error) {
        console.error('Error guardando áreas:', error);
        alert('Error al guardar las áreas. Detalle: ' + error.message);
      }
    },


   async eliminarArea(localId) {
      if (confirm('¿Eliminar esta área permanentemente?')) {
        try {
          // Cargar todas las áreas
          const todasAreas = await getAllDataFromStore('area');
          
          // Filtrar para mantener todas excepto la que queremos eliminar
          const areasActualizadas = todasAreas.filter(area => area.id !== localId); // Cambiado a 'id'
          
          // Eliminar todas las áreas
          await clearStore('area');
          
          // Guardar las áreas actualizadas (excepto la eliminada)
          for (const area of areasActualizadas) {
            await saveFormData('area', area);
          }
          
          await this.cargarAreasGuardadas();
          alert('Área eliminada correctamente');
        } catch (error) {
          console.error('Error eliminando área:', error);
          alert('Error al eliminar el área: ' + error.message);
        }
      }
    },
    irAFertilizacion() {
      this.$router.push(`/fertilizacion?visita_id=${this.visitaId}`);
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

    await this.cargarAreasGuardadas();
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

.offline-form-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

.area-form-group {
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

  .offline-container{
    margin-left: -240px;
    margin-top: 65PX;
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

.form-label {
  font-weight: 500;
  margin-bottom: 5px;
  display: block;
}
</style>
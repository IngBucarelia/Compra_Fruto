<template>
  <div class="offline-container offline-form-container">
    <h2 class="offline-title">📍 Registro de Áreas (Modo Offline)</h2><br><br>
      <!-- INFORMACIÓN DE LA VISITA - DISEÑO MEJORADO -->
    <div v-if="visitaInfo" class="visita-info-card mb-4">
      <div class="card visita-card-theme">
        <div class="card-header visita-header">
          <div class="d-flex align-items-center">
            <div class="visita-icon">
              <i class="fas fa-tree-palm"></i>
            </div>
            <div class="visita-title">
              <h4 class="mb-0">🌴 Información de la Visita - Plantación de Palma</h4>
              <small class="visita-subtitle">Datos de la visita técnica</small>
            </div>
          </div>
          <div class="visita-status" :class="getStatusClass(visitaInfo.estado)">
            {{ getEstadoText(visitaInfo.estado) }}
          </div>
        </div>
        
        <div class="card-body visita-body">
          <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-user-tie"></i>
                </div>
                <div class="info-content">
                  <label>Proveedor</label>
                  <p class="info-value">{{ visitaInfo.proveedor_nombre || visitaInfo.proveedor?.proveedor_nombre || 'No especificado' }}</p>
                </div>
              </div>
              
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-tractor"></i>
                </div>
                <div class="info-content">
                  <label>Plantación</label>
                  <p class="info-value">{{ visitaInfo.plantacion_nombre || visitaInfo.plantacion?.nombre || 'No especificado' }}</p>
                </div>
              </div>
              
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-content">
                  <label>Ubicación</label>
                  <p class="info-value">{{ visitaInfo.ubicacion || 'No especificada' }}</p>
                </div>
              </div>
            </div>
            
            <!-- Columna Derecha -->
            <div class="col-md-6">
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-user-hard-hat"></i>
                </div>
                <div class="info-content">
                  <label>Técnico de Campo</label>
                  <p class="info-value">{{ visitaInfo.tecnico_campo || 'No asignado' }}</p>
                </div>
              </div>
              
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="info-content">
                  <label>Tipo de Visita</label>
                  <p class="info-value">{{ visitaInfo.tipo_visita || 'No especificado' }}</p>
                </div>
              </div>
              
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="info-content">
                  <label>Fecha de Visita</label>
                  <p class="info-value">{{ formatDate(visitaInfo.fecha) }}</p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Información adicional -->
          <div class="row mt-3">
            <div class="col-12">
              <div class="info-item full-width">
                <div class="info-icon">
                  <i class="fas fa-users"></i>
                </div>
                <div class="info-content">
                  <label>Persona que recibió la visita</label>
                  <p class="info-value">{{ visitaInfo.recibio_visita || 'No especificado' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="card-footer visita-footer">
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
              <i class="fas fa-sync-alt me-1"></i>
              Modo offline activo - Los datos se sincronizarán cuando haya conexión
            </small>
            <div class="visita-id">
              ID: {{ visitaId }}
            </div>
          </div>
        </div>
      </div>
    </div>

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
                <label class="form-label">Variedad</label>
                <select v-model="formArea.variedad" class="form-control" required>
                  <option value="">Seleccione</option>
                  <option value="guinense">Guinense</option>
                  <option value="hibrido">Híbrido</option>
                </select>
              </div>
              
              <!-- CAMPO MATERIAL MODIFICADO -->
              <div class="form-group mb-3">
                <label class="form-label">Material</label>
                <select v-model="formArea.material" class="form-control" @change="toggleOtroMaterial(index)" required>
                  <option value="">Seleccione</option>
                  <option value="cuari x lame">cuari x lame</option>
                  <option value="cuari x lame – fortuna">cuari x lame – fortuna</option>
                  <option value="manicore">manicore</option>
                  <option value="taisha">taisha</option>
                  <option value="amazon">amazon</option>
                  <option value="unipalma">unipalma</option>
                  <option value="deli x lame">deli x lame</option>
                  <option value="deli x yangambi">deli x yangambi</option>
                  <option value="kigoma">kigoma</option>
                  <option value=" # s"> # s</option>
                  <option value="# pc"># pc</option>
                  <option value="# c"># c</option>
                  <option value="paraiso">paraiso</option>
                  <option value="otro">otro</option>
                </select>
                
                <!-- CAMPO PARA "OTRO" MATERIAL -->
                <div v-if="formArea.mostrarCampoOtroMaterial" class="mt-2">
                  <input 
                    type="text" 
                    v-model="formArea.otroMaterial" 
                    class="form-control" 
                    placeholder="Ingrese el nombre del material"
                    @input="actualizarNombreMaterial(index)"
                    required
                  >
                  <small class="text-muted">Escriba el nombre del material que no aparece en la lista</small>
                </div>
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
                <input 
                  type="number" 
                  v-model="formArea.anio_siembra" 
                  @blur="validateYear(index)" 
                  class="form-control" 
                  min="1900" 
                  :max="new Date().getFullYear()" 
                  required 
                  placeholder="Seleccione el año"
                />
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
            <li class="list-group-item"><strong>Variedad:</strong> {{ area.variedad }}</li>
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
import { saveFormData, getAllDataFromStore, deleteDataFromStore, getFormDataByVisita } from '../store/indexeddb';

export default {
  data() {
    return {
      formulariosAreas: [this.nuevoFormularioArea()],
      areasGuardadas: [],
      visitaId: null,
      visitaInfo: null,
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
    // MÉTODO PARA CARGAR INFORMACIÓN DE LA VISITA
    async cargarInformacionVisita() {
      try {
        console.log('Cargando información de visita para ID:', this.visitaId);
        
        // Intentar cargar desde localStorage primero (más rápido)
        const visitaLocalStorage = localStorage.getItem('visita_offline_' + this.visitaId);
        if (visitaLocalStorage) {
          this.visitaInfo = JSON.parse(visitaLocalStorage);
          console.log('Visita cargada desde localStorage:', this.visitaInfo);
          return;
        }

        // Si no está en localStorage, buscar en IndexedDB
        const todasVisitas = await getAllDataFromStore('visita');
        console.log('Todas las visitas en IndexedDB:', todasVisitas);
        
        const visita = todasVisitas.find(v => 
          v.id == this.visitaId || 
          v.local_id == this.visitaId ||
          (v.formData && (v.formData.id == this.visitaId || v.formData.local_id == this.visitaId))
        );

        if (visita) {
          // Si la visita está guardada con la estructura de submissions
          this.visitaInfo = visita.formData || visita;
          console.log('Visita cargada desde IndexedDB:', this.visitaInfo);
          
          // Guardar en localStorage para próximas cargas
          localStorage.setItem('visita_offline_' + this.visitaId, JSON.stringify(this.visitaInfo));
        } else {
          console.warn('No se encontró información de la visita con ID:', this.visitaId);
          // Crear objeto vacío para evitar errores
          this.visitaInfo = {
            proveedor_nombre: 'No disponible (modo offline)',
            plantacion_nombre: 'No disponible (modo offline)',
            ubicacion: 'No disponible',
            tecnico_campo: 'No disponible',
            tipo_visita: 'No disponible',
            recibio_visita: 'No disponible',
            estado: 'en_progreso'
          };
        }
      } catch (error) {
        console.error('Error cargando información de la visita:', error);
        // Crear objeto vacío para evitar errores
        this.visitaInfo = {
          proveedor_nombre: 'Error al cargar',
          plantacion_nombre: 'Error al cargar',
          ubicacion: 'Error al cargar',
          tecnico_campo: 'Error al cargar',
          tipo_visita: 'Error al cargar',
          recibio_visita: 'Error al cargar',
          estado: 'en_progreso'
        };
      }
    },

    getEstadoText(estado) {
      const estados = {
        'pendiente': 'Pendiente',
        'en_progreso': 'En Progreso',
        'completada': 'Completada'
      };
      return estados[estado] || 'En Progreso';
    },

    // MÉTODOS EXISTENTES (sin cambios)
    toggleOtroMaterial(index) {
      if (this.formulariosAreas[index].material === 'otro') {
        this.formulariosAreas[index].mostrarCampoOtroMaterial = true;
        this.formulariosAreas[index].otroMaterial = '';
        this.formulariosAreas[index].material = '';
      } else {
        this.formulariosAreas[index].mostrarCampoOtroMaterial = false;
        this.formulariosAreas[index].otroMaterial = '';
      }
    },
    
    actualizarNombreMaterial(index) {
      if (this.formulariosAreas[index].otroMaterial.trim() !== '') {
        this.formulariosAreas[index].material = this.formulariosAreas[index].otroMaterial;
      } else {
        this.formulariosAreas[index].material = '';
      }
    },

    nuevoFormularioArea() {
      return {
        variedad: '',
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
        numero_plantas_orden_plantis: null,
        mostrarCampoOtroMaterial: false,
        otroMaterial: ''
      };
    },

    generateUUID() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
    },

    validateYear(index) {
      const year = this.formulariosAreas[index].anio_siembra;
      const currentYear = new Date().getFullYear();
      
      if (year && (year < 1900 || year > currentYear)) {
        alert('Por favor ingrese un año válido entre 1900 y ' + currentYear);
        this.formulariosAreas[index].anio_siembra = '';
      }
    },

    formatDate(dateString) {
      if (!dateString) return 'N/A';
      try {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });
      } catch (error) {
        return dateString;
      }
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
        this.areasGuardadas = todasAreas
          .filter(area => area.visita_id == this.visitaId)
          .map(area => ({
            ...area,
            id: area.id
          }));
      } catch (error) {
        console.error('Error cargando áreas:', error);
      }
    },

    async guardarAreas() {
      try {
        const areasValidas = this.formulariosAreas.filter(form => {
          const tieneMaterial = form.material && form.material.trim() !== '';
          const tieneAnioSiembra = form.anio_siembra && form.anio_siembra !== '';
          const tieneArea = form.area && form.area !== '';
          return tieneMaterial && tieneAnioSiembra && tieneArea;
        });

        if (areasValidas.length === 0) {
          alert('Complete al menos un formulario válido (Material, Año siembra y Área son obligatorios)');
          return;
        }

        for (const form of areasValidas) {
          const local_id = form.local_id || this.generateUUID();
          const { mostrarCampoOtroMaterial, otroMaterial, ...areaDataParaGuardar } = form;
          
          const areaData = {
            ...areaDataParaGuardar,
            local_id,
            visita_id: this.visitaId,
            aplica_orden_plantis: Boolean(form.aplica_orden_plantis),
            area: parseFloat(form.area),
            area_total_finca_hectareas: form.area_total_finca_hectareas ? parseFloat(form.area_total_finca_hectareas) : null,
            area_palmas_desarrollo_hectareas: form.area_palmas_desarrollo_hectareas ? parseFloat(form.area_palmas_desarrollo_hectareas) : null,
            area_palmas_produccion_hectareas: form.area_palmas_produccion_hectareas ? parseFloat(form.area_palmas_produccion_hectareas) : null,
            numero_palmas_total_finca: form.numero_palmas_total_finca ? parseInt(form.numero_palmas_total_finca) : null,
            numero_palmas_desarrollo: form.numero_palmas_desarrollo ? parseInt(form.numero_palmas_desarrollo) : null,
            numero_palmas_produccion: form.numero_palmas_produccion ? parseInt(form.numero_palmas_produccion) : null,
            ciclos_cosecha: form.ciclos_cosecha ? parseInt(form.ciclos_cosecha) : null,
            produccion_toneladas_por_mes: form.produccion_toneladas_por_mes ? parseFloat(form.produccion_toneladas_por_mes) : null,
            numero_plantas_orden_plantis: form.numero_plantas_orden_plantis ? parseInt(form.numero_plantas_orden_plantis) : null
          };
          
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
          const todasAreas = await getAllDataFromStore('area');
          const areasActualizadas = todasAreas.filter(area => area.id !== localId);
          const { clearStore } = await import('../store/indexeddb');
          await clearStore('area');
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
    },

    updateOnlineStatus() {
      this.canSync = navigator.onLine;
    },
    getStatusClass(estado) {
      const statusClasses = {
        'completada': 'status-completed',
        'en_progreso': 'status-in-progress',
        'pendiente': 'status-pending'
      };
      return statusClasses[estado] || 'status-in-progress';
    },
  },

  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    // Cargar información de la visita y áreas
    await this.cargarInformacionVisita();
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

.visita-info-card {
  margin-bottom: 20px;
}

.visita-info-card .card-header {
  font-weight: bold;
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

.badge {
  font-size: 0.875em;
}

/* ESTILOS MEJORADOS PARA LA TARJETA DE VISITA */

.visita-card-theme {
  border: none;
  border-radius: 15px;
  box-shadow: 0 8px 25px rgba(139, 195, 74, 0.15);
  overflow: hidden;
  background: linear-gradient(135deg, #ffffff 0%, #f8fff8 100%);
}

.visita-header {
  background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
  color: white;
  padding: 20px 25px;
  border-bottom: 3px solid #ffd54f;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.visita-icon {
  font-size: 2rem;
  margin-right: 15px;
  background: rgba(255, 255, 255, 0.2);
  padding: 12px;
  border-radius: 12px;
  backdrop-filter: blur(10px);
}

.visita-title h4 {
  font-weight: 700;
  font-size: 1.4rem;
  margin-bottom: 5px;
}

.visita-subtitle {
  opacity: 0.9;
  font-size: 0.85rem;
}

.visita-status {
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-completed {
  background: rgba(76, 175, 80, 0.9);
  color: white;
}

.status-in-progress {
  background: rgba(255, 193, 7, 0.9);
  color: #5d4037;
}

.status-pending {
  background: rgba(158, 158, 158, 0.9);
  color: white;
}

.visita-body {
  padding: 25px;
}

.info-item {
  display: flex;
  align-items: flex-start;
  margin-bottom: 20px;
  padding: 15px;
  background: rgba(139, 195, 74, 0.05);
  border-radius: 10px;
  border-left: 4px solid #4caf50;
  transition: all 0.3s ease;
}

.info-item:hover {
  background: rgba(139, 195, 74, 0.1);
  transform: translateX(5px);
}

.info-item.full-width {
  border-left-color: #ff9800;
}

.info-icon {
  font-size: 1.3rem;
  color: #4caf50;
  margin-right: 15px;
  padding: 8px;
  background: rgba(76, 175, 80, 0.1);
  border-radius: 8px;
  min-width: 45px;
  text-align: center;
}

.info-item.full-width .info-icon {
  color: #ff9800;
  background: rgba(255, 152, 0, 0.1);
}

.info-content label {
  font-weight: 600;
  color: #2e7d32;
  font-size: 0.85rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 5px;
  display: block;
}

.info-value {
  font-weight: 500;
  color: #37474f;
  margin: 0;
  font-size: 1rem;
}

.visita-footer {
  background: rgba(139, 195, 74, 0.08);
  border-top: 1px solid rgba(139, 195, 74, 0.2);
  padding: 15px 25px;
  font-size: 0.85rem;
}

.visita-id {
  background: rgba(76, 175, 80, 0.1);
  padding: 4px 12px;
  border-radius: 12px;
  color: #2e7d32;
  font-weight: 600;
  font-size: 0.8rem;
}

/* ESTILOS MEJORADOS PARA EL BOTÓN */
.btn-palma {
  background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
  color: white;
  border: none;
  border-radius: 10px;
  padding: 12px 25px;
  font-weight: 600;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
}

.btn-palma:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
  background: linear-gradient(135deg, #43a047 0%, #1b5e20 100%);
  color: white;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .visita-header {
    flex-direction: column;
    text-align: center;
    gap: 15px;
  }
  
  .visita-status {
    align-self: center;
  }
  
  .info-item {
    flex-direction: column;
    text-align: center;
  }
  
  .info-icon {
    margin-right: 0;
    margin-bottom: 10px;
  }
  
  .visita-footer {
    text-align: center;
  }
  
  .visita-footer .d-flex {
    flex-direction: column;
    gap: 10px;
  }
}

/* ANIMACIONES */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.visita-info-card {
  animation: fadeInUp 0.6s ease-out;
}

/* MEJORAS PARA EL TÍTULO PRINCIPAL */
.offline-title {
  background: linear-gradient(135deg, #2e7d32, #4caf50);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-weight: 700;
  font-size: 2rem;
  text-align: center;
  margin-bottom: 2rem;
  position: relative;
}

.offline-title::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 4px;
  background: linear-gradient(135deg, #4caf50, #2e7d32);
  border-radius: 2px;}
</style>
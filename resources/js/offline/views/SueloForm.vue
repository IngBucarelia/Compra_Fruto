<template>
  <div class="offline-container">
    <h2 class="offline-title">🌱 Suelo - Registros Previos </h2>

    <div class="row mb-4">
      <!-- Tarjeta: Áreas -->
      <div class="accordion mb-4" id="accordionArea">
            <div class="accordion-item">
              <h2 class="accordion-header" id="headingArea">
                <button
                  style="background-color: darkseagreen !important; color: aliceblue"
                  class="accordion-button"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#collapseArea"
                  aria-controls="collapseArea"
                >
                  📍 Información del Área(s) guardada localmente
                </button>
              </h2>
              <div
                id="collapseArea"
                class="accordion-collapse collapse show"
                aria-labelledby="headingArea"
                data-bs-parent="#accordionArea"
              >
                <div
                  class="accordion-body"
                  style="background-color: rgb(209, 241, 209) !important; color: rgb(31, 32, 34)"
                >
                  <!-- ⭐⭐ INFORMACIÓN GENERAL DE LA FINCA ⭐⭐ -->
                  <div v-if="areasInfo.length > 0" class="mb-4">
                    <div class="card shadow-sm border-success">
                      <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                          <i class="fas fa-chart-bar me-2"></i>
                          Información General de la Finca
                        </h5>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="info-box-general mb-3">
                              <label class="fw-bold">
                                <i class="fas fa-ruler-combined me-2"></i>
                                Área Total Finca:
                              </label>
                              <div class="info-value">
                                {{ getInformacionGeneral('area_total_finca_hectareas') }} Ha
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="info-box-general mb-3">
                              <label class="fw-bold">
                                <i class="fas fa-tree me-2"></i>
                                Total de Palmas:
                              </label>
                              <div class="info-value">
                                {{ getInformacionGeneral('numero_palmas_total_finca') }}
                                <small class="text-muted ms-2">(incluye Orden Plantis)</small>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="info-box-general mb-3">
                              <label class="fw-bold">
                                <i class="fas fa-sync-alt me-2"></i>
                                Ciclos de Cosecha:
                              </label>
                              <div class="info-value">
                                {{ getInformacionGeneral('ciclos_cosecha') }}
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="info-box-general mb-3">
                              <label class="fw-bold">
                                <i class="fas fa-weight-hanging me-2"></i>
                                Producción Total:
                              </label>
                              <div class="info-value">
                                {{ getInformacionGeneral('produccion_toneladas_por_mes') }} Ton/Mes
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Desglose del total de palmas -->
                        <div class="row mt-3 bg-light p-3 rounded">
                          <div class="col-12">
                            <h6 class="mb-2">
                              <i class="fas fa-list-ol me-2"></i>
                              Desglose del Total de Palmas
                            </h6>
                            <div class="row">
                              <div class="col-md-4">
                                <small class="d-block">
                                  <span class="badge bg-info me-2">🌱</span>
                                  <strong>Desarrollo:</strong> {{ calcularTotalDesarrollo }}
                                </small>
                              </div>
                              <div class="col-md-4">
                                <small class="d-block">
                                  <span class="badge bg-success me-2">🌳</span>
                                  <strong>Producción:</strong> {{ calcularTotalProduccionPalmas }}
                                </small>
                              </div>
                              <div class="col-md-4">
                                <small class="d-block">
                                  <span class="badge bg-warning me-2">📋</span>
                                  <strong>Orden Plantis:</strong> {{ calcularTotalOrdenPlantis }}
                                </small>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- ⭐⭐ ÁREAS INDIVIDUALES ⭐⭐ -->
                  <div v-if="areasInfo.length > 0">
                    <h5 class="mb-3 mt-4">
                      <i class="fas fa-map-marked-alt me-2"></i>
                      Áreas Individuales ({{ areasInfo.length }})
                    </h5>
                    
                    <div class="row">
                      <div
                        v-for="(area, index) in areasInfo"
                        :key="area.local_id"
                        class="col-md-6 mb-3"
                      >
                        <div class="card h-100 shadow-sm border-primary">
                          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <div>
                              <h6 class="mb-0">
                                <i class="fas fa-seedling me-2"></i>
                                Área #{{ index + 1 }}
                              </h6>
                              <small class="opacity-75">
                                ID: {{ area.local_id?.substring(0, 8) || 'N/A' }}
                              </small>
                            </div>
                            <span class="badge" :class="getEstadoBadgeClass(area.estado)">
                              {{ area.estado === 'produccion' ? 'Producción' : 'Desarrollo' }}
                            </span>
                          </div>
                          
                          <div class="card-body">
                            <!-- Información básica -->
                            <div class="row mb-2">
                              <div class="col-6">
                                <small class="text-muted d-block">Variedad</small>
                                <strong>{{ area.variedad || 'N/A' }}</strong>
                              </div>
                              <div class="col-6">
                                <small class="text-muted d-block">Material</small>
                                <strong>{{ area.material || 'N/A' }}</strong>
                              </div>
                            </div>
                            
                            <div class="row mb-3">
                              <div class="col-12">
                                <small class="text-muted d-block">Año de Siembra</small>
                                <strong>{{ area.anio_siembra || 'N/A' }}</strong>
                              </div>
                            </div>
                            
                            <!-- Información específica según estado -->
                            <div v-if="area.estado === 'desarrollo'" class="bg-info bg-opacity-10 p-2 rounded mb-2">
                              <h6 class="text-info mb-2">
                                <i class="fas fa-seedling me-2"></i>
                                Información de Desarrollo
                              </h6>
                              <div class="row">
                                <div class="col-6">
                                  <small class="text-muted d-block">Área (Ha)</small>
                                  <strong>{{ area.area_palmas_desarrollo_hectareas || '0.00' }}</strong>
                                </div>
                                <div class="col-6">
                                  <small class="text-muted d-block">N° Palmas</small>
                                  <strong>{{ area.numero_palmas_desarrollo || '0' }}</strong>
                                </div>
                              </div>
                            </div>
                            
                            <div v-if="area.estado === 'produccion'" class="bg-success bg-opacity-10 p-2 rounded mb-2">
                              <h6 class="text-success mb-2">
                                <i class="fas fa-tree me-2"></i>
                                Información de Producción
                              </h6>
                              <div class="row">
                                <div class="col-6">
                                  <small class="text-muted d-block">Área (Ha)</small>
                                  <strong>{{ area.area_palmas_produccion_hectareas || '0.00' }}</strong>
                                </div>
                                <div class="col-6">
                                  <small class="text-muted d-block">N° Palmas</small>
                                  <strong>{{ area.numero_palmas_produccion || '0' }}</strong>
                                </div>
                              </div>
                            </div>
                            
                            <!-- Información de Orden Plantis -->
                            <div v-if="area.aplica_orden_plantis" class="bg-warning bg-opacity-10 p-2 rounded">
                              <h6 class="text-warning mb-2">
                                <i class="fas fa-clipboard-check me-2"></i>
                                Orden Plantis
                              </h6>
                              <div class="row">
                                <div class="col-6">
                                  <small class="text-muted d-block">N° Orden</small>
                                  <strong>{{ area.orden_plantis_numero || 'N/A' }}</strong>
                                </div>
                                <div class="col-6">
                                  <small class="text-muted d-block">N° Plantas</small>
                                  <strong>{{ area.numero_plantas_orden_plantis || '0' }}</strong>
                                </div>
                              </div>
                              <div class="row mt-1">
                                <div class="col-12">
                                  <small class="text-muted d-block">Estado</small>
                                  <span class="badge" :class="getOrdenPlantisBadgeClass(area.estado_oren_plantis)">
                                    {{ area.estado_oren_plantis === 'produccion' ? 'Producción' : 'Desarrollo' }}
                                  </span>
                                </div>
                              </div>
                            </div>
                            
                            <!-- Estado si no aplica Orden Plantis -->
                            <div v-else class="text-center mt-2">
                              <small class="text-muted">
                                <i class="fas fa-times-circle me-1"></i>
                                No aplica Orden Plantis
                              </small>
                            </div>
                          </div>
                          
                          <div class="card-footer bg-transparent">
                            <small class="text-muted">
                              <i class="fas fa-calendar me-1"></i>
                              {{ formatDate(area.fecha_guardado) }}
                            </small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <p v-else class="text-muted">No se ha registrado área para esta visita.</p>
                </div>
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
        <select v-model="form.tipo_suelo" class="form-control"  @change="toggleOtroTipoSuelo">
          <option value="">Seleccione</option>
          <option value="arenoso">Arenoso</option>
          <option value="arcilloso">Arcilloso</option>
          <option value="franco">Franco</option>
          <option value="franco arcilloso">Franco arcilloso</option>
          <option value="franco arenoso">Franco arenoso</option>
          <option value="otro">Otro</option>
        </select>
        
        <div v-if="mostrarCampoOtroTipoSuelo" class="mt-2">
          <input 
            type="text" 
            v-model="form.otro_tipo_suelo" 
            class="form-control" 
            placeholder="Ingrese el tipo de suelo"
            @input="actualizarNombreTipoSuelo"
            required
          >
          <small class="text-muted">Escriba el tipo de suelo que no aparece en la lista</small>
        </div>
      </div>

      <button type="submit" class="btn btn-primary">💾 Guardar Suelo</button>
    </form>
    <button type="button" class="btn btn-success" @click="irALaboresCultivo">
      ➡️ Ir a Labores de Cultivo
    </button>
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
        tipo_suelo: '',
        otro_tipo_suelo: ''
      },
      mostrarCampoOtroTipoSuelo: false,
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
    await this.cargarDatosSuelo();
    // Eventos para detectar cambios en la conexión
    window.addEventListener('online', this.updateOnlineStatus);
    window.addEventListener('offline', this.updateOnlineStatus);
  },
  beforeUnmount() {
    window.removeEventListener('online', this.updateOnlineStatus);
    window.removeEventListener('offline', this.updateOnlineStatus);
  },
  methods: {
     getOrdenPlantisBadgeClass(estado) {
    return estado === 'produccion' ? 'bg-success' : 'bg-info';
  },
    getEstadoBadgeClass(estado) {
    return estado === 'produccion' ? 'bg-success' : 'bg-info';
  },
    getInformacionGeneral(campo) {
    if (this.areasInfo.length > 0) {
      return this.areasInfo[0][campo] || 'N/A';
    }
    return 'N/A';
  },
  
  // ⭐⭐ CALCULAR TOTALES ⭐⭐
  calcularTotalDesarrollo() {
    return this.areasInfo.reduce((total, area) => {
      return total + (parseInt(area.numero_palmas_desarrollo) || 0);
    }, 0);
  },
  
  calcularTotalProduccionPalmas() {
    return this.areasInfo.reduce((total, area) => {
      return total + (parseInt(area.numero_palmas_produccion) || 0);
    }, 0);
  },
  
  calcularTotalOrdenPlantis() {
    return this.areasInfo.reduce((total, area) => {
      if (area.aplica_orden_plantis) {
        return total + (parseInt(area.numero_plantas_orden_plantis) || 0);
      }
      return total;
    }, 0);
  },

    toggleOtroTipoSuelo() {
    if (this.form.tipo_suelo === 'otro') {
      this.mostrarCampoOtroTipoSuelo = true;
      this.form.otro_tipo_suelo = '';
      this.form.tipo_suelo = ''; // Limpiar el select para forzar que use el campo "otro"
    } else {
      this.mostrarCampoOtroTipoSuelo = false;
      this.form.otro_tipo_suelo = '';
    }
  },
  
  // ⭐⭐ MÉTODO PARA ACTUALIZAR EL NOMBRE DEL TIPO DE SUELO ⭐⭐
  actualizarNombreTipoSuelo() {
    if (this.form.otro_tipo_suelo.trim() !== '') {
      // Cuando se escribe en "otro", automáticamente se asigna como tipo de suelo
      this.form.tipo_suelo = this.form.otro_tipo_suelo;
    } else {
      this.form.tipo_suelo = '';
    }
  },
  
  // ⭐⭐ MÉTODO MODIFICADO PARA GUARDAR ⭐⭐
  async guardar() {
    try {
      // Validar que si seleccionó "otro", haya ingresado un valor
      if (this.mostrarCampoOtroTipoSuelo && !this.form.otro_tipo_suelo.trim()) {
        alert('Por favor ingrese el tipo de suelo en el campo "Otro"');
        return;
      }
      
      // Crear objeto de datos limpio
      const data = { 
        analisis_foliar: this.form.analisis_foliar,
        alanalisis_suelo: this.form.alanalisis_suelo,
        tipo_suelo: this.form.tipo_suelo, // Esto puede ser el valor del select o del campo "otro"
        visita_id: this.visitaId,
        created_at: new Date().toISOString()
      };
      
      // Si se usó "otro", agregar ese campo también para referencia
      if (this.mostrarCampoOtroTipoSuelo && this.form.otro_tipo_suelo) {
        data.otro_tipo_suelo = this.form.otro_tipo_suelo;
        data.es_tipo_suelo_personalizado = true;
      }
      
      console.log('Guardando datos de suelo:', data);
      await saveFormData('suelo', data);
      
      // Limpiar formulario después de guardar
      this.form = {
        analisis_foliar: '',
        alanalisis_suelo: '',
        tipo_suelo: '',
        otro_tipo_suelo: ''
      };
      this.mostrarCampoOtroTipoSuelo = false;
      
      alert('✅ Datos de suelo guardados correctamente');
      
    } catch (error) {
      console.error('Error al guardar:', error);
      alert('Error al guardar los datos de suelo: ' + error.message);
    }
  },
  
  // ⭐⭐ MÉTODO PARA CARGAR DATOS EXISTENTES (si editas) ⭐⭐
  async cargarDatosSuelo() {
    try {
      const datosSuelo = await getFormDataByVisita('suelo', this.visitaId);
      if (datosSuelo && !Array.isArray(datosSuelo)) {
        // Si hay datos guardados, cargarlos en el formulario
        this.form.analisis_foliar = datosSuelo.analisis_foliar || '';
        this.form.alanalisis_suelo = datosSuelo.alanalisis_suelo || '';
        
        // Verificar si es un tipo de suelo personalizado
        if (datosSuelo.es_tipo_suelo_personalizado && datosSuelo.otro_tipo_suelo) {
          this.form.tipo_suelo = 'otro';
          this.form.otro_tipo_suelo = datosSuelo.otro_tipo_suelo;
          this.mostrarCampoOtroTipoSuelo = true;
        } else {
          this.form.tipo_suelo = datosSuelo.tipo_suelo || '';
          this.mostrarCampoOtroTipoSuelo = false;
        }
      }
    } catch (error) {
      console.error('Error cargando datos de suelo:', error);
    }
  },
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
/* Estilos para el campo "Otro" */
.form-control {
  margin-bottom: 10px;
}

.mt-2 {
  margin-top: 10px;
  padding: 10px;
  background-color: #f8f9fa;
  border-radius: 5px;
  border-left: 3px solid #ffc107;
}

.text-muted {
  font-size: 0.85rem;
  color: #6c757d;
  display: block;
  margin-top: 5px;
}

/* Estilo para el campo de texto "Otro" */
input[type="text"].form-control {
  border: 1px solid #ced4da;
  border-radius: 4px;
  padding: 8px 12px;
  transition: border-color 0.15s ease-in-out;
}

input[type="text"].form-control:focus {
  border-color: #80bdff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Estilo para el select */
select.form-control {
  cursor: pointer;
}

/* Estilo para validación */
input:invalid {
  border-color: #dc3545;
}

input:valid {
  border-color: #28a745;
}

/* Animación suave para mostrar/ocultar */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s, transform 0.3s;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
<template>
  <div class="container">
    <div v-if="tieneAreasHibridas">
      <div class="offline-container offline-form-container">
        <h2 class="offline-title">🌱 Polinización - Registros Previos</h2>

        <!-- Sección de información previa -->
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
                <label>🌬️ Nombre de ANA:</label>
                <input type="text" v-model="formPol.nombre_ana" class="form-control" required min="0">
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
    },
    tieneAreasHibridas() {
      return this.areasInfo.some(area => area.variedad && area.variedad.toLowerCase() === 'hibrido');
    }
  },
  methods: {
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
  
  // ⭐⭐ CLASES PARA BADGES ⭐⭐
  getEstadoBadgeClass(estado) {
    return estado === 'produccion' ? 'bg-success' : 'bg-info';
  },
  
  getOrdenPlantisBadgeClass(estado) {
    return estado === 'produccion' ? 'bg-success' : 'bg-info';
  },
  
  // ⭐⭐ FORMATEAR FECHA ⭐⭐
  formatDate(dateString) {
    if (!dateString) return 'Fecha no disponible';
    try {
      const date = new Date(dateString);
      return date.toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    } catch (error) {
      return dateString;
    }
  },
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
        // Verificar si hay áreas híbridas (acepta con o sin tilde, mayúsculas, etc.)
        this.tieneAreasHibridas = this.areasInfo.some(area => {
          if (!area.material) return false;
          const mat = area.material.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, ''); // elimina tildes
          return mat.includes('hibrido'); // busca sin importar tilde o mayúsculas
        });

        
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
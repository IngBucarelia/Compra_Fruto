<template>
<div class="container">
  <div class="offline-container offline-form-container">
    <h2 class="offline-title">🪩 Sanidad - Registros Previos</h2>

    <!-- Sección de información previa -->
    <div class="row mb-4">
      <!-- Tarjeta: Áreas -->
      <!-- REEMPLAZA el contenido del accordion con esto: -->
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
    </div>



  <h2>Registro de Sanidad (Modo Offline)</h2>

    <form @submit.prevent="guardar">
      <!-- Enfermedades -->
      <h3>Enfermedades</h3>
      <div v-for="(enf, index) in dynamicEnfermedades" :key="enf.id" class="mb-3 p-3 border rounded">
        <button type="button" class="btn btn-danger btn-sm float-end" @click="removeEnfermedad(index)">Eliminar</button>
        
        <div class="form-group mb-2">
          <label>Nombre de la enfermedad:</label>
          <select v-model="enf.nombre" class="form-select">
            <option value="">-- Seleccione --</option>
            <option value="Pudrición de cogollo (pc)">Pudrición de cogollo (pc)</option>
            <option value="Pestalotiopsis">Pestalotiopsis</option>
            <option value="Pudrición basal">Pudrición basal</option>
            <option value="Budrición de estipite">Budrición de estipite</option>
            <option value="Pudrición de racimos">Pudrición de racimos</option>
            <option value="Racimos malogros">Racimos malogros</option>
          </select>
        </div>

        <div class="form-group mb-2">
          <label>Estado (% afectación):</label>
          <input type="number" min="0" max="100" v-model="enf.estado" class="form-control" placeholder="Porcentaje afectado" />
        </div>
      </div>
      <button type="button" class="btn btn-secondary mb-3" @click="addEnfermedad()">+ Agregar Enfermedad</button>

      <!-- Plagas - CORREGIDO CON INSTAR -->
      <h3>Plagas</h3>
      <div v-for="(plaga, index) in dynamicPlagas" :key="plaga.id" class="mb-3 p-3 border rounded">
        <button type="button" class="btn btn-danger btn-sm float-end" @click="removePlaga(index)">Eliminar</button>
        
        <div class="form-group mb-2">
          <label>Nombre de la Plaga:</label>
          <select v-model="plaga.nombre" class="form-control" @change="resetarInstar(index)">
            <option value="">Seleccione plaga</option>
            <option value="Leptopharsa gibbicarina">Leptopharsa gibbicarina</option>
            <option value="Stenoma cecropia">Stenoma cecropia</option>
            <option value="Leucothyreus femaratus">Leucothyreus femaratus</option>
            <option value="Brassolis sophorae">Brassolis sophorae</option>
            <option value="Euprosterna eleasa">Euprosterna eleasa</option>
            <option value="Sibine fusca">Sibine fusca</option>
            <option value="Opsiphanes cassina">Opsiphanes cassina</option>
            <option value="Automeris liberia">Automeris liberia</option>
            <option value="Dirphia gragatus">Dirphia gragatus</option>
            <option value="Cephaloleia vagelineata">Cephaloleia vagelineata</option>
            <option value="Demotispa neivai">Demotispa neivai</option>
            <option value="Loxotoma elegans">Loxotoma elegans</option>
            <option value="Hispoleptis subfasciata">Hispoleptis subfasciata</option>
            <option value="Haplaxius crudus">Haplaxius crudus</option>
            <option value="Rhynchophorus palmarum">Rhynchophorus palmarum</option>
            <option value="Strategus aloeus">Strategus aloeus</option>
            <option value="Sagalassa valida">Sagalassa valida</option>
          </select>
        </div>

        <div class="form-group mb-2">
          <label>Estado:</label>
          <select v-model="plaga.estado" class="form-control" @change="manejarCambioEstado(index)">
            <option value="">Seleccione estado</option>
            <option value="Adulto">Adulto</option>
            <option value="Larva">Larva</option>
            <option value="Huevo">Huevo</option>
          </select>
        </div>

        <!-- Campo Instar - Solo visible cuando estado es "Larva" -->
        <div v-if="plaga.estado === 'Larva'" class="form-group mb-2 instar-container">
          <label>Instar:</label>
          <select v-model="plaga.instar" class="form-control">
            <option value="">Seleccione instar</option>
            <option value="Instar I">Instar I</option>
            <option value="Instar II">Instar II</option>
            <option value="Instar III">Instar III</option>
            <option value="Instar IV">Instar IV</option>
            <option value="Instar V">Instar V</option>
            <option value="Instar VI">Instar VI</option>
            <option value="Instar VII">Instar VII</option>
            <option value="Instar VIII">Instar VIII</option>
            <option value="Instar IX">Instar IX</option>
          </select>
        </div>
      </div>
      <button type="button" class="btn btn-secondary mb-3" @click="addPlaga()">+ Agregar Plaga</button>

      <!-- Campos estáticos -->
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" v-model="form.censo_enfermedades" id="censoEnfermedadesCheck">
        <label class="form-check-label" for="censoEnfermedadesCheck">
          Realizó censo de enfermedades
        </label>
      </div>

      <div class="form-group mb-3" v-if="form.censo_enfermedades">
        <label>Ciclos de lectura de enfermedades:</label>
        <input type="text" v-model="form.ciclos_lectura_enfermedades" class="form-control" />
      </div>

      <div class="form-group mb-3">
        <label>Ciclos de lectura de plagas:</label>
        <input type="text" v-model="form.ciclos_lectura_plagas" class="form-control" />
      </div>

      <!-- Trampas -->
      <hr>
      <h3>Trampas de Palmarum</h3>
      <div v-for="(trampa, index) in dynamicTraps" :key="trampa.id" class="trampa-group mb-3 p-3 border rounded">
        <button type="button" class="btn btn-danger btn-sm float-end" @click="removeTrampa(index)">Eliminar</button>
        <div class="form-group mb-2">
          <label>Ciclos:</label>
          <input type="text" v-model="trampa.ciclos" class="form-control form-control-sm" />
        </div>
        <div class="form-group mb-2">
          <label>Machos capturados:</label>
          <input type="number" v-model="trampa.machos" class="form-control form-control-sm" min="0" />
        </div>
        <div class="form-group">
          <label>Hembras capturadas:</label>
          <input type="number" v-model="trampa.hembras" class="form-control form-control-sm" min="0" />
        </div>
      </div>
      <button type="button" class="btn btn-secondary mb-3" @click="addTrampa()">+ Agregar Trampa</button>

      <!-- Otros -->
      <div class="form-group mb-3">
        <label>Otros (descripción):</label>
        <input type="text" v-model="form.otros" class="form-control" />
      </div>

      <div class="form-group mb-3">
        <label>Observaciones:</label>
        <textarea v-model="form.observaciones" class="form-control" rows="3"></textarea>
      </div>

      <!-- Botones -->
      <div class="button-group mt-4">
        <button type="submit" class="btn btn-primary">Guardar Sanidad</button>
        <button type="button" class="btn btn-success" @click="irASuelo">Ir a Estudio de Suelo</button>
        <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
      </div>
    </form>

    <!-- Sanidades guardadas localmente - ACTUALIZADO -->
    <div class="mt-4 p-3 bg-light rounded">
      <h4 class="mb-3">Sanidades Guardadas Localmente</h4>
      <div v-if="localSanidades.length > 0">
        <div v-for="sanidad in localSanidades" :key="sanidad.id" class="card mb-3">
          <div class="card-body">
            <!-- Enfermedades -->
            <div v-if="sanidad.enfermedades && sanidad.enfermedades.length > 0">
              <h6>Enfermedades:</h6>
              <ul class="list-group list-group-flush mb-2">
                <li v-for="(enf, eIndex) in sanidad.enfermedades" :key="eIndex" class="list-group-item">
                  <strong>Nombre:</strong> {{ enf.nombre || '-' }},
                  <strong>Estado (%):</strong> {{ enf.estado || '-' }}
                </li>
              </ul>
            </div>
            <p v-else class="text-muted">No se registraron enfermedades.</p>

            <!-- Plagas - ACTUALIZADO PARA MOSTRAR INSTAR -->
            <div v-if="sanidad.plagas && sanidad.plagas.length > 0">
              <h6>Plagas:</h6>
              <ul class="list-group list-group-flush mb-2">
                <li v-for="(pla, pIndex) in sanidad.plagas" :key="pIndex" class="list-group-item">
                  <strong>Nombre:</strong> {{ pla.nombre || '-' }},
                  <strong>Estado:</strong> {{ pla.estado || '-' }}
                  <span v-if="pla.estado === 'Larva' && pla.instar">
                    - <strong>Instar:</strong> {{ pla.instar }}
                  </span>
                </li>
              </ul>
            </div>
            <p v-else class="text-muted">No se registraron plagas.</p>

            <!-- Campos adicionales -->
            <ul class="list-group list-group-flush">
              <li v-if="sanidad.censo_enfermedades"><strong>Censo de enfermedades:</strong> Sí</li>
              <li v-if="sanidad.ciclos_lectura_enfermedades"><strong>Ciclos lectura enfermedades:</strong> {{ sanidad.ciclos_lectura_enfermedades }}</li>
              <li v-if="sanidad.ciclos_lectura_plagas"><strong>Ciclos lectura plagas:</strong> {{ sanidad.ciclos_lectura_plagas }}</li>
              <li v-if="sanidad.otros"><strong>Otros:</strong> {{ sanidad.otros }}</li>
              <li v-if="sanidad.observaciones"><strong>Observaciones:</strong> {{ sanidad.observaciones }}</li>
            </ul>

            <!-- Trampas -->
            <div v-if="sanidad.trampas && sanidad.trampas.length > 0" class="mt-3">
              <h6>Trampas de Palmarum:</h6>
              <ul class="list-group list-group-flush">
                <li v-for="(trampa, tIndex) in sanidad.trampas" :key="tIndex" class="list-group-item">
                  Ciclos: {{ trampa.ciclos || '-' }}, Machos: {{ trampa.machos }}, Hembras: {{ trampa.hembras }}
                </li>
              </ul>
            </div>
            <p v-else class="text-muted mt-2">No se registraron trampas de Palmarum.</p>
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
        censo_enfermedades: false,
        ciclos_lectura_enfermedades: '',
        ciclos_lectura_plagas: '',
        otros: '',
        observaciones: '',
      },
      dynamicEnfermedades: [],
      dynamicPlagas: [], // Array de plagas con estructura completa
      dynamicTraps: [],
      localSanidades: [],
      canSync: navigator.onLine,
      currentEnfermedadIndex: 0,
    }
  },
  computed: {
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

      if (this.dynamicEnfermedades.length === 0) {
        this.addEnfermedad();
      }
      
      if (this.dynamicTraps.length === 0) {
        this.addTrampa();
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
    // Métodos para manejar Instar en plagas
    manejarCambioEstado(index) {
      const plaga = this.dynamicPlagas[index];
      // Si el estado cambia y NO es "Larva", limpiar el instar
      if (plaga.estado !== 'Larva') {
        plaga.instar = '';
      }
    },
    
    resetarInstar(index) {
      // Si cambia la plaga, resetear el instar
      const plaga = this.dynamicPlagas[index];
      plaga.instar = '';
    },

    getInformacionGeneral(campo) {
      if (this.areasInfo.length > 0) {
        return this.areasInfo[0][campo] || 'N/A';
      }
      return 'N/A';
    },
    
    getEstadoBadgeClass(estado) {
      return estado === 'produccion' ? 'bg-success' : 'bg-info';
    },
    
    getOrdenPlantisBadgeClass(estado) {
      return estado === 'produccion' ? 'bg-success' : 'bg-info';
    },
    
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

    updateSyncStatus() {
      this.canSync = navigator.onLine;
    },

    irASuelo() {
      this.$router.push(`/suelo?visita_id=${this.visitaId}`);
    },

    addEnfermedad(defaultNombre = '', defaultEstado = '') {
      if (!Array.isArray(this.dynamicEnfermedades)) this.dynamicEnfermedades = [];
      this.dynamicEnfermedades.push({
        id: Date.now() + Math.random(),
        nombre: defaultNombre,
        estado: defaultEstado
      });
    },

    removeEnfermedad(index) {
      if (!Array.isArray(this.dynamicEnfermedades)) return;
      this.dynamicEnfermedades.splice(index, 1);
      if (this.dynamicEnfermedades.length === 0) this.addEnfermedad();
    },

    // Métodos para plagas - CORREGIDOS CON INSTAR
    addPlaga() {
      this.dynamicPlagas.push({
        id: Date.now() + Math.random(),
        nombre: '',
        estado: '',
        instar: ''
      });
    },

    removePlaga(index) {
      this.dynamicPlagas.splice(index, 1);
      if (this.dynamicPlagas.length === 0) this.addPlaga();
    },

    async guardar() {
      try {
        if (!this.visitaId) {
          throw new Error('No se encontró el ID de visita');
        }

        // Validar plagas: si estado es "Larva", debe tener instar
        const plagasConErrores = [];
        this.dynamicPlagas.forEach((plaga, index) => {
          if (plaga.estado === 'Larva' && !plaga.instar) {
            plagasConErrores.push(`Plaga #${index + 1}: seleccione un instar para la larva`);
          }
        });

        if (plagasConErrores.length > 0) {
          alert('Corrija los siguientes errores:\n' + plagasConErrores.join('\n'));
          return;
        }

        const formData = {
          ...this.form,
          visita_id: this.visitaId,
          id: uuidv4(),
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
          enfermedades: this.dynamicEnfermedades.filter(e => e.nombre),
          plagas: this.dynamicPlagas.filter(p => p.nombre), // Ahora incluye instar
          trampas: this.dynamicTraps.filter(t => t.ciclos || t.machos || t.hembras),
        };
        
        await saveFormData('sanidad', formData);
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
        console.log('Error cargando datos iniciales:', error);
      }
    },

    async loadLocalSanidades() {
      try {
        const allSanidades = await getAllDataFromStore('sanidad');
        this.localSanidades = Array.isArray(allSanidades) ?
          allSanidades.filter(item => item.visita_id == this.visitaId) : [];
      } catch (error) {
        console.log('Error cargando sanidades locales:', error);
      }
    },

    async loadExistingSanidadData() {
      try {
        const existingSanidad = this.localSanidades[0];
        if (!existingSanidad) return;

        // Llenar campos del formulario
        Object.keys(this.form).forEach(key => {
          if (existingSanidad[key] !== undefined) {
            this.form[key] = existingSanidad[key];
          }
        });

        // Enfermedades existentes
        if (existingSanidad.enfermedades && Array.isArray(existingSanidad.enfermedades)) {
          this.dynamicEnfermedades = existingSanidad.enfermedades.map((enf, idx) => ({
            id: Date.now() + idx,
            nombre: enf.nombre || '',
            estado: enf.estado || '',
          }));
        } else {
          this.dynamicEnfermedades = [];
          this.addEnfermedad();
        }

        // Plagas existentes - CON INSTAR
        if (existingSanidad.plagas && Array.isArray(existingSanidad.plagas)) {
          this.dynamicPlagas = existingSanidad.plagas.map((plaga, idx) => ({
            id: Date.now() + idx,
            nombre: plaga.nombre || '',
            estado: plaga.estado || '',
            instar: plaga.instar || '' // Cargar instar si existe
          }));
        } else {
          this.dynamicPlagas = [];
          this.addPlaga();
        }

        // Trampas existentes
        if (existingSanidad.trampas && Array.isArray(existingSanidad.trampas)) {
          this.dynamicTraps = existingSanidad.trampas.map((trampa, index) => ({
            id: index,
            ciclos: trampa.ciclos,
            machos: trampa.machos,
            hembras: trampa.hembras,
          }));
        } else {
          this.dynamicTraps = [{ id: 0, ciclos: '', machos: null, hembras: null }];
        }

      } catch (error) {
        console.error('Error cargando datos existentes:', error);
      }
    },

    // Métodos para trampas
    addTrampa() {
      this.dynamicTraps.push({
        id: this.dynamicTraps.length,
        ciclos: '',
        machos: null,
        hembras: null,
      });
    },

    removeTrampa(index) {
      this.dynamicTraps.splice(index, 1);
      if (this.dynamicTraps.length === 0) {
        this.addTrampa();
      }
    },

    // Método para sincronizar
    async sincronizar() {
      if (!this.canSync) {
        alert('No hay conexión a internet para sincronizar');
        return;
      }
      alert('Función de sincronización pendiente de implementar');
    }
  }
};
</script>

<style scoped>
@import '../styles/offline.css';

.container.offline-form-container {
  background-color: rgba(129, 165, 114, 0.929);
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

/* Estilos para el campo Instar */
.instar-container {
  background-color: #f8f9fa;
  padding: 10px;
  border-radius: 5px;
  border-left: 4px solid #007bff;
  animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
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
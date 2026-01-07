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
    <!-- ===========================
     CAMPOS GENERALES DE FINCA
=============================== -->
    <div class="card mb-4 p-3 bg-white shadow-sm">
    <h3>Información General de la Finca</h3>

    <div class="row mt-3">
      <div class="col-md-6">
        <div class="form-group">
          <label>Área Total Finca (Ha)</label>
          <input type="number" class="form-control" 
                :value="calcularAreaTotalFinca" 
                readonly>
          <small class="text-muted">Suma de todas las áreas (Desarrollo + Producción)</small>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label>N° Total de Palmas</label>
          <input type="number" class="form-control"
                :value="calcularTotalPalmas"
                readonly>
          <small class="text-muted">Suma de todas las palmas (Desarrollo + Producción)</small>
        </div>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-md-6">
        <div class="form-group">
          <label>Ciclos de Cosecha</label>
          <!-- ⭐⭐ CAMBIO: Usar datosGenerales ⭐⭐ -->
          <input type="number" class="form-control" 
                 v-model="datosGenerales.ciclos_cosecha">
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label>Producción Total (Ton/Mes)</label>
          <!-- ⭐⭐ CAMBIO: Usar datosGenerales ⭐⭐ -->
          <input type="number" step="0.01"
                 class="form-control" 
                 v-model="datosGenerales.produccion_toneladas_por_mes"
                 >
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
                <select v-model="formArea.estado" class="form-control" required 
                        @change="actualizarHabilitacionEstado(index)">
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
                  required 
                  placeholder="Seleccione el año"
                />
              </div>
            </div>
            <!--  <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Área (m²)</label>
                <input type="number" step="0.01" v-model="formArea.area" class="form-control" required />
              </div>
            </div>  -->
          </div>

         

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Área Palmas Desarrollo (Ha)</label>
                <input type="number" step="0.01"
                        v-model="formArea.area_palmas_desarrollo_hectareas"
                        class="form-control"
                        :disabled="!formArea.habilitarDesarrollo"
                        @input="actualizarCalculos" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>N° Palmas Desarrollo</label>
                <input type="number"
                        v-model="formArea.numero_palmas_desarrollo"
                        class="form-control"
                        :disabled="!formArea.habilitarDesarrollo"
                        @input="actualizarCalculos" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>Área Palmas Producción (Ha)</label>
                <input type="number" step="0.01"
                        v-model="formArea.area_palmas_produccion_hectareas"
                        class="form-control"
                        :disabled="!formArea.habilitarProduccion" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label>N° Palmas Producción</label>
                <input type="number"
                      v-model="formArea.numero_palmas_produccion"
                      class="form-control"
                      :disabled="!formArea.habilitarProduccion" />
              </div>
            </div>
          </div>

          <div class="row">
          
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
                  <select v-model="formArea.estado_orden_plantis"> <!-- ← estado_orden_plantis, no estado_oren_plantis -->
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


    
    <div v-if="areasGuardadas.length > 0" class="mb-4">
  <h3>Información Guardada</h3>
  
  <!-- ⭐⭐ 1. TARJETA DE INFORMACIÓN GENERAL ⭐⭐ -->
  <div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">📊 Información General de la Finca Guardada</h4>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="info-box">
            <label><i class="fas fa-ruler-combined me-2"></i>Área Total Finca</label>
            <p class="info-value">{{ areasGuardadas[0]?.area_total_finca_hectareas || '0.00' }} Ha</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-box">
            <label><i class="fas fa-tree me-2"></i>Total de Palmas</label>
            <p class="info-value">{{ areasGuardadas[0]?.numero_palmas_total_finca || '0' }} palmas</p>
          </div>
        </div>
      </div>
      
      <div class="row mt-3">
        <div class="col-md-6">
          <div class="info-box">
            <label><i class="fas fa-sync-alt me-2"></i>Ciclos de Cosecha</label>
            <p class="info-value">{{ areasGuardadas[0]?.ciclos_cosecha || 'No especificado' }}</p>
          </div>
        </div>
        <div class="col-md-6">
          <div class="info-box">
            <label><i class="fas fa-weight-hanging me-2"></i>Producción Total</label>
            <p class="info-value">{{ areasGuardadas[0]?.produccion_toneladas_por_mes || '0.00' }} Ton/Mes</p>
          </div>
        </div>
      </div>
      
      <!-- Botón para editar información general -->
      <div class="mt-3 text-end">
        <button @click="editarInformacionGeneral" class="btn btn-sm btn-warning">
          <i class="fas fa-edit me-1"></i> Editar Información General
        </button>
      </div>
    </div>
  </div>
  
  <!-- ⭐⭐ 2. TARJETAS DE ÁREAS INDIVIDUALES ⭐⭐ -->
  <h4 class="mt-4 mb-3">🌿 Áreas Individuales Guardadas</h4>
  
  <div v-for="(area, index) in areasGuardadas" :key="area.local_id" class="card mb-3 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-light">
      <div>
        <span class="fw-bold">Área #{{ index + 1 }}</span>
        <small class="text-muted ms-2">
          <i class="fas fa-seedling me-1"></i>{{ area.variedad || 'Sin variedad' }}
        </small>
      </div>
      <button @click="eliminarArea(area.local_id)" class="btn btn-sm btn-danger">
        <i class="fas fa-trash me-1"></i> Eliminar
      </button>
    </div>
    
    <div class="card-body">
      <div class="row">
        <!-- Columna Izquierda -->
        <div class="col-md-6">
          <div class="info-item mb-2">
            <strong><i class="fas fa-leaf me-2"></i>Material:</strong>
            <span class="ms-2">{{ area.material || 'No especificado' }}</span>
          </div>
          
          <div class="info-item mb-2">
            <strong><i class="fas fa-chart-line me-2"></i>Estado:</strong>
            <span class="ms-2 badge" :class="area.estado === 'produccion' ? 'bg-success' : 'bg-info'">
              {{ area.estado === 'produccion' ? 'Producción' : 'Desarrollo' }}
            </span>
          </div>
          
          <div class="info-item mb-2">
            <strong><i class="fas fa-calendar me-2"></i>Año Siembra:</strong>
            <span class="ms-2">{{ area.anio_siembra || 'No especificado' }}</span>
          </div>
          
          <div v-if="area.estado === 'desarrollo'" class="info-item mb-2">
            <strong><i class="fas fa-seedling me-2"></i>Área Desarrollo:</strong>
            <span class="ms-2">{{ area.area_palmas_desarrollo_hectareas || '0.00' }} Ha</span>
          </div>
          
          <div v-if="area.estado === 'desarrollo'" class="info-item mb-2">
            <strong><i class="fas fa-trees me-2"></i>Palmas Desarrollo:</strong>
            <span class="ms-2">{{ area.numero_palmas_desarrollo || '0' }}</span>
          </div>
        </div>
        
        <!-- Columna Derecha -->
        <div class="col-md-6">
          <div v-if="area.estado === 'produccion'" class="info-item mb-2">
            <strong><i class="fas fa-seedling me-2"></i>Área Producción:</strong>
            <span class="ms-2">{{ area.area_palmas_produccion_hectareas || '0.00' }} Ha</span>
          </div>
          
          <div v-if="area.estado === 'produccion'" class="info-item mb-2">
            <strong><i class="fas fa-trees me-2"></i>Palmas Producción:</strong>
            <span class="ms-2">{{ area.numero_palmas_produccion || '0' }}</span>
          </div>
          
          <div v-if="area.aplica_orden_plantis" class="info-item mb-2">
            <strong><i class="fas fa-file-alt me-2"></i>Orden Plantis:</strong>
            <span class="ms-2">#{{ area.orden_plantis_numero || 'N/A' }}</span>
          </div>
          
          <div v-if="area.aplica_orden_plantis" class="info-item mb-2">
            <strong><i class="fas fa-clipboard-check me-2"></i>Estado Orden:</strong>
            <span class="ms-2">{{ area.estado_orden_plantis === 'produccion' ? 'Producción' : 'Desarrollo' }}</span>
          </div>
          
          <div v-if="area.aplica_orden_plantis" class="info-item mb-2">
            <strong><i class="fas fa-leaf me-2"></i>Plantas Orden:</strong>
            <span class="ms-2">{{ area.numero_plantas_orden_plantis || '0' }}</span>
          </div>
        </div>
      </div>
      
      <!-- Resumen del área -->
      <div class="mt-3 pt-3 border-top">
        <div class="row">
          <div class="col-md-4">
            <small class="text-muted">
              <i class="fas fa-hashtag me-1"></i>ID: {{ area.local_id?.substring(0, 8) || 'N/A' }}
            </small>
          </div>
          <div class="col-md-4">
            <small class="text-muted">
              <i class="fas fa-clock me-1"></i>
              {{ area.fecha_guardado }}
            </small>
          </div>
          <div class="col-md-4 text-end">
            <button @click="editarArea(area.local_id)" class="btn btn-sm btn-outline-primary">
              <i class="fas fa-edit me-1"></i> Editar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Resumen total -->
  <div class="card mt-3 bg-light">
    <div class="card-body text-center">
      <h5 class="mb-0">
        <i class="fas fa-clipboard-check me-2 text-success"></i>
        Total: {{ areasGuardadas.length }} {{ areasGuardadas.length === 1 ? 'área guardada' : 'áreas guardadas' }}
      </h5>
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
      datosGenerales: {
        ciclos_cosecha: '',
        produccion_toneladas_por_mes: ''
      }
      

    };
  },
  computed: {
    hayFormulariosValidos() {
    return this.formulariosAreas.some(form => 
      form.material && form.anio_siembra 
    );
  },

  calcularAreaTotalFinca() {
      return this.formulariosAreas.reduce((total, form) => {
        const areaDesarrollo = parseFloat(form.area_palmas_desarrollo_hectareas) || 0;
        const areaProduccion = parseFloat(form.area_palmas_produccion_hectareas) || 0;
        return total + areaDesarrollo + areaProduccion;
      }, 0).toFixed(2);
    },

  calcularAreaTotal() {
    return this.formulariosAreas.reduce((total, f) => {
      return total + (parseFloat(f.area) || 0);
    }, 0).toFixed(2);
  },

  calcularTotalPalmas() {
    return this.formulariosAreas.reduce((total, form) => {
      let totalEsteFormulario = 0;
      
      // 1. Sumar palmas de desarrollo
      const palmasDesarrollo = parseInt(form.numero_palmas_desarrollo) || 0;
      totalEsteFormulario += palmasDesarrollo;
      
      // 2. Sumar palmas de producción
      const palmasProduccion = parseInt(form.numero_palmas_produccion) || 0;
      totalEsteFormulario += palmasProduccion;
      
      // 3. Sumar plantas de Orden Plantis si aplica
      if (form.aplica_orden_plantis) {
        const plantasOrdenPlantis = parseInt(form.numero_plantas_orden_plantis) || 0;
        totalEsteFormulario += plantasOrdenPlantis;
        
        console.log(`Formulario #${this.formulariosAreas.indexOf(form) + 1}:`);
        console.log(`- Palmas desarrollo: ${palmasDesarrollo}`);
        console.log(`- Palmas producción: ${palmasProduccion}`);
        console.log(`- Plantas orden plantis: ${plantasOrdenPlantis}`);
        console.log(`- Total este formulario: ${totalEsteFormulario}`);
      }
      
      return total + totalEsteFormulario;
    }, 0);
  },

  calcularTotalProduccion() {
    return this.formulariosAreas.reduce((total, f) => {
      return total + (parseFloat(f.produccion_toneladas_por_mes) || 0);
    }, 0).toFixed(2);
  },
  calcularProduccionTotal() {
      return this.formulariosAreas.reduce((total, form) => {
        return total + (parseFloat(form.produccion_toneladas_por_mes) || 0);
      }, 0).toFixed(2);
    }
  },
  methods: {
    // MÉTODO PARA CARGAR INFORMACIÓN DE LA VISITA
    actualizarCalculos() {
      // Este método se llamará desde los @input de los campos relevantes
      // Los computed properties se actualizarán automáticamente
    }, 

    cargarDatosParaEditar() {
    if (this.areasGuardadas.length > 0) {
      // Cargar datos generales desde la primera área
      const primeraArea = this.areasGuardadas[0];
      
      // Actualizar datosGenerales
      this.datosGenerales = {
        ciclos_cosecha: primeraArea.ciclos_cosecha || '',
        produccion_toneladas_por_mes: primeraArea.produccion_toneladas_por_mes || ''
      };
      
      // Actualizar campos calculados (los computed se actualizan solos)
      console.log('Datos generales cargados para edición:', this.datosGenerales);
    }
  },
  
  // ⭐⭐ NUEVO: Editar información general ⭐⭐
  editarInformacionGeneral() {
    this.cargarDatosParaEditar();
    
    // Hacer scroll a la sección de edición
    const elemento = document.querySelector('.card.mb-4.p-3.bg-white.shadow-sm');
    if (elemento) {
      elemento.scrollIntoView({ behavior: 'smooth' });
    }
    
    // Mostrar mensaje
    alert('La información general se ha cargado para editar. Modifica los valores y guarda los cambios.');
  },
  
  // ⭐⭐ NUEVO: Editar área específica ⭐⭐
  async editarArea(localId) {
    try {
      // Buscar el área específica
      const areaAEditar = this.areasGuardadas.find(area => area.local_id === localId);
      
      if (!areaAEditar) {
        alert('Área no encontrada');
        return;
      }
      
      // Preguntar qué hacer
      const opcion = confirm(
        `¿Qué deseas hacer con el Área #${this.areasGuardadas.findIndex(a => a.local_id === localId) + 1}?\n\n` +
        'Aceptar: Cargar para editar\n' +
        'Cancelar: Eliminar'
      );
      
      if (opcion) {
        // Cargar en formulariosAreas para editar
        this.formulariosAreas = [areaAEditar];
        
        // Cargar datos generales
        this.cargarDatosParaEditar();
        
        // Eliminar de areasGuardadas
        this.areasGuardadas = this.areasGuardadas.filter(area => area.local_id !== localId);
        
        // Hacer scroll al formulario
        setTimeout(() => {
          const elemento = document.querySelector('.area-form-group');
          if (elemento) {
            elemento.scrollIntoView({ behavior: 'smooth' });
          }
        }, 100);
        
        alert('Área cargada para editar. Modifica los valores y guarda los cambios.');
      } else {
        // Eliminar directamente
        await this.eliminarArea(localId);
      }
    } catch (error) {
      console.error('Error editando área:', error);
      alert('Error al editar el área: ' + error.message);
    }
  },
  
  // Modificar el método guardarAreas para incluir fecha
  async guardarAreas() {
      try {
        const areasValidas = this.formulariosAreas.filter(form => {
          return form.material && form.material.trim() !== '' && form.anio_siembra;
        });

        if (areasValidas.length === 0) {
          alert('Complete al menos un formulario válido');
          return;
        }

        for (const form of areasValidas) {
          const local_id = form.local_id || this.generateUUID();
          
          // ⭐⭐ CALCULAR TOTAL DE PALMAS PARA ESTA ÁREA ESPECÍFICA ⭐⭐
          let totalPalmasEstaArea = 0;
          
          // 1. Palmas desarrollo
          totalPalmasEstaArea += parseInt(form.numero_palmas_desarrollo) || 0;
          
          // 2. Palmas producción
          totalPalmasEstaArea += parseInt(form.numero_palmas_produccion) || 0;
          
          // 3. Plantas Orden Plantis (si aplica)
          if (form.aplica_orden_plantis) {
            totalPalmasEstaArea += parseInt(form.numero_plantas_orden_plantis) || 0;
          }

          const areaData = {
            ...form,
            local_id,
            visita_id: this.visitaId,
            fecha_guardado: new Date().toISOString(),
            ciclos_cosecha: this.datosGenerales.ciclos_cosecha,
            produccion_toneladas_por_mes: this.datosGenerales.produccion_toneladas_por_mes,
            area_total_finca_hectareas: this.calcularAreaTotalFinca,
            numero_palmas_total_finca: this.calcularTotalPalmas, // ⭐⭐ TOTAL GENERAL DE TODA LA FINCA ⭐⭐
            // ⭐⭐ NUEVO: Guardar también el total específico de esta área ⭐⭐
            numero_palmas_esta_area: totalPalmasEstaArea,
          };
          
          // Convertir tipos
          if (this.datosGenerales.ciclos_cosecha) {
            areaData.ciclos_cosecha = parseInt(this.datosGenerales.ciclos_cosecha);
          }
          
          if (this.datosGenerales.produccion_toneladas_por_mes) {
            areaData.produccion_toneladas_por_mes = parseFloat(this.datosGenerales.produccion_toneladas_por_mes);
          }
          
          // Eliminar campos temporales
          delete areaData.habilitarProduccion;
          delete areaData.habilitarDesarrollo;
          delete areaData.mostrarCampoOtroMaterial;
          delete areaData.otroMaterial;
          
          console.log('Guardando área:', {
            area: areaData.material,
            palmasDesarrollo: parseInt(form.numero_palmas_desarrollo) || 0,
            palmasProduccion: parseInt(form.numero_palmas_produccion) || 0,
            plantasOrdenPlantis: form.aplica_orden_plantis ? (parseInt(form.numero_plantas_orden_plantis) || 0) : 0,
            totalEstaArea: totalPalmasEstaArea,
            totalGeneral: this.calcularTotalPalmas
          });
          
          await saveFormData('area', areaData);
        }

        await this.cargarAreasGuardadas();
        this.formulariosAreas = [this.nuevoFormularioArea()];
        
        // Mostrar resumen detallado
        console.log('=== RESUMEN DE GUARDADO ===');
        console.log('Área Total Finca:', this.calcularAreaTotalFinca, 'Ha');
        console.log('Total Palmas (incluye Orden Plantis):', this.calcularTotalPalmas);
        
        alert(`✅ ${areasValidas.length} áreas guardadas correctamente\n` +
              `🌴 Total de palmas: ${this.calcularTotalPalmas} (incluye plantas de Orden Plantis)`);
        
      } catch (error) {
        console.error('Error guardando áreas:', error);
        alert('Error al guardar: ' + error.message);
      }
    },
    debugFormulario() {
    if (this.formulariosAreas.length > 0) {
      console.log('Estructura del primer formulario:', this.formulariosAreas[0]);
      console.log('¿Tiene ciclos_cosecha?', 'ciclos_cosecha' in this.formulariosAreas[0]);
    }},

    actualizarHabilitacionEstado(index) {
      const form = this.formulariosAreas[index];

      if (form.estado === "produccion") {
        form.habilitarProduccion = true;
        form.habilitarDesarrollo = false;

        // Limpiar desarrollo
        form.area_palmas_desarrollo_hectareas = null;
        form.numero_palmas_desarrollo = null;
        
        // CORRECCIÓN: Habilitar campos de producción si aplica
        if (!form.produccion_toneladas_por_mes) {
          // Puedes inicializar con un valor por defecto si quieres
        }
      } else {
        form.habilitarProduccion = false;
        form.habilitarDesarrollo = true;

        // Limpiar producción
        form.area_palmas_produccion_hectareas = null;
        form.numero_palmas_produccion = null;
        form.produccion_toneladas_por_mes = null;
        form.ciclos_cosecha = null;
      }
      
      // Actualizar cálculos
      this.actualizarCalculos();
    },

  nuevoFormularioArea() {
      return {
        variedad: '',
        material: '',
        estado: 'desarrollo',
        anio_siembra: '',
        orden_plantis_numero: '',
        estado_orden_plantis: 'desarrollo', 
        area_total_finca_hectareas: null,
        numero_palmas_total_finca: null,
        area_palmas_desarrollo_hectareas: null,
        numero_palmas_desarrollo: null,
        area_palmas_produccion_hectareas: null,
        numero_palmas_produccion: null,
        aplica_orden_plantis: false,
        numero_plantas_orden_plantis: null,
        mostrarCampoOtroMaterial: false,
        otroMaterial: '',
        habilitarProduccion: false,
        habilitarDesarrollo: true
      };
    },

    
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

 

    generateUUID() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
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
          return form.material && form.material.trim() !== '' && form.anio_siembra;
        });

        if (areasValidas.length === 0) {
          alert('Complete al menos un formulario válido');
          return;
        }

        for (const form of areasValidas) {
          const local_id = form.local_id || this.generateUUID();
          
          const areaData = {
            ...form,
            local_id,
            visita_id: this.visitaId,
            // ⭐⭐ AÑADIR los datos generales a cada área ⭐⭐
            ciclos_cosecha: this.datosGenerales.ciclos_cosecha,
            produccion_toneladas_por_mes: this.datosGenerales.produccion_toneladas_por_mes,
            area_total_finca_hectareas: this.calcularAreaTotalFinca,
            numero_palmas_total_finca: this.calcularTotalPalmas,
          };
          
          // Convertir tipos
          if (this.datosGenerales.ciclos_cosecha) {
            areaData.ciclos_cosecha = parseInt(this.datosGenerales.ciclos_cosecha);
          }
          
          if (this.datosGenerales.produccion_toneladas_por_mes) {
            areaData.produccion_toneladas_por_mes = parseFloat(this.datosGenerales.produccion_toneladas_por_mes);
          }
          
          // Eliminar campos temporales
          delete areaData.habilitarProduccion;
          delete areaData.habilitarDesarrollo;
          delete areaData.mostrarCampoOtroMaterial;
          delete areaData.otroMaterial;
          
          await saveFormData('area', areaData);
        }

        await this.cargarAreasGuardadas();
        this.formulariosAreas = [this.nuevoFormularioArea()];
        
        // ⭐⭐ OPCIONAL: Limpiar datos generales después de guardar ⭐⭐
        // this.datosGenerales = { ciclos_cosecha: '', produccion_toneladas_por_mes: '' };
        
        alert(`✅ ${areasValidas.length} áreas guardadas correctamente`);
        
      } catch (error) {
        console.error('Error guardando áreas:', error);
        alert('Error al guardar: ' + error.message);
      }
    },


    async cargarDatosGenerales() {
      if (this.areasGuardadas.length > 0) {
        // Tomar los datos del primer área guardada
        const primeraArea = this.areasGuardadas[0];
        this.datosGenerales = {
          ciclos_cosecha: primeraArea.ciclos_cosecha || '',
          produccion_toneladas_por_mes: primeraArea.produccion_toneladas_por_mes || ''
        };
      }
    },

    async eliminarArea(localId) {
      if (confirm('¿Eliminar esta área permanentemente?')) {
        try {
          const todasAreas = await getAllDataFromStore('area');
          const areasActualizadas = todasAreas.filter(area => area.local_id !== localId);
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

     if (this.areasGuardadas.length > 0) {
        await this.cargarDatosGenerales();
      }


    this.debugFormulario();
    window.addEventListener('online', this.updateOnlineStatus);
    window.addEventListener('offline', this.updateOnlineStatus);
  },

  beforeUnmount() {
    window.removeEventListener('online', this.updateOnlineStatus);
    window.removeEventListener('offline', this.updateOnlineStatus);
  
  },
toggleOtroMaterial(index) {
    const form = this.formulariosAreas[index];

    if (form.material === 'otro') {
      this.$set(this.formulariosAreas[index], 'mostrarCampoOtroMaterial', true);
      this.$set(this.formulariosAreas[index], 'otroMaterial', '');
    } else {
      this.$set(this.formulariosAreas[index], 'mostrarCampoOtroMaterial', false);
      this.$set(this.formulariosAreas[index], 'otroMaterial', '');
    }
  },

  actualizarNombreMaterial(index) {
    const form = this.formulariosAreas[index];
    if (form.mostrarCampoOtroMaterial) {
      form.material = form.otroMaterial;
    }
  },



  // ------------------------------
  // CAMBIO DE ESTADO: PRODUCCIÓN / DESARROLLO
  // ------------------------------
  validarEstadoProduccion(index) {
    const form = this.formulariosAreas[index];

    if (form.estado === 'produccion') {
      // Campos obligatorios si es producción
      if (!form.produccion_toneladas_por_mes) {
        alert('Debe especificar la producción mensual si el estado es Producción.');
      }
      if (!form.ciclos_cosecha) {
        alert('Debe especificar los ciclos de cosecha si el estado es Producción.');
      }
    }
  },

  // ------------------------------
  // LÓGICA DE ORDEN PLANTIS
  // ------------------------------
  validarOrdenPlantis(index) {
    const form = this.formulariosAreas[index];

    if (form.aplica_orden_plantis) {
      if (!form.orden_plantis_numero) {
        alert('Debe especificar el número de Orden Plantis.');
        return false;
      }
      if (!form.estado_oren_plantis) {
        alert('Debe seleccionar el estado de Orden Plantis.');
        return false;
      }
    }

    return true;
  },

  
  agregarFormulario() {
    this.formulariosAreas.push(this.nuevoFormularioArea());
  },

  eliminarFormulario(index) {
    this.formulariosAreas.splice(index, 1);
  },

  // ------------------------------
  // VALIDACIONES ANTES DE GUARDAR
  // ------------------------------
  validarFormulario(index) {
    const form = this.formulariosAreas[index];

    if (!form.variedad || !form.material || !form.anio_siembra || !form.area) {
      alert('Todos los campos obligatorios deben estar completos.');
      return false;
    }

    if (!this.validarOrdenPlantis(index)) return false;

    return true;
  },

  guardarAreas() {
    for (let i = 0; i < this.formulariosAreas.length; i++) {
      if (!this.validarFormulario(i)) return;
    }

    // Guardar en IndexedDB...
    this.formulariosAreas.forEach(form => {
      form.visita_id = this.visitaId;
      saveFormData('areas', form);
    });

    alert('Áreas guardadas correctamente.');
    this.cargarAreasGuardadas();
    this.formulariosAreas = [this.nuevoFormularioArea()];
  },

  async cargarAreasGuardadas() {
    const data = await getFormDataByVisita('areas', this.visitaId);
    this.areasGuardadas = data ?? [];
  },

  irAFertilizacion() {
    this.$router.push(`/offline/fertilizacion/${this.visitaId}`);
  },

  // ------------------------------
  // LÓGICA VISITA (YA EXISTENTE)
  // ------------------------------
  async cargarInformacionVisita() { /* lo que ya tienes */ },

  formatDate(date) { /* lo que ya tienes */ }

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
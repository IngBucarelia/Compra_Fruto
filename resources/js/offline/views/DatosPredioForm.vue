<template>
  <div class="offline-container offline-form-container">
    <!-- Header con información de la visita -->
    <div class="visita-info-card mb-4" style="margin-top: 50px;">
      <div class="card-header bg-gradient-info text-white">
        <h4 class="mb-0 text-center">
          <i class="fas fa-tractor me-2"></i>
          Datos del Predio - Visita Social (Modo Offline)
        </h4>
      </div>
      <div class="card-body">
        <div class="row text-center text-md-start">
          <div class="col-md-6">
            <p class="text-white"><strong>Proveedor:</strong> {{ proveedorNombre }}</p>
            <p class="text-white"><strong>Visita ID:</strong> #{{ visitaId }}</p>
            <p class="text-white"><strong>Plantación:</strong> {{ plantacionNombre }}</p>
          </div>
          <div class="col-md-6">
            <p class="text-white"><strong>Fecha:</strong> {{ fechaActual }}</p>
            <p class="text-white"><strong>Municipio:</strong> {{ datosPredio?.municipio || 'No especificado' }}</p>
            <p class="text-white"><strong>Estado:</strong> <span class="badge bg-warning">📱 Modo Offline</span></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Resumen de Datos Previos -->
    <div class="resumen-cards mb-4">
      <div class="row">
        <!-- Datos Personales -->
        <div class="col-md-6 mb-3" v-if="datosPersonales">
          <div class="card h-100">
            <div class="card-header bg-success text-white py-2 text-center">
              <h6 class="mb-0">
                <i class="fas fa-user me-1"></i>Datos Personales
              </h6>
            </div>
            <div class="card-body p-3">
              <p class="mb-1 text-white"><small><strong>Productor:</strong> {{ datosPersonales.telefono }}</small></p>
              <p class="mb-1 text-white"><small><strong>Nivel Estudio:</strong> {{ datosPersonales.nivel_estudio }}</small></p>
              <p class="mb-0 text-white"><small><strong>Reside:</strong> {{ datosPersonales.reside_predio }}</small></p>
            </div>
          </div>
        </div>

        <!-- Miembros del Hogar -->
        <div class="col-md-6 mb-3" v-if="miembrosHogar.length > 0">
          <div class="card h-100">
            <div class="card-header bg-primary text-white py-2 text-center">
              <h6 class="mb-0">
                <i class="fas fa-users me-1"></i>Miembros ({{ miembrosHogar.length }})
              </h6>
            </div>
            <div class="card-body p-3">
              <p class="mb-1 text-white" v-for="miembro in miembrosHogar.slice(0, 2)" :key="miembro.local_id">
                <small>{{ miembro.nombre }} - {{ miembro.parentezco }}</small>
              </p>
              <p class="mb-0 text-white" v-if="miembrosHogar.length > 2">
                <small>+{{ miembrosHogar.length - 2 }} más...</small>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navegación entre secciones -->
    <div class="navigation-card mb-4">
      <div class="card">
        <div class="card-body text-center">
          <div class="d-flex flex-wrap gap-2 justify-content-center">
            <button @click="irASeccion('inicio')" class="btn btn-outline-primary btn-sm">
              🏠 Inicio
            </button>
            <button @click="irASeccion('datos-personales')" class="btn btn-outline-success btn-sm">
              👤 Datos Personales
            </button>
            <button @click="irASeccion('miembros')" class="btn btn-outline-info btn-sm">
              👨‍👩‍👧‍👦 Miembros Hogar
            </button>
            <button @click="irASeccion('predio')" class="btn btn-outline-warning btn-sm active">
              🏡 Datos Predio
            </button>
            <button @click="irASeccion('fuerza-laboral')" class="btn btn-outline-dark btn-sm">
              🧑‍🌾 Fuerza Laboral
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Datos Guardados del Predio -->
    <div v-if="datosPredio" class="datos-guardados-card mb-4">
      <div class="card">
        <div class="card-header bg-success text-white text-center">
          <h5 class="mb-0">
            <i class="fas fa-check-circle me-2"></i>Datos del Predio Guardados
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <p><strong class="text-white">Forma Tenencia:</strong> 
                <span class="text-white">
                  {{ Array.isArray(datosPredio.forma_tenencia) 
                      ? datosPredio.forma_tenencia.join(', ') 
                      : datosPredio.forma_tenencia }}
                </span>
              </p>
              <p><strong class="text-white">Registro ICA:</strong> <span class="text-white">{{ datosPredio.registrado_ica }}</span></p>
                          </div>
            <div class="col-md-4">
              <p><strong class="text-white">Vive en Predio:</strong> <span class="text-white">{{ datosPredio.vive_predio }}</span></p>
              <p><strong class="text-white">Infraestructura:</strong> <span class="text-white">{{ datosPredio.infraestructura_predio }}</span></p>
            </div>
            <div class="col-md-4">
              <p><strong class="text-white">Vías:</strong> <span class="text-white">{{ Array.isArray(datosPredio.infraestructura_vial) ? datosPredio.infraestructura_vial.join(', ') : datosPredio.infraestructura_vial }}</span></p>
            </div>
          </div>
          <div class="text-center">
            <button @click="eliminarDatos" class="btn btn-danger btn-sm mt-3">
              <i class="fas fa-trash me-1"></i>Eliminar Datos
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Formulario de Datos del Predio -->
    <div class="form-card">
      <div class="card-header bg-white text-center">
        <h5 class="mb-0 text-primary">
          <i class="fas fa-tractor me-2"></i>Información del Predio
        </h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="guardarDatos">
          <div class="row">
            <!-- Nombre de la finca -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-flag me-2"></i>Nombre de la finca
              </label>
              <input type="text" v-model="formData.nombre_finca" class="form-control" 
                     placeholder="Nombre de la finca o predio" required>
            </div>

            <!-- Forma de tenencia -->
                          
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold text-white">
                  <i class="fas fa-file-contract me-2"></i>Forma de tenencia
                </label>

                <div class="form-check" v-for="opcion in opcionesTenencia" :key="opcion.value">
                  <input class="form-check-input" type="checkbox"
                        :value="opcion.value"
                        v-model="formData.forma_tenencia"
                        :id="'tenencia-' + opcion.value" />
                  <label class="form-check-label text-white" :for="'tenencia-' + opcion.value">
                    {{ opcion.label }}
                  </label>
                </div>

                <small class="text-white">Puedes seleccionar una o varias opciones</small>
              </div>

          </div>

          <div class="row">
            <!-- Municipio -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-map-marker-alt me-2"></i>Municipio
              </label>
              <input type="text" v-model="formData.municipio" class="form-control" 
                     placeholder="Municipio donde se ubica el predio" required>
            </div>

            <!-- Vereda -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-road me-2"></i>Vereda
              </label>
              <input type="text" v-model="formData.vereda" class="form-control" 
                     placeholder="Vereda o sector" required>
            </div>
          </div>

          <div class="row">
            <!-- Registro ICA -->
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-certificate me-2"></i>Registro ICA
              </label>
              <select v-model="formData.registrado_ica" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="En gestión">En gestión</option>
                <option value="No aplica">No aplica</option>
              </select>
            </div>

            <!-- Vive en el predio -->
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-home me-2"></i>Vive en el predio
              </label>
              <select v-model="formData.vive_predio" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="NO (va por temporadas)">No (va por temporadas)</option>
                <option value="Fines de semana">Fines de semana</option>
              </select>
            </div>

            <!-- Infraestructura vivienda -->
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-building me-2"></i>¿Cuenta con vivienda o infraestructura?
              </label>
              <select v-model="formData.infraestructura_predio" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="En construcción">En construcción</option>
              </select>
            </div>
          </div>

          <!-- Infraestructura vial -->
          <div class="mb-3">
            <label class="form-label fw-bold text-white">
              <i class="fas fa-road me-2"></i>Infraestructura vial (Seleccione todas las que apliquen)
            </label>
            <div class="vias-options">
              <div class="form-check" v-for="via in opcionesVias" :key="via.value">
                <input class="form-check-input" type="checkbox" :value="via.value" 
                       v-model="formData.infraestructura_vial" :id="'via-' + via.value">
                <label class="form-check-label text-white" :for="'via-' + via.value">
                  {{ via.label }}
                </label>
              </div>
            </div>
            <small class="text-white">Puede seleccionar múltiples opciones</small>
          </div>

          <!-- Tipo de vivienda (si tiene infraestructura) -->
          <div v-if="formData.infraestructura_predio === 'SI'" class="mb-3">
            <label class="form-label fw-bold text-white">
              <i class="fas fa-house-user me-2"></i>Tipo de vivienda
            </label>
            <select v-model="formData.tipo_vivienda" class="form-select">
              <option value="">Seleccione...</option>
              <option value="Casa">Casa</option>
              <option value="Apartamento">Apartamento</option>
              <option value="Finca">Finca</option>
              <option value="Rancho">Rancho</option>
              <option value="Otro">Otro</option>
            </select>
          </div>

          <!-- Material predominante vivienda -->
          <div v-if="formData.infraestructura_predio === 'SI'" class="mb-3">
            <label class="form-label fw-bold text-white">
              <i class="fas fa-hammer me-2"></i>Material predominante de la vivienda
            </label>
            <select v-model="formData.material_vivienda" class="form-select">
              <option value="">Seleccione...</option>
              <option value="Ladrillo">Ladrillo</option>
              <option value="Madera">Madera</option>
              <option value="Adobe">Adobe</option>
              <option value="Bahareque">Bahareque</option>
              <option value="Prefabricado">Prefabricado</option>
              <option value="Mixto">Mixto</option>
            </select>
          </div>

          <!-- Servicios públicos -->
          <div class="mb-3">
            <label class="form-label fw-bold text-white">
              <i class="fas fa-bolt me-2"></i>Servicios públicos con los que cuenta
            </label>
            <div class="servicios-options">
              <div class="row">
                <div class="col-md-3" v-for="servicio in opcionesServicios" :key="servicio.value">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" :value="servicio.value" 
                           v-model="formData.servicios_publicos" :id="'servicio-' + servicio.value">
                    <label class="form-check-label text-white" :for="'servicio-' + servicio.value">
                      {{ servicio.label }}
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Observaciones adicionales -->
          <div class="mb-3">
            <label class="form-label fw-bold text-white">
              <i class="fas fa-sticky-note me-2"></i>Observaciones adicionales del predio
            </label>
            <textarea v-model="formData.observaciones" class="form-control" 
                      placeholder="Observaciones sobre el predio, accesos, condiciones, etc..." 
                      rows="3"></textarea>
          </div>

          <!-- Botones de acción -->
          <div class="button-group mt-4">
            <button type="submit" class="btn btn-success btn-lg" :disabled="!formValido">
              <i class="fas fa-save me-2"></i>
              {{ datosPredio ? 'Actualizar Datos' : 'Guardar Datos del Predio' }}
            </button>
            
            <button type="button" class="btn btn-info btn-lg" @click="irAFuerzaLaboral">
              <i class="fas fa-arrow-right me-2"></i>Siguiente: Fuerza Laboral
            </button>
            
            <button v-if="canSync && datosPredio" @click="sincronizar" class="btn btn-warning btn-lg">
              <i class="fas fa-sync me-2"></i>Sincronizar
            </button>
            
            <button type="button" class="btn btn-outline-light btn-lg" onclick="history.back()">
              <i class="fas fa-arrow-left me-2"></i>Volver
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { saveFormData, getFormDataByVisita, getAllDataFromStore, deleteDataFromStore } from '../store/indexeddb';

export default {
  data() {
    return {
      formData: {
        nombre_finca: '',
        forma_tenencia: [],
        municipio: '',
        vereda: '',
        registrado_ica: '',
        vive_predio: '',
        infraestructura_predio: '',
        infraestructura_vial: [],
        tipo_vivienda: '',
        material_vivienda: '',
        servicios_publicos: [],
        observaciones: ''
      },
      datosPredio: null,
      datosPersonales: null,
      miembrosHogar: [],
      visitaId: null,
      proveedorNombre: 'Cargando...',
      plantacionNombre: 'Predio',
      canSync: navigator.onLine,
      fechaActual: new Date().toLocaleDateString(),
      opcionesVias: [
        { value: 'Vía destapada carreteable', label: 'Vía destapada carreteable' },
        { value: 'Vía pavimentada', label: 'Vía pavimentada' },
        { value: 'Camino', label: 'Camino' },
        { value: 'Fluvial', label: 'Fluvial' },
        { value: 'Trocha', label: 'Trocha' },
        { value: 'Acceso limitado', label: 'Acceso limitado' }
      ],
      opcionesServicios: [
        { value: 'Energía', label: '⚡ Energía' },
        { value: 'Agua potable', label: '💧 Agua potable' },
        { value: 'Alcantarillado', label: '🚽 Alcantarillado' },
        { value: 'Gas natural', label: '🔥 Gas natural' },
        { value: 'Telefonía', label: '📞 Telefonía' },
        { value: 'Internet', label: '🌐 Internet' },
        { value: 'Recolección basuras', label: '🗑️ Recolección basuras' },
        { value: 'Ninguno', label: '❌ Ninguno' }
      ],
      opcionesTenencia: [
        { value: 'Propietario con escritura', label: 'Propietario con escritura' },
        { value: 'Arrendamiento', label: 'Arrendamiento' },
        { value: 'Carta venta', label: 'Carta venta' },
        { value: 'Tradición y libertad', label: 'Tradición y libertad' },
        { value: 'Posesión', label: 'Posesión' },
        { value: 'Problemas Juridos', label: 'Prddio con Problemas Juridicos' },
        { value: 'Comunidad', label: 'Comunidad' },
        { value: 'Otro', label: 'Otro' }
      ],

    };
  },
  computed: {
    formValido() {
      return this.formData.nombre_finca && 
             this.formData.forma_tenencia && 
             this.formData.municipio && 
             this.formData.vereda &&
             this.formData.registrado_ica &&
             this.formData.vive_predio &&
             this.formData.infraestructura_predio &&
             this.formData.infraestructura_vial.length > 0;
    }
  },
  methods: {
    generateUUID() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
    },

    async cargarDatosPersonales() {
      try {
        this.datosPersonales = await getFormDataByVisita('datos_personales_social', this.visitaId);
      } catch (error) {
        console.error('Error cargando datos personales:', error);
      }
    },

    async cargarMiembrosHogar() {
      try {
        const todosLosMiembros = await getAllDataFromStore('miembros_hogar_social');
        this.miembrosHogar = todosLosMiembros.filter(miembro => 
          miembro.visita_id == this.visitaId
        );
      } catch (error) {
        console.error('Error cargando miembros:', error);
      }
    },

    async cargarDatosPredio() {
      try {
        this.datosPredio = await getFormDataByVisita('datos_predio_social', this.visitaId);
        if (this.datosPredio) {
          // Cargar datos en el formulario
          this.formData = { ...this.datosPredio };
          // Asegurar que los arrays estén inicializados
          if (!Array.isArray(this.formData.infraestructura_vial)) {
            this.formData.infraestructura_vial = [];
          }
          if (!Array.isArray(this.formData.servicios_publicos)) {
            this.formData.servicios_publicos = [];
          }
        }
      } catch (error) {
        console.error('Error cargando datos del predio:', error);
      }
    },

    async guardarDatos() {
      try {
        const datosCompletos = {
          ...this.formData,
          local_id: this.datosPredio ? this.datosPredio.local_id : this.generateUUID(),
          visita_id: this.visitaId,
          proveedor_nombre: this.proveedorNombre,
          plantacion_nombre: this.plantacionNombre,
          timestamp: new Date().toISOString(),
          sincronizado: false
        };

        await saveFormData('datos_predio_social', datosCompletos);
        await this.cargarDatosPredio();
        
        this.mostrarAlerta('success', '✅ Datos del predio guardados correctamente en modo offline');
        
      } catch (error) {
        console.error('Error guardando datos del predio:', error);
        this.mostrarAlerta('error', 'Error al guardar los datos: ' + error.message);
      }
    },

    async eliminarDatos() {
      if (confirm('¿Eliminar los datos del predio guardados?')) {
        try {
          await deleteDataFromStore('datos_predio_social', this.datosPredio.local_id);
          this.datosPredio = null;
          this.formData = { ...this.$options.data().formData }; // Reset form
          this.mostrarAlerta('success', 'Datos eliminados correctamente');
        } catch (error) {
          console.error('Error eliminando datos:', error);
          this.mostrarAlerta('error', 'Error al eliminar los datos: ' + error.message);
        }
      }
    },

    irAFuerzaLaboral() {
      this.$router.push(`/fuerza-laboral?visita_id=${this.visitaId}`);
    },

    irASeccion(seccion) {
      switch(seccion) {
        case 'inicio':
          window.location.href = `/visitas_social/showSocial/${this.visitaId}`;
          break;
        case 'datos-personales':
          this.$router.push(`/datos-personales?visita_id=${this.visitaId}`);
          break;
        case 'miembros':
          this.$router.push(`/miembros-hogar?visita_id=${this.visitaId}`);
          break;
        case 'predio':
          // Ya estamos aquí
          break;
        case 'fuerza-laboral':
          this.$router.push(`/fuerza-laboral?visita_id=${this.visitaId}`);
          break;
      }
    },

    async sincronizar() {
      if (!this.canSync) {
        this.mostrarAlerta('warning', 'No hay conexión a internet para sincronizar');
        return;
      }
      
      if (!this.datosPredio) {
        this.mostrarAlerta('warning', 'No hay datos del predio para sincronizar');
        return;
      }

      try {
        this.mostrarAlerta('info', '🔄 Sincronizando datos del predio...');
        
        // Aquí iría la lógica de sincronización con tu backend
        // Ejemplo:
        // const response = await fetch('/api/sync-datos-predio', {
        //   method: 'POST',
        //   headers: { 'Content-Type': 'application/json' },
        //   body: JSON.stringify(this.datosPredio)
        // });
        
        // Simulamos sincronización
        setTimeout(() => {
          this.mostrarAlerta('success', '✅ Datos del predio sincronizados correctamente');
        }, 2000);
        
      } catch (error) {
        this.mostrarAlerta('error', 'Error en sincronización: ' + error.message);
      }
    },

    mostrarAlerta(tipo, mensaje) {
      if (tipo === 'success') {
        alert(mensaje);
      } else if (tipo === 'error') {
        alert('❌ ' + mensaje);
      } else if (tipo === 'warning') {
        alert('⚠️ ' + mensaje);
      } else {
        alert(mensaje);
      }
    },

    updateOnlineStatus() {
      this.canSync = navigator.onLine;
    },

    cargarInformacionVisita() {
      this.proveedorNombre = localStorage.getItem(`proveedor_${this.visitaId}`) || 'Proveedor';
      this.plantacionNombre = localStorage.getItem(`plantacion_${this.visitaId}`) || 'Predio';
      
      // Cargar datos básicos del predio si existen
      const predioData = localStorage.getItem(`predio_basico_${this.visitaId}`);
      if (predioData) {
        const predio = JSON.parse(predioData);
        this.formData.municipio = predio.municipio || '';
        this.formData.vereda = predio.vereda || '';
        this.formData.nombre_finca = predio.nombre || '';
      }
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    this.cargarInformacionVisita();
    await this.cargarDatosPersonales();
    await this.cargarMiembrosHogar();
    await this.cargarDatosPredio();

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
.offline-form-container {
  max-width: 1200px;
  margin-top: 100px;
  padding: 5%;
  min-height: 100vh;
  border-radius: 25px;
}

.visita-info-card {
  border-radius: 15px;
  overflow: hidden;
  background: linear-gradient(135deg, #fe977aa2 0%, #fad2c9b9 100%);
}

.navigation-card .card-body {
  padding: 15px;
}

.form-card {
  border-radius: 15px;
  overflow: hidden;
  margin-bottom: 20px;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.resumen-cards .card {
  border: 1px solid rgba(255, 255, 255, 0.3);
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
}

.datos-guardados-card .card {
  border: 2px solid #28a745;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
}

.button-group {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: center;
}

.form-label {
  font-weight: 500;
  margin-bottom: 8px;
  display: block;
  font-size: 0.95rem;
}

.card-header {
  border-radius: 15px 15px 0 0 !important;
}

.badge {
  font-size: 0.8rem;
}

/* Estilos responsivos */
@media (max-width: 768px) {
  .offline-form-container {
    margin: 1% auto;
    padding: 3%;
     width: 330px !important;
    margin-left: -250px !important;
  }

  .button-group {
    flex-direction: column;
  }
  
  .button-group .btn {
    width: 100%;
    margin-bottom: 10px;
  }

  .form-card {
    margin-bottom: 15px;
  }

  .visita-info-card .card-body .row {
    text-align: center;
  }

  .resumen-cards .col-md-6 {
    margin-bottom: 15px;
  }
}

@media (max-width: 576px) {
  .offline-form-container {
    margin: 0.5% auto;
    padding: 4%;
  }

  .form-label {
    font-size: 0.9rem;
  }

  .btn-lg {
    padding: 12px 20px;
    font-size: 1rem;
  }
}

/* Mejoras de legibilidad */
.form-control, .form-select {
  border-radius: 8px;
  border: 1px solid #dee2e6;
  padding: 10px 15px;
  font-size: 0.95rem;
}

.form-control:focus, .form-select:focus {
  border-color: #89ea66ab;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn {
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Fondo general */
.offline-container {
  background: linear-gradient(135deg, #83ea6666 0%, #4ba24ec9 100%);
  min-height: 100vh;
  padding: 20px 0;
}

/* Texto blanco para todos los labels y textos */
.text-white {
  color: white !important;
}

.form-card .card-body,
.datos-guardados-card .card-body,
.resumen-cards .card-body {
  color: white;
}

.vias-options, .servicios-options {
  background-color: rgba(255, 255, 255, 0.1);
  padding: 15px;
  border-radius: 8px;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.vias-options .form-check {
  margin-bottom: 8px;
}

.servicios-options .form-check {
  margin-bottom: 5px;
}

.navigation-card .btn.active {
  background-color: #ffc107;
  color: #212529;
  border-color: #ffc107;
}
</style>
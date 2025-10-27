<template>
  <div class="offline-container offline-form-container" >
    <!-- Header con información de la visita -->
    <div class="visita-info-card mb-4" style="margin-top: 50px;">
      <div class="card-header bg-gradient-info text-white">
        <h4 class="mb-0 text-center">
          <i class="fas fa-users me-2"></i>
          Datos Personales - Visita Social (Modo Offline)
        </h4>
      </div>
      <div class="card-body">
        <div class="row text-center text-md-start">
          <div class="col-md-6">
            <p class="text-white"><strong>Proveedor:</strong> {{ proveedorNombre }}</p>
            <p class="text-white"><strong>Visita ID:</strong> #{{ visitaId }}</p>
          </div>
          <div class="col-md-6">
            <p class="text-white"><strong>Fecha:</strong> {{ fechaActual }}</p>
            <p class="text-white"><strong>Estado:</strong> <span class="badge bg-warning">📱 Modo Offline</span></p>
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
            <button @click="irASeccion('miembros')" class="btn btn-outline-success btn-sm">
              👨‍👩‍👧‍👦 Miembros Hogar
            </button>
            <button @click="irASeccion('predio')" class="btn btn-outline-info btn-sm">
              🏡 Datos Predio
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Formulario de Datos Personales -->
    <div class="form-card">
      <div class="card-header bg-white text-center">
        <h5 class="mb-0 text-primary">
          <i class="fas fa-user-circle me-2"></i>Información Personal del Productor
        </h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="guardarDatos">
          <div class="row">
            <!-- Teléfono -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-phone me-2"></i>Teléfono del productor/a
              </label>
              <input type="text" v-model="formData.telefono" class="form-control" 
                     placeholder="Digite el teléfono" maxlength="20">
            </div>

            <!-- Sexo -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-venus-mars me-2"></i>Sexo
              </label>
              <select v-model="formData.sexo" class="form-select">
                <option value="">Seleccione</option>
                <option value="Hombre">Hombre</option>
                <option value="Mujer">Mujer</option>
                <option value="No se identifica">No se identifica</option>
              </select>
            </div>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold text-white">
              <i class="fas fa-file-signature me-2"></i>¿Ha firmado oferta mercantil?
            </label>
            <select v-model="formData.oferta_mercantil" @change="toggleOfertaMercantil" class="form-select">
              <option value="">Seleccione</option>
              <option value="SI">SI</option>
              <option value="NO">NO</option>
            </select>
          </div>

          <!-- Campo condicional: Hace cuánto -->
          <div class="col-md-6 mb-3" v-if="showHaceCuanto">
            <label class="form-label fw-bold text-white">
              <i class="fas fa-calendar-check me-2"></i>¿Hace cuánto?
            </label>
            <input type="text" v-model="formData.hace_cuanto" class="form-control" placeholder="Ej: 6 meses, 1 año...">
          </div>

          <div class="row">
           <!-- RNP -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-bold text-white">
              <i class="fas fa-id-card me-2"></i>Cuenta con RNP?
            </label>
            <select v-model="formData.rnp" @change="toggleNumeroRNP" class="form-select">
              <option value="">Seleccione</option>
              <option value="SI">SI</option>
              <option value="NO">NO</option>
            </select>
          </div>

          <!-- Número RNP - Campo condicional -->
          <div class="col-md-6 mb-3" v-if="showNumeroRNP">
            <label class="form-label fw-bold text-white">
              <i class="fas fa-hashtag me-2"></i>Número de RNP
            </label>
            <input type="text" v-model="formData.numero_rnp" class="form-control" 
                  placeholder="Digite el número de RNP" maxlength="50">
          </div>

            <!-- Fedepalma -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-tree me-2"></i>Afiliado a Fedepalma?
              </label>
              <select v-model="formData.fedepalma" class="form-select">
                <option value="">Seleccione</option>
                <option value="SI">SI</option>
                <option value="NO">NO</option>
              </select>
            </div>
          </div>

          <div class="row">
            <!-- Alfabetizado - CORREGIDO para string -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-book me-2"></i>Sabe leer y/o escribir?
              </label>
              <select v-model="formData.alfabetizado" class="form-select">
                <option value="">Seleccione</option>
                <option value="SI">SI</option>
                <option value="NO">NO</option>
              </select>
            </div>

            <!-- Nivel de estudio -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-graduation-cap me-2"></i>Nivel de estudio
              </label>
              <select v-model="formData.nivel_estudio" class="form-select">
                <option value="">Seleccione</option>
                <option value="Primaria">Primaria</option>
                <option value="Bachillerato">Bachillerato</option>
                <option value="Tecnico">Tecnico</option>
                <option value="Tecnologo">Tecnologo</option>
                <option value="Profesional">Profesional</option>
                <option value="Maestria">Maestria</option>
                <option value="Ninguno">Ninguno</option>
              </select>
            </div>
          </div>

          <div class="row">
            <!-- Fecha nacimiento -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-birthday-cake me-2"></i>Fecha de nacimiento
              </label>
              <input type="date" v-model="formData.fecha_nacimiento" class="form-control">
            </div>

            <!-- Grupo poblacional -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-users me-2"></i>Grupo poblacional
              </label>
              <select v-model="formData.grupo_poblacional" class="form-select">
                <option value="">Seleccione</option>
                <option value="Indigena">Indigena</option>
                <option value="Afrodescendiente">Afrodescendiente</option>
                <option value="Campesino">Campesino</option>
                <option value="Ninguno">Ninguno de los anteriores</option>
              </select>
            </div>
          </div>

          <div class="row">
            <!-- Años en palmicultura -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-calendar-alt me-2"></i>Años en palmicultura
              </label>
              <input type="number" v-model.number="formData.anios_palmicultura" class="form-control" 
                     placeholder="Ej: 10" min="0" max="100">
            </div>

            <!-- Régimen de salud -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-heartbeat me-2"></i>Régimen de salud
              </label>
              <select v-model="formData.regimen_salud" class="form-select">
                <option value="">Seleccione</option>
                <option value="Contributivo">Contributivo</option>
                <option value="Subsidiado">Subsidiado</option>
                <option value="Regimen especial">Regimen especial</option>
                <option value="Ninguno">Ninguno</option>
              </select>
            </div>
          </div>

          <div class="row">
            <!-- Reside en el predio - CORREGIDO para string -->
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-home me-2"></i>Reside en el predio?
              </label>
              <select v-model="formData.reside_predio" class="form-select">
                <option value="">Seleccione</option>
                <option value="SI">SI</option>
                <option value="NO">NO</option>
              </select>
            </div>

            <!-- Administra cultivo - CORREGIDO para string -->
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-tasks me-2"></i>Administra cultivo?
              </label>
              <select v-model="formData.administra_cultivo" class="form-select">
                <option value="">Seleccione</option>
                <option value="SI">SI</option>
                <option value="NO">NO</option>
              </select>
            </div>

            <!-- Internet -->
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-wifi me-2"></i>Acceso a internet?
              </label>
              <select v-model="formData.internet" class="form-select">
                <option value="">Seleccione</option>
                <option value="SI">SI</option>
                <option value="NO">NO</option>
              </select>
            </div>
          </div>

          <div class="row">
            <!-- Red social -->
            <div class="col-md-12 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-share-alt me-2"></i>Red social que más usa
              </label>
              <select v-model="formData.red_social" class="form-select">
                <option value="">Seleccione</option>
                <option value="Whatsapp">Whatsapp</option>
                <option value="Facebook">Facebook</option>
                <option value="Instagram">Instagram</option>
                <option value="TikTok">TikTok</option>
                <option value="Todas las anteriores">Todas las anteriores</option>
                <option value="Ninguna de las anteriores">Ninguna de las anteriores</option>
              </select>
            </div>
          </div>

          <!-- Campos adicionales que no están en la validación pero sí en el modelo -->
          <div class="row" v-if="showAdditionalFields">
            <!-- Otras líneas -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-briefcase me-2"></i>Otras líneas de negocio?
              </label>
              <select v-model="formData.otras_lineas" class="form-select">
                <option value="">Seleccione</option>
                <option value="SI">SI</option>
                <option value="NO">NO</option>
              </select>
            </div>

            <!-- Supervisa cultivo -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-eye me-2"></i>Supervisa cultivo?
              </label>
              <select v-model="formData.supervisa_cultivo" class="form-select">
                <option value="">Seleccione</option>
                <option value="SI">SI</option>
                <option value="NO">NO</option>
                <option value="Lo realiza un tercero">Lo realiza un tercero</option>
              </select>
            </div>
          </div>

          <div class="row" v-if="showAdditionalFields">
            <!-- Realiza cultivo -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-tools me-2"></i>Realiza cultivo?
              </label>
              <select v-model="formData.realiza_cultivo" class="form-select">
                <option value="">Seleccione</option>
                <option value="SI">SI</option>
                <option value="NO">NO</option>
                <option value="Las realiza un tercero">Las realiza un tercero</option>
              </select>
            </div>

            <!-- Tipo persona -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-user-tie me-2"></i>Tipo de persona
              </label>
              <select v-model="formData.tipo_persona" class="form-select">
                <option value="">Seleccione</option>
                <option value="Natural">Natural</option>
                <option value="Juridica">Juridica</option>
              </select>
            </div>
          </div>

          <!-- Botón para mostrar/ocultar campos adicionales -->
          <div class="mb-3 text-center">
            <button type="button" class="btn btn-outline-light btn-sm" @click="showAdditionalFields = !showAdditionalFields">
              <i class="fas" :class="showAdditionalFields ? 'fa-eye-slash' : 'fa-eye'"></i>
              {{ showAdditionalFields ? 'Ocultar' : 'Mostrar' }} campos adicionales
            </button>
          </div>

          <!-- Botones de acción -->
          <div class="button-group mt-4">
            <button type="submit" class="btn btn-success btn-lg" :disabled="!formValido">
              <i class="fas fa-save me-2"></i>Guardar Datos
            </button>
            <button type="button" class="btn btn-info btn-lg" @click="irAMiembros" :disabled="!datosGuardados">
              <i class="fas fa-arrow-right me-2"></i>Siguiente: Miembros
            </button>
            <button v-if="canSync" @click="sincronizar" class="btn btn-warning btn-lg">
              <i class="fas fa-sync me-2"></i>Sincronizar
            </button>
            <button type="button" class="btn btn-secondary btn-lg" onclick="history.back()">
              <i class="fas fa-arrow-left me-2"></i>Volver
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Datos Guardados -->
    <div v-if="datosGuardados" class="saved-data-card mt-4">
      <div class="card">
        <div class="card-header bg-success text-white text-center">
          <h5 class="mb-0">
            <i class="fas fa-check-circle me-2"></i>Datos Guardados Localmente
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong class="text-white">Teléfono:</strong> <span class="text-white">{{ datosGuardados.telefono || 'No especificado' }}</span></p>
              <p><strong class="text-white">Sexo:</strong> <span class="text-white">{{ datosGuardados.sexo || 'No especificado' }}</span></p>
              <p><strong class="text-white">RNP:</strong> <span class="text-white">{{ datosGuardados.rnp || 'No especificado' }}</span></p>
              <p><strong class="text-white">Fedepalma:</strong> <span class="text-white">{{ datosGuardados.fedepalma || 'No especificado' }}</span></p>
              <p><strong class="text-white">Alfabetizado:</strong> <span class="text-white">{{ datosGuardados.alfabetizado || 'No especificado' }}</span></p>
            </div>
            <div class="col-md-6">
              <p><strong class="text-white">Nivel estudio:</strong> <span class="text-white">{{ datosGuardados.nivel_estudio || 'No especificado' }}</span></p>
              <p><strong class="text-white">Grupo poblacional:</strong> <span class="text-white">{{ datosGuardados.grupo_poblacional || 'No especificado' }}</span></p>
              <p><strong class="text-white">Años palmicultura:</strong> <span class="text-white">{{ datosGuardados.anios_palmicultura || 'No especificado' }}</span></p>
              <p><strong class="text-white">Red social:</strong> <span class="text-white">{{ datosGuardados.red_social || 'No especificado' }}</span></p>
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
  </div>
</template>

<script>
import { saveFormData, getFormDataByVisita, deleteDataFromStore } from '../store/indexeddb';

export default {
  data() {
    return {
      formData: {
        telefono: '',
        sexo: '',
        rnp: '',
        numero_rnp: '',
        fedepalma: '',
        alfabetizado: '', // Cambiado a string vacío
        nivel_estudio: '',
        otras_lineas: '',
        fecha_nacimiento: '',
        grupo_poblacional: '',
        reside_predio: '', // Cambiado a string vacío
        administra_cultivo: '', // Cambiado a string vacío
        supervisa_cultivo: '',
        realiza_cultivo: '',
        anios_palmicultura: null,
        internet: '',
        tipo_persona: '',
        red_social: '',
        regimen_salud: '',
        oferta_mercantil: '',
        hace_cuanto:''
      },
      datosGuardados: null,
      visitaId: null,
      proveedorNombre: 'Cargando...',
      canSync: navigator.onLine,
      fechaActual: new Date().toLocaleDateString(),
      showAdditionalFields: false,
      showNumeroRNP: false,
      showHaceCuanto: false,

    };
  },
  computed: {
    formValido() {
      return this.formData.telefono && 
             this.formData.sexo;
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

    toggleOfertaMercantil() {
      this.showHaceCuanto = this.formData.oferta_mercantil === 'SI';
      if (this.formData.oferta_mercantil === 'NO') {
        this.formData.hace_cuanto = '';
      }
    },
    toggleNumeroRNP() {
      this.showNumeroRNP = this.formData.rnp === 'SI';
      // Si selecciona NO, limpiar el campo número RNP
      if (this.formData.rnp === 'NO') {
        this.formData.numero_rnp = '';
      }
    },

    async cargarDatosGuardados() {
      try {
        this.datosGuardados = await getFormDataByVisita('datos_personales_social', this.visitaId);
        if (this.datosGuardados) {
          this.formData = { 
            ...this.formData,
            ...this.datosGuardados 
          };
        }
        this.showNumeroRNP = this.formData.rnp === 'SI';
      } catch (error) {
        console.error('Error cargando datos:', error);
      }
    },

    async guardarDatos() {
      try {
        // Preparar datos con todos los campos como strings
        const datosParaGuardar = {
          // Campos principales
          telefono: this.formData.telefono || null,
          sexo: this.formData.sexo || null,
          rnp: this.formData.rnp || null,
          numero_rnp: this.formData.rnp === 'SI' ? (this.formData.numero_rnp || null) : null, 
          fedepalma: this.formData.fedepalma || null,
          alfabetizado: this.formData.alfabetizado || null, 
          nivel_estudio: this.formData.nivel_estudio || null,
          fecha_nacimiento: this.formData.fecha_nacimiento || null,
          grupo_poblacional: this.formData.grupo_poblacional || null,
          anios_palmicultura: this.formData.anios_palmicultura ? parseInt(this.formData.anios_palmicultura) : null,
          regimen_salud: this.formData.regimen_salud || null,
          reside_predio: this.formData.reside_predio || null, 
          administra_cultivo: this.formData.administra_cultivo || null, 
          internet: this.formData.internet || null,
          red_social: this.formData.red_social || null,
          
          // Campos adicionales
          otras_lineas: this.formData.otras_lineas || null,
          supervisa_cultivo: this.formData.supervisa_cultivo || null,
          realiza_cultivo: this.formData.realiza_cultivo || null,
          tipo_persona: this.formData.tipo_persona || null,
          oferta_mercantil: this.formData.oferta_mercantil || null,
          hace_cuanto: this.formData.oferta_mercantil === 'SI' ? (this.formData.hace_cuanto || null) : null,


          // Metadatos
          local_id: this.generateUUID(),
          visita_id: this.visitaId,
          proveedor_nombre: this.proveedorNombre,
          timestamp: new Date().toISOString(),
          sincronizado: false
        };

        console.log('Datos a guardar:', datosParaGuardar);

        await saveFormData('datos_personales_social', datosParaGuardar);
        await this.cargarDatosGuardados();
        
        this.mostrarAlerta('success', '✅ Datos personales guardados correctamente en modo offline');
        
      } catch (error) {
        console.error('Error guardando datos:', error);
        this.mostrarAlerta('error', 'Error al guardar los datos: ' + error.message);
      }
    },

    async eliminarDatos() {
      if (confirm('¿Eliminar los datos personales guardados?')) {
        try {
          if (this.datosGuardados && this.datosGuardados.local_id) {
            await deleteDataFromStore('datos_personales_social', this.datosGuardados.local_id);
          }
          this.datosGuardados = null;
          this.formData = { ...this.$options.data().formData };
          this.mostrarAlerta('success', 'Datos eliminados correctamente');
        } catch (error) {
          console.error('Error eliminando datos:', error);
          this.mostrarAlerta('error', 'Error al eliminar los datos: ' + error.message);
        }
      }
    },

    irAMiembros() {
      this.$router.push(`/miembros-hogar?visita_id=${this.visitaId}`);
    },

    irASeccion(seccion) {
      if (!this.visitaId) {
        alert('Error: No se encontró el ID de la visita');
        return;
      }

      switch(seccion) {
        case 'inicio':
          window.location.href = `/visitas_social/showSocial/${this.visitaId}`;
          break;
        case 'miembros':
          this.$router.push({
            path: '/miembros-hogar',
            query: { visita_id: this.visitaId }
          });
          break;
        case 'predio':
          this.$router.push({
            path: '/datos-predio', 
            query: { visita_id: this.visitaId }
          });
          break;
        default:
          console.warn('Sección no reconocida:', seccion);
      }
    },

    async sincronizar() {
      if (!this.canSync) {
        this.mostrarAlerta('warning', 'No hay conexión a internet para sincronizar');
        return;
      }
      
      if (!this.datosGuardados) {
        this.mostrarAlerta('warning', 'No hay datos para sincronizar');
        return;
      }

      try {
        this.mostrarAlerta('info', '🔄 Sincronizando datos...');
        setTimeout(() => {
          this.mostrarAlerta('success', '✅ Datos sincronizados correctamente');
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
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    this.cargarInformacionVisita();
    await this.cargarDatosGuardados();

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

.saved-data-card .card {
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
.saved-data-card .card-body {
  color: white;
}
</style>
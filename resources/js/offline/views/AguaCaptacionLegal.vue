<template>
  <div class="offline-container offline-form-container">
    <h2 class="offline-title">💧 Agua — Captación Legal (Modo Offline)</h2><br><br>
    
    <!-- INFORMACIÓN DE LA VISITA - Mismo diseño que Áreas -->
    <div v-if="visitaInfo" class="visita-info-card mb-4">
      <div class="card visita-card-theme">
        <div class="card-header visita-header">
          <div class="d-flex align-items-center">
            <div class="visita-icon">
              <i class="fas fa-tint"></i>
            </div>
            <div class="visita-title">
              <h4 class="mb-0">💧 Captación Legal de Agua</h4>
              <small class="visita-subtitle">Componente ambiental - Visita #{{ visitaId }}</small>
            </div>
          </div>
          <div class="visita-status" :class="getStatusClass(visitaInfo.estado)">
            {{ getEstadoText(visitaInfo.estado) }}
          </div>
        </div>
        
        <div class="card-body visita-body">
          <div class="row">
            <div class="col-md-6">
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="info-content">
                  <label>Fecha de Visita</label>
                  <p class="info-value">{{ formatDate(visitaInfo.fecha_visita) }}</p>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-user-tie"></i>
                </div>
                <div class="info-content">
                  <label>Proveedor</label>
                  <p class="info-value">{{ visitaInfo.proveedor.proveedor_nombre || 'No especificado' }}</p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-tractor"></i>
                </div>
                <div class="info-content">
                  <label>Plantación</label>
                  <p class="info-value">{{ visitaInfo.plantacion.nombre || 'No especificado' }}</p>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
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

    <!-- SELECTOR DE SECCIONES -->
    <div class="selector-seccion mb-4">
      <div class="mb-3">
        <label for="componente" class="form-label fw-bold text-success">
          <i class="fas fa-map-signs me-2"></i>Seleccione un componente:
        </label>
        <div class="input-group input-group-lg">
          <select v-model="componenteSeleccionado" class="form-select">
            <option value="datos-personales">🏠 Inicio de Visita</option>
            <option value="agua-captacion-legal">💧 Agua - Captación Legal</option>
            <option value="agua-uso-eficiente">🚰 Agua - Uso Eficiente</option>
            <option value="SueloConservacion">🌱 Suelo - Conservación</option>
            <option value="energia-uso-eficiente">⚡ Energía</option>
            <option value="gobernanza-hidrica">🤝 Gobernanza Hídrica</option>
            <option value="emisiones-gei">🏭 Emisiones GEI</option>
            <option value="residuos-manejo">🗑️ Residuos - Manejo</option>
            <option value="sustancias-manejo">🧪 Sustancias - Manejo</option>
            <option value="vertimientos-manejo">💦 Vertimientos - Manejo</option>
            <option value="hmp-manejo">☣️ HMP - Manejo</option>
            <option value="avc-control">🛡️ AVC - Control</option>
            <option value="ecosistema-proteccion">🌳 Ecosistema - Protección</option>
            <option value="noremplazo-nodeforestacion">🚫 No Deforestación</option>
            <option value="cierre-visita">✅ Cierre de Visita</option>
          </select>
          <button @click="irAComponente" class="btn btn-success" 
                  style="border-radius: 0 10px 10px 0;">
            <i class="fas fa-arrow-right me-2"></i>Ir
          </button>
        </div>
      </div>
    </div>

    <!-- SI EL COMPONENTE YA EXISTE, MOSTRAR RESUMEN -->
    <div v-if="componenteGuardado" class="card-component mb-4">
      <div class="card-header-green">Componente registrado</div>
      <div class="p-3">
        <p><strong>Permiso concesión:</strong> {{ componenteGuardado.permiso_concesion ? 'Sí' : 'No' }}</p>
        <p><strong>Permiso ocupación cauce:</strong> {{ componenteGuardado.permiso_ocupacion_cauce ? 'Sí' : 'No' }}</p>
        <p><strong>Permisos captación:</strong> {{ componenteGuardado.permisos_captacion ? 'Sí' : 'No' }}</p>
        <p><strong>Registro agua:</strong> {{ componenteGuardado.registro_agua ? 'Sí' : 'No' }}</p>
        <p><strong>Cumple manejo/construcción:</strong> {{ componenteGuardado.cumple_manejo_construccion ? 'Sí' : 'No' }}</p>
        <p><strong>Gestión permiso ocupación:</strong> {{ componenteGuardado.gestion_permiso_ocupacion ? 'Sí' : 'No' }}</p>
        <p><strong>Gestión permiso captación:</strong> {{ componenteGuardado.gestion_permiso_captacion ? 'Sí' : 'No' }}</p>
        
        <div v-if="componenteGuardado.observaciones" class="mt-3">
          <p><strong>Observaciones:</strong> {{ componenteGuardado.observaciones }}</p>
        </div>

        <div class="btn-group-top">
          <button @click="habilitarEdicion" class="btn btn-warning">✏️ Editar componente</button>
          <button @click="irAInicioVisita" class="btn btn-secondary">⬅️ Volver a la visita</button>
        </div>
      </div>
    </div>

    <!-- FORMULARIO DE CREACIÓN/EDICIÓN -->
    <div v-else class="card-component">
      <div class="card-header-green">
        {{ modoEdicion ? '✏️ Editar — Agua: Captación Legal' : '💧 Registrar — Agua: Captación Legal' }}
      </div>
      
      <div class="p-3">
        <!-- Mensajes de alerta -->
        <div v-if="mensajeExito" class="alert alert-success alert-dismissible fade show">
          {{ mensajeExito }}
          <button type="button" class="btn-close" @click="mensajeExito = ''"></button>
        </div>
        
        <div v-if="mensajeError" class="alert alert-danger alert-dismissible fade show">
          {{ mensajeError }}
          <button type="button" class="btn-close" @click="mensajeError = ''"></button>
        </div>
        
        <div v-if="errores.length > 0" class="alert alert-danger">
          <h5 class="alert-heading">Errores de validación</h5>
          <ul class="mb-0">
            <li v-for="error in errores" :key="error">{{ error }}</li>
          </ul>
        </div>

        <form @submit.prevent="guardarComponente">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">Cuenta con el permiso de concesión actualizado de acuerdo al volumen requerido</label>
                <select v-model="formulario.permiso_concesion" class="form-select">
                  <option value="">Seleccione...</option>
                  <option value="1">Sí</option>
                  <option value="0">No</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">Cuenta con el permiso de ocupación de cauce</label>
                <select v-model="formulario.permiso_ocupacion_cauce" class="form-select">
                  <option value="">Seleccione...</option>
                  <option value="1">Sí</option>
                  <option value="0">No</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">Cuenta con los permisos que autoricen la(s) captación(es)</label>
                <select v-model="formulario.permisos_captacion" class="form-select">
                  <option value="">Seleccione...</option>
                  <option value="1">Sí</option>
                  <option value="0">No</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">Cuenta con un registro de agua que evidencie el cumplimiento del volumen concesionado</label>
                <select v-model="formulario.registro_agua" class="form-select">
                  <option value="">Seleccione...</option>
                  <option value="1">Sí</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label fw-bold">Cumple con la obligación de manejo, construcción y distribución requerida en el permiso</label>
                <select v-model="formulario.cumple_manejo_construccion" class="form-select">
                  <option value="">Seleccione...</option>
                  <option value="1">Sí</option>
                  <option value="0">No</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">Ha realizado la gestión para la obtención de permisos de ocupación de cauce</label>
                <select v-model="formulario.gestion_permiso_ocupacion" class="form-select">
                  <option value="">Seleccione...</option>
                  <option value="1">Sí</option>
                  <option value="0">No</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">Ha realizado la gestión para la obtención de permisos que autoricen la(s) captación(es)</label>
                <select v-model="formulario.gestion_permiso_captacion" class="form-select">
                  <option value="">Seleccione...</option>
                  <option value="1">Sí</option>
                  <option value="0">No</option>
                </select>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold">Observaciones</label>
            <textarea v-model="formulario.observaciones" rows="3" class="form-control"></textarea>
          </div>

          <div class="d-flex justify-content-between">
            <button type="button" @click="irAInicioVisita" class="btn btn-secondary">⬅️ Volver</button>
            <button type="submit" class="btn btn-success" :disabled="!formularioValido">
              {{ modoEdicion ? 'Actualizar componente' : 'Guardar componente' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
// Importar las mismas funciones que usas en Áreas
import { saveFormData, getAllDataFromStore, deleteDataFromStore, getFormDataByVisita } from '../store/indexeddb';

export default {
  data() {
    return {
      visitaId: null,
      visitaInfo: null,
      componenteGuardado: null,
      componenteSeleccionado: 'agua_captacion_legal',
      modoEdicion: false,
      mensajeExito: '',
      mensajeError: '',
      errores: [],
      
      formulario: {
        local_id: '',
        permiso_concesion: '',
        permiso_ocupacion_cauce: '',
        permisos_captacion: '',
        registro_agua: '',
        cumple_manejo_construccion: '',
        gestion_permiso_ocupacion: '',
        gestion_permiso_captacion: '',
        observaciones: ''
      }
    };
  },
  
  computed: {
    formularioValido() {
      // Validar que al menos un campo esté completo
      return Object.values(this.formulario).some(val => 
        val !== '' && val !== null && val !== undefined
      );
    }
  },
  
  methods: {
    // ============ MÉTODOS PARA INFORMACIÓN DE VISITA ============
    irAComponente() {
      if (!this.componenteSeleccionado) return;

      this.$router.push({
        path: `/${this.componenteSeleccionado}`,
        query: { visita_id: this.visitaId }
      });
    },

    async cargarInformacionVisita() {
      try {
        console.log('Cargando información de visita para ID:', this.visitaId);
        
        // Intentar cargar desde localStorage primero (igual que en Áreas)
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
          this.visitaInfo = visita.formData || visita;
          console.log('Visita cargada desde IndexedDB:', this.visitaInfo);
          
          // Guardar en localStorage para próximas cargas
          localStorage.setItem('visita_offline_' + this.visitaId, JSON.stringify(this.visitaInfo));
        } else {
          console.warn('No se encontró información de la visita con ID:', this.visitaId);
          this.visitaInfo = {
            proveedor_nombre: 'No disponible (modo offline)',
            plantacion_nombre: 'No disponible (modo offline)',
            ubicacion: 'No disponible',
            estado: 'en_progreso'
          };
        }
      } catch (error) {
        console.error('Error cargando información de la visita:', error);
        this.visitaInfo = {
          proveedor_nombre: 'Error al cargar',
          plantacion_nombre: 'Error al cargar',
          ubicacion: 'Error al cargar',
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
    
    getStatusClass(estado) {
      const statusClasses = {
        'completada': 'status-completed',
        'en_progreso': 'status-in-progress',
        'pendiente': 'status-pending'
      };
      return statusClasses[estado] || 'status-in-progress';
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
    
    // ============ MÉTODOS PARA EL COMPONENTE ============
    async cargarComponenteGuardado() {
      try {
        // Buscar en IndexedDB si ya existe este componente para esta visita
        const todosComponentes = await getAllDataFromStore('agua_captacion_legal');
        
        const componente = todosComponentes.find(comp => 
          comp.visita_id == this.visitaId || 
          comp.visita_id == this.visitaInfo?.id
        );
        
        if (componente) {
          this.componenteGuardado = componente;
          console.log('Componente cargado desde IndexedDB:', componente);
        } else {
          this.componenteGuardado = null;
        }
      } catch (error) {
        console.error('Error cargando componente guardado:', error);
        this.componenteGuardado = null;
      }
    },
    
    habilitarEdicion() {
      if (this.componenteGuardado) {
        this.modoEdicion = true;
        this.formulario = { ...this.componenteGuardado };
        this.componenteGuardado = null;
        
        // Hacer scroll al formulario
        setTimeout(() => {
          const elemento = document.querySelector('.card-component');
          if (elemento) {
            elemento.scrollIntoView({ behavior: 'smooth' });
          }
        }, 100);
        
        this.mensajeExito = 'Componente cargado para edición. Modifica los valores y guarda los cambios.';
      }
    },
    
    async guardarComponente() {
      try {
        // Validar formulario
        this.errores = [];
        
        // Puedes agregar validaciones específicas aquí si necesitas
        if (!this.formulario.permiso_concesion) {
          this.errores.push('Debe seleccionar si cuenta con permiso de concesión');
        }
        
        if (this.errores.length > 0) {
          this.mensajeError = 'Hay errores en el formulario. Por favor revíselos.';
          return;
        }
        
        // Generar ID si es nuevo
        if (!this.formulario.local_id) {
          this.formulario.local_id = this.generateUUID();
        }
        
        // Agregar metadatos (igual que en Áreas)
        const datosParaGuardar = {
          ...this.formulario,

          proveedor_nombre: this.visitaInfo.proveedor_nombre,
          nombre: this.visitaInfo.nombre, // plantación
          fecha_visita: this.visitaInfo.fecha_visita,

          proveedor_id: this.visitaInfo.proveedor_id ?? null,
          plantacion_id: this.visitaInfo.plantacion_id ?? null,
          tecnico_id: this.visitaInfo.tecnico_id ?? null,

          visita_id: this.visitaId,
          fecha_guardado: new Date().toISOString(),
          tipo_componente: 'agua_captacion_legal'
        
        };
        
        // Convertir valores booleanos/numéricos
        Object.keys(datosParaGuardar).forEach(key => {
          if (datosParaGuardar[key] === '1') datosParaGuardar[key] = true;
          if (datosParaGuardar[key] === '0') datosParaGuardar[key] = false;
        });
        
        // Guardar en IndexedDB
        await saveFormData('agua_captacion_legal', datosParaGuardar);
        
        // Actualizar estado
        this.modoEdicion = false;
        this.componenteGuardado = datosParaGuardar;
        
        // Limpiar formulario
        this.formulario = {
          local_id: '',
          permiso_concesion: '',
          permiso_ocupacion_cauce: '',
          permisos_captacion: '',
          registro_agua: '',
          cumple_manejo_construccion: '',
          gestion_permiso_ocupacion: '',
          gestion_permiso_captacion: '',
          observaciones: ''
        };
        
        this.mensajeExito = `✅ Componente ${this.modoEdicion ? 'actualizado' : 'guardado'} correctamente`;
        
        // Redirigir automáticamente después de guardar
        setTimeout(() => {
          this.irASiguienteComponente();
        }, 1500);
        
      } catch (error) {
        console.error('Error guardando componente:', error);
        this.mensajeError = 'Error al guardar el componente: ' + error.message;
      }
    },
    
    // ============ NAVEGACIÓN ============
      irASiguienteComponente() {
        const orden = [
          'agua-captacion-legal',
          'agua-uso-eficiente',
          'suelo',
          'energia-manejo',
          'cierre-visita'
        ];

        const actual = 'agua-captacion-legal';
        const index = orden.indexOf(actual);

        if (index === -1) return;

        const siguiente = orden[index + 1];

        if (siguiente) {
          this.$router.push({
            path: `/${siguiente}`,
            query: { visita_id: this.visitaId }
          });
        }
      },

    
    
    
    irAInicioVisita() {
      this.$router.push(`/offline/visita/${this.visitaId}`);
    },
    
    // ============ UTILIDADES ============
    generateUUID() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
    },

  
  },

  

  
  async mounted() {
    // Obtener ID de visita de la URL o localStorage (igual que en Áreas)
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || 
                    localStorage.getItem('visita_id');
    
    if (this.visitaId) {
      localStorage.setItem('visita_id', this.visitaId);
      await this.cargarInformacionVisita();
      await this.cargarComponenteGuardado();
    } else {
      this.mensajeError = 'No se encontró ID de visita. Regrese a la página principal.';
    }
  }
};
</script>

<style scoped>
@import '../styles/offline.css';

/* ESTILOS ESPECÍFICOS PARA ESTE COMPONENTE */
.offline-form-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

.offline-title {
  background: linear-gradient(135deg, #0d6efd, #0dcaf0);
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
  background: linear-gradient(135deg, #0d6efd, #0dcaf0);
  border-radius: 2px;
}

.card-component {
  border: 1px solid #d5e6d5;
  border-radius: 10px;
  padding: 18px;
  margin-bottom: 18px;
  background: #f1f6f1;
}

.card-header-green {
  background: linear-gradient(45deg,#28a745,#20c997);
  color: white;
  padding: 12px 16px;
  border-radius: 8px 8px 0 0;
  margin: -18px -18px 12px -18px;
  font-weight: 700;
}

.selector-seccion {
  margin-bottom: 20px;
}

.btn-group-top {
  display: flex;
  gap: 10px;
  margin-top: 12px;
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


/* ESTILOS PARA LA TARJETA DE VISITA (igual que en Áreas) */
.visita-card-theme {
  border: none;
  border-radius: 15px;
  box-shadow: 0 8px 25px rgba(13, 110, 253, 0.15);
  overflow: hidden;
  background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%);
}

.visita-header {
  background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
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
  background: rgba(13, 110, 253, 0.05);
  border-radius: 10px;
  border-left: 4px solid #0d6efd;
  transition: all 0.3s ease;
}

.info-item:hover {
  background: rgba(13, 110, 253, 0.1);
  transform: translateX(5px);
}

.info-icon {
  font-size: 1.3rem;
  color: #0d6efd;
  margin-right: 15px;
  padding: 8px;
  background: rgba(13, 110, 253, 0.1);
  border-radius: 8px;
  min-width: 45px;
  text-align: center;
}

.info-content label {
  font-weight: 600;
  color: #0d6efd;
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
  background: rgba(13, 110, 253, 0.08);
  border-top: 1px solid rgba(13, 110, 253, 0.2);
  padding: 15px 25px;
  font-size: 0.85rem;
}

.visita-id {
  background: rgba(13, 110, 253, 0.1);
  padding: 4px 12px;
  border-radius: 12px;
  color: #0d6efd;
  font-weight: 600;
  font-size: 0.8rem;
}
</style>
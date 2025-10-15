<template>
  <div class="offline-container offline-form-container">
    <!-- Header con información de la visita -->
    <div class="visita-info-card mb-4" style="margin-top: 50px;">
      <div class="card-header bg-gradient-info text-white">
        <h4 class="mb-0 text-center">
          <i class="fas fa-users me-2"></i>
          Miembros del Hogar - Visita Social (Modo Offline)
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

    <!-- Datos Personales Guardados -->
    <div v-if="datosPersonales" class="datos-personales-card mb-4">
      <div class="card">
        <div class="card-header bg-success text-white text-center">
          <h5 class="mb-0">
            <i class="fas fa-user-check me-2"></i>Datos Personales del Productor
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <p><strong class="text-white">Teléfono:</strong> <span class="text-white">{{ datosPersonales.telefono }}</span></p>
              <p><strong class="text-white">Sexo:</strong> <span class="text-white">{{ datosPersonales.sexo }}</span></p>
              <p><strong class="text-white">Nivel Estudio:</strong> <span class="text-white">{{ datosPersonales.nivel_estudio }}</span></p>
            </div>
            <div class="col-md-4">
              <p><strong class="text-white">RNP:</strong> <span class="text-white">{{ datosPersonales.rnp }}</span></p>
              <p><strong class="text-white">Fedepalma:</strong> <span class="text-white">{{ datosPersonales.fedepalma }}</span></p>
              <p><strong class="text-white">Grupo Poblacional:</strong> <span class="text-white">{{ datosPersonales.grupo_poblacional }}</span></p>
            </div>
            <div class="col-md-4">
              <p><strong class="text-white">Años Palmicultura:</strong> <span class="text-white">{{ datosPersonales.anios_palmicultura }}</span></p>
              <p><strong class="text-white">Reside Predio:</strong> <span class="text-white">{{ datosPersonales.reside_predio }}</span></p>
              <p><strong class="text-white">Red Social:</strong> <span class="text-white">{{ datosPersonales.red_social }}</span></p>
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
            <button @click="irASeccion('miembros')" class="btn btn-outline-info btn-sm active">
              👨‍👩‍👧‍👦 Miembros Hogar
            </button>
            <button @click="irASeccion('predio')" class="btn btn-outline-warning btn-sm">
              🏡 Datos Predio
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Lista de Miembros Guardados -->
    <div v-if="miembrosGuardados.length > 0" class="miembros-list-card mb-4">
      <div class="card">
        <div class="card-header bg-primary text-white text-center">
          <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Miembros del Hogar Guardados ({{ miembrosGuardados.length }})
          </h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="text-black">Nombre</th>
                  <th class="text-black">Documento</th>
                  <th class="text-black">Sexo</th>
                  <th class="text-black">Parentezco</th>
                  <th class="text-black">Reside</th>
                  <th class="text-black">Estudio</th>
                  <th class="text-black">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="miembro in miembrosGuardados" :key="miembro.local_id">
                  <td class="text-black">{{ miembro.nombre }}</td>
                  <td class="text-black">{{ miembro.documento }}</td>
                  <td class="text-black">{{ miembro.sexo }}</td>
                  <td class="text-black">{{ miembro.parentezco }}</td>
                  <td class="text-black">{{ miembro.reside_predio ? 'Sí' : 'No' }}</td>
                  <td class="text-black">{{ miembro.nivel_estudio }}</td>
                  <td>
                    <button @click="editarMiembro(miembro)" class="btn btn-sm btn-warning me-1">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button @click="eliminarMiembro(miembro.local_id)" class="btn btn-sm btn-danger">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Formulario de Miembros del Hogar -->
    <div class="form-card">
      <div class="card-header bg-white text-center">
        <h5 class="mb-0 text-primary">
          <i class="fas fa-user-plus me-2"></i>
          {{ miembroEditando ? 'Editar Miembro' : 'Agregar Nuevo Miembro' }}
        </h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="guardarMiembro">
          <div class="row">
            <!-- Nombre -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-user me-2"></i>Nombres y Apellidos
              </label>
              <input type="text" v-model="formData.nombre" class="form-control" 
                     placeholder="Digite nombres y apellidos completos" required>
            </div>

            <!-- Documento -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-id-card me-2"></i>Número de Documento
              </label>
              <input type="text" v-model="formData.documento" class="form-control" 
                     placeholder="Digite el número de documento" required>
            </div>
          </div>

          <div class="row">
            <!-- Sexo -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-venus-mars me-2"></i>Sexo
              </label>
              <select v-model="formData.sexo" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="Mujer">Mujer</option>
                <option value="Hombre">Hombre</option>
                <option value="No se identifica">No se identifica con ninguno</option>
              </select>
            </div>

            <!-- Parentezco -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-family me-2"></i>Parentezco con el productor/a
              </label>
              <select v-model="formData.parentezco" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="Hijo/a">Hijo/a</option>
                <option value="Hijastro/a">Hijastro/a</option>
                <option value="Mamá">Mamá</option>
                <option value="Papá">Papá</option>
                <option value="Hermano/a">Hermana/o</option>
                <option value="Esposo/a">Esposo/a</option>
                <option value="Abuelo/a">Abuelo/a</option>
                <option value="Nieto/a">Nieto/a</option>
                <option value="Otro">Otro</option>
              </select>
            </div>
          </div>

          <div class="row">
            <!-- Reside en predio -->
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-home me-2"></i>¿Reside en el predio?
              </label>
              <select v-model="formData.reside_predio" class="form-select" required>
                <option value="">Seleccione...</option>
                <option :value="true">Sí</option>
                <option :value="false">No</option>
              </select>
            </div>

            <!-- Sabe leer -->
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-book me-2"></i>¿Sabe leer y/o escribir?
              </label>
              <select v-model="formData.sabe_leer" class="form-select" required>
                <option value="">Seleccione...</option>
                <option :value="true">Sí</option>
                <option :value="false">No</option>
              </select>
            </div>

            <!-- Participa en labores -->
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-tractor me-2"></i>¿Participa en labores del cultivo?
              </label>
              <select v-model="formData.participa_labores" class="form-select" required>
                <option value="">Seleccione...</option>
                <option :value="true">Sí</option>
                <option :value="false">No</option>
              </select>
            </div>
          </div>

          <div class="row">
            <!-- Nivel de estudio -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-graduation-cap me-2"></i>Nivel de estudio
              </label>
              <select v-model="formData.nivel_estudio" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="Ninguno">Ninguno</option>
                <option value="Primaria">Primaria</option>
                <option value="Bachillerato">Bachillerato</option>
                <option value="Técnico">Técnico</option>
                <option value="Tecnólogo">Tecnólogo</option>
                <option value="Profesional">Profesional</option>
                <option value="Maestría">Maestría</option>
                <option value="Doctorado">Doctorado</option>
              </select>
            </div>

            <!-- Edad -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold text-white">
                <i class="fas fa-birthday-cake me-2"></i>Edad
              </label>
              <input type="number" v-model="formData.edad" class="form-control" 
                     placeholder="Edad del miembro" min="0" max="120">
            </div>
          </div>

          <!-- Observaciones -->
          <div class="mb-3">
            <label class="form-label fw-bold text-white">
              <i class="fas fa-sticky-note me-2"></i>Observaciones
            </label>
            <textarea v-model="formData.observaciones" class="form-control" 
                      placeholder="Observaciones adicionales..." rows="3"></textarea>
          </div>

          <!-- Botones de acción -->
          <div class="button-group mt-4">
            <button type="submit" class="btn btn-success btn-lg" :disabled="!formValido">
              <i class="fas fa-save me-2"></i>
              {{ miembroEditando ? 'Actualizar Miembro' : 'Guardar Miembro' }}
            </button>
            
            <button v-if="miembroEditando" 
                    @click="cancelarEdicion" 
                    type="button" 
                    class="btn btn-secondary btn-lg">
              <i class="fas fa-times me-2"></i>Cancelar
            </button>
            
            <button type="button" class="btn btn-info btn-lg" @click="irAPredio">
              <i class="fas fa-arrow-right me-2"></i>Siguiente: Datos Predio
            </button>
            
            <button v-if="canSync && miembrosGuardados.length > 0" 
                    @click="sincronizar" 
                    class="btn btn-warning btn-lg">
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
        nombre: '',
        documento: '',
        sexo: '',
        parentezco: '',
        reside_predio: '',
        sabe_leer: '',
        nivel_estudio: '',
        participa_labores: '',
        edad: '',
        observaciones: ''
      },
      miembrosGuardados: [],
      datosPersonales: null,
      miembroEditando: null,
      visitaId: null,
      proveedorNombre: 'Cargando...',
      canSync: navigator.onLine,
      fechaActual: new Date().toLocaleDateString()
    };
  },
  computed: {
    formValido() {
      return this.formData.nombre && 
             this.formData.documento && 
             this.formData.sexo && 
             this.formData.parentezco &&
             this.formData.reside_predio !== '' &&
             this.formData.sabe_leer !== '' &&
             this.formData.nivel_estudio &&
             this.formData.participa_labores !== '';
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

    async cargarMiembrosGuardados() {
      try {
        const todosLosMiembros = await getAllDataFromStore('miembros_hogar_social');
        this.miembrosGuardados = todosLosMiembros.filter(miembro => 
          miembro.visita_id == this.visitaId
        );
      } catch (error) {
        console.error('Error cargando miembros:', error);
      }
    },

    async guardarMiembro() {
      try {
        const datosCompletos = {
          ...this.formData,
          local_id: this.miembroEditando ? this.miembroEditando.local_id : this.generateUUID(),
          visita_id: this.visitaId,
          proveedor_nombre: this.proveedorNombre,
          timestamp: new Date().toISOString(),
          sincronizado: false
        };

        await saveFormData('miembros_hogar_social', datosCompletos);
        
        if (this.miembroEditando) {
          this.mostrarAlerta('success', '✅ Miembro actualizado correctamente');
        } else {
          this.mostrarAlerta('success', '✅ Miembro guardado correctamente en modo offline');
        }
        
        await this.cargarMiembrosGuardados();
        this.limpiarFormulario();
        
      } catch (error) {
        console.error('Error guardando miembro:', error);
        this.mostrarAlerta('error', 'Error al guardar el miembro: ' + error.message);
      }
    },

    async eliminarMiembro(localId) {
      if (confirm('¿Está seguro de eliminar este miembro?')) {
        try {
          await deleteDataFromStore('miembros_hogar_social', localId);
          await this.cargarMiembrosGuardados();
          this.mostrarAlerta('success', '✅ Miembro eliminado correctamente');
        } catch (error) {
          console.error('Error eliminando miembro:', error);
          this.mostrarAlerta('error', 'Error al eliminar el miembro: ' + error.message);
        }
      }
    },

    editarMiembro(miembro) {
      this.miembroEditando = miembro;
      this.formData = { ...miembro };
      // Scroll to form
      document.querySelector('.form-card').scrollIntoView({ behavior: 'smooth' });
    },

    cancelarEdicion() {
      this.miembroEditando = null;
      this.limpiarFormulario();
    },

    limpiarFormulario() {
      this.formData = {
        nombre: '',
        documento: '',
        sexo: '',
        parentezco: '',
        reside_predio: '',
        sabe_leer: '',
        nivel_estudio: '',
        participa_labores: '',
        edad: '',
        observaciones: ''
      };
      this.miembroEditando = null;
    },

    irAPredio() {
      this.$router.push(`/datos-predio?visita_id=${this.visitaId}`);
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
          // Ya estamos aquí
          break;
        case 'predio':
          this.$router.push(`/datos-predio?visita_id=${this.visitaId}`);
          break;
      }
    },

    async sincronizar() {
      if (!this.canSync) {
        this.mostrarAlerta('warning', 'No hay conexión a internet para sincronizar');
        return;
      }
      
      if (this.miembrosGuardados.length === 0) {
        this.mostrarAlerta('warning', 'No hay miembros para sincronizar');
        return;
      }

      try {
        this.mostrarAlerta('info', '🔄 Sincronizando miembros del hogar...');
        
        // Aquí iría la lógica de sincronización con tu backend
        // Ejemplo:
        // const response = await fetch('/api/sync-miembros-hogar', {
        //   method: 'POST',
        //   headers: { 'Content-Type': 'application/json' },
        //   body: JSON.stringify({ 
        //     visita_id: this.visitaId,
        //     miembros: this.miembrosGuardados 
        //   })
        // });
        
        // Simulamos sincronización
        setTimeout(() => {
          this.mostrarAlerta('success', `✅ ${this.miembrosGuardados.length} miembros sincronizados correctamente`);
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
    console.log('🔍 MiembrosHogarForm - URL completa:', window.location.href);
    console.log('🔍 MiembrosHogarForm - Query params:', new URLSearchParams(window.location.search).toString());
    console.log('🔍 MiembrosHogarForm - Visita ID del localStorage:', localStorage.getItem('visita_id'));
    const urlParams = new URLSearchParams(window.location.search);
  
    const storedVisitaId = localStorage.getItem('visita_id');
     this.visitaId = urlParams.get('visita_id') || storedVisitaId;
  
      if (!this.visitaId) {
        console.error('No se encontró visita_id');
        alert('Error: No se pudo identificar la visita. Regrese al inicio.');
        return;
      }
      
      // Guardar en localStorage para persistencia
      localStorage.setItem('visita_id', this.visitaId);
      
      console.log('Visita ID cargado:', this.visitaId); // Para debug

      this.cargarInformacionVisita();
      await this.cargarDatosPersonales();
      await this.cargarMiembrosGuardados();

      window.addEventListener('online', this.updateOnlineStatus);
      window.addEventListener('offline', this.updateOnlineStatus);
  
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    this.cargarInformacionVisita();
    await this.cargarDatosPersonales();
    await this.cargarMiembrosGuardados();

    window.addEventListener('online', this.updateOnlineStatus);
    window.addEventListener('offline', this.updateOnlineStatus);
  },
  beforeUnmount() {
    window.removeEventListener('online', this.updateOnlineStatus);
    window.removeEventListener('offline', this.updateOnlineStatus);
  },

  
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

.datos-personales-card .card {
  border: 2px solid #28a745;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
}

.miembros-list-card .card {
  border: 2px solid #007bff;
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

  .miembros-list-card .table-responsive {
    font-size: 0.875rem;
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
.datos-personales-card .card-body,
.miembros-list-card .card-body {
  color: white;
}

.miembros-list-card table {
  margin-bottom: 0;
}

.miembros-list-card th {
  background-color: rgba(255, 255, 255, 0.1);
  font-weight: 600;
  color: white;
}

.navigation-card .btn.active {
  background-color: #007bff;
  color: white;
  border-color: #007bff;
}
</style>
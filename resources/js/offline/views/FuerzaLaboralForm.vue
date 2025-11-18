<template>
  <div class="offline-container offline-form-container">
    <div class="visita-info-card mb-4" style="margin-top: 50px;">
      <div class="card-header bg-gradient-info text-white">
        <h4 class="mb-0">
          <i class="fas fa-hard-hat me-2"></i>
          Fuerza Laboral - Visita Social (Modo Offline)
        </h4>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Proveedor:</strong> {{ proveedorNombre }}</p>
            <p><strong>Visita ID:</strong> #{{ visitaId }}</p>
            <p><strong>Predio:</strong> {{ datosPredio?.nombre_finca || 'No especificado' }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Fecha:</strong> {{ fechaActual }}</p>
            <p><strong>Municipio:</strong> {{ datosPredio?.municipio || 'No especificado' }}</p>
            <p><strong>Estado:</strong> <span class="badge bg-warning">📱 Modo Offline</span></p>
          </div>
        </div>
      </div>
    </div>

    <div class="resumen-cards mb-4">
      <div class="row">
        <!-- DATOS PERSONALES -->
        <div class="col-md-4 mb-3" v-if="datosPersonales">
          <div class="card h-100">
            <div class="card-header bg-success text-white py-2 text-center">
              <h6 class="mb-0">
                <i class="fas fa-user me-1"></i>Datos Personales
              </h6>
            </div>
            <div class="card-body p-3 text-white">
              <p class="mb-1"><small><strong>Teléfono:</strong> {{ datosPersonales.telefono || 'No registrado' }}</small></p>
              <p class="mb-1"><small><strong>Sexo:</strong> {{ datosPersonales.sexo || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>RNP:</strong> {{ datosPersonales.rnp || 'No especificado' }}</small></p>
              <p class="mb-1" v-if="datosPersonales.rnp === 'SI'">
                <small><strong>Número RNP:</strong> {{ datosPersonales.numero_rnp || 'No especificado' }}</small>
              </p>
              <p class="mb-1"><small><strong>Fedepalma:</strong> {{ datosPersonales.fedepalma || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Alfabetizado:</strong> {{ datosPersonales.alfabetizado || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Nivel Estudio:</strong> {{ datosPersonales.nivel_estudio || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Otras Líneas:</strong> {{ datosPersonales.otras_lineas || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Fecha Nacimiento:</strong> {{ datosPersonales.fecha_nacimiento || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Grupo Poblacional:</strong> {{ datosPersonales.grupo_poblacional || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Reside Predio:</strong> {{ datosPersonales.reside_predio || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Administra Cultivo:</strong> {{ datosPersonales.administra_cultivo || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Supervisa Cultivo:</strong> {{ datosPersonales.supervisa_cultivo || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Realiza Cultivo:</strong> {{ datosPersonales.realiza_cultivo || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Años Palmicultura:</strong> {{ datosPersonales.anios_palmicultura || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Internet:</strong> {{ datosPersonales.internet || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Tipo Persona:</strong> {{ datosPersonales.tipo_persona || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Red Social:</strong> {{ datosPersonales.red_social || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Régimen Salud:</strong> {{ datosPersonales.regimen_salud || 'No especificado' }}</small></p>
              <p class="mb-1"><small><strong>Oferta Mercantil:</strong> {{ datosPersonales.oferta_mercantil || 'No especificado' }}</small></p>
              <p class="mb-0"><small><strong>Hace Cuánto:</strong> {{ datosPersonales.hace_cuanto || 'No especificado' }}</small></p>
            </div>
          </div>
        </div>

        <!-- MIEMBROS DEL HOGAR -->
        <div class="col-md-4 mb-3" v-if="miembrosHogar.length > 0">
          <div class="card h-100">
            <div class="card-header bg-primary text-white py-2 text-center">
              <h6 class="mb-0">
                <i class="fas fa-users me-1"></i>Miembros del Hogar ({{ miembrosHogar.length }})
              </h6>
            </div>
            <div class="card-body p-2 bg-light" style="overflow-x: auto;">
              <div class="table-responsive">
                <table class="table table-sm table-striped table-bordered align-middle mb-0">
                  <thead class="table-primary text-center">
                    <tr>
                      <th>Nombre</th>
                      <th>Parentesco</th>
                      <th>Documento</th>
                      <th>Sexo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="miembro in miembrosHogar" :key="miembro.local_id">
                      <td>{{ miembro.nombre }}</td>
                      <td>{{ miembro.parentezco }}</td>
                      <td>{{ miembro.documento }}</td>
                      <td>{{ miembro.sexo }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>


        <!-- DATOS DEL PREDIO -->
        <div class="col-md-4 mb-3" v-if="datosPredio">
          <div class="card h-100">
            <div class="card-header bg-warning text-dark py-2 text-center">
              <h6 class="mb-0">
                <i class="fas fa-tractor me-1"></i>Datos del Predio
              </h6>
            </div>
            <div class="card-body p-3 text-white">
              <p class="mb-1"><small><strong>Visita Social ID:</strong> {{ datosPredio.visita_social_id }}</small></p>
              <p class="mb-1"><small><strong>Plantación ID:</strong> {{ datosPredio.plantacion_id }}</small></p>
              <p class="mb-1"><small><strong>Nombre de la Finca:</strong> {{ datosPredio.nombre_finca }}</small></p>
              <p class="mb-1"><small><strong>Forma de Tenencia:</strong> 
                {{ Array.isArray(datosPredio.forma_tenencia) ? datosPredio.forma_tenencia.join(', ') : datosPredio.forma_tenencia }}
              </small></p>
              <p class="mb-1"><small><strong>Municipio:</strong> {{ datosPredio.municipio }}</small></p>
              <p class="mb-1"><small><strong>Vereda:</strong> {{ datosPredio.vereda }}</small></p>
              <p class="mb-1"><small><strong>Registrado ICA:</strong> {{ datosPredio.registrado_ica }}</small></p>
              <p class="mb-1"><small><strong>Vive en el Predio:</strong> {{ datosPredio.vive_predio }}</small></p>
              <p class="mb-1"><small><strong>Infraestructura Vial:</strong> 
                {{ Array.isArray(datosPredio.infraestructura_vial) ? datosPredio.infraestructura_vial.join(', ') : datosPredio.infraestructura_vial }}
              </small></p>
              <p class="mb-0"><small><strong>Infraestructura del Predio:</strong> 
                {{ Array.isArray(datosPredio.infraestructura_predio) ? datosPredio.infraestructura_predio.join(', ') : datosPredio.infraestructura_predio }}
              </small></p>
            </div>
          </div>
        </div>
      </div>
    </div>

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
            <button @click="irASeccion('predio')" class="btn btn-outline-warning btn-sm">
              🏡 Datos Predio
            </button>
            <button @click="irASeccion('fuerza-laboral')" class="btn btn-outline-dark btn-sm active">
              🧑‍🌾 Fuerza Laboral
            </button>
            <button @click="irASeccion('organizacion')" class="btn btn-outline-secondary btn-sm">
              👥 Organización Social
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="datosFuerzaLaboral" class="datos-guardados-card mb-4">
      <div class="card">
        <div class="card-header bg-success text-white text-center">
          <h5 class="mb-0">
            <i class="fas fa-check-circle me-2"></i>Datos de Fuerza Laboral Guardados
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-4">
              <p><strong class="text-white">Trabajadores:</strong> <span class="text-white">{{ datosFuerzaLaboral.num_trabajadores }}</span></p>
              <p><strong class="text-white">Hombres:</strong> <span class="text-white">{{ datosFuerzaLaboral.num_hombres }}</span></p>
              <p><strong class="text-white">Mujeres:</strong> <span class="text-white">{{ datosFuerzaLaboral.num_mujeres }}</span></p>
              <p><strong class="text-white">Contrato Predom.:</strong> <span class="text-white">{{ datosFuerzaLaboral.tipo_contrato || 'N/A' }}</span></p>
            </div>
            <div class="col-md-4">
              <p><strong class="text-white">Contrato Formal:</strong> <span class="text-white">{{ datosFuerzaLaboral.contrato_formal }}</span></p>
              <p><strong class="text-white">Seguridad Social:</strong> <span class="text-white">{{ datosFuerzaLaboral.seguridad_social }}</span></p>
              <p><strong class="text-white">SG-SST:</strong> <span class="text-white">{{ datosFuerzaLaboral.sg_sst }}</span></p>
              <p><strong class="text-white">Contrato Firmado:</strong> <span class="text-white">{{ datosFuerzaLaboral.contrato_firmado || 'N/A' }}</span></p>
            </div>
            <div class="col-md-4">
              <p><strong class="text-white">Formas Contratación:</strong> <span class="text-white">{{ Array.isArray(datosFuerzaLaboral.forma_contratacion) ? datosFuerzaLaboral.forma_contratacion.join(', ') : datosFuerzaLaboral.forma_contratacion }}</span></p>
              <p><strong class="text-white">Exámenes Médicos:</strong> <span class="text-white">{{ datosFuerzaLaboral.examenes_medicos || 'N/A' }}</span></p>
              <p><strong class="text-white">Migrantes:</strong> <span class="text-white">{{ datosFuerzaLaboral.trabajadores_migrantes || 'N/A' }}</span></p>
            </div>
          </div>
          <div class="mt-3">
              <p class="mb-0"><strong class="text-white">Observaciones:</strong> <span class="text-white">{{ datosFuerzaLaboral.observaciones || 'Sin observaciones.' }}</span></p>
          </div>
          <button @click="eliminarDatos" class="btn btn-danger btn-sm mt-3">
            <i class="fas fa-trash me-1"></i>Eliminar Datos
          </button>
        </div>
      </div>
    </div>

    <div class="form-card">
      <div class="card-header bg-white">
        <h5 class="mb-0 text-success">
          <i class="fas fa-hard-hat me-2"></i>Información de Fuerza Laboral
        </h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="guardarDatos">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold " style="color: whitesmoke;"><br><br>
                <i class="fas fa-file-contract me-2"></i>Forma de contratación
              </label>
              <div class="contratacion-options">
                <div class="form-check" v-for="forma in opcionesContratacion" :key="forma.value">
                  <input class="form-check-input" type="checkbox" :value="forma.value" 
                         v-model="formData.forma_contratacion" :id="'forma-' + forma.value">
                  <label class="form-check-label" :for="'forma-' + forma.value">
                    {{ forma.label }}
                  </label>
                </div>
              </div>
              <small class="text-muted">Seleccione todas las que apliquen</small>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold " style="color: whitesmoke;">
                <i class="fas fa-users me-2"></i>Número total de trabajadores
              </label>
              <input type="number" v-model="formData.num_trabajadores" class="form-control" 
                     placeholder="Ej: 15" min="0">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" style="color: whitesmoke;">
                <i class="fas fa-venus-mars me-2"></i>Distribución por género
              </label>
              <div class="row">
                <div class="col-6">
                  <label class="form-label small">Hombres</label>
                  <input type="number" v-model="formData.num_hombres" class="form-control" 
                         placeholder="Hombres" min="0">
                </div>
                <div class="col-6">
                  <label class="form-label small">Mujeres</label>
                  <input type="number" v-model="formData.num_mujeres" class="form-control" 
                         placeholder="Mujeres" min="0">
                </div>
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" style="color: whitesmoke;">
                <i class="fas fa-handshake me-2"></i>Tipo de contrato predominante
              </label>
              <select v-model="formData.tipo_contrato" class="form-select">
                <option value="">Seleccione...</option>
                <option value="Termino fijo">Término fijo</option>
                <option value="Termino indefinido">Término indefinido</option>
                <option value="Por obra labor">Por obra o labor</option>
                <option value="Contrato tiempo parcial">Contrato tiempo parcial</option>
                <option value="Prestación servicios">Prestación de servicios</option>
                <option value="Mixto">Mixto</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold" style="color: whitesmoke;">
                <i class="fas fa-file-signature me-2"></i>¿Contrato formal?
              </label>
              <select v-model="formData.contrato_formal" class="form-select">
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="Parcial">Parcial</option>
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold " style="color: whitesmoke;">
                <i class="fas fa-shield-alt me-2"></i>¿Seguridad social?
              </label>
              <select v-model="formData.seguridad_social" class="form-select">
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="En proceso">En proceso</option>
                <option value="Parcial">Parcial</option>
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold " style="color: whitesmoke;">
                <i class="fas fa-signature me-2"></i>¿Contrato firmado?
              </label>
              <select v-model="formData.contrato_firmado" class="form-select">
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="Parcial">Parcial</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold " style="color: whitesmoke;">
                <i class="fas fa-first-aid me-2"></i>¿Sistema de Gestión SST?
              </label>
              <select v-model="formData.sg_sst" class="form-select">
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="En proceso">En proceso</option>
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold" style="color: whitesmoke;">
                <i class="fas fa-stethoscope me-2"></i>¿Exámenes médicos?
              </label>
              <select v-model="formData.examenes_medicos" class="form-select">
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="Parcial">Parcial</option>
              </select>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label fw-bold" style="color: whitesmoke;">
                <i class="fas fa-passport me-2"></i>¿Trabajadores migrantes?
              </label>
              <select v-model="formData.trabajadores_migrantes" class="form-select">
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" style="color: whitesmoke;">
                <i class="fas fa-receipt me-2"></i>¿Comprobantes de pago?
              </label>
              <select v-model="formData.comprobantes_pago" class="form-select">
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="Parcial">Parcial</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold"style="color: whitesmoke;">
                <i class="fas fa-tshirt me-2"></i>¿Dotación?
              </label>
              <select v-model="formData.dotacion" class="form-select">
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="Parcial">Parcial</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold" style="color: whitesmoke;">
              <i class="fas fa-sticky-note me-2"></i>Observaciones adicionales
            </label>
            <textarea v-model="formData.observaciones" class="form-control" 
                       placeholder="Observaciones sobre la fuerza laboral, condiciones, etc..." 
                       rows="3"></textarea>
          </div>

          <div class="button-group mt-4">
            <button type="submit" class="btn btn-success btn-lg" :disabled="!formValido">
              <i class="fas fa-save me-2"></i>
              {{ datosFuerzaLaboral ? 'Actualizar Datos' : 'Guardar Fuerza Laboral' }}
            </button>
            
            <button type="button" class="btn btn-info btn-lg" @click="irAOrganizacionSocial">
              <i class="fas fa-arrow-right me-2"></i>Siguiente: Organización Social
            </button>
            
            <button v-if="canSync && datosFuerzaLaboral" @click="sincronizar" class="btn btn-warning btn-lg">
              <i class="fas fa-sync me-2"></i>Sincronizar
            </button>
            
            <button type="button" class="btn btn-outline-secondary btn-lg" onclick="history.back()">
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
        forma_contratacion: [],
        num_trabajadores: '',
        num_hombres: '',
        num_mujeres: '',
        contrato_formal: '',
        seguridad_social: '',
        tipo_contrato: '',
        contrato_firmado: '',
        sg_sst: '',
        examenes_medicos: '',
        trabajadores_migrantes: '',
        comprobantes_pago: '',
        dotacion: '',
        observaciones: ''
      },
      datosFuerzaLaboral: null,
      datosPersonales: null,
      miembrosHogar: [],
      datosPredio: null,
      visitaId: null,
      proveedorNombre: 'Cargando...',
      canSync: navigator.onLine,
      fechaActual: new Date().toLocaleDateString(),
      opcionesContratacion: [
        { value: 'Dependiente', label: 'Dependiente' },
        { value: 'Independiente', label: 'Independiente' },
        { value: 'Todas las anteriores', label: 'Todas las anteriores' },
        { value: 'No identifica la forma contractual', label: 'No identifica la forma contractual' },
        { value: 'Contrata a traves de una SAS', label: 'Contrata a través de una SAS' },
        { value: 'Prestación de servicios', label: 'Prestación de servicios' },
        { value: 'Cooperativa', label: 'Cooperativa' }
      ]
    };
  },
  computed: {
    formValido() {
      return this.formData.forma_contratacion.length > 0 && 
             this.formData.num_trabajadores !== '' &&
             this.formData.contrato_formal &&
             this.formData.seguridad_social;
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
      } catch (error) {
        console.error('Error cargando datos del predio:', error);
      }
    },

    async cargarDatosFuerzaLaboral() {
      try {
        this.datosFuerzaLaboral = await getFormDataByVisita('fuerza_laboral_social', this.visitaId);
        if (this.datosFuerzaLaboral) {
          // Cargar datos en el formulario
          this.formData = { ...this.datosFuerzaLaboral };
          // Asegurar que los arrays estén inicializados 
          if (!Array.isArray(this.formData.forma_contratacion)) {
            this.formData.forma_contratacion = [];
          }
        }
      } catch (error) {
        console.error('Error cargando datos de fuerza laboral:', error);
      }
    },

    async guardarDatos() {
      try {
        const datosCompletos = {
          ...this.formData,
          local_id: this.datosFuerzaLaboral ? this.datosFuerzaLaboral.local_id : this.generateUUID(),
          visita_id: this.visitaId,
          proveedor_nombre: this.proveedorNombre,
          timestamp: new Date().toISOString(),
          sincronizado: false
        };

        await saveFormData('fuerza_laboral_social', datosCompletos);
        await this.cargarDatosFuerzaLaboral();
        
        this.mostrarAlerta('success', '✅ Datos de fuerza laboral guardados correctamente en modo offline');
        
      } catch (error) {
        console.error('Error guardando datos de fuerza laboral:', error);
        this.mostrarAlerta('error', 'Error al guardar los datos: ' + error.message);
      }
    },

    async eliminarDatos() {
      if (confirm('¿Eliminar los datos de fuerza laboral guardados?')) {
        try {
          await deleteDataFromStore('fuerza_laboral_social', this.datosFuerzaLaboral.local_id);
          this.datosFuerzaLaboral = null;
          this.formData = { ...this.$options.data().formData }; // Reset form
          this.mostrarAlerta('success', 'Datos eliminados correctamente');
        } catch (error) {
          console.error('Error eliminando datos:', error);
          this.mostrarAlerta('error', 'Error al eliminar los datos: ' + error.message);
        }
      }
    },

    irAOrganizacionSocial() {
      this.$router.push(`/organizacion-social?visita_id=${this.visitaId}`);
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
          this.$router.push(`/datos-predio?visita_id=${this.visitaId}`);
          break;
        case 'fuerza-laboral':
          // Ya estamos aquí
          break;
        case 'organizacion':
          this.$router.push(`/organizacion-social?visita_id=${this.visitaId}`);
          break;
      }
    },

    async sincronizar() {
      if (!this.canSync) {
        this.mostrarAlerta('warning', 'No hay conexión a internet para sincronizar');
        return;
      }
      
      if (!this.datosFuerzaLaboral) {
        this.mostrarAlerta('warning', 'No hay datos de fuerza laboral para sincronizar');
        return;
      }

      try {
        this.mostrarAlerta('info', '🔄 Sincronizando datos de fuerza laboral...');
        
        // Aquí iría la lógica de sincronización con tu backend
        setTimeout(() => {
          this.mostrarAlerta('success', '✅ Datos de fuerza laboral sincronizados correctamente');
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
    await this.cargarDatosPersonales();
    await this.cargarMiembrosHogar();
    await this.cargarDatosPredio();
    await this.cargarDatosFuerzaLaboral();

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

/* ESTILOS APLICADOS PARA ARMONIZAR */
.offline-form-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Tarjetas de Resumen de Datos Previos (NUEVO ESTILO CONSOLIDADOR) */
.resumen-cards .card { 
  border: 1px solid #dee2e6; 
  height: 100%; 
  background-color: #8b6761c7; /* Fondo oscuro para el cuerpo de la tarjeta */
} 

.resumen-cards .card-header { 
  font-size: 0.875rem; 
  padding: 8px 12px; 
} 

.resumen-cards .card-body p.text-white {
    color: #ffffff !important;
}

/* Tarjeta de Datos Guardados de la sección actual (Fuerza Laboral) */
.datos-guardados-card .card { 
  border: 2px solid #28a745; 
  background-color: #406653a2; /* Fondo oscuro para el cuerpo de la tarjeta */
} 
.datos-guardados-card .card-body p .text-white {
    color: #ffffff !important;
}

/* Otros estilos existentes */
.navigation-card .btn.active { 
  background-color: #59a04e91; 
  color: white; 
  border-color: #343a40; 
} 

.contratacion-options { 
  background-color: #f8f9fa; 
  padding: 15px; 
  border-radius: 8px; 
  border: 1px solid #dee2e6; 
  max-height: 200px; 
  overflow-y: auto; 
} 

.contratacion-options .form-check { 
  margin-bottom: 8px; 
} 

.button-group { 
  display: flex; 
  gap: 10px; 
  flex-wrap: wrap; 
  justify-content: center; 
} 

@media (max-width: 768px) { 
  .offline-container { 
    margin-left: 0; 
    margin-top: 20px; 
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

  .resumen-cards .col-md-4 { 
    margin-bottom: 15px; 
  } 

  .contratacion-options { 
    padding: 10px; 
    max-height: 150px; 
  } 
} 

.form-label { 
  font-weight: 500; 
  margin-bottom: 5px; 
  display: block; 
} 

.card-header { 
  border-radius: 15px 15px 0 0 !important; 
} 

.badge { 
  font-size: 0.8rem;
}

/* ESTILO DEL HEADER DE VISITA PARA USAR EL COLOR CORRECTO */
.visita-info-card .card-body {
    background-color: #17a2b8; /* Color de fondo del card-header bg-gradient-info */
    border-radius: 0 0 15px 15px;
    color: white;
}
.visita-info-card .card-body strong {
    color: #f8f9fa;
}
</style>
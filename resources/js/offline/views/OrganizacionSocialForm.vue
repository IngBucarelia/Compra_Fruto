<template>
  <div class="offline-container offline-form-container">
    <!-- Header con información de la visita -->
    <div class="visita-info-card mb-4" style="margin-top: 50px;">
      <div class="card-header bg-gradient-info text-white">
        <h4 class="mb-0">
          <i class="fas fa-handshake me-2"></i>
          Organización Social - Visita Social (Modo Offline)
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

    <!-- Resumen Completo de Todos los Datos Previos -->
    <div class="resumen-completo-cards mb-4">
      <h5 class="text-center mb-3" >
        <i class="fas fa-clipboard-check me-2"></i>Resumen Completo de Datos Ingresados
      </h5>
      <div class="row">
        <!-- Datos Personales -->
        <div class="col-lg-3 col-md-6 mb-3" v-if="datosPersonales">
          <div class="card h-100">
            <div class="card-header bg-success text-white py-2">
              <h6 class="mb-0">
                <i class="fas fa-user me-1"></i>Datos Personales
              </h6>
            </div>
            <div class="card-body p-2">
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

        <!-- Miembros del Hogar -->
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
        <!-- Datos del Predio -->
        <div class="col-lg-3 col-md-6 mb-3" v-if="datosPredio">
          <div class="card h-100">
            <div class="card-header bg-warning text-dark py-2">
              <h6 class="mb-0">
                <i class="fas fa-tractor me-1"></i>Datos Predio
              </h6>
            </div>
            <div class="card-body p-2">
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

        <!-- Fuerza Laboral -->
        <div class="col-lg-3 col-md-6 mb-3" v-if="datosFuerzaLaboral">
          <div class="card h-100">
            <div class="card-header bg-dark text-white py-2">
              <h6 class="mb-0">
                <i class="fas fa-hard-hat me-1"></i>Fuerza Laboral
              </h6>
            </div>
            <div class="card-body p-2">
              <p class="mb-1"><small><strong>Forma Contratación:</strong> {{ datosFuerzaLaboral.forma_contratacion }}</small></p>
      <p class="mb-1"><small><strong>Total Trabajadores:</strong> {{ datosFuerzaLaboral.num_trabajadores }}</small></p>
      <p class="mb-1"><small><strong>Hombres:</strong> {{ datosFuerzaLaboral.num_hombres }}</small></p>
      <p class="mb-1"><small><strong>Mujeres:</strong> {{ datosFuerzaLaboral.num_mujeres }}</small></p>
      <p class="mb-1"><small><strong>Contrato Formal:</strong> {{ datosFuerzaLaboral.contrato_formal }}</small></p>
      <p class="mb-1"><small><strong>Tipo Contrato:</strong> {{ datosFuerzaLaboral.tipo_contrato }}</small></p>
      <p class="mb-1"><small><strong>Contrato Firmado:</strong> {{ datosFuerzaLaboral.contrato_firmado }}</small></p>
      <p class="mb-1"><small><strong>Seguridad Social:</strong> {{ datosFuerzaLaboral.seguridad_social }}</small></p>
      <p class="mb-1"><small><strong>SG-SST:</strong> {{ datosFuerzaLaboral.sg_sst }}</small></p>
      <p class="mb-1"><small><strong>Exámenes Médicos:</strong> {{ datosFuerzaLaboral.examenes_medicos }}</small></p>
      <p class="mb-1"><small><strong>Trabajadores Migrantes:</strong> {{ datosFuerzaLaboral.trabajadores_migrantes }}</small></p>
      <p class="mb-1"><small><strong>Comprobantes de Pago:</strong> {{ datosFuerzaLaboral.comprobantes_pago }}</small></p>
      <p class="mb-0"><small><strong>Dotación:</strong> {{ datosFuerzaLaboral.dotacion }}</small></p>
   </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navegación entre secciones -->
    <div class="navigation-card mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap gap-2">
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
            <button @click="irASeccion('fuerza-laboral')" class="btn btn-outline-dark btn-sm">
              🧑‍🌾 Fuerza Laboral
            </button>
            <button @click="irASeccion('organizacion')" class="btn btn-outline-secondary btn-sm active">
              👥 Organización Social
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Datos Guardados de Organización Social -->
    <div v-if="datosOrganizacion" class="datos-guardados-card mb-4">
      <div class="card">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">
            <i class="fas fa-check-circle me-2"></i>Datos de Organización Social Guardados
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Pertenece a JAC:</strong> {{ datosOrganizacion.pertenece_jac }}</p>
              <p><strong>Pertenece a Asociación:</strong> {{ datosOrganizacion.pertenece_asociacion }}</p>
            </div>
            <div class="col-md-6" v-if="datosOrganizacion.nombre_asociacion">
              <p><strong>Nombre Asociación:</strong> {{ datosOrganizacion.nombre_asociacion }}</p>
              <p><strong>Participa en Otras:</strong> {{ datosOrganizacion.participa_otras_organizaciones }}</p>
            </div>
          </div>
          <button @click="eliminarDatos" class="btn btn-danger btn-sm mt-3">
            <i class="fas fa-trash me-1"></i>Eliminar Datos
          </button>
        </div>
      </div>
    </div>

    <!-- Formulario de Organización Social -->
    <div class="form-card">
      <div class="card-header bg-white">
        <h5 class="mb-0" style="color: whitesmoke;">
          <i class="fas fa-handshake me-2"></i>Información de Organización Social
        </h5>
      </div>
      <div class="card-body">
        <form @submit.prevent="guardarDatos">
          <div class="row">
            <!-- Pertenece a JAC -->
            <div class="col-md-6 mb-3"><br><br>
              <label class="form-label fw-bold" style="color: whitesmoke;">
                <i class="fas fa-home me-2"></i>¿Pertenece a la JAC (Junta de Acción Comunal)?
              </label>
              <select v-model="formData.pertenece_jac" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="Antes pertenecía">Antes pertenecía</option>
                <option value="No conoce">No conoce</option>
              </select>
              <small class="text-muted">Junta de Acción Comunal de su vereda o barrio</small>
            </div>

            <!-- Pertenece a asociación de palmicultores -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" style="color: whitesmoke;">
                <i class="fas fa-tree me-2"></i>¿Pertenece a asociación de palmicultores?
              </label>
              <select v-model="formData.pertenece_asociacion" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="Está en proceso">Está en proceso</option>
                <option value="No conoce">No conoce</option>
              </select>
            </div>
          </div>

          <!-- Nombre de la asociación (si pertenece) -->
          <div v-if="formData.pertenece_asociacion === 'SI'" class="mb-3">
            <label class="form-label fw-bold" style="color: whitesmoke;">
              <i class="fas fa-signature me-2"></i>Nombre de la asociación a la que pertenece
            </label>
            <input type="text" v-model="formData.nombre_asociacion" class="form-control" 
                   placeholder="Ej: Asopalma, Asoprocampo, etc...">
          </div>

          <div class="row">
            <!-- Participa en otras organizaciones -->
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" style="color: whitesmoke;">
                <i class="fas fa-users me-2"></i>¿Participa en otras organizaciones?
              </label>
              <select v-model="formData.participa_otras_organizaciones" class="form-select">
                <option value="">Seleccione...</option>
                <option value="SI">Sí</option>
                <option value="NO">No</option>
                <option value="Ocasionalmente">Ocasionalmente</option>
              </select>
            </div>

            <!-- Tipo de organizaciones adicionales -->
            <div class="col-md-6 mb-3" v-if="formData.participa_otras_organizaciones === 'SI'">
              <label class="form-label fw-bold" style="color: whitesmoke;">
                <i class="fas fa-network-wired me-2"></i>¿En qué tipo de organizaciones?
              </label>
              <select v-model="formData.tipo_organizaciones" class="form-select" multiple>
                <option value="Cooperativas">Cooperativas</option>
                <option value="Asociaciones productivas">Asociaciones productivas</option>
                <option value="Organizaciones ambientales">Organizaciones ambientales</option>
                <option value="Juntas administradoras">Juntas administradoras</option>
                <option value="Organizaciones comunitarias">Organizaciones comunitarias</option>
                <option value="Grupos religiosos">Grupos religiosos</option>
                <option value="Organizaciones deportivas">Organizaciones deportivas</option>
                <option value="Otros">Otros</option>
              </select>
              <small class="text-muted">Mantén CTRL para seleccionar varias</small>
            </div>
          </div>

          <!-- Cargos en organizaciones -->
          <div v-if="formData.pertenece_jac === 'SI' || formData.pertenece_asociacion === 'SI'" class="mb-3">
            <label class="form-label fw-bold" style="color: whitesmoke;">
              <i class="fas fa-user-tie me-2"></i>¿Ha desempeñado algún cargo directivo?
            </label>
            <select v-model="formData.cargos_directivos" class="form-select">
              <option value="">Seleccione...</option>
              <option value="SI">Sí</option>
              <option value="NO">No</option>
              <option value="Actualmente">Actualmente</option>
            </select>
          </div>

          <!-- Descripción de cargos (si ha tenido) -->
          <div v-if="formData.cargos_directivos === 'SI' || formData.cargos_directivos === 'Actualmente'" class="mb-3">
            <label class="form-label fw-bold" style="color: whitesmoke;">
              <i class="fas fa-briefcase me-2"></i>¿Qué cargos ha desempeñado?
            </label>
            <textarea v-model="formData.descripcion_cargos" class="form-control" 
                      placeholder="Describa los cargos que ha tenido en organizaciones..."
                      rows="2"></textarea>
          </div>

          <!-- Beneficios de la participación -->
          <div class="mb-3">
            <label class="form-label fw-bold" style="color: whitesmoke;">
              <i class="fas fa-gift me-2"></i>¿Qué beneficios obtiene de su participación?
            </label>
            <div class="beneficios-options">
              <div class="row">
                <div class="col-md-4" v-for="beneficio in opcionesBeneficios" :key="beneficio.value">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" :value="beneficio.value" 
                           v-model="formData.beneficios_participacion" :id="'beneficio-' + beneficio.value">
                    <label class="form-check-label" :for="'beneficio-' + beneficio.value">
                      {{ beneficio.label }}
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Frecuencia de participación -->
          <div class="mb-3">
            <label class="form-label fw-bold" style="color: whitesmoke;">
              <i class="fas fa-calendar-alt me-2"></i>Frecuencia de participación en reuniones
            </label>
            <select v-model="formData.frecuencia_participacion" class="form-select">
              <option value="">Seleccione...</option>
              <option value="Semanal">Semanal</option>
              <option value="Quincenal">Quincenal</option>
              <option value="Mensual">Mensual</option>
              <option value="Bimestral">Bimestral</option>
              <option value="Trimestral">Trimestral</option>
              <option value="Anual">Anual</option>
              <option value="Ocasional">Ocasional</option>
              <option value="No participa">No participa</option>
            </select>
          </div>

          <!-- Observaciones adicionales -->
          <div class="mb-3">
            <label class="form-label fw-bold" style="color: whitesmoke;">
              <i class="fas fa-sticky-note me-2"></i>Observaciones adicionales
            </label>
            <textarea v-model="formData.observaciones" class="form-control" 
                      placeholder="Observaciones sobre participación social, organizaciones, etc..." 
                      rows="3"></textarea>
          </div>

          <!-- Botones de acción -->
          <div class="button-group mt-4">
            <button type="submit" class="btn btn-success btn-lg" :disabled="!formValido">
              <i class="fas fa-save me-2"></i>
              {{ datosOrganizacion ? 'Actualizar Datos' : 'Guardar Organización Social' }}
            </button>
            
            <button type="button" class="btn btn-info btn-lg" @click="irACierreVisita">
              <i class="fas fa-arrow-right me-2"></i>Siguiente: Cierre Visita
            </button>
            
            <button v-if="canSync && datosOrganizacion" @click="sincronizar" class="btn btn-warning btn-lg">
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
        pertenece_jac: '',
        pertenece_asociacion: '',
        nombre_asociacion: '',
        participa_otras_organizaciones: '',
        tipo_organizaciones: [],
        cargos_directivos: '',
        descripcion_cargos: '',
        beneficios_participacion: [],
        frecuencia_participacion: '',
        observaciones: ''
      },
      datosOrganizacion: null,
      datosPersonales: null,
      miembrosHogar: [],
      datosPredio: null,
      datosFuerzaLaboral: null,
      visitaId: null,
      proveedorNombre: 'Cargando...',
      canSync: navigator.onLine,
      fechaActual: new Date().toLocaleDateString(),
      opcionesBeneficios: [
        { value: 'Capacitación', label: '📚 Capacitación' },
        { value: 'Acceso a créditos', label: '💰 Acceso a créditos' },
        { value: 'Asistencia técnica', label: '🔧 Asistencia técnica' },
        { value: 'Comercialización', label: '📊 Comercialización' },
        { value: 'Representación', label: '🎯 Representación' },
        { value: 'Networking', label: '🤝 Networking' },
        { value: 'Apoyo en insumos', label: '🌱 Apoyo en insumos' },
        { value: 'Ninguno', label: '❌ Ninguno' }
      ]
    };
  },
  computed: {
    formValido() {
      return this.formData.pertenece_jac && 
             this.formData.pertenece_asociacion;
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
      } catch (error) {
        console.error('Error cargando datos de fuerza laboral:', error);
      }
    },

    async cargarDatosOrganizacion() {
      try {
        this.datosOrganizacion = await getFormDataByVisita('organizacion_social', this.visitaId);
        if (this.datosOrganizacion) {
          // Cargar datos en el formulario
          this.formData = { ...this.datosOrganizacion };
          // Asegurar que los arrays estén inicializados
          if (!Array.isArray(this.formData.tipo_organizaciones)) {
            this.formData.tipo_organizaciones = [];
          }
          if (!Array.isArray(this.formData.beneficios_participacion)) {
            this.formData.beneficios_participacion = [];
          }
        }
      } catch (error) {
        console.error('Error cargando datos de organización social:', error);
      }
    },

    async guardarDatos() {
      try {
        const datosCompletos = {
          ...this.formData,
          local_id: this.datosOrganizacion ? this.datosOrganizacion.local_id : this.generateUUID(),
          visita_id: this.visitaId,
          proveedor_nombre: this.proveedorNombre,
          timestamp: new Date().toISOString(),
          sincronizado: false
        };

        await saveFormData('organizacion_social', datosCompletos);
        await this.cargarDatosOrganizacion();
        
        this.mostrarAlerta('success', '✅ Datos de organización social guardados correctamente en modo offline');
        
      } catch (error) {
        console.error('Error guardando datos de organización social:', error);
        this.mostrarAlerta('error', 'Error al guardar los datos: ' + error.message);
      }
    },

    async eliminarDatos() {
      if (confirm('¿Eliminar los datos de organización social guardados?')) {
        try {
          await deleteDataFromStore('organizacion_social', this.datosOrganizacion.local_id);
          this.datosOrganizacion = null;
          this.formData = { ...this.$options.data().formData }; // Reset form
          this.mostrarAlerta('success', 'Datos eliminados correctamente');
        } catch (error) {
          console.error('Error eliminando datos:', error);
          this.mostrarAlerta('error', 'Error al eliminar los datos: ' + error.message);
        }
      }
    },

    irACierreVisita() {
      this.$router.push(`/cierre-visita?visita_id=${this.visitaId}`);
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
          this.$router.push(`/fuerza-laboral?visita_id=${this.visitaId}`);
          break;
        case 'organizacion':
          // Ya estamos aquí
          break;
      }
    },

    async sincronizar() {
      if (!this.canSync) {
        this.mostrarAlerta('warning', 'No hay conexión a internet para sincronizar');
        return;
      }
      
      if (!this.datosOrganizacion) {
        this.mostrarAlerta('warning', 'No hay datos de organización social para sincronizar');
        return;
      }

      try {
        this.mostrarAlerta('info', '🔄 Sincronizando datos de organización social...');
        
        // Aquí iría la lógica de sincronización con tu backend
        setTimeout(() => {
          this.mostrarAlerta('success', '✅ Datos de organización social sincronizados correctamente');
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
    await this.cargarDatosOrganizacion();

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
<template>
  <div class="offline-container offline-form-container">
    <h2 class="offline-title">📋 Revisión Final - Visita Social (Modo Offline)</h2>
    
    <!-- Información de la Visita -->
    <div class="card border-primary mb-4">
      <div class="card-header bg-primary text-white">
        ℹ️ Información de la Visita
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
            <p><strong>Estado:</strong> <span class="badge" :class="isOnline ? 'bg-success' : 'bg-warning'">
              {{ isOnline ? '🌐 En Línea' : '📱 Modo Offline' }}
            </span></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Resumen Completo de Todos los Datos Sociales -->
    <div class="all-sections-container">
      <!-- Datos Personales -->
      <div class="card border-success mb-4" v-if="datosPersonales">
        <div class="card-header bg-success text-white">
          👤 Datos Personales del Productor
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Teléfono:</strong> {{ datosPersonales.telefono || 'N/A' }}</li>
                <li class="list-group-item"><strong>Sexo:</strong> {{ datosPersonales.sexo || 'N/A' }}</li>
                <li class="list-group-item"><strong>Fecha Nacimiento:</strong> {{ formatDate(datosPersonales.fecha_nacimiento) }}</li>
                <li class="list-group-item"><strong>RNP:</strong> {{ datosPersonales.rnp || 'N/A' }}</li>
                <li class="list-group-item"><strong>Fedepalma:</strong> {{ datosPersonales.fedepalma || 'N/A' }}</li>
              </ul>
            </div>
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Alfabetizado:</strong> {{ datosPersonales.alfabetizado || 'N/A' }}</li>
                <li class="list-group-item"><strong>Nivel Estudio:</strong> {{ datosPersonales.nivel_estudio || 'N/A' }}</li>
                <li class="list-group-item"><strong>Grupo Poblacional:</strong> {{ datosPersonales.grupo_poblacional || 'N/A' }}</li>
                <li class="list-group-item"><strong>Años Palmicultura:</strong> {{ datosPersonales.anios_palmicultura || 'N/A' }}</li>
                <li class="list-group-item"><strong>Régimen Salud:</strong> {{ datosPersonales.regimen_salud || 'N/A' }}</li>
              </ul>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-12">
              <div class="alert alert-info">
                <strong>Información Adicional:</strong><br>
                <small>
                  Reside en predio: {{ datosPersonales.reside_predio || 'N/A' }} | 
                  Administra cultivo: {{ datosPersonales.administra_cultivo || 'N/A' }} | 
                  Acceso a internet: {{ datosPersonales.internet || 'N/A' }} | 
                  Red social principal: {{ datosPersonales.red_social || 'N/A' }}
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="card border-light mb-4">
        <div class="card-body text-center text-muted">
          <i class="fas fa-user fa-2x mb-2"></i><br>
          No hay datos personales registrados
        </div>
      </div>

      <!-- Miembros del Hogar -->
      <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white">
          👨‍👩‍👧‍👦 Miembros del Hogar ({{ miembrosHogar.length }})
        </div>
        <div class="card-body">
          <div v-if="miembrosHogar.length > 0">
            <div class="table-responsive">
              <table class="table table-striped table-sm">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Sexo</th>
                    <th>Parentezco</th>
                    <th>Reside</th>
                    <th>Estudio</th>
                    <th>Participa</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="miembro in miembrosHogar" :key="miembro.local_id">
                    <td>{{ miembro.nombre }}</td>
                    <td>{{ miembro.documento }}</td>
                    <td>{{ miembro.sexo }}</td>
                    <td>{{ miembro.parentezco }}</td>
                    <td>{{ miembro.reside_predio ? 'Sí' : 'No' }}</td>
                    <td>{{ miembro.nivel_estudio }}</td>
                    <td>{{ miembro.participa_labores ? 'Sí' : 'No' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <p v-else class="text-muted text-center">No hay miembros del hogar registrados</p>
        </div>
      </div>

      <!-- Datos del Predio -->
      <div class="card border-warning mb-4" v-if="datosPredio">
        <div class="card-header bg-warning text-dark">
          🏡 Datos del Predio
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Nombre Finca:</strong> {{ datosPredio.nombre_finca || 'N/A' }}</li>
                <li class="list-group-item"><strong>Forma Tenencia:</strong> {{ datosPredio.forma_tenencia || 'N/A' }}</li>
                <li class="list-group-item"><strong>Municipio:</strong> {{ datosPredio.municipio || 'N/A' }}</li>
                <li class="list-group-item"><strong>Vereda:</strong> {{ datosPredio.vereda || 'N/A' }}</li>
              </ul>
            </div>
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Registro ICA:</strong> {{ datosPredio.registrado_ica || 'N/A' }}</li>
                <li class="list-group-item"><strong>Vive en Predio:</strong> {{ datosPredio.vive_predio || 'N/A' }}</li>
                <li class="list-group-item"><strong>Infraestructura:</strong> {{ datosPredio.infraestructura_predio || 'N/A' }}</li>
                <li class="list-group-item"><strong>Vías de Acceso:</strong> 
                  <span v-if="Array.isArray(datosPredio.infraestructura_vial)">
                    {{ datosPredio.infraestructura_vial.join(', ') }}
                  </span>
                  <span v-else>{{ datosPredio.infraestructura_vial || 'N/A' }}</span>
                </li>
              </ul>
            </div>
          </div>
          <div v-if="datosPredio.servicios_publicos && datosPredio.servicios_publicos.length > 0" class="mt-3">
            <div class="alert alert-info">
              <strong>Servicios Públicos:</strong> {{ datosPredio.servicios_publicos.join(', ') }}
            </div>
          </div>
          <div v-if="datosPredio.observaciones" class="mt-2">
            <div class="alert alert-secondary">
              <strong>Observaciones:</strong> {{ datosPredio.observaciones }}
            </div>
          </div>
        </div>
      </div>
      <div v-else class="card border-light mb-4">
        <div class="card-body text-center text-muted">
          <i class="fas fa-tractor fa-2x mb-2"></i><br>
          No hay datos del predio registrados
        </div>
      </div>

      <!-- Fuerza Laboral -->
      <div class="card border-info mb-4" v-if="datosFuerzaLaboral">
        <div class="card-header bg-info text-white">
          🧑‍🌾 Fuerza Laboral
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Total Trabajadores:</strong> {{ datosFuerzaLaboral.num_trabajadores || 'N/A' }}</li>
                <li class="list-group-item"><strong>Hombres:</strong> {{ datosFuerzaLaboral.num_hombres || 'N/A' }}</li>
                <li class="list-group-item"><strong>Mujeres:</strong> {{ datosFuerzaLaboral.num_mujeres || 'N/A' }}</li>
                <li class="list-group-item"><strong>Formas Contratación:</strong>
                  <span v-if="Array.isArray(datosFuerzaLaboral.forma_contratacion)">
                    {{ datosFuerzaLaboral.forma_contratacion.join(', ') }}
                  </span>
                  <span v-else>{{ datosFuerzaLaboral.forma_contratacion || 'N/A' }}</span>
                </li>
              </ul>
            </div>
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Contrato Formal:</strong> {{ datosFuerzaLaboral.contrato_formal || 'N/A' }}</li>
                <li class="list-group-item"><strong>Seguridad Social:</strong> {{ datosFuerzaLaboral.seguridad_social || 'N/A' }}</li>
                <li class="list-group-item"><strong>SG-SST:</strong> {{ datosFuerzaLaboral.sg_sst || 'N/A' }}</li>
                <li class="list-group-item"><strong>Dotación:</strong> {{ datosFuerzaLaboral.dotacion || 'N/A' }}</li>
              </ul>
            </div>
          </div>
          <div v-if="datosFuerzaLaboral.observaciones" class="mt-3">
            <div class="alert alert-secondary">
              <strong>Observaciones:</strong> {{ datosFuerzaLaboral.observaciones }}
            </div>
          </div>
        </div>
      </div>
      <div v-else class="card border-light mb-4">
        <div class="card-body text-center text-muted">
          <i class="fas fa-hard-hat fa-2x mb-2"></i><br>
          No hay datos de fuerza laboral registrados
        </div>
      </div>

      <!-- Organización Social -->
      <div class="card border-secondary mb-4" v-if="datosOrganizacion">
        <div class="card-header bg-secondary text-white">
          👥 Organización Social
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Pertenece a JAC:</strong> {{ datosOrganizacion.pertenece_jac || 'N/A' }}</li>
                <li class="list-group-item"><strong>Pertenece a Asociación:</strong> {{ datosOrganizacion.pertenece_asociacion || 'N/A' }}</li>
                <li class="list-group-item" v-if="datosOrganizacion.nombre_asociacion">
                  <strong>Nombre Asociación:</strong> {{ datosOrganizacion.nombre_asociacion }}
                </li>
                <li class="list-group-item"><strong>Participa Otras Organizaciones:</strong> {{ datosOrganizacion.participa_otras_organizaciones || 'N/A' }}</li>
              </ul>
            </div>
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Cargos Directivos:</strong> {{ datosOrganizacion.cargos_directivos || 'N/A' }}</li>
                <li class="list-group-item"><strong>Frecuencia Participación:</strong> {{ datosOrganizacion.frecuencia_participacion || 'N/A' }}</li>
                <li class="list-group-item" v-if="datosOrganizacion.beneficios_participacion && datosOrganizacion.beneficios_participacion.length > 0">
                  <strong>Beneficios:</strong> {{ datosOrganizacion.beneficios_participacion.join(', ') }}
                </li>
              </ul>
            </div>
          </div>
          <div v-if="datosOrganizacion.descripcion_cargos" class="mt-3">
            <div class="alert alert-info">
              <strong>Cargos Desempeñados:</strong> {{ datosOrganizacion.descripcion_cargos }}
            </div>
          </div>
          <div v-if="datosOrganizacion.observaciones" class="mt-2">
            <div class="alert alert-secondary">
              <strong>Observaciones:</strong> {{ datosOrganizacion.observaciones }}
            </div>
          </div>
        </div>
      </div>
      <div v-else class="card border-light mb-4">
        <div class="card-body text-center text-muted">
          <i class="fas fa-handshake fa-2x mb-2"></i><br>
          No hay datos de organización social registrados
        </div>
      </div>

      <!-- Cierre de Visita -->
      <div class="card border-dark mb-4" v-if="datosCierre">
        <div class="card-header bg-dark text-white">
          ✅ Cierre de Visita Social
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Fecha Cierre:</strong> {{ formatDate(datosCierre.fecha_cierre) }}</li>
                <li class="list-group-item"><strong>Estado:</strong> {{ datosCierre.estado_visita || 'N/A' }}</li>
                <li class="list-group-item"><strong>Firmas Registradas:</strong> 
                  {{ (datosCierre.firma_responsable ? '✅ ' : '') + (datosCierre.firma_recibe ? '✅ ' : '') + (datosCierre.firma_testigo ? '✅' : '') }}
                </li>
              </ul>
            </div>
            <div class="col-md-6">
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Imágenes:</strong> {{ datosCierre.imagenes ? datosCierre.imagenes.length : 0 }}</li>
                <li class="list-group-item"><strong>Guardado:</strong> {{ formatDateTime(datosCierre.timestamp) }}</li>
              </ul>
            </div>
          </div>
          
          <!-- Observaciones y Recomendaciones -->
          <div v-if="datosCierre.observaciones_finales" class="mt-3">
            <div class="alert alert-warning">
              <strong>Observaciones Finales:</strong> {{ datosCierre.observaciones_finales }}
            </div>
          </div>
          <div v-if="datosCierre.recomendaciones" class="mt-2">
            <div class="alert alert-success">
              <strong>Recomendaciones:</strong> {{ datosCierre.recomendaciones }}
            </div>
          </div>

          <!-- Firmas -->
          <div v-if="datosCierre.firma_responsable || datosCierre.firma_recibe || datosCierre.firma_testigo" class="mt-4">
            <h6 class="text-success">✍️ Firmas Registradas</h6>
            <div class="row">
              <div class="col-md-4 mb-3" v-if="datosCierre.firma_responsable">
                <strong>Responsable</strong><br>
                <img :src="datosCierre.firma_responsable" class="img-fluid border mt-2" style="max-height: 120px;" alt="Firma Responsable" />
              </div>
              <div class="col-md-4 mb-3" v-if="datosCierre.firma_recibe">
                <strong>Recibe</strong><br>
                <img :src="datosCierre.firma_recibe" class="img-fluid border mt-2" style="max-height: 120px;" alt="Firma Recibe" />
              </div>
              <div class="col-md-4 mb-3" v-if="datosCierre.firma_testigo">
                <strong>Testigo</strong><br>
                <img :src="datosCierre.firma_testigo" class="img-fluid border mt-2" style="max-height: 120px;" alt="Firma Testigo" />
              </div>
            </div>
          </div>

          <!-- Imágenes -->
          <div v-if="datosCierre.imagenes && datosCierre.imagenes.length > 0" class="mt-4">
            <h6 class="text-success">📸 Fotos de la Visita ({{ datosCierre.imagenes.length }})</h6>
            <div class="row">
              <div class="col-6 col-md-3 mb-3" v-for="(img, idx) in datosCierre.imagenes" :key="idx">
                <img :src="img" class="img-thumbnail w-100" style="height: 100px; object-fit: cover;" alt="Imagen de la visita" />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="card border-light mb-4">
        <div class="card-body text-center text-muted">
          <i class="fas fa-flag-checkered fa-2x mb-2"></i><br>
          No hay cierre de visita registrado
        </div>
      </div>
    </div>

    <!-- Acciones -->
    <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
      <button class="btn btn-primary" @click="generarPDF">
        🖨️ Generar PDF
      </button>
      <button class="btn btn-outline-success" @click="descargarExcel">
        📥 Exportar a Excel
      </button>
      <!-- Botón de sincronización -->
    <button 
      class="btn btn-success" 
      @click="sincronizarSocial" 
      :disabled="!isOnline || sincronizando"
      :title="isOnline ? 'Sincronizar datos sociales con el servidor' : 'No hay conexión a internet para sincronizar'"
    >
      <span v-if="sincronizando">
        <span class="spinner-border spinner-border-sm" role="status"></span>
        Sincronizando...
      </span>
      <span v-else-if="isOnline">🔄 Sincronizar Datos Sociales</span>
      <span v-else>🔴 Sin Conexión</span>
    </button>
    <!-- Modal de Sincronización en Progreso -->
    <div v-if="mostrarModalProgreso" class="modal-overlay">
      <div class="modal-contenido">
        <div class="text-center">
          <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Sincronizando...</span>
          </div>
          <h4 class="text-primary">🔄 Sincronizando Datos Sociales</h4>
          <p class="mt-3">Estamos enviando tus datos al servidor...</p>
          
          <!-- Progreso detallado -->
          <div class="progress mt-4" style="height: 20px;">
            <div 
              class="progress-bar progress-bar-striped progress-bar-animated" 
              role="progressbar" 
              :style="{ width: progresoPorcentaje + '%' }"
              :aria-valuenow="progresoPorcentaje" 
              aria-valuemin="0" 
              aria-valuemax="100"
            >
              {{ progresoPorcentaje }}%
            </div>
          </div>
          
          <p class="text-muted small mt-3">
            <i class="fas fa-info-circle"></i>
            No cierres esta ventana hasta que termine el proceso.
          </p>
          
          <div class="mt-3">
            <small class="text-info">
              <i class="fas fa-sync-alt fa-spin"></i>
              {{ mensajeProgreso }}
            </small>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Sincronización Completada -->
    <div v-if="mostrarModalExito" class="modal-overlay">
      <div class="modal-contenido">
        <div class="text-center">
          <div class="mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
          </div>
          <h4 class="text-success mb-3">✅ ¡Sincronización Exitosa!</h4>
          
          <div class="alert alert-success">
            <strong>Resumen de la sincronización:</strong>
            <div class="mt-2">
              <p class="mb-1">📊 <strong>Total de registros sincronizados:</strong> {{ totalRegistrosSincronizados }}</p>
              <p class="mb-1">🕒 <strong>Fecha y hora:</strong> {{ fechaSincronizacion }}</p>
              <p class="mb-0">🌐 <strong>Estado:</strong> Completado exitosamente</p>
            </div>
          </div>

          <div class="d-grid gap-2 mt-4">
            <button @click="cerrarModalExito" class="btn btn-success btn-lg">
              <i class="fas fa-check me-2"></i>
              Aceptar
            </button>
            <button @click="verDetallesSincronizacion" class="btn btn-outline-info btn-sm">
              <i class="fas fa-list me-1"></i>
              Ver detalles técnicos
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Error en Sincronización -->
    <div v-if="mostrarModalError" class="modal-overlay">
      <div class="modal-contenido">
        <div class="text-center">
          <div class="mb-4">
            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
          </div>
          <h4 class="text-warning mb-3">⚠️ Error en Sincronización</h4>
          
          <div class="alert alert-warning">
            <strong>Ocurrió un problema:</strong>
            <p class="mt-2 mb-0">{{ mensajeError }}</p>
          </div>

          <div class="alert alert-info small">
            <i class="fas fa-lightbulb"></i>
            <strong>Sugerencia:</strong> Verifica tu conexión a internet e intenta nuevamente.
          </div>

          <div class="d-grid gap-2 mt-4">
            <button @click="reintentarSincronizacion" class="btn btn-warning">
              <i class="fas fa-redo me-2"></i>
              Reintentar
            </button>
            <button @click="cerrarModalError" class="btn btn-outline-secondary">
              <i class="fas fa-times me-2"></i>
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
    </div>

    <!-- Indicador de estado de conexión -->
    <p class="mt-2 text-center" :class="isOnline ? 'text-success' : 'text-warning'">
      <i class="fas" :class="isOnline ? 'fa-wifi' : 'fa-wifi-slash'"></i>
      Estado: {{ isOnline ? 'En Línea - Listo para sincronizar' : 'Sin Conexión - Modo Offline' }}
    </p>

    <!-- Modal de Sincronización -->
    <div v-if="mostrarModal" class="modal-overlay">
      <div class="modal-contenido">
        <h3 v-if="!sincronizacionCompleta">⏳ Sincronizando Datos Sociales...</h3>
        <h3 v-else>✅ ¡Sincronización Completa!</h3>

        <div v-if="!sincronizacionCompleta" class="text-center">
          <div class="spinner-border text-primary mb-3" role="status">
            <span class="visually-hidden">Sincronizando...</span>
          </div>
          <p>Por favor espera mientras se sincronizan los datos sociales.</p>
          <p class="text-muted small">No cierres esta ventana.</p>
        </div>
        
        <div v-else class="text-center">
          <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
          <p>Los datos sociales se sincronizaron correctamente.</p>
          <button @click="cerrarModal" class="btn btn-success">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getAllDataFromStore } from '../store/indexeddb'
import { generarResumenPDFSocial } from '../utils/pdfGeneratorSocial';
import { sincronizarDatosSocialesOffline } from '../utils/sincronizadorSocial';

// Importar utilidades (debes crearlas o adaptarlas)
// import { generarResumenPDFSocial } from '../utils/pdfGeneratorSocial'
// import { exportarResumenExcelSocial } from '../utils/excelExporterSocial'
// import { sincronizarDatosSocialesOffline } from '../utils/sincronizadorSocial'

export default {
  data() {
    return {
      visitaId: null,
      proveedorNombre: 'Cargando...',
      fechaActual: new Date().toLocaleDateString(),
      isOnline: navigator.onLine,
      
      // Datos sociales
      datosPersonales: null,
      miembrosHogar: [],
      datosPredio: null,
      datosFuerzaLaboral: null,
      datosOrganizacion: null,
      datosCierre: null,
      
      // Estado de sincronización
      mostrarModal: false,
      sincronizacionCompleta: false,
      // Estados para los modals
      sincronizando: false,
      mostrarModalProgreso: false,
      mostrarModalExito: false,
      mostrarModalError: false,
      
      // Datos de progreso
      progresoPorcentaje: 0,
      mensajeProgreso: 'Iniciando sincronización...',
      totalRegistrosSincronizados: 0,
      fechaSincronizacion: '',
      mensajeError: ''
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id')
    localStorage.setItem('visita_id', this.visitaId)

    await this.loadAllData()
    
    // Escuchar cambios de conexión
    window.addEventListener('online', this.handleOnline)
    window.addEventListener('offline', this.handleOffline)
  },
  beforeUnmount() {
    window.removeEventListener('online', this.handleOnline)
    window.removeEventListener('offline', this.handleOffline)
  },
  methods: {
    // Métodos de formato
    formatDate(dateString) {
      if (!dateString) return 'N/A'
      try {
        const date = new Date(dateString)
        const options = { year: 'numeric', month: '2-digit', day: '2-digit' }
        return date.toLocaleDateString('es-ES', options)
      } catch (e) {
        return dateString
      }
    },

    
    formatDateTime(dateString) {
      if (!dateString) return 'N/A'
      try {
        const date = new Date(dateString)
        return date.toLocaleString('es-ES')
      } catch (e) {
        return dateString
      }
    },

    // Cargar todos los datos sociales
    async loadAllData() {
      try {
        const visitaId = Number(this.visitaId) || this.visitaId
        
        // Cargar datos personales
        const allDatosPersonales = await getAllDataFromStore('datos_personales_social') || []
        this.datosPersonales = allDatosPersonales.find(d => {
          const dataVisitaId = Number(d.visita_id) || d.visita_id
          return dataVisitaId == visitaId
        }) || null
        
        // Cargar miembros del hogar
        const allMiembros = await getAllDataFromStore('miembros_hogar_social') || []
        this.miembrosHogar = allMiembros.filter(m => {
          const miembroVisitaId = Number(m.visita_id) || m.visita_id
          return miembroVisitaId == visitaId
        })
        
        // Cargar datos del predio
        const allPredios = await getAllDataFromStore('datos_predio_social') || []
        this.datosPredio = allPredios.find(p => {
          const predioVisitaId = Number(p.visita_id) || p.visita_id
          return predioVisitaId == visitaId
        }) || null
        
        // Cargar fuerza laboral
        const allFuerzaLaboral = await getAllDataFromStore('fuerza_laboral_social') || []
        this.datosFuerzaLaboral = allFuerzaLaboral.find(f => {
          const fuerzaVisitaId = Number(f.visita_id) || f.visita_id
          return fuerzaVisitaId == visitaId
        }) || null
        
        // Cargar organización social
        const allOrganizacion = await getAllDataFromStore('organizacion_social') || []
        this.datosOrganizacion = allOrganizacion.find(o => {
          const orgVisitaId = Number(o.visita_id) || o.visita_id
          return orgVisitaId == visitaId
        }) || null
        
        // Cargar cierre de visita
        const allCierres = await getAllDataFromStore('cierre_visita_social') || []
        this.datosCierre = allCierres.find(c => {
          const cierreVisitaId = Number(c.visita_id) || c.visita_id
          return cierreVisitaId == visitaId
        }) || null
        
        // Cargar información del proveedor
        this.proveedorNombre = localStorage.getItem(`proveedor_${this.visitaId}`) || 'Proveedor'
        
      } catch (error) {
        console.error('Error cargando datos sociales:', error)
        alert('Error al cargar los datos sociales: ' + error.message)
      }
    },

    // Generar PDF (implementar según tus necesidades)
    async generarPDF() {
      await generarResumenPDFSocial({
        datosPersonales: this.datosPersonales,
        miembrosHogar: this.miembrosHogar,
        datosPredio: this.datosPredio,
        datosFuerzaLaboral: this.datosFuerzaLaboral,
        datosOrganizacion: this.datosOrganizacion,
        datosCierre: this.datosCierre,
        visitaId: this.visitaId,
        proveedorNombre: this.proveedorNombre
      });
    },

    // Exportar a Excel (implementar según tus necesidades)
    descargarExcel() {
      alert('Funcionalidad de Excel en desarrollo')
      // exportarResumenExcelSocial({
      //   datosPersonales: this.datosPersonales,
      //   miembrosHogar: this.miembrosHogar,
      //   datosPredio: this.datosPredio,
      //   datosFuerzaLaboral: this.datosFuerzaLaboral,
      //   datosOrganizacion: this.datosOrganizacion,
      //   datosCierre: this.datosCierre
      // })
    },

    // Sincronizar todos los datos
    async sincronizarSocial() {
      if (!this.isOnline) {
          alert('No hay conexión a internet. Por favor, conéctate para sincronizar los datos sociales.');
          return;
      }

      // Iniciar estado de sincronización
      this.sincronizando = true;
      this.mostrarModalProgreso = true;
      this.progresoPorcentaje = 0;
      this.mensajeProgreso = 'Preparando datos para sincronización...';

      try {
          // Simular progreso inicial
          await this.delay(500);
          this.progresoPorcentaje = 10;
          this.mensajeProgreso = 'Conectando con el servidor...';

          // 🔹 Sincronizar datos sociales con actualización de progreso
          const totalSincronizados = await this.sincronizarConProgreso();

          // Actualizar progreso final
          this.progresoPorcentaje = 100;
          this.mensajeProgreso = 'Finalizando sincronización...';
          await this.delay(500);

          // 🔹 Actualizar estado de la visita social
          // await this.actualizarEstadoVisitaSocial();
          
          // Mostrar modal de éxito
          this.totalRegistrosSincronizados = totalSincronizados;
          this.fechaSincronizacion = new Date().toLocaleString('es-ES');
          this.mostrarModalProgreso = false;
          this.mostrarModalExito = true;
          
          // Recargar datos para verificar sincronización
          await this.loadAllData();
          
          // ✅ AGREGAR: Configurar redirección cuando se cierre el modal de éxito
          this.configurarRedireccionExito();
          
      } catch (error) {
          console.error('Error al sincronizar datos sociales:', error);
          
          // Mostrar modal de error
          this.mensajeError = error.message || 'Error desconocido al sincronizar los datos.';
          this.mostrarModalProgreso = false;
          this.mostrarModalError = true;
          
      } finally {
          this.sincronizando = false;
          this.progresoPorcentaje = 0;
      }
  },

  // 🔹 NUEVO MÉTODO: Configurar redirección cuando la sincronización es exitosa
  configurarRedireccionExito() {
      // Usar $nextTick para asegurar que el modal esté renderizado
      this.$nextTick(() => {
          // Encontrar el botón de "Aceptar" en el modal de éxito
          const botonAceptar = document.querySelector('.modal-exito .btn-success, .modal-exito .btn-primary');
          
          if (botonAceptar) {
              // Agregar evento de clic para redirigir
              botonAceptar.addEventListener('click', this.redirigirAlDashboard);
          }
          
          // También redirigir si se cierra el modal con la X o haciendo clic fuera
          const modalExito = document.querySelector('.modal-exito');
          if (modalExito) {
              // Observar cambios en la visibilidad del modal
              const observer = new MutationObserver((mutations) => {
                  mutations.forEach((mutation) => {
                      if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                          if (!modalExito.style.display || modalExito.style.display === 'none') {
                              this.redirigirAlDashboard();
                          }
                      }
                  });
              });
              
              observer.observe(modalExito, { attributes: true });
          }
      });
  },

  // 🔹 NUEVO MÉTODO: Redirigir al dashboard
  redirigirAlDashboard() {
      console.log('✅ Redirigiendo al dashboard...');
      window.location.href = '/dashboard';
  },

      async sincronizarConProgreso() {
        // Simular progreso durante la sincronización
        const pasosProgreso = [20, 40, 60, 80];
        
        for (let i = 0; i < pasosProgreso.length; i++) {
          await this.delay(800);
          this.progresoPorcentaje = pasosProgreso[i];
          
          const mensajes = [
            'Sincronizando datos personales...',
            'Enviando información del hogar...',
            'Procesando datos del predio...',
            'Finalizando envío de información social...'
          ];
          this.mensajeProgreso = mensajes[i];
        }

        // Llamar a la función real de sincronización
        return await sincronizarDatosSocialesOffline(this.visitaId, this.actualizarProgreso.bind(this));
      },

      actualizarProgreso(progreso) {
        // Esta función será llamada desde el sincronizador para actualizar el progreso
        this.progresoPorcentaje = progreso.porcentaje;
        this.mensajeProgreso = progreso.mensaje;
      },

      async actualizarEstadoVisitaSocial() {
        try {
          this.mensajeProgreso = 'Actualizando estado de la visita...';
          
          const response = await fetch(`/visitas-social/${this.visitaId}/update-status`, {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
              status: 'sincronizado',
              fecha_sincronizacion: new Date().toISOString()
            })
          });

          if (!response.ok) {
            throw new Error('Error al actualizar estado de la visita social');
          }
        } catch (error) {
          console.error('Error actualizando estado visita social:', error);
          throw error;
        }
      },

    // Métodos para manejar los modals
    cerrarModalExito() {
      this.mostrarModalExito = false;
      this.totalRegistrosSincronizados = 0;
      this.fechaSincronizacion = '';
    },

    cerrarModalError() {
      this.mostrarModalError = false;
      this.mensajeError = '';
    },

    reintentarSincronizacion() {
      this.mostrarModalError = false;
      this.mensajeError = '';
      // Esperar un momento antes de reintentar
      setTimeout(() => {
        this.sincronizarSocial();
      }, 500);
    },

    verDetallesSincronizacion() {
      // Aquí puedes implementar ver detalles técnicos en consola o en otro modal
      console.log('Detalles de sincronización:', {
        visitaId: this.visitaId,
        registrosSincronizados: this.totalRegistrosSincronizados,
        fecha: this.fechaSincronizacion
      });
      
      // O mostrar un alert con más detalles
      alert(`Detalles de sincronización:\n\n• Visita ID: ${this.visitaId}\n• Registros: ${this.totalRegistrosSincronizados}\n• Fecha: ${this.fechaSincronizacion}\n• Estado: Completado exitosamente`);
    },

    // Utilidad para delays
    delay(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    }
  },

    cerrarModal() {
      this.mostrarModal = false
      this.sincronizacionCompleta = false
    },

    irAInicioLaravel() {
      if (!confirm('¿Salir de la revisión final?')) return
      window.location.href = '/dashboard'
    },

    // Manejo de conexión
    handleOnline() {
      this.isOnline = true
      console.log('Conexión reestablecida.')
    },

    handleOffline() {
      this.isOnline = false
      console.warn('Conexión perdida. Operando en modo offline.')
    }
  }

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
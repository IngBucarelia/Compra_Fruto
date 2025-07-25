<template>
  <div class="offline-container">
    <h2>🌱 Fertilización - Registros Previos</h2>

    <!-- Sección para mostrar múltiples áreas guardadas localmente -->
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
            <div v-if="areasInfo.length > 0">
              <div
                v-for="(area, index) in areasInfo"
                :key="area.local_id"
                class="area-info-card mb-3"
              >
                <h5>Área #{{ index + 1 }} - Material: {{ area.material }}</h5>
                <ul>
                  <li><strong>Estado:</strong> {{ area.estado }}</li>
                  <li><strong>Año siembra:</strong> {{ area.anio_siembra }}</li>
                  <li><strong>Área (m²):</strong> {{ area.area }}</li>
                  <li>
                    <strong>Área Total Finca (Ha):</strong>
                    {{ area.area_total_finca_hectareas ?? 'N/A' }}
                  </li>
                  <li>
                    <strong>Palmas Total Finca:</strong>
                    {{ area.numero_palmas_total_finca ?? 'N/A' }}
                  </li>
                  <li>
                    <strong>Área Palmas Desarrollo (Ha):</strong>
                    {{ area.area_palmas_desarrollo_hectareas ?? 'N/A' }}
                  </li>
                  <li>
                    <strong>Palmas Desarrollo:</strong>
                    {{ area.numero_palmas_desarrollo ?? 'N/A' }}
                  </li>
                  <li>
                    <strong>Área Palmas Producción (Ha):</strong>
                    {{ area.area_palmas_produccion_hectareas ?? 'N/A' }}
                  </li>
                  <li>
                    <strong>Palmas Producción:</strong>
                    {{ area.numero_palmas_produccion ?? 'N/A' }}
                  </li>
                  <li>
                    <strong>Ciclos de Cosecha:</strong>
                    {{ area.ciclos_cosecha ?? 'N/A' }}
                  </li>
                  <li>
                    <strong>Producción Toneladas/Mes:</strong>
                    {{ area.produccion_toneladas_por_mes ?? 'N/A' }}
                  </li>
                  <li>
                    <strong>Aplica Orden Plantis:</strong>
                    {{ area.aplica_orden_plantis ? 'Sí' : 'No' }}
                  </li>
                  <template v-if="area.aplica_orden_plantis">
                    <li>
                      <strong>Orden Plantis N°:</strong>
                      {{ area.orden_plantis_numero ?? 'N/A' }}
                    </li>
                    <li>
                      <strong>Número de Plantas (Orden Plantis):</strong>
                      {{ area.numero_plantas_orden_plantis ?? 'N/A' }}
                    </li>
                    <li>
                      <strong>Estado Orden Plantis:</strong>
                      {{ area.estado_oren_plantis ?? 'N/A' }}
                    </li>
                  </template>
                </ul>
              </div>
            </div>
            <p v-else class="text-muted">No se ha registrado área para esta visita.</p>
          </div>
        </div>
      </div>
    </div>

    <h2>🌱 Registro de Fertilización (Modo Offline)</h2>
    <form @submit.prevent="guardar">
      <div class="form-group mb-3">
        <label>Fecha General de la Fertilización:</label>
        <input type="date" v-model="fertilizacion.fecha_fertilizacion" class="form-control" required />
      </div>

      <h5>Fertilizantes aplicados</h5>
      <div
        v-for="(item, index) in fertilizacion.fertilizantes"
        :key="item.local_id"
        class="fertilizante-group mb-3"
      >
        <button
          v-if="fertilizacion.fertilizantes.length > 1"
          type="button"
          class="remove-fertilizante-btn"
          @click="removeFertilizante(index)"
        >
          ✖️
        </button>
        <div class="form-group mb-2">
          <label>Fecha de aplicación:</label>
          <input type="date" v-model="item.fecha_aplicacion" class="form-control" required />
        </div>
        <div class="form-group mb-2">
          <label>Fertilizante:</label>
          <select v-model="item.nombre" class="form-control" required>
            <option value="">Seleccione fertilizante</option>
            <option value="urea">Urea</option>
            <option value="compost">Compost</option>
            <option value="npk">NPK</option>
            <option value="otro">Otro</option>
          </select>
        </div>
        <div class="form-group mb-2">
          <label>Cantidad:</label>
          <input
            type="number"
            v-model="item.cantidad"
            class="form-control"
            placeholder="Cantidad"
            required
            min="0"
            step="0.01"
          />
        </div>
        <div class="form-group mb-0">
          <label>Unidad de Medida:</label>
          <select v-model="item.unidad_medida" class="form-control" required>
            <option value="">Seleccione unidad</option>
            <option value="kg">Kilogramos (kg)</option>
            <option value="litros">Litros</option>
            <option value="gramos">Gramos</option>
            <option value="unidades">Unidades</option>
          </select>
        </div>
      </div>

      <button type="button" @click="agregarFertilizante" class="btn btn-info mt-3 mb-3">
        ➕ Añadir otro fertilizante
      </button>
      <br />
      <button type="submit" class="btn btn-primary">💾 Guardar Local</button>
    </form>

    <button v-if="canSync" @click="sincronizar" class="btn btn-success mt-3">
      🔄 Sincronizar
    </button>

    <div class="mt-3">
      <button type="button" class="btn btn-success" @click="irAPolinizacion">
        ➡️ Ir a Polinizacion
      </button>
      <br />
      <button @click="redirectToOnlineDashboard" class="btn btn-primary fixed bottom-4 right-4" v-if="isOnline">
        Ir al Dashboard Online
      </button>
      <button class="btn btn-secondary mt-3 ms-2" @click="volver">⬅️ Volver</button>
    </div>

    <!-- Sección para mostrar fertilizaciones guardadas localmente -->
    <div class="mt-5 p-4 bg-light rounded shadow-sm">
      <h4 class="mb-3">Fertilizaciones Guardadas Localmente (Pendientes de Sincronizar)</h4>
      <div v-if="localFertilizaciones.length > 0">
        <div v-for="lFert in localFertilizaciones" :key="lFert.local_id" class="card mb-3">
          <div class="card-header">
            <strong>🗓 Fecha General:</strong> {{ lFert.fecha_fertilizacion }}
            (ID Local: {{ lFert.local_id.substring(0, 8) }})
          </div>
          <div class="card-body">
            <h6>Detalles de Fertilizantes:</h6>
            <ul class="list-group">
              <li
                v-for="(detail, dIndex) in lFert.fertilizantes"
                :key="detail.local_id || dIndex"
                class="list-group-item d-flex justify-content-between flex-wrap"
              >
                <div>
                  <strong>{{ detail.nombre }}</strong> ({{ detail.fecha_aplicacion ?? 'N/A' }})
                </div>
                <span>{{ detail.cantidad }} {{ detail.unidad_medida ?? 'N/A' }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <p v-else class="text-muted">No hay fertilizaciones guardadas localmente para esta visita.</p>
    </div>
  </div>
</template>

<script>
// ✅ Importar las funciones que tienes en tu indexeddb.js
import { saveFormData, getAllDataFromStore, clearStore } from '../store/indexeddb';
import { v4 as uuidv4 } from 'uuid'; // Importar uuid para generar IDs únicos

export default {
  data() {
    return {
      isOnline: navigator.onLine,
      visitaId: null,
      areasInfo: [], // Ahora es un array para múltiples áreas
      canSync: navigator.onLine,
      fertilizacion: {
        local_id: uuidv4(), // ID único para el registro principal de fertilización
        fecha_fertilizacion: '',
        fertilizantes: [
          {
            local_id: uuidv4(), // ID único para cada fertilizante individual
            fecha_aplicacion: '',
            nombre: '',
            cantidad: '',
            unidad_medida: '',
          },
        ],
      },
      localFertilizaciones: [], // Para mostrar las fertilizaciones ya guardadas localmente
    };
  },
  async mounted() {
    // Escuchar cambios en el estado de la red
    window.addEventListener('online', this.updateOnlineStatus);
    window.addEventListener('offline', this.updateOnlineStatus);

    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    await this.loadAreasInfo(); // Cargar todas las áreas
    await this.loadLocalFertilizaciones(); // Cargar fertilizaciones existentes

    // Si no hay fertilizaciones cargadas, asegurar que haya al menos un formulario de fertilizante
    if (this.localFertilizaciones.length === 0 && this.fertilizacion.fertilizantes.length === 0) {
      this.agregarFertilizante(); // Añade el primer formulario si no hay ninguno
    }
  },
  beforeUnmount() {
    // Limpiar los listeners al destruir el componente
    window.removeEventListener('online', this.updateOnlineStatus);
    window.removeEventListener('offline', this.updateOnlineStatus);
  },
  methods: {
    updateOnlineStatus() {
      this.isOnline = navigator.onLine;
      this.canSync = navigator.onLine;
    },
    redirectToOnlineDashboard() {
      window.location.href = '/dashboard';
    },
    async loadAreasInfo() {
      // Cargar todas las áreas de IndexedDB y filtrar por visita_id
      const allAreaSubmissions = await getAllDataFromStore('area');
      this.areasInfo = allAreaSubmissions.filter(item => item.visita_id == this.visitaId);
    },
    async loadLocalFertilizaciones() {
      // Cargar todas las fertilizaciones de IndexedDB y filtrar por visita_id
      const allFertilizacionSubmissions = await getAllDataFromStore('fertilizacion');
      this.localFertilizaciones = allFertilizacionSubmissions.filter(item => item.visita_id == this.visitaId);
    },
    agregarFertilizante() {
      this.fertilizacion.fertilizantes.push({
        local_id: uuidv4(), // ID único para cada nuevo fertilizante
        fecha_aplicacion: '',
        nombre: '',
        cantidad: '',
        unidad_medida: '',
      });
    },
    removeFertilizante(index) {
      this.fertilizacion.fertilizantes.splice(index, 1);
      // Opcional: Si eliminas el último, añade uno nuevo para que el formulario no quede vacío
      if (this.fertilizacion.fertilizantes.length === 0) {
        this.agregarFertilizante();
      }
    },
    async guardar() {
      if (!this.visitaId) {
        alert('Error: No se ha encontrado el ID de la visita.');
        return;
      }

      // Validar campos de la fertilización principal
      if (!this.fertilizacion.fecha_fertilizacion) {
        alert('Por favor, ingrese la Fecha General de la Fertilización.');
        return;
      }

      // Validar cada fertilizante individual
      if (this.fertilizacion.fertilizantes.length === 0) {
        alert('Debe añadir al menos un fertilizante.');
        return;
      }

      for (const item of this.fertilizacion.fertilizantes) {
        if (!item.fecha_aplicacion || !item.nombre || !item.cantidad || !item.unidad_medida) {
          alert('Por favor, complete todos los campos (Fecha de aplicación, Fertilizante, Cantidad, Unidad de Medida) para cada fertilizante.');
          return;
        }
      }

      // Asignar visita_id y local_id si no están ya asignados (para el caso de carga inicial)
      this.fertilizacion.visita_id = this.visitaId;
      if (!this.fertilizacion.local_id) {
          this.fertilizacion.local_id = uuidv4();
      }
      
      // ✅ Guardar el objeto completo de fertilización en IndexedDB
      await saveFormData('fertilizacion', JSON.parse(JSON.stringify(this.fertilizacion)));
      alert('✅ Fertilización guardada localmente');
      
      // Reiniciar el formulario después de guardar
      this.fertilizacion = {
        local_id: uuidv4(), // Generar nuevo ID para el próximo registro
        fecha_fertilizacion: '',
        fertilizantes: [{
          local_id: uuidv4(),
          fecha_aplicacion: '',
          nombre: '',
          cantidad: '',
          unidad_medida: '',
        }],
      };
      await this.loadLocalFertilizaciones(); // Recargar la lista de fertilizaciones locales
    },
    async sincronizar() {
      if (!this.isOnline) {
        alert('No hay conexión a internet para sincronizar.');
        return;
      }

      if (this.localFertilizaciones.length === 0) {
        alert('No hay datos de fertilización guardados localmente para sincronizar.');
        return;
      }

      console.log('Iniciando sincronización de fertilizaciones...');
      const submissionsToSend = this.localFertilizaciones.map(fert => ({
        formName: 'fertilizacion',
        formData: fert, // Envía el objeto completo de fertilización
        local_id: fert.local_id // Para identificar el registro en el backend
      }));

      try {
        const response = await fetch('/sync-offline-data', { // Ajusta esta ruta si es diferente
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: JSON.stringify({ submissions: submissionsToSend }), // Envía el array de submissions
        });

        const result = await response.json();
        console.log('Resultado de sincronización:', result);

        if (response.ok) {
          let allSuccess = true;
          let syncedLocalIds = [];
          for (const res of result.results) {
            if (res.success) {
              syncedLocalIds.push(res.id); // Recopila los local_id exitosos
            } else {
              allSuccess = false;
              console.error(`Error al sincronizar fertilización ${res.id}:`, res.message, res.errors);
              alert(`Error al sincronizar fertilización ${res.id}: ${res.message}\nErrores: ${res.errors ? res.errors.join(', ') : 'N/A'}`);
            }
          }

          if (allSuccess) {
            await clearStore('fertilizacion'); // Limpia todos los registros de 'fertilizacion' en 'submissions'
            alert('✅ Todas las fertilizaciones locales sincronizadas con éxito y eliminadas localmente.');
          } else {
            alert('⚠️ Algunas fertilizaciones no pudieron sincronizarse. Revisa la consola y el log del servidor para más detalles. Los datos fallidos permanecen localmente.');
          }
          await this.loadLocalFertilizaciones(); // Recargar la lista de fertilizaciones locales después de la sincronización
        } else {
          console.error('Error en la respuesta del servidor durante la sincronización:', result);
          alert('Error en la sincronización: ' + (result.message || 'Error desconocido del servidor.'));
        }
      } catch (error) {
        console.error('Error de red o inesperado durante la sincronización:', error);
        alert('Error de red o inesperado durante la sincronización. Revisa la consola.');
      }
    },
    irAPolinizacion() {
      this.$router.push(`/polinizacion?visita_id=${this.visitaId}`);
    },
    volver() {
      this.$router.push('/area'); // Volver al formulario de área offline
    },
  },
};
</script>

<style scoped>
@import '../styles/offline.css'; /* Asegúrate de que este archivo exista y contenga los estilos base */


</style>
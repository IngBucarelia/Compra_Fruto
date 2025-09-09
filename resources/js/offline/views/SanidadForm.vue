<template>
<div class="container">
  <div class="offline-container offline-form-container">
    <h2 class="offline-title">🪩 Sanidad - Registros Previos</h2>

    <!-- Sección de información previa -->
    <div class="row mb-4">
      <!-- Tarjeta: Áreas -->
      <div class="col-md-6">
        <div class="card border-success">
          <div class="card-header bg-success text-white">
            📍 Áreas Registradas
          </div>
          <div class="card-body">
            <div v-if="areasInfo.length > 0">
              <div v-for="(area, index) in areasInfo" :key="area.id" class="mb-3 area-card">
                <h5>Área #{{ index + 1 }}</h5>
                <ul class="list-group">
                  <li class="list-group-item"><strong>Variedad:</strong> {{ area.variedad }}</li>
                  <li class="list-group-item"><strong>Material:</strong> {{ area.material }}</li>
                  <li class="list-group-item"><strong>Estado:</strong> {{ area.estado }}</li>
                  <li class="list-group-item"><strong>Año siembra:</strong> {{ formatDate(area.anio_siembra) }}</li>
                  <li class="list-group-item"><strong>Área (m²):</strong> {{ area.area }}</li>
                  
                  <li class="list-group-item"><strong>Área Total Finca (Ha):</strong> {{ area.area_total_finca_hectareas || 'N/A' }}</li>
                  <li class="list-group-item"><strong>N° Palmas Total Finca:</strong> {{ area.numero_palmas_total_finca || 'N/A' }}</li>
                  
                  <li class="list-group-item"><strong>Área Palmas Desarrollo (Ha):</strong> {{ area.area_palmas_desarrollo_hectareas || 'N/A' }}</li>
                  <li class="list-group-item"><strong>N° Palmas Desarrollo:</strong> {{ area.numero_palmas_desarrollo || 'N/A' }}</li>
                  
                  <li class="list-group-item"><strong>Área Palmas Producción (Ha):</strong> {{ area.area_palmas_produccion_hectareas || 'N/A' }}</li>
                  <li class="list-group-item"><strong>N° Palmas Producción:</strong> {{ area.numero_palmas_produccion || 'N/A' }}</li>
                  
                  <li class="list-group-item"><strong>Ciclos de Cosecha:</strong> {{ area.ciclos_cosecha || 'N/A' }}</li>
                  <li class="list-group-item"><strong>Producción (Toneladas/Mes):</strong> {{ area.produccion_toneladas_por_mes || 'N/A' }}</li>
                  
                  <li class="list-group-item"><strong>Aplica Orden Plantis:</strong> {{ area.aplica_orden_plantis ? 'Sí' : 'No' }}</li>
                  
                  <template v-if="area.aplica_orden_plantis">
                    <li class="list-group-item"><strong>Orden Plantis N°:</strong> {{ area.orden_plantis_numero || 'N/A' }}</li>
                    <li class="list-group-item"><strong>Estado Orden Plantis:</strong> {{ area.estado_oren_plantis || 'N/A' }}</li>
                    <li class="list-group-item"><strong>N° Plantas Orden Plantis:</strong> {{ area.numero_plantas_orden_plantis || 'N/A' }}</li>
                  </template>
                </ul>
              </div>
            </div>
            <p v-else class="text-muted">No hay áreas registradas</p>
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

      <!-- Plagas -->
      <h3>Plagas</h3>
      <div v-for="(pla, index) in dynamicPlagas" :key="pla.id" class="mb-3 p-3 border rounded">
        <button type="button" class="btn btn-danger btn-sm float-end" @click="removePlaga(index)">Eliminar</button>
        
        <div class="form-group mb-2">
          <label>Nombre de la plaga:</label>
          <select v-model="pla.nombre" class="form-select">
            <option value="">-- Seleccione --</option>
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
          <select v-model="pla.estado" class="form-select">
            <option value="">-- Seleccione --</option>
            <option value="Larva">Larva</option>
            <option value="Ninfa">Ninfa</option>
            <option value="Adulto">Adulto</option>
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
        <button v-if="canSync" @click="sincronizar" class="btn btn-success">Sincronizar</button>
        <button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
      </div>
    </form>


   <!-- Sanidades guardadas localmente -->
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

        <!-- Plagas -->
        <div v-if="sanidad.plagas && sanidad.plagas.length > 0">
          <h6>Plagas:</h6>
          <ul class="list-group list-group-flush mb-2">
            <li v-for="(pla, pIndex) in sanidad.plagas" :key="pIndex" class="list-group-item">
              <strong>Nombre:</strong> {{ pla.nombre || '-' }},
              <strong>Estado:</strong> {{ pla.estado || '-' }}
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
      dynamicEnfermedades: [], // <- Array único para enfermedades
      dynamicPlagas: [],
      dynamicTraps: [],
      localSanidades: [],
      canSync: navigator.onLine,
      currentEnfermedadIndex: 0,
    }
  },
  computed: {
    diseaseOptions() {
      // Genera las opciones del select a partir del mapa de campos
      return Object.keys(this.diseaseFieldMap).map(name => ({
        value: name,
        text: name
      }));
    }
  },
  async mounted() {
    try {
      this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id');
      if (!this.visitaId) {
        console.error('No se encontr�� visita_id');
        return;
      }

      localStorage.setItem('visita_id', this.visitaId);
      await this.loadInitialData();

      if (this.dynamicEnfermedades.length === 0) {
        this.addEnfermedad();
      }
      
      // A�0�9adir una trampa por defecto si no hay ninguna
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



    addPlaga() {
      this.dynamicPlagas.push({
        id: Date.now() + Math.random(),
        nombre: '',
        estado: ''
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

        const formData = {
          ...this.form,
          visita_id: this.visitaId,
          id: uuidv4(),
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
          enfermedades: this.dynamicEnfermedades.filter(e => e.nombre),
          plagas: this.dynamicPlagas.filter(p => p.nombre),
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

        // --- Enfermedades ---
        if (existingSanidad.enfermedades && Array.isArray(existingSanidad.enfermedades)) {
          this.dynamicEnfermedades = existingSanidad.enfermedades.map((enf, idx) => ({
            id: Date.now() + idx,
            nombre: enf.nombre || '', // aquí se asigna la enfermedad seleccionada
            estado: enf.estado || '', // si hay algún estado extra
          }));
        } else {
          this.dynamicEnfermedades = [];
          this.addEnfermedad(); // Siempre tener al menos un select
        }

        // --- Plagas ---
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

        // --- Renderizar selects ---
        this.renderDynamicEnfermedades();
        this.renderDynamicPlagas();

      } catch (error) {
        console.error('Error cargando datos existentes:', error);
      }
    },


    // --- Render dinámico para enfermedades ---
    renderDynamicEnfermedades() {
      const container = document.getElementById('enfermedades-container');
      container.innerHTML = ''; // limpiar

      this.dynamicEnfermedades.forEach((enf, index) => {
        const div = document.createElement('div');
        div.className = 'row mb-2';
        div.innerHTML = `
          <div class="col-md-6 mb-2">
            <label>Enfermedad:</label>
            <select class="form-select" name="enfermedades[${index}][nombre]">
              <option value="">-- Seleccione --</option>
              <option value="opsophanes" ${enf.nombre === 'opsophanes' ? 'selected' : ''}>Opsophanes</option>
              <option value="pudricion_cogollo" ${enf.nombre === 'pudricion_cogollo' ? 'selected' : ''}>Pudrición de Cogollo</option>
              <option value="raspador" ${enf.nombre === 'raspador' ? 'selected' : ''}>Raspador</option>
              <option value="palmarum" ${enf.nombre === 'palmarum' ? 'selected' : ''}>Palmarum</option>
              <option value="strategus" ${enf.nombre === 'strategus' ? 'selected' : ''}>Strategus</option>
              <option value="leptopharsa" ${enf.nombre === 'leptopharsa' ? 'selected' : ''}>Leptopharsa</option>
              <option value="pestalotiopsis" ${enf.nombre === 'pestalotiopsis' ? 'selected' : ''}>Pestalotiopsis</option>
              <option value="pudricion_basal" ${enf.nombre === 'pudricion_basal' ? 'selected' : ''}>Pudrición Basal</option>
              <option value="pudricion_estipe" ${enf.nombre === 'pudricion_estipe' ? 'selected' : ''}>Pudrición de Estípe</option>
            </select>
          </div>
          <div class="col-md-4 mb-2">
            <label>% afectación:</label>
            <input type="number" min="0" max="100" class="form-control" name="enfermedades[${index}][estado]" value="${enf.estado}">
          </div>
          <div class="col-md-2 mb-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeEnfermedad(${enf.id})">Eliminar</button>
          </div>
        `;
        container.appendChild(div);
      });
    },

    // --- Render dinámico para plagas ---
    renderDynamicPlagas() {
      const container = document.getElementById('plagas-container');
      container.innerHTML = '';

      this.dynamicPlagas.forEach((plaga, index) => {
        const div = document.createElement('div');
        div.className = 'row mb-2';
        div.innerHTML = `
          <div class="col-md-6 mb-2">
            <label>Plaga:</label>
            <select class="form-select" name="plagas[${index}][nombre]">
              <option value="">-- Seleccione --</option>
              <option value="palmarum" ${plaga.nombre === 'palmarum' ? 'selected' : ''}>R. palmarum</option>
              <option value="otra_plaga" ${plaga.nombre === 'otra_plaga' ? 'selected' : ''}>Otra plaga</option>
            </select>
          </div>
          <div class="col-md-4 mb-2">
            <label>Estado:</label>
            <select class="form-select" name="plagas[${index}][estado]">
              <option value="">-- Seleccione --</option>
              <option value="leve" ${plaga.estado === 'leve' ? 'selected' : ''}>Leve</option>
              <option value="moderada" ${plaga.estado === 'moderada' ? 'selected' : ''}>Moderada</option>
              <option value="grave" ${plaga.estado === 'grave' ? 'selected' : ''}>Grave</option>
            </select>
          </div>
          <div class="col-md-2 mb-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm" onclick="removePlaga(${plaga.id})">Eliminar</button>
          </div>
        `;
        container.appendChild(div);
      });
    },


    formatDate(dateString) {
      if (!dateString) return 'N/A';
      try {
        const date = new Date(dateString);
        return isNaN(date.getTime()) ? 'Fecha inv��lida' :
          date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
          });
      } catch {
        return 'N/A';
      }
    },
    resetHiddenDiseaseInputs() {
      // Esta funci��n no es necesaria para el formulario offline
    },
    updateHiddenDiseaseInputs() {
      // Esta funci��n no es necesaria para el formulario offline
    },
   
   
    
    // M��todos para trampas
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
    renderDynamicDiseases() {
      // Este m��todo ya no es necesario ya que Vue lo hace autom��ticamente
    },
    async submitForm() {
      // Esta funci��n fue reemplazada por 'guardar'
    },
    async syncData() {
      // L��gica para sincronizar datos
    }
  }
};
</script>

<style scoped>

@import '../styles/offline.css';


  .container.offline-form-container {
          background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
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

.polinizacion-form-group {
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

    .container{
      margin-left: -70px !important;
      margin-top: 70px !important;
    }

    .container.offline-form-container {
          background-color: rgba(129, 165, 114, 0.929); /* Color de fondo específico para este formulario */
          
      }


  .offline-form-container h2.title {
      text-align: center;
      font-family: Arial Black;
      font-weight: bold;
      font-size: 30px;
      color: #fdffe5;
      text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
  }


  .offline-container {
    margin-left: -180px;
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
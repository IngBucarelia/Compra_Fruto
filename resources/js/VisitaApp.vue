<template>
  <div class="p-4">
    <div class="accordion" id="accordionFormularios">

      <!-- Formulario de Área -->
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingArea">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-expanded="true">
            📍 Formulario de Área
          </button>
        </h2>
        <div id="collapseArea" class="accordion-collapse collapse show" data-bs-parent="#accordionFormularios">
          <div class="accordion-body">
            <form @submit.prevent="guardarArea">
              <!-- campos del formulario de área -->
              <div class="mb-3">
                <label>Material</label>
                <select v-model="form.material" class="form-control" required>
                  <option value="">Seleccione</option>
                  <option value="guinense">Guinense</option>
                  <option value="hibrido">Híbrido</option>
                </select>
              </div>

              <div class="mb-3">
                <label>Estado</label>
                <select v-model="form.estado" class="form-control" required>
                  <option value="">Seleccione</option>
                  <option value="desarrollo">Desarrollo</option>
                  <option value="produccion">Producción</option>
                </select>
              </div>

              <div class="mb-3">
                <label>Año siembra</label>
                <input type="date" v-model="form.anio_siembra" class="form-control" required />
              </div>

              <div class="mb-3">
                <label>Área (m²)</label>
                <input type="number" v-model="form.area" class="form-control" required />
              </div>

              

              <div class="mb-3">
                <label>d</label>
                <select v-model="form.estado_orden_plantis" class="form-control">
                  <option value="">Seleccione</option>
                  <option value="desarrollo">Desarrollo</option>
                  <option value="produccion">Producción</option>
                </select>
              </div>

              <button class="btn btn-success">Guardar Área</button>
            </form>

            <hr />
            <h5>Áreas Guardadas</h5>
            <ul class="list-group">
              <li v-for="(area, index) in areas" :key="index" class="list-group-item">
                {{ area.material }} - {{ area.estado }} - {{ area.area }} m²
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Formulario de Fertilización -->
      <div class="accordion-item mt-3">
        <h2 class="accordion-header" id="headingFert">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFert" aria-expanded="false">
            💧 Formulario de Fertilización
          </button>
        </h2>
        <div id="collapseFert" class="accordion-collapse collapse" data-bs-parent="#accordionFormularios">
          <div class="accordion-body">
            <form @submit.prevent="guardarFertilizacion">
              <div class="mb-3">
                <label>Fecha fertilización</label>
                <input type="date" v-model="fertilizacion.fecha" class="form-control" required />
              </div>

              <div class="mb-3">
                <label>Fertilizante</label>
                <select v-model="fertilizacion.fertilizante" class="form-control" required>
                  <option value="">Seleccione</option>
                  <option value="urea">Urea</option>
                  <option value="compost">Compost</option>
                  <option value="npk">NPK</option>
                  <option value="otro">Otro</option>
                </select>
              </div>

              <div class="mb-3">
                <label>Cantidad aplicada (kg)</label>
                <input type="number" v-model="fertilizacion.cantidad" class="form-control" required />
              </div>

              <button class="btn btn-primary">Guardar Fertilización</button>
            </form>

            <hr />
            <h5>Fertilizaciones Guardadas</h5>
            <ul class="list-group">
              <li v-for="(fert, index) in fertilizaciones" :key="index" class="list-group-item">
                {{ fert.fecha }} - {{ fert.fertilizante }} - {{ fert.cantidad }} kg
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>

    <!-- Botón sincronizar general -->
    <div class="mt-4">
      <button class="btn btn-outline-primary" @click="intentarSincronizar">🔁 Sincronizar todo</button>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      visitaId: null,
      form: {
        material: '',
        estado: '',
        anio_siembra: '',
        area: '',
        orden_plantis_numero: '',
        estado_orden_plantis: ''
      },
      fertilizacion: {
        fecha: '',
        fertilizante: '',
        cantidad: ''
      },
      areas: [],
      fertilizaciones: []
    };
  },
  mounted() {
    const visita = JSON.parse(localStorage.getItem('visita_activa'));
    this.visitaId = visita?.id || null;

    this.areas = JSON.parse(localStorage.getItem('areas_visita')) || [];
    this.fertilizaciones = JSON.parse(localStorage.getItem('fertilizaciones_visita')) || [];

    window.addEventListener('online', this.intentarSincronizar);
  },
  methods: {
    guardarArea() {
      const nueva = { ...this.form, visita_id: this.visitaId };

      if (navigator.onLine) {
        fetch('/api/sync-areas', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ datos: [nueva] })
        }).then(() => alert('✅ Área guardada')).catch(() => {
          this.guardarLocal('areas_visita', nueva);
        });
      } else {
        this.guardarLocal('areas_visita', nueva);
      }

      this.form = { material: '', estado: '', anio_siembra: '', area: '', orden_plantis_numero: '', estado_orden_plantis: '' };
    },

    guardarFertilizacion() {
      const nueva = { ...this.fertilizacion, visita_id: this.visitaId };

      if (navigator.onLine) {
        fetch('/api/sync-fertilizaciones', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ datos: [nueva] })
        }).then(() => alert('✅ Fertilización guardada')).catch(() => {
          this.guardarLocal('fertilizaciones_visita', nueva);
        });
      } else {
        this.guardarLocal('fertilizaciones_visita', nueva);
      }

      this.fertilizacion = { fecha: '', fertilizante: '', cantidad: '' };
    },

    guardarLocal(clave, objeto) {
      const lista = JSON.parse(localStorage.getItem(clave)) || [];
      lista.push(objeto);
      localStorage.setItem(clave, JSON.stringify(lista));
      if (clave === 'areas_visita') this.areas = lista;
      if (clave === 'fertilizaciones_visita') this.fertilizaciones = lista;
    },

    intentarSincronizar() {
      ['areas_visita', 'fertilizaciones_visita'].forEach(endpoint => {
        const datos = JSON.parse(localStorage.getItem(endpoint) || '[]').filter(d => d.visita_id);
        if (!datos.length) return;

        const url = endpoint === 'areas_visita' ? '/api/sync-areas' : '/api/sync-fertilizaciones';

        fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({ datos })
        }).then(() => {
          localStorage.removeItem(endpoint);
          if (endpoint === 'areas_visita') this.areas = [];
          if (endpoint === 'fertilizaciones_visita') this.fertilizaciones = [];
          alert(`✅ ${endpoint} sincronizados`);
        }).catch(() => {
          console.warn(`❌ Error al sincronizar ${endpoint}`);
        });
      });
    }
  }
};
</script>
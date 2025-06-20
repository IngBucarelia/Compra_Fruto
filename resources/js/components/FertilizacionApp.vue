<template>
  <div class="p-4">
    <h4>🌱 Formulario de Fertilización</h4>

    <form @submit.prevent="guardarFertilizacion">
      <div class="mb-3">
        <label>Fecha fertilización</label>
        <input type="date" v-model="form.fecha_fertilizacion" class="form-control" required />
      </div>

      <div class="mb-3">
        <label>Fertilizantes usados</label>
        <div v-for="(fert, i) in form.fertilizantes" :key="i" class="input-group mb-2">
          <input type="text" v-model="fert.nombre" placeholder="Nombre fertilizante" class="form-control" />
          <input type="number" v-model="fert.cantidad" placeholder="Cantidad (kg)" class="form-control" />
          <button @click.prevent="eliminarFertilizante(i)" class="btn btn-danger">🗑</button>
        </div>
        <button @click.prevent="agregarFertilizante" class="btn btn-outline-success">+ Añadir fertilizante</button>
      </div>

      <button class="btn btn-primary mt-3">Guardar fertilización</button>
    </form>

    <hr />

    <h5>Fertilizaciones guardadas</h5>
    <ul class="list-group">
      <li v-for="(f, index) in fertilizaciones" :key="index" class="list-group-item">
        📅 {{ f.fecha_fertilizacion }} – {{ f.fertilizantes.length }} fertilizantes
      </li>
    </ul>

    <button class="btn btn-outline-primary mt-3" @click="intentarSincronizar">
      🔁 Sincronizar ahora
    </button>

    <div class="mt-2 text-muted" v-if="ultimaSync">
      Última sincronización: <strong>{{ ultimaSync }}</strong>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      visitaId: null,
      fertilizaciones: [],
      form: {
        fecha_fertilizacion: '',
        fertilizantes: [],
      },
      ultimaSync: null
    };
  },
  mounted() {
    const visita = JSON.parse(localStorage.getItem('visita_activa'));
    this.visitaId = visita?.id;

    this.fertilizaciones = JSON.parse(localStorage.getItem('fertilizaciones_visita')) || [];
    this.ultimaSync = localStorage.getItem('ultima_sync_fertilizacion') || null;

    window.addEventListener('online', this.intentarSincronizar);
  },
  methods: {
    agregarFertilizante() {
      this.form.fertilizantes.push({ nombre: '', cantidad: '' });
    },
    eliminarFertilizante(index) {
      this.form.fertilizantes.splice(index, 1);
    },
    guardarFertilizacion() {
      const nueva = {
        ...this.form,
        visita_id: this.visitaId
      };

      this.fertilizaciones.push(nueva);
      localStorage.setItem('fertilizaciones_visita', JSON.stringify(this.fertilizaciones));

      this.form = { fecha_fertilizacion: '', fertilizantes: [] };
    },
    intentarSincronizar() {
      const datos = JSON.parse(localStorage.getItem('fertilizaciones_visita') || '[]');

      if (!datos.length) return;

      fetch('/api/sync-fertilizaciones', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ datos })
      })
        .then(res => {
          if (!res.ok) throw new Error('Fallo en la sincronización');
          return res.json();
        })
        .then(() => {
          alert('✅ Fertilizaciones sincronizadas');
          localStorage.removeItem('fertilizaciones_visita');
          this.fertilizaciones = [];
          this.ultimaSync = new Date().toLocaleString();
          localStorage.setItem('ultima_sync_fertilizacion', this.ultimaSync);
        })
        .catch(err => {
          console.error('❌ No se pudo sincronizar:', err);
        });
    }
  }
};
</script>

<style scoped>
label {
  font-weight: 500;
}
</style>

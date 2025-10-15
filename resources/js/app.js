import 'bootstrap';

// Importa Bootstrap CSS (si no lo haces ya en un archivo Sass/CSS separado)
import 'bootstrap/dist/css/bootstrap.min.css';

import { createApp } from 'vue'

const el = document.getElementById('offline-app')
if (el) {
  createApp(AreaForm).mount(el)
}

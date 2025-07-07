import { createApp } from 'vue'
import AreaForm from './offline/AreaForm.vue'

const el = document.getElementById('offline-app')
if (el) {
  createApp(AreaForm).mount(el)
}

import { createApp } from 'vue'
import AreaForm from './offline/AreaForm.vue'
import './styles/offline.css';

const el = document.getElementById('offline-app')
if (el) {
  createApp(AreaForm).mount(el)
}

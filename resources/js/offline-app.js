import { createApp } from 'vue'
import App from './offline/App.vue'
import router from './offline/router'
import 'bootstrap/dist/css/bootstrap.min.css'

const app = createApp(App)
app.use(router)
app.use(store)
app.mount('#offline-app')

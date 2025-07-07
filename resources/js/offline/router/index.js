
import { createRouter, createWebHistory } from 'vue-router';
import AreaForm from '../views/AreaForm.vue';
import FertilizacionForm from '../views/FertilizacionForm.vue';
import PolinizacionForm from '../views/PolinizacionForm.vue';
import SanidadForm from '../views/SanidadForm.vue';
import SueloForm from '../views/SueloForm.vue';
import LaboresCultivoForm from '../views/LaboresCultivoForm.vue';
import EvaluacionCosechaForm from '../views/EvaluacionCosechaForm.vue'
import RevisionFinal from '../views/RevisionFinal.vue'
import FirmasForm from '../views/FirmasForm.vue'







const routes = [
  { path: '/area', name: 'Area', component: AreaForm },
  { path: '/fertilizacion', name: 'Fertilizacion', component: FertilizacionForm },
  { path: '/polinizacion', name: 'Polinizacion', component: PolinizacionForm },
  { path: '/sanidad', name: 'Sanidad', component: SanidadForm },
  { path: '/suelo', name: 'Suelo', component: SueloForm },
  { path: '/labores', name: 'LaboresCultivo', component: LaboresCultivoForm },
  { path: '/evaluacion-cosecha', name: 'EvaluacionCosecha', component: EvaluacionCosechaForm },
  { path: '/revisionfinal', name: 'RevisionFinal', component: RevisionFinal },
  { path: '/firmas', name: 'Firmas', component: FirmasForm },


  
];


const router = createRouter({
  history: createWebHistory('/offline/'),
  routes
})

export default router

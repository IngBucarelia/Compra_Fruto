
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
import DatosPersonalesForm from '../views/DatosPersonalesForm.vue';
import MiembrosHogarForm from '../views/MiembrosHogarForm.vue';
import DatosPredioForm from '../views/DatosPredioForm.vue';
import FuerzaLaboralForm from '../views/FuerzaLaboralForm.vue';
import OrganizacionSocialForm from '../views/OrganizacionSocialForm.vue';
import CierreVisitaForm from '../views/CierreVisitaForm.vue';




// Rutas para la funcionalidad offline 


const routes = [


 //rutas visitas sociales  
 { 
    path: '/', 
    redirect: (to) => {
      const visitaId = to.query.visita_id;
      return { 
        path: '/datos-personales', 
        query: visitaId ? { visita_id: visitaId } : {}
      }
    }
  },

 {
    path: '/datos-personales',
    name: 'DatosPersonales',
    component: DatosPersonalesForm 
  },

  {
    path: '/miembros-hogar',
    name: 'MiembrosHogar',
    component: MiembrosHogarForm
  },

  {
    path: '/datos-predio',
    name: 'DatosPredio',
    component: DatosPredioForm
  },

  {
    path: '/fuerza-laboral',
    name: 'FuerzaLaboral',
    component: FuerzaLaboralForm
  },
  {
    path: '/organizacion-social',
    name: 'OrganizacionSocial',
    component: OrganizacionSocialForm
  },

  {
    path: '/cierre-visita',
    name: 'CierreVisita',
    component: CierreVisitaForm
  },
  {
    path: '/revision-final-social',
    name: 'RevisionFinalSocial',
    component: () => import('../views/RevisionFinalSocial.vue')
  },

  // Rutas Visitas agronómicas
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

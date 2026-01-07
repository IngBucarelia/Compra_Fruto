
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


import SueloConservacion from '../views/SueloConservacion.vue'
import EnergiaUsoEficiente from '../views/EnergiaUsoEficiente.vue'
import GobernanzaHidrica from '../views/GobernanzaHidrica.vue'
import EmisionesGEI from '../views/EmisionesGEI.vue'
import ResiduosManejo from '../views/ResiduosManejo.vue'
import SustanciasManejo from '../views/SustanciasManejo.vue'
import VertimientosManejo from '../views/VertimientosManejo.vue'
import HmpManejo from '../views/HmpManejo.vue'
import AvcControl from '../views/AvcControl.vue'
import EcosistemaProteccion from '../views/EcosistemaProteccion.vue'
import NoReemplazoNoDeforestacion from '../views/NoReemplazoNoDeforestacion.vue'
import AguaCaptacionLegal from '../views/AguaCaptacionLegal.vue';
import AguaUsoEficiente from '../views/AguaUsoEficiente.vue';
import CierreVisitaAmbiental from '../views/CierreVisitaAmbiental.vue';
import RevisionFinalAmbiental from '../views/RevisionFinalAmbiental.vue';
import RevisionFinalSocial from   '../views/RevisionFinalSocial.vue';




// Rutas para la funcionalidad offline 


const routes = [


  //rutas visitas ambientales 


  {
    path: '/agua-captacion-legal',
    component: AguaCaptacionLegal
  },

  {
    path: '/agua-uso-eficiente',
    component: AguaUsoEficiente
  },

  {
    path: '/SueloConservacion',
    component: SueloConservacion
  },
  {
    path: '/energia-uso-eficiente',
    component: EnergiaUsoEficiente
  },
  {
    path: '/gobernanza-hidrica',
    component: GobernanzaHidrica
  },
  {
    path: '/emisiones-gei',
    component: EmisionesGEI
  },
  {
    path: '/residuos-manejo',
    component: ResiduosManejo
  },
  {
    path: '/sustancias-manejo',
    component: SustanciasManejo
  },
  {
    path: '/vertimientos-manejo',
    component: VertimientosManejo
  },
  {
    path: '/hmp-manejo',
    component: HmpManejo
  },
  {
    path: '/avc-control',
    component: AvcControl
  },
  {
    path: '/ecosistema-proteccion',
    component: EcosistemaProteccion
  },
  {
    path: '/noremplazo-nodeforestacion',
    component: NoReemplazoNoDeforestacion
  },
  
  {
    path: '/cierre-visita',
    component: CierreVisitaAmbiental
  },

  {
    path: '/revision-final-ambiental',
    component: RevisionFinalAmbiental
  },



 //rutas visitas sociales  
 { 
  path: '/', 
  redirect: (to) => {
    if (to.fullPath !== '/') {
      return to.fullPath
    }

    const visitaId = to.query.visita_id
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
    component: RevisionFinalSocial
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

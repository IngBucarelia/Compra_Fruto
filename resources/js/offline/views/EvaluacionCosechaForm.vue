<template>
  <div class="offline-container">
    <h2 class="offline-title">🌱 Evaluación Cosecha - Registros Previos</h2>
    <div class="row mb-4">
      <!-- 🧾 Módulos previos -->
      <div class="accordion mb-4" id="accordionArea">
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingArea">
            <button
              style="background-color: darkseagreen !important; color: aliceblue"
              class="accordion-button"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#collapseArea"
              aria-controls="collapseArea"
            >
              📍 Información del Área(s) guardada localmente
            </button>
          </h2>
          <div
            id="collapseArea"
            class="accordion-collapse collapse show"
            aria-labelledby="headingArea"
            data-bs-parent="#accordionArea"
          >
            <div
              class="accordion-body"
              style="background-color: rgb(209, 241, 209) !important; color: rgb(31, 32, 34)"
            >
              <!-- ⭐⭐ INFORMACIÓN GENERAL DE LA FINCA ⭐⭐ -->
              <div v-if="areas.length > 0" class="mb-4">
                <div class="card shadow-sm border-success">
                  <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                      <i class="fas fa-chart-bar me-2"></i>
                      Información General de la Finca
                    </h5>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="info-box-general mb-3">
                          <label class="fw-bold">
                            <i class="fas fa-ruler-combined me-2"></i>
                            Área Total Finca:
                          </label>
                          <div class="info-value">
                            {{ getInformacionGeneral('area_total_finca_hectareas') }} Ha
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="info-box-general mb-3">
                          <label class="fw-bold">
                            <i class="fas fa-tree me-2"></i>
                            Total de Palmas:
                          </label>
                          <div class="info-value">
                            {{ getInformacionGeneral('numero_palmas_total_finca') }}
                            <small class="text-muted ms-2">(incluye Orden Plantis)</small>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-md-6">
                        <div class="info-box-general mb-3">
                          <label class="fw-bold">
                            <i class="fas fa-sync-alt me-2"></i>
                            Ciclos de Cosecha:
                          </label>
                          <div class="info-value">
                            {{ getInformacionGeneral('ciclos_cosecha') }}
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="info-box-general mb-3">
                          <label class="fw-bold">
                            <i class="fas fa-weight-hanging me-2"></i>
                            Producción Total:
                          </label>
                          <div class="info-value">
                            {{ getInformacionGeneral('produccion_toneladas_por_mes') }} Ton/Mes
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Desglose del total de palmas -->
                    <div class="row mt-3 bg-light p-3 rounded">
                      <div class="col-12">
                        <h6 class="mb-2">
                          <i class="fas fa-list-ol me-2"></i>
                          Desglose del Total de Palmas
                        </h6>
                        <div class="row">
                          <div class="col-md-4">
                            <small class="d-block">
                              <span class="badge bg-info me-2">🌱</span>
                              <strong>Desarrollo:</strong> {{ calcularTotalDesarrollo() }}
                            </small>
                          </div>
                          <div class="col-md-4">
                            <small class="d-block">
                              <span class="badge bg-success me-2">🌳</span>
                              <strong>Producción:</strong> {{ calcularTotalProduccionPalmas() }}
                            </small>
                          </div>
                          <div class="col-md-4">
                            <small class="d-block">
                              <span class="badge bg-warning me-2">📋</span>
                              <strong>Orden Plantis:</strong> {{ calcularTotalOrdenPlantis() }}
                            </small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- ⭐⭐ ÁREAS INDIVIDUALES ⭐⭐ -->
              <div v-if="areas.length > 0">
                <h5 class="mb-3 mt-4">
                  <i class="fas fa-map-marked-alt me-2"></i>
                  Áreas Individuales ({{ areas.length }})
                </h5>
                
                <div class="row">
                  <div
                    v-for="(area, index) in areas"
                    :key="area.local_id || index"
                    class="col-md-6 mb-3"
                  >
                    <div class="card h-100 shadow-sm border-primary">
                      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="mb-0">
                            <i class="fas fa-seedling me-2"></i>
                            Área #{{ index + 1 }}
                          </h6>
                          <small class="opacity-75">
                            ID: {{ area.local_id?.substring(0, 8) || 'N/A' }}
                          </small>
                        </div>
                        <span class="badge" :class="getEstadoBadgeClass(area.estado)">
                          {{ area.estado === 'produccion' ? 'Producción' : 'Desarrollo' }}
                        </span>
                      </div>
                      
                      <div class="card-body">
                        <!-- Información básica -->
                        <div class="row mb-2">
                          <div class="col-6">
                            <small class="text-muted d-block">Variedad</small>
                            <strong>{{ area.variedad || 'N/A' }}</strong>
                          </div>
                          <div class="col-6">
                            <small class="text-muted d-block">Material</small>
                            <strong>{{ area.material || 'N/A' }}</strong>
                          </div>
                        </div>
                        
                        <div class="row mb-3">
                          <div class="col-12">
                            <small class="text-muted d-block">Año de Siembra</small>
                            <strong>{{ area.anio_siembra || 'N/A' }}</strong>
                          </div>
                        </div>
                        
                        <!-- Información específica según estado -->
                        <div v-if="area.estado === 'desarrollo'" class="bg-info bg-opacity-10 p-2 rounded mb-2">
                          <h6 class="text-info mb-2">
                            <i class="fas fa-seedling me-2"></i>
                            Información de Desarrollo
                          </h6>
                          <div class="row">
                            <div class="col-6">
                              <small class="text-muted d-block">Área (Ha)</small>
                              <strong>{{ area.area_palmas_desarrollo_hectareas || '0.00' }}</strong>
                            </div>
                            <div class="col-6">
                              <small class="text-muted d-block">N° Palmas</small>
                              <strong>{{ area.numero_palmas_desarrollo || '0' }}</strong>
                            </div>
                          </div>
                        </div>
                        
                        <div v-if="area.estado === 'produccion'" class="bg-success bg-opacity-10 p-2 rounded mb-2">
                          <h6 class="text-success mb-2">
                            <i class="fas fa-tree me-2"></i>
                            Información de Producción
                          </h6>
                          <div class="row">
                            <div class="col-6">
                              <small class="text-muted d-block">Área (Ha)</small>
                              <strong>{{ area.area_palmas_produccion_hectareas || '0.00' }}</strong>
                            </div>
                            <div class="col-6">
                              <small class="text-muted d-block">N° Palmas</small>
                              <strong>{{ area.numero_palmas_produccion || '0' }}</strong>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Información de Orden Plantis -->
                        <div v-if="area.aplica_orden_plantis" class="bg-warning bg-opacity-10 p-2 rounded">
                          <h6 class="text-warning mb-2">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Orden Plantis
                          </h6>
                          <div class="row">
                            <div class="col-6">
                              <small class="text-muted d-block">N° Orden</small>
                              <strong>{{ area.orden_plantis_numero || 'N/A' }}</strong>
                            </div>
                            <div class="col-6">
                              <small class="text-muted d-block">N° Plantas</small>
                              <strong>{{ area.numero_plantas_orden_plantis || '0' }}</strong>
                            </div>
                          </div>
                          <div class="row mt-1">
                            <div class="col-12">
                              <small class="text-muted d-block">Estado</small>
                              <span class="badge" :class="getOrdenPlantisBadgeClass(area.estado_oren_plantis)">
                                {{ area.estado_oren_plantis === 'produccion' ? 'Producción' : 'Desarrollo' }}
                              </span>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Estado si no aplica Orden Plantis -->
                        <div v-else class="text-center mt-2">
                          <small class="text-muted">
                            <i class="fas fa-times-circle me-1"></i>
                            No aplica Orden Plantis
                          </small>
                        </div>
                      </div>
                      
                      <div class="card-footer bg-transparent">
                        <small class="text-muted">
                          <i class="fas fa-calendar me-1"></i>
                          {{ formatDate(area.fecha_guardado) }}
                        </small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <p v-else class="text-muted">No se ha registrado área para esta visita.</p>
            </div>
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

      <!-- Polinizaciones -->
      <div class="col-md-6">
        <div class="card border-info">
          <div class="card-header bg-info text-white">
            🌸 Polinizaciones Registradas
          </div>
          <div class="card-body">
            <div v-if="polinizaciones.length > 0">
              <div v-for="(poli, index) in polinizaciones" :key="poli.local_id || index" class="mb-3">
                <h5>Polinización #{{ index + 1 }}</h5>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><strong>Fecha:</strong> {{ poli.fecha }}</li>
                  <li class="list-group-item"><strong>N° Pases:</strong> {{ poli.n_pases }}</li>
                  <li class="list-group-item"><strong>Ciclos:</strong> {{ poli.ciclos_ronda }}</li>
                  <li class="list-group-item"><strong>ANA:</strong> {{ poli.ana }} ({{ poli.tipo_ana }})</li>
                  <li class="list-group-item"><strong>Talco:</strong> {{ poli.talco }} kg</li>
                </ul>
              </div>
            </div>
            <p v-else class="text-muted">No hay polinizaciones registradas.</p>
          </div>
        </div>
      </div>

      <!-- Sanidad -->
      <div class="col-md-6">
        <div class="card border-danger mb-3">
          <div class="card-header bg-danger text-white">🦠 Sanidad</div>
          <div class="card-body" v-if="sanidad">
            <ul class="list-group list-group-flush">
              <!-- Enfermedades -->
              <li v-if="sanidad.enfermedades && sanidad.enfermedades.length > 0" class="list-group-item">
                <h6>Enfermedades:</h6>
                <ul class="list-group list-group-flush">
                  <li v-for="(enf, eIndex) in sanidad.enfermedades" :key="eIndex" class="list-group-item">
                    <strong>Nombre:</strong> {{ enf.nombre || '-' }},
                    <strong>Estado (%):</strong> {{ enf.estado || '-' }}
                  </li>
                </ul>
              </li>
              <li v-else class="list-group-item text-muted">No hay enfermedades registradas.</li>

              <!-- Plagas -->
              <li v-if="sanidad.plagas && sanidad.plagas.length > 0" class="list-group-item">
                <h6>Plagas:</h6>
                <ul class="list-group list-group-flush">
                  <li v-for="(pla, pIndex) in sanidad.plagas" :key="pIndex" class="list-group-item">
                    <strong>Nombre:</strong> {{ pla.nombre || '-' }},
                    <strong>Estado:</strong> {{ pla.estado || '-' }}
                  </li>
                </ul>
              </li>
              <li v-else class="list-group-item text-muted">No hay plagas registradas.</li>

              <!-- Censo y ciclos -->
              <li v-if="sanidad.censo_enfermedades !== undefined" class="list-group-item">
                <strong>Censo de enfermedades:</strong> {{ sanidad.censo_enfermedades ? 'Sí' : 'No' }}
              </li>
              <li v-if="sanidad.ciclos_lectura_enfermedades" class="list-group-item">
                <strong>Ciclos lectura enfermedades:</strong> {{ sanidad.ciclos_lectura_enfermedades }}
              </li>
              <li v-if="sanidad.ciclos_lectura_plagas" class="list-group-item">
                <strong>Ciclos lectura plagas:</strong> {{ sanidad.ciclos_lectura_plagas }}
              </li>

              <!-- Otros y observaciones -->
              <li v-if="sanidad.otros" class="list-group-item">
                <strong>Otros:</strong> {{ sanidad.otros }}
              </li>
              <li v-if="sanidad.observaciones" class="list-group-item">
                <strong>Observaciones:</strong> {{ sanidad.observaciones }}
              </li>

              <!-- Trampas -->
              <li v-if="sanidad.trampas && sanidad.trampas.length > 0" class="list-group-item">
                <h6 class="mt-2">Trampas de Palmarum:</h6>
                <ul class="list-group list-group-flush">
                  <li v-for="(trampa, index) in sanidad.trampas" :key="index" class="list-group-item">
                    Ciclos: {{ trampa.ciclos || '-' }}, Machos: {{ trampa.machos }}, Hembras: {{ trampa.hembras }}
                  </li>
                </ul>
              </li>
              <li v-else class="list-group-item text-muted">
                No hay trampas registradas.
              </li>
            </ul>
          </div>
          <p v-else class="text-muted card-body">Sin sanidad registrada.</p>
        </div>
      </div>

      <!-- Tarjeta: Suelo -->
      <div class="col-md-6" v-if="suelo">
        <div class="card border-warning">
          <div class="card-header bg-warning text-white">
            🧪 Suelo
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>Análisis Foliar:</strong> {{ suelo.analisis_foliar || 'N/A' }}</li>
              <li class="list-group-item"><strong>Análisis Suelo:</strong> {{ suelo.alanalisis_suelo || 'N/A' }}</li>
              <li class="list-group-item"><strong>Tipo Suelo:</strong> {{ suelo.tipo_suelo || 'N/A' }}</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Labores de Cultivo -->
      <div class="col-12" v-if="laboresCultivo && laboresCultivo.length > 0">
        <div class="card border-success">
          <div class="card-header bg-success text-white">🚜 Labores de Cultivo</div>
          <div class="card-body">
            <div v-for="(labor, index) in laboresCultivo" :key="index" class="mb-3">
              <strong>📅 {{ formatDate(labor.created_at) }}</strong>
              <div class="labor-details">
                <div><strong>Tipo Planta:</strong> {{ ucfirst(labor.tipo_planta) }}</div>
                <div v-for="(label, key) in camposLabores" :key="key">
                  <span>{{ label }}:</span>
                  <strong>{{ labor[key] || 0 }}%</strong>
                </div>
              </div>
              <p v-if="labor.observaciones"><strong>Observaciones:</strong> {{ labor.observaciones }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <h2 class="offline-title">🌴 Evaluación de Cosecha (Modo Offline)</h2>

    <!-- Contenedor para formularios de evaluación -->
    <div class="evaluacion-container">
      <div 
        v-for="(evaluacion, index) in evaluacionesForms" 
        :key="evaluacion.local_id" 
        class="evaluacion-form-card"
        :data-index="index"
      >
        <div class="form-header">
          <h4>Evaluación #{{ index + 1 }}</h4>
          <button 
            v-if="evaluacionesForms.length > 1"
            type="button" 
            class="remove-evaluacion-btn"
            @click="removeEvaluacionForm(index)"
          >
            ✖️
          </button>
        </div>

        <div class="mb-3">
          <label :for="`evaluaciones_${index}_variedad_fruto`" class="form-label">Variedad del fruto:</label>
          <select 
            :id="`evaluaciones_${index}_variedad_fruto`" 
            v-model="evaluacion.variedad_fruto" 
            class="form-select" 
            required
            @change="toggleConformacion(index)"
          >
            <option value="">Seleccione</option>
            <option value="guinense">Guinense</option>
            <option value="hibrido">Híbrido</option>
          </select>
        </div>

        <div class="mb-3">
          <label :for="`evaluaciones_${index}_cantidad_racimos`" class="form-label">Cantidad de racimos:</label>
          <input 
            type="number" 
            :id="`evaluaciones_${index}_cantidad_racimos`"
            v-model.number="evaluacion.cantidad_racimos" 
            class="form-control" 
            min="0" 
            required
          >
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label :for="`evaluaciones_${index}_verde`" class="form-label">Verde (%):</label>
            <input 
              type="number" 
              :id="`evaluaciones_${index}_verde`"
              v-model.number="evaluacion.verde" 
              class="form-control" 
              min="0" 
              max="100" 
              required
            >
          </div>
          <div class="col-md-6 mb-3">
            <label :for="`evaluaciones_${index}_maduro`" class="form-label">Maduro (%):</label>
            <input 
              type="number" 
              :id="`evaluaciones_${index}_maduro`"
              v-model.number="evaluacion.maduro" 
              class="form-control" 
              min="0" 
              max="100" 
              required
            >
          </div>
          <div class="col-md-6 mb-3">
            <label :for="`evaluaciones_${index}_sobremaduro`" class="form-label">Sobremaduro (%):</label>
            <input 
              type="number" 
              :id="`evaluaciones_${index}_sobremaduro`"
              v-model.number="evaluacion.sobremaduro" 
              class="form-control" 
              min="0" 
              max="100" 
              required
            >
          </div>
          <div class="col-md-6 mb-3">
            <label :for="`evaluaciones_${index}_pedunculo`" class="form-label">Pedúnculo (%):</label>
            <input 
              type="number" 
              :id="`evaluaciones_${index}_pedunculo`"
              v-model.number="evaluacion.pedunculo" 
              class="form-control" 
              min="0" 
              max="100" 
              required
            >
          </div>
        </div>

        <!-- CONFORMACIÓN: visible solo si variedad === 'hibrido' -->
        <div 
          class="mb-3 conformacion-group" 
          :id="`conformacion_group_${index}`"
          :style="{ display: evaluacion.variedad_fruto === 'hibrido' ? 'block' : 'none' }"
        >
          <label class="form-label">Conformación (múltiple):</label>
          <div class="conformacion-multiple" :id="`conformacion_multiple_${index}`">
            <div 
              v-for="clase in CONFORMACION_CLASSES" 
              :key="clase" 
              class="d-flex mb-2 align-items-center"
            >
              <input 
                type="checkbox" 
                class="form-check-input me-2 conformacion-checkbox"
                :data-index="index"
                :id="`conformacion_${index}_${escapeForId(clase)}`"
                :value="clase"
                :checked="evaluacion.conformacion_seleccionadas.includes(clase)"
                @change="updateConformacionCheckbox(index, clase, $event)"
              >
              <label :for="`conformacion_${index}_${escapeForId(clase)}`" class="me-2 mb-0">{{ clase }}</label>
              <input 
                v-if="evaluacion.conformacion_seleccionadas.includes(clase)"
                type="number" 
                min="0" 
                max="100" 
                class="form-control conformacion-percent"
                style="width: 100px;"
                placeholder="%"
                :data-clase="clase"
                :data-index="index"
                :value="evaluacion.conformacion_porcentajes[clase] || ''"
                @input="updateConformacionPercent(index, clase, $event.target.value)"
              >
            </div>
          </div>

          <!-- hidden input que recibe el string final -->
          <input type="hidden" :id="`evaluaciones_${index}_conformacion`" v-model="evaluacion.conformacion_string">
          
          <div class="conformacion-preview mt-2" v-if="evaluacion.conformacion_string">
            <small><strong>Cadena generada:</strong> {{ evaluacion.conformacion_string }}</small>
          </div>
        </div>

        <div class="mb-3">
          <label :for="`evaluaciones_${index}_observaciones`" class="form-label">Observaciones (Opcional):</label>
          <textarea 
            :id="`evaluaciones_${index}_observaciones`"
            v-model="evaluacion.observaciones" 
            class="form-control" 
            rows="2"
          ></textarea>
        </div>
      </div>
    </div>

    <button 
      type="button" 
      class="btn btn-info mb-3" 
      @click="addEvaluacionForm"
    >
      + Añadir Otra Evaluación
    </button>

    <div class="button-group">
      <button type="button" class="btn btn-primary" @click="guardarEvaluaciones">
        💾 Guardar evaluación
      </button>
      <button type="button" class="btn btn-success" @click="irAFirmas">
        📌 Finalizar Visita
      </button>
      
      <button type="button" class="btn btn-secondary" @click="$router.go(-1)">
        Cancelar
      </button>
    </div>

    <!-- Mostrar evaluaciones guardadas localmente -->
    <div v-if="evaluacionesGuardadas.length > 0" class="mt-4">
      <hr>
      <h4 class="offline-subtitle">📋 Evaluaciones registradas</h4>

      <div 
        v-for="(evaluacionEntry, index) in evaluacionesGuardadas" 
        :key="index" 
        class="list-group mb-4 evaluacion-info-card"
      >
        <li class="list-group-item d-flex justify-content-between">
          <span>Variedad del fruto</span>
          <strong>{{ ucfirst(evaluacionEntry.variedad_fruto) }}</strong>
        </li>
        <li class="list-group-item">
          <span><strong>Cantidad de racimos:</strong></span>
          <p>{{ evaluacionEntry.cantidad_racimos || '0' }}</p>
        </li>
        <li v-for="campo in ['verde', 'maduro', 'sobremaduro', 'pedunculo']" :key="campo" class="list-group-item d-flex justify-content-between">
          <span>{{ evaluacionLabels[campo] }}</span>
          <strong>{{ evaluacionEntry[campo] || '0' }}%</strong>
        </li>
        <li v-if="evaluacionEntry.variedad_fruto === 'hibrido'" class="list-group-item">
          <span><strong>Conformación:</strong></span>
          <div class="mt-2">
            <strong>{{ evaluacionEntry.conformacion || 'No especificada' }}</strong>
          </div>
        </li>
        <li class="list-group-item">
          <span><strong>Observaciones:</strong></span>
          <p>{{ evaluacionEntry.observaciones || 'No registradas' }}</p>
        </li>
        <div class="button-group mt-2 d-flex justify-content-end">
          <button 
            type="button" 
            class="btn btn-warning btn-sm me-2"
            @click="editarEvaluacion(index)"
          >
            ✏️ Editar este registro
          </button>
          <button 
            type="button" 
            class="btn btn-danger btn-sm"
            @click="eliminarEvaluacion(index)"
          >
            🗑️ Eliminar
          </button>
        </div>
      </div>
      <div class="button-group">
        <button type="button" class="btn btn-secondary" @click="$router.go(-1)">
          ⬅️ Volver
        </button>
      </div>
    </div>
    <div v-else>
      <p class="text-muted text-center mt-4">No se han registrado evaluaciones de cosecha aún.</p>
    </div>
  </div>
</template>

<script>
import InfoCard from '../../components/InfoCard.vue';
import { getFormDataByVisita, saveFormData, getAllDataFromStore, deleteDataFromStore } from '../store/indexeddb';

export default {
  components: { InfoCard },
  data() {
    return {
      visitaId: null,
      areas: [],
      fertilizaciones: [],
      polinizaciones: [],
      sanidad: null,
      suelo: null,
      laboresCultivo: [],
      evaluacionesForms: [],
      evaluacionesGuardadas: [],
      canSync: navigator.onLine,
      
      // Clases disponibles para conformación (igual que online)
      CONFORMACION_CLASSES: ['clase 1', 'clase 2', 'clase 3', 'clase 4'],
      
      // Etiquetas para mostrar
      evaluacionLabels: {
        'variedad_fruto': 'Variedad del fruto',
        'cantidad_racimos': 'Cantidad de racimos',
        'verde': 'Verde',
        'maduro': 'Maduro',
        'sobremaduro': 'Sobremaduro',
        'pedunculo': 'Pedúnculo',
        'conformacion': 'Conformación',
      },
      
      camposLabores: {
        polinizacion: 'Polinización',
        limpieza_calle: 'Limpieza Calle',
        limpieza_plato: 'Limpieza Plato',
        poda: 'Poda',
        fertilizacion: 'Fertilización',
        enmiendas: 'Enmiendas',
        ubicacion_tusa_fibra: 'Ubicación Tusa/Fibra',
        ubicacion_hoja: 'Hoja en Barrera',
        lugar_ubicacion_hoja: 'Hoja en Plato',
        plantas_nectariferas: 'Plantas Nectaríferas',
        cobertura: 'Cobertura',
        labor_cosecha: 'Labor Cosecha',
        calidad_fruta: 'Calidad Fruta',
        recoleccion_fruta: 'Recolección Fruta',
        drenajes: 'Drenajes'
      },
      diseaseFieldMapInvertido: {
        'opsophanes': 'Opsophanes',
        'pudricion_cogollo': 'Pudrición del cogollo',
        'raspador': 'Raspador',
        'palmarum': 'Palmarum',
        'strategus': 'Strategus',
        'leptopharsa': 'Leptopharsa',
        'pestalotiopsis': 'Pestalotiopsis',
        'pudricion_basal': 'Pudrición basal',
        'pudricion_estipe': 'Pudrición estipe',
        'vaso_de_escoba': 'Vaso de escoba',
        'lignina': 'Lignina',
        'hojas_rojas': 'Hojas rojas',
        'anillos': 'Anillos',
        'corte_en_v': 'Corte en V'
      }
    };
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || 
                    localStorage.getItem('visita_id');
    localStorage.setItem('visita_id', this.visitaId);

    // Cargar datos previos
    await this.loadDatosPrevios();
    // Cargar evaluaciones existentes
    await this.loadEvaluacionesExistentes();
    
    // Si no hay evaluaciones, añadir una por defecto
    if (this.evaluacionesForms.length === 0) {
      this.addEvaluacionForm();
    }

    window.addEventListener('online', this.updateOnlineStatus);
    window.addEventListener('offline', this.updateOnlineStatus);
  },
  beforeUnmount() {
    window.removeEventListener('online', this.updateOnlineStatus);
    window.removeEventListener('offline', this.updateOnlineStatus);
  },
  methods: {
    getOrdenPlantisBadgeClass(estado) {
      return estado === 'produccion' ? 'bg-success' : 'bg-info';
    },
    
    getEstadoBadgeClass(estado) {
      return estado === 'produccion' ? 'bg-success' : 'bg-info';
    },
    
    getInformacionGeneral(campo) {
      if (this.areas && this.areas.length > 0) {
        return this.areas[0][campo] || 'N/A';
      }
      return 'N/A';
    },
    
    // ⭐⭐ CALCULAR TOTALES ⭐⭐
    calcularTotalDesarrollo() {
      if (!this.areas || this.areas.length === 0) return 0;
      return this.areas.reduce((total, area) => {
        return total + (parseInt(area.numero_palmas_desarrollo) || 0);
      }, 0);
    },
    
    calcularTotalProduccionPalmas() {
      if (!this.areas || this.areas.length === 0) return 0;
      return this.areas.reduce((total, area) => {
        return total + (parseInt(area.numero_palmas_produccion) || 0);
      }, 0);
    },
    
    calcularTotalOrdenPlantis() {
      if (!this.areas || this.areas.length === 0) return 0;
      return this.areas.reduce((total, area) => {
        if (area.aplica_orden_plantis) {
          return total + (parseInt(area.numero_plantas_orden_plantis) || 0);
        }
        return total;
      }, 0);
    },

    updateOnlineStatus() {
      this.canSync = navigator.onLine;
    },
    
    async loadDatosPrevios() {
      try {
        // Cargar todas las áreas
        const allAreas = await getAllDataFromStore('area') || [];
        this.areas = allAreas.filter(area => area.visita_id == this.visitaId);
        
        // Cargar fertilizaciones
        const allFertilizaciones = await getAllDataFromStore('fertilizacion') || [];
        this.fertilizaciones = allFertilizaciones.filter(f => f.visita_id == this.visitaId);
        
        // Cargar polinizaciones
        const allPolinizaciones = await getAllDataFromStore('polinizacion') || [];
        this.polinizaciones = allPolinizaciones.filter(p => p.visita_id == this.visitaId);
        
        // Cargar sanidad
        const allSanidad = await getAllDataFromStore('sanidad') || [];
        this.sanidad = allSanidad.find(s => s.visita_id == this.visitaId) || null;
        
        // Cargar suelo
        const allSuelos = await getAllDataFromStore('suelo') || [];
        this.suelo = allSuelos.find(s => s.visita_id == this.visitaId) || null;
        
        // Cargar labores de cultivo
        const allLabores = await getAllDataFromStore('labores_cultivo') || [];
        this.laboresCultivo = allLabores.filter(l => l.visita_id == this.visitaId);
        
      } catch (error) {
        console.error('Error cargando datos previos:', error);
        this.areas = [];
        this.fertilizaciones = [];
        this.polinizaciones = [];
        this.laboresCultivo = [];
      }
    },
    
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      try {
        const date = new Date(dateString);
        return isNaN(date.getTime()) ? 'Fecha inválida' : 
          date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
          });
      } catch {
        return 'N/A';
      }
    },
    
    async loadEvaluacionesExistentes() {
      try {
        const evaluaciones = await getAllDataFromStore('evaluacion_cosecha') || [];
        if (evaluaciones.length > 0) {
          this.evaluacionesGuardadas = evaluaciones
            .filter(e => e.visita_id == this.visitaId)
            .sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0));
        }
      } catch (error) {
        console.error('Error cargando evaluaciones existentes:', error);
      }
    },
    
    addEvaluacionForm(data = {}) {
      const newEvaluacion = {
        local_id: Date.now().toString(),
        visita_id: this.visitaId,
        variedad_fruto: data.variedad_fruto || '',
        cantidad_racimos: data.cantidad_racimos || '',
        verde: data.verde || '',
        maduro: data.maduro || '',
        sobremaduro: data.sobremaduro || '',
        pedunculo: data.pedunculo || '',
        // Nuevas propiedades para conformación múltiple
        conformacion_string: data.conformacion || '', // String final que se guardará
        conformacion_seleccionadas: [], // Array de clases seleccionadas
        conformacion_porcentajes: {}, // Objeto con porcentajes por clase
        observaciones: data.observaciones || '',
        created_at: new Date().toISOString()
      };

      // Inicializar el objeto de porcentajes
      this.CONFORMACION_CLASSES.forEach(clase => {
        newEvaluacion.conformacion_porcentajes[clase] = '';
      });

      // Si hay datos de conformación, parsearlos
      if (data.conformacion && data.conformacion.trim() !== '') {
        this.preloadConformacionData(newEvaluacion, data.conformacion);
      }

      this.evaluacionesForms.push(newEvaluacion);
    },
    
    removeEvaluacionForm(index) {
      if (!confirm('¿Estás seguro de que quieres eliminar este bloque de formulario de evaluación?')) return;
      this.evaluacionesForms.splice(index, 1);
      if (this.evaluacionesForms.length === 0) {
        this.addEvaluacionForm();
      }
    },
    
    toggleConformacion(index) {
      const evaluacion = this.evaluacionesForms[index];
      if (evaluacion.variedad_fruto !== 'hibrido') {
        // Limpiar datos de conformación
        evaluacion.conformacion_seleccionadas = [];
        this.CONFORMACION_CLASSES.forEach(clase => {
          evaluacion.conformacion_porcentajes[clase] = '';
        });
        evaluacion.conformacion_string = '';
      }
    },
    
    updateConformacionCheckbox(index, clase, event) {
      const evaluacion = this.evaluacionesForms[index];
      const isChecked = event.target.checked;
      
      if (isChecked) {
        if (!evaluacion.conformacion_seleccionadas.includes(clase)) {
          evaluacion.conformacion_seleccionadas.push(clase);
        }
      } else {
        evaluacion.conformacion_seleccionadas = evaluacion.conformacion_seleccionadas.filter(c => c !== clase);
        evaluacion.conformacion_porcentajes[clase] = '';
      }
      this.updateConformacionString(index);
    },
    
    updateConformacionPercent(index, clase, value) {
      const evaluacion = this.evaluacionesForms[index];
      evaluacion.conformacion_porcentajes[clase] = value;
      this.updateConformacionString(index);
    },
    
    updateConformacionString(index) {
      const evaluacion = this.evaluacionesForms[index];
      if (evaluacion.variedad_fruto !== 'hibrido') {
        evaluacion.conformacion_string = '';
        return;
      }
      
      const parts = [];
      evaluacion.conformacion_seleccionadas.forEach(clase => {
        let porcentaje = evaluacion.conformacion_porcentajes[clase];
        if (porcentaje === '' || porcentaje === null || porcentaje === undefined) {
          porcentaje = '0';
        }
        
        // normalizar y evitar valores incorrectos
        let n = parseFloat(porcentaje);
        if (isNaN(n)) n = 0;
        if (n < 0) n = 0;
        if (n > 100) n = 100;
        
        parts.push(`${clase}:${n}%`);
      });
      
      evaluacion.conformacion_string = parts.join(', ');
    },
    
    preloadConformacionData(evaluacion, conformacionString) {
      // Parsea un string como "clase 1:20%, clase 3:50%"
      const pairs = conformacionString.split(',').map(s => s.trim()).filter(s => s.length > 0);
      const map = {};
      
      pairs.forEach(pair => {
        const [rawClase, rawPercent] = pair.split(':').map(x => x && x.trim());
        if (!rawClase) return;
        
        let percent = '';
        if (rawPercent) {
          percent = rawPercent.replace('%', '').trim();
        }
        map[rawClase] = percent;
      });

      // Setear checkboxes y percent inputs
      this.CONFORMACION_CLASSES.forEach(clase => {
        if (map.hasOwnProperty(clase)) {
          evaluacion.conformacion_seleccionadas.push(clase);
          evaluacion.conformacion_porcentajes[clase] = map[clase] || '';
        }
      });
      
      // Actualizar el string
      this.updateConformacionStringForEvaluacion(evaluacion);
    },
    
    updateConformacionStringForEvaluacion(evaluacion) {
      if (evaluacion.variedad_fruto !== 'hibrido') {
        evaluacion.conformacion_string = '';
        return;
      }
      
      const parts = [];
      evaluacion.conformacion_seleccionadas.forEach(clase => {
        let porcentaje = evaluacion.conformacion_porcentajes[clase];
        if (porcentaje === '' || porcentaje === null || porcentaje === undefined) {
          porcentaje = '0';
        }
        
        let n = parseFloat(porcentaje);
        if (isNaN(n)) n = 0;
        if (n < 0) n = 0;
        if (n > 100) n = 100;
        
        parts.push(`${clase}:${n}%`);
      });
      
      evaluacion.conformacion_string = parts.join(', ');
    },
    
    escapeForId(str) {
      return str.replace(/\s+/g, '_').replace(/[^A-Za-z0-9_]/g, '');
    },
    
    ucfirst(str) {
      return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
    },
    
    async guardarEvaluaciones() {
      try {
        // Validar al menos una variedad de fruto seleccionada
        const hasValidForm = this.evaluacionesForms.some(e => e.variedad_fruto);
        if (!hasValidForm) {
          alert('Seleccione al menos una variedad de fruto');
          return;
        }

        // Validar porcentajes de conformación si es híbrido
        for (const evaluacion of this.evaluacionesForms) {
          if (evaluacion.variedad_fruto === 'hibrido' && 
              evaluacion.conformacion_seleccionadas.length > 0) {
            
            // Verificar que todas las clases seleccionadas tengan porcentaje
            const faltanPorcentajes = evaluacion.conformacion_seleccionadas.some(
              clase => !evaluacion.conformacion_porcentajes[clase] && 
                      evaluacion.conformacion_porcentajes[clase] !== 0
            );
            
            if (faltanPorcentajes) {
              alert(`Para la evaluación con híbrido, debe especificar porcentaje para todas las clases seleccionadas`);
              return;
            }
          }
        }

        // Guardar cada evaluación
        for (const evaluacion of this.evaluacionesForms) {
          if (evaluacion.variedad_fruto) {
            const evaluacionToSave = {
              ...evaluacion,
              // Convertir campos numéricos
              cantidad_racimos: parseInt(evaluacion.cantidad_racimos) || 0,
              verde: parseInt(evaluacion.verde) || null,
              maduro: parseInt(evaluacion.maduro) || null,
              sobremaduro: parseInt(evaluacion.sobremaduro) || null,
              pedunculo: parseInt(evaluacion.pedunculo) || null,
              // Usar el string generado para conformacion
              conformacion: evaluacion.variedad_fruto === 'hibrido' ? evaluacion.conformacion_string : null,
              // No guardar las propiedades temporales en IndexedDB
              created_at: evaluacion.created_at || new Date().toISOString(),
              updated_at: new Date().toISOString()
            };
            
            // Eliminar propiedades temporales antes de guardar
            delete evaluacionToSave.conformacion_string;
            delete evaluacionToSave.conformacion_seleccionadas;
            delete evaluacionToSave.conformacion_porcentajes;
            
            await saveFormData('evaluacion_cosecha', evaluacionToSave);
          }
        }
        
        alert('Evaluaciones guardadas correctamente');
        await this.loadEvaluacionesExistentes();
        
        // Limpiar el formulario después de guardar
        this.evaluacionesForms = [];
        this.addEvaluacionForm();
        
      } catch (error) {
        console.error('Error al guardar evaluaciones:', error);
        alert('Error al guardar: ' + error.message);
      }
    },
    
    editarEvaluacion(index) {
      const evaluacion = this.evaluacionesGuardadas[index];
      
      // Cargar la evaluación en el formulario
      this.addEvaluacionForm({
        ...evaluacion,
        conformacion: evaluacion.conformacion || ''
      });
      
      // Opcional: Eliminar la evaluación de la lista después de cargarla
      // this.evaluacionesGuardadas.splice(index, 1);
    },
    
    async eliminarEvaluacion(index) {
      if (!confirm('¿Deseas eliminar este registro de Evaluación?')) return;
      
      try {
        const evaluacion = this.evaluacionesGuardadas[index];
        if (evaluacion.local_id) {
          await deleteDataFromStore('evaluacion_cosecha', evaluacion.local_id);
          this.evaluacionesGuardadas.splice(index, 1);
          alert('Registro eliminado correctamente');
        }
      } catch (error) {
        console.error('Error eliminando evaluación:', error);
        alert('Error al eliminar el registro');
      }
    },
    
    irAFirmas() {
      this.$router.push(`/firmas?visita_id=${this.visitaId}`);
    },
    
    async sincronizar() {
      if (!this.canSync) {
        alert('No hay conexión a internet');
        return;
      }
      alert('Sincronización iniciada...');
      // Aquí iría tu lógica de sincronización
    }
  }
};
</script>

<style scoped>
  @import '../styles/offline.css';


</style>
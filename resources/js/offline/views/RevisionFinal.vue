<template>
  <div class="offline-container offline-form-container">
    <h2 class="offline-title">📋 Revisión Final de Formulario (Modo Offline)</h2>
   <div class="row mb-4">
    <!-- Contenedor principal para las secciones, para que se apilen correctamente -->
    <div class="all-sections-container">
      <!-- Áreas -->
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
      <div class="card border-info mb-4">
        <div class="card-header bg-info text-white">
          🌸 Polinizaciones Registradas
        </div>
        <div class="card-body">
          <div v-if="polinizaciones && polinizaciones.length > 0">
            <div v-for="(poli, index) in polinizaciones" :key="poli.local_id || index" class="mb-3">
              <h5>Polinización #{{ index + 1 }}</h5>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Fecha:</strong> {{ formatDate(poli.fecha) }}</li>
                <li class="list-group-item"><strong>N° Pases:</strong> {{ poli.n_pases || 'N/A' }}</li>
                <li class="list-group-item"><strong>Ciclos:</strong> {{ poli.ciclos_ronda || 'N/A' }}</li>
                <li class="list-group-item"><strong>ANA:</strong> {{ poli.ana || 'N/A' }} ({{ poli.tipo_ana || 'N/A' }})</li>
                <li class="list-group-item"><strong>Talco:</strong> {{ poli.talco || 'N/A' }} kg</li>
              </ul>
            </div>
          </div>
          <p v-else class="text-muted">No hay polinizaciones registradas.</p>
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
      

      <!-- Suelo -->
      <div class="card border-warning mb-4" v-if="suelo">
        <div class="card-header bg-warning text-white">
          🧪 Suelo
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Análisis Foliar:</strong> {{ formatSiNo(suelo.analisis_foliar) }}</li>
            <li class="list-group-item"><strong>Análisis Suelo:</strong> {{ formatSiNo(suelo.alanalisis_suelo) }}</li>
            <li class="list-group-item"><strong>Tipo Suelo:</strong> {{ suelo.tipo_suelo || 'N/A' }}</li>
          </ul>
        </div>
      </div>
      <p v-else class="text-muted text-center mb-4">No hay datos de suelo registrados.</p>

      <!-- Labores de Cultivo -->
      <div class="card border-warning mb-4">
        <div class="card-header bg-warning text-white">
          🚜 Labores de Cultivo
        </div>
        <div class="card-body p-2">
          <div v-if="laboresCultivo && laboresCultivo.length > 0">
            <div v-for="(labor, index) in laboresCultivo" :key="labor.local_id || index" class="mb-3 labor-card">
              <div class="d-flex justify-content-between align-items-center mb-2 bg-light p-2 rounded">
                <h6 class="mb-0">Labor #{{ index + 1 }}</h6>
                <small class="text-muted" v-if="labor.created_at">{{ formatDate(labor.created_at) }}</small>
              </div>
              
              <div class="mb-2" v-if="labor.tipo_planta">
                <span class="badge bg-info text-dark w-100">
                  <strong>Tipo Planta:</strong> {{ ucfirst(labor.tipo_planta) || 'N/A' }}
                </span>
              </div>
              
              <div class="list-group list-group-flush">
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Polinización:</span>
                  <strong>{{ formatPercentage(labor.polinizacion) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Limpieza Calle:</span>
                  <strong>{{ formatPercentage(labor.limpieza_calle) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Limpieza Plato:</span>
                  <strong>{{ formatPercentage(labor.limpieza_plato) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Poda:</span>
                  <strong>{{ formatPercentage(labor.poda) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Fertilización:</span>
                  <strong>{{ formatPercentage(labor.fertilizacion) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Enmiendas:</span>
                  <strong>{{ formatPercentage(labor.enmiendas) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Ubicación Tusa/Fibra:</span>
                  <strong>{{ formatPercentage(labor.ubicacion_tusa_fibra) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Hoja en Barrera:</span>
                  <strong>{{ formatPercentage(labor.ubicacion_hoja) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Hoja en Plato:</span>
                  <strong>{{ formatPercentage(labor.lugar_ubicacion_hoja) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Plantas Nectaríferas:</span>
                  <strong>{{ formatPercentage(labor.plantas_nectariferas) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Cobertura:</span>
                  <strong>{{ formatPercentage(labor.cobertura) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Labor Cosecha:</span>
                  <strong>{{ formatPercentage(labor.labor_cosecha) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Calidad Fruta:</span>
                  <strong>{{ formatPercentage(labor.calidad_fruta) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Recolección Fruta:</span>
                  <strong>{{ formatPercentage(labor.recoleccion_fruta) }}</strong>
                </div>
                <div class="list-group-item py-1 d-flex justify-content-between">
                  <span>Drenajes:</span>
                  <strong>{{ formatPercentage(labor.drenajes) }}</strong>
                </div>
              </div>
              
              <div class="mt-2" v-if="labor.observaciones">
                <div class="alert alert-info p-2 mb-0">
                  <strong>Observaciones:</strong> {{ labor.observaciones }}
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-3">
            <p class="text-muted">No hay labores de cultivo registradas</p>
          </div>
        </div>
      </div>

      <!-- Evaluación de Cosecha -->
      <div class="card border-info mb-4">
        <div class="card-header bg-info text-white">
          🌴 Evaluación de Cosecha
        </div>
        <div class="card-body">
          <div v-if="evaluacionesCosecha && evaluacionesCosecha.length > 0">
            <div v-for="(evaluacion, index) in evaluacionesCosecha" :key="evaluacion.local_id || index" class="mb-3">
              <h5>Evaluación #{{ index + 1 }}</h5>
              <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Variedad:</strong> {{ ucfirst(evaluacion.variedad_fruto) || 'N/A' }}</li>
                <li class="list-group-item"><strong>Racimos:</strong> {{ evaluacion.cantidad_racimos || 'N/A' }}</li>
                <li class="list-group-item"><strong>Verde:</strong> {{ formatPercentage(evaluacion.verde) }}</li>
                <li class="list-group-item"><strong>Maduro:</strong> {{ formatPercentage(evaluacion.maduro) }}</li>
                <li class="list-group-item"><strong>Sobremaduro:</strong> {{ formatPercentage(evaluacion.sobremaduro) }}</li>
                <li class="list-group-item"><strong>Pedúnculo:</strong> {{ formatPercentage(evaluacion.pedunculo) }}</li>
                <li v-if="evaluacion.conformacion" class="list-group-item">
                  <strong>Conformación:</strong> {{ evaluacion.conformacion }}
                </li>
                <li class="list-group-item"><strong>Observaciones:</strong> {{ evaluacion.observaciones || 'N/A' }}</li>
              </ul>
            </div>
          </div>
          <p v-else class="text-muted">No hay evaluaciones de cosecha</p>
        </div>
      </div>
    </div> <!-- Fin all-sections-container -->

    <!-- Cierre de Visita -->
    <div class="card border-secondary mb-4" v-if="cierreVisita && cierreVisita.fecha_cierre">
      <div class="card-header bg-secondary text-white">
        ✅ Cierre de Visita
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <strong>Fecha de Cierre:</strong> {{ formatDate(cierreVisita.fecha_cierre) }}
            </div>
            <div class="mb-3">
              <strong>Estado:</strong> {{ cierreVisita.estado_visita || 'N/A' }}
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <strong>Observaciones:</strong> {{ cierreVisita.observaciones_finales || 'Ninguna' }}
            </div>
            <div class="mb-3">
              <strong>Recomendaciones:</strong> {{ cierreVisita.recomendaciones || 'Ninguna' }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <p v-else class="text-muted text-center mb-4">No hay datos de cierre de visita registrados.</p>

    <!-- Firmas -->
    <div class="card border-dark mb-4" v-if="cierreVisita && (cierreVisita.firma_responsable || cierreVisita.firma_recibe || cierreVisita.firma_testigo)">
      <div class="card-header bg-dark text-white">
        ✍️ Firmas
      </div>
      <div class="card-body">
        <div class="row">
          <!-- Firma Responsable -->
          <div class="col-md-4 mb-3" v-if="cierreVisita.firma_responsable">
            <h5>Realizó la visita</h5>
            <img :src="cierreVisita.firma_responsable" class="img-fluid border" style="max-height: 150px;" alt="Firma Responsable" />
          </div>
          
          <!-- Firma Recibe -->
          <div class="col-md-4 mb-3" v-if="cierreVisita.firma_recibe">
            <h5>Recibió la visita</h5>
            <img :src="cierreVisita.firma_recibe" class="img-fluid border" style="max-height: 150px;" alt="Firma Recibe" />
          </div>
          
          <!-- Firma Testigo -->
          <div class="col-md-4 mb-3" v-if="cierreVisita.firma_testigo">
            <h5>Testigo</h5>
            <img :src="cierreVisita.firma_testigo" class="img-fluid border" style="max-height: 150px;" alt="Firma Testigo" />
          </div>
        </div>
      </div>
    </div>

    <!-- Imágenes -->
    <div class="card border-info mb-4" v-if="cierreVisita && cierreVisita.imagenes && cierreVisita.imagenes.length > 0">
      <div class="card-header bg-info text-white">
        📸 Fotos de la Visita
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-6 col-md-3 mb-3" v-for="(img, idx) in cierreVisita.imagenes" :key="idx">
            <img :src="img" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;" alt="Imagen de la visita" />
          </div>
        </div>
      </div>
    </div>

    <!-- Acciones -->
    <div class="d-flex flex-wrap gap-2 mb-4 justify-content-center">
      <button class="btn btn-primary" @click="generarPDF">
        🖨️ Generar PDF
      </button>
      <button class="btn btn-outline-success" @click="descargarExcel">
        📥 Exportar a Excel
      </button>
      <button 
      class="btn btn-success" 
      @click="sincronizarTodo" 
      :disabled="!isOnline"
      :title="isOnline ? 'Sincronizar datos con el servidor' : 'No hay conexión a internet para sincronizar'"
    >
    
      <span v-if="isOnline">🔄 Sincronizar Datos</span>
      <span v-else>🔴 Sin Conexión</span>
    </button>
    <button type="button" class="btn btn-info" @click="irAInicioLaravel">
        🏠 Ir al Inicio
      </button>

    <!-- Puedes agregar un indicador visual de estado de conexión -->
    <p class="mt-2 text-sm" :class="isOnline ? 'text-green-600' : 'text-red-600'">
      Estado: {{ isOnline ? 'Online' : 'Offline' }}
    </p>
    </div>
    </div>
    </div>

    <!-- Modal de Sincronización -->
<div>
  <!-- Botón para abrir modal -->


  <!-- Modal -->
  <div v-if="mostrar" class="modal-overlay">
    <div class="modal-contenido">
      <h3 v-if="!completo">⏳ Sincronizando...</h3>
      <h3 v-else>✅ ¡Sincronización Completa!</h3>

      <p v-if="!completo">Por favor espera mientras se sincronizan los datos.</p>
      <p v-else>Los datos se sincronizaron correctamente.</p>

      <button v-if="completo" @click="mostrar = false" class="btn btn-success">
        Cerrar
      </button>
    </div>
  </div>
</div>


</template>

<script>
import { getFormDataByVisita, getAllDataFromStore } from '../store/indexeddb'
import { generarResumenPDF } from '../utils/pdfGenerator'
import { exportarResumenExcel } from '../utils/excelExporter'
import { sincronizarDatosOffline } from '../utils/sincronizador'

export default {
  data() {
    return {
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
      'corte_en_v': 'Corte en V',
        // indica si ya terminó
    },mostrar: false,   // controla visibilidad del modal
      completo: false,
      visitaId: null,
      areas: [],
      fertilizaciones: [],
      polinizaciones: [],
      sanidad: null,
      suelo: null,
      laboresCultivo: [],
      evaluacionesCosecha: [],
      isOnline: navigator.onLine,
      cierreVisita: {
        fecha_cierre: '',
        estado_visita: '',
        observaciones: '',
        recomendaciones: '',
        firma_responsable: null,
        firma_recibe: null,
        firma_testigo: null,
        imagenes: []
      }
    }
  },
  async mounted() {
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id')
    localStorage.setItem('visita_id', this.visitaId)

    await this.loadAllData()
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


      
      // Método para capitalizar la primera letra de una cadena
      ucfirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
      },

      
      // Método para formatear fechas
      formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
          const date = new Date(dateString);
          // Opciones de formato, puedes ajustarlas según tu preferencia
          const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
          return date.toLocaleDateString('es-ES', options);
        } catch (e) {
          console.error("Error al formatear fecha:", dateString, e);
          return dateString; // Devuelve la cadena original si hay error
        }
      },
      // Método para formatear porcentajes
      formatPercentage(value) {
        return (value !== null && value !== undefined) ? `${value}%` : 'N/A';
      },
      // Método para formatear "si"/"no" a "Sí"/"No"
      formatSiNo(value) {
        if (value === 'si') return 'Sí';
        if (value === 'no') return 'No';
        return 'N/A';
      },
      irAInicioLaravel() {
          if (!confirm('¿Salir y borrar todos los datos?')) return;
          
          // Limpieza instantánea
          this.cierreVisita = { fecha_cierre: '', estado_visita: '', observaciones: '', recomendaciones: '' };
          this.imagenes = [];
          localStorage.clear();
          
          // Limpieza en segundo plano (no bloqueante)
          try {
            indexedDB.deleteDatabase('NombreDeTuBaseDeDatos');
          } catch (e) {
            console.warn('Error borrando DB:', e);
          }
          
          // Redirección inmediata
          window.location.href = '/dashboard';
        },

         mostrarModal() {
          const modalEl = document.getElementById('miModal');
          const modal = new Modal(modalEl);
          modal.show();
        },


        async loadAllData() {
      try {
        const visitaId = Number(this.visitaId) || this.visitaId
        
        // 🔴 PRIMERO: Cargar información de la visita
        console.log('🔍 Buscando información de visita ID:', visitaId)
        
        // 1. Intentar desde localStorage (la forma más común en tu app)
        const lsKey = 'visita_offline_' + visitaId
        const lsData = localStorage.getItem(lsKey)
        
        if (lsData) {
          this.visitaInfo = JSON.parse(lsData)
          console.log('✅ Visita cargada desde localStorage:', this.visitaInfo)
        } else {
          // 2. Buscar en IndexedDB
          const allVisitas = await getAllDataFromStore('visita') || []
          console.log('📊 Total visitas en IndexedDB:', allVisitas.length)
          
          // Buscar la visita específica por ID - revisar múltiples formatos
          let visitaEncontrada = null
          
          for (const v of allVisitas) {
            // Verificar diferentes estructuras posibles
            const formData = v.formData || v
            
            // Verificar diferentes nombres de campo para el ID
            const posiblesIds = [
              formData.id,
              formData.visita_id,
              formData.local_id,
              v.id,
              v.visita_id,
              v.local_id
            ]
            
            const coincide = posiblesIds.some(id => {
              if (id === undefined || id === null) return false
              const idNum = Number(id)
              const visitaIdNum = Number(visitaId)
              return idNum == visitaIdNum || String(id) === String(visitaId)
            })
            
            if (coincide) {
              visitaEncontrada = formData
              console.log('🎯 Visita encontrada en IndexedDB:', formData)
              break
            }
          }
          
          this.visitaInfo = visitaEncontrada
          
          // 3. Si no se encuentra en IndexedDB, buscar datos dispersos
          if (!this.visitaInfo || Object.keys(this.visitaInfo).length === 0) {
            console.log('⚠️ No se encontró visita estructurada, usando datos dispersos')
            
            this.visitaInfo = {
              // Intentar obtener de localStorage (campos individuales)
              fecha: localStorage.getItem('fecha_visita') || 
                    localStorage.getItem('visita_fecha') || 
                    new Date().toISOString(),
              tecnico_campo: localStorage.getItem('tecnico_nombre') || 
                          localStorage.getItem('usuario_nombre') || 
                          'Técnico No Asignado',
              tipo_visita: localStorage.getItem('tipo_visita') || 'Visita Técnica',
              ubicacion: localStorage.getItem('ubicacion_visita') || 
                        localStorage.getItem('plantacion_ubicacion') || 
                        'Ubicación no especificada',
              proveedor: {
                nombre: localStorage.getItem('proveedor_nombre') || 
                      localStorage.getItem('nombre_proveedor') || 
                      'No especificado'
              },
              plantacion: {
                nombre: localStorage.getItem('plantacion_nombre') || 
                      localStorage.getItem('finca_nombre') || 
                      'No especificado'
              }
            }
            
            // Guardar esta estructura en localStorage para futuras cargas
            localStorage.setItem(lsKey, JSON.stringify(this.visitaInfo))
          }
        }
        
        // 🟡 NORMALIZAR ESTRUCTURA para el PDF
        // Esto es CRÍTICO - el PDF espera una estructura específica
        if (this.visitaInfo) {
          // Si la visita viene con campos planos (proveedor_nombre), convertimos a estructura anidada
          if (this.visitaInfo.proveedor_nombre && !this.visitaInfo.proveedor) {
            this.visitaInfo.proveedor = { 
              nombre: this.visitaInfo.proveedor_nombre 
            }
            delete this.visitaInfo.proveedor_nombre
          }
          
          if (this.visitaInfo.plantacion_nombre && !this.visitaInfo.plantacion) {
            this.visitaInfo.plantacion = { 
              nombre: this.visitaInfo.plantacion_nombre 
            }
            delete this.visitaInfo.plantacion_nombre
          }
          
          // Asegurar que todos los campos requeridos existan
          this.visitaInfo = {
            proveedor: this.visitaInfo.proveedor || { nombre: 'No especificado' },
            plantacion: this.visitaInfo.plantacion || { nombre: 'No especificado' },
            ubicacion: this.visitaInfo.ubicacion || 'No especificada',
            tecnico_campo: this.visitaInfo.tecnico_campo || 'No asignado',
            fecha: this.visitaInfo.fecha || new Date().toISOString(),
            tipo_visita: this.visitaInfo.tipo_visita || 'Visita técnica',
            // Mantener cualquier otro campo adicional
            ...this.visitaInfo
          }
          
          // Eliminar posibles duplicados
          delete this.visitaInfo.proveedor_nombre
          delete this.visitaInfo.plantacion_nombre
        }
        
        console.log('📋 Información de visita normalizada para PDF:', this.visitaInfo)
        
        // 🟡 LUEGO: Cargar todas las otras cosas
        console.log('📂 Cargando áreas para visita ID:', visitaId)
        const allAreas = await getAllDataFromStore('area') || []
        this.areas = allAreas.filter(a => {
          const areaVisitaId = Number(a.visita_id) || a.visita_id
          const coincide = areaVisitaId == visitaId
          if (coincide) {
            console.log(`✅ Área encontrada: ${a.variedad || 'Sin variedad'} (${a.material || 'Sin material'})`)
          }
          return coincide
        })
        console.log(`📊 Total áreas cargadas: ${this.areas.length}`)
        
        // Cargar evaluaciones de cosecha
        console.log('📂 Cargando evaluaciones de cosecha...')
        const allEvaluaciones = await getAllDataFromStore('evaluacion_cosecha') || []
        this.evaluacionesCosecha = allEvaluaciones.filter(e => {
          const evalVisitaId = Number(e.visita_id) || e.visita_id
          return evalVisitaId == visitaId
        })
        console.log(`📊 Total evaluaciones: ${this.evaluacionesCosecha.length}`)
        
        // Cargar fertilizaciones
         const allFertilizaciones = await getAllDataFromStore('fertilizacion') || [];
        this.fertilizaciones = allFertilizaciones.filter(f => f.visita_id == this.visitaId);
        
      
        
        // Cargar polinizaciones
        console.log('📂 Cargando polinizaciones...')
        const allPolinizaciones = await getAllDataFromStore('polinizacion') || []
        this.polinizaciones = allPolinizaciones.filter(p => {
          const poliVisitaId = Number(p.visita_id) || p.visita_id
          return poliVisitaId == visitaId
        })
        console.log(`📊 Total polinizaciones: ${this.polinizaciones.length}`)
        
        // Cargar sanidad
        console.log('📂 Cargando sanidad...')
        const allSanidad = await getAllDataFromStore('sanidad') || []
        this.sanidad = allSanidad.find(s => {
          const sanidadVisitaId = Number(s.visita_id) || s.visita_id
          return sanidadVisitaId == visitaId
        }) || null
        console.log(`📊 Sanidad cargada: ${this.sanidad ? 'Sí' : 'No'}`)
        
        // Cargar suelo
        console.log('📂 Cargando suelo...')
        const allSuelos = await getAllDataFromStore('suelo') || []
        this.suelo = allSuelos.find(s => {
          const sueloVisitaId = Number(s.visita_id) || s.visita_id
          return sueloVisitaId == visitaId
        }) || null
        console.log(`📊 Suelo cargado: ${this.suelo ? 'Sí' : 'No'}`)
        
        // Cargar labores de cultivo
        console.log('📂 Cargando labores de cultivo...')
        const allLabores = await getAllDataFromStore('labores_cultivo') || []
        this.laboresCultivo = allLabores.filter(l => {
          const laborVisitaId = Number(l.visita_id) || l.visita_id
          return laborVisitaId == visitaId
        })
        console.log(`📊 Total labores: ${this.laboresCultivo.length}`)
        
        // Cargar cierre de visita
        console.log('📂 Cargando cierre de visita...')
        const allCierres = await getAllDataFromStore('cierre_visitas') || []
        const cierre = allCierres.find(c => {
          const cierreVisitaId = Number(c.visita_id) || c.visita_id
          return cierreVisitaId == visitaId
        })
        
        if (cierre) {
          this.cierreVisita = {
            ...this.cierreVisita,
            ...cierre
          }
          console.log(`✅ Cierre cargado con ${cierre.imagenes?.length || 0} imágenes`)
        } else {
          console.log('⚠️ No se encontró cierre de visita')
        }
        
        // 🔴 VERIFICACIÓN FINAL - mostrar lo que se cargó
        console.log('=== RESUMEN DE DATOS CARGADOS ===')
        console.log(`🌴 Áreas: ${this.areas.length}`)
        console.log(`💩 Fertilizaciones: ${this.fertilizaciones.length}`)
        console.log(`🌸 Polinizaciones: ${this.polinizaciones.length}`)
        console.log(`🏥 Sanidad: ${this.sanidad ? 'Sí' : 'No'}`)
        console.log(`🌱 Suelo: ${this.suelo ? 'Sí' : 'No'}`)
        console.log(`🔧 Labores: ${this.laboresCultivo.length}`)
        console.log(`📈 Evaluaciones: ${this.evaluacionesCosecha.length}`)
        console.log(`📋 Información de visita: ${this.visitaInfo ? 'Sí' : 'No'}`)
        
        if (this.visitaInfo) {
          console.log('📝 Datos de visita:')
          console.log('- Proveedor:', this.visitaInfo.proveedor?.nombre)
          console.log('- Plantación:', this.visitaInfo.plantacion?.nombre)
          console.log('- Ubicación:', this.visitaInfo.ubicacion)
          console.log('- Técnico:', this.visitaInfo.tecnico_campo)
          console.log('- Fecha:', this.visitaInfo.fecha)
        }
        
        console.log('✅ TODOS los datos cargados exitosamente')
        
      } catch (error) {
        console.error('❌ Error cargando datos:', error)
        console.error('Stack trace:', error.stack)
        alert('Error al cargar los datos: ' + error.message)
      }
    },
    // En tu componente, actualiza el método generarPDF:
  async generarPDF() {
  // Verificar que tenemos datos mínimos
  if (!this.visitaInfo || !this.visitaInfo.proveedor || !this.visitaInfo.plantacion) {
    console.warn('⚠️ Información de visita incompleta, forzando carga...')
    await this.loadAllData()
  }
  
  // Mostrar qué vamos a enviar al PDF
  console.log('📤 Enviando al generador de PDF:')
  console.log('Proveedor:', this.visitaInfo?.proveedor?.nombre || 'No definido')
  console.log('Plantación:', this.visitaInfo?.plantacion?.nombre || 'No definida')
  console.log('Áreas:', this.areas?.length || 0)
  console.log('Fertilizaciones:', this.fertilizaciones?.length || 0)
  
  await generarResumenPDF({
    areas: this.areas || [],
    fertilizaciones: this.fertilizaciones || [],
    polinizaciones: this.polinizaciones || [],
    sanidad: this.sanidad,
    suelo: this.suelo,
    laboresCultivo: this.laboresCultivo || [],
    evaluacionesCosecha: this.evaluacionesCosecha || [],
    cierreVisita: this.cierreVisita || {},
    visitaInfo: this.visitaInfo || {
      proveedor: { nombre: 'No especificado' },
      plantacion: { nombre: 'No especificado' },
      ubicacion: 'No especificada',
      tecnico_campo: 'No asignado',
      fecha: new Date().toISOString()
    },
    headerImagePath: '/images/header.png',
    footerImagePath: '/images/footer.png'
  });
},

    descargarExcel() {
      exportarResumenExcel({
        areas: this.areas,
        fertilizaciones: this.fertilizaciones,
        polinizaciones: this.polinizaciones,
        sanidad: this.sanidad,
        suelo: this.suelo,
        laboresCultivo: this.laboresCultivo,
        evaluacionesCosecha: this.evaluacionesCosecha,
        cierreVisita: this.cierreVisita,
      })
    },
    async sincronizarTodo() {
          if (!this.isOnline) {
            alert('No hay conexión a internet. Por favor, conéctate para sincronizar los datos.');
            return;
          }

          this.mostrar = true;
          this.completo = false;


          try {
            // 🔹 Primero sincronizas todo
            await sincronizarDatosOffline();

            // 🔹 Luego actualizas el estado de la visita a "finalizado"
            const visitaId = this.visita_id; // <-- asegúrate de tener este valor en tu componente
            await fetch(`/visitas/${visitaId}/update-status`, {
              method: 'PUT',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              }
            });
            this.completo = true;


            alert("✅ Sincronización completa y estado de la visita actualizado.");
          } catch (error) {
            console.error('Error general al iniciar la sincronización:', error);
            this.mostrar = false;
          } finally {
            if (modalInstance) modalInstance.close();

          }
        },

    
    /**
     * Actualiza el estado 'isOnline' cuando el navegador se conecta.
     */
    handleOnline() {
      this.isOnline = true;
      console.log('Conexión reestablecida.');
    },

    /**
     * Actualiza el estado 'isOnline' cuando el navegador se desconecta.
     */
    handleOffline() {
      this.isOnline = false;
      console.warn('Conexión perdida. Operando en modo offline.');
    }
  }
}
</script>

<style scoped>
/* Importa tus estilos base de offline.css */
@import '../styles/offline.css';

/* Estilos Base para Contenedores de Formularios */
.offline-form-container {
    background-color: rgba(90, 104, 84, 0.968);
    padding: 20px;
    margin-top: 25px; /* Margen superior para desktop */
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    max-width: 900px; /* Ancho máximo para pantallas grandes */
   
    box-sizing: border-box; /* Asegura que padding y border se incluyan en el ancho total */
}

/* Contenedor para todas las secciones de datos previos */
.all-sections-container {
  display: flex;
  flex-direction: column; /* Apila las secciones por defecto (móvil) */
  gap: 20px; /* Espacio entre las tarjetas de sección */
}

/* Estilos para Títulos de Formularios */
.offline-title {
    text-align: center;
    color: #2F4F4F; /* Un color oscuro para el título */
    margin-bottom: 25px;
    font-size: 1.8em; /* Tamaño de fuente para el título */
}

/* Estilos para las tarjetas de información */
.card {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.card-header {
    font-weight: bold;
    padding: 10px 15px;
    border-bottom: 1px solid rgba(0,0,0,.125);
    border-top-left-radius: calc(0.25rem - 1px);
    border-top-right-radius: calc(0.25rem - 1px);
}

.card-body {
    padding: 15px;
}

.list-group-item {
    padding: 8px 15px;
    border-color: #f0f0f0;
}

.list-group-item:last-child {
    border-bottom: none;
}

/* Colores específicos para las tarjetas */
.card.border-success { border-color: #28a745 !important; }
.card-header.bg-success { background-color: #28a745 !important; }

.card.border-primary { border-color: #007bff !important; }
.card-header.bg-primary { background-color: #007bff !important; }

.card.border-info { border-color: #17a2b8 !important; }
.card-header.bg-info { background-color: #17a2b8 !important; }

.card.border-danger { border-color: #dc3545 !important; }
.card-header.bg-danger { background-color: #dc3545 !important; }

.card.border-warning { border-color: #ffc107 !important; }
.card-header.bg-warning { background-color: #ffc107 !important; }

.card.border-secondary { border-color: #6c757d !important; }
.card-header.bg-secondary { background-color: #6c757d !important; }

.card.border-dark { border-color: #343a40 !important; }
.card-header.bg-dark { background-color: #343a40 !important; }


/* Estilos para los botones de acción */
.btn {
  padding: 10px 20px;
  border-radius: 0.25rem;
  font-size: 1em;
  cursor: pointer;
  transition: background-color 0.2s ease, transform 0.2s ease;
  border: none;
}

.btn-primary { background-color: #007bff; color: white; }
.btn-primary:hover { background-color: #0056b3; transform: translateY(-1px); }

.btn-success { background-color: #28a745; color: white; }
.btn-success:hover { background-color: #1e7e34; transform: translateY(-1px); }

.btn-outline-success { border-color: #28a745; color: #28a745; background-color: transparent;}
.btn-outline-success:hover { background-color: #28a745; color: white; }


/* Estilos para imágenes */
.img-thumbnail {
  max-height: 150px;
  object-fit: contain;
}

/* Responsividad */
@media (max-width: 967.98px) { /* Para pantallas más pequeñas que md (768px) */
 
    .offline-form-container {
        padding: 15px;
        border-radius: 0;
        box-shadow: none;
        width: 300px !important; 
        margin-left: -210px !important;
        padding-top: 90px !important; 
        box-sizing: border-box; 
    }

    .offline-title {
        font-size: 1.5em;
        margin-bottom: 20px;
    }

    .card {
      margin-bottom: 15px; /* Menos espacio entre tarjetas en móvil */
    }

    .d-flex.flex-wrap.gap-2 {
      flex-direction: column; /* Apila los botones de acción en móvil */
      gap: 10px;
    }
    .d-flex.flex-wrap.gap-2 .btn {
      width: 100%; /* Botones de acción ocupan todo el ancho */
    }

    /* Ajustes para las imágenes de firmas */
    .card-body .row .col-md-4,
    .card-body .row .col-6 {
        flex: 0 0 100%; /* Cada imagen ocupa su propia fila en móvil */
        max-width: 100%;
    }
    .card-body img {
        max-height: 120px !important; /* Ajusta la altura máxima de las imágenes en móvil */
        object-fit: contain; /* Asegura que la imagen completa sea visible */
    }
}

.modal-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}
.modal-contenido {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  width: 350px;
  text-align: center;
}

</style>

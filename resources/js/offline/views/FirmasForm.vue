<template>
  <div class="offline-container" >
    <h2 class="offline-title">📋 Resumen de Visita - Firmas</h2>
    
    <!-- Sección de Datos Previos -->
    <div class="row mb-4">
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
            <div v-if="fertilizaciones && fertilizaciones.length > 0">
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

      <div v-if="polinizaciones && polinizaciones.length > 0">
        <div class="card-header bg-success text-white">
            🌸 Polinizaciones Registradas
          </div>
        
        <div v-for="(poli, index) in polinizaciones" :key="poli.local_id" class="card border-info form-group mb-3">
          <div class="card-header bg-info text-white">
            🌸 Polinización #{{ index + 1 }}
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item"><strong>Fecha:</strong> {{ poli.fecha }}</li>
              <li class="list-group-item"><strong>N° Pases:</strong> {{ poli.n_pases }}</li>
              <li class="list-group-item"><strong>Ciclos:</strong> {{ poli.ciclos_ronda }}</li>
              <li class="list-group-item"><strong>ANA:</strong> {{ poli.ana }} ({{ poli.tipo_ana }})</li>
              <li class="list-group-item"><strong>Talco:</strong> {{ poli.talco }} kg</li>
            </ul>
          </div>
        </div>
      </div>
      <p v-else class="text-muted">No hay polinizaciones registradas.</p>   

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
              <!-- Agrega más campos de suelo según sea necesario -->
            </ul>
          </div>
        </div>
      </div>
      <!-- Labores de Cultivo -->
      <div class="col-12">
        <div class="card border-warning mb-3">
          <div class="card-header bg-warning text-white">
            🚜 Labores de Cultivo
          </div>
          <div class="card-body p-2">
            <div v-if="laboresCultivo && laboresCultivo.length > 0">
              <div v-for="(labor, index) in laboresCultivo" :key="labor.id || index" class="mb-3 labor-card">
                <!-- Encabezado -->
                <div class="d-flex justify-content-between align-items-center mb-2 bg-light p-2 rounded">
                  <h6 class="mb-0">Labor #{{ index + 1 }}</h6>
                  <small class="text-muted">{{ formatDate(labor.created_at) }}</small>
                </div>
                
                <!-- Tipo de Planta -->
                <div class="mb-2">
                  <span class="badge bg-info text-dark w-100">
                    <strong>Tipo Planta:</strong> {{ ucfirst(labor.tipo_planta) || 'N/A' }}
                  </span>
                </div>
                
                <!-- Lista de labores -->
                <div class="list-group">
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Polinización:</span>
                    <strong>{{ labor.polinizacion || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Limpieza Calle:</span>
                    <strong>{{ labor.limpieza_calle || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Limpieza Plato:</span>
                    <strong>{{ labor.limpieza_plato || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Poda:</span>
                    <strong>{{ labor.poda || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Fertilización:</span>
                    <strong>{{ labor.fertilizacion || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Enmiendas:</span>
                    <strong>{{ labor.enmiendas || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Ubicación Tusa/Fibra:</span>
                    <strong>{{ labor.ubicacion_tusa_fibra || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Hoja en Barrera:</span>
                    <strong>{{ labor.ubicacion_hoja || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Hoja en Plato:</span>
                    <strong>{{ labor.lugar_ubicacion_hoja || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Plantas Nectaríferas:</span>
                    <strong>{{ labor.plantas_nectariferas || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Cobertura:</span>
                    <strong>{{ labor.cobertura || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Labor Cosecha:</span>
                    <strong>{{ labor.labor_cosecha || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Calidad Fruta:</span>
                    <strong>{{ labor.calidad_fruta || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Recolección Fruta:</span>
                    <strong>{{ labor.recoleccion_fruta || 0 }}%</strong>
                  </div>
                  <div class="list-group-item py-1 d-flex justify-content-between">
                    <span>Drenajes:</span>
                    <strong>{{ labor.drenajes || 0 }}%</strong>
                  </div>
                </div>
                
                <!-- Observaciones -->
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
      </div>
      </div>

      <!-- Evaluación de Cosecha -->
      <div class="col-md-6">
        <div class="card border-info">
          <div class="card-header bg-info text-white">
            🌴 Evaluación de Cosecha
          </div>
          <div class="card-body">
            <div v-if="evaluacionesCosecha && evaluacionesCosecha.length > 0">
              <div v-for="(evaluacion, index) in evaluacionesCosecha" :key="index" class="mb-3">
                <h5>Evaluación #{{ index + 1 }}</h5>
                <ul class="list-group">
                  <li class="list-group-item"><strong>Variedad:</strong> {{ ucfirst(evaluacion.variedad_fruto) }}</li>
                  <li class="list-group-item"><strong>Racimos:</strong> {{ evaluacion.cantidad_racimos }}</li>
                  <li class="list-group-item"><strong>Verde:</strong> {{ evaluacion.verde }}%</li>
                  <li class="list-group-item"><strong>Maduro:</strong> {{ evaluacion.maduro }}%</li>
                  <li class="list-group-item"><strong>Sobremaduro:</strong> {{ evaluacion.sobremaduro }}%</li>
                  <li class="list-group-item"><strong>Pedúnculo:</strong> {{ evaluacion.pedunculo }}%</li>
                  <li v-if="evaluacion.conformacion" class="list-group-item">
                    <strong>Conformación:</strong> {{ evaluacion.conformacion }}
                  </li>
                </ul>
              </div>
            </div>
            <p v-else class="text-muted">No hay evaluaciones de cosecha</p>
          </div>
        </div>
      </div>

      <!-- Resto de formularios (fertilización, polinización, etc.) -->
      <!-- ... -->
   

    <h2>🖊️ Registro de Firmas y Cierre de Visita (Modo Offline)</h2>

    <!-- Campos para Cierre de Visita -->
    <div class="form-group">
      <label for="fechaCierre" class="form-label">📅 Fecha de Cierre *</label>
      <input type="date" id="fechaCierre" class="form-control" v-model="cierreVisita.fecha_cierre" required />
    </div>

    <div class="form-group">
      <label for="estadoVisita" class="form-label">📊 Estado de la Visita *</label>
      <select id="estadoVisita" class="form-control" v-model="cierreVisita.estado_visita" required>
        <option value="">Seleccione</option>
        <option value="completado">Completado</option>
        <option value="pendiente">Pendiente</option>
        <option value="cancelado">Cancelado</option>
      </select>
    </div>

    <div class="form-group">
      <label for="observacionesFinales" class="form-label">📝 Observaciones Finales (Opcional)</label>
      <textarea id="observacionesFinales" class="form-control" rows="3" v-model="cierreVisita.observaciones"></textarea>
    </div>

    <!-- ✅ Campo Recomendaciones (NUEVO - si lo tienes en DB y lo quieres enviar) -->
    <div class="form-group">
      <label for="recomendaciones" class="form-label">💡 Recomendaciones (Opcional)</label>
      <textarea id="recomendaciones" class="form-control" rows="3" v-model="cierreVisita.recomendaciones"></textarea>
    </div>

    <!-- Firma: Realiza la visita -->
    <div class="form-group">
      <h5>✍️ Firma de quien realiza la visita *</h5>
      <canvas ref="firmaRealiza" class="firma-canvas border" width="300" height="150"></canvas>
      <div class="mt-2">
        <button class="btn btn-sm btn-secondary" @click="limpiarFirma('realiza')">🧹 Limpiar</button>
      </div>
    </div>

    <!-- Firma: Recibe la visita -->
    <div class="form-group">
      <h5>✍️ Firma de quien recibe la visita *</h5>
      <canvas ref="firmaRecibe" class="firma-canvas border" width="300" height="150"></canvas>
      <div class="mt-2">
        <button class="btn btn-sm btn-secondary" @click="limpiarFirma('recibe')">🧹 Limpiar</button>
      </div>
    </div>

    <!-- Firma: Testigo (opcional) -->
    <div class="form-group">
      <h5>✍️ Firma del testigo (opcional)</h5>
      <canvas ref="firmaTestigo" class="firma-canvas border" width="300" height="150"></canvas>
      <div class="mt-2">
        <button class="btn btn-sm btn-secondary" @click="limpiarFirma('testigo')">🧹 Limpiar</button>
      </div>
    </div>

    <!-- Galería de imágenes -->
    <div class="mb-3">
      <h5>📸 Fotos de la visita (opcional)</h5>
      <!-- ✅ CAMBIO AQUÍ: Eliminado capture="environment" -->
      <input type="file" accept="image/*" multiple @change="cargarImagenes" />
      <div class="row mt-3">
        <div class="col-4" v-for="(img, i) in imagenes" :key="i">
          <img :src="img" class="img-thumbnail mb-2" />
        </div>
      </div>
        <button @click="guardar" class="btn btn-primary">💾 Guardar Cierre de Visita</button>
    <button type="button" class="btn btn-success" @click="verRevisionFinal">
      ➡️ Ver Revisión Final
    </button>
    </div>
 </div>
    <!-- Acciones -->
  

  
</template>

<script>
import SignaturePad from 'signature_pad'
import { saveFormData, getFormDataByVisita, getAllDataFromStore } from '../store/indexeddb' // Asegúrate de importar getAllDataFromStore

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
      'corte_en_v': 'Corte en V'
    },
      visitaId: null,
      areas: [],
      evaluacionesCosecha: [],
      fertilizaciones: [],
      polinizaciones: [],
      sanidad: null,
      suelo: null,
      laboresCultivo: [],
      firmaPadRealiza: null,
      firmaPadRecibe: null,
      firmaPadTestigo: null,
      imagenes: [],
      cierreVisita: {
        fecha_cierre: '',
        estado_visita: '',
        observaciones: '',
        recomendaciones: '' // ✅ Añadido el campo recomendaciones
      }
    }
  },
  async mounted() { // Usar async mounted para await
    this.visitaId = new URLSearchParams(window.location.search).get('visita_id') || localStorage.getItem('visita_id')
    localStorage.setItem('visita_id', this.visitaId)

    // Inicializar SignaturePads
    this.firmaPadRealiza = new SignaturePad(this.$refs.firmaRealiza)
    this.firmaPadRecibe = new SignaturePad(this.$refs.firmaRecibe)
    this.firmaPadTestigo = new SignaturePad(this.$refs.firmaTestigo)

    // Cargar datos existentes de cierre de visita si los hay
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
    
    async loadAllData() {
      try {
        // Convertir visitaId a número si es necesario
        const visitaId = Number(this.visitaId) || this.visitaId
        
        // Cargar todas las áreas
        const allAreas = await getAllDataFromStore('area') || []
        this.areas = allAreas.filter(a => {
          // Comparación flexible de visita_id
          const areaVisitaId = Number(a.visita_id) || a.visita_id
          return areaVisitaId == visitaId
        })
        
        // Cargar evaluaciones de cosecha
        const allEvaluaciones = await getAllDataFromStore('evaluacion_cosecha') || []
        this.evaluacionesCosecha = allEvaluaciones.filter(e => {
          const evalVisitaId = Number(e.visita_id) || e.visita_id
          return evalVisitaId == visitaId
        })
        
        // Cargar fertilizaciones
        const allFertilizaciones = await getAllDataFromStore('fertilizacion') || []
        this.fertilizaciones = allFertilizaciones.filter(f => {
          const fertVisitaId = Number(f.visita_id) || f.visita_id
          return fertVisitaId == visitaId
        })
        
        // Cargar polinizaciones
        const allPolinizaciones = await getAllDataFromStore('polinizacion') || []
        this.polinizaciones = allPolinizaciones.filter(p => {
          const poliVisitaId = Number(p.visita_id) || p.visita_id
          return poliVisitaId == visitaId
        })
        
        // Cargar sanidad
        const allSanidad = await getAllDataFromStore('sanidad') || []
        this.sanidad = allSanidad.find(s => {
          const sanidadVisitaId = Number(s.visita_id) || s.visita_id
          return sanidadVisitaId == visitaId
        }) || null
        
        // Cargar suelo
        const allSuelos = await getAllDataFromStore('suelo') || []
        this.suelo = allSuelos.find(s => {
          const sueloVisitaId = Number(s.visita_id) || s.visita_id
          return sueloVisitaId == visitaId
        }) || null
        
        // Cargar labores de cultivo
        const allLabores = await getAllDataFromStore('labores_cultivo') || []
        this.laboresCultivo = allLabores.filter(l => {
          const laborVisitaId = Number(l.visita_id) || l.visita_id
          return laborVisitaId == visitaId
        })
        
        // Cargar datos existentes de cierre de visita
        await this.loadExistingCierreVisitaData()
        
      } catch (error) {
        console.error('Error cargando datos:', error)
        
      }
    },
    limpiarFirma(tipo) {
      if (tipo === 'realiza') this.firmaPadRealiza.clear()
      if (tipo === 'recibe') this.firmaPadRecibe.clear()
      if (tipo === 'testigo') this.firmaPadTestigo.clear()
    },
    cargarImagenes(event) {
      const files = event.target.files
      for (let i = 0; i < files.length; i++) {
        const reader = new FileReader()
        reader.onload = e => {
          this.imagenes.push(e.target.result)
        }
        reader.readAsDataURL(files[i])
      }
    },
    async guardar() {
      try {
        // Validaciones previas (firmas, campos obligatorios)
        if (this.firmaPadRealiza.isEmpty() || this.firmaPadRecibe.isEmpty()) {
          alert('⚠️ Las dos firmas obligatorias deben estar diligenciadas');
          return;
        }
        if (!this.cierreVisita.fecha_cierre || !this.cierreVisita.estado_visita) {
          alert('⚠️ La Fecha de Cierre y el Estado de la Visita son obligatorios.');
          return;
        }

        // Preparar datos para guardar
        const dataToSave = {
          visita_id: this.visitaId,
          fecha_cierre: this.cierreVisita.fecha_cierre,
          estado_visita: this.cierreVisita.estado_visita,
          observaciones_finales: this.cierreVisita.observaciones,
          recomendaciones: this.cierreVisita.recomendaciones,
          firma_responsable: this.firmaPadRealiza.toDataURL('image/png'), // Especificar formato PNG
          firma_recibe: this.firmaPadRecibe.toDataURL('image/png'),
          firma_testigo: this.firmaPadTestigo.isEmpty() ? null : this.firmaPadTestigo.toDataURL('image/png'),
          imagenes: this.imagenes.map(img => img.startsWith('data:') ? img : `data:image/jpeg;base64,${img}`),
          finalizada_en: new Date().toISOString()
        };

        // Guardar en IndexedDB
        await saveFormData('cierre_visitas', dataToSave);
        
        // Limpiar el formulario
        this.resetForm();
        
        alert('✅ Cierre de Visita guardado localmente. Se sincronizará cuando haya conexión.');
      } catch (error) {
        console.error('Error al guardar:', error);
        alert('❌ Error al guardar: ' + error.message);
      }
    },

    resetForm() {
      this.cierreVisita = {
        fecha_cierre: '',
        estado_visita: '',
        observaciones: '',
        recomendaciones: ''
      };
      this.imagenes = [];
      this.firmaPadRealiza.clear();
      this.firmaPadRecibe.clear();
      this.firmaPadTestigo.clear();
    },
    verRevisionFinal() {
      this.$router.push(`/revisionfinal?visita_id=${this.visitaId}`);
    },
    async loadExistingCierreVisitaData() { // Renombrado para mayor claridad
      try {
        const existingCierre = await getFormDataByVisita('cierre_visitas', this.visitaId);
        if (existingCierre) {
          this.cierreVisita = {
            fecha_cierre: existingCierre.fecha_cierre || '',
            estado_visita: existingCierre.estado_visita || '',
            observaciones: existingCierre.observaciones_finales || existingCierre.observaciones || '', // Compatibilidad
            recomendaciones: existingCierre.recomendaciones || '',
          };
          this.imagenes = existingCierre.imagenes || [];

          // Cargar firmas existentes en los SignaturePads si no están vacías
          if (existingCierre.firma_responsable && this.firmaPadRealiza) {
            this.firmaPadRealiza.fromDataURL(existingCierre.firma_responsable);
          }
          if (existingCierre.firma_recibe && this.firmaPadRecibe) {
            this.firmaPadRecibe.fromDataURL(existingCierre.firma_recibe);
          }
          if (existingCierre.firma_testigo && this.firmaPadTestigo) {
            this.firmaPadTestigo.fromDataURL(existingCierre.firma_testigo);
          }
        }
      } catch (error) {
        console.error('Error cargando datos de cierre de visita existentes:', error);
      }
    },
    ucfirst(str) {
      return str ? str.charAt(0).toUpperCase() + str.slice(1) : ''
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A'
      try {
        const date = new Date(dateString)
        return isNaN(date.getTime()) ? 'Fecha inválida' : 
          date.toLocaleDateString('es-ES')
      } catch {
        return 'N/A'
      }
    },
  }
}
</script>

<style scoped>
@import '../styles/offline.css';
.firma-canvas {
  background-color: #fff;
  width: 100%;
  max-width: 300px;
  height: 150px; /* Corregido el error tipográfico '150p' a '150px' */
  border: 1px solid #ccc; /* Añadido borde para mejor visualización */
}
</style>

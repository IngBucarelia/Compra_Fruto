@extends('layouts.app')

@section('content')
<div class="container">
<h3>🌴🌴 Información de plantación - Detalle Completo de Visita Realizado el {{ $visita->fecha }}  🌴🌴 <br> Proveedor:<span class="text-primary"> {{ $visita->proveedor->proveedor_nombre }} </span><br> Plantación:
    <span class="text-primary">{{ $visita->plantacion->nombre ?? 'Sin nombre de plantación' }}</span>
</h3>
    <div class="accordion mt-4" id="acordeonDetalleVisita">

        {{-- Área --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingArea">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea" aria-expanded="true">
                    📍 Área registrada
                </button>
            </h2>
            <div id="collapseArea" class="accordion-collapse collapse show" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->area)
                        <ul>
                            <li><strong>Material:</strong> {{ $visita->area->material }}</li>
                            <li><strong>Estado:</strong> {{ $visita->area->estado }}</li>
                            <li><strong>Año siembra:</strong> {{ $visita->area->anio_siembra }}</li>
                            <li><strong>Área (m²):</strong> {{ $visita->area->area }}</li>
                            <li><strong>Orden Plantis:</strong> {{ $visita->area->orden_plantis_numero }}</li>
                            <li><strong>Estado orden Plantis:</strong> {{ $visita->area->estado_orden_plantis }}</li>
                        </ul>
                    @else
                        <p>No se ha registrado información de área.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Fertilizaciones --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFert">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFert">
                    💧 Fertilizaciones
                </button>
            </h2>
            <div id="collapseFert" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->fertilizaciones->count())
                        @foreach ($visita->fertilizaciones as $fertilizacion)
                            <div class="mb-3">
                                <strong>Fecha:</strong> {{ $fertilizacion->fecha_fertilizacion }}
                                <ul>
                                    @foreach ($fertilizacion->fertilizantes as $f)
                                        <li>{{ ucfirst($f->nombre) }} - {{ $f->cantidad }} kg</li>
                                    @endforeach
                                </ul>
                                <hr>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay fertilizaciones registradas.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Polinizaciones --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPol">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePol">
                    🌸 Polinizaciones
                </button>
            </h2>
            <div id="collapsePol" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->polinizaciones->count())
                        <ul class="list-group">
                            @foreach ($visita->polinizaciones as $poli)
                                <li class="list-group-item">
                                    📅 {{ $poli->fecha }} |
                                    Pases: <strong>{{ $poli->n_pases }}</strong>,
                                    Ronda: <strong>{{ $poli->ciclos_ronda }}</strong>,
                                    ANA: <strong>{{ $poli->ana }}</strong> ({{ $poli->tipo_ana }}),
                                    Talco: <strong>{{ $poli->talco }}</strong>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No hay polinizaciones registradas.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sanidad --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSanidad">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSanidad">
                    🧪 Sanidad
                </button>
            </h2>
            <div id="collapseSanidad" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->sanidad)
                        <ul>
                            <li><strong>Opsophanes:</strong> {{ $visita->sanidad->opsophanes }}%</li>
                            <li><strong>Pudrición cogollo:</strong> {{ $visita->sanidad->pudricion_cogollo }}%</li>
                            <li><strong>Raspador:</strong> {{ $visita->sanidad->raspador }}%</li>
                            <li><strong>Palmarum:</strong> {{ $visita->sanidad->palmarum }}%</li>
                            <li><strong>Strategus:</strong> {{ $visita->sanidad->strategus }}%</li>
                            <li><strong>Leptoparsha:</strong> {{ $visita->sanidad->leptoparsha }}%</li>
                            <li><strong>Pestalotiopsis:</strong> {{ $visita->sanidad->pestalotiopsis }}%</li>
                            <li><strong>Pudrición basal:</strong> {{ $visita->sanidad->pudricion_basal }}%</li>
                            <li><strong>Pudrición estipe:</strong> {{ $visita->sanidad->pudricion_estipe }}%</li>
                            <li><strong>Otros:</strong> {{ $visita->sanidad->otros }}</li>
                            <li><strong>Observaciones:</strong> {{ $visita->sanidad->observaciones }}</li>
                        </ul>
                    @else
                        <p>No se ha registrado información de sanidad.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Suelo --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingSuelo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSuelo">
                    🧬 Análisis de Suelo
                </button>
            </h2>
            <div id="collapseSuelo" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->suelo)
                        <ul>
                            <li><strong>Análisis foliar:</strong> {{ ucfirst($visita->suelo->analisis_foliar) }}</li>
                            <li><strong>Análisis suelo:</strong> {{ ucfirst($visita->suelo->alanisis_suelo) }}</li>
                            <li><strong>Tipo de suelo:</strong> {{ ucfirst($visita->suelo->tipo_suelo) }}</li>
                        </ul>
                    @else
                        <p>No se ha registrado análisis de suelo.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Labores de Cultivo --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingLabores">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLabores">
                    🌾 Labores de Cultivo
                </button>
            </h2>
            <div id="collapseLabores" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->laboresCultivo)
                        <ul>
                            <li><strong>Polinización:</strong> {{ $visita->laboresCultivo->polinizacion }}%</li>
                            <li><strong>Limpieza calle:</strong> {{ $visita->laboresCultivo->limpieza_calle }}%</li>
                            <li><strong>Limpieza plato:</strong> {{ $visita->laboresCultivo->limpieza_plato }}%</li>
                            <li><strong>Poda:</strong> {{ $visita->laboresCultivo->poda }}%</li>
                            <li><strong>Fertilización:</strong> {{ $visita->laboresCultivo->fertilizacion }}%</li>
                            <li><strong>Enmiendas:</strong> {{ $visita->laboresCultivo->enmiendas }}%</li>
                            <li><strong>Ubicación tusa/fibra:</strong> {{ $visita->laboresCultivo->ubicacion_tusa_fibra }}%</li>
                            <li><strong>Ubicación hoja:</strong> {{ $visita->laboresCultivo->ubicacion_hoja }}%</li>
                            <li><strong>Lugar hoja:</strong> {{ $visita->laboresCultivo->lugar_ubicacion_hoja }}%</li>
                            <li><strong>Plantas nectaríferas:</strong> {{ $visita->laboresCultivo->plantas_nectariferas }}%</li>
                            <li><strong>Cobertura:</strong> {{ $visita->laboresCultivo->cobertura }}%</li>
                            <li><strong>Labor cosecha:</strong> {{ $visita->laboresCultivo->labor_cosecha }}%</li>
                            <li><strong>Calidad fruta:</strong> {{ $visita->laboresCultivo->calidad_fruta }}%</li>
                            <li><strong>Recolección fruta:</strong> {{ $visita->laboresCultivo->recoleccion_fruta }}%</li>
                            <li><strong>Drenajes:</strong> {{ $visita->laboresCultivo->drenajes }}%</li>
                        </ul>
                    @else
                        <p class="text-muted">No se han registrado labores de cultivo.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Evaluación de Cosecha en Campo --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingEvaluacionCosecha">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEvaluacionCosecha">
                    🍌 Evaluación de Cosecha en Campo
                </button>
            </h2>
            <div id="collapseEvaluacionCosecha" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleVisita">
                <div class="accordion-body">
                    @if ($visita->evaluacionCosechaCampo)
                        <ul>
                            <li><strong>Variedad de Fruto:</strong> {{ ucfirst($visita->evaluacionCosechaCampo->variedad_fruto) }}</li>
                            <li><strong>Cantidad de Racimos:</strong> {{ $visita->evaluacionCosechaCampo->cantidad_racimos }}</li>
                            <li><strong>Verde:</strong> {{ $visita->evaluacionCosechaCampo->verde }}%</li>
                            <li><strong>Maduro:</strong> {{ $visita->evaluacionCosechaCampo->maduro }}%</li>
                            <li><strong>Sobre Maduro:</strong> {{ $visita->evaluacionCosechaCampo->sobremaduro }}%</li>
                            <li><strong>Pedúnculo:</strong> {{ $visita->evaluacionCosechaCampo->pedunculo }}%</li>
                            <li><strong>Observaciones:</strong> {{ $visita->evaluacionCosechaCampo->observaciones }}</li>
                        </ul>
                    @else
                        <p class="text-muted">No se ha registrado evaluación de cosecha.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cierre de Visita --}}
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingCierre">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCierre">
                🔏 Cierre de Visita
            </button>
        </h2>
        <div id="collapseCierre" class="accordion-collapse collapse" data-bs-parent="#acordeonDetalleVisita">
            <div class="accordion-body">
                @if ($visita->cierreVisita)
                    <ul>
                        <li><strong>Responsable cierre:</strong> {{ $visita->tecnico->name }}</li>
                        <li><strong>Recomendaciones:</strong> {{ $visita->cierreVisita->recomendaciones ?? 'No se especificaron' }}</li>
                        <li><strong>Observaciones:</strong> {{ $visita->cierreVisita->observaciones_finales ?? 'No registradas' }}</li>
                        <li><strong>Fecha:</strong> {{ $visita->cierreVisita->created_at->format('d/m/Y') }}</li>
                    </ul>

                    {{-- Firma --}}
                    @if ($visita->cierreVisita->firma_responsable)
                        <div class="mt-3">
                            <strong>📄 Firma Responsable de Visita:</strong><br>
                            <img src="{{ asset('storage/' . $visita->cierreVisita->firma_responsable) }}" alt="Firma" style="max-height: 120px;">
                        </div>
                    @endif

                    {{-- Imágenes finales --}}
                    @if ($visita->cierreVisita->imagenes)
                        @php
                            $imagenes = json_decode($visita->cierreVisita->imagenes, true);
                        @endphp

                        @if (is_array($imagenes) && count($imagenes))
                            <div class="mt-4">
                                <strong>🖼️ Imágenes finales:</strong><br>
                                <div class="row">
                                    @foreach ($imagenes as $img)
                                        <div class="col-md-4 mb-3">
                                            <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded shadow">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                @else
                    <p class="text-muted">No se ha registrado el cierre de visita.</p>
                @endif
            </div>
        </div>
    </div>




    </div>
    <!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Botón de exportación -->
<button onclick="descargarPDFConSweet()" class="btn btn-danger">
    📥 Exportar PDF
</button>

<!-- Iframe oculto para la descarga -->
<iframe id="descargaPDFiframe" style="display:none;"></iframe>

<script>
    function descargarPDFConSweet() {
        Swal.fire({
            title: 'Generando PDF...',
            text: 'Esto puede tardar unos segundos',
            imageUrl: '{{ asset('images/loader.gif') }}',
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        // Cargar el PDF en el iframe oculto (no recarga la página)
        const iframe = document.getElementById('descargaPDFiframe');
        iframe.src = "{{ route('visitas.exportar.pdf', $visita->id) }}";

        // Cerrar el SweetAlert luego de 4-6 segundos
        setTimeout(() => {
            Swal.close();
        }, 16000); // Ajusta el tiempo según la velocidad de generación
    }
</script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Botón de exportación a Excel -->
<button onclick="descargarExcelConSweet()" class="btn btn-success">
    📊 Exportar a Excel
</button>

<!-- Iframe oculto -->
<iframe id="descargaExcelIframe" style="display: none;"></iframe>

<script>
    function descargarExcelConSweet() {
        Swal.fire({
            title: 'Generando Excel...',
            text: 'Esto puede tardar unos segundos',
            imageUrl: '{{ asset('images/loader.gif') }}', // Usa tu gif de carga
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        });

        document.getElementById('descargaExcelIframe').src = "{{ route('visitas.exportar.excel', $visita->id) }}";

        setTimeout(() => {
            Swal.close();
        }, 4000); // Ajusta si se demora más
    }
</script>


    <a href="{{ route('visitas.index') }}" class="btn btn-secondary mt-4">⬅️ Volver</a>
</div>
@endsection

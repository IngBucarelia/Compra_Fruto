@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background-image: url('{{ asset('images/fondo_agronomico.png') }}');
    }
    
    .container-dashboard {
        width: 90%;
        padding: 20px;
    }
    
    .card-chart {
        border-radius: 12px;
        background: rgb(173, 173, 173);
        padding: 12px;
        color: #fff;
        margin-bottom: 18px;
        min-height: 260px;
    }
    
    .card-resumen {
        background: linear-gradient(135deg, #2e7d32, #1b5e20);
        border-radius: 12px;
        padding: 15px;
        color: white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .resumen-item {
        text-align: center;
        padding: 10px;
    }
    
    .resumen-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .resumen-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .button-33 {
        background-color: #073c1a;
        border-radius: 100px;
        box-shadow: rgba(44, 187, 99, .2) 0 -25px 18px -14px inset,
                    rgba(44, 187, 99, .15) 0 1px 2px,
                    rgba(44, 187, 99, .15) 0 2px 4px,
                    rgba(44, 187, 99, .15) 0 4px 8px,
                    rgba(44, 187, 99, .15) 0 8px 16px,
                    rgba(44, 187, 99, .15) 0 16px 32px;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        padding: 8px 22px;
        text-align: center;
        text-decoration: none;
        transition: all 250ms;
        border: 0;
        font-size: 15px;
        user-select: none;
    }
    
    .button-33:hover {
        box-shadow: rgba(44,187,99,.35) 0 -25px 18px -14px inset,
                    rgba(44,187,99,.25) 0 1px 2px,
                    rgba(44,187,99,.25) 0 2px 4px,
                    rgba(44,187,99,.25) 0 4px 8px,
                    rgba(44,187,99,.25) 0 8px 16px,
                    rgba(44,187,99,.25) 0 16px 32px;
        transform: scale(1.05) rotate(-1deg);
    }
    
    .card-chart h6 { 
        font-weight: 700; 
        color: #fff; 
        font-size: 14px;
        margin-bottom: 10px;
    }
    
    .canvas-small { 
        width: 100% !important; 
        height: 170px !important; 
    }
    
    .grid-3 { 
        display: grid; 
        grid-template-columns: repeat(3, 1fr); 
        gap: 16px; 
    }
    
    .grid-2 { 
        display: grid; 
        grid-template-columns: repeat(2, 1fr); 
        gap: 16px; 
    }
    
    .grid-4 { 
        display: grid; 
        grid-template-columns: repeat(4, 1fr); 
        gap: 16px; 
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .grid-3, .grid-2, .grid-4 {
            grid-template-columns: 1fr;
        }
        
        .container {
            margin-left: -70px;
            width: 125%;
        }
        
        .card-chart {
            min-height: 220px;
        }
        
        .resumen-number {
            font-size: 2rem;
        }
    }
</style>

<div class="container container-dashboard" style="background-color: #5282289d">
    <!-- Título -->
    <div class="row mb-3">
        <div class="col-md-9">
            <h3 class="text-white">🌱 Panel de Indicadores Agronómicos</h3>
            <p style="color: #fff">Visualiza comparativos por proveedor y plantación. Filtros en tiempo real.</p>
        </div>
        
        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="{{ route('visitas.index') }}" class="button-33 me-2">📋 Lista de Visitas</a>
                <a href="{{ route('planificaciones.calendario') }}" class="button-33">🗓️ Ver Calendario</a>
            </div>
            
            <div>
                <a href="{{ route('dashboard.visitas.agro') }}" class="btn btn-light btn-sm fw-bold shadow-sm">
                    🔁 Reset filtros
                </a>
            </div>
        </div>
    </div>

    <!-- FILTROS -->
    <div class="card mb-3 p-3">
        <form id="filtrosForm" class="row g-2">
            <div class="col-md-3">
                <label>Desde</label>
                <input type="date" id="filtroDesde" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Hasta</label>
                <input type="date" id="filtroHasta" class="form-control">
            </div>
            <div class="col-md-2">
                <label>Proveedor</label>
                <select id="filtroProveedor" class="form-control">
                    <option value="">Todos</option>
                    @foreach($proveedores as $p)
                        <option value="{{ $p->id }}">{{ $p->proveedor_nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Plantación</label>
                <select id="filtroPlantacion" class="form-control">
                    <option value="">Todas</option>
                    @foreach($plantaciones as $pl)
                        <option value="{{ $pl->id }}">{{ $pl->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Técnico</label>
                <select id="filtroTecnico" class="form-control">
                    <option value="">Todos</option>
                    @foreach($tecnicos as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 text-end mt-2">
                <button type="button" id="btnAplicar" class="btn btn-success btn-sm">Aplicar filtros</button>
            </div>
        </form>
    </div>

    <!-- 🔹 SECCIÓN 1: RESUMEN DE VISITAS (Página 1 PDF) -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card-resumen">
                <h5 class="text-center mb-3">📊 Proveedores visitados en el mes</h5>
                <div class="grid-3">
                    <div class="resumen-item">
                        <div class="resumen-number" id="visitasMes">0</div>
                        <div class="resumen-label">N° visitas en el mes</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-number" id="visitasAnio">0</div>
                        <div class="resumen-label">Visitas acumuladas año</div>
                    </div>
                    <div class="resumen-item">
                        <div class="resumen-number" id="proveedoresMes">0</div>
                        <div class="resumen-label">N° de proveedores visitados</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 SECCIÓN 2: FERTILIZACIÓN (Página 2 PDF) -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="text-white mb-3">🌱 Fertilización</h5>
        </div>
        <div class="grid-2">
            <div class="card-chart">
                <h6>Productores que fertilizaron</h6>
                <canvas id="chartFertProductores" class="canvas-small"></canvas>
            </div>
            <div class="card-chart">
                <h6>Kilos aplicados</h6>
                <canvas id="chartFertKilos" class="canvas-small"></canvas>
            </div>
        </div>
    </div>

    <!-- 🔹 SECCIÓN 3: POLINIZACIÓN (Página 2-3 PDF) -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="text-white mb-3">🐝 Polinización</h5>
        </div>
        <div class="grid-3">
            <div class="card-chart">
                <h6>N° de productores que realizan polinización</h6>
                <div class="text-center mt-4">
                    <div class="resumen-number" id="polinizadoresTotal" style="font-size: 3rem;">0</div>
                </div>
            </div>
            <div class="card-chart">
                <h6>Pases de polinización</h6>
                <canvas id="chartPolPases" class="canvas-small"></canvas>
            </div>
            <div class="card-chart">
                <h6>Clase de conformación de racimo</h6>
                <canvas id="chartPolClases" class="canvas-small"></canvas>
            </div>
        </div>
    </div>

    <!-- 🔹 SECCIÓN 4: SANIDAD (Página 4 PDF) -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="text-white mb-3">🩺 Sanidad</h5>
        </div>
        <div class="grid-3">
            <div class="card-chart">
                <h6>Proveedores con Censo PC</h6>
                <div class="text-center mt-4">
                    <div class="resumen-number" id="censoPC" style="font-size: 3rem;">0</div>
                </div>
            </div>
            <div class="card-chart">
                <h6>Proveedores con Monitoreo Plagas</h6>
                <div class="text-center mt-4">
                    <div class="resumen-number" id="monitoreoPlagas" style="font-size: 3rem;">0</div>
                </div>
            </div>
            <div class="card-chart">
                <h6>Proveedores con Trampas R. palmarum</h6>
                <div class="text-center mt-4">
                    <div class="resumen-number" id="trampasRP" style="font-size: 3rem;">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 SECCIÓN 5: LABORES Y EVALUACIÓN (Página 4 PDF) -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="text-white mb-3">📋 Labores de Cultivo</h5>
        </div>
        <div class="grid-2">
            <div class="card-chart">
                <h6>Libros de Cultivo registrados</h6>
                <div class="text-center mt-4">
                    <div class="resumen-number" id="laboresTotal" style="font-size: 3rem;">0</div>
                </div>
            </div>
            <div class="card-chart">
                <h6>Evaluaciones de Cosecha</h6>
                <div class="text-center mt-4">
                    <div class="resumen-number" id="evaluacionesTotal" style="font-size: 3rem;">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔹 SECCIÓN 6: RENDIMIENTO HISTÓRICO (Página 4 PDF) -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h5 class="text-white mb-3">📈 Rendimiento Histórico (t/ha/año)</h5>
        </div>
        <div class="col-md-12">
            <div class="card-chart" style="min-height: 300px;">
                <canvas id="chartRendimiento" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- 🔹 BOTONES FINALES -->
    <div class="d-flex flex-wrap align-items-center mt-4">
        <a href="{{ route('visitas.index') }}" class="button-33 me-2 mb-2">📋 Lista de Visitas</a>
        <a href="{{ route('planificaciones.calendario') }}" class="button-33 me-2 mb-2">🗓️ Ver Calendario</a>
        <a href="{{ url()->previous() }}" class="button-33 mb-2">⬅️ Volver atrás</a>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Crear todos los charts
    const charts = {
        // Fertilización - Productores
        fertProductores: new Chart(document.getElementById('chartFertProductores').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['En el mes', 'Acumulado año'],
                datasets: [{
                    label: 'Productores',
                    data: [0, 0],
                    backgroundColor: ['#4CAF50', '#2E7D32'],
                    borderWidth: 1,
                    borderColor: ['#388E3C', '#1B5E20']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#fff',
                            font: { size: 11 }
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#fff',
                            font: { size: 11 }
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        }
                    }
                }
            }
        }),
        
        // Fertilización - Kilos
        fertKilos: new Chart(document.getElementById('chartFertKilos').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Kilos mes', 'Kilos año'],
                datasets: [{
                    label: 'Kilos',
                    data: [0, 0],
                    backgroundColor: ['#FF9800', '#F57C00'],
                    borderWidth: 1,
                    borderColor: ['#F57C00', '#E65100']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw} kg`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#fff',
                            font: { size: 11 }
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#fff',
                            font: { size: 11 }
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.1)'
                        }
                    }
                }
            }
        }),
        
        // Polinización - Pases
        polPases: new Chart(document.getElementById('chartPolPases').getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['1 pase', '2 pases', '3 o más'],
                datasets: [{
                    data: [0, 0, 0],
                    backgroundColor: ['#2196F3', '#03A9F4', '#4FC3F7'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: {
                            color: '#fff',
                            padding: 15,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value * 100) / total);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        }),
        
        // Polinización - Clases
        polClases: new Chart(document.getElementById('chartPolClases').getContext('2d'), {
            type: 'pie',
            data: {
                labels: ['Clase 1', 'Clase 2', 'Clase 3'],
                datasets: [{
                    data: [0, 0, 0],
                    backgroundColor: ['#9C27B0', '#7B1FA2', '#4A148C'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: {
                            color: '#fff',
                            padding: 15,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        }),
        
        // Rendimiento histórico
        rendimiento: new Chart(document.getElementById('chartRendimiento').getContext('2d'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Rendimiento Proyectado t/ha/año',
                        data: [],
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 3
                    },
                    {
                        label: 't CPO/ha/año',
                        data: [],
                        borderColor: '#2196F3',
                        backgroundColor: 'rgba(33, 150, 243, 0.1)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 3
                    },
                    {
                        label: 'TEA',
                        data: [],
                        borderColor: '#FF9800',
                        backgroundColor: 'rgba(255, 152, 0, 0.1)',
                        fill: true,
                        tension: 0.3,
                        borderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { 
                            color: '#fff',
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw} t/ha`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'T/HA/AÑO',
                            color: '#fff',
                            font: { size: 14, weight: 'bold' }
                        },
                        ticks: { 
                            color: '#fff',
                            font: { size: 12 }
                        },
                        grid: { 
                            color: 'rgba(255,255,255,0.1)'
                        }
                    },
                    x: {
                        ticks: { 
                            color: '#fff',
                            font: { size: 12 }
                        },
                        grid: { 
                            color: 'rgba(255,255,255,0.1)'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                }
            }
        })
    };

    // Función para actualizar gráfico de pastel con porcentajes
    function updatePieChart(chart, newData) {
        if (chart && chart.data && chart.data.datasets && chart.data.datasets[0]) {
            chart.data.datasets[0].data = newData;
            
            // Actualizar tooltip para mostrar porcentajes
            if (chart.options && chart.options.plugins && chart.options.plugins.tooltip) {
                const total = newData.reduce((a, b) => a + b, 0);
                chart.options.plugins.tooltip.callbacks.label = function(context) {
                    const label = context.label || '';
                    const value = context.raw || 0;
                    const percentage = total > 0 ? Math.round((value * 100) / total) : 0;
                    return `${label}: ${value} (${percentage}%)`;
                };
            }
            
            chart.update();
        }
    }

    // 🔹 FUNCIÓN PARA MOSTRAR NOMBRES EN LAS TARJETAS
    function mostrarInformacionNombres(jsonVisitas, jsonModulos) {
        // 1. Mostrar información en el resumen
        if (jsonVisitas.resumenVisitas && jsonVisitas.resumenVisitas.proveedores_mes_nombres) {
            const proveedoresMesDiv = document.getElementById('proveedoresMes');
            if (proveedoresMesDiv) {
                const cantidad = jsonVisitas.resumenVisitas.proveedores_mes || 0;
                const nombres = jsonVisitas.resumenVisitas.proveedores_mes_nombres || [];
                
                let html = `<div class="resumen-number">${cantidad}</div>`;
                if (nombres.length > 0) {
                    html += `<div class="proveedores-lista" style="font-size: 11px; margin-top: 5px; max-height: 60px; overflow-y: auto;">`;
                    nombres.forEach(nombre => {
                        html += `<div>✓ ${nombre}</div>`;
                    });
                    html += `</div>`;
                }
                
                proveedoresMesDiv.innerHTML = html;
            }
        }

        // 2. Mostrar información de proveedores en SANIDAD
        const elementosSanidad = [
            { id: 'censoPC', datos: jsonModulos?.sanidad?.proveedores_censo_pc },
            { id: 'monitoreoPlagas', datos: jsonModulos?.sanidad?.proveedores_monitoreo },
            { id: 'trampasRP', datos: jsonModulos?.sanidad?.proveedores_trampas }
        ];

        elementosSanidad.forEach(item => {
            const elemento = document.getElementById(item.id);
            if (elemento && item.datos && Array.isArray(item.datos)) {
                const cantidad = elemento.textContent;
                const nombres = item.datos;
                
                let html = `<div style="font-size: 3rem; font-weight: bold;">${cantidad}</div>`;
                if (nombres.length > 0) {
                    html += `<div class="proveedores-minilista" style="font-size: 10px; margin-top: 5px; max-height: 40px; overflow-y: auto; background: rgba(255,255,255,0.1); padding: 3px; border-radius: 3px;">`;
                    nombres.slice(0, 3).forEach(nombre => {
                        html += `<div>• ${nombre}</div>`;
                    });
                    if (nombres.length > 3) {
                        html += `<div>... y ${nombres.length - 3} más</div>`;
                    }
                    html += `</div>`;
                    
                    // Agregar tooltip con todos los nombres
                    elemento.setAttribute('title', `Proveedores:\n${nombres.join('\n')}`);
                    elemento.style.cursor = 'help';
                }
                
                elemento.innerHTML = html;
            }
        });

        // 3. Mostrar información de proveedores en POLINIZACIÓN
        const polinizadoresDiv = document.getElementById('polinizadoresTotal');
        if (polinizadoresDiv && jsonModulos?.polinizacion?.proveedores_nombres) {
            const cantidad = polinizadoresDiv.textContent;
            const nombres = jsonModulos.polinizacion.proveedores_nombres;
            
            let html = `<div style="font-size: 3rem; font-weight: bold;">${cantidad}</div>`;
            if (nombres.length > 0) {
                html += `<div class="proveedores-minilista" style="font-size: 10px; margin-top: 5px; max-height: 40px; overflow-y: auto; background: rgba(255,255,255,0.1); padding: 3px; border-radius: 3px;">`;
                nombres.slice(0, 3).forEach(nombre => {
                    html += `<div>• ${nombre}</div>`;
                });
                if (nombres.length > 3) {
                    html += `<div>... y ${nombres.length - 3} más</div>`;
                }
                html += `</div>`;
                
                polinizadoresDiv.setAttribute('title', `Proveedores con polinización:\n${nombres.join('\n')}`);
                polinizadoresDiv.style.cursor = 'help';
            }
            
            polinizadoresDiv.innerHTML = html;
        }

        // 4. Mostrar información de proveedores en FERTILIZACIÓN (en tooltips de gráficos)
        if (jsonModulos?.fertilizacion) {
            const fert = jsonModulos.fertilizacion;
            
            // Actualizar tooltips del gráfico de productores
            if (charts.fertProductores && charts.fertProductores.options && charts.fertProductores.options.plugins) {
                charts.fertProductores.options.plugins.tooltip = {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let value = context.raw || 0;
                            let info = `${label}: ${value}`;
                            
                            // Agregar nombres si existen
                            if (context.dataIndex === 0 && fert.proveedores_mes_nombres && fert.proveedores_mes_nombres.length > 0) {
                                info += `\nProveedores del mes: ${fert.proveedores_mes_nombres.join(', ')}`;
                            } else if (context.dataIndex === 1 && fert.proveedores_anio_nombres && fert.proveedores_anio_nombres.length > 0) {
                                info += `\nProveedores del año: ${fert.proveedores_anio_nombres.join(', ')}`;
                            }
                            
                            return info;
                        }
                    }
                };
                charts.fertProductores.update();
            }
        }

        // 5. Mostrar información general de nombres y plantaciones
        if (jsonVisitas.nombres) {
            const nombres = jsonVisitas.nombres;
            
            // Crear o actualizar la sección de información
            let infoSection = document.getElementById('info-nombres');
            if (!infoSection) {
                infoSection = document.createElement('div');
                infoSection.id = 'info-nombres';
                infoSection.className = 'card mb-3 p-3';
                infoSection.style.background = 'rgba(255,255,255,0.1)';
                infoSection.style.borderRadius = '8px';
                
                // Insertar después de los filtros
                const filtrosCard = document.querySelector('.card.mb-3.p-3');
                if (filtrosCard) {
                    filtrosCard.parentNode.insertBefore(infoSection, filtrosCard.nextSibling);
                }
            }
            
            let infoHTML = `
                <h6 style="color: #4CAF50; margin-bottom: 10px;">📋 Información de Filtros</h6>
                <div class="row">
                    <div class="col-md-6">
                        <small><strong>Proveedores (${nombres.total_proveedores || 0}):</strong></small>
                        <div style="max-height: 80px; overflow-y: auto; font-size: 12px; margin-top: 5px;">
            `;
            
            if (nombres.proveedores && Object.keys(nombres.proveedores).length > 0) {
                Object.values(nombres.proveedores).forEach(nombre => {
                    infoHTML += `<span class="badge bg-success me-1 mb-1">${nombre}</span>`;
                });
            } else {
                infoHTML += `<span class="text-muted">Sin proveedores</span>`;
            }
            
            infoHTML += `
                        </div>
                    </div>
                    <div class="col-md-6">
                        <small><strong>Plantaciones (${nombres.total_plantaciones || 0}):</strong></small>
                        <div style="max-height: 80px; overflow-y: auto; font-size: 12px; margin-top: 5px;">
            `;
            
            if (nombres.plantaciones && Object.keys(nombres.plantaciones).length > 0) {
                Object.values(nombres.plantaciones).forEach(nombre => {
                    infoHTML += `<span class="badge bg-info me-1 mb-1">${nombre}</span>`;
                });
            } else {
                infoHTML += `<span class="text-muted">Sin plantaciones</span>`;
            }
            
            infoHTML += `
                        </div>
                    </div>
                </div>
            `;
            
            infoSection.innerHTML = infoHTML;
        }
    }

    // Cargar datos
    async function loadData() {
        const from = document.getElementById('filtroDesde').value;
        const to = document.getElementById('filtroHasta').value;
        const proveedor = document.getElementById('filtroProveedor').value;
        const plantacion = document.getElementById('filtroPlantacion').value;
        const tecnico = document.getElementById('filtroTecnico').value;

        const params = new URLSearchParams({ from, to, proveedor, plantacion, tecnico });

        try {
            // 🔹 1. Datos de visitas (resumen)
            const resVisitas = await fetch('{{ route('dashboard.visitas.agro.data') }}?' + params.toString());
            const jsonVisitas = await resVisitas.json();

            // Actualizar resumen de visitas
            if (jsonVisitas.resumenVisitas) {
                document.getElementById('visitasMes').textContent = jsonVisitas.resumenVisitas.visitas_mes || 0;
                document.getElementById('visitasAnio').textContent = jsonVisitas.resumenVisitas.visitas_anio || 0;
            }

            // 🔹 2. Datos de módulos
            const resModulos = await fetch('{{ route('dashboard.visitas.agro.data.modulos') }}?' + params.toString());
            const jsonModulos = await resModulos.json();

            // FERTILIZACIÓN
            const fert = jsonModulos.fertilizacion || {};
            charts.fertProductores.data.datasets[0].data = [
                fert.productores_mes || 0,
                fert.productores_anio || 0
            ];
            charts.fertProductores.update();
            
            charts.fertKilos.data.datasets[0].data = [
                fert.kilos_mes || 0,
                fert.kilos_anio || 0
            ];
            charts.fertKilos.update();

            // POLINIZACIÓN
            const pol = jsonModulos.polinizacion || {};
            document.getElementById('polinizadoresTotal').textContent = pol.total_proveedores || 0;
            
            if (pol.pases) {
                updatePieChart(charts.polPases, [
                    pol.pases['1 pase'] || 0,
                    pol.pases['2 pases'] || 0,
                    pol.pases['3 o más'] || 0
                ]);
            }
            
            if (pol.clases) {
                updatePieChart(charts.polClases, [
                    pol.clases['Clase 1'] || 0,
                    pol.clases['Clase 2'] || 0,
                    pol.clases['Clase 3'] || 0
                ]);
            }

            // SANIDAD
            const san = jsonModulos.sanidad || {};
            document.getElementById('censoPC').textContent = san['Censo PC'] || 0;
            document.getElementById('monitoreoPlagas').textContent = san['Monitoreo Plagas'] || 0;
            document.getElementById('trampasRP').textContent = san['Trampas RP'] || 0;

            // LABORES
            const lab = jsonModulos.labores || {};
            document.getElementById('laboresTotal').textContent = lab.total_labores || 0;
            document.getElementById('evaluacionesTotal').textContent = lab.evaluaciones || 0;

            // RENDIMIENTO HISTÓRICO
            const rend = jsonModulos.rendimiento || {};
            if (rend.labels && rend.labels.length > 0) {
                charts.rendimiento.data.labels = rend.labels;
                charts.rendimiento.data.datasets[0].data = rend.rendimiento_proyectado || [];
                charts.rendimiento.data.datasets[1].data = rend.cpo || [];
                charts.rendimiento.data.datasets[2].data = rend.tea || [];
                charts.rendimiento.update();
            }

            // 🔹 3. Mostrar información de nombres
            mostrarInformacionNombres(jsonVisitas, jsonModulos);

        } catch (error) {
            console.error('Error cargando datos:', error);
        }
    }

    // Botón aplicar filtros
    document.getElementById('btnAplicar').addEventListener('click', () => {
        loadData();
    });

    // Cargar datos iniciales
    loadData();
});
</script>
@endsection
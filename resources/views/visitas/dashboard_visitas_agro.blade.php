@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.container-dashboard {
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

.card-chart h6 { font-weight: 700; color: #fff; }
.canvas-small { width: 100% !important; height: 170px !important; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    .grid-3 { grid-template-columns: repeat(2,1fr);}

    .grid-3 { grid-template-columns: 1fr; }
    .btn-circle {
        width: 30px;
        height: 30px;
    }
    
    .table-responsive {
        font-size: 0.9em;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group .form-control {
        margin-bottom: 10px;
    }
     .container {
        margin-left: -70px;
        width: 125%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }

        .card{
        width: 100%;
    }
}
</style>

<div class="container container-dashboard" style="background-color: #5282289d">
    <div class="row mb-3">
        <div class="col-md-9">
            <h3 class="text-white">🌱 Panel de Indicadores Agronómicos</h3>
            <p  style="color: #fff">Visualiza comparativos por proveedor y plantación. Filtros en tiempo real.</p>
        </div>
        
       <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">

            {{-- 🔹 Botones principales a la izquierda --}}
            <div>
                <a href="{{ route('visitas.index') }}" class="button-33 me-2">📋 Lista de Visitas</a>
                <a href="{{ route('planificaciones.calendario') }}" class="button-33">🗓️ Ver Calendario</a>
            </div>

            {{-- 🔹 Botón Reset a la derecha --}}
            <div>
                <a href="{{ route('dashboard.visitas.agro') }}" class="btn btn-light btn-sm fw-bold shadow-sm">
                    🔁 Reset filtros
                </a>
            </div>
        </div>

    </div>

    {{-- FILTROS --}}
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

    {{-- GRILLA 3x por fila --}}
    <div class="grid-3 mb-4">
        <div class="card-chart" id="card-visitas" data-target="#modalVisitas">
            <h6>Visitas por Estado</h6>
            <canvas id="chartVisitas" class="canvas-small"></canvas>
            <div class="mt-2 text-end">
                <button class="btn btn-sm btn-light btn-export" data-canvas="chartVisitas">📤 Exportar</button>
                <button class="btn btn-sm btn-outline-light btn-expand" data-bs-toggle="modal" data-bs-target="#modalVisitas">🔎</button>
            </div>
        </div>

        <div class="card-chart" id="card-proveedor" data-target="#modalProveedor">
            <h6>Visitas por Proveedor (top)</h6>
            <canvas id="chartProveedor" class="canvas-small"></canvas>
            <div class="mt-2 text-end">
                <button class="btn btn-sm btn-light btn-export" data-canvas="chartProveedor">📤 Exportar</button>
                <button class="btn btn-sm btn-outline-light btn-expand" data-bs-toggle="modal" data-bs-target="#modalProveedor">🔎</button>
            </div>
        </div>

        <div class="card-chart" id="card-plantacion" data-target="#modalPlantacion">
            <h6>Visitas por Plantación (top)</h6>
            <canvas id="chartPlantacion" class="canvas-small"></canvas>
            <div class="mt-2 text-end">
                <button class="btn btn-sm btn-light btn-export" data-canvas="chartPlantacion">📤 Exportar</button>
                <button class="btn class="btn btn-sm btn-outline-light btn-expand" data-bs-toggle="modal" data-bs-target="#modalPlantacion">🔎</button>
            </div>
        </div>
    </div>

    {{-- Segunda fila: modulos --}}
    <div class="grid-3 mb-4">
        <div class="card-chart">
            <h6>Fertilizaciones</h6>
            <canvas id="chartFert" class="canvas-small"></canvas>
        </div>
        <div class="card-chart">
            <h6>Polinizaciones</h6>
            <canvas id="chartPol" class="canvas-small"></canvas>
        </div>
        <div class="card-chart">
            <h6>Sanidad</h6>
            <canvas id="chartSanidad" class="canvas-small"></canvas>
        </div>
    </div>

    <div class="grid-3 mb-4">
        <div class="card-chart">
            <h6>Libros de Cultivo</h6>
            <canvas id="chartLabores" class="canvas-small"></canvas>
        </div>
        <div class="card-chart">
            <h6>Evaluación Cosecha</h6>
            <canvas id="chartEvaluacion" class="canvas-small"></canvas>
        </div>
        <div class="card-chart">
            <h6>Producción por Plantación (t/mes)</h6>
            <canvas id="chartProduccion" class="canvas-small"></canvas>
        </div>
    </div>
     <div class="d-flex flex-wrap align-items-center">
        <a href="{{ route('visitas.index') }}" class="button-33 me-2 mb-2">📋 Lista de Visitas</a>
        <a href="{{ route('planificaciones.calendario') }}" class="button-33 me-2 mb-2">🗓️ Ver Calendario</a>
        <a href="{{ url()->previous() }}" class="button-33 mb-2">⬅️ Volver atrás</a>
    </div>
</div>

{{-- Modales para ampliar gráficos --}}
<div class="modal fade" id="modalVisitas" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Visitas por Estado (ampliado)</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <canvas id="chartVisitasBig"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalProveedor" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Visitas por Proveedor (ampliado)</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <canvas id="chartProveedorBig"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalPlantacion" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Visitas por Plantación (ampliado)</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <canvas id="chartPlantacionBig"></canvas>
      </div>
    </div>
  </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Crear charts vacíos
    const ctxVisitas = new Chart(document.getElementById('chartVisitas').getContext('2d'), { type: 'doughnut', data: { labels: [], datasets: [{ data: [], backgroundColor: [] }] }, options: { responsive:true } });
    const ctxProveedor = new Chart(document.getElementById('chartProveedor').getContext('2d'), { type: 'bar', data: { labels: [], datasets: [{ data: [], backgroundColor: [] }] }, options: { responsive:true } });
    const ctxPlantacion = new Chart(document.getElementById('chartPlantacion').getContext('2d'), { type: 'bar', data: { labels: [], datasets: [{ data: [], backgroundColor: [] }] }, options: { responsive:true } });

    // Charts para módulos
    const ctxFert = new Chart(document.getElementById('chartFert').getContext('2d'), { type: 'bar', data: { labels: [], datasets: [{ data: [], backgroundColor: '#2e7d32' }] }, options:{ responsive:true }});
    const ctxPol = new Chart(document.getElementById('chartPol').getContext('2d'), { type: 'bar', data: { labels: [], datasets: [{ data: [], backgroundColor: '#f57c00' }] }, options:{ responsive:true }});
    const ctxSan = new Chart(document.getElementById('chartSanidad').getContext('2d'), { type: 'pie', data: { labels: [], datasets: [{ data: [], backgroundColor: ['#d81b60'] }] }, options:{ responsive:true }});
    const ctxLab = new Chart(document.getElementById('chartLabores').getContext('2d'), { type: 'bar', data: { labels: [], datasets: [{ data: [], backgroundColor: '#8bc34a' }] }, options:{ responsive:true }});
    const ctxEval = new Chart(document.getElementById('chartEvaluacion').getContext('2d'), { type: 'bar', data: { labels: [], datasets: [{ data: [], backgroundColor: '#3f51b5' }] }, options:{ responsive:true }});
    const ctxProd = new Chart(document.getElementById('chartProduccion').getContext('2d'), { type: 'bar', data: { labels: [], datasets: [{ data: [], backgroundColor: '#009688' }] }, options:{ responsive:true }});

    // Big charts (modal)
    const ctxVisitasBig = new Chart(document.getElementById('chartVisitasBig').getContext('2d'), { type:'doughnut', data: { labels:[], datasets:[{ data:[], backgroundColor:[] }] }, options:{ responsive:true }});
    const ctxProvBig = new Chart(document.getElementById('chartProveedorBig').getContext('2d'), { type:'bar', data: { labels:[], datasets:[{ data:[], backgroundColor:[] }] }, options:{ responsive:true }});
    const ctxPlantBig = new Chart(document.getElementById('chartPlantacionBig').getContext('2d'), { type:'bar', data: { labels:[], datasets:[{ data:[], backgroundColor:[] }] }, options:{ responsive:true }});

    // Helper colores
    function palette(n){
        const colors = ['#4CAF50','#FFC107','#2196F3','#9E9E9E','#66BB6A','#FFA726','#E91E63','#8BC34A','#3F51B5','#009688'];
        return colors.slice(0,n);
    }

    // Cargar datos (AJAX)
    async function loadData() {
        const from = document.getElementById('filtroDesde').value;
        const to = document.getElementById('filtroHasta').value;
        const proveedor = document.getElementById('filtroProveedor').value;
        const plantacion = document.getElementById('filtroPlantacion').value;
        const tecnico = document.getElementById('filtroTecnico').value;

        const params = new URLSearchParams({ from, to, proveedor, plantacion, tecnico });

        // Datos visitas
        const res = await fetch('{{ route('dashboard.visitas.agro.data') }}?'+params.toString());
        const json = await res.json();

        // Visitas por estado
        const keysEstado = Object.keys(json.visitasPorEstado || {});
        const valsEstado = Object.values(json.visitasPorEstado || {});
        ctxVisitas.data.labels = keysEstado;
        ctxVisitas.data.datasets[0].data = valsEstado;
        ctxVisitas.data.datasets[0].backgroundColor = palette(keysEstado.length);
        ctxVisitas.update();

        // Big
        ctxVisitasBig.data.labels = keysEstado;
        ctxVisitasBig.data.datasets[0].data = valsEstado;
        ctxVisitasBig.data.datasets[0].backgroundColor = palette(keysEstado.length);
        ctxVisitasBig.update();

        // Proveedor
        const provLabels = Object.keys(json.visitasPorProveedor || {});
        const provVals = Object.values(json.visitasPorProveedor || {});
        ctxProveedor.data.labels = provLabels;
        ctxProveedor.data.datasets[0].data = provVals;
        ctxProveedor.data.datasets[0].backgroundColor = palette(provLabels.length);
        ctxProveedor.update();

        ctxProvBig.data.labels = provLabels;
        ctxProvBig.data.datasets[0].data = provVals;
        ctxProvBig.data.datasets[0].backgroundColor = palette(provLabels.length);
        ctxProvBig.update();

        // Plantacion
        const plantLabels = Object.keys(json.visitasPorPlantacion || {});
        const plantVals = Object.values(json.visitasPorPlantacion || {});
        ctxPlantacion.data.labels = plantLabels;
        ctxPlantacion.data.datasets[0].data = plantVals;
        ctxPlantacion.data.datasets[0].backgroundColor = palette(plantLabels.length);
        ctxPlantacion.update();

        ctxPlantBig.data.labels = plantLabels;
        ctxPlantBig.data.datasets[0].data = plantVals;
        ctxPlantBig.data.datasets[0].backgroundColor = palette(plantLabels.length);
        ctxPlantBig.update();

        // Produccion
        const prodLabels = Object.keys(json.produccionPorPlantacion || {});
        const prodVals = Object.values(json.produccionPorPlantacion || {});
        ctxProd.data.labels = prodLabels;
        ctxProd.data.datasets[0].data = prodVals;
        ctxProd.update();

        // MÓDULOS (fertilizaciones, polinizaciones, etc)
        const resMod = await fetch('{{ route('dashboard.visitas.agro.data.modulos') }}?' + params.toString());
        const jsonMod = await resMod.json();
        
        /* FERTILIZACIONES */
        const fertLabels = Object.keys(jsonMod.fertilizaciones || {});
        const fertValues = Object.values(jsonMod.fertilizaciones || {});
        ctxFert.data.labels = fertLabels;
        ctxFert.data.datasets[0].data = fertValues;
        ctxFert.update();
        
        /* POLINIZACIONES */
        const polLabels = Object.keys(jsonMod.polinizaciones || {});
        const polValues = Object.values(jsonMod.polinizaciones || {});
        ctxPol.data.labels = polLabels;
        ctxPol.data.datasets[0].data = polValues;
        ctxPol.update();
        
        /* SANIDAD */
        const sanLabels = Object.keys(jsonMod.sanidad || {});
        const sanValues = Object.values(jsonMod.sanidad || {});
        ctxSan.data.labels = sanLabels;
        ctxSan.data.datasets[0].data = sanValues;
        ctxSan.update();
        
        /* LABORES DE CULTIVO */
        const labLabels = Object.keys(jsonMod.labores_cultivo || {});
        const labValues = Object.values(jsonMod.labores_cultivo || {});
        ctxLab.data.labels = labLabels;
        ctxLab.data.datasets[0].data = labValues;
        ctxLab.update();
        
        /* EVALUACIONES COSECHA */
        const evalLabels = Object.keys(jsonMod.evaluacion_cosecha || {});
        const evalValues = Object.values(jsonMod.evaluacion_cosecha || {});
        ctxEval.data.labels = evalLabels;
        ctxEval.data.datasets[0].data = evalValues;
        ctxEval.update();
        
        /* PRODUCCIÓN POR PLANTACIÓN */
        const prodModLabels = Object.keys(jsonMod.produccion_plantacion || {});
        const prodModValues = Object.values(jsonMod.produccion_plantacion || {});
        ctxProd.data.labels = prodModLabels;
        ctxProd.data.datasets[0].data = prodModValues;
        ctxProd.update();
    }

    // Boton aplicar
    document.getElementById('btnAplicar').addEventListener('click', () => { loadData(); });

    // Inicial
    loadData();

    // Export buttons (export canvas to PNG)
    document.querySelectorAll('.btn-export').forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.getAttribute('data-canvas');
            const canvas = document.getElementById(id);
            const url = canvas.toDataURL('image/png');
            const a = document.createElement('a');
            a.href = url;
            a.download = id + '.png';
            a.click();
        });
    });

});
</script>
@endsection
@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .canvas-small {
    width: 100% !important;
    max-height: 220px !important; /* tamaño visual cómodo y nítido */
}

/* para pantallas pequeñas */
@media (max-width: 768px) {
    .canvas-small {
        max-height: 180px !important;
    }
}

.container-dashboard {
    padding: 20px;
    background-color: #5282289d;
    border-radius: 10px;
}
.card {
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.9);
}
.canvas-small {
    width: 100% !important;
    height: 2500px !important; /* 50% más pequeñas */
}
.button-33 {
  background-color: #073c1a;
  border-radius: 100px;
  box-shadow: rgba(44,187,99,.2) 0 -25px 18px -14px inset,
              rgba(44,187,99,.15) 0 1px 2px,
              rgba(44,187,99,.15) 0 2px 4px,
              rgba(44,187,99,.15) 0 4px 8px,
              rgba(44,187,99,.15) 0 8px 16px,
              rgba(44,187,99,.15) 0 16px 32px;
  color: #fff;
  cursor: pointer;
  display: inline-block;
  padding: 8px 22px;
  text-align: center;
  border: 0;
  font-size: 15px;
  transition: all 250ms;
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
@media (max-width: 768px) {
    .container-dashboard {
        margin-left: -20px;
        width: 110%;
    }
}
</style>

<div class="container container-dashboard">
    <div class="row mb-3">
        <div class="col-md-9">
            <h3 class="text-white">👥 Panel Social Integral</h3>
            <p class="text-white">Indicadores sociales filtrables en tiempo real.</p>
        </div>

        <div class="col-md-12 d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="{{ route('visitas_social.indexSocial') }}" class="button-33 me-2">📋 Lista de Visitas Sociales</a>
                <a href="{{ url()->previous() }}" class="button-33">⬅️ Volver atrás</a>
            </div>
            <div>
                <a href="{{ route('dashboard.visitas.social') }}" class="btn btn-light btn-sm fw-bold shadow-sm">
                    🔁 Reset filtros
                </a>
            </div>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="card mb-4 p-3 shadow-sm">
       <form id="filtrosForm" class="row g-2" method="GET" action="{{ route('dashboard.visitas.social') }}">
    <div class="col-md-3">
        <label>Desde</label>
        <input type="date" name="desde" id="filtroDesde" class="form-control" value="{{ request('desde') }}">
    </div>
    <div class="col-md-3">
        <label>Hasta</label>
        <input type="date" name="hasta" id="filtroHasta" class="form-control" value="{{ request('hasta') }}">
    </div>
    <div class="col-md-3">
        <label>Proveedor</label>
        <select name="proveedor" id="filtroProveedor" class="form-control">
            <option value="">Todos</option>
            @foreach($proveedores as $p)
                <option value="{{ $p->id }}" {{ request('proveedor') == $p->id ? 'selected' : '' }}>
                    {{ $p->proveedor_nombre }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 text-end">
        <label>&nbsp;</label>
        <button type="submit" id="btnAplicar" class="btn btn-success w-100">Aplicar filtros</button>
    </div>
</form>

    </div>

    {{-- TARJETAS PRINCIPALES --}}
    <div class="row text-center mb-4">
        <div class="col-md-3"><div class="card shadow p-3"><h6>Visitas Sociales</h6><h2 class="text-primary">{{ $totalVisitas }}</h2></div></div>
        <div class="col-md-3"><div class="card shadow p-3"><h6>Proveedores Visitados</h6><h2 class="text-success">{{ $totalProveedores }}</h2></div></div>
        <div class="col-md-3"><div class="card shadow p-3"><h6>Total Trabajadores</h6><h2 class="text-info">{{ $totalTrabajadores }}</h2></div></div>
        <div class="col-md-3"><div class="card shadow p-3"><h6>Miembros de Hogar</h6><h2 class="text-warning">{{ $totalMiembros }}</h2></div></div>
    </div>

    {{-- GRÁFICAS SOCIALES --}}
    <div class="card shadow mb-3 p-3">
        <h5 class="text-center">📅 Visitas Sociales por Mes</h5>
        <canvas id="visitasMesChart" class="canvas-small"></canvas>
    </div>

    <div class="row mb-3">
        <div class="col-md-6"><div class="card shadow p-3"><h5 class="text-center">👨‍🌾 Promedio Hombres vs Mujeres</h5><canvas id="fuerzaGenero" class="canvas-small"></canvas></div></div>
        <div class="col-md-6"><div class="card shadow p-3"><h5 class="text-center">🩺 Seguridad Social</h5><canvas id="seguridadChart" class="canvas-small"></canvas></div></div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6"><div class="card shadow p-3"><h5 class="text-center">📄 Tipos de Contrato</h5><canvas id="contratoChart" class="canvas-small"></canvas></div></div>
        <div class="col-md-6"><div class="card shadow p-3"><h5 class="text-center">🏠 Forma de Tenencia</h5><canvas id="tenenciaChart" class="canvas-small"></canvas></div></div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6"><div class="card shadow p-3"><h5 class="text-center">🎓 Nivel Educativo</h5><canvas id="nivelChart" class="canvas-small"></canvas></div></div>
        <div class="col-md-6"><div class="card shadow p-3"><h5 class="text-center">🤝 Participación Social</h5><canvas id="orgChart" class="canvas-small"></canvas></div></div>
    </div>

    <div class="card shadow mb-3 p-3">
        <h5 class="text-center">👨‍👩‍👧‍👦 Distribución por Sexo en Hogares</h5>
        <canvas id="sexoChart" class="canvas-small"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const colorSet = ['#4BC0C0','#FF6384','#36A2EB','#FFCE56','#9966FF','#FF9F40'];

// VISITAS POR MES
new Chart(document.getElementById('visitasMesChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($visitasPorMes->pluck('mes')) !!},
        datasets: [{
            label: 'Visitas',
            data: {!! json_encode($visitasPorMes->pluck('total')) !!},
            borderColor: '#36A2EB',
            backgroundColor: 'rgba(54,162,235,0.2)',
            fill: true,
            tension: 0.4
        }]
    }
});

// FUERZA LABORAL POR GÉNERO
new Chart(document.getElementById('fuerzaGenero'), {
    type: 'pie',
    data: {
        labels: ['Hombres','Mujeres'],
        datasets: [{
            data: [{{ round($fuerzaGenero->hombres ?? 0,1) }}, {{ round($fuerzaGenero->mujeres ?? 0,1) }}],
            backgroundColor: ['#36A2EB','#FF6384']
        }]
    }
});

// SEGURIDAD SOCIAL
new Chart(document.getElementById('seguridadChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($seguridad->pluck('seguridad_social')) !!},
        datasets: [{
            label: 'Trabajadores',
            data: {!! json_encode($seguridad->pluck('total')) !!},
            backgroundColor: colorSet
        }]
    }
});

// TIPO DE CONTRATO
new Chart(document.getElementById('contratoChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($contratos->pluck('tipo_contrato')) !!},
        datasets: [{
            data: {!! json_encode($contratos->pluck('total')) !!},
            backgroundColor: colorSet
        }]
    }
});

// TENENCIA
new Chart(document.getElementById('tenenciaChart'), {
    type: 'polarArea',
    data: {
        labels: {!! json_encode($tenencia->pluck('forma_tenencia')) !!},
        datasets: [{
            data: {!! json_encode($tenencia->pluck('total')) !!},
            backgroundColor: colorSet
        }]
    }
});

// NIVEL EDUCATIVO
new Chart(document.getElementById('nivelChart'), {
    type: 'radar',
    data: {
        labels: {!! json_encode($niveles->pluck('nivel_estudio')) !!},
        datasets: [{
            label: 'Cantidad',
            data: {!! json_encode($niveles->pluck('total')) !!},
            backgroundColor: 'rgba(255,99,132,0.3)',
            borderColor: 'rgb(255,99,132)'
        }]
    }
});

// PARTICIPACIÓN
new Chart(document.getElementById('orgChart'), {
    type: 'bar',
    data: {
        labels: ['JAC','Asociaciones'],
        datasets: [{
            label: 'Participantes',
            data: [{{ $participacion['jac'] }}, {{ $participacion['asociacion'] }}],
            backgroundColor: ['#00A86B','#008B8B']
        }]
    }
});

// SEXO HOGAR
new Chart(document.getElementById('sexoChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($sexoHogar->pluck('sexo')) !!},
        datasets: [{
            label: 'Número de Personas',
            data: {!! json_encode($sexoHogar->pluck('total')) !!},
            backgroundColor: ['#36A2EB','#FF6384']
        }]
    }
});
</script>
@endsection

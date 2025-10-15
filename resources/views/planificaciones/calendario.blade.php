@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header con Estadísticas -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-success shadow-lg border-0">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="text-white mb-1">
                                <i class="fas fa-calendar-alt me-2"></i>Calendario de Planificaciones
                            </h2>
                            <p class="text-white opacity-8 mb-0">
                                Visualización y gestión de todas las visitas programadas
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="bg-white rounded-pill px-3 py-1 d-inline-block">
                                <span class="text-success fw-bold fs-5" id="total-eventos">0</span>
                                <span class="text-dark">Eventos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Acción Rápida -->
    <div class="row mb-4">
        @if(Auth::check() && in_array(Auth::user()->rol, [1,2]))
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <span class="text-xs font-weight-bold text-primary text-uppercase">Nueva Planificación</span>
                    <a href="{{ route('planificaciones.create') }}" class="btn btn-primary btn-circle">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <span class="text-xs font-weight-bold text-info text-uppercase">Lista Completa</span>
                    <a href="{{ route('planificaciones.index') }}" class="btn btn-info btn-circle">
                        <i class="fas fa-list"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <span class="text-xs font-weight-bold text-warning text-uppercase">Vista Semanal</span>
                    <button class="btn btn-warning btn-circle" onclick="changeView('timeGridWeek')">
                        <i class="fas fa-calendar-week"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros Rápidos -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-filter me-2"></i>Filtrar por Estado
            </h6>
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-success active" data-status="all">Todos</button>
                <button type="button" class="btn btn-outline-warning" data-status="pendiente">Pendientes</button>
                <button type="button" class="btn btn-outline-info" data-status="completada">Completadas</button>
                <button type="button" class="btn btn-outline-danger" data-status="cancelada">Canceladas</button>
            </div>
        </div>
    </div>

    <!-- Calendario -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-calendar me-2"></i>Vista de Calendario
            </h6>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-success" id="prev-btn"><i class="fas fa-chevron-left"></i></button>
                <button class="btn btn-sm btn-outline-success" id="today-btn">Hoy</button>
                <button class="btn btn-sm btn-outline-success" id="next-btn"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="eventoModal" tabindex="-1" aria-labelledby="eventoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="eventoModalLabel"><i class="fas fa-calendar-check me-2"></i>Detalle de Visita</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Tipo:</strong> <span id="modalTipo"></span></p>
                    <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                    <p><strong>Estado:</strong> <span id="modalEstado"></span></p>
                    <div class="d-grid gap-2 mt-3">
                        <a id="modalVerBtn" href="#" class="btn btn-success"><i class="fas fa-eye me-2"></i> Ver Detalles</a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-2"></i> Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* mismos estilos que el calendario social */
.bg-gradient-success { background: linear-gradient(45deg, #1cc88a, #13855c) !important; }
.btn-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.fc-day-today { background-color: rgba(28,200,138,0.1) !important; }
.event-pendiente { background-color: #f6c23e; color: #000; }
.event-completada { background-color: #1cc88a; color: #fff; }
.event-cancelada { background-color: #e74a3b; color: #fff; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let calendar;
    let allEvents = [];

    function initCalendar() {
        const calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek' },
            buttonText: { today: 'Hoy', month: 'Mes', week: 'Semana', day: 'Día', list: 'Lista' },
            events: { url: '/api/planificaciones', method: 'GET' },
            eventDidMount: function(info) {
                const estado = info.event.extendedProps.estado || 'pendiente';
                info.el.classList.add(`event-${estado}`);
            },
            eventClick: function(info) {
                fetch(`/api/planificacion/${info.event.id}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('modalTipo').textContent = data.tipo_visita;
                        document.getElementById('modalFecha').textContent = data.fecha;
                        document.getElementById('modalEstado').textContent = data.estado;
                        document.getElementById('modalVerBtn').href = `/planificaciones/${data.id}`;
                        new bootstrap.Modal(document.getElementById('eventoModal')).show();
                    });
            },
            eventsSet: function(events) {
                allEvents = events;
                updateEventCount(allEvents.length);
            }
        });

        calendar.render();

        document.getElementById('prev-btn').addEventListener('click', () => calendar.prev());
        document.getElementById('next-btn').addEventListener('click', () => calendar.next());
        document.getElementById('today-btn').addEventListener('click', () => calendar.today());
    }

    // Filtros
    document.querySelectorAll('[data-status]').forEach(button => {
        button.addEventListener('click', function() {
            const status = this.getAttribute('data-status');
            document.querySelectorAll('[data-status]').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            let filteredEvents = status === 'all' ? allEvents : allEvents.filter(e => (e.extendedProps.estado || 'pendiente') === status);
            calendar.removeAllEvents();
            filteredEvents.forEach(ev => calendar.addEvent(ev));
            updateEventCount(filteredEvents.length);
        });
    });

    function updateEventCount(count) { document.getElementById('total-eventos').textContent = count; }

    initCalendar();
});
</script>
@endsection

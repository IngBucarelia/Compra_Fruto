@extends('layouts.app')

@section('content')
<div class="container" >
    <div class="row">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
                <h2 class="title">Calendario de Planificaciones</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('planificaciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> <span class="d-none d-md-inline">Listado</span>
                    </a>
                    <a href="{{ route('planificaciones.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> <span class="d-none d-md-inline">Nueva</span>
                    </a>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="eventoModal" tabindex="-1" aria-labelledby="eventoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="eventoModalLabel">Detalle de visita</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Tipo:</strong> <span id="modalTipo"></span></p>
                            <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                            <p><strong>Estado:</strong> <span id="modalEstado"></span></p>
                            <div class="d-grid gap-2">
                                <a id="modalVerBtn" href="#" class="btn btn-primary mt-2">
                                    <i class="fas fa-eye"></i> Ver planificación
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendario -->
            <div class="card">
                <div class="card-body p-0">
                    <div id="calendar" style="min-height: 70vh;"></div>
                </div>
            </div>
        </div>
    </div><button type="button" class="btn btn-secondary" onclick="history.back()">Cancelar</button>
</div>

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />
<!-- Font Awesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<!-- Bootstrap Bundle JS (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        views: {
            dayGridMonth: {
                titleFormat: { year: 'numeric', month: 'long' }
            },
            timeGridWeek: {
                titleFormat: { year: 'numeric', month: 'short', day: 'numeric' }
            },
            listWeek: {
                titleFormat: { year: 'numeric', month: 'short', day: 'numeric' }
            }
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            list: 'Lista'
        },
        events: '/api/planificaciones',
        eventColor: '#198754',
        eventDisplay: 'block',
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        },
        eventDidMount: function(info) {
            // Ajustar el tamaño de los eventos en móvil
            if (window.innerWidth < 768) {
                info.el.style.fontSize = '0.8em';
                info.el.style.padding = '2px';
            }
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
        }
    });

    calendar.render();

    // Ajustar calendario al cambiar tamaño de pantalla
    window.addEventListener('resize', function() {
        calendar.updateSize();
    });
});
</script>

<style>

    .container{
        background-color: rgba(129, 165, 114, 0.929);
        padding: 20px;
    }

    .title{
    text-align: center; 
    font-family: Arial Black; 
    font-weight: bold; 
    font-size: 30px; 
    color: #fdffe5; 
    text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;
    }

/* Estilos responsivos */
@media (max-width: 767.98px) {
    #calendar .fc-header-toolbar {
        flex-direction: column;
        gap: 0.5rem;
    }
    #calendar .fc-toolbar-title {
        font-size: 1.2rem;
        margin: 0.5rem 0;
    }
    #calendar .fc-button {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
    .fc-daygrid-event {
        white-space: normal !important;
        line-height: 1.2 !important;
    }
    .container {
        margin-left: -35px;
        width: 110%;
    

    }

        .dashboard-content {
            max-width: 100%;
        }
        .dashboard-card {
            margin-bottom: 15px;
        }
}

/* Mejoras generales */
.fc-event {
    cursor: pointer;
    transition: all 0.2s;
}
.fc-event:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
</style>
@endsection
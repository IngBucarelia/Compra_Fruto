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
                                <i class="fas fa-calendar-alt me-2"></i>Calendario de Planificaciones Sociales
                            </h2>
                            <p class="text-white opacity-8 mb-0">
                                Visualización y gestión de todas las planificaciones sociales programadas
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
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Nueva Planificación
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('planificaciones_social.create') }}" class="btn btn-primary btn-circle">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Lista Completa
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('planificaciones_social.index') }}" class="btn btn-info btn-circle">
                                <i class="fas fa-list"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Vista Semanal
                            </div>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-warning btn-circle" onclick="changeView('timeGridWeek')">
                                <i class="fas fa-calendar-week"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros Rápidos -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-filter me-2"></i>Filtrar por Estado
                    </h6>
                </div>
                <div class="col-md-6">
                    <div class="btn-group btn-group-sm float-end">
                        <button type="button" class="btn btn-outline-success active" data-status="all">
                            Todos
                        </button>
                        <button type="button" class="btn btn-outline-warning" data-status="pendiente">
                            Pendientes
                        </button>
                        <button type="button" class="btn btn-outline-info" data-status="completada">
                            Completadas
                        </button>
                        <button type="button" class="btn btn-outline-danger" data-status="cancelada">
                            Canceladas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendario -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-calendar me-2"></i>Vista de Calendario
                </h6>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-success" id="prev-btn">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-success" id="today-btn">
                        Hoy
                    </button>
                    <button class="btn btn-sm btn-outline-success" id="next-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Modal de Detalles -->
    <div class="modal fade" id="eventoModal" tabindex="-1" aria-labelledby="eventoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="eventoModalLabel">
                        <i class="fas fa-calendar-check me-2"></i>Detalle de Planificación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-success">Tipo de Visita:</label>
                                <p id="modalTipo" class="mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-success">Fecha:</label>
                                <p id="modalFecha" class="mb-0"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-success">Estado:</label>
                                <p id="modalEstado" class="mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-success">Proveedor:</label>
                                <p id="modalProveedor" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-3">
                        <a id="modalVerBtn" href="#" class="btn btn-success">
                            <i class="fas fa-eye me-2"></i> Ver Detalles Completos
                        </a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales-all.global.min.js"></script>



<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: 2px solid #e3e6f0;
}

.btn-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-circle:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #1cc88a, #13855c) !important;
}

/* FullCalendar Personalizado */
#calendar {
    padding: 20px;
    min-height: 70vh;
}

.fc .fc-toolbar-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1cc88a;
}

.fc .fc-button {
    background-color: #1cc88a;
    border-color: #1cc88a;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.fc .fc-button:hover {
    background-color: #13855c;
    border-color: #13855c;
    transform: translateY(-2px);
}

.fc .fc-button-primary:not(:disabled).fc-button-active {
    background-color: #13855c;
    border-color: #13855c;
}

.fc-event {
    border-radius: 8px;
    border: none;
    padding: 4px 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.fc-event:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.fc-day-today {
    background-color: rgba(28, 200, 138, 0.1) !important;
}

/* Colores para diferentes estados */
.event-pendiente { background-color: #f6c23e; color: #000; }
.event-completada { background-color: #1cc88a; color: #fff; }
.event-cancelada { background-color: #e74a3b; color: #fff; }

/* Responsive */
@media (max-width: 768px) {
    #calendar {
        padding: 10px;
        min-height: 60vh;
    }
    
    .fc .fc-toolbar {
        flex-direction: column;
        gap: 10px;
    }
    
    .fc .fc-toolbar-title {
        font-size: 1.2rem;
    }
    
    .fc .fc-button {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
    }
    
    .fc-event {
        font-size: 0.7rem;
        padding: 2px 4px;
    }
    
    .btn-circle {
        width: 35px;
        height: 35px;
    }
}

/* Animaciones */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.fc-event {
    animation: fadeIn 0.5s ease;
}

/* Loading state */
#calendar::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #1cc88a;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    display: none;
    z-index: 1000;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

.loading::before {
    display: block !important;
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function() {
    let calendar;
    let allEvents = [];
    
    // Inicializar calendario
    function initCalendar() {
        const calendarEl = document.getElementById('calendar');
        
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            events: {
                url: "{{ route('planificaciones_social.eventos') }}",
                method: 'GET',
                failure: function() {
                    showError('Error al cargar las planificaciones');
                }
            },

            loading: function(isLoading) {
                if (isLoading) {
                    document.getElementById('calendar').classList.add('loading');
                } else {
                    document.getElementById('calendar').classList.remove('loading');
                }
            },
            eventDidMount: function(info) {
                // Agregar clases según el estado
                const estado = info.event.extendedProps.estado || 'pendiente';
                info.el.classList.add(`event-${estado}`);
                
                // Tooltip
                info.el.setAttribute('title', `${info.event.title} - ${estado.toUpperCase()}`);
                
                // Responsive
                if (window.innerWidth < 768) {
                    info.el.style.fontSize = '0.7em';
                    info.el.style.padding = '2px 4px';
                }
            },
            eventClick: function(info) {
                showEventDetails(info.event);
            },
            eventsSet: function(events) {
            allEvents = events; // ya es un array
                console.log(allEvents.map(ev => ev.extendedProps.estado)); // 🔍 imprime todos los estados

            updateEventCount(allEvents.length);
}
        });

        calendar.render();
        
        // Configurar botones de navegación
        document.getElementById('prev-btn').addEventListener('click', function() {
            calendar.prev();
        });
        
        document.getElementById('next-btn').addEventListener('click', function() {
            calendar.next();
        });
        
        document.getElementById('today-btn').addEventListener('click', function() {
            calendar.today();
        });
    }

    // Mostrar detalles del evento
    function showEventDetails(event) {
        const extendedProps = event.extendedProps;
        
        document.getElementById('modalTipo').textContent = event.title || 'N/A';
        document.getElementById('modalFecha').textContent = event.start ? event.start.toLocaleDateString('es-ES') : 'N/A';
        document.getElementById('modalEstado').textContent = extendedProps.estado ? extendedProps.estado.toUpperCase() : 'N/A';
        document.getElementById('modalProveedor').textContent = extendedProps.proveedor || 'N/A';
        document.getElementById('modalVerBtn').href = `/planificaciones-social/${data.id}`;
        
        const modal = new bootstrap.Modal(document.getElementById('eventoModal'));
        modal.show();
    }

    // Cambiar vista
    window.changeView = function(viewName) {
        calendar.changeView(viewName);
    }

    // Actualizar contador de eventos
    function updateEventCount(count) {
        document.getElementById('total-eventos').textContent = count;
    }

    // Mostrar error
    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: message,
            confirmButtonColor: '#1cc88a'
        });
    }

    // 🔹 Filtros por estado
// 🔹 Filtros por estado
document.querySelectorAll('[data-status]').forEach(button => {
    button.addEventListener('click', function() {
        const status = this.getAttribute('data-status');
        
        // Actualizar botones activos
        document.querySelectorAll('[data-status]').forEach(btn => {
            btn.classList.remove('active');
        });
        this.classList.add('active');
        
        // 🔹 Siempre filtramos sobre allEvents original
        let filteredEvents;
        if (status === 'all') {
            filteredEvents = allEvents;
        } else {
            filteredEvents = allEvents.filter(event => {
                const eventStatus = event.extendedProps.estado || 'pendiente';
                return eventStatus === status;
            });
        }

        // 🔹 Refrescar eventos en el calendario
        calendar.removeAllEvents(); // limpia el calendario
        filteredEvents.forEach(ev => {
            calendar.addEvent(ev); // vuelve a pintar los que sí aplican
        });

        // 🔹 Actualizar contador
        updateEventCount(filteredEvents.length);
    });
});



    // Inicializar calendario
    initCalendar();

    // Ajustar calendario al redimensionar
    window.addEventListener('resize', function() {
        calendar.updateSize();
    });
});
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    
   <div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Calendario de Planificaciones</h2>
        <a href="{{ route('planificaciones.create') }}" class="btn btn-success">+ Nueva planificación</a>
    </div>
    <!-- Modal -->
        <div class="modal fade" id="eventoModal" tabindex="-1" aria-labelledby="eventoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventoModalLabel">Detalle de visita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p><strong>Tipo:</strong> <span id="modalTipo"></span></p>
                <p><strong>Fecha:</strong> <span id="modalFecha"></span></p>
                <p><strong>Estado:</strong> <span id="modalEstado"></span></p>
                <a id="modalVerBtn" href="#" target="_blank" class="btn btn-primary mt-2">Ver planificación</a>
            </div>
            </div>
        </div>
        </div>


    
    <div id="calendar" style="height: 600px;"></div>
    <a href="{{ route('planificaciones.index') }}" class="btn btn-secondary mt-4">Volver al listado</a>
</div>

<!-- FullCalendar CSS & JS -->
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />

<!-- FullCalendar JS (versión global, NO módulo) -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: '/api/planificaciones',
        eventColor: '#198754',
        eventClick: function(info) {
            // Obtener información adicional si la necesitas
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
});


</script>
@endsection

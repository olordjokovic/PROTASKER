@extends('layouts.dashboard')

@section('title', 'Calendario de Eventos')
@section('page-title', 'Calendario de Eventos')

@section('styles')
.calendar-card{
    padding: 24px;
}

#calendar{
    background: #fff;
    border-radius: 20px;
    padding: 18px;
}

.fc .fc-toolbar-title{
    font-weight: 800;
    color: #14213d;
}

.fc .fc-button{
    border-radius: 10px !important;
    font-weight: 600 !important;
}

.event-detail-box{
    background: #f8fafc;
    border: 1px solid rgba(15,23,42,0.08);
    border-radius: 18px;
    padding: 20px;
    margin-top: 24px;
}

.detail-label{
    font-weight: 700;
    color: #64748b;
    font-size: .85rem;
    text-transform: uppercase;
}

.detail-value{
    color: #14213d;
    font-weight: 600;
    margin-bottom: 14px;
}
@endsection

@section('content')
<div class="card calendar-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="mb-1">Calendario de proyectos y tareas</h4>
            <p class="text-muted mb-0">
                Consulta vencimientos, tareas pendientes y proyectos asignados.
            </p>
        </div>

        <div class="d-flex gap-2">
            <span class="badge bg-primary">Proyectos</span>
            <span class="badge bg-warning text-dark">Tareas Pendientes</span>

            <span class="badge bg-success">Tareas Completadas</span>
        </div>
    </div>

    <div id="calendar"></div>

    <div id="eventDetail" class="event-detail-box d-none">
        <h5 class="mb-3">Detalle seleccionado</h5>

       <div class="row">
    <div class="col-md-4">
        <div class="detail-label">Tipo</div>
        <div class="detail-value" id="detailTipo"></div>
    </div>

    <div class="col-md-4">
        <div class="detail-label">Nombre</div>
        <div class="detail-value" id="detailNombre"></div>
    </div>

    <div class="col-md-4">
        <div class="detail-label">Estado</div>
        <div class="detail-value" id="detailEstado"></div>
    </div>

    <div class="col-md-4">
        <div class="detail-label">Usuario</div>
        <div class="detail-value" id="detailUsuario"></div>
    </div>

    <div class="col-md-4">
        <div class="detail-label">Fecha</div>
        <div class="detail-value" id="detailFecha"></div>
    </div>

    <div class="col-md-4">
        <div class="detail-label">Descripción</div>
        <div class="detail-value" id="detailDescripcion"></div>
    </div>
</div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        height: 720,
        firstDay: 1,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Lista'
        },
        events: '{{ route('calendar.events') }}',

        eventClick: function(info) {
            const props = info.event.extendedProps;

            document.getElementById('eventDetail').classList.remove('d-none');

            document.getElementById('detailTipo').innerText = props.tipo || '-';
            document.getElementById('detailNombre').innerText = props.nombre || '-';
            document.getElementById('detailEstado').innerText = props.estado || '-';

            document.getElementById('detailUsuario').innerText =
                (props.usuario || '-') + ' - ' + (props.email || 'Sin correo');

            
            document.getElementById('detailFecha').innerText = props.fecha || '-';
            document.getElementById('detailDescripcion').innerText = props.descripcion || '-';
        }
    });

    calendar.render();
});
</script>
@endsection
@extends('layouts.dashboard')

@section('title', 'Eventos de Google Calendar')
@section('page-title', 'Eventos de Google Calendar')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        @if(session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="card shadow-sm p-4 mb-4">
            <h4 class="mb-4">Crear evento</h4>

            <form method="POST" action="{{ route('google.calendar.events.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="summary" class="form-label">Título</label>
                    <input type="text" name="summary" id="summary" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="start_datetime" class="form-label">Fecha y hora de inicio</label>
                    <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="end_datetime" class="form-label">Fecha y hora de fin</label>
                    <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Crear evento en Google Calendar
                </button>
            </form>
        </div>

        <div class="card shadow-sm p-4">
            <h4 class="mb-4">Próximos eventos</h4>

            @if(isset($items) && count($items) > 0)
                @foreach($items as $event)
                    @php
                        $start = $event->start->dateTime ?? $event->start->date ?? 'Sin fecha';
                        $end = $event->end->dateTime ?? $event->end->date ?? 'Sin fecha';
                    @endphp

                    <div class="border rounded p-3 mb-3">
                        <h5 class="mb-2">{{ $event->summary ?? 'Sin título' }}</h5>

                        <p class="mb-1">
                            <strong>Inicio:</strong> {{ $start }}
                        </p>

                        <p class="mb-1">
                            <strong>Fin:</strong> {{ $end }}
                        </p>

                        @if(!empty($event->description))
                            <p class="mb-0">
                                <strong>Descripción:</strong> {{ $event->description }}
                            </p>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="alert alert-info mb-0">
                    No hay eventos próximos en tu Google Calendar.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
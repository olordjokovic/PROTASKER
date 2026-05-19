@extends('layouts.dashboard')

@section('title', 'Detalle de tareas')
@section('page-title', 'Detalle de tareas')

@section('content')
<div class="card p-4">

    <h4 class="mb-4">
        {{ $title }}
    </h4>

    <div class="table-responsive">
        <table class="table align-middle">

            <thead>
                <tr>
                    <th>Tarea</th>
                    <th>Descripción</th>
                    <th>Asignado a</th>
                    <th>Estado</th>
                    <th>Prioridad</th>
                    <th>Fecha límite</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($tasks as $task)

                    <tr>
                        <td>{{ $task->title }}</td>

                        <td>
                            {{ $task->description ?: 'Sin descripción' }}
                        </td>

                        <td>
                            {{ $task->assignedUser->name ?? 'Sin asignar' }}
                        </td>

                        <td>
                            {{ ucfirst($task->status) }}
                        </td>

                        <td>
                            {{ ucfirst($task->priority ?? 'normal') }}
                        </td>

                        <td>
                            {{ $task->due_date ?? 'Sin fecha' }}
                        </td>

                        <td>

                            @if(!$user->isAdmin())

                                <a
                                    href="{{ route('dashboard.report', ['task', $task->id]) }}"
                                    class="btn btn-outline-danger btn-sm"
                                >
                                    Informar problema
                                </a>

                                @if($task->status !== 'completada')

                                    <a
                                        href="{{ route('completion.create', ['task', $task->id]) }}"
                                        class="btn btn-success btn-sm"
                                    >
                                        Marcar como completada
                                    </a>

                                @endif

                            @endif

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="8">
                            No hay tareas.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>
</div>
@endsection
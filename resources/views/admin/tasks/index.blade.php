@extends('layouts.dashboard')

@section('title', 'Admin tareas')
@section('page-title', 'Gestión de tareas')

@section('content')

<a href="{{ route('admin.tasks.create') }}" class="btn btn-primary mb-3">
    Nueva tarea
</a>

<div class="card shadow-sm p-3">

    <table class="table table-bordered align-middle">

        <thead>
            <tr>
                
                <th>Título</th>
                <th>Descripción</th>
                <th>Asignada a</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Fecha límite</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

        @forelse($tasks as $item)

            <tr>
                

                <td>
                    {{ $item->title }}
                </td>

                <td style="max-width:250px;">
                    {{ $item->description }}
                </td>

                <td>
                    {{ $item->assignedUser?->name }}
                    {{ $item->assignedUser?->surname }}
                </td>

                <td>
                    {{ $item->assignedUser?->email }}
                </td>

                <td>
                    {{ ucfirst($item->status) }}
                </td>

                <td>
                    {{ ucfirst($item->priority) }}
                </td>

                <td>
                    {{ $item->due_date ?? 'Sin fecha' }}
                </td>

                <td>

                    <a
                        href="{{ route('admin.tasks.edit', $item) }}"
                        class="btn btn-warning btn-sm"
                    >
                        Editar
                    </a>

                    <form
                        action="{{ route('admin.tasks.destroy', $item) }}"
                        method="POST"
                        class="d-inline"
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Eliminar tarea?')"
                        >
                            Borrar
                        </button>
                    </form>

                </td>
            </tr>

        @empty

            <tr>
                <td colspan="9" class="text-center text-muted py-4">
                    No hay tareas registradas.
                </td>
            </tr>

        @endforelse

        </tbody>
    </table>
</div>

@endsection

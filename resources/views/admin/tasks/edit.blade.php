@extends('layouts.dashboard')

@section('title', 'Editar tarea')
@section('page-title', 'Editar tarea')

@section('content')
<div class="card shadow-sm p-4">

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tasks.update', $task) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Usuario asignado</label>

            <input
                type="text"
                class="form-control"
                value="{{ $task->assignedUser->name }} {{ $task->assignedUser->surname }} - {{ $task->assignedUser->email }}"
                disabled
            >

            <input
                type="hidden"
                name="assigned_user_id"
                value="{{ $task->assigned_user_id }}"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Título</label>

            <input
                type="text"
                name="title"
                class="form-control"
                value="{{ old('title', $task->title) }}"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>

            <textarea
                name="description"
                class="form-control"
                required
            >{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>

            <select name="status" class="form-select" required>
                <option value="pendiente" @selected(old('status', $task->status) == 'pendiente')>
                    pendiente
                </option>

               

                <option value="completada" @selected(old('status', $task->status) == 'completada')>
                    completada
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Prioridad</label>

            <select name="priority" class="form-select" required>
                <option value="baja" @selected(old('priority', $task->priority) == 'baja')>
                    baja
                </option>

                <option value="media" @selected(old('priority', $task->priority) == 'media')>
                    media
                </option>

                <option value="alta" @selected(old('priority', $task->priority) == 'alta')>
                    alta
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha límite</label>

            <input
                type="date"
                name="due_date"
                class="form-control"
                value="{{ old('due_date', $task->due_date) }}"
                min="{{ now()->format('Y-m-d') }}"
                max="2099-12-31"
                required
            >
        </div>

        <button class="btn btn-primary">
            Actualizar
        </button>
    </form>
</div>
@endsection
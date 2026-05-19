@extends('layouts.dashboard')

@section('title', 'Crear tarea')
@section('page-title', 'Crear tarea')

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

    <form method="POST" action="{{ route('admin.tasks.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Asignar a usuario</label>

            <select name="assigned_user_id" class="form-select" required>

                @foreach($users as $u)

                    <option
                        value="{{ $u->id }}"
                        @selected(old('assigned_user_id') == $u->id)
                    >
                        {{ $u->name }} {{ $u->surname }} - {{ $u->email }}
                    </option>

                @endforeach

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Título</label>

            <input
                type="text"
                name="title"
                class="form-control"
                value="{{ old('title') }}"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>

            <textarea
                name="description"
                class="form-control"
                required
            >{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>

            <select name="status" class="form-select" required>

                <option
                    value="pendiente"
                    @selected(old('status') == 'pendiente')
                >
                    pendiente
                </option>

                

                

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Prioridad</label>

            <select name="priority" class="form-select" required>

                <option
                    value="baja"
                    @selected(old('priority') == 'baja')
                >
                    baja
                </option>

                <option
                    value="media"
                    @selected(old('priority') == 'media')
                >
                    media
                </option>

                <option
                    value="alta"
                    @selected(old('priority') == 'alta')
                >
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
                value="{{ old('due_date') }}"
                min="{{ now()->format('Y-m-d') }}"
                max="2099-12-31"
                required
            >
        </div>

        <button class="btn btn-primary">
            Guardar
        </button>
    </form>
</div>
@endsection
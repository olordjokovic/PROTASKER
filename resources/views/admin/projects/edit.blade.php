@extends('layouts.dashboard')

@section('title', 'Editar proyecto')
@section('page-title', 'Editar proyecto')

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

    <form method="POST" action="{{ route('admin.projects.update', $project) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Usuario propietario</label>

            <input
                type="text"
                class="form-control"
                value="{{ $project->user->name }} {{ $project->user->surname }} - {{ $project->user->email }}"
                disabled
            >

            <input
                type="hidden"
                name="user_id"
                value="{{ $project->user_id }}"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre</label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name', $project->name) }}"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>

            <textarea
                name="description"
                class="form-control"
                required
            >{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>

            <select name="status" class="form-select" required>
                <option value="pendiente" @selected(old('status', $project->status) == 'pendiente')>
                    pendiente
                </option>

               

                <option value="completado" @selected(old('status', $project->status) == 'completado')>
                    completado
                </option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha inicio</label>

            <input
                type="date"
                name="start_date"
                class="form-control"
                value="{{ old('start_date', $project->start_date) }}"
                min="{{ now()->toDateString() }}"
                max="2099-12-31"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha fin</label>

            <input
                type="date"
                name="end_date"
                class="form-control"
                value="{{ old('end_date', $project->end_date) }}"
                min="{{ now()->toDateString() }}"
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
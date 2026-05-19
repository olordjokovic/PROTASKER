@extends('layouts.dashboard')

@section('title', 'Crear proyecto')
@section('page-title', 'Crear proyecto')

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

    <form method="POST" action="{{ route('admin.projects.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Usuario propietario</label>

            <select name="user_id" class="form-select" required>

                @foreach($users as $u)

                    <option
                        value="{{ $u->id }}"
                        @selected(old('user_id') == $u->id)
                    >
                        {{ $u->name }} {{ $u->surname }} - {{ $u->email }}
                    </option>

                @endforeach

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre</label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name') }}"
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
            <label class="form-label">Fecha inicio</label>

            <input
                type="date"
                name="start_date"
                class="form-control"
                value="{{ old('start_date') }}"
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
                value="{{ old('end_date') }}"
                min="{{ now()->toDateString() }}"
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
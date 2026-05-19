@extends('layouts.dashboard')

@section('title', 'Crear usuario')
@section('page-title', 'Crear usuario')

@section('content')
<div class="card shadow-sm p-4">

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Rol</label>

            <select name="role_id" class="form-select">

                @foreach($roles as $role)

                    @if($role->name !== 'admin')
                        <option value="{{ $role->id }}">
                            {{ ucfirst($role->name) }}
                        </option>
                    @endif

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
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Apellidos</label>

            <input
                type="text"
                name="surname"
                class="form-control"
                value="{{ old('surname') }}"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>

            <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>

            <input
                type="password"
                name="password"
                class="form-control"
            >
        </div>

        <button class="btn btn-primary">
            Guardar
        </button>
    </form>
</div>
@endsection
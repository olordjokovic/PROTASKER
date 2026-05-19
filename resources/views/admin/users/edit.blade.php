@extends('layouts.dashboard')

@section('title', 'Editar usuario')
@section('page-title', 'Editar usuario')

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

    <form method="POST" action="{{ route('admin.users.update', $userToEdit) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Rol</label>

            @if($userToEdit->isMainAdmin())

                {{-- ADMIN PRINCIPAL BLOQUEADO --}}
                <input
                    type="text"
                    class="form-control"
                    value="Administrador principal"
                    disabled
                >

                <input
                    type="hidden"
                    name="role_id"
                    value="{{ $userToEdit->role_id }}"
                >

                <small class="text-danger">
                    El rol del administrador principal no puede modificarse.
                </small>

            @else

                {{-- RESTO DE USUARIOS --}}
                <select name="role_id" class="form-select">

                    @foreach($roles as $role)

                        @if($role->name !== 'admin')

                            <option
                                value="{{ $role->id }}"
                                @selected($userToEdit->role_id == $role->id)
                            >
                                {{ ucfirst($role->name) }}
                            </option>

                        @endif

                    @endforeach

                </select>

            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Nombre</label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name', $userToEdit->name) }}"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Apellidos</label>

            <input
                type="text"
                name="surname"
                class="form-control"
                value="{{ old('surname', $userToEdit->surname) }}"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>

            <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email', $userToEdit->email) }}"
            >
        </div>

        <button class="btn btn-primary">
            Actualizar
        </button>
    </form>
</div>
@endsection
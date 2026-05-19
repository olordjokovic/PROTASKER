@extends('layouts.dashboard')

@section('title', 'Admin usuarios')
@section('page-title', 'Gestión de usuarios')

@section('content')
<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Nuevo usuario</a>

<div class="card shadow-sm p-3">
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }} {{ $item->surname }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->role?->name }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $item) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('admin.users.destroy', $item) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar usuario?')">Borrar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
@extends('layouts.dashboard')

@section('title', 'Admin proyectos')
@section('page-title', 'Gestión de proyectos')

@section('content')
<a href="{{ route('admin.projects.create') }}" class="btn btn-primary mb-3">Nuevo proyecto</a>

<div class="card shadow-sm p-3">
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Proyecto</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($projects as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->user?->name }}</td>
                <td>{{ $item->status }}</td>
                <td>
                    <a href="{{ route('admin.projects.edit', $item) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('admin.projects.destroy', $item) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar proyecto?')">Borrar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
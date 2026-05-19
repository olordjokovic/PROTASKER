@extends('layouts.dashboard')

@section('title', 'Solicitudes de finalización')
@section('page-title', 'Solicitudes de finalización')

@section('content')
<div class="card shadow-sm p-4">
    <table class="table align-middle">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @foreach($requests as $request)
                <tr>
                    <td>{{ $request->user->name }} - {{ $request->user->email }}</td>
                    <td>{{ $request->item_type }}</td>
                    <td>{{ $request->status }}</td>
                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('completion.admin.show', $request) }}" class="btn btn-primary btn-sm">
                            Revisar
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
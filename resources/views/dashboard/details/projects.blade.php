@extends('layouts.dashboard')

@section('title', 'Detalle de proyectos')
@section('page-title', 'Detalle de proyectos')

@section('content')
<div class="card p-4">
    <h4 class="mb-4">Proyectos</h4>

    <div class="table-responsive">
        <table class="table align-middle">

            <thead>
                <tr>
                    <th>Proyecto</th>
                    <th>Descripción</th>
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse($projects as $project)

                    <tr>
                        <td>{{ $project->name }}</td>

                        <td>
                            {{ $project->description ?: 'Sin descripción' }}
                        </td>

                        <td>
                            {{ $project->user->name ?? 'Sin usuario' }}
                        </td>

                        <td>
                            {{ ucfirst($project->status) }}
                        </td>

                        <td>
                            {{ $project->start_date ?? 'Sin fecha' }}
                        </td>

                        <td>
                            {{ $project->end_date ?? 'Sin fecha' }}
                        </td>

                        <td>

                            @if(!$user->isAdmin())

                                <a
                                    href="{{ route('dashboard.report', ['project', $project->id]) }}"
                                    class="btn btn-outline-danger btn-sm"
                                >
                                    Informar problema
                                </a>

                                @if($project->status !== 'completado')

                                    <a
                                        href="{{ route('completion.create', ['project', $project->id]) }}"
                                        class="btn btn-success btn-sm"
                                    >
                                        Marcar como completado
                                    </a>

                                @endif

                            @endif

                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7">
                            No hay proyectos.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>
</div>
@endsection
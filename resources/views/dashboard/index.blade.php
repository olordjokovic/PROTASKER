@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', $pageTitle)

@section('content')

<div class="row g-4">
    <div class="col-md-4">
        <a href="{{ route('dashboard.projects') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm stat-card">
                <h6 class="text-muted">
                    @if($user->isAdmin())
                        Total proyectos
                    @else
                        Mis proyectos
                    @endif
                </h6>
                <h2>{{ $totalProjects }}</h2>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="{{ route('dashboard.tasks.pending') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm stat-card">
                <h6 class="text-muted">Total Tareas pendientes</h6>
                <h2>{{ $pendingTasks }}</h2>
            </div>
        </a>
    </div>

    

    <div class="col-md-4">
        <a href="{{ route('dashboard.tasks.completed') }}" class="text-decoration-none text-dark">
            <div class="card shadow-sm stat-card">
                <h6 class="text-muted">Total Tareas Completadas</h6>
                <h2>{{ $completedTasks }}</h2>
            </div>
        </a>
    </div>
</div>

<div class="card shadow-sm mt-4 p-4">
    <h4>{{ $dashboardMessage }}</h4>
    <p class="text-muted mb-0">
        {{ $dashboardDescription }}
    </p>
</div>

@endsection
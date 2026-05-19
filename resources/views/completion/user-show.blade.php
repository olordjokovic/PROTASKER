{{-- resources/views/completion/user-show.blade.php --}}

@extends('layouts.dashboard')

@section('title', 'Resultado de solicitud')
@section('page-title', 'Resultado de solicitud')

@section('content')
<div class="card shadow-sm p-4">

    <h3 class="mb-3">
        Decisión sobre solicitud de finalización
    </h3>

    <p class="text-muted">
        Resultado:
        <strong>{{ ucfirst($completionRequest->status) }}</strong>
    </p>

    <hr>

    <p>
        <strong>Elemento:</strong>
        {{ $item->name ?? $item->title ?? 'Elemento no encontrado' }}
    </p>

    <p>
        <strong>Tu justificación:</strong><br>
        {{ $completionRequest->description }}
    </p>

    @if($completionRequest->evidence_path)
        <div class="mb-4">
            <strong>Tu evidencia enviada:</strong><br>

            <img
                src="{{ asset('storage/' . $completionRequest->evidence_path) }}"
                style="max-width:320px; border-radius:14px; margin-top:10px;"
                alt="Evidencia del usuario"
            >
        </div>
    @endif

    <div class="alert {{ $completionRequest->status === 'aprobada' ? 'alert-success' : 'alert-danger' }}">
        <strong>Respuesta del administrador:</strong><br>
        {{ $completionRequest->admin_response }}
    </div>

    @if($completionRequest->admin_evidence_path)
        <div class="mt-4">
            <strong>Evidencia adjuntada por el administrador:</strong><br>

            <img
                src="{{ asset('storage/' . $completionRequest->admin_evidence_path) }}"
                style="max-width:320px; border-radius:14px; margin-top:10px;"
                alt="Evidencia del administrador"
            >
        </div>
    @endif

</div>
@endsection
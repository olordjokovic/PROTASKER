@extends('layouts.dashboard')

@section('title', 'Revisar solicitud')
@section('page-title', 'Revisar solicitud')

@section('content')
<div class="card shadow-sm p-4">

    <h4>{{ $item->name ?? $item->title }}</h4>

    <p><strong>Usuario:</strong> {{ $completionRequest->user->name }} - {{ $completionRequest->user->email }}</p>
    <p><strong>Justificación:</strong> {{ $completionRequest->description }}</p>

    @if($completionRequest->evidence_path)
        <p>
            <strong>Evidencia enviada:</strong><br>
            <img src="{{ asset('storage/' . $completionRequest->evidence_path) }}" style="max-width: 320px; border-radius: 12px;">
        </p>
    @endif

    <hr>

    <form method="POST" enctype="multipart/form-data" class="mb-3">
        @csrf

        <div class="mb-3">
            <label class="form-label">Justificación del admin</label>
            <textarea name="admin_response" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Evidencia opcional del admin</label>
            <input type="file" name="admin_evidence" class="form-control" accept="image/*">
        </div>

        <button formaction="{{ route('completion.approve', $completionRequest) }}" class="btn btn-success">
            Aprobar
        </button>

        <button formaction="{{ route('completion.reject', $completionRequest) }}" class="btn btn-danger">
            Rechazar
        </button>
    </form>
</div>
@endsection
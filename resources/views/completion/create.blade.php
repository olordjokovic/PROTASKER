@extends('layouts.dashboard')

@section('title', 'Marcar como completado')
@section('page-title', 'Marcar como completado')

@section('content')
<div class="card shadow-sm p-4">

    <h4>Solicitud de finalización</h4>

    <p class="text-muted">
        Vas a solicitar finalizar:
        <strong>{{ $item->name ?? $item->title }}</strong>
    </p>

    <form method="POST" action="{{ route('completion.store', [$type, $item->id]) }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Descripción / justificación</label>
            <textarea name="description" class="form-control" rows="6" required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Evidencia opcional</label>
            <input type="file" name="evidence" class="form-control" accept="image/*">
        </div>

        <button class="btn btn-success">
            Enviar solicitud
        </button>
    </form>
</div>
@endsection
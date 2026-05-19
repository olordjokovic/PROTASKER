@extends('layouts.dashboard')

@section('title', 'Informar problema')
@section('page-title', 'Informar problema')

@section('content')
<div class="card p-4">
    <h4 class="mb-4">Informar problema</h4>

    <div class="alert alert-info">
        @if($type === 'project')
            Vas a informar un problema sobre el proyecto: <strong>{{ $item->name }}</strong>
        @else
            Vas a informar un problema sobre la tarea: <strong>{{ $item->title }}</strong>
        @endif
    </div>

    <form method="POST" action="{{ route('dashboard.report.send', [$type, $item->id]) }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Asunto</label>
            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Mensaje</label>
            <textarea name="message" rows="7" class="form-control">{{ old('message') }}</textarea>
        </div>

        <button class="btn btn-primary">
            Enviar aviso
        </button>
    </form>
</div>
@endsection
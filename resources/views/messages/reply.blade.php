@extends('layouts.dashboard')

@section('title', 'Responder mensaje')
@section('page-title', 'Responder mensaje')

@section('content')

<div class="card shadow-sm p-4">

    <div class="mb-4">
        <h5>Mensaje original</h5>

        <div class="border rounded-4 p-3 bg-light">
            <strong>{{ $message->subject }}</strong>

            <p class="mt-3 mb-0">
                {!! nl2br(e($message->body)) !!}
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('messages.reply.store', $message) }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Responder a</label>
            <input type="text" class="form-control" value="{{ $receiver->name }} ({{ $receiver->email }})" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Mensaje</label>
            <textarea name="message" rows="7" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary">
            Enviar respuesta
        </button>

        <a href="{{ route('messages.show', $message) }}" class="btn btn-outline-secondary">
            Cancelar
        </a>
    </form>

</div>

@endsection
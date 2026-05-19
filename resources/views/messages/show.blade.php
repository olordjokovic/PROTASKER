@extends('layouts.dashboard')

@section('title', 'Mensaje')
@section('page-title', 'Mensaje recibido')

@section('content')

<div class="card shadow-sm p-4">

    <h3 class="mb-3">
        {{ $message->subject }}
    </h3>

    <div class="mb-4 text-muted">
        De:
        {{ $message->sender->name }}
    </div>

    <div class="border rounded-4 p-4">
        {!! nl2br(e($message->body)) !!}
    </div>

    <div class="mt-4">
    <a href="{{ route('messages.reply', $message) }}" class="btn btn-primary">
        Responder
    </a>
</div>

</div>

@endsection
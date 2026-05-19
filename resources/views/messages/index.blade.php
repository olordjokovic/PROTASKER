@extends('layouts.dashboard')

@section('title', 'Mensajes')
@section('page-title', 'Bandeja de mensajes')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Mensajes recibidos</h4>

    <a href="{{ route('messages.create') }}"
       class="btn btn-primary">
        Nuevo mensaje
    </a>
</div>

<div class="card shadow-sm p-4">

    @forelse($messages as $message)

        <a href="{{ route('messages.show', $message) }}"
           class="text-decoration-none text-dark">

            <div class="border rounded-4 p-3 mb-3">

                <div class="d-flex justify-content-between">
                    <strong>
                        {{ $message->subject }}
                    </strong>

                    @if(!$message->read)
                        <span class="badge bg-danger">
                            Nuevo
                        </span>
                    @endif
                </div>

                <div class="text-muted mt-2">
                    De:
                    {{ $message->sender->name }}
                </div>

            </div>

        </a>

    @empty

        <p class="mb-0">
            No tienes mensajes.
        </p>

    @endforelse

</div>

@endsection
@extends('layouts.dashboard')

@section('title', 'Nuevo mensaje')
@section('page-title', 'Enviar mensaje')

@section('content')

<div class="card shadow-sm p-4">

    <form method="POST" action="{{ route('messages.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">
                Destinatario
            </label>

            <select
                name="receiver_id"
                class="form-select"
            >
                @foreach($receivers as $receiver)

                    <option value="{{ $receiver->id }}">
                        {{ $receiver->name }}
                        ({{ $receiver->email }})
                    </option>

                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">
                Asunto
            </label>

            <input
                type="text"
                name="subject"
                class="form-control"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">
                Mensaje
            </label>

            <textarea
                name="message"
                rows="7"
                class="form-control"
            ></textarea>
        </div>

        <button class="btn btn-primary">
            Enviar mensaje
        </button>

    </form>

</div>

@endsection
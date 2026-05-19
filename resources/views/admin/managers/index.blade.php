@extends('layouts.dashboard')

@section('title', 'Gestores')
@section('page-title', 'Gestores')

@section('content')
<div class="card p-4">
    <h4 class="mb-4">Asignar usuarios a gestores</h4>

    @foreach($managers as $manager)
        <div class="border rounded p-3 mb-4">
            <h5>{{ $manager->name }} {{ $manager->surname }}</h5>
            <p class="text-muted">{{ $manager->email }}</p>

            <form method="POST" action="{{ route('admin.managers.syncUsers', $manager) }}">
                @csrf

                <div class="row">
                    @foreach($users as $normalUser)
                        <div class="col-md-6 mb-2">
                            <label class="form-check">
                                <input
                                    type="checkbox"
                                    name="user_ids[]"
                                    value="{{ $normalUser->id }}"
                                    class="form-check-input"
                                    @checked($manager->managedUsers->contains($normalUser->id))
                                >
                                <span class="form-check-label">
                                    {{ $normalUser->name }} {{ $normalUser->surname }} - {{ $normalUser->email }}
                                </span>
                            </label>
                        </div>
                    @endforeach
                </div>

                <button class="btn btn-primary mt-3">
                    Guardar asignación
                </button>
            </form>
        </div>
    @endforeach
</div>
@endsection
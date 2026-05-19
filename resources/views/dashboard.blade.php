@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
.card{
    border:none;
    border-radius:15px;
    padding:40px;
    width:100%;
    text-align:center;
}
.auth-card{
    max-width:600px;
    width:100%;
}
@endsection

@section('content')
<div class="auth-card">
    <div class="card shadow-lg">
        <h2 class="mb-3 text-dark">Bienvenido, {{ session('user_name') }}</h2>
        <p class="text-muted">Has iniciado sesión correctamente en ProTasker.</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger">Cerrar sesión</button>
        </form>
    </div>
</div>
@endsection
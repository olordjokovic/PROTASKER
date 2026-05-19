@extends('layouts.app')

@section('title', 'Verificar cuenta')

@section('styles')
body{
    background:
        radial-gradient(circle at top left, rgba(13,110,253,0.18), transparent 35%),
        radial-gradient(circle at bottom right, rgba(111,66,193,0.18), transparent 35%),
        linear-gradient(135deg, #f4f7fb 0%, #e9eef8 100%);
    min-height: 100vh;
}

.auth-wrapper{
    min-height: calc(100vh - 120px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}

.auth-container{
    max-width: 960px;
    width: 100%;
}

.auth-card{
    border: none;
    border-radius: 28px;
    overflow: hidden;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(10px);
    box-shadow: 0 25px 70px rgba(18, 38, 63, 0.15);
}

.auth-left{
    padding: 52px 42px;
}

.auth-title{
    font-size: 2.25rem;
    font-weight: 800;
    color: #14213d;
    margin-bottom: 16px;
    letter-spacing: -0.03em;
}

.auth-subtitle{
    color: #64748b;
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 26px;
}

.form-label{
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
}

.form-control{
    border-radius: 12px;
    padding: 12px 14px;
    border: 1px solid rgba(15, 23, 42, 0.12);
    box-shadow: none;
}

.form-control:focus{
    border-color: rgba(13,110,253,0.45);
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.10);
}

.code-input{
    text-align: center;
    font-size: 1.6rem;
    font-weight: 700;
    letter-spacing: 0.35em;
}

.btn-main{
    border-radius: 12px;
    padding: 12px;
    font-weight: 600;
    transition: all 0.25s ease;
}

.btn-main:hover{
    transform: translateY(-2px);
}

.alert{
    border: none;
    border-radius: 14px;
}

.auth-right{
    background:
        linear-gradient(160deg, #0d6efd 0%, #3d7bfd 45%, #6f42c1 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 34px;
    position: relative;
    overflow: hidden;
}

.auth-panel{
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 24px;
    padding: 30px 26px;
    color: #fff;
    backdrop-filter: blur(8px);
    max-width: 300px;
    width: 100%;
    box-shadow: 0 18px 40px rgba(0,0,0,0.18);
}

.auth-panel h5{
    font-weight: 800;
    margin-bottom: 14px;
}

.auth-panel p{
    font-size: 0.95rem;
    opacity: 0.92;
    margin-bottom: 0;
}

.metric-card{
    background: rgba(255,255,255,0.14);
    border-radius: 18px;
    padding: 14px 16px;
    margin-top: 16px;
}

.metric-label{
    font-size: 0.84rem;
    opacity: 0.82;
    margin-bottom: 4px;
}

.metric-value{
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 0;
}

.floating-dot{
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.14);
}

.dot-1{
    width: 120px;
    height: 120px;
    top: 18px;
    right: 18px;
}

.dot-2{
    width: 82px;
    height: 82px;
    bottom: 26px;
    left: 18px;
}

.dot-3{
    width: 42px;
    height: 42px;
    top: 110px;
    left: 34px;
}

@media (max-width: 991px){
    .auth-left{
        padding: 40px 28px;
    }

    .auth-title{
        font-size: 1.95rem;
        text-align: center;
    }

    .auth-subtitle{
        text-align: center;
    }

    .auth-right{
        padding: 28px 22px;
    }
}

@media (max-width: 575.98px){
    .auth-title{
        font-size: 1.75rem;
    }

    .auth-left{
        padding: 34px 22px;
    }

    .code-input{
        font-size: 1.35rem;
        letter-spacing: 0.22em;
    }
}
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-container">
        <div class="card auth-card">
            <div class="row g-0">

                <div class="col-lg-7">
                    <div class="auth-left">
                        <h3 class="auth-title text-center text-lg-start">Verificar cuenta</h3>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <p class="auth-subtitle">
                            Introduce el código de 6 dígitos que se ha enviado a tu correo.
                        </p>

                        <form method="POST" action="{{ route('register.code.verify') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Código de verificación</label>
                                <input type="text" name="code" maxlength="6" class="form-control code-input" placeholder="123456">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-main">
                                Verificar cuenta
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="auth-right">
                        <div class="floating-dot dot-1"></div>
                        <div class="floating-dot dot-2"></div>
                        <div class="floating-dot dot-3"></div>

                        <div class="auth-panel position-relative">
                            <h5>Acceso verificado</h5>
                            <p>
                                Confirma tu registro con el código temporal enviado
                                a tu correo y activa tu cuenta en ProTasker.
                            </p>

                            <div class="metric-card">
                                <div class="metric-label">Código temporal</div>
                                <p class="metric-value">6 dígitos</p>
                            </div>

                            <div class="metric-card">
                                <div class="metric-label">Tiempo de validez</div>
                                <p class="metric-value">10 min</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
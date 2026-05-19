@extends('layouts.app')

@section('title', 'Verificar código')

@section('styles')
body{
    min-height: 100vh;
    overflow-x: hidden;
    background:
        radial-gradient(circle at top left, rgba(13,110,253,0.22), transparent 32%),
        radial-gradient(circle at bottom right, rgba(111,66,193,0.22), transparent 34%),
        linear-gradient(135deg, #f4f7fb 0%, #e9eef8 100%);
}

.auth-wrapper{
    min-height: calc(100vh - 120px);
    display: flex;
    align-items: stretch;
    justify-content: center;
    padding: 0;
}

.auth-container{
    width: 100%;
    max-width: 1540px;
    min-height: calc(100vh - 120px);
    display: flex;
    align-items: center;
}

.auth-card{
    width: 100%;
    min-height: 78vh;
    border: none;
    border-radius: 0;
    overflow: hidden;
    background: rgba(255,255,255,0.42);
    backdrop-filter: blur(10px);
    box-shadow: none;
}

.auth-card .row{
    min-height: 78vh;
}

.auth-left{
    min-height: 78vh;
    padding: 70px 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.auth-left-content{
    width: 100%;
    max-width: 520px;
}

.auth-badge{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 16px;
    border-radius: 999px;
    background: rgba(13,110,253,0.10);
    color: #0d6efd;
    font-weight: 700;
    font-size: 0.95rem;
    margin-bottom: 24px;
}

.auth-badge span{
    background:#0d6efd;
    color:#fff;
    font-size:.75rem;
    padding:3px 8px;
    border-radius:999px;
}

.auth-title{
    font-size: clamp(2.6rem, 5vw, 4.6rem);
    line-height: .98;
    font-weight: 900;
    color: #14213d;
    margin-bottom: 18px;
    letter-spacing: -0.06em;
}

.auth-subtitle{
    color: #64748b;
    font-size: 1.08rem;
    line-height: 1.7;
    margin-bottom: 30px;
}

.form-label{
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
}

.form-control{
    border-radius: 0;
    padding: 13px 15px;
    border: 1px solid rgba(15, 23, 42, 0.14);
    box-shadow: none;
    background: rgba(255,255,255,0.92);
}

.form-control:focus{
    border-color: rgba(13,110,253,0.55);
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.10);
}

.code-input{
    text-align: center;
    font-size: 1.7rem;
    font-weight: 800;
    letter-spacing: 0.35em;
}

.btn-main{
    border-radius: 0;
    padding: 13px;
    font-weight: 700;
    transition: all 0.25s ease;
}

.btn-main:hover{
    transform: translateY(-2px);
}

.btn-primary.btn-main{
    box-shadow: 0 14px 30px rgba(13,110,253,0.28);
}

.alert{
    border: none;
    border-radius: 0;
}

.auth-right{
    min-height: 78vh;
    background:
        linear-gradient(160deg, #0d6efd 0%, #3d7bfd 45%, #6f42c1 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 70px 60px;
    position: relative;
    overflow: hidden;
}

.auth-right::before{
    content: "";
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 22% 22%, rgba(255,255,255,0.22), transparent 18%),
        radial-gradient(circle at 80% 76%, rgba(255,255,255,0.16), transparent 23%);
}

.auth-panel{
    position: relative;
    z-index: 2;
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.24);
    border-radius: 0;
    padding: 36px;
    color: #fff;
    backdrop-filter: blur(12px);
    max-width: 460px;
    width: 100%;
    box-shadow: 0 28px 70px rgba(0,0,0,0.22);
}

.auth-panel h5{
    font-size: 1.6rem;
    font-weight: 900;
    margin-bottom: 14px;
}

.auth-panel p{
    font-size: 1rem;
    opacity: 0.92;
    line-height: 1.7;
    margin-bottom: 0;
}

.metric-card{
    background: rgba(255,255,255,0.14);
    border-radius: 0;
    padding: 16px 18px;
    margin-top: 16px;
}

.metric-label{
    font-size: 0.84rem;
    opacity: 0.82;
    margin-bottom: 4px;
}

.metric-value{
    font-size: 1.25rem;
    font-weight: 800;
    margin-bottom: 0;
}

.floating-dot{
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.14);
    z-index: 1;
}

.dot-1{
    width: 210px;
    height: 210px;
    top: 8%;
    right: 8%;
}

.dot-2{
    width: 130px;
    height: 130px;
    bottom: 9%;
    left: 9%;
}

.dot-3{
    width: 72px;
    height: 72px;
    top: 42%;
    left: 12%;
}

@media (max-width: 991px){
    .auth-container{
        min-height: auto;
    }

    .auth-card,
    .auth-card .row,
    .auth-left,
    .auth-right{
        min-height: auto;
    }

    .auth-left{
        padding: 60px 30px;
        text-align: center;
    }

    .auth-title{
        font-size: 2.5rem;
    }

    .auth-right{
        min-height: 420px;
        padding: 50px 24px;
    }
}

@media (max-width: 575.98px){
    .auth-title{
        font-size: 2.1rem;
    }

    .auth-left{
        padding: 46px 22px;
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
                        <div class="auth-left-content">

                            <div class="auth-badge">
                                <span>Pro</span>
                                Código de seguridad
                            </div>

                            <h1 class="auth-title">
                                Verificar código
                            </h1>

                            <p class="auth-subtitle">
                                Introduce el código de 6 dígitos para continuar con el proceso de verificación.
                            </p>

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

                            <form method="POST" action="{{ route('code.verify') }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Código de 6 dígitos</label>
                                    <input
                                        type="text"
                                        name="code"
                                        maxlength="6"
                                        class="form-control code-input"
                                        placeholder="123456"
                                    >
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-main">
                                    Verificar código
                                </button>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="auth-right">
                        <div class="floating-dot dot-1"></div>
                        <div class="floating-dot dot-2"></div>
                        <div class="floating-dot dot-3"></div>

                        <div class="auth-panel">
                            <h5>Verificación segura</h5>
                            <p>
                                Confirma tu identidad con un código temporal para proteger
                                el acceso a tu cuenta en ProTasker.
                            </p>

                            <div class="metric-card">
                                <div class="metric-label">Código temporal</div>
                                <p class="metric-value">6 dígitos</p>
                            </div>

                            <div class="metric-card">
                                <div class="metric-label">Validez de seguridad</div>
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
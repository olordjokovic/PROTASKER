@extends('layouts.app')

@section('title', 'ProTasker')

@section('styles')
body{
    min-height: 100vh;
    overflow-x: hidden;
    background:
        radial-gradient(circle at top left, rgba(13,110,253,0.22), transparent 32%),
        radial-gradient(circle at bottom right, rgba(111,66,193,0.22), transparent 34%),
        linear-gradient(135deg, #f4f7fb 0%, #e9eef8 100%);
}

.welcome-full{
    min-height: calc(100vh - 120px);
    display: flex;
    align-items: stretch;
    justify-content: center;
    padding: 0;
}

.hero-shell{
    width: 100%;
    min-height: calc(100vh - 120px);
    display: grid;
    grid-template-columns: 1.05fr 0.95fr;
    background: rgba(255,255,255,0.38);
    backdrop-filter: blur(10px);
}

.hero-left{
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 70px 80px;
}

.hero-content{
    max-width: 620px;
    width: 100%;
}

.hero-badge{
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

.hero-title{
    font-size: clamp(3rem, 6vw, 5.6rem);
    line-height: 0.98;
    font-weight: 900;
    color: #14213d;
    margin-bottom: 24px;
    letter-spacing: -0.06em;
}

.hero-subtitle{
    font-size: 1.25rem;
    color: #64748b;
    line-height: 1.7;
    max-width: 520px;
    margin-bottom: 38px;
}

.hero-actions{
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: center;
}

.btn-main{
    min-width: 165px;
    height: 56px;
    border-radius: 14px;
    padding: 0 22px;
    font-weight: 700;
    font-size: 0.96rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
}

.btn-main:hover{
    transform: translateY(-3px);
}

.btn-primary.btn-main{
    background: linear-gradient(135deg, #0d6efd 0%, #3b82f6 100%);
    border: none;
    box-shadow: 0 14px 30px rgba(13,110,253,0.28);
}

.admin-btn{
    background: linear-gradient(135deg, #111827 0%, #1e293b 45%, #334155 100%);
    color: white !important;
    border: none;
    position: relative;
    overflow: hidden;
    box-shadow:
        0 14px 32px rgba(15,23,42,0.35),
        inset 0 1px 0 rgba(255,255,255,0.08);
}

.admin-btn::before{
    content: "";
    position: absolute;
    top: 0;
    left: -120%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255,255,255,0.18),
        transparent
    );
    transition: 0.7s;
}

.admin-btn:hover::before{
    left: 120%;
}

.admin-btn:hover{
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 45%, #475569 100%);
    color: white !important;
    transform: translateY(-3px) scale(1.02);
}

.hero-right{
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 70px 60px;
    background:
        linear-gradient(160deg, #0d6efd 0%, #3d7bfd 45%, #6f42c1 100%);
}

.hero-right::before{
    content: "";
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at 20% 20%, rgba(255,255,255,0.22), transparent 18%),
        radial-gradient(circle at 80% 78%, rgba(255,255,255,0.16), transparent 22%);
}

.dashboard-preview{
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 520px;
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.24);
    border-radius: 30px;
    padding: 28px;
    color: #fff;
    backdrop-filter: blur(12px);
    box-shadow: 0 28px 70px rgba(0,0,0,0.22);
}

.preview-header{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 26px;
}

.preview-title{
    font-size: 1.1rem;
    font-weight: 800;
    margin: 0;
}

.preview-pill{
    padding: 7px 13px;
    border-radius: 999px;
    background: rgba(255,255,255,0.18);
    font-size: 0.85rem;
    font-weight: 700;
}

.preview-grid{
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.metric-card{
    background: rgba(255,255,255,0.15);
    border-radius: 22px;
    padding: 20px;
}

.metric-label{
    font-size: 0.9rem;
    opacity: 0.86;
    margin-bottom: 8px;
}

.metric-value{
    font-size: 2rem;
    font-weight: 900;
    margin-bottom: 12px;
}

.metric-bar{
    height: 8px;
    border-radius: 999px;
    background: rgba(255,255,255,0.22);
    overflow: hidden;
}

.metric-bar span{
    display: block;
    height: 100%;
    border-radius: 999px;
    background: #fff;
}

.preview-wide{
    grid-column: span 2;
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

@media (max-width: 1200px){
    .btn-main{
        min-width: 150px;
        height: 54px;
        font-size: 0.9rem;
        padding: 0 18px;
    }
}

@media (max-width: 991.98px){
    .hero-shell{
        grid-template-columns: 1fr;
    }

    .hero-left{
        padding: 60px 30px;
        text-align: center;
    }

    .hero-subtitle{
        margin-left: auto;
        margin-right: auto;
    }

    .hero-actions{
        justify-content: center;
    }

    .hero-right{
        padding: 50px 24px;
    }
}

@media (max-width: 575.98px){
    .hero-left{
        padding: 46px 22px;
    }

    .hero-actions{
        flex-direction: column;
        width: 100%;
    }

    .btn-main{
        width: 100%;
    }

    .preview-grid{
        grid-template-columns: 1fr;
    }

    .preview-wide{
        grid-column: span 1;
    }
}
@endsection

@section('content')
<div class="welcome-full">
    <div class="hero-shell">
        <section class="hero-left">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="badge rounded-pill text-bg-primary">Pro</span>
                    Plataforma de gestión
                </div>

                <h1 class="hero-title">Bienvenido a ProTasker</h1>

                <p class="hero-subtitle">
                    Gestión de proyectos y tareas
                </p>

                <div class="hero-actions d-flex flex-wrap gap-3">

    <a href="/login" class="btn btn-primary hero-btn">
        Iniciar sesión
    </a>

    <a href="/register" class="btn btn-outline-primary hero-btn">
        Registrarse
    </a>

    <a href="/loginadmin" class="btn admin-btn hero-btn">
        Acceso admin
    </a>

    <a href="/about" class="btn btn-outline-primary hero-btn">
        Acerca de ProTasker
    </a>

</div>
            </div>
        </section>

        <section class="hero-right">
            <div class="floating-dot dot-1"></div>
            <div class="floating-dot dot-2"></div>
            <div class="floating-dot dot-3"></div>

        </section>
    
@endsection
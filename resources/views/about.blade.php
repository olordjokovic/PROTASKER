@extends('layouts.app')

@section('title', 'Acerca de ProTasker')

@section('styles')
body{
    min-height: 100vh;
    background:
        radial-gradient(circle at top left, rgba(13,110,253,0.22), transparent 32%),
        radial-gradient(circle at bottom right, rgba(111,66,193,0.22), transparent 34%),
        linear-gradient(135deg, #f4f7fb 0%, #e9eef8 100%);
}

.about-wrapper{
    min-height: calc(100vh - 120px);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:60px 24px;
}

.about-card{
    max-width:1100px;
    width:100%;
    border:none;
    border-radius:30px;
    overflow:hidden;
    background:rgba(255,255,255,0.92);
    backdrop-filter:blur(10px);
    box-shadow:0 25px 70px rgba(18,38,63,0.15);
}

.about-left{
    padding:60px 56px;
}

.about-title{
    font-size:clamp(2.4rem, 5vw, 4.5rem);
    line-height:1;
    font-weight:900;
    color:#14213d;
    letter-spacing:-0.05em;
    margin-bottom:24px;
}

.about-text{
    color:#64748b;
    font-size:1.08rem;
    line-height:1.75;
    margin-bottom:20px;
}

.about-actions{
    display:flex;
    gap:16px;
    flex-wrap:wrap;
    margin-top:32px;
}

.btn-main{
    min-width:180px;
    border-radius:14px;
    padding:14px 22px;
    font-weight:700;
}

.about-right{
    background:linear-gradient(160deg,#0d6efd 0%,#3d7bfd 45%,#6f42c1 100%);
    padding:50px 36px;
    color:white;
    position:relative;
    overflow:hidden;
}

.about-panel{
    position:relative;
    z-index:2;
    background:rgba(255,255,255,0.14);
    border:1px solid rgba(255,255,255,0.22);
    border-radius:26px;
    padding:28px;
    backdrop-filter:blur(10px);
    box-shadow:0 24px 60px rgba(0,0,0,0.20);
}

.feature{
    background:rgba(255,255,255,0.14);
    border-radius:18px;
    padding:18px;
    margin-bottom:16px;
}

.feature h5{
    font-weight:800;
    margin-bottom:8px;
}

.feature p{
    opacity:.9;
    margin-bottom:0;
    line-height:1.55;
}

.floating-dot{
    position:absolute;
    border-radius:50%;
    background:rgba(255,255,255,0.14);
}

.dot-1{width:180px;height:180px;top:30px;right:30px;}
.dot-2{width:110px;height:110px;bottom:40px;left:30px;}
.dot-3{width:60px;height:60px;top:45%;left:18%;}

@media(max-width:991px){
    .about-left{
        padding:44px 30px;
        text-align:center;
    }

    .about-actions{
        justify-content:center;
    }

    .about-right{
        padding:36px 24px;
    }
}
@endsection

@section('content')
<div class="about-wrapper">
    <div class="card about-card">
        <div class="row g-0">
            <div class="col-lg-7">
                <div class="about-left">
                    <span class="badge rounded-pill text-bg-primary mb-3 px-3 py-2">
                        Acerca de ProTasker
                    </span>

                    <h1 class="about-title">Gestión de proyectos y tareas</h1>

                    <p class="about-text">
                        ProTasker es una aplicación orientada a la gestión de proyectos, tareas y usuarios dentro de una estructura organizada por roles.
                    </p>

                    <p class="about-text">
                        El sistema permite que un administrador tenga control completo sobre la plataforma, pudiendo gestionar usuarios, proyectos, tareas y asignar gestores responsables.
                    </p>

                    <p class="about-text">
                        Los gestores funcionan como responsables intermedios: pueden coordinar proyectos y tareas de los usuarios que tienen asignados, manteniendo una gestión clara y controlada.
                    </p>

                    <p class="about-text">
                        Los usuarios finales pueden acceder a su perfil, consultar sus tareas asignadas y trabajar dentro del flujo de proyectos definido por administradores y gestores.
                    </p>

                    <div class="about-actions">
                        

                        <a href="/" class="btn btn-outline-primary btn-main">
                            Volver al inicio
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="about-right h-100">
                    <div class="floating-dot dot-1"></div>
                    <div class="floating-dot dot-2"></div>
                    <div class="floating-dot dot-3"></div>

                    <div class="about-panel">
                        <div class="feature">
                            <h5>Administrador</h5>
                            <p>Control total sobre usuarios, roles, gestores, proyectos y tareas del sistema.</p>
                        </div>

                        <div class="feature">
                            <h5>Gestor</h5>
                            <p>Gestiona proyectos y tareas de los usuarios que tiene asignados.</p>
                        </div>

                        <div class="feature">
                            <h5>Usuario</h5>
                            <p>Consulta sus tareas, participa en proyectos y mantiene actualizado su perfil.</p>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - ProTasker')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{
            --sidebar-bg-1:#0f172a;
            --sidebar-bg-2:#1e3a8a;
            --sidebar-accent:#3b82f6;
            --page-bg:#eef3fb;
            --card-bg:rgba(255,255,255,0.82);
            --text-main:#14213d;
            --text-soft:#6b7280;
            --border-soft:rgba(15, 23, 42, 0.08);
            --shadow-main:0 20px 50px rgba(15, 23, 42, 0.10);
        }

        body{
            min-height:100vh;
            background:
                radial-gradient(circle at top left, rgba(59,130,246,0.12), transparent 28%),
                radial-gradient(circle at bottom right, rgba(99,102,241,0.12), transparent 32%),
                linear-gradient(135deg, #f7f9fd 0%, #eef3fb 45%, #e9f0fb 100%);
            color:var(--text-main);
        }

        .dashboard-shell{
            min-height:100vh;
        }

        .sidebar{
            min-height:100vh;
            background:
                linear-gradient(180deg, rgba(15,23,42,0.96) 0%, rgba(30,58,138,0.96) 100%);
            color:white;
            padding:28px 20px;
            position:relative;
            overflow:hidden;
            box-shadow: 12px 0 35px rgba(15, 23, 42, 0.14);
        }

        .sidebar::before,
        .sidebar::after{
            content:"";
            position:absolute;
            border-radius:50%;
            background:rgba(255,255,255,0.06);
            pointer-events:none;
        }

        .sidebar::before{
            width:160px;
            height:160px;
            top:-40px;
            right:-40px;
        }

        .sidebar::after{
            width:120px;
            height:120px;
            bottom:30px;
            left:-35px;
        }

        .brand-box{
            position:relative;
            z-index:1;
            margin-bottom:32px;
        }

        .brand-title{
            font-size:1.9rem;
            font-weight:800;
            letter-spacing:-0.03em;
            margin-bottom:6px;
        }

        .brand-subtitle{
            font-size:0.9rem;
            color:rgba(255,255,255,0.72);
            margin-bottom:0;
        }

        .nav-section{
            position:relative;
            z-index:1;
        }

        .sidebar a{
            color:rgba(255,255,255,0.92);
            text-decoration:none;
            display:flex;
            align-items:center;
            gap:10px;
            padding:13px 15px;
            border-radius:14px;
            margin-bottom:10px;
            font-weight:500;
            transition:all 0.25s ease;
            border:1px solid transparent;
        }

        .sidebar a:hover{
            background:rgba(255,255,255,0.10);
            border-color:rgba(255,255,255,0.08);
            transform:translateX(4px);
        }

        .main-panel{
            padding:20px;
        }

        .topbar{
            background:rgba(255,255,255,0.78);
            backdrop-filter: blur(10px);
            border:1px solid rgba(255,255,255,0.55);
            border-radius:22px;
            box-shadow:var(--shadow-main);
            padding:18px 24px;
            margin-bottom:22px;
        }

        .page-title{
            font-size:1.45rem;
            font-weight:700;
            color:var(--text-main);
            letter-spacing:-0.02em;
        }

        .user-box{
            display:flex;
            align-items:center;
            gap:14px;
            background:rgba(248,250,252,0.95);
            border:1px solid var(--border-soft);
            border-radius:18px;
            padding:8px 10px 8px 16px;
        }

        .user-name{
            font-weight:600;
            color:var(--text-main);
            white-space:nowrap;
        }

        .profile-mini{
            width:44px;
            height:44px;
            border-radius:50%;
            object-fit:cover;
            border:2px solid rgba(255,255,255,0.9);
            box-shadow:0 4px 14px rgba(15,23,42,0.12);
            background:#fff;
        }

        .content-area{
            background:var(--card-bg);
            backdrop-filter: blur(8px);
            border:1px solid rgba(255,255,255,0.55);
            border-radius:26px;
            box-shadow:var(--shadow-main);
            padding:28px;
            min-height:calc(100vh - 130px);
        }

        .alert{
            border:none;
            border-radius:16px;
            box-shadow:0 8px 24px rgba(15,23,42,0.06);
        }

        .stat-card{
            border:none;
            border-radius:20px;
            padding:24px;
            background:rgba(255,255,255,0.95);
            box-shadow:0 14px 30px rgba(15,23,42,0.08);
        }

        .btn{
            border-radius:12px;
            font-weight:600;
        }

        .btn-sm{
            border-radius:10px;
            padding:8px 14px;
        }

        .content-area .card{
            border:none;
            border-radius:22px;
            box-shadow:0 16px 35px rgba(15,23,42,0.08);
        }

        @media (max-width: 991.98px){
            .sidebar{
                min-height:auto;
                border-radius:0 0 24px 24px;
            }

            .main-panel{
                padding:14px;
            }

            .topbar{
                padding:16px 18px;
            }

            .content-area{
                padding:22px;
                min-height:auto;
            }

            .page-title{
                font-size:1.2rem;
            }

            .user-box{
                gap:10px;
                padding:8px 10px;
            }

            .user-name{
                font-size:0.95rem;
            }
        }
.icon-action{
    width:42px;
    height:42px;
    border-radius:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#fff;
    border:1px solid var(--border-soft);
    text-decoration:none;
    font-size:1.15rem;
    color:var(--text-main);
    position:relative;
    box-shadow:0 4px 14px rgba(15,23,42,0.08);
}

.icon-action:hover{
    background:#eef3fb;
    color:#0d6efd;
}

.icon-badge{
    position:absolute;
    top:-6px;
    right:-6px;
    min-width:20px;
    height:20px;
    border-radius:999px;
    background:#dc3545;
    color:#fff;
    font-size:0.72rem;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:700;
}


        @yield('styles')
    </style>
</head>
<body>
<div class="container-fluid dashboard-shell">
    <div class="row g-0">
        <div class="col-lg-2 col-md-3 sidebar">
            <div class="brand-box">
                <h4 class="brand-title">ProTasker</h4>
                <p class="brand-subtitle">Gestión inteligente</p>
            </div>

            <div class="nav-section">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('profile.show') }}">Mi perfil</a>

    @if($user->isAdmin())
        <a href="{{ route('admin.users.index') }}">Admin usuarios</a>
        <a href="{{ route('admin.managers.index') }}">Admin gestores</a>
        <a href="{{ route('admin.projects.index') }}">Admin proyectos</a>
        <a href="{{ route('admin.tasks.index') }}">Admin tareas</a>
         <a href="{{ route('completion.admin.index') }}">Solicitudes de finalización</a>
    @elseif($user->isGestor())
        <a href="{{ route('admin.projects.index') }}">Mis proyectos gestionados</a>
        <a href="{{ route('admin.tasks.index') }}">Tareas de mi equipo</a>
    @endif

    <a href="{{ route('messages.index') }}">Notificaciones</a>

    @if($user->google_access_token)
        <a href="{{ route('calendar.index') }}">Calendario de Eventos</a>
    @else
        <a href="{{ route('calendar.index') }}">Calendario de Eventos</a>
    @endif
</div>
        </div>

        <div class="col-lg-10 col-md-9 main-panel">
            <div class="topbar d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="mb-0 page-title">@yield('page-title', 'Dashboard')</h5>

                <div class="user-box">
    <a href="{{ route('messages.index') }}" class="icon-action" title="Notificaciones">
        🔔

        @if($user->unreadMessagesCount() > 0)
            <span class="icon-badge">
                {{ $user->unreadMessagesCount() }}
            </span>
        @endif
    </a>

    <a href="{{ route('messages.create') }}" class="icon-action" title="Enviar mensaje">
        ✉️
    </a>

    <span class="user-name">{{ $user->name }}</span>

                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" class="profile-mini">
                    @else
                        <img src="https://i.pinimg.com/474x/d9/d8/8e/d9d88e3d1f74e2b8ced3df051cecb81d.jpg" class="profile-mini">
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

            <div class="content-area">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>
</div>
</body>
</html>
@extends('layouts.dashboard')

@section('title', 'Mi perfil')
@section('page-title', 'Mi perfil')

@section('styles')
.profile-wrapper{
    max-width: 860px;
    margin: 0 auto;
}

.profile-card{
    border: none;
    border-radius: 26px;
    overflow: hidden;
    background: rgba(255,255,255,0.92);
    backdrop-filter: blur(8px);
    box-shadow: 0 22px 55px rgba(15, 23, 42, 0.10);
}

.profile-header{
    background:
        radial-gradient(circle at top left, rgba(59,130,246,0.18), transparent 35%),
        linear-gradient(135deg, #f8fbff 0%, #eef4ff 100%);
    padding: 38px 30px 28px;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    text-align: center;
}

.profile-photo-large{
    width: 170px;
    height: 170px;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid rgba(255,255,255,0.95);
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.14);
    background: #fff;
}

.profile-title{
    font-size: 1.6rem;
    font-weight: 800;
    color: #14213d;
    margin-top: 20px;
    margin-bottom: 0;
    letter-spacing: -0.02em;
}

.profile-body{
    padding: 30px;
}

.info-grid{
    display: grid;
    grid-template-columns: 1fr;
    gap: 14px;
    margin-bottom: 28px;
}

.info-item{
    background: rgba(248,250,252,0.95);
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 18px;
    padding: 16px 18px;
    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
}

.info-label{
    display: block;
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #64748b;
    margin-bottom: 6px;
}

.info-value{
    font-size: 1.02rem;
    font-weight: 600;
    color: #14213d;
    margin: 0;
    word-break: break-word;
}

.section-divider{
    border: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(15,23,42,0.10), transparent);
    margin: 26px 0;
}

.section-title{
    font-size: 1.1rem;
    font-weight: 800;
    color: #14213d;
    margin-bottom: 18px;
    letter-spacing: -0.01em;
}

.upload-box{
    background: rgba(248,250,252,0.92);
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 20px;
    padding: 22px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
}

.form-control{
    border-radius: 14px;
    padding: 12px 14px;
    border: 1px solid rgba(15, 23, 42, 0.12);
    box-shadow: none;
}

.form-control:focus{
    border-color: rgba(13,110,253,0.45);
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.10);
}

.btn{
    border-radius: 12px;
    font-weight: 600;
    padding: 10px 16px;
}

.btn-primary{
    box-shadow: 0 10px 22px rgba(13,110,253,0.20);
}

.calendar-box{
    background: linear-gradient(135deg, rgba(248,250,252,0.98) 0%, rgba(241,245,249,0.98) 100%);
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 20px;
    padding: 22px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
}

.danger-zone{
    background: linear-gradient(135deg, rgba(255,245,245,0.98) 0%, rgba(254,242,242,0.98) 100%);
    border: 1px solid rgba(220, 53, 69, 0.18);
    border-radius: 20px;
    padding: 22px;
    box-shadow: 0 10px 24px rgba(220, 53, 69, 0.06);
}

.danger-zone .section-title{
    color: #dc3545;
}

.danger-text{
    color: #64748b;
    margin-bottom: 16px;
    line-height: 1.6;
}

.calendar-status{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(25,135,84,0.10);
    color: #198754;
    font-weight: 700;
    font-size: 0.92rem;
}

.action-row{
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 16px;
}

.remove-photo-form{
    margin-top: 8px;
}

@media (max-width: 767.98px){
    .profile-body{
        padding: 22px;
    }

    .profile-header{
        padding: 30px 20px 22px;
    }

    .profile-photo-large{
        width: 145px;
        height: 145px;
    }

    .action-row{
        flex-direction: column;
    }

    .action-row .btn{
        width: 100%;
    }

    .action-row form{
        width: 100%;
    }

    .action-row form .btn{
        width: 100%;
    }
}
@endsection

@section('content')
<div class="profile-wrapper">
    <div class="card profile-card">
        <div class="profile-header">
            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto de perfil" class="profile-photo-large">
            @else
                <img src="https://i.pinimg.com/474x/d9/d8/8e/d9d88e3d1f74e2b8ced3df051cecb81d.jpg" alt="Sin foto" class="profile-photo-large">
            @endif

            <h4 class="profile-title">Datos del usuario</h4>
        </div>

        <div class="profile-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nombre</span>
                    <p class="info-value">{{ $user->name }}</p>
                </div>

                <div class="info-item">
                    <span class="info-label">Apellidos</span>
                    <p class="info-value">{{ $user->surname }}</p>
                </div>

                <div class="info-item">
                    <span class="info-label">Email</span>
                    <p class="info-value">{{ $user->email }}</p>
                </div>
            </div>

            <hr class="section-divider">

            <div class="upload-box">
                <h5 class="section-title">Cambiar foto de perfil</h5>

                <form method="POST" action="{{ route('profile.updatePhoto') }}" enctype="multipart/form-data" class="mb-0">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Cambiar foto de perfil</label>
                        <input type="file" name="profile_photo" class="form-control">
                    </div>
                    <button class="btn btn-primary">Guardar nueva foto</button>
                </form>
@if($user->profile_photo)
                <form method="POST" action="{{ route('profile.removePhoto') }}" class="remove-photo-form">
                    @csrf
                    <button class="btn btn-outline-danger">Eliminar foto</button>
                </form>
            @endif

            

            </div>

            <hr class="section-divider">

<div class="danger-zone">
    <h5 class="section-title">Eliminar cuenta</h5>

    <p class="danger-text">
        Esta acción es irreversible. Se eliminarán todos tus datos, proyectos y tareas asociados a tu cuenta.
    </p>

    <form method="POST" action="{{ route('profile.delete') }}" onsubmit="return confirm('¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.')">
        @csrf
        @method('DELETE')

        <button class="btn btn-outline-danger">
            Eliminar mi cuenta
        </button>
    </form>
</div>

            

            
        </div>
    </div>
</div>
@endsection
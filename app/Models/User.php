<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* Representa a un usuario registrado en la aplicación.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'surname',
        'email',
        'profile_photo',
        'password',
        'google_id',
        'google_access_token',
        'google_refresh_token',
        'google_token_expires_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'google_token_expires_at' => 'datetime',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /* Obtiene el rol del usuario.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /* Obtiene las cuentas sociales asociadas al usuario (google).
     */
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    /* Obtiene los códigos de recuperación del usuario.
     */
    public function passwordResetCodes(): HasMany
    {
        return $this->hasMany(PasswordResetCode::class);
    }

    public function registrationCodes(): HasMany
    {
        return $this->hasMany(RegistrationCode::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_user_id');
    }

    public function managedUsers()
    {
        return $this->belongsToMany(User::class, 'manager_user', 'manager_id', 'user_id');
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'manager_user', 'user_id', 'manager_id');
    }

    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function isGestor(): bool
    {
        return $this->role && $this->role->name === 'gestor';
    }

    public function isUsuario(): bool
    {
        return $this->role && $this->role->name === 'usuario';
    }

    public function isMainAdmin(): bool
    {
        return $this->email === 'pinillar100@outlook.es';
    }

    public function sentMessages()
{
    return $this->hasMany(Message::class, 'sender_id');
}

public function receivedMessages()
{
    return $this->hasMany(Message::class, 'receiver_id');
}

public function unreadMessagesCount(): int
{
    return $this->receivedMessages()
        ->where('read', false)
        ->count();
}
}
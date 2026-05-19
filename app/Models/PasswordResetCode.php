<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* funcion que Almacena códigos temporales para restablecimiento de contraseña.
 */
class PasswordResetCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
        'used',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used' => 'boolean',
        ];
    }

    /**
     * Obtiene el usuario del código.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
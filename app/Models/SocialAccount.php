<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* Representa una cuenta social vinculada a un usuario (google ).
 */
class SocialAccount extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'provider_email',
    ];

    /* Obtiene el usuario propietario de google.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
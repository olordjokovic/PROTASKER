<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* Representa un rol dentro del sistema (gestor admin o usuario).
 */
class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    /* Obtiene los usuarios asociados al rol.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
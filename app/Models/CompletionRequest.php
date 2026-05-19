<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompletionRequest extends Model
{
    protected $fillable = [
        'user_id',
        'item_type',
        'item_id',
        'description',
        'evidence_path',
        'status',
        'admin_response',
        'admin_evidence_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
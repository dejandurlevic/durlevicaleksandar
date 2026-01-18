<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'plan',
        'message',
        'approved',
        'approved_at',
        'invite_token',
        'invite_token_expires_at',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'datetime',
        'invite_token_expires_at' => 'datetime',
    ];
}

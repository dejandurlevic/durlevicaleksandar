<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Inquiry extends Model
{
    use HasFactory;

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

    public function generateInviteToken(): string
    {
        $token = Str::random(64);
        $this->update([
            'invite_token' => $token,
            'invite_token_expires_at' => now()->addDays(7),
        ]);
        return $token;
    }

    public function isInviteTokenValid(): bool
    {
        return $this->invite_token 
            && $this->invite_token_expires_at 
            && $this->invite_token_expires_at->isFuture();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'agent_id',
        'status',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function end()
    {
        $this->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);
    }

    public function canAccess($user = null)
    {
        // If no user is provided, check session ID for guests
        if (!$user) {
            return $this->user_id === session()->getId();
        }

        // Admin can access all chats
        if ($user->isAdmin()) {
            return true;
        }

        // Agent can access their assigned chats
        if ($user->isAgent()) {
            return $this->agent_id === $user->id;
        }

        // User can access their own chats
        return $this->user_id === $user->id || $this->user_id === session()->getId();
    }
}

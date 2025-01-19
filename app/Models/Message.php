<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'content',
        'type',
        'file_path',
        'sender_type',
        'read'
    ];

    protected $with = ['user'];

    protected $appends = ['is_sender', 'formatted_time', 'sender_type'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSenderTypeAttribute()
    {
        if (!$this->user) {
            return null;
        }
        return in_array($this->user->role, ['admin', 'agent']) ? 'agent' : 'user';
    }

    /**
     * Get whether the current user is the sender of this message
     */
    public function getIsSenderAttribute()
    {
        if (auth()->check()) {
            return $this->user_id === auth()->id();
        }
        return $this->user_id === session()->getId();
    }

    /**
     * Get the formatted time for this message
     */
    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }
}

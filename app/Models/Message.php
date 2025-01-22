<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'content',
        'user_id',
        'type',
        'file_path'
    ];

    protected $with = ['user'];

    protected $appends = ['is_sender', 'formatted_time', 'sender_type'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
        if (Auth::check()) {
            return $this->user_id === Auth::id();
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

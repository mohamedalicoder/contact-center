<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contact_id',
        'agent_id',
        'status',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
 // Define the inverse relationship (i.e., each chat belongs to a user)
 public function user()
 {
     return $this->belongsTo(User::class);
 }

}

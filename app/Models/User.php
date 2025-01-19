<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin, agent, user
        'status', // active, inactive
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAgent()
    {
        return $this->role === 'agent';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Get the calls handled by the user.
     */
    public function calls()
    {
        return $this->hasMany(Call::class, 'agent_id');
    }

    /**
     * Get the tickets handled by the user.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    /**
     * Get the queue items assigned to this user.
     */
    public function queueItems()
    {
        return $this->hasMany(QueueItem::class, 'agent_id');
    }

    /**
     * Get chats where user is the customer
     */
    public function chats()
    {
        return $this->hasMany(Chat::class, 'user_id');
    }

    /**
     * Get chats where user is the agent
     */
    public function agentChats()
    {
        return $this->hasMany(Chat::class, 'agent_id');
    }

    /**
     * Get active chats where user is the agent
     */
    public function activeAgentChats()
    {
        return $this->agentChats()->where('status', 'active');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}

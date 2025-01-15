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
        'role',
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
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    public static function getRoles()
    {
        return ['admin', 'supervisor', 'agent'];
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
     * Get chats where user is the admin/agent
     */
    public function adminChats()
    {
        return $this->hasMany(Chat::class, 'admin_id');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
}

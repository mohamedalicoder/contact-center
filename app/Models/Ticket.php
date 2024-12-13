<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Contact;
use App\Models\User;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contact_id',
        'user_id',
        'assigned_to',
        'subject',
        'description',
        'status',
        'priority',
        'due_date',
        'resolution',
        'resolved_at'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public static function getStatuses()
    {
        return ['open', 'in_progress', 'closed'];
    }

    public static function getPriorities()
    {
        return ['low', 'medium', 'high'];
    }
}

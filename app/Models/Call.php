<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Contact;
use App\Models\User;

class Call extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'contact_id',
        'user_id',
        'type',
        'duration',
        'status',
        'notes',
        'started_at',
        'ended_at'
    ];

    protected $casts = [
        'duration' => 'integer',
        'started_at' => 'datetime',
        'ended_at' => 'datetime'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getTypes()
    {
        return ['inbound', 'outbound'];
    }

    public static function getStatuses()
    {
        return ['completed', 'missed', 'voicemail'];
    }
}

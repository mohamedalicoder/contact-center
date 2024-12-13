<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueueItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_id',
        'contact_id',
        'agent_id',
        'type',
        'status',
        'priority',
        'metadata',
        'entered_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'entered_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function getWaitTime()
    {
        if ($this->started_at) {
            return $this->started_at->diffInSeconds($this->entered_at);
        }
        return now()->diffInSeconds($this->entered_at);
    }

    public function getHandleTime()
    {
        if ($this->completed_at && $this->started_at) {
            return $this->completed_at->diffInSeconds($this->started_at);
        }
        return null;
    }

    public function assignToAgent(User $agent)
    {
        $this->update([
            'agent_id' => $agent->id,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function abandon()
    {
        $this->update([
            'status' => 'abandoned',
            'completed_at' => now(),
        ]);
    }
}

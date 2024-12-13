<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'priority',
        'max_size',
        'wait_time_threshold',
        'is_active',
        'business_hours',
    ];

    protected $casts = [
        'business_hours' => 'array',
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(QueueItem::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'queue_skills')
            ->withPivot('minimum_proficiency')
            ->withTimestamps();
    }

    public function activeItems()
    {
        return $this->items()->whereIn('status', ['waiting', 'in_progress']);
    }

    public function getEstimatedWaitTime()
    {
        $avgHandleTime = $this->items()
            ->where('completed_at', '>=', now()->subHours(24))
            ->whereNotNull('started_at')
            ->whereNotNull('completed_at')
            ->avg(DB::raw('TIMESTAMPDIFF(SECOND, started_at, completed_at)'));

        $activeAgents = User::whereHas('queueItems', function ($query) {
            $query->where('status', 'in_progress');
        })->count();

        $waitingItems = $this->items()->where('status', 'waiting')->count();

        if ($activeAgents === 0 || !$avgHandleTime) {
            return null;
        }

        return ($waitingItems * $avgHandleTime) / $activeAgents;
    }

    public function isWithinBusinessHours()
    {
        if (!$this->business_hours) {
            return true;
        }

        $now = now();
        $dayOfWeek = strtolower($now->format('l'));
        $currentTime = $now->format('H:i');

        if (!isset($this->business_hours[$dayOfWeek])) {
            return false;
        }

        $hours = $this->business_hours[$dayOfWeek];
        return $currentTime >= $hours['start'] && $currentTime <= $hours['end'];
    }
}

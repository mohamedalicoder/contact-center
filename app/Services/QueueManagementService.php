<?php

namespace App\Services;

use App\Models\Queue;
use App\Models\QueueItem;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;

class QueueManagementService
{
    public function addToQueue(Contact $contact, $type, Queue $queue, $priority = 1, $metadata = [])
    {
        if (!$queue->is_active || !$queue->isWithinBusinessHours()) {
            throw new \Exception('Queue is not currently active');
        }

        if ($queue->max_size && $queue->activeItems()->count() >= $queue->max_size) {
            throw new \Exception('Queue is at maximum capacity');
        }

        return QueueItem::create([
            'queue_id' => $queue->id,
            'contact_id' => $contact->id,
            'type' => $type,
            'status' => 'waiting',
            'priority' => $priority,
            'metadata' => $metadata,
            'entered_at' => now(),
        ]);
    }

    public function findBestAgent(QueueItem $queueItem)
    {
        $queue = $queueItem->queue;
        $requiredSkills = $queue->skills;

        return User::query()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'agent');
            })
            ->where('is_online', true)
            ->where('is_available', true)
            ->whereDoesntHave('queueItems', function ($query) {
                $query->where('status', 'in_progress');
            })
            ->when($requiredSkills->isNotEmpty(), function ($query) use ($requiredSkills) {
                foreach ($requiredSkills as $skill) {
                    $query->whereHas('skills', function ($q) use ($skill) {
                        $q->where('skills.id', $skill->id)
                            ->where('proficiency_level', '>=', $skill->pivot->minimum_proficiency);
                    });
                }
            })
            ->orderBy('last_activity_at', 'asc')
            ->first();
    }

    public function processNextItem(Queue $queue)
    {
        $nextItem = $queue->items()
            ->where('status', 'waiting')
            ->orderBy('priority', 'desc')
            ->orderBy('entered_at', 'asc')
            ->first();

        if (!$nextItem) {
            return null;
        }

        $agent = $this->findBestAgent($nextItem);
        
        if (!$agent) {
            return null;
        }

        $nextItem->assignToAgent($agent);
        
        return $nextItem;
    }

    public function monitorQueueHealth(Queue $queue)
    {
        $metrics = [
            'total_waiting' => $queue->items()->where('status', 'waiting')->count(),
            'total_in_progress' => $queue->items()->where('status', 'in_progress')->count(),
            'avg_wait_time' => $queue->items()
                ->whereNotNull('started_at')
                ->where('entered_at', '>=', now()->subDay())
                ->avg(DB::raw('TIMESTAMPDIFF(SECOND, entered_at, started_at)')),
            'avg_handle_time' => $queue->items()
                ->whereNotNull('completed_at')
                ->where('completed_at', '>=', now()->subDay())
                ->avg(DB::raw('TIMESTAMPDIFF(SECOND, started_at, completed_at)')),
            'abandonment_rate' => $this->calculateAbandonmentRate($queue),
            'service_level' => $this->calculateServiceLevel($queue),
        ];

        // Log metrics or trigger alerts if thresholds are exceeded
        $this->checkThresholds($queue, $metrics);

        return $metrics;
    }

    private function calculateAbandonmentRate(Queue $queue)
    {
        $total = $queue->items()
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($total === 0) {
            return 0;
        }

        $abandoned = $queue->items()
            ->where('status', 'abandoned')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        return ($abandoned / $total) * 100;
    }

    private function calculateServiceLevel(Queue $queue)
    {
        $total = $queue->items()
            ->whereNotNull('started_at')
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($total === 0) {
            return 100;
        }

        $withinThreshold = $queue->items()
            ->whereNotNull('started_at')
            ->where('created_at', '>=', now()->subDay())
            ->whereRaw('TIMESTAMPDIFF(SECOND, entered_at, started_at) <= ?', [$queue->wait_time_threshold])
            ->count();

        return ($withinThreshold / $total) * 100;
    }

    private function checkThresholds(Queue $queue, array $metrics)
    {
        // Example threshold checks
        if ($metrics['avg_wait_time'] > $queue->wait_time_threshold) {
            // Trigger alert or notification
        }

        if ($metrics['abandonment_rate'] > 20) {
            // Trigger alert or notification
        }

        if ($metrics['service_level'] < 80) {
            // Trigger alert or notification
        }
    }
}

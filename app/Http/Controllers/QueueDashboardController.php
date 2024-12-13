<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\QueueItem;
use App\Models\User;
use App\Services\QueueManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueDashboardController extends Controller
{
    protected $queueService;

    public function __construct(QueueManagementService $queueService)
    {
        $this->queueService = $queueService;
    }

    public function index()
    {
        $queues = Queue::withCount(['items as waiting_count' => function ($query) {
            $query->where('status', 'waiting');
        }, 'items as in_progress_count' => function ($query) {
            $query->where('status', 'in_progress');
        }])->get();

        $queueMetrics = $queues->map(function ($queue) {
            return [
                'id' => $queue->id,
                'name' => $queue->name,
                'metrics' => $this->queueService->monitorQueueHealth($queue),
                'waiting_count' => $queue->waiting_count,
                'in_progress_count' => $queue->in_progress_count,
                'is_active' => $queue->is_active,
                'within_hours' => $queue->isWithinBusinessHours(),
            ];
        });

        $agentMetrics = $this->getAgentMetrics();
        $hourlyStats = $this->getHourlyStats();
        $dailyStats = $this->getDailyStats();

        return view('queue.dashboard', compact(
            'queueMetrics',
            'agentMetrics',
            'hourlyStats',
            'dailyStats'
        ));
    }

    protected function getAgentMetrics()
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        })->withCount(['queueItems as handled_today' => function ($query) {
            $query->where('status', 'completed')
                ->whereDate('completed_at', today());
        }])->get()->map(function ($agent) {
            $currentItem = $agent->queueItems()
                ->where('status', 'in_progress')
                ->first();

            return [
                'id' => $agent->id,
                'name' => $agent->name,
                'status' => $agent->is_online ? ($agent->is_available ? 'Available' : 'Busy') : 'Offline',
                'handled_today' => $agent->handled_today,
                'current_interaction' => $currentItem ? [
                    'type' => $currentItem->type,
                    'duration' => $currentItem->started_at->diffInMinutes(now()),
                    'contact' => $currentItem->contact->name,
                ] : null,
                'avg_handle_time' => $this->calculateAgentAverageHandleTime($agent),
            ];
        });
    }

    protected function calculateAgentAverageHandleTime(User $agent)
    {
        return $agent->queueItems()
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->whereNotNull('started_at')
            ->avg(DB::raw('TIMESTAMPDIFF(SECOND, started_at, completed_at)'));
    }

    protected function getHourlyStats()
    {
        $stats = QueueItem::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
            DB::raw('SUM(CASE WHEN status = "abandoned" THEN 1 ELSE 0 END) as abandoned'),
            DB::raw('AVG(TIMESTAMPDIFF(SECOND, entered_at, IFNULL(started_at, NOW()))) as avg_wait_time')
        )
            ->whereDate('created_at', today())
            ->groupBy('hour')
            ->get();

        $hours = range(0, 23);
        return collect($hours)->map(function ($hour) use ($stats) {
            $hourStats = $stats->firstWhere('hour', $hour) ?? null;
            return [
                'hour' => sprintf('%02d:00', $hour),
                'total' => $hourStats->total ?? 0,
                'completed' => $hourStats->completed ?? 0,
                'abandoned' => $hourStats->abandoned ?? 0,
                'avg_wait_time' => round($hourStats->avg_wait_time ?? 0),
            ];
        });
    }

    protected function getDailyStats()
    {
        return QueueItem::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed'),
            DB::raw('SUM(CASE WHEN status = "abandoned" THEN 1 ELSE 0 END) as abandoned'),
            DB::raw('AVG(TIMESTAMPDIFF(SECOND, entered_at, IFNULL(started_at, NOW()))) as avg_wait_time')
        )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->get()
            ->map(function ($stat) {
                return [
                    'date' => $stat->date,
                    'total' => $stat->total,
                    'completed' => $stat->completed,
                    'abandoned' => $stat->abandoned,
                    'avg_wait_time' => round($stat->avg_wait_time),
                ];
            });
    }

    public function queueDetails(Queue $queue)
    {
        $waitingItems = $queue->items()
            ->with(['contact', 'agent'])
            ->where('status', 'waiting')
            ->orderBy('priority', 'desc')
            ->orderBy('entered_at', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'contact' => $item->contact->name,
                    'type' => $item->type,
                    'priority' => $item->priority,
                    'wait_time' => $item->getWaitTime(),
                    'entered_at' => $item->entered_at->format('H:i:s'),
                ];
            });

        $activeItems = $queue->items()
            ->with(['contact', 'agent'])
            ->where('status', 'in_progress')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'contact' => $item->contact->name,
                    'agent' => $item->agent->name,
                    'type' => $item->type,
                    'duration' => $item->started_at->diffInMinutes(now()),
                    'started_at' => $item->started_at->format('H:i:s'),
                ];
            });

        return response()->json([
            'waiting' => $waitingItems,
            'active' => $activeItems,
        ]);
    }
}

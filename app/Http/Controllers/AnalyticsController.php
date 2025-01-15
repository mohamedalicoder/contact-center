<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Call;
use App\Models\Ticket;
use App\Models\User;

class AnalyticsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth']);
    //     $this->middleware(['permission:view analytics'])->only(['index']);
    //     $this->middleware(['permission:export analytics'])->only(['export']);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index()
    
    {
        // Get date range
        $startDate = request('start_date', now()->subDays(30));
        $endDate = request('end_date', now());

        // Fetch call analytics
        $callAnalytics = [
            'total_calls' => Call::whereBetween('created_at', [$startDate, $endDate])->count(),
            'average_duration' => Call::whereBetween('created_at', [$startDate, $endDate])
                ->avg('duration'),
            'calls_by_status' => Call::whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('status')
                ->selectRaw('status, count(*) as count')
                ->pluck('count', 'status'),
        ];

        // Fetch ticket analytics
        $ticketAnalytics = [
            'total_tickets' => Ticket::whereBetween('created_at', [$startDate, $endDate])->count(),
            'open_tickets' => Ticket::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'open')
                ->count(),
            'average_resolution_time' => Ticket::whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('resolved_at')
                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_time')
                ->value('avg_time'),
            'tickets_by_priority' => Ticket::whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('priority')
                ->selectRaw('priority, count(*) as count')
                ->pluck('count', 'priority'),
        ];

        // Agent performance metrics - get all users who have calls or tickets
        $agentPerformance = User::select(['users.*'])
            ->selectRaw('(SELECT COUNT(*) FROM calls WHERE users.id = calls.user_id AND created_at BETWEEN ? AND ? AND calls.deleted_at IS NULL) as calls_count', [$startDate, $endDate])
            ->selectRaw('(SELECT AVG(calls.duration) FROM calls WHERE users.id = calls.user_id AND created_at BETWEEN ? AND ? AND calls.deleted_at IS NULL) as calls_avg_duration', [$startDate, $endDate])
            ->selectRaw('(SELECT COUNT(*) FROM tickets WHERE users.id = tickets.assigned_to AND created_at BETWEEN ? AND ? AND tickets.deleted_at IS NULL) as tickets_count', [$startDate, $endDate])
            ->where(function($query) {
                $query->whereExists(function($subquery) {
                    $subquery->select('*')
                        ->from('calls')
                        ->whereRaw('users.id = calls.user_id')
                        ->whereNull('calls.deleted_at');
                })
                ->orWhereExists(function($subquery) {
                    $subquery->select('*')
                        ->from('tickets')
                        ->whereRaw('users.id = tickets.assigned_to')
                        ->whereNull('tickets.deleted_at');
                });
            })
            ->whereNull('users.deleted_at')
            ->get();

        return view('analytics.index', compact(
            'callAnalytics',
            'ticketAnalytics',
            'agentPerformance',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

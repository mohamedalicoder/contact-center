<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Call;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Contact;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get last 7 days
        $lastSevenDays = collect(range(6, 0))->map(function ($days) {
            return Carbon::today()->subDays($days)->format('Y-m-d');
        })->values();

        // Get inbound calls data for last 7 days
        $inboundCallsData = $lastSevenDays->map(function ($date) {
            return Call::where('type', 'inbound')
                ->whereDate('created_at', $date)
                ->count();
        })->values();

        // Get outbound calls data for last 7 days
        $outboundCallsData = $lastSevenDays->map(function ($date) {
            return Call::where('type', 'outbound')
                ->whereDate('created_at', $date)
                ->count();
        })->values();

        // Get tickets status data for pie chart
        $ticketsStatusData = [
            Ticket::where('status', 'open')->count(),
            Ticket::where('status', 'pending')->count(),
            Ticket::where('status', 'closed')->count(),
        ];

        // Get statistics
        $stats = [
            'active_tickets' => Ticket::where('status', '!=', 'closed')->count(),
            'total_calls_today' => Call::whereDate('created_at', today())->count(),
            'inbound_calls_today' => Call::where('type', 'inbound')->whereDate('created_at', today())->count(),
            'outbound_calls_today' => Call::where('type', 'outbound')->whereDate('created_at', today())->count(),
            'active_employees' => User::where('status', 'active')->count(),
            'closed_tickets' => Ticket::where('status', 'closed')->count(),
        ];

        // Get recent activities
        $recentCalls = Call::with(['contact', 'user'])->latest()->take(5)->get();
        $recentTickets = Ticket::with(['contact', 'assignedTo'])->latest()->take(5)->get();

        return view('dashboard', compact(
            'stats',
            'lastSevenDays',
            'inboundCallsData',
            'outboundCallsData',
            'ticketsStatusData',
            'recentCalls',
            'recentTickets'
        ));
    }
}

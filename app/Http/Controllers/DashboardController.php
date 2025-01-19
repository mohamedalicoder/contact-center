<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get today's date range
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Get last 7 days
        $lastSevenDays = collect(range(6, 0))->map(function ($days) {
            return Carbon::today()->subDays($days)->format('Y-m-d');
        })->values();

        // Get active chats data for last 7 days
        $activeChatsData = $lastSevenDays->map(function ($date) {
            return Chat::where('status', 'active')
                ->whereDate('created_at', $date)
                ->count();
        })->values();

        // Get ended chats data for last 7 days
        $endedChatsData = $lastSevenDays->map(function ($date) {
            return Chat::where('status', 'ended')
                ->whereDate('created_at', $date)
                ->count();
        })->values();

        // Get active agents count
        $activeAgents = User::whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        })->where('status', 'active')->count();

        // Get today's chat statistics
        $todayStats = [
            'total_chats' => Chat::whereDate('created_at', $today)->count(),
            'active_chats' => Chat::where('status', 'active')->count(),
            'ended_chats' => Chat::where('status', 'ended')->whereDate('ended_at', $today)->count(),
        ];

        // Get overall statistics
        $stats = [
            'active_chats' => Chat::where('status', 'active')->count(),
            'total_chats_today' => $todayStats['total_chats'],
            'active_employees' => $activeAgents,
            'ended_chats' => Chat::where('status', 'ended')->count(),
        ];

        // Get tickets status data for pie chart
        $chatStatusData = [
            Chat::where('status', 'active')->count(),
            Chat::where('status', 'ended')->count(),
            Chat::where('status', 'pending')->count(),
        ];

        // Get recent chats with their last messages
        $recentChats = Chat::with(['user', 'agent', 'messages' => function($query) {
            $query->latest()->take(1);
        }])
        ->latest()
        ->take(5)
        ->get()
        ->map(function ($chat) {
            $chat->last_message = $chat->messages->first();
            unset($chat->messages);
            return $chat;
        });

        return view('dashboard', compact(
            'stats',
            'lastSevenDays',
            'activeChatsData',
            'endedChatsData',
            'chatStatusData',
            'recentChats',
            'todayStats'
        ));
    }
}

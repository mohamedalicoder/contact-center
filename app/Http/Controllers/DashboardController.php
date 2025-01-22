<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get authenticated user
        $user = auth()->user();

        // Get today's date range
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Get last 7 days
        $lastSevenDays = collect(range(6, 0))->map(function ($days) {
            return Carbon::today()->subDays($days)->format('Y-m-d');
        })->values();

        // Base query for chats based on user role
        $chatQuery = Chat::query();
        if ($user->hasRole('agent')) {
            $chatQuery->where('agent_id', $user->id);
        } elseif ($user->hasRole('user')) {
            $chatQuery->where('user_id', $user->id);
        }

        // Get active chats data for last 7 days
        $activeChatsData = $lastSevenDays->map(function ($date) use ($chatQuery) {
            return (clone $chatQuery)->where('status', 'active')
                ->whereDate('created_at', $date)
                ->count();
        })->values();

        // Get ended chats data for last 7 days
        $endedChatsData = $lastSevenDays->map(function ($date) use ($chatQuery) {
            return (clone $chatQuery)->where('status', 'ended')
                ->whereDate('created_at', $date)
                ->count();
        })->values();

        // Get active agents count
        $activeAgents = User::whereHas('roles', function ($query) {
            $query->where('name', 'agent');
        })->where('is_available', '=', true)->count();

        // Get today's chat statistics
        $todayStats = [
            'total_chats' => (clone $chatQuery)->whereDate('created_at', $today)->count(),
            'active_chats' => (clone $chatQuery)->where('status', 'active')->count(),
            'ended_chats' => (clone $chatQuery)->where('status', 'ended')->whereDate('ended_at', $today)->count(),
        ];

        // Get overall statistics
        $stats = [
            'active_chats' => (clone $chatQuery)->where('status', 'active')->count(),
            'total_chats_today' => $todayStats['total_chats'],
            'active_employees' => $activeAgents,
            'ended_chats' => (clone $chatQuery)->where('status', 'ended')->count(),
        ];

        // Get tickets status data for pie chart
        $chatStatusData = [
            (clone $chatQuery)->where('status', 'active')->count(),
            (clone $chatQuery)->where('status', 'ended')->count(),
            (clone $chatQuery)->where('status', 'pending')->count(),
        ];

        // Get recent chats with their last messages
        $recentChatsQuery = Chat::with(['user', 'agent', 'messages' => function($query) {
            $query->latest()->take(1);
        }]);

        if ($user->hasRole('agent')) {
            $recentChatsQuery->where('agent_id', $user->id);
        } elseif ($user->hasRole('user')) {
            $recentChatsQuery->where('user_id', $user->id);
        }

        $recentChats = $recentChatsQuery->latest()
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
            'todayStats',
            'activeAgents'
        ));
    }
}

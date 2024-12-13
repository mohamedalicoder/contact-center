<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Ticket;
use App\Models\Contact;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_calls' => Call::count(),
            'today_calls' => Call::whereDate('created_at', today())->count(),
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'total_contacts' => Contact::count(),
            'recent_calls' => Call::with('contact')->latest()->take(5)->get(),
            'recent_tickets' => Ticket::with(['contact', 'assignedTo'])->latest()->take(5)->get(),
        ];

        return view('dashboard', compact('stats'));
    }
}

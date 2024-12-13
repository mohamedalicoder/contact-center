<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Call;
use App\Models\Contact;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        // Get some statistics for the landing page
        $stats = [
            'total_calls' => Call::count(),
            'total_contacts' => Contact::count(),
            'total_agents' => User::count(),
            'recent_calls' => Call::with('contact')->latest()->take(5)->get()
        ];

        return view('landing', compact('stats'));
    }
}

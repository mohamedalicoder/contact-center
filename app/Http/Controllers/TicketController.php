<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Contact;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['contact', 'assignedTo', 'user']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $tickets = $query->latest()->paginate(10);
        $users = User::all();

        return view('tickets.index', compact('tickets', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $contacts = Contact::all();
        $users = User::all();
        $priorities = ['low', 'medium', 'high'];
        return view('tickets.create', compact('contacts', 'users', 'priorities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,in_progress,closed',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'resolution' => 'nullable|string'
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to create a ticket.');
        }

        $validated['user_id'] = Auth::id();

        Ticket::create($validated);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['contact', 'assignedTo', 'user']);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $contacts = Contact::all();
        $users = User::all();
        $priorities = ['low', 'medium', 'high'];
        return view('tickets.edit', compact('ticket', 'contacts', 'users', 'priorities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,in_progress,closed',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'resolution' => 'nullable|string'
        ]);

        // Set resolved_at timestamp when ticket is closed
        if ($validated['status'] === 'closed' && $ticket->status !== 'closed') {
            $validated['resolved_at'] = now();
        }

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }
}

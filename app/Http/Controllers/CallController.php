<?php
namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CallController extends Controller
{
//     public function __construct()
// {
//     $this->middleware(['auth', 'role:admin,supervisor,agent']);
// }
    public function index(Request $request)
    {
        $query = Call::with(['contact', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->filled('contact_id')) {
            $query->where('contact_id', $request->contact_id);
        }

        $calls = $query->latest()->paginate(10);
        return view('calls.index', [
            'calls' => $calls,
            'types' => Call::getTypes(),
            'statuses' => Call::getStatuses(),
        ]);
    }

    public function create()
    {
        $contacts = Contact::select('id', 'name')->get();
        $types = Call::getTypes();
        $statuses = Call::getStatuses();
        return view('calls.create', compact('contacts', 'types', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'type' => ['required', 'string', 'in:' . implode(',', Call::getTypes())],
            'status' => ['required', 'string', 'in:' . implode(',', Call::getStatuses())],
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
            'notes' => 'nullable|string'
        ]);

        $call = new Call($validated);
        $call->user_id = Auth::id();
        
        // Calculate duration in seconds
        $started = new \DateTime($validated['started_at']);
        $ended = new \DateTime($validated['ended_at']);
        $call->duration = $ended->getTimestamp() - $started->getTimestamp();
        
        $call->save();

        return redirect()->route('calls.index')
            ->with('success', 'Call logged successfully.');
    }

    public function show(Call $call)
    {
        return view('calls.show', compact('call'));
    }

    public function edit(Call $call)
    {
        $contacts = Contact::select('id', 'name')->get();
        return view('calls.edit', compact('call', 'contacts'));
    }

    public function update(Request $request, Call $call)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'type' => 'required|in:' . join(',', Call::getTypes()),
            'status' => 'required|in:' . join(',', Call::getStatuses()),
            'notes' => 'nullable|string',
            'started_at' => 'required|date',
            'ended_at' => 'required|date|after:started_at',
        ]);

        try {
            if (Auth::check()) {
                $validated['user_id'] = Auth::user()->id;
            } else {
                return redirect()->back()->withErrors('User must be authenticated to update a call record.');
            }
            $validated['duration'] = strtotime($validated['ended_at']) - strtotime($validated['started_at']);
            $call->update($validated);

            return redirect()->route('calls.index')
                ->with('success', 'Call record updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Failed to update the call record. Please try again.');
        }
    }

    public function destroy(Call $call)
    {
        try {
            $call->delete();
            return redirect()->route('calls.index')
                ->with('success', 'Call record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Failed to delete the call record. Please try again.');
        }
    }
}

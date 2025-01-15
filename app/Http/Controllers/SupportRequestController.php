<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\Request;

class SupportRequestController extends Controller
{
    public function index()
    {
        $requests = SupportRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('support-requests.index', compact('requests'));
    }

    public function create()
    {
        return view('support-requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'call_id' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'open';

        SupportRequest::create($validated);

        return redirect()->route('support-requests.index')
            ->with('success', 'Support request created successfully.');
    }

    public function show(SupportRequest $supportRequest)
    {
        return view('support-requests.show', ['request' => $supportRequest]);
    }

    public function edit(SupportRequest $supportRequest)
    {
        return view('support-requests.edit', ['request' => $supportRequest]);
    }

    public function update(Request $request, SupportRequest $supportRequest)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'call_id' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($validated['status'] === 'resolved' && $supportRequest->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }

        $supportRequest->update($validated);

        return redirect()->route('support-requests.show', $supportRequest)
            ->with('success', 'Support request updated successfully.');
    }

    public function destroy(SupportRequest $supportRequest)
    {
        $supportRequest->delete();

        return redirect()->route('support-requests.index')
            ->with('success', 'Support request deleted successfully.');
    }
}

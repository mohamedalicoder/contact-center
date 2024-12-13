<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\QueueItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueueController extends Controller
{
    public function dashboard()
    {
        $queues = Queue::withCount('items')->get();
        $activeItems = QueueItem::where('status', 'active')->count();
        $completedItems = QueueItem::where('status', 'completed')->count();
        
        return view('queue.dashboard', compact('queues', 'activeItems', 'completedItems'));
    }
    
    public function index()
    {
        $queues = Queue::withCount('items')->paginate(10);
        return view('queue.index', compact('queues'));
    }
    
    public function create()
    {
        return view('queue.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|integer|min:1|max:10',
            'status' => 'required|in:active,paused,closed'
        ]);
        
        Queue::create($validated);
        
        return redirect()->route('queues.index')
            ->with('success', 'Queue created successfully.');
    }
    
    public function show(Queue $queue)
    {
        $queue->load(['items' => function($query) {
            $query->latest();
        }]);
        
        return view('queue.show', compact('queue'));
    }
    
    public function edit(Queue $queue)
    {
        return view('queue.edit', compact('queue'));
    }
    
    public function update(Request $request, Queue $queue)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|integer|min:1|max:10',
            'status' => 'required|in:active,paused,closed'
        ]);
        
        $queue->update($validated);
        
        return redirect()->route('queues.index')
            ->with('success', 'Queue updated successfully.');
    }
    
    public function destroy(Queue $queue)
    {
        $queue->delete();
        
        return redirect()->route('queues.index')
            ->with('success', 'Queue deleted successfully.');
    }
    
    public function items(Queue $queue)
    {
        $items = $queue->items()->paginate(15);
        return view('queue.items', compact('queue', 'items'));
    }
    
    public function addItem(Request $request, Queue $queue)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|integer|min:1|max:10',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id'
        ]);
        
        $queue->items()->create($validated);
        
        return redirect()->route('queues.items', $queue)
            ->with('success', 'Item added to queue successfully.');
    }
    
    public function updateItem(Request $request, Queue $queue, QueueItem $item)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|integer|min:1|max:10',
            'status' => 'required|in:pending,active,completed,cancelled',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id'
        ]);
        
        $item->update($validated);
        
        return redirect()->route('queues.items', $queue)
            ->with('success', 'Queue item updated successfully.');
    }
    
    public function removeItem(Queue $queue, QueueItem $item)
    {
        $item->delete();
        
        return redirect()->route('queues.items', $queue)
            ->with('success', 'Item removed from queue successfully.');
    }
    
    public function analytics()
    {
        $queues = Queue::withCount(['items as total_items',
            'items as active_items' => function($query) {
                $query->where('status', 'active');
            },
            'items as completed_items' => function($query) {
                $query->where('status', 'completed');
            }
        ])->get();
        
        return view('queue.analytics', compact('queues'));
    }
}

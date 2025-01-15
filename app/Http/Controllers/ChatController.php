<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin' || $user->role === 'agent') {
            $activeChats = Chat::with(['user', 'admin', 'messages' => function($query) {
                $query->latest()->take(1);
            }])
            ->where('admin_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->get();
        } else {
            $activeChats = Chat::with(['user', 'admin', 'messages' => function($query) {
                $query->latest()->take(1);
            }])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
        }

        return view('chat.index', compact('activeChats'));
    }

    public function create()
    {
        // Check if user already has an active chat
        $existingChat = Chat::where('user_id', auth()->id())
            ->where('status', 'active')
            ->first();

        if ($existingChat) {
            return redirect()->route('chat.show', $existingChat);
        }

        return view('chat.create');
    }

    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            if (!$user) {
                throw new \Exception('You must be logged in to start a chat.');
            }

            // Check if user already has an active chat
            $existingChat = Chat::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if ($existingChat) {
                if (request()->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'redirect' => route('chat.show', $existingChat)
                    ]);
                }
                return redirect()->route('chat.show', $existingChat);
            }

            $validated = $request->validate([
                'message' => 'required|string|min:1|max:1000',
            ]);

            DB::beginTransaction();

            // Find available admin/agent with least active chats
            $admin = User::whereIn('role', ['admin', 'agent'])
                ->where('status', 'active')
                ->withCount(['adminChats' => function($query) {
                    $query->where('status', 'active');
                }])
                ->orderBy('admin_chats_count', 'asc')
                ->first();

            if (!$admin) {
                throw new \Exception('No agents are available at the moment. Please try again later.');
            }

            $chat = Chat::create([
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'status' => 'active',
                'started_at' => now(),
            ]);

            $message = Message::create([
                'chat_id' => $chat->id,
                'user_id' => $user->id,
                'content' => $validated['message'],
                'is_read' => false,
            ]);

            // Send welcome message from admin
            Message::create([
                'chat_id' => $chat->id,
                'user_id' => $admin->id,
                'content' => "Hello! I'm {$admin->name}. How can I assist you today?",
                'is_read' => false,
            ]);

            broadcast(new MessageSent($message))->toOthers();

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('chat.show', $chat)
                ]);
            }

            return redirect()->route('chat.show', $chat);

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(Chat $chat)
    {
        // Ensure the user has access to this chat
        if ($chat->user_id !== auth()->id() && $chat->admin_id !== auth()->id()) {
            abort(403);
        }

        $chat->load(['messages.user', 'admin', 'user']);
        
        if (request()->wantsJson()) {
            return response()->json([
                'messages' => $chat->messages,
            ]);
        }

        // If request is from iframe, use embed view
        if (request()->header('Sec-Fetch-Dest') === 'iframe') {
            return view('chat.embed', compact('chat'));
        }

        return view('chat.show', compact('chat'));
    }

    /**
     * Show the chat in embedded view
     */
    public function showEmbed(Chat $chat)
    {
        // Ensure the user has access to this chat
        if ($chat->user_id !== auth()->id() && $chat->admin_id !== auth()->id()) {
            abort(403);
        }

        $chat->load(['messages.user', 'admin', 'user']);
        
        return view('chat.embed', compact('chat'));
    }

    public function sendMessage(Request $request, Chat $chat)
    {
        // Ensure the user has access to this chat
        if ($chat->user_id !== auth()->id() && $chat->admin_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Check if chat is active
        if ($chat->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This chat has ended.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            $data = [
                'chat_id' => $chat->id,
                'user_id' => auth()->id(),
                'type' => 'text'
            ];

            if ($request->hasFile('audio')) {
                $file = $request->file('audio');
                $path = $file->store('chat-audio', 'public');
                $data['type'] = 'audio';
                $data['file_path'] = $path;
                $data['content'] = 'Audio message';
            } else {
                $request->validate([
                    'message' => 'required|string'
                ]);
                $data['content'] = $request->input('message');
            }

            $message = Message::create($data);
            $message->load('user');

            broadcast(new MessageSent($message))->toOthers();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error sending message: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message. Please try again.'
            ], 500);
        }
    }

    public function endChat(Chat $chat)
    {
        if ($chat->admin_id !== auth()->id()) {
            abort(403);
        }

        $chat->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        return redirect()->route('chat.index')
            ->with('success', 'Chat ended successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Events\NewChatStarted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ChatController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');

    // }

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $chats = Chat::with(['user', 'agent', 'messages' => function($query) {
                $query->latest();
            }])
            ->when($user->isAgent(), function($query) use ($user) {
                return $query->where('agent_id', $user->id);
            })
            ->when($user->isUser(), function($query) use ($user) {
                return $query->where('user_id', $user->id);
            })
            ->orderByDesc(function ($query) {
                $query->select('created_at')
                    ->from('messages')
                    ->whereColumn('chat_id', 'chats.id')
                    ->latest()
                    ->limit(1);
            })
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'chats' => $chats
            ]);
        }

        return view('chat.index', compact('chats'));
    }

    public function indexJson()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $chats = Chat::with(['user', 'messages'])
            ->when($user->isAgent(), function ($query) use ($user) {
                return $query->where('agent_id', $user->id);
            })
            ->latest()
            ->get();

        return response()->json(['chats' => $chats]);
    }

    public function create()
    {
        // For guest users, store session ID
        if (!Auth::check()) {
            session()->put('guest_id', session()->getId());
        }

        // Get user ID (either authenticated user ID or session ID for guests)
        $userId = Auth::id() ?? session()->getId();

        // Get all chats for the user
        $chats = Chat::where('user_id', $userId)
            ->with(['messages' => function($query) {
                $query->latest()->first();
            }, 'agent'])
            ->latest()
            ->get();

        if ($chats->isNotEmpty()) {
            return view('chat.list', compact('chats'));
        }

        return view('chat.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required|string'
            ]);

            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'user'
                ]
            );

            // Find available agent
            $agent = User::where('role', 'agent')
                ->whereDoesntHave('agentChats', function($query) {
                    $query->where('status', 'active');
                })
                ->inRandomOrder()
                ->first();

            if (!$agent) {
                $agent = User::where('role', 'agent')
                    ->withCount(['agentChats' => function($query) {
                        $query->where('status', 'active');
                    }])
                    ->orderBy('agent_chats_count')
                    ->first();
            }

            if (!$agent) {
                return response()->json([
                    'success' => false,
                    'message' => 'No agents available at the moment'
                ], 503);
            }

            DB::beginTransaction();

            // Create chat
            $chat = Chat::create([
                'user_id' => $user->id,
                'agent_id' => $agent->id,
                'status' => 'active',
                'started_at' => now(),
            ]);

            // Create first message
            $message = Message::create([
                'chat_id' => $chat->id,
                'user_id' => $user->id,
                'content' => $validated['message'],
                'type' => 'text'
            ]);

            // Broadcast new chat event
            broadcast(new NewChatStarted($chat))->toOthers();

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'chat' => $chat->load('agent', 'messages'),
                    'message' => 'Chat started successfully'
                ]);
            }

            return redirect()->route('chat.show', $chat->id)
                ->with('success', 'Chat started successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to start chat: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to start chat: ' . $e->getMessage()]);
        }
    }

    public function show(Chat $chat)
    {
        // Check if user is authenticated
        if (auth()->check()) {
            // For authenticated users, check if they are the agent or user of this chat
            if (auth()->id() !== $chat->user_id && auth()->id() !== $chat->agent_id) {
                if (request()->wantsJson()) {
                    return response()->json(['error' => 'Unauthorized'], 403);
                }
                return abort(403);
            }
        } else {
            // For unauthenticated users, store chat ID in session
            session(['current_chat_id' => $chat->id]);
        }

        // Load relationships
        $chat->load(['user', 'agent', 'messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'chat' => $chat
            ]);
        }

        return view('chat.show', compact('chat'));
    }

    public function showJson(Chat $chat)
    {
        // Check access
        if (auth()->check()) {
            if (auth()->id() !== $chat->user_id && auth()->id() !== $chat->agent_id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        } else {
            if (session('current_chat_id') !== $chat->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }

        // Load relationships
        $chat->load(['user', 'agent', 'messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        return response()->json([
            'success' => true,
            'chat' => $chat
        ]);
    }

    public function showEmbed(Chat $chat)
    {
        // Ensure the user has access to this chat
        if (!$chat->canAccess(Auth::user())) {
            abort(403);
        }

        $chat->load(['messages.user', 'agent', 'user']);

        return view('chat.embed', compact('chat'));
    }

    public function sendMessage(Request $request, Chat $chat)
    {
        // Ensure the user has access to this chat
        if (!$chat->canAccess(Auth::user())) {
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

            // Create the message
            $message = Message::create([
                'chat_id' => $chat->id,
                'user_id' => Auth::id(),
                'content' => $request->input('message'),
                'type' => 'text'
            ]);

            // Load relationships
            $message->load('user');

            // Broadcast the message
            broadcast(new MessageSent($chat, $message))->toOthers();

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
        if ($chat->agent_id !== Auth::id()) {
            abort(403);
        }

        $chat->update([
            'status' => 'ended',
            'ended_at' => now(),
        ]);

        return redirect()->route('chat.index')
            ->with('success', 'Chat ended successfully.');
    }

    public function contactForm()
    {
        return view('chat.contact-form');
    }

    public function agent()
    {
        $user = Auth::user();
        // For non-authenticated users, show the chat interface
        if (!$user) {
            return view('chat.contact-form');
        }

        // For agents, show the agent dashboard
        if ($user->isAgent()) {
            return view('chat.agent', [
                'user' => $user
            ]);
        }

        // For regular users, show the chat interface
        return view('chat.agent');
    }

    public function bot()
    {
        return view('chat.bot');
    }

}

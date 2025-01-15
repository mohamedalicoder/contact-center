<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Contact;
use App\Models\User;
use App\Events\NewChatMessage;
use App\Models\Message;

class LiveChatController extends Controller
{
    public function index()
    {
        $activeChats = Chat::where('status', 'active')
            ->where('admin_id', auth()->id())
            ->with(['user', 'messages'])
            ->get();

        return view('chat.index', compact('activeChats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $chat = Chat::create([
            'user_id' => $validated['user_id'],
            'admin_id' => auth()->id(),
            'status' => 'active',
            'started_at' => now(),
        ]);

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => auth()->id(),
            'content' => $validated['message'],
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($chat->load(['messages', 'user', 'admin']));
    }

    public function show(Chat $chat)
    {
        // Ensure the user has access to this chat
        if ($chat->user_id !== auth()->id() && $chat->admin_id !== auth()->id()) {
            abort(403);
        }

        return view('chat.show', compact('chat'));
    }

    public function sendMessage(Request $request, Chat $chat)
    {
        // Ensure the user has access to this chat
        if ($chat->user_id !== auth()->id() && $chat->admin_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string'
        ]);

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => auth()->id(),
            'content' => $validated['message'],
        ]);

        // Load the relationships needed for the broadcast
        $message->load('user');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);
    }

    public function end(Chat $chat)
    {
        $chat->update(['status' => 'closed']);
        return redirect()->route('chat.index')->with('success', 'Chat ended successfully');
    }
}

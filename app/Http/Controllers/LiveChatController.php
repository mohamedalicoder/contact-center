<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Contact;
use App\Models\User;
use App\Events\NewChatMessage;

class LiveChatController extends Controller
{
    public function index()
    {
        $activeChats = Chat::where('status', 'active')
            ->where('agent_id', auth()->id())
            ->with(['contact', 'messages'])
            ->get();

        return view('chat.index', compact('activeChats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'message' => 'required|string'
        ]);

        $chat = Chat::create([
            'contact_id' => $validated['contact_id'],
            'agent_id' => auth()->id(),
            'status' => 'active'
        ]);

        $message = $chat->messages()->create([
            'content' => $validated['message'],
            'sender_type' => 'agent',
            'sender_id' => auth()->id()
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($chat->load('messages'));
    }

    public function show(Chat $chat)
    {
        return view('chat.show', compact('chat'));
    }

    public function sendMessage(Request $request, Chat $chat)
    {
        $validated = $request->validate([
            'message' => 'required|string'
        ]);

        $message = $chat->messages()->create([
            'content' => $validated['message'],
            'sender_type' => 'agent',
            'sender_id' => auth()->id()
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function end(Chat $chat)
    {
        $chat->update(['status' => 'closed']);
        return redirect()->route('chat.index')->with('success', 'Chat ended successfully');
    }
}

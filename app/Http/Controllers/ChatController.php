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
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;
use Illuminate\Validation\ValidationException;

class ChatController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');

    // }

    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $chats = Chat::with(['user', 'agent', 'lastMessage'])
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
                ->get()
                ->map(function($chat) use ($user) {
                    $lastMessage = $chat->lastMessage;
                    return [
                        'id' => $chat->id,
                        'user_name' => $chat->user ? $chat->user->name : 'Unknown User',
                        'last_message' => $lastMessage ? $lastMessage->content : '',
                        'last_message_time' => $lastMessage ? $lastMessage->created_at->diffForHumans() : '',
                        'status' => $chat->status,
                        'unread_messages' => $chat->messages()
                            ->where('read', false)
                            ->when($user->isAgent(), function($query) {
                                return $query->where('user_id', '!=', Auth::id());
                            })
                            ->when($user->isUser(), function($query) use ($user) {
                                return $query->where('user_id', '!=', $user->id);
                            })
                            ->count()
                    ];
                });

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'chats' => $chats
                ]);
            }

            return view('chat.index', compact('chats'));
        } catch (\Exception $e) {
            \Log::error('Error in ChatController@index: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load chats',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred while loading chats'
            ], 500);
        }
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
        $user = Auth::user();

        // Create a new chat
        $chat = new Chat();
        $chat->user_id = $user->id;

        // If user is not an agent, find an available agent
        if (!$user->isAgent()) {
            $agent = User::where('role', 'agent')
                        ->whereDoesntHave('agentChats', function($query) {
                            $query->where('status', 'active');
                        })
                        ->first();

            if ($agent) {
                $chat->agent_id = $agent->id;
            }
        }

        $chat->status = 'active';
        $chat->save();

        // Create initial system message
        Message::create([
            'chat_id' => $chat->id,
            'content' => 'تم بدء المحادثة',
            'type' => 'system'
        ]);

        // Emit event for new chat
        event(new NewChatStarted($chat));

        return redirect()->route('chat.show', $chat);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'message' => 'required|string'
            ]);

            // Create a new user or get existing one
            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'user'
                ]
            );

            // Create the chat
            $chat = Chat::create([
                'user_id' => $user->id,
                'status' => 'open'
            ]);

            // Create the first message
            Message::create([
                'chat_id' => $chat->id,
                'user_id' => $user->id,
                'content' => $validated['message'],
                'type' => 'text'
            ]);

            // Send notification email
            Mail::to(config('mail.admin_address', 'admin@example.com'))->send(
                new ContactFormSubmitted(
                    $validated['name'],
                    $validated['email'],
                    $validated['message']
                )
            );

            // Broadcast new chat event
            broadcast(new NewChatStarted($chat))->toOthers();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'chat' => $chat
                ]);
            }

            return redirect()->route('chat.show', $chat);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in ChatController@store: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to create chat',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred while creating the chat'
            ], 500);
        }
    }

    public function show(Chat $chat)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Check if user has access to this chat
            if ($user->isAgent() && $chat->agent_id !== $user->id) {
                return response()->json(['error' => 'You do not have access to this chat'], 403);
            }

            if ($user->isUser() && $chat->user_id !== $user->id) {
                return response()->json(['error' => 'You do not have access to this chat'], 403);
            }

            // Load relationships
            $chat->load(['messages' => function($query) {
                $query->with('user')->orderBy('created_at', 'asc');
            }, 'user', 'agent']);

            // Mark messages as read
            $chat->messages()
                ->where('read', false)
                ->when($user->isAgent(), function($query) use ($user) {
                    return $query->where('user_id', '!=', $user->id);
                })
                ->when($user->isUser(), function($query) use ($user) {
                    return $query->where('user_id', '!=', $user->id);
                })
                ->update(['read' => true]);

            // Format the response
            $formattedChat = [
                'id' => $chat->id,
                'status' => $chat->status,
                'user_name' => $chat->user ? $chat->user->name : 'Unknown User',
                'agent_name' => $chat->agent ? $chat->agent->name : null,
                'messages' => $chat->messages->map(function($message) {
                    return [
                        'id' => $message->id,
                        'content' => $message->content,
                        'user_id' => $message->user_id,
                        'user_name' => $message->user ? $message->user->name : 'Unknown User',
                        'created_at' => $message->created_at,
                        'is_sender' => $message->user_id === Auth::id()
                    ];
                })
            ];

            return response()->json([
                'success' => true,
                'chat' => $formattedChat
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in ChatController@show: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load chat',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred while loading the chat'
            ], 500);
        }
    }

    public function showJson(Chat $chat)
    {
        // Check access
        if (Auth::check()) {
            if (Auth::id() !== $chat->user_id && Auth::id() !== $chat->agent_id) {
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
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Check if user has access to this chat
            if ($user->isAgent() && $chat->agent_id !== $user->id) {
                return response()->json(['error' => 'You do not have access to this chat'], 403);
            }

            if ($user->isUser() && $chat->user_id !== $user->id) {
                return response()->json(['error' => 'You do not have access to this chat'], 403);
            }

            // Validate request
            $validated = $request->validate([
                'content' => 'required|string|max:1000'
            ]);

            // Check if chat is active
            if ($chat->status !== 'active') {
                return response()->json([
                    'error' => 'This chat has ended'
                ], 403);
            }

            DB::beginTransaction();

            // Create the message
            $message = new Message([
                'chat_id' => $chat->id,
                'user_id' => $user->id,
                'content' => $validated['content'],
                'type' => 'text',
                'read' => false
            ]);

            $message->save();

            // Load the user relationship
            $message->load('user');

            // Format the message response
            $formattedMessage = [
                'id' => $message->id,
                'content' => $message->content,
                'user_id' => $message->user_id,
                'user_name' => $message->user->name,
                'created_at' => $message->created_at,
                'is_sender' => true
            ];

            // Broadcast the message
            broadcast(new MessageSent($chat, $message))->toOthers();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $formattedMessage
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in ChatController@sendMessage: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to send message',
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred while sending the message'
            ], 500);
        }
    }

    /**
     * Store a voice message in the chat.
     *
     * @param Request $request
     * @param Chat $chat
     * @return JsonResponse
     */
    public function storeVoiceMessage(Request $request, Chat $chat)
    {
        try {
            // Log request information
            \Log::info('Voice message request', [
                'user_id' => Auth::check() ? Auth::id() : null,
                'chat_id' => $chat->id,
                'chat_agent_id' => $chat->agent_id,
                'chat_customer_id' => $chat->customer_id,
                'chat_status' => $chat->status
            ]);

            // Basic authentication check
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'يجب تسجيل الدخول أولاً.'
                ], 401);
            }

            // Check if chat is active
            if ($chat->status !== 'active') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'لا يمكن إرسال رسائل في محادثة منتهية.'
                ], 400);
            }

            $validated = $request->validate([
                'audio' => [
                    'required',
                    'file',
                    'mimes:webm,mp3,wav',
                    'max:10240', // 10MB max
                    function ($attribute, $value, $fail) {
                        if (!$value->isValid()) {
                            $fail('الملف الصوتي تالف أو غير صالح.');
                        }
                    },
                ],
                'type' => 'required|in:voice'
            ], [
                'audio.required' => 'الرجاء تحديد ملف صوتي.',
                'audio.file' => 'يجب أن يكون الملف المرفق ملفًا صوتيًا صالحًا.',
                'audio.mimes' => 'يجب أن يكون الملف بصيغة webm أو mp3 أو wav.',
                'audio.max' => 'حجم الملف الصوتي يجب أن لا يتجاوز 10 ميجابايت.',
                'type.required' => 'نوع الرسالة مطلوب.',
                'type.in' => 'نوع الرسالة غير صالح.'
            ]);

            DB::beginTransaction();

            // Store the audio file
            $path = $request->file('audio')->store('voice-messages/' . date('Y/m'), 'public');

            // Create message record
            $message = Message::create([
                'chat_id' => $chat->id,
                'user_id' => Auth::check() ? Auth::id() : null,
                'content' => asset('storage/' . $path),
                'type' => 'voice',
                'metadata' => [
                    'original_name' => $request->file('audio')->getClientOriginalName(),
                    'mime_type' => $request->file('audio')->getMimeType(),
                    'size' => $request->file('audio')->getSize(),
                ]
            ]);

            // Update chat last activity
            $chat->update([
                'last_activity' => now(),
                'last_message_type' => 'voice'
            ]);

            DB::commit();

            // Broadcast the message
            broadcast(new MessageSent($chat, $message))->toOthers();

            return response()->json([
                'status' => 'success',
                'message' => 'تم إرسال الرسالة الصوتية بنجاح',
                'data' => $message
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Voice message error: ' . $e->getMessage(), [
                'user_id' => Auth::check() ? Auth::id() : null,
                'chat_id' => $chat->id ?? null,
                'file' => $request->hasFile('audio') ? $request->file('audio')->getClientOriginalName() : null
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إرسال الرسالة الصوتية. الرجاء المحاولة مرة أخرى.'
            ], 500);
        }
    }

    public function endChat(Chat $chat)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Check if user has access to this chat
            if ($user->isAgent() && $chat->agent_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this chat'
                ], 403);
            }

            $chat->update([
                'status' => 'ended',
                'ended_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Chat ended successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in ChatController@endChat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to end chat'
            ], 500);
        }
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

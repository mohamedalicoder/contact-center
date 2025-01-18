<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        window.pusherKey = '{{ config('broadcasting.connections.pusher.key') }}';
        window.pusherCluster = '{{ config('broadcasting.connections.pusher.options.cluster') }}';
        window.currentChatId = '{{ $chat->exists ? $chat->id : '' }}';
    </script>
    <script src="{{ asset('js/chat.js') }}"></script>
</head>
<body class="bg-gray-100 h-full">
    <div class="h-full flex flex-col">
        <!-- Chat Header -->
        <div class="p-4 bg-white border-b flex justify-between items-center">
            <div>
                @if($chat->exists)
                    <h3 class="font-medium">
                        @if($chat->agent)
                            Chat with {{ $chat->agent->name }}
                        @else
                            Chat #{{ $chat->id }}
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500">
                        Status: <span class="@if($chat->status === 'active') text-green-500 @else text-gray-500 @endif">
                            {{ ucfirst($chat->status) }}
                        </span>
                    </p>
                @else
                    <h3 class="font-medium">Chat Not Found</h3>
                    <p class="text-sm text-gray-500">This chat does not exist</p>
                @endif
            </div>
            <a href="{{ route('chat.create') }}" 
               onclick="event.preventDefault(); window.parent.loadChat('{{ route('chat.create') }}')"
               class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L7.414 9H15a1 1 0 110 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
            @if($chat->exists)
                @foreach($chat->messages as $message)
                    <div class="flex {{ $message->is_sender ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[70%] {{ $message->is_sender ? 'bg-blue-500 text-white' : 'bg-gray-100' }} rounded-lg p-3">
                            @if(!$message->is_sender)
                                <div class="text-xs font-medium {{ $message->is_sender ? 'text-blue-100' : 'text-gray-500' }} mb-1">
                                    {{ $message->user ? $message->user->name : ($message->sender_type === 'agent' ? 'Agent' : 'Guest') }}
                                </div>
                            @endif
                            <p class="text-sm">{{ $message->content }}</p>
                            <span class="text-xs {{ $message->is_sender ? 'text-blue-100' : 'text-gray-500' }}">
                                {{ $message->formatted_time }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="flex items-center justify-center h-full">
                    <div class="text-center text-gray-500">
                        <p>This chat does not exist.</p>
                        <a href="{{ route('chat.create') }}" 
                           onclick="event.preventDefault(); window.parent.loadChat('{{ route('chat.create') }}')"
                           class="inline-block mt-4 text-blue-500 hover:text-blue-600">
                            Return to Chat List
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Message Input -->
        @if($chat->exists && $chat->status === 'active')
            <div class="p-4 bg-white border-t">
                <form id="message-form" class="flex items-center space-x-2">
                    @csrf
                    <input type="text" 
                           id="message-input" 
                           class="flex-1 rounded-full border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500" 
                           placeholder="Type your message..." 
                           required>
                    <button type="submit" 
                            class="p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                    </button>
                </form>
            </div>
        @elseif($chat->exists)
            <div class="p-4 bg-gray-100 border-t text-center text-gray-500">
                This chat has ended
            </div>
        @endif
    </div>
</body>
</html>

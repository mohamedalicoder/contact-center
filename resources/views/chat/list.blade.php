<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Your Chats</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 h-full">
    <div class="h-full p-4">
        <div class="space-y-3">
            @foreach($chats as $chat)
                <a href="{{ route('chat.show', $chat) }}" 
                   onclick="event.preventDefault(); window.parent.loadChat('{{ route('chat.show', $chat) }}')" 
                   class="block bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
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
                            </div>
                            <span class="text-xs text-gray-500">
                                {{ $chat->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        @if($chat->messages->isNotEmpty())
                            <p class="text-sm text-gray-600 truncate">
                                {{ $chat->messages->first()->content }}
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            <a href="{{ route('chat.create') }}" 
               onclick="event.preventDefault(); window.parent.loadChat('{{ route('chat.create') }}')"
               class="block w-full py-2 px-4 bg-blue-500 text-white text-center rounded-lg hover:bg-blue-600 transition-colors">
                Start New Chat
            </a>
        </div>
    </div>
</body>
</html>

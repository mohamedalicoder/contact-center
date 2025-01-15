<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="h-full flex flex-col">
        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
            @foreach($chat->messages as $message)
                <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[70%] {{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-100' }} rounded-lg p-3">
                        <div class="flex items-center space-x-2">
                            @if($message->user_id !== auth()->id())
                                <span class="text-xs font-medium {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                                    {{ $message->user->name }}
                                </span>
                            @endif
                        </div>
                        <p class="mt-1">{{ $message->content }}</p>
                        <span class="text-xs {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                            {{ $message->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message Input -->
        @if($chat->status === 'active')
            <div class="border-t p-4 bg-white">
                <form id="message-form" class="flex space-x-4">
                    @csrf
                    <input type="text" id="message-input" class="flex-1 rounded-md border-gray-300" placeholder="Type your message..." required>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Send
                    </button>
                </form>
            </div>
        @else
            <div class="border-t p-4 text-center text-gray-500 bg-white">
                This chat has ended
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('chat-messages');
            const messageForm = document.getElementById('message-form');
            const messageInput = document.getElementById('message-input');
            const chatId = {{ $chat->id }};
            const userId = {{ auth()->id() }};

            // Scroll to bottom of messages
            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
            scrollToBottom();

            // Initialize Pusher
            const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                encrypted: true
            });

            // Subscribe to the chat channel
            const channel = pusher.subscribe('chat.' + chatId);
            channel.bind('message.sent', function(data) {
                if (data.message.user_id !== userId) {
                    const messageHtml = `
                        <div class="flex justify-start">
                            <div class="max-w-[70%] bg-gray-100 rounded-lg p-3">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-medium text-gray-500">
                                        ${data.message.user.name}
                                    </span>
                                </div>
                                <p class="mt-1">${data.message.content}</p>
                                <span class="text-xs text-gray-500">
                                    ${new Date(data.message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                </span>
                            </div>
                        </div>
                    `;
                    messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                    scrollToBottom();
                }
            });

            // Send message
            if (messageForm) {
                messageForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const message = messageInput.value.trim();
                    if (!message) return;

                    try {
                        const response = await fetch('{{ route('chat.message', $chat) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ message: message })
                        });

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        const data = await response.json();
                        
                        if (data.success) {
                            messageInput.value = '';
                            const messageHtml = `
                                <div class="flex justify-end">
                                    <div class="max-w-[70%] bg-blue-500 text-white rounded-lg p-3">
                                        <p class="mt-1">${message}</p>
                                        <span class="text-xs text-blue-100">
                                            ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                        </span>
                                    </div>
                                </div>
                            `;
                            messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                            scrollToBottom();
                        } else {
                            throw new Error(data.message || 'Failed to send message');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Failed to send message. Please try again.');
                    }
                });
            }
        });
    </script>
</body>
</html>

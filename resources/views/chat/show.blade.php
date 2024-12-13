<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Chat with') }} {{ $chat->contact->name }}
            </h2>
            <button onclick="endChat()" class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-600">
                End Chat
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Chat Window -->
                    <div class="h-[600px] flex flex-col">
                        <!-- Chat Messages -->
                        <div class="overflow-y-auto flex-1 p-4 space-y-4" id="chat-messages">
                            @foreach($chat->messages as $message)
                                <div class="flex {{ $message->sender_type === 'agent' ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[70%] {{ $message->sender_type === 'agent' ? 'bg-blue-500 text-white' : 'bg-gray-100' }} rounded-lg p-3">
                                        <p>{{ $message->content }}</p>
                                        <span class="text-xs {{ $message->sender_type === 'agent' ? 'text-blue-100' : 'text-gray-500' }}">
                                            {{ $message->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Message Input -->
                        <div class="p-4 border-t">
                            <form id="message-form" class="flex gap-4">
                                <input type="text"
                                       id="message-input"
                                       class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                       placeholder="Type your message...">
                                <button type="submit"
                                        class="px-6 py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                                    Send
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Set current chat ID for real-time updates
        window.currentChatId = {{ $chat->id }};

        // Initialize Pusher
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
        });

        // Subscribe to the chat channel
        const channel = pusher.subscribe('chat');

        // Listen for new messages
        channel.bind('new-message', function(data) {
            if (window.currentChatId === data.chat_id) {
                appendMessage(data.message);
            }
        });

        // Handle message form submission
        document.getElementById('message-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const input = document.getElementById('message-input');
            const message = input.value.trim();

            if (!message) return;

            try {
                const response = await fetch('{{ route('chat.message', $chat) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });

                if (!response.ok) throw new Error('Failed to send message');

                input.value = '';
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to send message. Please try again.');
            }
        });

        function appendMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${message.sender_type === 'agent' ? 'justify-end' : 'justify-start'}`;
            messageDiv.innerHTML = `
                <div class="max-w-[70%] ${message.sender_type === 'agent' ? 'bg-blue-500 text-white' : 'bg-gray-100'} rounded-lg p-3">
                    <p>${message.content}</p>
                    <span class="text-xs ${message.sender_type === 'agent' ? 'text-blue-100' : 'text-gray-500'}">${message.created_at}</span>
                </div>
            `;
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function endChat() {
            if (!confirm('Are you sure you want to end this chat?')) return;

            fetch('{{ route('chat.end', $chat) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => {
                if (response.ok) {
                    window.location.href = '{{ route('chat.index') }}';
                } else {
                    alert('Failed to end chat. Please try again.');
                }
            });
        }

        // Auto-scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
    </script>
    @endpush
</x-app-layout>

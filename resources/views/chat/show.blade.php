<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Chat with {{ $chat->agent->name }}</h2>
                        <span class="px-3 py-1 text-sm {{ $chat->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} rounded-full">
                            {{ ucfirst($chat->status) }}
                        </span>
                    </div>

                    <div id="chat-messages" class="space-y-4 mb-6 h-96 overflow-y-auto">
                        @foreach($chat->messages as $message)
                            <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }} mb-4">
                                <div class="max-w-xs lg:max-w-md {{ $message->user_id === auth()->id() ? 'bg-orange-500 text-white' : 'bg-gray-100'}} rounded-lg px-4 py-2">
                                    <p class="text-sm">{{ $message->content }}</p>
                                    <span class="text-xs {{ $message->user_id === auth()->id() ? 'text-orange-100' : 'text-gray-500'}} block mt-1">
                                        {{ $message->created_at->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($chat->status === 'active')
                        <form id="message-form" class="space-y-4">
                            @csrf
                            <div>
                                <label for="message" class="sr-only">Message</label>
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="3"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Type your message..."
                                    required
                                ></textarea>
                            </div>
                            <div>
                                <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 rounded-md border border-transparent focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                                    Send Message
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4 text-gray-500">
                            This chat has ended
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Initialize Pusher
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
        });

        // Subscribe to private chat channel
        const channel = pusher.subscribe('private-chat.{{ $chat->id }}');
        channel.bind('message-sent', function(data) {
            if (data.message.user_id !== {{ auth()->id() }}) {
                appendMessage(data.message);
            }
        });

        const messagesContainer = document.getElementById('chat-messages');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message');

        // Function to append a new message
        function appendMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${message.user_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'} mb-4`;
            
            messageDiv.innerHTML = `
                <div class="max-w-xs lg:max-w-md ${message.user_id === {{ auth()->id() }} ? 'bg-orange-500 text-white' : 'bg-gray-100'} rounded-lg px-4 py-2">
                    <p class="text-sm">${message.content}</p>
                    <span class="text-xs ${message.user_id === {{ auth()->id() }} ? 'text-orange-100' : 'text-gray-500'} block mt-1">
                        ${new Date(message.created_at).toLocaleTimeString()}
                    </span>
                </div>
            `;
            
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Handle message form submission
        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) {
                return;
            }
            
            try {
                const response = await fetch('{{ route("chat.messages.store", $chat) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.message || 'Failed to send message');
                }
                
                // Clear input
                messageInput.value = '';
                
                // Append the message
                appendMessage(data.message);
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Failed to send message. Please try again.');
            }
        });

        // Scroll to bottom initially
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    </script>
    @endpush
</x-app-layout>

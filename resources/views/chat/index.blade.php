<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Live Chat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-12 gap-4">
                        <!-- Chat List -->
                        <div class="col-span-4 border-r">
                            <div class="space-y-4">
                                @forelse($activeChats as $chat)
                                    <div class="p-4 rounded border cursor-pointer hover:bg-gray-50"
                                         onclick="window.location.href='{{ route('chat.show', $chat) }}'">
                                        <div class="flex justify-between items-center">
                                            <h3 class="font-semibold">{{ $chat->contact->name }}</h3>
                                            <span class="text-sm text-gray-500">
                                                {{ $chat->messages->last()?->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 truncate">
                                            {{ $chat->messages->last()?->content ?? 'No messages' }}
                                        </p>
                                    </div>
                                @empty
                                    <div class="py-4 text-center text-gray-500">
                                        No active chats
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Chat Window -->
                        <div class="col-span-8">
                            <div class="h-[600px] flex flex-col">
                                <div class="overflow-y-auto flex-1 p-4 space-y-4" id="chat-messages">
                                    <div class="text-center text-gray-500">
                                        Select a chat to start messaging
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
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

        function appendMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${message.sender_type === 'agent' ? 'justify-end' : 'justify-start'}`;
            messageDiv.innerHTML = `
                <div class="max-w-[70%] ${message.sender_type === 'agent' ? 'bg-blue-500 text-white' : 'bg-gray-100'} rounded-lg p-3">
                    <p>${message.content}</p>
                    <span class="text-xs text-gray-500">${message.created_at}</span>
                </div>
            `;
            document.getElementById('chat-messages').appendChild(messageDiv);
        }
    </script>
    @endpush
</x-app-layout>

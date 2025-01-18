<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 to-white">
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                <!-- Header Section -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">Get in Touch with Our Support Team</h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">We're here to help! Fill out the form below to start a conversation with one of our customer support agents.</p>
                </div>

                <div class="grid gap-8 md:grid-cols-5">
                    <!-- Contact Form -->
                    <div class="md:col-span-3">
                        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                            <div class="p-8">
                                <form id="start-chat-form" method="POST" action="{{ route('chat.store') }}" class="space-y-6">
                                    @csrf
                                    <div class="space-y-4">
                                        <div>
                                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <input type="text" name="name" id="name" required
                                                    class="pl-10 w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500"
                                                    placeholder="John Doe">
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                                <input type="email" name="email" id="email" required
                                                    class="pl-10 w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500"
                                                    placeholder="john@example.com">
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <label for="message" class="block text-sm font-medium text-gray-700">How can we help you?</label>
                                            <div class="relative">
                                                <div class="absolute top-3 left-3 flex items-center pointer-events-none">
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                    </svg>
                                                </div>
                                                <textarea name="message" id="message" rows="4" required
                                                    class="pl-10 w-full rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500"
                                                    placeholder="Please describe your issue or question..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <button type="submit"
                                            class="w-full flex items-center justify-center px-8 py-4 border border-transparent text-base font-medium rounded-xl text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            Start Chat
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Features Section -->
                    <div class="md:col-span-2">
                        <div class="space-y-6">
                            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 bg-orange-100 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Quick Response Time</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get answers to your questions within minutes from our support team.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 bg-orange-100 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Secure Chat</h3>
                                        <p class="mt-1 text-sm text-gray-500">Your conversations are encrypted and protected with industry-standard security.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="p-2 bg-orange-100 rounded-lg">
                                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Chat History</h3>
                                        <p class="mt-1 text-sm text-gray-500">Access your conversation history anytime for future reference.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Window (Hidden by default) -->
        <div id="chat-window" class="hidden fixed bottom-0 right-0 mb-4 mr-4 w-96">
            <div class="bg-white rounded-t-xl shadow-2xl">
                <div class="p-4 bg-orange-600 text-white rounded-t-xl flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                        <span class="font-medium">Live Chat</span>
                    </div>
                    <button id="minimize-chat" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <div id="chat-messages" class="h-96 overflow-y-auto p-4 space-y-4">
                    <!-- Messages will appear here -->
                </div>

                <div class="p-4 border-t border-gray-200">
                    <form id="chat-form" class="flex gap-2">
                        @csrf
                        <input type="text" id="message-input"
                            class="flex-1 rounded-xl border-gray-200 focus:border-orange-500 focus:ring-orange-500"
                            placeholder="Type your message...">
                        <button type="submit"
                            class="px-4 py-2 bg-orange-600 text-white rounded-xl hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const startChatForm = document.getElementById('start-chat-form');
        const chatWindow = document.getElementById('chat-window');
        const chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const minimizeChat = document.getElementById('minimize-chat');
        let currentChatId = null;

        startChatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(startChatForm);
            
            try {
                const response = await fetch('{{ route("chat.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: formData.get('name'),
                        email: formData.get('email'),
                        message: formData.get('message')
                    })
                });

                const data = await response.json();
                if (data.chat) {
                    currentChatId = data.chat.id;
                    startChatForm.closest('.md\\:col-span-3').classList.add('hidden');
                    chatWindow.classList.remove('hidden');
                    appendMessage('user', formData.get('message'));
                    initializeChat(data.chat.id);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to start chat. Please try again.');
            }
        });

        function initializeChat(chatId) {
            // Listen for new messages
            Echo.private(`chat.${chatId}`)
                .listen('MessageSent', (e) => {
                    if (e.message.user_id !== {{ Auth::id() ?? 'null' }}) {
                        appendMessage('agent', e.message.content);
                    }
                });

            chatForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const message = messageInput.value.trim();
                if (!message) return;

                try {
                    const response = await fetch(`/chats/${chatId}/messages`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ message })
                    });

                    const data = await response.json();
                    if (data.success) {
                        appendMessage('user', message);
                        messageInput.value = '';
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to send message. Please try again.');
                }
            });
        }

        function appendMessage(type, content) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${type === 'user' ? 'justify-end' : 'justify-start'}`;
            
            const messageBubble = document.createElement('div');
            messageBubble.className = `max-w-xs px-4 py-3 rounded-xl ${
                type === 'user' 
                    ? 'bg-orange-600 text-white' 
                    : 'bg-gray-100 text-gray-900'
            }`;
            messageBubble.textContent = content;
            
            messageDiv.appendChild(messageBubble);
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        minimizeChat.addEventListener('click', () => {
            chatWindow.classList.toggle('hidden');
        });
    </script>
    @endpush
</x-app-layout>

<div class="fixed bottom-4 right-4 z-50">
    <!-- Chat Button -->
    <button onclick="toggleChat()" class="bg-blue-500 hover:bg-blue-600 text-white rounded-full w-14 h-14 shadow-lg flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>

    <!-- Chat Window -->
    <div id="chat-window" class="hidden fixed bottom-20 right-4 w-96 bg-white rounded-lg shadow-xl">
        <div class="p-4 bg-blue-500 text-white rounded-t-lg flex justify-between items-center">
            <h3 class="text-lg font-semibold">Live Chat</h3>
            <button onclick="toggleChat()" class="text-white hover:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="chat-content" class="bg-gray-50">
            @auth
                @if(isset($activeChat) && $activeChat)
                    <iframe src="{{ route('chat.show', $activeChat) }}" class="w-full h-[500px] border-0"></iframe>
                @else
                    <div class="p-4 h-[500px] flex flex-col">
                        <div class="flex-1 overflow-y-auto mb-4" id="messages-container"></div>
                        <form id="chat-form" class="space-y-4">
                            @csrf
                            <div class="flex items-center space-x-2">
                                <input type="text" 
                                       id="message-input"
                                       class="flex-1 rounded-full border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500"
                                       placeholder="Type your message..."
                                       required
                                       autofocus>
                                <button type="submit" 
                                        class="bg-blue-500 text-white rounded-full p-2 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            @else
                <div class="p-8 text-center">
                    <p class="text-gray-600 mb-4">Please login to start a chat</p>
                    <a href="{{ route('login') }}" class="inline-block bg-blue-500 text-white rounded-full px-6 py-2 hover:bg-blue-600 transition-colors">
                        Login
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>

<script>
function toggleChat() {
    const chatWindow = document.getElementById('chat-window');
    chatWindow.classList.toggle('hidden');
    
    // Focus on input when chat window is opened
    const messageInput = document.getElementById('message-input');
    if (messageInput && !chatWindow.classList.contains('hidden')) {
        messageInput.focus();
    }
}

// Handle new chat creation
const chatForm = document.getElementById('chat-form');
if (chatForm) {
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        if (!message) return;

        try {
            const response = await fetch('{{ route('chat.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            if (data.success) {
                // Reload the page to show the new chat
                window.location.reload();
            } else {
                throw new Error(data.message || 'Failed to start chat');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to start chat. Please try again.');
        }
    });
}
</script>

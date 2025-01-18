<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md">
            <div class="p-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold">المساعد الذكي</h2>
                <p class="text-sm text-gray-600">احصل على مساعدة فورية من المساعد الذكي</p>
            </div>
            
            <div class="p-4">
                <div id="chat-messages" class="space-y-4 h-96 overflow-y-auto mb-4">
                    <div class="flex justify-start">
                        <div class="max-w-xs px-4 py-2 rounded-lg bg-gray-100 text-gray-900">
                            مرحباً! أنا المساعد الذكي. كيف يمكنني مساعدتك اليوم؟
                        </div>
                    </div>
                </div>
                
                <form id="chat-form" class="flex gap-2">
                    @csrf
                    <input type="text" id="message-input" 
                        class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="اكتب رسالتك هنا...">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        إرسال
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        
        // Function to append message to chat
        function appendMessage(type, content) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${type === 'user' ? 'justify-end' : 'justify-start'}`;
            
            const messageBubble = document.createElement('div');
            messageBubble.className = `max-w-xs px-4 py-2 rounded-lg ${
                type === 'user' 
                    ? 'bg-blue-600 text-white' 
                    : type === 'error'
                        ? 'bg-red-100 text-red-700'
                        : 'bg-gray-100 text-gray-900'
            }`;
            messageBubble.textContent = content;
            
            messageDiv.appendChild(messageBubble);
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            return messageDiv;
        }

        // Function to send message to server
        async function sendMessage(message) {
            const response = await fetch('/chat/bot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'حدث خطأ في الاتصال');
            }

            return data;
        }
        
        // Handle form submission
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Add user message
            appendMessage('user', message);
            messageInput.value = '';
            
            try {
                // Show loading message
                const loadingMessage = appendMessage('bot', 'جاري التفكير...');
                
                // Get response from bot
                const data = await sendMessage(message);
                
                // Remove loading message
                loadingMessage.remove();
                
                // Add bot response
                appendMessage('bot', data.response);
            } catch (error) {
                appendMessage('error', error.message);
            }
        });
    </script>
    @endpush
</x-app-layout>

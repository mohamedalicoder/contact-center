document.addEventListener('DOMContentLoaded', () => {
    const messagesContainer = document.getElementById('chat-messages');
    
    // Initialize Pusher
    if (typeof Pusher !== 'undefined' && window.pusherKey && window.pusherCluster) {
        const pusher = new Pusher(window.pusherKey, {
            cluster: window.pusherCluster,
            encrypted: true
        });

        // Subscribe to private chat channel
        const chatId = window.currentChatId;
        if (chatId) {
            const channel = pusher.subscribe(`private-chat.${chatId}`);
            
            channel.bind('MessageSent', function(data) {
                // Only append message if it's not from the current user
                if (!data.message.is_sender) {
                    appendMessage(data.message);
                    scrollToBottom();
                }
            });

            // Handle subscription error
            channel.bind('pusher:subscription_error', function(status) {
                console.error('Pusher subscription error:', status);
            });
        }
    }

    // Function to append a message to the chat
    function appendMessage(message) {
        const messageDiv = document.createElement('div');
        const isSender = message.is_sender;
        
        messageDiv.className = `flex ${isSender ? 'justify-end' : 'justify-start'} mb-4`;
        
        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md ${isSender ? 'bg-orange-500 text-white' : 'bg-gray-100'} rounded-lg px-4 py-2">
                <div class="flex items-center mb-1">
                    <span class="text-xs font-medium ${isSender ? 'text-orange-100' : 'text-gray-900'}">${message.user_name}</span>
                </div>
                <p class="text-sm">${message.content}</p>
                <span class="text-xs ${isSender ? 'text-orange-100' : 'text-gray-500'} block mt-1">
                    ${new Date(message.created_at).toLocaleTimeString()}
                </span>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }

    // Function to scroll chat to bottom
    function scrollToBottom() {
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    // Scroll to bottom on load
    scrollToBottom();
});

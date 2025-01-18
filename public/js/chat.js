document.addEventListener('DOMContentLoaded', () => {
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
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
                appendMessage(data.message);
                scrollToBottom();
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
        const isSender = message.sender_type === 'agent';
        
        messageDiv.className = `flex ${isSender ? 'justify-end' : 'justify-start'} mb-4`;
        
        messageDiv.innerHTML = `
            <div class="${isSender ? 'bg-blue-500 text-white' : 'bg-gray-200'} rounded-lg px-4 py-2 max-w-[70%]">
                <p class="text-sm">${message.content}</p>
                <span class="text-xs ${isSender ? 'text-blue-100' : 'text-gray-400'}">${new Date(message.created_at).toLocaleTimeString()}</span>
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

    // Handle message submission
    if (messageForm) {
        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;

            try {
                const response = await fetch(`/chat/${window.currentChatId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();
                
                if (data.success) {
                    messageInput.value = '';
                    messageInput.focus();
                } else {
                    throw new Error(data.message || 'Failed to send message');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Failed to send message. Please try again.');
            }
        });
    }

    // Scroll to bottom on load
    scrollToBottom();
});

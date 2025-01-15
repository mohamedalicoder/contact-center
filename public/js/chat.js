// Initialize Pusher
const pusher = new Pusher(window.pusherKey, {
    cluster: window.pusherCluster,
    encrypted: true
});

// Subscribe to the private chat channel
window.Echo.private(`chat.${window.currentChatId}`)
    .listen('MessageSent', (e) => {
        appendMessage(e.message);
    });

// Listen for new messages
const channel = pusher.subscribe(`private-chat.${window.currentChatId}`);
channel.bind('message.sent', function(data) {
    if (window.currentChatId === data.message.chat_id) {
        appendMessage(data.message);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const messageForm = document.getElementById('message-form');
    if (!messageForm) return;

    // Function to append a new message to the chat window
    window.appendMessage = function(message) {
        const chatMessages = document.getElementById('chat-messages');
        if (!chatMessages) return;

        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${message.sender_type === 'agent' ? 'justify-end' : 'justify-start'} mb-4`;
        
        messageDiv.innerHTML = `
            <div class="${message.sender_type === 'agent' ? 'bg-blue-500 text-white' : 'bg-gray-200'} rounded-lg px-4 py-2 max-w-[70%]">
                <p class="text-sm">${message.content}</p>
                <span class="text-xs ${message.sender_type === 'agent' ? 'text-blue-100' : 'text-gray-400'}">${new Date(message.created_at).toLocaleTimeString()}</span>
            </div>
        `;

        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    };

    // Function to send a new message
    async function sendMessage(event) {
        event.preventDefault();
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        
        if (!message) return;

        try {
            const response = await fetch(`/chats/${window.currentChatId}/message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Failed to send message');
            }

            const data = await response.json();
            if (data.success) {
                messageInput.value = '';
                messageInput.focus();
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert(error.message || 'Failed to send message. Please try again.');
        }
    }

    // Add event listener for form submission
    messageForm.addEventListener('submit', sendMessage);

    // Auto-scroll chat to bottom on load
    const chatMessages = document.getElementById('chat-messages');
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});

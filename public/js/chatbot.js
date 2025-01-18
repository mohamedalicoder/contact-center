async function sendMessage(message) {
    try {
        const response = await fetch('/api/chat/bot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ message })
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'Failed to get response from ChatGPT');
        }

        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

// Example usage in your chat interface:
/*
document.getElementById('chat-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const messageInput = document.getElementById('message-input');
    const message = messageInput.value;
    
    try {
        const response = await sendMessage(message);
        // Add the bot's response to your chat interface
        console.log(response.response);
        messageInput.value = '';
    } catch (error) {
        console.error('Failed to get response:', error);
    }
});
*/

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">المساعد الآلي</h5>
                </div>
                <div class="card-body">
                    <div id="chat-messages" class="chat-container mb-3" style="height: 400px; overflow-y: auto;">
                        <!-- Messages will appear here -->
                    </div>
                    <form id="chat-form" class="mt-3">
                        @csrf
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary">إرسال</button>
                            <input type="text" id="message-input" class="form-control" placeholder="اكتب رسالتك هنا..." dir="rtl">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .chat-container {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
    }
    .message {
        margin-bottom: 1rem;
        padding: 0.75rem;
        border-radius: 0.5rem;
        max-width: 80%;
    }
    .user-message {
        background: #e3f2fd;
        margin-left: 20%;
        text-align: right;
    }
    .bot-message {
        background: #ffffff;
        margin-right: 20%;
        text-align: right;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');

    // Add initial bot message
    addMessage('مرحباً! كيف يمكنني مساعدتك اليوم؟', 'bot');

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Add user message to chat
        addMessage(message, 'user');

        // Clear input
        messageInput.value = '';

        // Send message to server
        fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            // Add bot response to chat
            addMessage(data.response, 'bot');
        })
        .catch(error => {
            console.error('Error:', error);
            addMessage('عذراً، حدث خطأ. يرجى المحاولة مرة أخرى.', 'bot');
        });
    });

    function addMessage(text, type) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', `${type}-message`);
        messageDiv.textContent = text;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});
</script>
@endpush
@endsection

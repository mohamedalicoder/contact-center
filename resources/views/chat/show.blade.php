<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Chat Window -->
                    <div class="h-[600px] flex flex-col">
                        <!-- Chat Messages -->
                        <div class="flex-1 overflow-y-auto p-4 space-y-4" id="chat-messages">
                            @foreach($chat->messages as $message)
                                <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                    <div class="max-w-[70%] {{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-100' }} rounded-lg p-3">
                                        <div class="flex items-center space-x-2">
                                            @if($message->user_id !== auth()->id())
                                                <span class="text-xs font-medium {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                                                    {{ $message->user->name }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($message->type === 'audio')
                                            <div class="mt-1">
                                                <audio controls class="w-full">
                                                    <source src="{{ Storage::url($message->file_path) }}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                        @else
                                            <p class="mt-1">{{ $message->content }}</p>
                                        @endif
                                        <span class="text-xs {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }}">
                                            {{ $message->created_at->format('H:i') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Message Input -->
                        @if($chat->status === 'active')
                            <div class="border-t p-4 bg-white">
                                <form id="message-form" class="flex items-center space-x-2">
                                    @csrf
                                    <button type="button" 
                                            id="record-button"
                                            class="p-2 text-gray-500 hover:text-blue-500 focus:outline-none"
                                            onclick="toggleRecording()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                        </svg>
                                    </button>
                                    
                                    <input type="text" 
                                           id="message-input" 
                                           class="flex-1 rounded-full border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500" 
                                           placeholder="Type your message..." 
                                           required>
                                    
                                    <button type="submit" 
                                            class="p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let mediaRecorder;
            let audioChunks = [];
            let isRecording = false;

            // Initialize Pusher with environment variables
            const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                encrypted: true
            });

            // Subscribe to the chat channel
            const channel = pusher.subscribe('chat.{{ $chat->id }}');
            channel.bind('message.sent', function(data) {
                if (data.message.user_id !== {{ auth()->id() }}) {
                    let messageContent;
                    if (data.message.type === 'audio') {
                        messageContent = `
                            <div class="mt-1">
                                <audio controls class="w-full">
                                    <source src="/storage/${data.message.file_path}" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        `;
                    } else {
                        messageContent = `<p class="mt-1">${data.message.content}</p>`;
                    }

                    const messageHtml = `
                        <div class="flex justify-start">
                            <div class="max-w-[70%] bg-gray-100 rounded-lg p-3">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-medium text-gray-500">
                                        ${data.message.user.name}
                                    </span>
                                </div>
                                ${messageContent}
                                <span class="text-xs text-gray-500">
                                    ${new Date(data.message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                </span>
                            </div>
                        </div>
                    `;
                    const messagesContainer = document.getElementById('chat-messages');
                    messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            });

            // Handle text message submission
            const messageForm = document.getElementById('message-form');
            if (messageForm) {
                messageForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const messageInput = document.getElementById('message-input');
                    const message = messageInput.value.trim();
                    if (!message) return;

                    try {
                        const response = await fetch('{{ route('chat.message', $chat) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ message: message })
                        });

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        const data = await response.json();
                        
                        if (data.success) {
                            messageInput.value = '';
                            const messageHtml = `
                                <div class="flex justify-end">
                                    <div class="max-w-[70%] bg-blue-500 text-white rounded-lg p-3">
                                        <p class="mt-1">${message}</p>
                                        <span class="text-xs text-blue-100">
                                            ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                        </span>
                                    </div>
                                </div>
                            `;
                            const messagesContainer = document.getElementById('chat-messages');
                            messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        } else {
                            throw new Error(data.message || 'Failed to send message');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Failed to send message. Please try again.');
                    }
                });
            }

            window.setupRecorder = async function() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    
                    mediaRecorder.ondataavailable = (event) => {
                        audioChunks.push(event.data);
                    };

                    mediaRecorder.onstop = async () => {
                        const audioBlob = new Blob(audioChunks, { type: 'audio/mpeg-3' });
                        const formData = new FormData();
                        formData.append('audio', audioBlob, 'recording.mp3');
                        
                        try {
                            const response = await fetch('{{ route('chat.message', $chat) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                                body: formData
                            });

                            const data = await response.json();
                            if (data.success) {
                                const messageHtml = `
                                    <div class="flex justify-end">
                                        <div class="max-w-[70%] bg-blue-500 text-white rounded-lg p-3">
                                            <div class="mt-1">
                                                <audio controls class="w-full">
                                                    <source src="/storage/${data.message.file_path}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                            <span class="text-xs text-blue-100">
                                                ${new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
                                            </span>
                                        </div>
                                    </div>
                                `;
                                const messagesContainer = document.getElementById('chat-messages');
                                messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Failed to send audio message. Please try again.');
                        }

                        audioChunks = [];
                    };
                } catch (error) {
                    console.error('Error accessing microphone:', error);
                    alert('Unable to access microphone. Please check your browser permissions.');
                }
            }

            window.toggleRecording = function() {
                if (!mediaRecorder) {
                    setupRecorder();
                    return;
                }

                const recordButton = document.getElementById('record-button');
                
                if (isRecording) {
                    mediaRecorder.stop();
                    recordButton.classList.remove('text-red-500');
                    recordButton.classList.add('text-gray-500');
                } else {
                    mediaRecorder.start();
                    recordButton.classList.remove('text-gray-500');
                    recordButton.classList.add('text-red-500');
                }
                
                isRecording = !isRecording;
            }
        });
    </script>
    @endpush
</x-app-layout>

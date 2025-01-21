<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Main Content -->
        <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex h-[calc(100vh-9rem)] bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Sidebar -->
                <div class="w-80 bg-white border-r border-gray-100">
                    <div class="flex flex-col h-full">
                        <!-- Search and Status -->
                        <div class="p-4 bg-white border-b border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-semibold text-gray-800">Active Chats</h2>
                                <span class="px-3 py-1 text-sm font-medium text-blue-600 bg-blue-50 rounded-full shadow-sm" id="active-chats-count">0</span>
                            </div>
                            <div class="relative">
                                <input type="text" placeholder="Search conversations..."
                                    class="py-2.5 pr-4 pl-10 w-full rounded-xl border border-gray-200 transition duration-150 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Chat List -->
                        <div class="overflow-y-auto flex-1 p-3 space-y-2 bg-gray-50" id="chat-list">
                            <!-- Active chats will be listed here -->
                        </div>
                    </div>
                </div>

                <!-- Main Chat Area -->
                <div class="flex flex-col flex-1 bg-white">
                    <!-- Empty State -->
                    <div id="chat-placeholder" class="flex flex-col justify-center items-center h-full text-gray-400">
                        <svg class="mb-4 w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-lg font-medium">Select a chat to start messaging</p>
                    </div>

                    <!-- Chat Area -->
                    <div id="chat-area" class="flex hidden flex-col h-full">
                        <!-- Chat Header -->
                        <div class="flex justify-between items-center px-6 py-4 bg-white border-b border-gray-100 shadow-sm">
                            <div class="flex items-center space-x-4">
                                <div class="flex justify-center items-center w-12 h-12 text-white bg-gradient-to-r from-blue-500 to-blue-600 rounded-full shadow-md">
                                    <span id="chat-user-initial" class="text-lg font-medium"></span>
                                </div>
                                <div>
                                    <h3 id="chat-user-name" class="text-lg font-medium text-gray-900"></h3>
                                    <span id="chat-status" class="text-sm text-gray-500"></span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button id="end-chat-btn" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg transition duration-150 hover:bg-red-100">
                                    <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    End Chat
                                </button>
                            </div>
                        </div>

                        <!-- Messages Area -->
                        <div id="chat-messages" class="overflow-y-auto flex-1 p-6 space-y-4 bg-gray-50">
                            <!-- Messages will be inserted here -->
                        </div>

                        <!-- Message Input -->
                        <div class="p-4 bg-white border-t border-gray-100">
                            <form id="message-form" class="flex space-x-4">
                                <div class="flex-1">
                                    <label for="message" class="sr-only">Type your message</label>
                                    <textarea
                                        id="message-input"
                                        name="message"
                                        rows="1"
                                        class="block w-full rounded-xl border-gray-200 shadow-sm transition duration-150 resize-none focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Type your message..."
                                    ></textarea>
                                </div>
                                <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                    <button id="sendMessageBtn" type="submit" class="px-6 py-2.5 text-white bg-blue-600 rounded-xl shadow-sm transition duration-150 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                        إرسال
                                    </button>
                                    <button type="button" id="voiceMessageBtn" class="p-2.5 text-gray-600 bg-gray-100 rounded-xl transition duration-150 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Debug information
        console.log('Chat ID:', {{ $chat->id ?? 'null' }});
        console.log('User ID:', {{ auth()->id() ?? 'null' }});
        console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.content);

        let currentChatId = {{ $chat->id ?? 'null' }};
        const currentUserId = {{ auth()->id() ?? 'null' }};

        // Initialize Pusher
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
        });

        let chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const chatList = document.getElementById('chat-list');
        const emptyState = document.getElementById('chat-placeholder');
        const chatArea = document.getElementById('chat-area');
        const customerName = document.getElementById('chat-user-name');
        const customerInitial = document.getElementById('chat-user-initial');
        const chatStatus = document.getElementById('chat-status');
        const endChatBtn = document.getElementById('end-chat-btn');
        const activeChatsCount = document.getElementById('active-chats-count');

        // Subscribe to the public channel for new chats
        const publicChannel = pusher.subscribe('public-channel');
        publicChannel.bind('new-chat', function(data) {
            // Play notification sound
            const audio = new Audio('/notification.mp3');
            audio.play();

            // Show browser notification
            if (Notification.permission === "granted") {
                new Notification("New Chat Request", {
                    body: `New chat request from ${data.user_name}`,
                    icon: "/favicon.ico"
                });
            }

            // Add the new chat to the top of the list
            addNewChatToList(data.chat);

            // Update the active chats count
            const countElement = document.getElementById('active-chats-count');
            countElement.textContent = parseInt(countElement.textContent || 0) + 1;
        });

        // Function to subscribe to a specific chat channel
        function subscribeToChat(chatId) {
            const channel = pusher.subscribe(`private-chat.${chatId}`);

            channel.bind('message-sent', function(data) {
                if (data.message.user_id !== {{ auth()->id() }}) {
                    // Play notification sound
                    const audio = new Audio('/notification.mp3');
                    audio.play();

                    // Show browser notification
                    if (Notification.permission === "granted") {
                        new Notification("New Message", {
                            body: `${data.user_name}: ${data.message.content}`,
                            icon: "/favicon.ico"
                        });
                    }

                    // If this is the current chat, append the message
                    if (currentChatId === chatId) {
                        appendMessage(data.message);
                    }

                    // Move this chat to the top of the list
                    moveChatToTop(chatId);

                    // Update the last message in the chat list
                    const chatDiv = Array.from(document.getElementById('chat-list').children)
                        .find(div => div.onclick.toString().includes(chatId));

                    if (chatDiv) {
                        const messagePreview = chatDiv.querySelector('p');
                        const timeSpan = chatDiv.querySelector('span.text-gray-500');
                        if (messagePreview) {
                            messagePreview.textContent = data.message.content;
                        }
                        if (timeSpan) {
                            timeSpan.textContent = new Date(data.message.created_at).toLocaleTimeString();
                        }
                    }
                }
            });
        }

        // Function to move chat to top of list
        function moveChatToTop(chatId) {
            const chatList = document.getElementById('chat-list');
            const chatDiv = Array.from(chatList.children).find(div => div.onclick.toString().includes(chatId));

            if (chatDiv && chatList.firstChild !== chatDiv) {
                chatList.insertBefore(chatDiv, chatList.firstChild);
            }
        }

        // Function to append a new message
        function appendMessage(message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${message.user_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'} mb-4`;

            let messageContent = '';
            if (message.type === 'voice') {
                messageContent = `
                    <div class="flex items-center space-x-2 rtl:space-x-reverse ${message.user_id === {{ auth()->id() }} ? 'bg-blue-500 text-white' : 'bg-gray-200'} rounded-lg px-4 py-2">
                        <audio controls class="max-w-[200px]">
                            <source src="${message.content}" type="audio/webm">
                            <source src="${message.content}" type="audio/mpeg">
                            <source src="${message.content}" type="audio/wav">
                            متصفحك لا يدعم تشغيل الملفات الصوتية
                        </audio>
                        <div class="text-xs ${message.user_id === {{ auth()->id() }} ? 'text-gray-200' : 'text-gray-500'}">
                            ${new Date(message.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' })}
                        </div>
                    </div>
                `;
            } else {
                messageContent = `
                    <div class="${message.user_id === {{ auth()->id() }} ? 'bg-blue-500 text-white' : 'bg-gray-200'} rounded-lg px-4 py-2">
                        <div class="text-sm">
                            ${message.content}
                            <span class="block text-xs ${message.user_id === {{ auth()->id() }} ? 'text-gray-200' : 'text-gray-500'}">
                                ${new Date(message.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' })}
                            </span>
                        </div>
                    </div>
                `;
            }

            messageDiv.innerHTML = messageContent;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Function to load active chats
        async function loadActiveChats() {
            const chatList = document.getElementById('chat-list');
            try {
                const response = await fetch('{{ route("chat.index") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                // Clear the chat list
                while (chatList.firstChild) {
                    chatList.removeChild(chatList.firstChild);
                }

                // Update active chats count
                document.getElementById('active-chats-count').textContent = data.chats ? data.chats.length : 0;

                if (!data.chats || data.chats.length === 0) {
                    chatList.innerHTML = `
                        <div class="flex justify-center items-center p-4 text-gray-500">
                            No active chats
                        </div>
                    `;
                    return;
                }

                // Add each chat to the list
                data.chats.forEach(chat => {
                    const div = document.createElement('div');
                    const isUnread = chat.unread_messages > 0;

                    div.className = `flex items-center p-3 cursor-pointer rounded-lg hover:bg-gray-50 ${isUnread ? 'bg-orange-50' : ''}`;
                    div.onclick = () => loadChat(chat.id);

                    div.innerHTML = `
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <h3 class="text-sm font-medium ${isUnread ? 'text-orange-900' : 'text-gray-900'}">${chat.user_name}</h3>
                                <span class="text-xs text-gray-500">${chat.last_message_time}</span>
                            </div>
                            <div class="flex justify-between items-center mt-1">
                                <p class="text-sm ${isUnread ? 'text-orange-800 font-medium' : 'text-gray-500'} truncate">${chat.last_message || 'No messages'}</p>
                                ${isUnread ? `<span class="px-2 py-1 text-xs font-medium text-white bg-orange-600 rounded-full">${chat.unread_messages}</span>` : ''}
                            </div>
                        </div>
                    `;

                    chatList.appendChild(div);
                });
            } catch (error) {
                console.error('Error loading chats:', error);
                chatList.innerHTML = `
                    <div class="flex justify-center items-center p-4 text-red-500">
                        <p>Failed to load chats. Please try again.</p>
                    </div>
                `;
            }
        }

        // Function to add a new chat to the list
        function addNewChatToList(chat) {
            const chatList = document.getElementById('chat-list');
            const div = document.createElement('div');
            div.className = `flex items-center p-3 cursor-pointer rounded-lg hover:bg-gray-50`;
            div.onclick = () => loadChat(chat.id);

            div.innerHTML = `
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="text-sm font-medium text-gray-900">${chat.user_name}</h3>
                        <span class="text-xs text-gray-500">${chat.last_message_time || new Date().toLocaleTimeString()}</span>
                    </div>
                    <p class="text-sm text-gray-500 truncate">${chat.last_message || 'No messages yet'}</p>
                </div>
                <span class="w-2 h-2 bg-green-400 rounded-full"></span>
            `;

            // Insert at the beginning of the list
            if (chatList.firstChild) {
                chatList.insertBefore(div, chatList.firstChild);
            } else {
                chatList.appendChild(div);
            }
        }

        async function loadChat(chatId) {
            try {
                const response = await fetch(`/chat/${chatId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Failed to load chat');
                }

                // Set current chat ID
                currentChatId = chatId;
                window.currentChatId = chatId; // For Pusher

                // Clear existing messages
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.innerHTML = '';

                // Update chat header
                document.getElementById('chat-user-name').textContent = data.chat.user_name;
                document.getElementById('chat-user-initial').textContent = data.chat.user_name.charAt(0).toUpperCase();
                document.getElementById('chat-status').textContent = data.chat.status;

                // Show chat area and hide placeholder
                document.getElementById('chat-area').classList.remove('hidden');
                document.getElementById('chat-placeholder').classList.add('hidden');

                // Add messages
                data.chat.messages.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = `flex ${message.is_sender ? 'justify-end' : 'justify-start'} mb-4`;

                    let messageContent = '';
                    if (message.type === 'voice') {
                        messageContent = `
                            <div class="flex items-center space-x-2 rtl:space-x-reverse ${message.is_sender ? 'bg-blue-500 text-white' : 'bg-gray-200'} rounded-lg px-4 py-2">
                                <audio controls class="max-w-[200px]">
                                    <source src="${message.content}" type="audio/webm">
                                    <source src="${message.content}" type="audio/mpeg">
                                    <source src="${message.content}" type="audio/wav">
                                    متصفحك لا يدعم تشغيل الملفات الصوتية
                                </audio>
                                <div class="text-xs ${message.is_sender ? 'text-gray-200' : 'text-gray-500'}">
                                    ${new Date(message.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' })}
                                </div>
                            </div>
                        `;
                    } else {
                        messageContent = `
                            <div class="${message.is_sender ? 'bg-blue-500 text-white' : 'bg-gray-200'} rounded-lg px-4 py-2">
                                <div class="text-sm">
                                    ${message.content}
                                    <span class="block text-xs ${message.is_sender ? 'text-gray-200' : 'text-gray-500'}">
                                        ${new Date(message.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' })}
                                    </span>
                                </div>
                            </div>
                        `;
                    }

                    messageDiv.innerHTML = messageContent;
                    chatMessages.appendChild(messageDiv);
                });

                // Scroll to bottom
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Update active chat in list
                const chatItems = document.querySelectorAll('#chat-list > div');
                chatItems.forEach(item => {
                    if (item.onclick.toString().includes(chatId)) {
                        item.classList.add('bg-orange-50');
                    } else {
                        item.classList.remove('bg-orange-50');
                    }
                });

            } catch (error) {
                console.error('Error loading chat:', error);
                alert('Failed to load chat. Please try again.');
            }
        }

        // Function to send a message
        async function sendMessage(content) {
            if (!currentChatId) {
                alert('Please select a chat first');
                return;
            }

            try {
                const response = await fetch(`/chat/${currentChatId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ content })
                });

                if (!response.ok) {
                    const data = await response.json();
                    throw new Error(data.error || data.message || 'Failed to send message');
                }

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error || data.message || 'Failed to send message');
                }

                // Clear input
                document.querySelector('#message-input').value = '';

                // Append message to chat
                const messageDiv = document.createElement('div');
                messageDiv.className = 'flex justify-end mb-4';

                let messageContent = '';
                if (data.message.type === 'voice') {
                    messageContent = `
                        <div class="flex items-center px-4 py-2 space-x-2 text-white bg-blue-500 rounded-lg rtl:space-x-reverse">
                            <audio controls class="max-w-[200px]">
                                <source src="${data.message.content}" type="audio/webm">
                                <source src="${data.message.content}" type="audio/mpeg">
                                <source src="${data.message.content}" type="audio/wav">
                                متصفحك لا يدعم تشغيل الملفات الصوتية
                            </audio>
                            <div class="text-xs text-gray-200">
                                ${new Date(data.message.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' })}
                            </div>
                        </div>
                    `;
                } else {
                    messageContent = `
                        <div class="px-4 py-2 text-white bg-blue-500 rounded-lg">
                            <div class="text-sm">
                                ${data.message.content}
                                <span class="block text-xs text-gray-200">
                                    ${new Date(data.message.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' })}
                                </span>
                            </div>
                        </div>
                    `;
                }

                messageDiv.innerHTML = messageContent;
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;

            } catch (error) {
                console.error('Error sending message:', error);

                // Show error message to user
                const errorMessage = error.message.includes('status: 401') ? 'Please log in to send messages' :
                                   error.message.includes('status: 403') ? 'You do not have permission to send messages in this chat' :
                                   'Failed to send message. Please try again.';

                alert(errorMessage);
            }
        }

        // Handle message form submission
        document.getElementById('message-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const messageInput = document.getElementById('message-input');
            const message = messageInput.value.trim();

            if (!message) {
                return;
            }

            try {
                const response = await fetch(`/chat/${currentChatId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ content: message })
                });

                if (!response.ok) {
                    const data = await response.json();
                    throw new Error(data.error || data.message || 'Failed to send message');
                }

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error || data.message || 'Failed to send message');
                }

                // Clear input
                messageInput.value = '';

                // Append message to chat
                const messageDiv = document.createElement('div');
                messageDiv.className = 'flex justify-end mb-4';

                let messageContent = '';
                if (data.message.type === 'voice') {
                    messageContent = `
                        <div class="flex items-center px-4 py-2 space-x-2 text-white bg-blue-500 rounded-lg rtl:space-x-reverse">
                            <audio controls class="max-w-[200px]">
                                <source src="${data.message.content}" type="audio/webm">
                                <source src="${data.message.content}" type="audio/mpeg">
                                <source src="${data.message.content}" type="audio/wav">
                                متصفحك لا يدعم تشغيل الملفات الصوتية
                            </audio>
                            <div class="text-xs text-gray-200">
                                ${new Date(data.message.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' })}
                            </div>
                        </div>
                    `;
                } else {
                    messageContent = `
                        <div class="px-4 py-2 text-white bg-blue-500 rounded-lg">
                            <div class="text-sm">
                                ${data.message.content}
                                <span class="block text-xs text-gray-200">
                                    ${new Date(data.message.created_at).toLocaleTimeString('ar-EG', { hour: '2-digit', minute: '2-digit' })}
                                </span>
                            </div>
                        </div>
                    `;
                }

                messageDiv.innerHTML = messageContent;
                const chatMessages = document.getElementById('chat-messages');
                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;

            } catch (error) {
                console.error('Error sending message:', error);
                // Don't show alert if message was actually sent
                if (error.message.includes('Unexpected end of JSON input')) {
                    return;
                }
                alert(error.message);
            }
        });

        // Handle end chat button
        document.getElementById('end-chat-btn').addEventListener('click', async () => {
            if (!currentChatId) {

                return;
            }

            if (!confirm('Are you sure you want to end this chat?')) {
        
                return;
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch(`/chat/${currentChatId}/end`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || `HTTP error! status: ${response.status}`);
                }

                // Reset chat area
                document.getElementById('chat-placeholder').classList.remove('hidden');
                document.getElementById('chat-area').classList.add('hidden');
                document.getElementById('chat-messages').innerHTML = '';
                currentChatId = null;

                // Reload chat list
                loadActiveChats();

                // Show success message
                alert('Chat ended successfully');
            } catch (error) {
                console.error('Error ending chat:', error);
                alert(error.message || 'Failed to end chat. Please try again.');
            }
        });

        // Load active chats initially
        loadActiveChats();

        // Reload active chats every 30 seconds
        setInterval(loadActiveChats, 30000);
    </script>

    <script>
        let mediaRecorder;
        let audioChunks = [];
        let recordingInterval;
        let recordingSeconds = 0;

        document.getElementById('voiceMessageBtn')?.addEventListener('click', () => {
            document.getElementById('voiceRecordModal').classList.remove('hidden');
        });

        document.getElementById('cancelRecordBtn')?.addEventListener('click', () => {
            resetRecording();
            document.getElementById('voiceRecordModal').classList.add('hidden');
        });

        document.getElementById('startRecordBtn')?.addEventListener('click', async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.ondataavailable = (event) => {
                    audioChunks.push(event.data);
                };

                mediaRecorder.start();
                startTimer();

                document.getElementById('recordingStatus').textContent = 'جاري التسجيل...';
                document.getElementById('startRecordBtn').classList.add('hidden');
                document.getElementById('stopRecordBtn').classList.remove('hidden');
            } catch (err) {
                console.error('Error accessing microphone:', err);
                alert('لا يمكن الوصول إلى الميكروفون');
            }
        });

        document.getElementById('stopRecordBtn')?.addEventListener('click', () => {
            stopRecording();
        });

        document.getElementById('sendVoiceBtn')?.addEventListener('click', async () => {
            const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
            const formData = new FormData();
            formData.append('audio', audioBlob, 'voice-message.webm');
            formData.append('type', 'voice');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            try {
                console.log('Sending voice message to chat:', currentChatId);
                const response = await fetch(`/chat/${currentChatId}/messages/voice`, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                const data = await response.json();
                console.log('Server response:', data);

                if (!response.ok) {
                    throw new Error(data.message || 'حدث خطأ أثناء إرسال الرسالة الصوتية');
                }

                if (data.status === 'success') {
                    resetRecording();
                    document.getElementById('voiceRecordModal').classList.add('hidden');
                    // Add the voice message to the chat
                    appendMessage(data.data);
                } else {
                    throw new Error(data.message || 'حدث خطأ أثناء إرسال الرسالة الصوتية');
                }
            } catch (error) {
                console.error('Error sending voice message:', error);
                alert(error.message || 'حدث خطأ أثناء إرسال الرسالة الصوتية');
            }
        });

        function startTimer() {
            recordingSeconds = 0;
            recordingInterval = setInterval(() => {
                recordingSeconds++;
                const minutes = Math.floor(recordingSeconds / 60).toString().padStart(2, '0');
                const seconds = (recordingSeconds % 60).toString().padStart(2, '0');
                document.getElementById('recordingTime').textContent = `${minutes}:${seconds}`;

                if (recordingSeconds >= 300) { // 5 minutes max
                    stopRecording();
                }
            }, 1000);
        }

        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                mediaRecorder.stop();
                mediaRecorder.stream.getTracks().forEach(track => track.stop());
                clearInterval(recordingInterval);
                document.getElementById('stopRecordBtn').classList.add('hidden');
                document.getElementById('recordingStatus').textContent = 'تم التسجيل';
            }
        }

        function resetRecording() {
            audioChunks = [];
            clearInterval(recordingInterval);
            document.getElementById('recordingTime').textContent = '00:00';
            document.getElementById('startRecordBtn').classList.remove('hidden');
            document.getElementById('stopRecordBtn').classList.add('hidden');
            document.getElementById('sendVoiceBtn').classList.add('hidden');
            document.getElementById('recordingStatus').textContent = 'جاهز للتسجيل';
            if (mediaRecorder && mediaRecorder.stream) {
                mediaRecorder.stream.getTracks().forEach(track => track.stop());
            }
        }
    </script>
    @endpush
</x-app-layout>

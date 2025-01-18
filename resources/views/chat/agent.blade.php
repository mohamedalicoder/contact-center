<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Main Content -->
        <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex h-[calc(100vh-9rem)] bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Sidebar -->
                <div class="w-80 bg-white border-r border-gray-200">
                    <div class="flex flex-col h-full">
                        <!-- Search and Status -->
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-medium text-gray-900">Active Chats</h2>
                                <span class="px-2.5 py-0.5 text-sm font-medium text-orange-700 bg-orange-100 rounded-full" id="active-chats-count">0</span>
                            </div>
                            <div class="relative">
                                <input type="text" placeholder="Search conversations..."
                                    class="py-2 pr-4 pl-10 w-full rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Chat List -->
                        <div class="overflow-y-auto flex-1 p-3 space-y-2" id="chat-list">
                            <!-- Active chats will be listed here -->
                        </div>
                    </div>
                </div>

                <!-- Main Chat Area -->
                <div class="flex flex-col flex-1">
                    <!-- Empty State -->
                    <div id="chat-placeholder" class="flex flex-col justify-center items-center h-full text-gray-500">
                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-lg">Select a chat to start messaging</p>
                    </div>

                    <!-- Chat Area -->
                    <div id="chat-area" class="hidden flex flex-col h-full">
                        <!-- Chat Header -->
                        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="flex justify-center items-center w-10 h-10 bg-orange-100 rounded-full">
                                    <span id="chat-user-initial" class="text-sm font-medium text-orange-600"></span>
                                </div>
                                <div>
                                    <h3 id="chat-user-name" class="text-sm font-medium text-gray-900"></h3>
                                    <span id="chat-status" class="text-xs text-gray-500"></span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button id="end-chat-btn" class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-600 bg-red-100 rounded-md hover:bg-red-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    End Chat
                                </button>
                            </div>
                        </div>

                        <!-- Messages Area -->
                        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4">
                            <!-- Messages will be inserted here -->
                        </div>

                        <!-- Message Input -->
                        <div class="border-t border-gray-200 p-4">
                            <form id="message-form" class="flex space-x-4">
                                <div class="flex-1">
                                    <label for="message" class="sr-only">Type your message</label>
                                    <textarea
                                        id="message"
                                        name="message"
                                        rows="1"
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 resize-none"
                                        placeholder="Type your message..."
                                    ></textarea>
                                </div>
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </button>
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
        // Initialize Pusher
        const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
            cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}'
        });

        let currentChatId = null;
        const chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message');
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
            messageDiv.className = `flex ${message.user_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'}`;
            
            messageDiv.innerHTML = `
                <div class="max-w-xs lg:max-w-md ${message.user_id === {{ auth()->id() }} ? 'bg-orange-500 text-white' : 'bg-gray-100'} rounded-lg px-4 py-2">
                    <p class="text-sm">${message.content}</p>
                    <span class="text-xs ${message.user_id === {{ auth()->id() }} ? 'text-orange-100' : 'text-gray-500'} block mt-1">
                        ${new Date(message.created_at).toLocaleTimeString()}
                    </span>
                </div>
            `;
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        async function loadActiveChats() {
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
                
                if (!data.success) {
                    throw new Error(data.message || 'Failed to load chats');
                }

                const chatList = document.getElementById('chat-list');
                // Clear the chat list
                while (chatList.firstChild) {
                    chatList.removeChild(chatList.firstChild);
                }
                
                // Update active chats count
                document.getElementById('active-chats-count').textContent = data.chats.length;

                if (data.chats.length === 0) {
                    chatList.innerHTML = `
                        <div class="flex justify-center items-center p-4 text-gray-500">
                            No active chats
                        </div>
                    `;
                    return;
                }

                // Sort chats by latest message time, newest first
                const sortedChats = [...data.chats].sort((a, b) => {
                    const aTime = a.messages.length > 0 ? new Date(a.messages[a.messages.length - 1].created_at).getTime() : new Date(a.created_at).getTime();
                    const bTime = b.messages.length > 0 ? new Date(b.messages[b.messages.length - 1].created_at).getTime() : new Date(b.created_at).getTime();
                    return bTime - aTime;
                });

                // Create a document fragment for better performance
                const fragment = document.createDocumentFragment();

                sortedChats.forEach(chat => {
                    const lastMessage = chat.messages[chat.messages.length - 1] || { content: 'No messages yet' };
                    const div = document.createElement('div');
                    div.className = `flex items-center p-3 cursor-pointer rounded-lg hover:bg-gray-50 ${currentChatId === chat.id ? 'bg-orange-50' : ''}`;
                    div.onclick = () => loadChat(chat.id);
                    
                    div.innerHTML = `
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-1">
                                <h3 class="text-sm font-medium text-gray-900">${chat.user.name}</h3>
                                <span class="text-xs text-gray-500">${new Date(lastMessage.created_at || chat.created_at).toLocaleTimeString()}</span>
                            </div>
                            <p class="text-sm text-gray-500 truncate">${lastMessage.content}</p>
                        </div>
                        ${chat.status === 'active' ? '<span class="w-2 h-2 bg-green-400 rounded-full"></span>' : ''}
                    `;
                    
                    fragment.appendChild(div);
                });

                // Add all chats at once
                chatList.appendChild(fragment);
            } catch (error) {
                console.error('Error loading chats:', error);
                const chatList = document.getElementById('chat-list');
                chatList.innerHTML = `
                    <div class="flex justify-center items-center p-4 text-red-500">
                        Failed to load chats. Please try again.
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
                        <h3 class="text-sm font-medium text-gray-900">${chat.user.name}</h3>
                        <span class="text-xs text-gray-500">${new Date(chat.created_at).toLocaleTimeString()}</span>
                    </div>
                    <p class="text-sm text-gray-500 truncate">${chat.messages[0]?.content || 'No messages yet'}</p>
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
                currentChatId = chatId;
                
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

                // Update chat header
                document.getElementById('chat-user-name').textContent = data.chat.user.name;
                document.getElementById('chat-user-initial').textContent = data.chat.user.name.charAt(0).toUpperCase();
                document.getElementById('chat-status').textContent = data.chat.status;
                
                // Show chat area and hide placeholder
                document.getElementById('chat-placeholder').classList.add('hidden');
                document.getElementById('chat-area').classList.remove('hidden');
                
                // Clear and load messages
                const messagesContainer = document.getElementById('chat-messages');
                messagesContainer.innerHTML = '';
                
                data.chat.messages.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = `flex ${message.user_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'}`;
                    
                    messageDiv.innerHTML = `
                        <div class="max-w-xs lg:max-w-md ${message.user_id === {{ auth()->id() }} ? 'bg-orange-500 text-white' : 'bg-gray-100'} rounded-lg px-4 py-2">
                            <p class="text-sm">${message.content}</p>
                            <span class="text-xs ${message.user_id === {{ auth()->id() }} ? 'text-orange-100' : 'text-gray-500'} block mt-1">
                                ${new Date(message.created_at).toLocaleTimeString()}
                            </span>
                        </div>
                    `;
                    
                    messagesContainer.appendChild(messageDiv);
                });
                
                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                
                // Update chat list item highlighting
                document.querySelectorAll('#chat-list > div').forEach(div => {
                    if (div.dataset.chatId === chatId) {
                        div.classList.add('bg-orange-50');
                    } else {
                        div.classList.remove('bg-orange-50');
                    }
                });
                
                // Subscribe to the chat channel
                subscribeToChat(chatId);
            } catch (error) {
                console.error('Error loading chat:', error);
                alert('Failed to load chat. Please try again.');
            }
        }

        // Handle message form submission
        document.getElementById('message-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            if (!currentChatId) {
                alert('Please select a chat first');
                return;
            }
            
            const messageInput = document.getElementById('message');
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.message || 'Failed to send message');
                }
                
                // Clear input
                messageInput.value = '';
                
                // Reload chat to show new message
                loadChat(currentChatId);
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Failed to send message. Please try again.');
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
                const response = await fetch(`/chat/${currentChatId}/end`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.message || 'Failed to end chat');
                }
                
                // Reset chat area
                document.getElementById('chat-placeholder').classList.remove('hidden');
                document.getElementById('chat-area').classList.add('hidden');
                document.getElementById('chat-messages').innerHTML = '';
                currentChatId = null;
                
                // Reload chat list
                loadActiveChats();
            } catch (error) {
                console.error('Error ending chat:', error);
                alert('Failed to end chat. Please try again.');
            }
        });

        // Load active chats initially
        loadActiveChats();
        
        // Reload active chats every 30 seconds
        setInterval(loadActiveChats, 30000);
    </script>
    @endpush
</x-app-layout>
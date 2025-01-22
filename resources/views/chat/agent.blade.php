<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Main Content -->
        <div class="flex flex-col h-screen">
            <!-- Navigation bar height offset -->
            <div class="h-16"></div>

            <!-- Chat container -->
            <div class="flex overflow-hidden flex-1 p-4 bg-gray-50">
                <!-- Chat List Sidebar -->
                <div class="flex flex-col mr-4 w-1/4 bg-white rounded-lg shadow-sm">
                    <div class="p-6 border-b">
                        <h2 class="text-2xl font-bold text-gray-800">المحادثات النشطة</h2>
                        @if(auth()->user()->role === 'user')
                            <button
                                onclick="startNewChat()"
                                class="flex justify-center items-center px-6 py-3 mt-4 space-x-2 w-full text-white bg-blue-600 rounded-lg transition-colors duration-200 hover:bg-blue-700 rtl:space-x-reverse">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>بدء محادثة جديدة</span>
                            </button>
                        @endif
                    </div>
                    <div class="overflow-y-auto flex-1 py-4">
                        @forelse($chats as $chat)
                            <div class="mx-4 mb-3">
                                <div class="p-4 bg-white rounded-xl border transition-all duration-200 cursor-pointer hover:shadow-md chat-item"
                                     data-chat-id="{{ $chat->id }}"
                                     onclick="loadChat({{ $chat->id }})">
                                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                                        <div class="flex-shrink-0">
                                            <div class="flex justify-center items-center w-12 h-12 bg-blue-100 rounded-full">
                                                <span class="text-lg font-semibold text-blue-600">
                                                    {{ substr($chat->user->name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-center mb-1">
                                                <h3 class="text-sm font-semibold text-gray-900 truncate">
                                                    @if(auth()->user()->canViewAllChats())
                                                        {{ $chat->user->name ?? 'Unknown User' }}
                                                        <span class="block mt-0.5 text-xs text-gray-500">
                                                            {{"استاذ ". $chat->agent->name ?? 'Unassigned' }}
                                                        </span>
                                                    @else
                                                        محادثة #{{ $chat->id }}
                                                    @endif
                                                </h3>
                                                <span class="px-2.5 py-1 text-xs rounded-full inline-flex items-center
                                                    {{ $chat->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    <span class="w-2 h-2 rounded-full {{ $chat->status === 'active' ? 'bg-green-500' : 'bg-gray-500' }} mr-1.5"></span>
                                                    {{ $chat->status === 'active' ? 'نشط' : 'مغلق' }}
                                                </span>
                                            </div>
                                            @if($chat->messages->last())
                                                <p class="text-sm text-gray-600 truncate">
                                                    {{ $chat->messages->last()->content }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-400">
                                                    {{ $chat->messages->last()->created_at->diffForHumans() }}
                                                </p>
                                            @else
                                                <p class="text-sm italic text-gray-500">لا توجد رسائل</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center">
                                <div class="flex justify-center items-center mx-auto mb-4 w-16 h-16 bg-gray-100 rounded-full">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <p class="text-lg text-gray-500">لا توجد محادثات نشطة</p>

                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="flex flex-col w-3/4 bg-white rounded-lg shadow-sm">
                    <!-- Empty State -->
                    <div id="chat-placeholder" class="flex flex-1 justify-center items-center bg-gray-50 rounded-lg">
                        <div class="text-center">
                            <div class="flex justify-center items-center mx-auto mb-4 w-20 h-20 bg-blue-100 rounded-full">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <p class="mb-2 text-xl text-gray-600">اختر محادثة للبدء</p>
                            <p class="text-gray-500">حدد محادثة من القائمة للبدء في الدردشة</p>
                        </div>
                    </div>

                    <!-- Chat Content -->
                    <div id="chat-area" class="flex hidden flex-col h-full">
                        <!-- Chat Header -->
                        <div class="flex-none p-6 bg-white rounded-t-lg border-b">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800" id="chat-title">جاري التحميل...</h2>
                                    <p class="text-sm text-gray-500" id="chat-status"></p>
                                </div>
                                <button onclick="endChat(currentChatId)" class="px-4 py-2 text-red-600 rounded-lg transition-colors duration-200 hover:bg-red-50">
                                    إنهاء المحادثة
                                </button>
                            </div>
                        </div>

                        <!-- Messages Container -->
                        <div id="messages" class="overflow-y-auto flex-1 p-6 space-y-4 bg-gray-50"></div>

                        <!-- Message Input Form -->
                        <div class="flex-none p-6 bg-white border-t">
                            <form id="message-form" class="flex space-x-4 rtl:space-x-reverse">
                                <input type="text"
                                    id="message-input"
                                    class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                    placeholder="اكتب رسالتك هنا...">
                                <button type="submit"
                                    class="flex items-center px-6 py-2 space-x-2 text-white bg-blue-600 rounded-lg transition-colors duration-200 hover:bg-blue-700 rtl:space-x-reverse">
                                    <span>إرسال</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
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
    <script>
        let currentChatId = null;
        let notificationsEnabled = false;

        // Request notification permission on page load
        document.addEventListener('DOMContentLoaded', function() {
            requestNotificationPermission();
        });

        // Function to request notification permission
        async function requestNotificationPermission() {
            try {
                const permission = await Notification.requestPermission();
                notificationsEnabled = permission === 'granted';

                if (!notificationsEnabled) {
                    console.log('Notifications not enabled');
                }
            } catch (error) {
                console.error('Error requesting notification permission:', error);
            }
        }

        // Function to show notification
        function showNotification(message, chatId) {
            // Don't show notification if we're in the same chat
            if (currentChatId === chatId) return;

            // Create notification sound
            const audio = new Audio('/notification.mp3');
            audio.play().catch(e => console.log('Error playing sound:', e));

            if (notificationsEnabled) {
                const notification = new Notification('رسالة جديدة', {
                    body: message.content,
                    icon: '/logo.png',
                    tag: 'chat-message',
                    requireInteraction: true
                });

                notification.onclick = function() {
                    window.focus();
                    loadChat(chatId);
                    notification.close();
                };
            }

            // Also show in-app notification
            showInAppNotification(message);
        }

        // Function to show in-app notification
        function showInAppNotification(message) {
            const notificationDiv = document.createElement('div');
            notificationDiv.className = 'fixed top-4 right-4 bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in';
            notificationDiv.innerHTML = `
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold">رسالة جديدة</p>
                        <p class="text-sm">${message.content}</p>
                    </div>
                </div>
            `;

            document.body.appendChild(notificationDiv);

            // Add slide-in animation
            setTimeout(() => {
                notificationDiv.classList.add('translate-x-0');
            }, 100);

            // Remove notification after 5 seconds
            setTimeout(() => {
                notificationDiv.classList.add('animate-slide-out');
                setTimeout(() => {
                    notificationDiv.remove();
                }, 300);
            }, 5000);
        }

        // Initialize Pusher
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        // Subscribe to the chat channel
        const chatChannel = pusher.subscribe('chat');

        // Listen for new messages
        chatChannel.bind('MessageSent', function(data) {
            if (data.message.user_id !== {{ auth()->id() }}) {
                showNotification(data.message, data.chat_id);
            }

            if (currentChatId && data.chat_id === currentChatId) {
                data.message.is_sender = data.message.user_id === {{ auth()->id() }};
                appendMessage(data.message);
            }

            updateChatPreview(data.message);
        });

        function updateChatPreview(message) {
            const chatItem = document.querySelector(`.chat-item[data-chat-id="${message.chat_id}"]`);
            if (chatItem) {
                const previewElement = chatItem.querySelector('.text-sm.text-gray-600');
                const timeElement = chatItem.querySelector('.text-xs.text-gray-400');

                if (previewElement) {
                    previewElement.textContent = message.content;
                }

                if (timeElement) {
                    timeElement.textContent = 'Just now';
                }
            }
        }

        async function sendMessage(event) {
            event.preventDefault();

            const input = document.getElementById('message-input');
            const content = input.value.trim();

            if (!content || !currentChatId) return;

            try {
                const response = await fetch(`/chat/${currentChatId}/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ content })
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.error || 'Failed to send message');
                }

                const data = await response.json();

                if (data.success) {
                    // Clear input
                    input.value = '';

                    // Add message to chat
                    appendMessage(data.message);

                    // Update chat preview
                    updateChatPreview({
                        chat_id: currentChatId,
                        content: content
                    });
                } else {
                    throw new Error(data.error || 'Failed to send message');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Failed to send message. Please try again.');
            }
        }

        // Update the form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('message-form');
            if (form) {
                form.addEventListener('submit', sendMessage);
            }
        });

        function appendMessage(message) {
            const messagesContainer = document.getElementById('messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${message.is_sender ? 'justify-end' : 'justify-start'}`;

            const time = new Date(message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const bgColor = message.is_sender ? 'bg-blue-600' : 'bg-white border';
            const textColor = message.is_sender ? 'text-white' : 'text-gray-800';

            messageDiv.innerHTML = `
                <div class="max-w-[70%] ${bgColor} ${textColor} rounded-2xl px-6 py-3 shadow-sm">
                    <p class="text-sm">${message.content}</p>
                    <p class="mt-1 text-xs ${message.is_sender ? 'text-blue-100' : 'text-gray-400'}">${time}</p>
                </div>
            `;

            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        async function loadChat(chatId) {
            try {
                currentChatId = chatId;

                // Show loading state
                document.getElementById('chat-placeholder').classList.add('hidden');
                document.getElementById('chat-area').classList.remove('hidden');
                document.getElementById('chat-area').innerHTML = `
                    <div class="flex flex-col h-full">
                        <div class="flex-none p-6 bg-white rounded-t-lg border-b">
                            <h2 class="text-xl font-bold text-gray-800">جاري التحميل...</h2>
                            <p class="text-sm text-gray-500">جاري تحميل تفاصيل المحادثة...</p>
                        </div>
                        <div class="overflow-y-auto flex-1 p-6 bg-gray-50">
                            <div class="py-4 text-center">جاري تحميل الرسائل...</div>
                        </div>
                        <div class="flex-none p-6 bg-white border-t">
                            <div class="flex space-x-4 rtl:space-x-reverse">
                                <input type="text" disabled class="flex-1 rounded-lg border-gray-300" placeholder="جاري التحميل...">
                                <button disabled class="px-6 py-2 text-white bg-gray-400 rounded-lg">إرسال</button>
                            </div>
                        </div>
                    </div>
                `;

                const response = await fetch(`/chat/${chatId}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error || 'Failed to load chat');
                }

                const chat = data.chat;

                // Update chat area
                document.getElementById('chat-area').innerHTML = `
                    <div class="flex flex-col h-full">
                        <div class="flex-none p-6 bg-white rounded-t-lg border-b">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">محادثة مع ${chat.user.name}</h2>
                                    <p class="text-sm text-gray-500">الحالة: ${chat.status}</p>
                                </div>
                                ${chat.status === 'active' ? `
                                    <button id="end-chat-btn" class="px-4 py-2 text-red-600 rounded-lg transition-colors duration-200 hover:bg-red-50">
                                        إنهاء المحادثة
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                        <div id="messages" class="overflow-y-auto flex-1 p-6 space-y-4 bg-gray-50"></div>
                        ${chat.status === 'active' ? `
                            <div class="flex-none p-6 bg-white border-t">
                                <form id="message-form" class="flex space-x-4 rtl:space-x-reverse">
                                    <input type="text"
                                        id="message-input"
                                        class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                        placeholder="اكتب رسالتك هنا...">
                                    <button type="submit"
                                        class="flex items-center px-6 py-2 space-x-2 text-white bg-blue-600 rounded-lg transition-colors duration-200 hover:bg-blue-700 rtl:space-x-reverse">
                                        <span>إرسال</span>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        ` : ''}
                    </div>
                `;

                // Display messages
                const messagesContainer = document.getElementById('messages');
                messagesContainer.innerHTML = '';

                if (chat.messages && chat.messages.length > 0) {
                    chat.messages.forEach(message => {
                        appendMessage(message);
                    });
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                } else {
                    messagesContainer.innerHTML = '<div class="py-4 text-center text-gray-500">لا توجد رسائل</div>';
                }

                // Update active state in chat list
                document.querySelectorAll('.chat-item').forEach(item => {
                    if (item.dataset.chatId == chatId) {
                        item.classList.add('bg-blue-50');
                    } else {
                        item.classList.remove('bg-blue-50');
                    }
                });

                // Setup event listeners
                setupEventListeners(chat);

            } catch (error) {
                console.error('Error loading chat:', error);
                document.getElementById('chat-area').innerHTML = `
                    <div class="flex flex-col h-full">
                        <div class="flex flex-1 justify-center items-center">
                            <div class="text-center text-red-500">
                                <p class="text-xl">Failed to load chat</p>
                                <p class="mt-2">Please try again</p>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        function setupEventListeners(chat) {
            const form = document.getElementById('message-form');
            if (form) {
                form.onsubmit = async (e) => {
                    e.preventDefault();
                    const input = document.getElementById('message-input');
                    const content = input.value.trim();
                    if (!content) return;

                    try {
                        const response = await fetch(`/chat/${currentChatId}/messages`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ content })
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        if (data.success) {
                            input.value = '';
                            appendMessage({
                                id: data.message.id,
                                content: data.message.content,
                                user_id: data.message.user_id,
                                created_at: data.message.created_at,
                                is_sender: true
                            });
                        }
                    } catch (error) {
                        console.error('Error sending message:', error);
                        alert('Failed to send message. Please try again.');
                    }
                };
            }

            const endChatBtn = document.getElementById('end-chat-btn');
            if (endChatBtn) {
                endChatBtn.onclick = async () => {
                    if (!confirm('Are you sure you want to end this chat?')) return;

                    try {
                        const response = await fetch(`/chat/${currentChatId}/end`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        if (data.success) {
                            loadChat(currentChatId); // Reload chat to show updated status
                        }
                    } catch (error) {
                        console.error('Error ending chat:', error);
                        alert('Failed to end chat. Please try again.');
                    }
                };
            }
        }

        async function startNewChat() {
            try {
                const response = await fetch('{{ route("chat.start") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (!data.success) {
                    alert(data.message || 'Failed to start chat');
                    return;
                }

                // Reload the page to show the new chat
                window.location.reload();

            } catch (error) {
                console.error('Error starting chat:', error);
                alert('Failed to start chat. Please try again.');
            }
        }
    </script>
    @endpush
</x-app-layout>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }

    .animate-slide-out {
        animation: slideOut 0.3s ease-out;
    }
</style>

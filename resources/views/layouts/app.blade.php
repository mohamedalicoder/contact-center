<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <!-- Pusher -->
        <script>
            window.pusherKey = '{{ config('broadcasting.connections.pusher.key') }}';
            window.pusherCluster = '{{ config('broadcasting.connections.pusher.options.cluster') }}';
        </script>
        <!-- Chat Script -->
        <script src="{{ asset('js/chat.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            <!-- Page Content -->
            <main>
                {{ $slot ?? '' }}
            </main>

            @stack('modals')

            <!-- Chat Button -->
            <div id="chat-button" class="fixed bottom-4 right-4 z-50">
                <button onclick="toggleChat()" class="bg-blue-500 hover:bg-blue-600 text-white rounded-full p-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </button>
            </div>

            <!-- Chat Popup -->
            <div id="chat-popup" class="fixed bottom-20 right-4 z-50 hidden">
                <div class="bg-white rounded-lg shadow-xl w-96 h-[600px] flex flex-col">
                    <div class="p-4 border-b flex justify-between items-center bg-blue-500 text-white rounded-t-lg">
                        <h3 class="text-lg font-semibold">Live Chat</h3>
                        <button onclick="toggleChat()" class="text-white hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1">
                        <iframe id="chat-frame" src="" class="w-full h-full border-0"></iframe>
                    </div>
                </div>
            </div>

            <script>
                let chatInitialized = false;

                function toggleChat() {
                    const popup = document.getElementById('chat-popup');
                    const frame = document.getElementById('chat-frame');
                    
                    if (popup.classList.contains('hidden')) {
                        if (!chatInitialized) {
                            @auth
                                const activeChat = {{ \App\Models\Chat::where('user_id', auth()->id())->where('status', 'active')->first()?->id ?? 'null' }};
                                frame.src = activeChat ? '{{ url('/chat') }}/' + activeChat : '{{ route('chat.create') }}';
                            @else
                                frame.src = '{{ route('chat.create') }}';
                            @endauth
                            chatInitialized = true;
                        }
                        popup.classList.remove('hidden');
                    } else {
                        popup.classList.add('hidden');
                    }
                }
            </script>

            @vite(['resources/css/app.css', 'resources/js/app.js'])
            @stack('scripts')
        </div>
    </body>
</html>